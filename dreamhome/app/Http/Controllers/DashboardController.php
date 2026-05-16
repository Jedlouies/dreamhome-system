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
        $isRegular = strtolower($staff->position) === 'regular';

        // Base statistics
        $stats = [
            'totalProperties' => DB::table('property')->count(),
            'totalRenters'    => DB::table('renter')->count(),
            'totalRevenue'    => DB::table('lease_agreement')->sum('monthly_rent'),
            'pendingActions'  => DB::table('property_inspection')->where('status', '!=', 'Completed')->count(),
        ];

        // Specific data for Regular Staff
        $assignedData = [
            'assignedProperties' => collect(),
            'assignedViewings'   => collect(),
            'assignedInspections' => collect(),
            'completedInspections' => collect(),
            'assignedLeases'    => collect()
        ];
        if ($isRegular) {
            $assignedData = [
                'assignedProperties' => DB::table('property')
                    ->where('staffno', $staff->staffno)
                    ->get(),
                'assignedViewings' => DB::table('viewing as v')
                    ->join('property as p', 'v.propertyno', '=', 'p.propertyno')
                    ->join('renter as r', 'v.renterno', '=', 'r.renterno')
                    ->where('v.staffno', $staff->staffno)
                    ->where('v.status', '!=', 'Completed')
                    ->select('v.*', 'p.street', 'r.firstname as r_fname', 'r.lastname as r_lname')
                    ->get(),
                // Filter sidebar to only show active tasks
                'assignedInspections' => DB::table('property_inspection as i')
                    ->join('property as p', 'i.propertyno', '=', 'p.propertyno')
                    ->where('i.staffno', $staff->staffno)
                    ->where('i.status', '!=', 'Completed')
                    ->select('i.*', 'p.street', 'p.city')
                    ->orderBy('i.inspection_date', 'asc')
                    ->get(),
                // NEW: Fetch completed inspections for the Main Panel
                'completedInspections' => DB::table('property_inspection as i')
                    ->join('property as p', 'i.propertyno', '=', 'p.propertyno')
                    ->where('i.staffno', $staff->staffno)
                    ->where('i.status', '=', 'Completed')
                    ->select('i.*', 'p.street', 'p.city')
                    ->orderBy('i.updated_at', 'desc')
                    ->get(),
                'assignedLeases' => DB::table('lease_agreement as l')
                    ->join('property as p', 'l.propertyno', '=', 'p.propertyno')
                    ->join('renter as r', 'l.renterno', '=', 'r.renterno')
                    ->where('l.staffno', $staff->staffno)
                    ->select('l.*', 'p.street', 'r.firstname as r_fname', 'r.lastname as r_lname')
                    ->get(),
            ];

            $stats['totalViewings'] = count($assignedData['assignedViewings']);
            $stats['totalLeases'] = count($assignedData['assignedLeases']);
            $stats['pendingInspections'] = count($assignedData['assignedInspections']);
        }

        // Charts logic
        $inventoryMix = DB::table('property')
            ->select('property_type', DB::raw('count(*) as total'))
            ->groupBy('property_type')
            ->get();

        $chartData = [45, 52, 38, 24, 33, 26, 21]; // Placeholder
        $chartLabel = "Weekly Productivity";

        return array_merge($stats, $assignedData, [
            'isRegular'    => $isRegular,
            'inventoryMix' => $inventoryMix,
            'chartData'    => $chartData,
            'chartLabel'   => $chartLabel
        ]);
    }

    public function updateInspectionFeedback(Request $request)
    {
        $request->validate([
            'inspectionid' => 'required|exists:property_inspection,inspectionid',
            'comment'      => 'required|string|max:1000',
        ]);

        // Update the record with findings and set status to Completed
        DB::table('property_inspection')
            ->where('inspectionid', $request->inspectionid)
            ->update([
                'comment' => $request->comment,
                'status'  => 'Completed',
                'updated_at' => now()
            ]);

        return back()->with('success', 'Inspection report finalized and moved to dashboard history.');
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