<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StaffLeasesController extends Controller
{
    /**
     * Display all leases with dynamic payment status.
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
                $q->where('l.leaseno', 'LIKE', "%{$search}%")
                  ->orWhere('p.street', 'LIKE', "%{$search}%")
                  ->orWhere('r.firstname', 'LIKE', "%{$search}%");
            });
        }

        $leases = $query->select('l.*', 'p.street', 'p.city', 'r.firstname as r_fname', 'r.lastname as r_lname')
            ->addSelect([
                'is_paid_this_month' => DB::table('payment')
                    ->whereColumn('leaseno', 'l.leaseno')
                    ->whereMonth('payment_date', $currentMonth)
                    ->whereYear('payment_date', $currentYear)
                    ->selectRaw('count(*) > 0')
            ])
            ->orderBy('l.startdate', 'desc')->get();

        return view('staff.leases.index', compact('leases'));
    }

    /**
     * Detailed Month-by-Month Baseline View.
     */
    public function show($id)
    {
        $lease = DB::table('lease_agreement as l')
            ->join('property as p', 'l.propertyno', '=', 'p.propertyno')
            ->join('renter as r', 'l.renterno', '=', 'r.renterno')
            ->leftJoin('staff as s', 'l.staffno', '=', 's.staffno')
            ->where('l.leaseno', $id)
            ->select('l.*', 'p.street', 'p.city', 'p.area', 'r.firstname as r_fname', 'r.lastname as r_lname', 's.firstname as s_fname')
            ->first();

        if (!$lease) abort(404);

        $payments = DB::table('payment')
            ->where('leaseno', $id)
            ->get()
            ->mapWithKeys(function ($p) {
                return [Carbon::parse($p->payment_date)->format('F Y') => $p];
            });

        $start = Carbon::parse($lease->startdate)->startOfMonth();
        $end = Carbon::parse($lease->enddate)->startOfMonth();
        $periods = CarbonPeriod::create($start, '1 month', $end);

        $schedule = [];
        foreach ($periods as $date) {
            $monthKey = $date->format('F Y');
            $schedule[] = [
                'month' => $monthKey,
                'is_paid' => isset($payments[$monthKey]),
                'payment' => $payments[$monthKey] ?? null,
            ];
        }

        return view('staff.leases.show', compact('lease', 'schedule'));
    }

    /**
     * Store New Lease (Fixed duration integer error and missing columns).
     */
    public function store(Request $request)
    {
        $request->validate([
            'leaseno' => 'required|unique:lease_agreement,leaseno',
            'propertyno' => 'required|exists:property,propertyno',
            'renterno' => 'required|exists:renter,renterno',
            'staffno' => 'required|exists:staff,staffno',
            'monthly_rent' => 'required|numeric',
            'paymentmethod' => 'required|string',
            'deposit' => 'required|numeric',
            'isdepositpaid' => 'required|string',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after:startdate',
        ]);

        $start = Carbon::parse($request->startdate);
        $end = Carbon::parse($request->enddate);
        $duration = (int) $start->diffInMonths($end);

        DB::table('lease_agreement')->insert([
            'leaseno' => $request->leaseno,
            'propertyno' => $request->propertyno,
            'renterno' => $request->renterno,
            'staffno' => $request->staffno,
            'monthly_rent' => $request->monthly_rent,
            'paymentmethod' => $request->paymentmethod,
            'deposit' => $request->deposit,
            'isdepositpaid' => $request->isdepositpaid,
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
            'duration' => $duration,
            'is_overdue' => false,
            'payment_status' => 'Pending',
            'total_paid' => 0,
            'balance' => $request->monthly_rent * $duration
        ]);

        return redirect()->route('staff.leases.index')->with('success', 'Lease created successfully!');
    }

    /**
     * Process Advance Payment (Fixed paymentid Not Null violation).
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'leaseno' => 'required|exists:lease_agreement,leaseno',
            'months_to_pay' => 'required|integer|min:1',
            'payment_method' => 'required|string'
        ]);

        $lease = DB::table('lease_agreement')->where('leaseno', $request->leaseno)->first();
        $numMonths = (int) $request->months_to_pay;

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
                
                // GENERATE MANUAL PAYMENT ID to fix the Not Null Violation
                $paymentId = 'PAY-' . strtoupper(bin2hex(random_bytes(3))) . '-' . time() . $i;

                DB::table('payment')->insert([
                    'paymentid'      => $paymentId, // Fixed: Manual ID provided
                    'leaseno'        => $request->leaseno,
                    'payment_date'   => $targetMonth,
                    'amount_paid'    => $lease->monthly_rent,
                    'payment_method' => $request->payment_method,
                    'notes'          => "Advance payment for " . $targetMonth->format('F Y'),
                ]);
            }
        });

        return back()->with('success', "Payment of $numMonths month(s) confirmed.");
    }
}