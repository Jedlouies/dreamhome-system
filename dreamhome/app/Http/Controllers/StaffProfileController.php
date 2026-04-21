<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffProfileController extends Controller
{
    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        // 1. Fetch branches from the 'branch' table for the select dropdown
        $branches = DB::table('branch')->select('branchno', 'city')->get();

        // 2. Auto-generate Staff Number starting with SL234
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

            \DB::statement("CALL sp_AddStaff(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
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
