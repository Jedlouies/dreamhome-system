<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        // Defining the array here ensures the Blade variable $reports is defined
        $reports = [
            [
                'title' => 'Staff Productivity',
                'desc' => 'Export total viewings and leases managed by individual staff members.',
                'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197',
                'type' => 'PDF',
                'slug' => 'productivity',
                'color' => 'from-blue-500 to-blue-700'
            ],
            [
                'title' => 'Revenue Report',
                'desc' => 'Consolidated monthly rent collection from all active lease agreements.',
                'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'type' => 'PDF',
                'slug' => 'revenue',
                'color' => 'from-[#853953] to-[#5d273a]'
            ],
            [
                'title' => 'Property Inventory',
                'desc' => 'Current status of all flats and houses including vacancy rates.',
                'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                'type' => 'PDF',
                'slug' => 'inventory',
                'color' => 'from-emerald-500 to-emerald-700'
            ],
            [
                'title' => 'Inspection Logs',
                'desc' => 'Historical property evaluation data and pending maintenance flags.',
                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                'type' => 'PDF',
                'slug' => 'inspections',
                'color' => 'from-amber-500 to-amber-700'
            ]
        ];

        return view('staff.reports', compact('reports'));
    }

    public function generate(Request $request)
    {
        $type = $request->query('type');

        return match ($type) {
            'productivity' => $this->staffProductivity(),
            'revenue'      => $this->revenueReport(),
            'inventory'    => $this->propertyInventory(),
            'inspections'  => $this->inspectionLogs(),
            default        => back()->with('error', 'Invalid report type selected.'),
        };
    }

    private function staffProductivity()
    {
        $data = DB::table('staff as s')
            ->select('s.firstname', 's.lastname', 's.position',
                DB::raw('(SELECT COUNT(*) FROM viewing v WHERE v.staffno = s.staffno) as total_viewings'),
                DB::raw('(SELECT COUNT(*) FROM lease_agreement l WHERE l.staffno = s.staffno) as total_leases')
            )->get();
        return $this->export('Staff_Productivity_Report', $data);
    }

    private function revenueReport()
    {
        $data = DB::table('lease_agreement as l')
            ->join('property as p', 'l.propertyno', '=', 'p.propertyno')
            ->select('l.leaseno', 'p.street', 'l.paymentmethod', 'l.monthly_rent', 'l.startdate')
            ->get();
        return $this->export('Revenue_Collection_Report', $data);
    }

    private function propertyInventory()
    {
        $data = DB::table('property')
            ->select('propertyno', 'street', 'city', 'property_type', 'monthly_rate', 'staffno')
            ->get();
        return $this->export('Property_Inventory_Report', $data);
    }

    private function inspectionLogs()
    {
        $data = DB::table('property_inspection as i')
            ->join('property as p', 'i.propertyno', '=', 'p.propertyno')
            ->select('i.inspectionid', 'p.street', 'i.inspection_date', 'i.comment')
            ->get();
        return $this->export('Inspection_History_Report', $data);
    }

    private function export($filename, $data)
    {
        $pdf = Pdf::loadView('staff.reports.template', [
            'title' => str_replace('_', ' ', $filename),
            'data' => $data,
            'generated_at' => now()->format('F d, Y h:i A')
        ]);
        return $pdf->download($filename . '_' . now()->format('Ymd') . '.pdf');
    }
}