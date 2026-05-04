<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeasesController extends Controller
{
    public function index()
    {
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
