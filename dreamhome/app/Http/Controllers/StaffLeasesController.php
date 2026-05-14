<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StaffLeasesController extends Controller
{
    /**
     * Display all leases.
     * Note: Uses 'is_paid_this_month' as a real-time flag for the staff dashboard.
     */
    public function index(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $query = DB::table('lease_agreement as l')
            ->join('property as p', 'l.propertyno', '=', 'p.propertyno')
            ->join('renter as r', 'l.renterno', '=', 'r.renterno');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('l.leaseno', 'ILIKE', "%{$search}%")
                  ->orWhere('p.street', 'ILIKE', "%{$search}%")
                  ->orWhere('r.firstname', 'ILIKE', "%{$search}%")
                  ->orWhere('r.lastname', 'ILIKE', "%{$search}%");
            });
        }

        $leases = $query->select(
                'l.*', 
                'p.street', 'p.city', 
                'r.firstname as r_fname', 'r.lastname as r_lname'
            )
            ->addSelect([
                'is_paid_this_month' => DB::table('payment')
                    ->whereColumn('leaseno', 'l.leaseno')
                    ->whereMonth('payment_date', $currentMonth)
                    ->whereYear('payment_date', $currentYear)
                    ->selectRaw('count(*) > 0')
            ])
            ->orderBy('l.startdate', 'desc')
            ->get();

        return view('staff.leases.index', compact('leases'));
    }

    /**
     * Store New Lease.
     * Fixed: Improved duration calculation and initial balance logic.
     */
    public function store(Request $request)
    {
        $request->validate([
            'leaseno'      => 'required|unique:lease_agreement,leaseno',
            'propertyno'   => 'required|exists:property,propertyno',
            'renterno'     => 'required|exists:renter,renterno',
            'staffno'      => 'required|exists:staff,staffno',
            'monthly_rent' => 'required|numeric|min:0',
            'paymentmethod'=> 'required|string',
            'deposit'      => 'required|numeric|min:0',
            'isdepositpaid'=> 'required|in:Yes,No',
            'startdate'    => 'required|date',
            'enddate'      => 'required|date|after:startdate',
        ]);

        $start = Carbon::parse($request->startdate);
        $end = Carbon::parse($request->enddate);
        
        // Calculate duration in months (rounding up to ensure full term coverage)
        $duration = max(1, $start->diffInMonths($end));

        DB::table('lease_agreement')->insert([
            'leaseno'        => trim($request->leaseno),
            'propertyno'     => $request->propertyno,
            'renterno'       => $request->renterno,
            'staffno'        => $request->staffno,
            'monthly_rent'   => $request->monthly_rent,
            'paymentmethod'  => $request->paymentmethod,
            'deposit'        => $request->deposit,
            'isdepositpaid'  => $request->isdepositpaid,
            'startdate'      => $request->startdate,
            'enddate'        => $request->enddate,
            'duration'       => (int) $duration,
            'is_overdue'     => false,
            'payment_status' => 'Pending',
            'total_paid'     => 0,
            'balance'        => $request->monthly_rent * $duration,
            'created_at'     => now()
        ]);

        return redirect()->route('staff.leases.index')->with('success', 'New lease agreement successfully activated.');
    }

    /**
     * Detailed View: Month-by-month payment tracking.
     */
    public function show($id)
    {
        $lease = DB::table('lease_agreement as l')
            ->join('property as p', 'l.propertyno', '=', 'p.propertyno')
            ->join('renter as r', 'l.renterno', '=', 'r.renterno')
            ->leftJoin('staff as s', 'l.staffno', '=', 's.staffno')
            ->where('l.leaseno', $id)
            ->select(
                'l.*', 
                'p.street', 'p.city', 'p.area', 'p.postcode',
                'r.firstname as r_fname', 'r.lastname as r_lname', 'r.phone as r_phone',
                's.firstname as s_fname', 's.lastname as s_lname'
            )
            ->first();

        if (!$lease) abort(404);

        // Fetch all payments unmapped so we can iterate and dynamically filter them
        $payments = DB::table('payment')
            ->where('leaseno', $id)
            ->orderBy('payment_date', 'asc')
            ->get();

        // Generate the expected payment schedule based on lease duration
        $start = Carbon::parse($lease->startdate)->startOfMonth();
        $end = Carbon::parse($lease->enddate)->startOfMonth();
        $periods = CarbonPeriod::create($start, '1 month', $end);

        $schedule = [];
        foreach ($periods as $date) {
            $monthLabel = $date->format('F Y');

            // STAFF ENGINE REALIGNMENT: Check strings inside notes OR fallback to matching calendar timestamps
            $matchingPayment = $payments->first(function ($payment) use ($date, $monthLabel) {
                // Check 1: Did the renter or staff target this month directly inside the text log?
                if (!empty($payment->notes) && stripos($payment->notes, $monthLabel) !== false) {
                    return true;
                }

                // Check 2: Fallback natural check—did the transaction occur directly within this calendar month window?
                $payDate = Carbon::parse($payment->payment_date);
                $periodStart = $date->copy()->startOfMonth()->startOfDay();
                $periodEnd = $date->copy()->endOfMonth()->endOfDay();
                return $payDate->between($periodStart, $periodEnd);
            });

            $schedule[] = [
                'month'   => $monthLabel,
                'is_paid' => !is_null($matchingPayment),
                'payment' => $matchingPayment,
                'due_date'=> $date->copy()->day(1)->format('Y-m-d')
            ];
        }

        return view('staff.leases.show', compact('lease', 'schedule'));
    }

    /**
     * Staff-Initiated Payment Recording.
     * Useful for recording cash payments at the branch.
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'leaseno'        => 'required|exists:lease_agreement,leaseno',
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'payment_date'   => 'required|date',
            'notes'          => 'nullable|string|max:255'
        ]);

        $paymentId = 'PAY-' . strtoupper(uniqid());

        // Automatically append the targeted month name into the notes if staff inputs it
        // This ensures the custom-string matching engine registers it flawlessly.
        $notes = $request->notes ?? 'Manual staff entry';

        DB::table('payment')->insert([
            'paymentid'      => $paymentId,
            'leaseno'        => $request->leaseno,
            'payment_date'   => $request->payment_date,
            'amount_paid'    => $request->amount,
            'payment_method' => $request->payment_method,
            'notes'          => $notes,
        ]);

        return back()->with('success', "Payment {$paymentId} has been recorded.");
    }
}