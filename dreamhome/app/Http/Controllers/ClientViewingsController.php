<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientViewingsController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $viewings = collect();

        // Issue 5 & 9 fix: use get_renter_viewings() function
        if ($user->renterno) {
            $viewings = DB::table(
                DB::raw("get_renter_viewings(CAST(:renterno AS TEXT)) as v")
            )
            ->setBindings(['renterno' => $user->renterno])
            ->get();
        }

        return view('viewings', compact('viewings'));
    }
}