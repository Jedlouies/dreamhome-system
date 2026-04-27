<?php

namespace App\Http\Controllers;

use App\Models\Properties;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $branches = DB::table('branch')->select('branchno', 'city')->get();

        $selectedBranch = $request->input('branchno');
        $search = $request->input('search'); 

        $properties = DB::table(DB::raw("get_properties_by_branch(
                CAST(:branch AS TEXT), 
                CAST(:search AS TEXT)
            ) as property_data"))
            ->setBindings([
                'branch' => $selectedBranch ?: null,
                'search' => $search ?: null
            ])
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Properties $properties)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Properties $properties)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Properties $properties)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Properties $properties)
    {
        //
    }
}
