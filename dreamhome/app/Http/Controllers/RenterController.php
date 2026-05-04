<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RenterController extends Controller
{
    public function index(Request $request)
    {
        $branches = DB::table('branch')->select('branchno', 'city')->get();
        
        $selectedBranch = $request->input('branchno');
        $search = $request->input('search');

        $staffs = DB::table('staff')->select('staffno', 'firstname', 'lastname')->get();

        $renters = DB::table(DB::raw("get_renters_by_branch(
                CAST(:branch AS TEXT), 
                CAST(:search AS TEXT)
            ) as renter_data"))
            ->setBindings([
                'branch' => $selectedBranch ?: null,
                'search' => $search ?: null
            ])
            ->paginate(10)
            ->withQueryString();

        return view('staff.renters.index', compact('branches', 'renters', 'selectedBranch', 'search'));
    }

    public function create()
    {
        $branches = DB::table('branch')->select('branchno', 'city')->get();
        $staffs = DB::table('staff')->select('staffno', 'firstname', 'lastname')->get();

        $lastRenter = DB::table('renter')->latest('renterno')->first();
        $nextId = $lastRenter ? (int) filter_var($lastRenter->renterno, FILTER_SANITIZE_NUMBER_INT) + 1 : 1;
        $autoRenterNo = 'CR' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('staff.renters.create', compact('branches', 'staffs', 'autoRenterNo'));
    }

    public function show($id)
    {
        $renter = DB::table(DB::raw("get_renters_details(CAST(:id AS TEXT))"))
            ->setBindings(['id' => $id])
            ->first();

        if (!$renter) {
            abort(404, 'Renter not found.');
        }

        return view('staff.renters.show', compact('renter'));
    }

    public function store(Request $request)
        {
            $request->validate([
                'renterno'                => 'required|string|unique:renter,renterno',
                'firstname'               => 'required|string',
                'lastname'                => 'required|string',
                'address'                 => 'required|string',
                'phone'                   => 'required|string',
                'preferred_property_type' => 'required|string',
                'max_rent'                => 'required|numeric',
                'branchno'                => 'required|string',
                'witness_staffno'         => 'required|string',
            ]);

            DB::statement("CALL insert_renter(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                $request->renterno,
                $request->firstname,
                $request->lastname,
                $request->address,
                $request->phone,
                $request->preferred_property_type,
                (float)$request->max_rent,
                $request->comment,
                $request->branchno,
                $request->witness_staffno
            ]);

            return redirect()->route('staff.renters.index')
                            ->with('success', 'Renter ' . $request->firstname . ' registered successfully!');
        }

    public function edit($id)
        {
            $renter = DB::table('renter')->where('renterno', $id)->first();

             if (!$renter) {
                 abort(404, 'Renter not found.');
             }

             

            $branches = DB::table('branch')->get();
            $staffs = DB::table('staff')->get();

            if (!$renter) abort(404);

            return view('staff.renters.edit', compact('renter', 'branches', 'staffs'));
        }
    
     public function update(Request $request, $id)
        {
            $request->validate([
                'firstname'        => 'required|string',
                'lastname'         => 'required|string',
                'address'          => 'required|string',
                'phone'            => 'required|string',
                'sex'              => 'required|string',
                'preferred_property_type' => 'required|string',
                'max_rent'         => 'required|numeric',
                'comment'          => 'nullable|string',
                'witness_staffno'       => 'required|string',
                'branchno'      => 'required|string', // Added
            ]);

            DB::statement("CALL update_renter(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                $id,
                $request->firstname,
                $request->lastname,
                $request->address,
                $request->phone,
                $request->sex,
                $request->preferred_property_type,
                (float)$request->max_rent,
                $request->comment,
                $request->branchno,
                $request->witness_staffno
            ]);

            return redirect()->route('staff.renters.index')->with('success', 'Renter updated!');
        }
        public function history($id)
            {
                $renter = DB::table('renter')->where('renterno', $id)->first();

                if (!$renter) {
                    abort(404, 'Renter not found.');
                }

                $leases = DB::table(DB::raw("get_lease_history(CAST(:id AS TEXT)) as lease_data"))
                    ->setBindings(['id' => $id])
                    ->get();

                return view('staff.renters.leases', compact('renter', 'leases'));
        }
    
}
