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

    /**
     * Retrieves the primary lease record with property, staff, and renter details.
     */
    private function fetchLease($renterno)
    {
        return DB::table('lease_agreement as la')
            ->join('property as p', 'la.propertyno', '=', 'p.propertyno')
            ->join('staff as s',    'la.staffno',    '=', 's.staffno')
            ->join('renter as r',   'la.renterno',   '=', 'r.renterno')
            ->where('la.renterno', $renterno)
            ->select(
                'la.leaseno',
                'la.propertyno',
                'la.renterno',
                'la.staffno',
                'la.monthly_rent',
                'la.paymentmethod',
                'la.deposit',
                'la.isdepositpaid',
                'la.startdate',
                'la.enddate',
                'la.duration',
                'la.total_paid',
                'la.balance',
                'la.payment_status',
                'la.is_overdue',
                'p.street',
                'p.area',
                'p.city',
                'p.postcode',
                'p.property_type',
                'p.no_of_rooms',
                DB::raw("s.firstname || ' ' || s.lastname as staff_name"),
                DB::raw("r.firstname || ' ' || r.lastname as renter_name")
            )
            ->orderByDesc('la.startdate')
            ->first();
    }

    /**
     * Retrieves the history of payments for a specific lease.
     */
    private function fetchPayments($leaseno)
    {
        return DB::table('payment')
            ->where('leaseno', $leaseno)
            ->orderByDesc('payment_date')
            ->orderByDesc('paymentid')
            ->get();
    }

    /**
     * Calculates the percentage of time elapsed in the lease term.
     */
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

        // Safety check if user is not associated with a renter profile
        if (!$user->renterno) {
            return view('leases', [
                'lease'          => null,
                'payments'       => collect(),
                'progress'       => 0,
                'branch'         => DB::table('branch')->where('branchno', 'B001')->first(),
                'next_due_date'  => 'N/A',
                'overdue_months' => []
            ]);
        }

        $lease    = $this->fetchLease($user->renterno);
        $payments = $lease ? $this->fetchPayments($lease->leaseno) : collect();
        $progress = $lease ? $this->calcProgress($lease) : 0;
        $branch   = DB::table('branch')->where('branchno', 'B001')->first();

        // CALCULATION: Identify the next required payment month & pinpoint overdue months
        $next_due_date = 'N/A';
        $overdue_months = [];

        if ($lease) {
            if ($lease->balance <= 0 || $lease->payment_status === 'PAID') {
                $next_due_date = 'Fully Paid';
            } else {
                $latestPayment = $payments->first();

                if ($latestPayment) {
                    // Advance 1 month from the last recorded payment
                    $next_due_date = Carbon::parse($latestPayment->payment_date)
                        ->addMonth()
                        ->startOfMonth()
                        ->format('M d, Y');
                } else {
                    // If no payments exist, the first month of the lease is due
                    $next_due_date = Carbon::parse($lease->startdate)
                        ->startOfMonth()
                        ->format('M d, Y');
                }

                // Check if the calculated due date exceeds the lease end date
                if (Carbon::parse($next_due_date)->gt(Carbon::parse($lease->enddate))) {
                    $next_due_date = 'Term Completed';
                }

                // ===== ADDED: PAST MONTHS OVERDUE VERIFICATION ENGINE =====
                if ($lease->is_overdue || $lease->balance > 0) {
                    $currentPeriod = Carbon::parse($lease->startdate)->startOfDay();
                    $today         = Carbon::now()->startOfDay();

                    // Loop monthly from lease start date up until today's current month
                    while ($currentPeriod->isBefore($today)) {
                        $periodStart = $currentPeriod->copy()->startOfMonth()->startOfDay();
                        $periodEnd   = $currentPeriod->copy()->endOfMonth()->endOfDay();

                        // Look through successful payments to find any that match this month cycle window
                        $isPaidForPeriod = $payments->contains(function ($payment) use ($periodStart, $periodEnd) {
                            $payDate = Carbon::parse($payment->payment_date);
                            return $payDate->between($periodStart, $periodEnd);
                        });

                        // If no matching payment was assigned to this monthly cycle, tag it as overdue
                        if (!$isPaidForPeriod) {
                            $overdue_months[] = $currentPeriod->format('F Y');
                        }

                        // Progress engine step to next billing cycle month
                        $currentPeriod->addMonth();
                    }
                }
            }
        }

        return view('leases', compact('lease', 'payments', 'progress', 'branch', 'next_due_date', 'overdue_months'));
    }

    // ===== ACTIONS & TRANSACTIONS =====

    /**
     * Processes a new payment using a stored procedure.
     */
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
        $notes     = $request->notes ?: ($months === 1 ? 'Standard monthly rent' : "Advance payment for {$months} months");
        
        if ($request->reference_no && $request->payment_method !== 'Cash') {
            $notes .= ' | Ref: ' . $request->reference_no;
        }

        // Execute procedural database update
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

    /**
     * Generates a PDF version of the lease agreement.
     */
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

    /**
     * Submits a renewal request to the database.
     */
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

    /**
     * Creates a support ticket for maintenance or billing issues.
     */
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