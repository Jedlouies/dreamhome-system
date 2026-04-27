<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffProfileController extends Controller

{
    public function index(Request $request)
        {
            $branches = DB::table('branch')->select('branchno', 'city')->get();

            $selectedBranch = $request->input('branchno');
            $search = $request->input('search'); 

            $staffs = DB::table(DB::raw("get_staff_by_branch(
                    CAST(:branch AS TEXT), 
                    CAST(:search AS TEXT)
                ) as staff_data"))
                ->setBindings([
                    'branch' => $selectedBranch ?: null,
                    'search' => $search ?: null
                ])
                ->paginate(10)
                ->withQueryString();

            return view('staff.staff', compact('staffs', 'branches'));
        }

    public function show($id)
        {
            $staff = DB::table(DB::raw("get_staff_details(CAST(:id AS TEXT))"))
                ->setBindings(['id' => $id])
                ->first();

            if (!$staff) {
                abort(404, 'Staff member not found.');
            }

            return view('staff.show', compact('staff'));
        }

public function edit($id)
{
    $staff = DB::table(DB::raw("get_staff_details(CAST(:id AS TEXT))"))
        ->setBindings(['id' => $id])
        ->first();

    $branches = DB::table('branch')->select('branchno', 'city')->get();

    if (!$staff) {
        abort(404);
    }

    return view('staff.edit', compact('staff', 'branches'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'firstname'     => 'required|string',
        'lastname'      => 'required|string',
        'address'       => 'required|string',
        'telephoneno'   => 'required|string',
        'sex'           => 'required|in:M,F',
        'date_of_birth' => 'required|date',
        'nin'           => 'required|string',
        'position'      => 'required|in:Secretary,Manager,Regular', 
        'salary'        => 'required|numeric',
        'branchno'      => 'required|string',
        'email'         => 'required|email',
    ]);

    DB::statement("CALL update_staff_member(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
        $id,
        $request->firstname,
        $request->lastname,
        $request->address,
        $request->telephoneno,
        $request->sex,
        $request->date_of_birth,
        $request->nin,
        $request->position,
        $request->salary,
        $request->branchno,
        $request->email
    ]);

    return redirect()->route('staff.staff')->with('success', 'Staff updated successfully!');
}

    public function create()
    {
        $branches = DB::table('branch')->select('branchno', 'city')->get();

        $lastStaff = DB::table('staff')->latest('staffno')->first();
        $nextId = $lastStaff ? (int) filter_var($lastStaff->staffno, FILTER_SANITIZE_NUMBER_INT) + 1 : 1;
        $autoStaffNo = 'SL234' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('staff.create', compact('branches', 'autoStaffNo'));
    }

    public function store(Request $request)
        {
            $validated = $request->validate([
                'staffno'       => 'required',
                'firstname'     => 'required|string',
                'lastname'      => 'required|string',
                'address'       => 'required|string',
                'telephoneno'   => 'required|string',
                'sex'           => 'required|in:M,F',
                'date_of_birth' => 'required|date',
                'nin'           => 'required|string',
                'position'      => 'required|in:Secretary,Manager,Regular', 
                'salary'        => 'required|numeric',
                'branchno'      => 'required|string',
                'email'         => 'required|email',
                'password'      => 'required|min:8',
            ]);

            $hashedPassword = bcrypt($request->password);

            DB::statement("CALL insert_staff_member(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                $request->staffno,
                $request->firstname,
                $request->lastname,
                $request->address,
                $request->telephoneno,
                $request->sex,
                $request->date_of_birth,
                $request->nin,
                $request->position,
                $request->salary,
                $request->branchno,
                $hashedPassword,
                $request->email
            ]);

            return redirect()->route('staff.staff')->with('success', 'Staff added via Stored Procedure!');
        }
}
