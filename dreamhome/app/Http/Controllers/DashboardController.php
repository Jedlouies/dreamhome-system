<?php

namespace App\Http\Controllers;

use App\Models\Properties;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Display the dynamic staff dashboard.
     */
    public function index()
    {
        $staff = Auth::guard('staff')->user();
        $data = $this->getDashboardData($staff);
        return view('staff.dashboard', $data);
    }

    /**
     * Shared logic to fetch dashboard data.
     * Regular staff see only their assignments; Managers see system-wide totals.
     */
    private function getDashboardData($staff)
    {
        $isRegular = $staff && strtolower($staff->position) === 'regular';

        if ($isRegular) {
            return [
                'isRegular'         => true,
                'totalProperties'   => DB::table('property')->where('staffno', $staff->staffno)->count(),
                'totalViewings'     => DB::table('viewing')->where('staffno', $staff->staffno)->count(),
                'totalLeases'       => DB::table('lease_agreement')->where('staffno', $staff->staffno)->count(),
                'pendingInspections'=> DB::table('property_inspection')->where('staffno', $staff->staffno)->count(),
                
                // Detailed data for regular staff cards
                'assignedProperties'=> DB::table('property')
                                        ->where('staffno', $staff->staffno)
                                        ->select('propertyno', 'street', 'area', 'city', 'property_type', 'monthly_rate', 'main_image')
                                        ->get(),
                                        
                'assignedViewings'  => DB::table('viewing as v')
                                        ->join('property as p', 'v.propertyno', '=', 'p.propertyno')
                                        ->join('renter as r', 'v.renterno', '=', 'r.renterno')
                                        ->where('v.staffno', $staff->staffno)
                                        ->where('v.status', '!=', 'Completed') // Auto-hides completed tasks
                                        ->select('v.*', 'p.street', 'p.city', 'r.firstname as r_fname', 'r.lastname as r_lname')
                                        ->orderBy('v.view_date', 'asc')->get(),

                'assignedInspections'=> DB::table('property_inspection as i')
                                        ->join('property as p', 'i.propertyno', '=', 'p.propertyno')
                                        ->where('i.staffno', $staff->staffno)
                                        ->select('i.*', 'p.street', 'p.city')
                                        ->orderBy('i.inspection_date', 'asc')->get(),

                'assignedLeases'    => DB::table('lease_agreement as l')
                                        ->join('property as p', 'l.propertyno', '=', 'p.propertyno')
                                        ->join('renter as r', 'l.renterno', '=', 'r.renterno')
                                        ->where('l.staffno', $staff->staffno)
                                        ->select('l.*', 'p.street', 'p.city', 'r.firstname as r_fname', 'r.lastname as r_lname')
                                        ->orderBy('l.enddate', 'asc')->get(),

                'inventoryMix'      => DB::table('property')->where('staffno', $staff->staffno)
                                        ->select('property_type', DB::raw('count(*) as total'))
                                        ->groupBy('property_type')->get(),
                'chartLabel'        => "Weekly Activity",
                'chartData'         => [5, 12, 8, 15, 10, 20, 14]
            ];
        }

        // MANAGER VIEW DATA (Full Overview)
        return [
            'isRegular'         => false,
            'totalProperties'   => Properties::count(),
            'totalRenters'      => DB::table('renter')->count(),
            'totalRevenue'      => DB::table('property')->sum('monthly_rate'),
            'pendingActions'    => DB::table('lease_agreement')->where('isdepositpaid', 'No')->count(),
            'inventoryMix'      => Properties::select('property_type', DB::raw('count(*) as total'))
                                    ->groupBy('property_type')->get(),
            'chartLabel'        => "System Revenue",
            'chartData'         => [4500, 5200, 4800, 5900, 6100, 5800, 6500]
        ];
    }

    /**
     * Mark a viewing as completed with staff feedback.
     */
    public function updateViewingFeedback(Request $request)
    {
        $request->validate([
            'viewingid' => 'required|exists:viewing,viewingid',
            'comment'   => 'required|string|max:500',
        ]);

        DB::table('viewing')
            ->where('viewingid', $request->viewingid)
            ->update([
                'comment' => $request->comment,
                'status'  => 'Completed' // Triggers disappearance from dashboard
            ]);

        return back()->with('success', 'Viewing feedback recorded and task finalized.');
    }

    /**
     * Generate a detailed multi-section PDF report.
     */
    public function downloadReport()
    {
        $staff = Auth::guard('staff')->user();
        if (!$staff) return redirect()->route('staff.login');

        $data = $this->getDashboardData($staff);
        $data['staff'] = $staff;
        $data['generated_at'] = now()->format('F d, Y h:i A');

        $pdf = Pdf::loadView('staff.dashboard-pdf', $data);
        $filename = ($data['isRegular'] ? 'Operational_Report_' : 'Management_Report_') . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}