<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeasesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // If user has no renterno linked yet, show empty state
        if (!$user->renterno) {
            return view('leases', [
                'lease'    => null,
                'payments' => collect(),
                'progress' => 0,
            ]);
        }

        // Fetch the active lease for this renter
        // joined with property and staff details
        $lease = DB::table('lease_agreement as la')
            ->join('property as p',    'la.propertyno', '=', 'p.propertyno')
            ->join('staff as s',       'la.staffno',    '=', 's.staffno')
            ->join('renter as r',      'la.renterno',   '=', 'r.renterno')
            ->where('la.renterno', $user->renterno)
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

        // Fetch payment history for this lease
        $payments = collect();
        if ($lease) {
            $payments = DB::table('payment')
                ->where('leaseno', $lease->leaseno)
                ->orderByDesc('payment_date')
                ->orderByDesc('paymentid')
                ->get();
        }

        // Calculate lease progress percentage
        $progress = 0;
        if ($lease) {
            $start    = strtotime($lease->startdate);
            $end      = strtotime($lease->enddate);
            $today    = time();
            $total    = $end - $start;
            $elapsed  = max(0, min($today - $start, $total));
            $progress = $total > 0 ? round(($elapsed / $total) * 100) : 0;
        }

        return view('leases', compact('lease', 'payments', 'progress'));
    }
}
        $leases = DB::table('lease_agreement as l')
            ->join('renter as r', 'l.renterno', '=', 'r.renterno')
            ->join('property as p', 'l.propertyno', '=', 'p.propertyno')
            ->select(
                'l.leaseno as id',
                DB::raw("CONCAT(r.firstname, ' ', r.lastname) as name"),
                'r.renterno as renter_no',
                'p.street as property',
                DB::raw("CONCAT(p.street, ', ', p.city) as address"),
                'l.monthly_rent as rent',
                'l.startdate as start',
                'l.enddate as end'
            )
            ->get();

        return view('staff.leases.index', compact('leases'));
    }
}
