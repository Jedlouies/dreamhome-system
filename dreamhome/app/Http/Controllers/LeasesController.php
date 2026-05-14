<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LeasesController extends Controller
{
    // ===== SHARED LEASE FETCH =====
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

    private function fetchPayments($leaseno)
    {
        return DB::table('payment')
            ->where('leaseno', $leaseno)
            ->orderByDesc('payment_date')
            ->orderByDesc('paymentid')
            ->get();
    }

    private function fetchBranch($branchno = 'B001')
    {
        return DB::table('branch')->where('branchno', $branchno)->first();
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

    // ===== INDEX =====
    public function index()
    {
        $user = Auth::user();

        if (!$user->renterno) {
            return view('leases', [
                'lease'    => null,
                'payments' => collect(),
                'progress' => 0,
                'branch'   => null,
            ]);
        }

        $lease    = $this->fetchLease($user->renterno);
        $payments = $lease ? $this->fetchPayments($lease->leaseno) : collect();
        $progress = $lease ? $this->calcProgress($lease) : 0;
        $branch   = $this->fetchBranch();

        return view('leases', compact('lease', 'payments', 'progress', 'branch'));
    }

    // ===== DOWNLOAD PDF =====
    public function downloadPdf()
    {
        $user = Auth::user();

        if (!$user->renterno) {
            return redirect()->route('leases');
        }

        $lease    = $this->fetchLease($user->renterno);
        $payments = $lease ? $this->fetchPayments($lease->leaseno) : collect();
        $progress = $lease ? $this->calcProgress($lease) : 0;

        $pdf = Pdf::loadView('lease-pdf', compact('lease', 'payments', 'progress'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('lease-' . $lease->leaseno . '.pdf');
    }

    // ===== PROCESS PAYMENT =====
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

        if (!$lease) {
            return back()->with('error', 'No active lease found.');
        }

        if ($lease->balance <= 0) {
            return back()->with('error', 'Your lease is already fully paid.');
        }

        $months     = $request->payment_type === 'advance' ? (int) $request->months : 1;
        $amountPaid = $lease->monthly_rent * $months;

        // Cap at remaining balance
        $amountPaid = min($amountPaid, $lease->balance);

        $paymentId = 'PAY' . strtoupper(uniqid());
        $notes     = $request->notes
            ?: ($months === 1
                ? 'Monthly rent payment'
                : "Advance payment for {$months} month(s)");

        if ($request->payment_method !== 'Cash' && $request->reference_no) {
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

        $label = $months === 1 ? 'Monthly payment' : "Advance payment ({$months} months)";
        return back()->with('payment_success',
            "✓ {$label} of ₱" . number_format($amountPaid, 2) . " recorded successfully via {$request->payment_method}!"
        );
    }

    // ===== REQUEST RENEWAL =====
    public function requestRenewal(Request $request)
    {
        $request->validate([
            'reason'  => 'required|string',
            'message' => 'nullable|string|max:500',
        ]);

        $user  = Auth::user();
        $lease = $this->fetchLease($user->renterno);

        if (!$lease) {
            return back()->with('error', 'No active lease found.');
        }

        $requestId = 'RR' . time();

        DB::table('renewal_request')->insert([
            'requestid'  => $requestId,
            'leaseno'    => $lease->leaseno,
            'renterno'   => $user->renterno,
            'reason'     => $request->reason,
            'message'    => $request->message,
            'status'     => 'Pending',
            'created_at' => now(),
        ]);

        return back()->with('renewal_success', 'Your renewal request has been submitted successfully!');
    }

    // ===== CONTACT SUPPORT =====
    public function contactSupport(Request $request)
    {
        $request->validate([
            'issue_type' => 'required|string',
            'message'    => 'required|string|max:500',
        ]);

        $user  = Auth::user();
        $lease = $this->fetchLease($user->renterno);

        $ticketId = 'ST' . time();

        DB::table('support_ticket')->insert([
            'ticketid'   => $ticketId,
            'renterno'   => $user->renterno,
            'leaseno'    => $lease?->leaseno,
            'issue_type' => $request->issue_type,
            'message'    => $request->message,
            'status'     => 'Open',
            'created_at' => now(),
        ]);

        return back()->with('support_success', 'Your support ticket has been submitted! We\'ll get back to you shortly.');
    }
}