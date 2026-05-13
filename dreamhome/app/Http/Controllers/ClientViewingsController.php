<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ClientViewingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $viewings = collect();

        if ($user->renterno) {
            $viewings = DB::table(
                DB::raw("get_renter_viewings(CAST(:renterno AS TEXT)) as v")
            )
            ->setBindings(['renterno' => $user->renterno])
            ->get();
        }

        return view('viewings', compact('viewings'));
    }

public function store(Request $request)
{
    $request->validate([
        'propertyno' => 'required|exists:property,propertyno',
        'view_date'  => 'required|date|after:today',
        'comment'    => 'nullable|string',
        'contact_no' => 'required|string', 
    ]);

    $user = Auth::user();
    $renterNo = $user->renterno;

    // 1. Handle Guest to Renter Conversion
    if (!$renterNo) {
        // Fetch a valid branch ID from the database to avoid foreign key errors
        $validBranch = DB::table('branch')->select('branchno')->first();
        $branchId = $validBranch ? $validBranch->branchno : 'B001'; // Fallback to B001

        // Generate a new Renter ID
        $lastRenter = DB::table('renter')->latest('renterno')->first();
        $nextRNum = $lastRenter ? (int) filter_var($lastRenter->renterno, FILTER_SANITIZE_NUMBER_INT) + 1 : 1;
        $renterNo = 'R' . str_pad($nextRNum, 3, '0', STR_PAD_LEFT);

        DB::table('renter')->insert([
            'renterno'                => $renterNo,
            'firstname'               => explode(' ', $user->name)[0],
            'lastname'                => explode(' ', $user->name)[1] ?? '',
            'address'                 => 'Not Provided',
            'phone'                   => $request->contact_no,
            'preferred_property_type' => 'Flat',           
            'max_rent'                => 0,                
            'comment'                 => 'Auto-generated from viewing request',
            'branchno'                => $branchId, // FIXED: Using a confirmed valid branch ID
        ]);

        // Link the user to the new renter record
        DB::table('users')->where('id', $user->id)->update(['renterno' => $renterNo]);
    }

    // 2. Generate Viewing ID
    $lastViewing = DB::table('viewing')->latest('viewingid')->first();
    $nextVNum = $lastViewing ? (int) filter_var($lastViewing->viewingid, FILTER_SANITIZE_NUMBER_INT) + 1 : 1;
    $viewingId = 'V' . str_pad($nextVNum, 3, '0', STR_PAD_LEFT);

    // 3. Insert Viewing
    DB::table('viewing')->insert([
        'viewingid'  => $viewingId,
        'renterno'   => $renterNo,
        'propertyno' => $request->propertyno,
        'view_date'  => $request->view_date,
        'comment'    => $request->comment,
        'staffno'    => null, 
    ]);

    return redirect()->route('home')->with('success', 'Viewing request sent!');
}
}