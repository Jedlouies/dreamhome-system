<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $properties = DB::table(DB::raw("get_properties_by_branch(
            CAST(:branch AS TEXT),
            CAST(:search AS TEXT)
        ) as property_data"))
        ->setBindings([
            'branch' => null,
            'search' => null,
        ])
        ->get();

        $featured = $properties->first();
        $rest     = $properties->skip(1)->values();

        // Real stat counts
        $activeLeaseCount = 0;
        $viewingsCount    = 0;

        if ($user->renterno) {
            $activeLeaseCount = DB::table('lease_agreement')
                ->where('renterno', $user->renterno)
                ->where('enddate', '>=', now()->toDateString())
                ->count();

            $viewingsCount = DB::table('viewing')
                ->where('renterno', $user->renterno)
                ->count();
        }

        $availableCount = $properties->count();

        return view('home', compact(
            'properties', 'featured', 'rest',
            'activeLeaseCount', 'viewingsCount', 'availableCount'
        ));
    }
}