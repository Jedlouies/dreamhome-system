<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewingsController extends Controller
{
    public function index()
    {
        $viewings = DB::table('viewing as v')
            ->join('property as p', 'v.propertyno', '=', 'p.propertyno')
            ->select(
                'v.viewingid as id',
                'p.street as title',
                DB::raw("CONCAT(p.street, ', ', p.city) as addr"),
                'v.view_date as date',
                'v.comment'
            )
            ->orderBy('v.view_date', 'desc')
            ->get();

        $timeline = DB::table('viewing as v')
            ->join('property as p', 'v.propertyno', '=', 'p.propertyno')
            ->join('staff as s', 'p.staffno', '=', 's.staffno')
            ->where('v.view_date', '>=', now()->toDateString())
            ->select('v.view_date', 'p.street', 's.firstname as staff_name')
            ->get()
            ->groupBy('view_date');

        return view('staff.viewings', compact('viewings', 'timeline'));
    }
}
