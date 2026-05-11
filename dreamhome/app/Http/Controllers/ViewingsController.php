<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewingsController extends Controller
{
    public function index()
    {
        // MAIN PANEL: ONLY Scheduled (staffno is NOT NULL)
        $viewings = DB::table('viewing as v')
            ->join('property as p', 'v.propertyno', '=', 'p.propertyno')
            ->join('renter as r', 'v.renterno', '=', 'r.renterno')
            ->leftJoin('staff as s', 'v.staffno', '=', 's.staffno')
            ->whereNotNull('v.staffno') // This hides the pending requests
            ->select('v.viewingid as id', 'p.street as title', DB::raw("CONCAT(p.street, ', ', p.city) as addr"), 'v.view_date as date', 'v.comment', 's.firstname as staff_name')
            ->get();

        // SIDE PANEL: Pending Requests (staffno IS NULL)
        $requests = DB::table('viewing as v')
            ->join('property as p', 'v.propertyno', '=', 'p.propertyno')
            ->join('renter as r', 'v.renterno', '=', 'r.renterno')
            ->whereNull('v.staffno')
            ->select('v.viewingid as id', 'p.street as title', 'r.firstname', 'r.lastname', 'v.view_date')
            ->get();

        // TIMELINE: Clickable upcoming items for pre-filling
        $timeline = DB::table('viewing as v')
            ->join('property as p', 'v.propertyno', '=', 'p.propertyno')
            ->leftJoin('staff as s', 'v.staffno', '=', 's.staffno')
            ->where('v.view_date', '>=', now()->toDateString())
            ->select('v.viewingid as id', 'v.view_date', 'p.street', 's.firstname as staff_name')
            ->get()
            ->groupBy('view_date');

        $staffList = DB::table('staff')->select('staffno', 'firstname', 'lastname')->get();

        return view('staff.viewings', compact('viewings', 'requests', 'timeline', 'staffList'));
    }

    // ViewingsController.php

public function create(Request $request, $request_id = null)
{
    $prefilledRequest = null;
    
    if ($request_id) {
        // Use trim() to ensure no trailing spaces from database CHAR fields
        $prefilledRequest = DB::table('viewing')
            ->where('viewingid', trim($request_id))
            ->first();
    }

    $properties = DB::table('property')->select('propertyno', 'street')->get();
    $renters = DB::table('renter')->select('renterno', 'firstname', 'lastname')->get();
    $users = DB::table('users')->select('id', 'name')->get();
    $staffList = DB::table('staff')->select('staffno', 'firstname', 'lastname')->get();

    // Force values to strings for easier Blade comparison
    if ($prefilledRequest) {
        $prefilledRequest->propertyno = trim($prefilledRequest->propertyno);
        $prefilledRequest->renterno = trim($prefilledRequest->renterno);
        if ($prefilledRequest->staffno) {
            $prefilledRequest->staffno = trim($prefilledRequest->staffno);
        }
    }

    $autoViewingId = $prefilledRequest ? trim($prefilledRequest->viewingid) : null;
    
    if (!$autoViewingId) {
        $lastViewing = DB::table('viewing')->latest('viewingid')->first();
        $nextId = $lastViewing ? (int) filter_var($lastViewing->viewingid, FILTER_SANITIZE_NUMBER_INT) + 1 : 1;
        $autoViewingId = 'V' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    return view('staff.viewings.create', compact('properties', 'renters', 'users', 'staffList', 'autoViewingId', 'prefilledRequest'));
}


   // ViewingsController.php

public function store(Request $request)
{
    $request->validate([
        'viewingid'  => 'required|string',
        'propertyno' => 'required|string|exists:property,propertyno',
        'renterno'   => 'required|string',
        'view_date'  => 'required|date',
        'comment'    => 'nullable|string',
        'staffno'    => 'nullable|string|exists:staff,staffno' // Ensure staff is validated
    ]);

    // Use updateOrInsert to handle both new manual viewings and processed requests
    DB::table('viewing')->updateOrInsert(
        ['viewingid' => trim($request->viewingid)], // Search key
        [
            'propertyno' => trim($request->propertyno),
            'renterno'   => trim($request->renterno),
            'staffno'    => $request->staffno ? trim($request->staffno) : null,
            'view_date'  => $request->view_date,
            'comment'    => $request->comment,
            // Assuming status changes to 'Confirmed' once staff is assigned
            'status'     => $request->staffno ? 'Confirmed' : 'Pending' 
        ]
    );

    return redirect()->route('staff.viewings')->with('success', 'Viewing finalized successfully!');
}

    // ViewingsController.php

public function processRequest($request_id)
{
    // 1. Fetch the specific viewing request
    $requestData = DB::table('viewing as v')
        ->join('property as p', 'v.propertyno', '=', 'p.propertyno')
        ->join('renter as r', 'v.renterno', '=', 'r.renterno')
        ->where('v.viewingid', trim($request_id))
        ->select('v.*', 'p.street', 'r.firstname', 'r.lastname')
        ->first();

    if (!$requestData) {
        return redirect()->route('staff.viewings')->with('error', 'Request not found.');
    }

    // 2. Fetch staff list for assignment
    $staffList = DB::table('staff')->select('staffno', 'firstname', 'lastname')->get();

    return view('staff.viewings.process', compact('requestData', 'staffList'));
}
}