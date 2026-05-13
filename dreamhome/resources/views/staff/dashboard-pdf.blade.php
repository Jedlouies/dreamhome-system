<!DOCTYPE html>
<html>
<head>
    <title>DreamHome Report</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #853953; padding-bottom: 15px; margin-bottom: 30px; }
        .title { font-size: 22px; font-weight: bold; color: #853953; text-transform: uppercase; }
        .meta { font-size: 9px; color: #666; margin-top: 5px; }
        .stats-row { width: 100%; margin-bottom: 30px; }
        .stat-card { display: inline-block; width: 23%; background: #fdf2f4; padding: 15px 5px; border-radius: 12px; text-align: center; }
        .stat-val { font-size: 20px; font-weight: bold; color: #853953; display: block; }
        .stat-label { font-size: 8px; color: #999; text-transform: uppercase; font-weight: bold; }
        .section { margin-bottom: 30px; }
        .section-title { font-size: 13px; font-weight: bold; border-left: 4px solid #853953; padding-left: 10px; margin-bottom: 15px; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f8f8; font-size: 9px; text-align: left; padding: 10px; border-bottom: 1px solid #ddd; color: #666; }
        td { padding: 10px; font-size: 10px; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">DreamHome — {{ $isRegular ? 'Workspace Report' : 'System Overview' }}</div>
        <div class="meta">STAFF: {{ $staff->firstname }} {{ $staff->lastname }} | GENERATED: {{ $generated_at }}</div>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <span class="stat-val">{{ $totalProperties }}</span>
            <span class="stat-label">Properties</span>
        </div>
        <div class="stat-card">
            <span class="stat-val">{{ $isRegular ? $totalViewings : $totalRenters }}</span>
            <span class="stat-label">{{ $isRegular ? 'Viewings' : 'Renters' }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-val">{{ $isRegular ? $totalLeases : '₱' . number_format($totalRevenue/1000, 1) . 'k' }}</span>
            <span class="stat-label">{{ $isRegular ? 'Contracts' : 'Revenue' }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-val">{{ $isRegular ? $pendingInspections : $pendingActions }}</span>
            <span class="stat-label">Pending</span>
        </div>
    </div>

    @if($isRegular)
    <div class="section">
        <div class="section-title">My Managed Lease Agreements</div>
        <table>
            <thead>
                <tr>
                    <th>Lease #</th>
                    <th>Property Address</th>
                    <th>Renter Name</th>
                    <th>Expires</th>
                    <th>Monthly Rent</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignedLeases as $lease)
                <tr>
                    <td>{{ $lease->leaseno }}</td>
                    <td>{{ $lease->street }}, {{ $lease->city }}</td>
                    <td>{{ $lease->r_fname }} {{ $lease->r_lname }}</td>
                    <td>{{ \Carbon\Carbon::parse($lease->enddate)->format('M d, Y') }}</td>
                    <td>₱{{ number_format($lease->monthly_rent, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Pending Property Inspections</div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Property</th>
                    <th>Inspection Date</th>
                    <th>City</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignedInspections as $ins)
                <tr>
                    <td>#{{ $ins->inspectionid }}</td>
                    <td>{{ $ins->street }}</td>
                    <td>{{ \Carbon\Carbon::parse($ins->inspection_date)->format('F d, Y') }}</td>
                    <td>{{ $ins->city }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</body>
</html>