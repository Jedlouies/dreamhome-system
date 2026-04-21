<?php

namespace App\Http\Controllers;

use App\Models\Properties;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProperties = Properties::count();
        
        $totalRenters = DB::table('renter')->count(); 
        
        $totalRevenue = Properties::sum('monthly_rate'); 
        
        $pendingActions = Properties::where('property_type', 'Flat')->count(); 

        $inventoryMix = Properties::select('property_type', DB::raw('count(*) as total'))
            ->groupBy('property_type')
            ->get();
        $weeklyData = [4500, 5200, 4800, 5900, 6100, 5800, 6500]; 

        return view('staff.dashboard', compact(
            'totalProperties', 
            'totalRenters', 
            'totalRevenue', 
            'pendingActions',
            'inventoryMix',
            'weeklyData'
        ));
    }
}