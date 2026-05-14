<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LeasesController extends Controller
{
    // ===== SHARED DATA FETCHING =====

    private function fetchLease($renterno)
    {
        return DB::table('lease_agreement as la')
            ->join('property as p', 'la.propertyno', '=', 'p.propertyno')
            ->join('staff as s',    'la.staffno',    '=', 's.staffno')
            ->join('renter as r',   'la.renterno',   '=', 'r.renterno')
            ->where('la.renterno', $renterno)
            ->select(
                'la.leaseno', 'la.propertyno', 'la.renterno', 'la.staffno',
                'la.monthly_rent', 'la.paymentmethod', 'la.deposit', 'la.isdepositpaid',
                'la.startdate', 'la.enddate', 'la.duration', 'la.total_paid',
                'la.balance', 'la.payment_status', 'la.is_overdue',
                'p.street', 'p.area', 'p.city', 'p.postcode', 'p.property_type', 'p.no_of_rooms',
                DB::raw("s.firstname || ' ' || s.lastname as staff_name"),
                DB::raw("r.firstname || ' ' || r.lastname as renter_name")
            )
            ->orderByDesc('la.startdate')
            ->first();
    }

    private function fetchPayments($leaseno)
    {
        return DB::table('payment')
            ->where('leaseno', $leaseno)
            ->orderByDesc('payment_date')
            ->orderByDesc('paymentid')
            ->get();
    }

    private function calcProgress($lease)
    {
        $start   = strtotime($lease->startdate);
        $end     = strtotime($lease->enddate);
        $today   = time();
        $total   = $end - $start;
        $elapsed = max(0, min($today - $start, $total));
        return $total > 0 ? round(($elapsed / $total) * 100) : 0;
    }

    // ===== MAIN DASHBOARD =====

    public function index()
    {
        $user = Auth::user();

        if (!$user->renterno) {
            return view('leases', [
                'lease'            => null,
                'payments'         => collect(),
                'progress'         => 0,
                'branch'           => DB::table('branch')->where('branchno', 'B001')->first(),
                'next_due_date'    => 'N/A',
                'overdue_months'   => [],
                'hasPaidThisMonth' => false,
                'unpaid_months'    => []
            ]);
        }

        $lease    = $this->fetchLease($user->renterno);
        $payments = $lease ? $this->fetchPayments($lease->leaseno) : collect();
        $progress = $lease ? $this->calcProgress($lease) : 0;
        $branch   = DB::table('branch')->where('branchno', 'B001')->first();

        $next_due_date = 'N/A';
        $overdue_months = [];
        $unpaid_months = [];
        $hasPaidThisMonth = false;

        if ($lease) {
            if ($lease->balance <= 0 || $lease->payment_status === 'PAID') {
                $next_due_date = 'Fully Paid';
            } else {
                $leaseStart = Carbon::parse($lease->startdate)->startOfDay();
                $leaseEnd   = Carbon::parse($lease->enddate)->endOfDay();
                $today      = Carbon::now()->startOfDay();
                $currentMonthLabel = Carbon::now()->format('F Y');

                $currentPeriod = $leaseStart->copy();
                $firstUnpaidDate = null;

                // CHRONOLOGICAL LOOK-AHEAD SEQUENCER ENGINE
                while ($currentPeriod->isBefore($leaseEnd)) {
                    $monthLabel = $currentPeriod->format('F Y');

                    $isPaidForPeriod = $payments->contains(function ($payment) use ($currentPeriod, $monthLabel) {
                        if (!empty($payment->notes) && stripos($payment->notes, $monthLabel) !== false) {
                            return true;
                        }

                        $payDate     = Carbon::parse($payment->payment_date);
                        $periodStart = $currentPeriod->copy()->startOfMonth()->startOfDay();
                        $periodEnd   = $currentPeriod->copy()->endOfMonth()->endOfDay();
                        return $payDate->between($periodStart, $periodEnd);
                    });

                    // Track explicitly if this current calendar month has been marked paid
                    if ($monthLabel === $currentMonthLabel && $isPaidForPeriod) {
                        $hasPaidThisMonth = true;
                    }

                    if (!$isPaidForPeriod) {
                        $unpaid_months[] = $monthLabel;

                        if (is_null($firstUnpaidDate)) {
                            $firstUnpaidDate = $currentPeriod->copy()->startOfMonth();
                        }

                        if ($currentPeriod->isBefore($today)) {
                            $overdue_months[] = $monthLabel;
                        }
                    }

                    $currentPeriod->addMonth();
                }

                if ($firstUnpaidDate) {
                    $next_due_date = $firstUnpaidDate->format('M d, Y');
                } else {
                    $next_due_date = 'Term Completed';
                }
            }
        }

        return view('leases', compact('lease', 'payments', 'progress', 'branch', 'next_due_date', 'overdue_months', 'hasPaidThisMonth', 'unpaid_months'));
    }

    // ===== ACTIONS & TRANSACTIONS =====

    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_type'   => 'required|in:this_month,advance',
            'months'         => 'required_if:payment_type,advance|integer|min:1|max:12',
            'payment_method' => 'required|string',
            'reference_no'   => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:300',
        ]);

        $user  = Auth::user();
        $lease = $this->fetchLease($user->renterno);

        if (!$lease || $lease->balance <= 0) {
            return back()->with('error', 'Payment cannot be processed for this lease.');
        }

        $months     = $request->payment_type === 'advance' ? (int) $request->months : 1;
        $amountPaid = min($lease->monthly_rent * $months, $lease->balance);

        $paymentId = 'PAY' . strtoupper(uniqid());
        
        $notes = $request->notes ?: ($months === 1 ? 'Standard monthly rent' : "Advance payment for {$months} months");
        
        if ($request->reference_no && $request->payment_method !== 'Cash') {
            $notes .= ' | Ref: ' . $request->reference_no;
        }

        DB::statement("CALL insert_payment(
            CAST(:paymentid AS TEXT),
            CAST(:leaseno AS TEXT),
            CAST(:amount_paid AS NUMERIC),
            CAST(:payment_method AS TEXT),
            CAST(:notes AS TEXT)
        )", [
            'paymentid'      => $paymentId,
            'leaseno'        => $lease->leaseno,
            'amount_paid'    => $amountPaid,
            'payment_method' => $request->payment_method,
            'notes'          => $notes,
        ]);

        return back()->with('payment_success', "Payment of ₱" . number_format($amountPaid, 2) . " has been successfully recorded.");
    }

    public function downloadPdf()
    {
        $user = Auth::user();
        if (!$user->renterno) return redirect()->route('leases');

        $lease    = $this->fetchLease($user->renterno);
        $payments = $lease ? $this->fetchPayments($lease->leaseno) : collect();
        $progress = $lease ? $this->calcProgress($lease) : 0;

        $pdf = Pdf::loadView('lease-pdf', compact('lease', 'payments', 'progress'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Lease_Agreement_' . $lease->leaseno . '.pdf');
    }

    public function requestRenewal(Request $request)
    {
        $request->validate(['reason' => 'required|string', 'message' => 'nullable|string|max:500']);
        $user = Auth::user();
        $lease = $this->fetchLease($user->renterno);

        DB::table('renewal_request')->insert([
            'requestid'  => 'RR' . time(),
            'leaseno'    => $lease->leaseno,
            'renterno'   => $user->renterno,
            'reason'     => $request->reason,
            'message'    => $request->message,
            'status'     => 'Pending',
            'created_at' => now(),
        ]);

        return back()->with('renewal_success', 'Your renewal request is now pending review.');
    }

    public function contactSupport(Request $request)
    {
        $request->validate(['issue_type' => 'required|string', 'message' => 'required|string|max:500']);
        $user = Auth::user();
        $lease = $this->fetchLease($user->renterno);

        DB::table('support_ticket')->insert([
            'ticketid'   => 'ST' . time(),
            'renterno'   => $user->renterno,
            'leaseno'    => $lease?->leaseno,
            'issue_type' => $request->issue_type,
            'message'    => $request->message,
            'status'     => 'Open',
            'created_at' => now(),
        ]);

        return back()->with('support_success', 'Support ticket submitted. We will contact you shortly.');
    }
}