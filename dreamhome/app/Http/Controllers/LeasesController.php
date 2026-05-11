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

        // Generate unique request ID
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