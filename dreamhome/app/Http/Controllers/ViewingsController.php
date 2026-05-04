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

    // ViewingsController.php

    public function create()
        {
        // Fetch active properties and renters for the form dropdowns[cite: 2, 9]
        $properties = DB::table('property')->select('propertyno', 'street')->get();
        $renters = DB::table('renter')->select('renterno', 'firstname', 'lastname')->get();

        // Logic to auto-generate the next Viewing ID (e.g., V005)[cite: 2]
        $lastViewing = DB::table('viewing')->latest('viewingid')->first();
        $nextId = $lastViewing ? (int) filter_var($lastViewing->viewingid, FILTER_SANITIZE_NUMBER_INT) + 1 : 1;
        $autoViewingId = 'V' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('staff.viewings.create', compact('properties', 'renters', 'autoViewingId'));
    }

    public function store(Request $request)
    {
        // Validate input to prevent database errors[cite: 2, 7]
        $request->validate([
            'viewingid'  => 'required|string|unique:viewing,viewingid',
            'propertyno' => 'required|string|exists:property,propertyno',
            'renterno'   => 'required|string|exists:renter,renterno',
            'view_date'  => 'required|date',
            'comment'    => 'nullable|string'
        ]);

        // Use the Stored Procedure for data insertion[cite: 1, 2]
        DB::statement("CALL insert_viewing(?, ?, ?, ?, ?)", [
            $request->viewingid,
            $request->propertyno,
            $request->renterno,
            $request->view_date,
            $request->comment
        ]);

        return redirect()->route('staff.viewings.index')->with('success', 'Viewing scheduled successfully!');
    }
}
