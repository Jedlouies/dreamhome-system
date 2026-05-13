<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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
                'la.leaseno', 'la.propertyno', 'la.renterno', 'la.staffno',
                'la.monthly_rent', 'la.paymentmethod', 'la.deposit',
                'la.isdepositpaid', 'la.startdate', 'la.enddate', 'la.duration',
                'la.total_paid', 'la.balance', 'la.payment_status', 'la.is_overdue',
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

    // ===== INDEX =====
    public function index()
    {
    

        $user = Auth::user();
        if (!$user->renterno) return view('leases', ['lease' => null]);

        $lease = $this->fetchLease($user->renterno);
        if (!$lease) return view('leases', ['lease' => null]);

        $payments = $this->fetchPayments($lease->leaseno);
        $progress = $this->calcProgress($lease);

        // Map payments by Month-Year for the baseline tracking
        $paymentMap = $payments->mapWithKeys(function ($p) {
            return [Carbon::parse($p->payment_date)->format('F Y') => $p];
        });

        // Generate the monthly baseline schedule
        $start = Carbon::parse($lease->startdate)->startOfMonth();
        $end = Carbon::parse($lease->enddate)->startOfMonth();
        $periods = CarbonPeriod::create($start, '1 month', $end);

        $schedule = [];
        foreach ($periods as $date) {
            $monthKey = $date->format('F Y');
            $schedule[] = [
                'month' => $monthKey,
                'is_paid' => isset($paymentMap[$monthKey]),
                'payment' => $paymentMap[$monthKey] ?? null,
            ];
        }

        return view('leases', compact('lease', 'schedule', 'payments', 'progress'));
    }

    // ===== PROCESS ADVANCE PAYMENT =====
    public function processPayment(Request $request)
    {
        $request->validate([
            'leaseno' => 'required|exists:lease_agreement,leaseno',
            'months_to_pay' => 'required|integer|min:1|max:12',
            'payment_method' => 'required|string'
        ]);

        $lease = DB::table('lease_agreement')->where('leaseno', $request->leaseno)->first();
        $numMonths = (int) $request->months_to_pay;

        // Find the next unpaid month
        $latestPayment = DB::table('payment')
            ->where('leaseno', $request->leaseno)
            ->orderBy('payment_date', 'desc')
            ->first();

        $startDate = $latestPayment 
            ? Carbon::parse($latestPayment->payment_date)->addMonth()->startOfMonth()
            : Carbon::parse($lease->startdate)->startOfMonth();

        DB::transaction(function () use ($request, $numMonths, $lease, $startDate) {
            for ($i = 0; $i < $numMonths; $i++) {
                $targetMonth = $startDate->copy()->addMonths($i);
                
                // Fixed: Generating manual Payment ID to avoid Not Null Violation
                $paymentId = 'PAY-' . strtoupper(bin2hex(random_bytes(3))) . '-' . time() . $i;

                DB::table('payment')->insert([
                    'paymentid'      => $paymentId,
                    'leaseno'        => $request->leaseno,
                    'payment_date'   => $targetMonth,
                    'amount_paid'    => $lease->monthly_rent,
                    'payment_method' => $request->payment_method,
                    'notes'          => "Advance payment for " . $targetMonth->format('F Y'),
                ]);
            }
        });

        return back()->with('success', "Rent payment for $numMonths month(s) processed successfully!");
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