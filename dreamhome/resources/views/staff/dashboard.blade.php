<x-app-layout>
    <style>[x-cloak] { display: none !important; }</style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="font-black text-2xl text-gray-900 leading-tight tracking-tight">
                    {{ $isRegular ? __('My Workspace') : __('Staff Overview') }}
                </h2>
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] mt-1">DreamHome Management System</p>
            </div>

            <a href="{{ route('staff.dashboard.report') }}" 
               class="flex items-center justify-center gap-3 bg-[#853953] text-white px-8 py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-pink-100 hover:bg-pink-900 transition-all group">
                <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Generate Detailed PDF Report
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- SUCCESS FEEDBACK --}}
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-3xl font-black text-[10px] uppercase tracking-widest flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- 1. STAT CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-12">
                {{-- Total Properties --}}
                <div class="p-6 bg-white rounded-[2rem] shadow-sm border border-white group">
                    <div class="flex items-center">
                        <div class="p-3 rounded-2xl bg-pink-50 text-[#853953] group-hover:bg-[#853953] group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $isRegular ? 'Managed' : 'Properties' }}</p>
                            <h4 class="text-2xl font-black text-gray-900">{{ $totalProperties }}</h4>
                        </div>
                    </div>
                </div>

                {{-- Viewings/Renters --}}
                <div class="p-6 bg-white rounded-[2rem] shadow-sm border border-white group">
                    <div class="flex items-center">
                        <div class="p-3 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $isRegular ? 'Viewings' : 'Renters' }}</p>
                            <h4 class="text-2xl font-black text-gray-900">{{ $isRegular ? $totalViewings : $totalRenters }}</h4>
                        </div>
                    </div>
                </div>

                {{-- Contracts/Revenue --}}
                <div class="p-6 bg-white rounded-[2rem] shadow-sm border border-white group">
                    <div class="flex items-center">
                        <div class="p-3 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $isRegular ? 'Contracts' : 'Revenue' }}</p>
                            <h4 class="text-2xl font-black text-gray-900">
                                @if($isRegular) {{ $totalLeases }} @else ₱{{ number_format($totalRevenue / 1000, 1) }}k @endif
                            </h4>
                        </div>
                    </div>
                </div>

                {{-- Inspections/Unpaid --}}
                <div class="p-6 bg-white rounded-[2rem] shadow-sm border border-white group">
                    <div class="flex items-center">
                        <div class="p-3 rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $isRegular ? 'Inspections' : 'Unpaid' }}</p>
                            <h4 class="text-2xl font-black text-gray-900">{{ $isRegular ? $pendingInspections : $pendingActions }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. REGULAR STAFF VIEW --}}
            @if($isRegular)
                <div class="space-y-12 mb-12">
                    {{-- Portfolio Details --}}
                    <div>
                        <div class="flex items-center gap-2 mb-6 px-2">
                            <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#853953]">Active Portfolio Details</h2>
                            <div class="h-px flex-1 bg-pink-100"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($assignedProperties as $prop)
                                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white flex flex-col gap-5">
                                    <div class="flex justify-between items-start">
                                        <div><span class="text-[8px] font-black text-gray-400 uppercase">ID: {{ $prop->propertyno }}</span><h3 class="text-xl font-black text-gray-900 tracking-tighter">{{ $prop->street }}</h3></div>
                                        <span class="px-3 py-1 bg-pink-50 text-[#853953] rounded-lg text-[9px] font-black uppercase border border-pink-100">{{ $prop->property_type }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100"><p class="text-[8px] font-black text-gray-400 uppercase mb-1">City</p><p class="text-xs font-bold text-gray-800">{{ $prop->city }}</p></div>
                                        <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100"><p class="text-[8px] font-black text-gray-400 uppercase mb-1">Rate</p><p class="text-xs font-black text-[#853953]">₱{{ number_format($prop->monthly_rate, 2) }}</p></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Feedback Forms --}}
                    <div>
                        <div class="flex items-center gap-2 mb-6 px-2">
                            <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#853953]">Viewing Feedback Forms</h2>
                            <div class="h-px flex-1 bg-pink-100"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($assignedViewings as $viewing)
                                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white flex flex-col gap-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center text-[#853953] font-black text-xs">{{ \Carbon\Carbon::parse($viewing->view_date)->format('d') }}</div>
                                        <div><p class="text-[10px] font-black text-gray-900 uppercase">{{ \Carbon\Carbon::parse($viewing->view_date)->format('F Y') }}</p><p class="text-[9px] text-gray-400 font-bold uppercase">ID: {{ $viewing->viewingid }}</p></div>
                                    </div>
                                    <form action="{{ route('staff.viewings.feedback') }}" method="POST" class="space-y-3">
                                        @csrf
                                        <input type="hidden" name="viewingid" value="{{ $viewing->viewingid }}">
                                        <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                                            <p class="text-[8px] font-black text-gray-400 uppercase mb-1">Client / Property</p>
                                            <p class="text-xs font-bold">{{ $viewing->r_fname }} {{ $viewing->r_lname }} — {{ $viewing->street }}</p>
                                        </div>
                                        <textarea name="comment" rows="2" placeholder="Record feedback here..." class="w-full bg-gray-50 border-gray-100 rounded-2xl text-xs focus:ring-[#853953] p-3 shadow-inner"></textarea>
                                        <button type="submit" class="w-full bg-[#853953] text-white py-3 rounded-xl font-black text-[9px] uppercase tracking-widest shadow-lg shadow-pink-100 hover:bg-pink-900 transition-colors">Submit Feedback</button>
                                    </form>
                                </div>
                            @empty
                                <div class="md:col-span-2 bg-white rounded-[2.5rem] p-10 text-center border-dashed border-gray-200 border-2">
                                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">No pending feedback sessions</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Inspections & Leases --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <div>
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#853953] mb-4 ml-2">Scheduled Inspections</h3>
                            <div class="space-y-4">
                                @foreach($assignedInspections as $ins)
                                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-white flex flex-col gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 border border-gray-100"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"></path></svg></div>
                                            <div><p class="text-[10px] font-black text-gray-900 uppercase">ID: {{ $ins->inspectionid }}</p><p class="text-[11px] text-[#853953] font-black uppercase">{{ \Carbon\Carbon::parse($ins->inspection_date)->format('M d, Y') }}</p></div>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100"><p class="text-[8px] font-black text-gray-400 uppercase mb-1">Target Property</p><p class="text-xs font-bold text-gray-700">{{ $ins->street }}, {{ $ins->city }}</p></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#853953] mb-4 ml-2">Active Lease Logs</h3>
                            <div class="space-y-4">
                                @foreach($assignedLeases as $lease)
                                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-white flex flex-col gap-4">
                                        <div class="flex justify-between items-start">
                                            <div><p class="text-[8px] font-black text-gray-400 uppercase">Lease #{{ $lease->leaseno }}</p><h4 class="text-sm font-black text-gray-900 leading-tight">{{ $lease->street }}</h4></div>
                                            <span class="text-[10px] font-black text-[#853953]">₱{{ number_format($lease->monthly_rent, 2) }}</span>
                                        </div>
                                        <div class="flex items-center gap-4 bg-gray-50 p-3 rounded-2xl border border-gray-100 text-[10px]">
                                            <div class="flex-1"><p class="text-[8px] font-black text-gray-400 uppercase mb-0.5">Renter</p><p class="font-bold">{{ $lease->r_fname }} {{ $lease->r_lname }}</p></div>
                                            <div class="flex-1 text-right"><p class="text-[8px] font-black text-gray-400 uppercase mb-0.5">Expires</p><p class="font-bold text-gray-600">{{ \Carbon\Carbon::parse($lease->enddate)->format('M d, Y') }}</p></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 3. CHARTS SECTION (Visible to All) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-white p-8">
                    <h5 class="text-xl font-black text-gray-900 tracking-tighter mb-6">{{ $chartLabel }}</h5>
                    <div id="line-chart"></div>
                </div>
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-white p-8 text-center">
                    <h5 class="text-xl font-black text-gray-900 tracking-tighter mb-6">Inventory Mix</h5>
                    <div class="py-2" id="pie-chart"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const chartData = @json($chartData);
        const chartLabel = "{{ $chartLabel }}";
        const pieLabels = @json($inventoryMix->pluck('property_type'));
        const pieValues = @json($inventoryMix->pluck('total'));

        new ApexCharts(document.querySelector("#line-chart"), {
            chart: { height: 250, type: "area", fontFamily: "Inter, sans-serif", toolbar: { show: false } },
            series: [{ name: chartLabel, data: chartData, color: "#853953" }],
            stroke: { width: 4, colors: ["#853953"], curve: 'smooth' },
            fill: { type: "gradient", gradient: { opacityFrom: 0.6, opacityTo: 0.05, shade: "#853953" } },
            xaxis: { categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], labels: { style: { fontWeight: 600 } } }
        }).render();

        new ApexCharts(document.querySelector("#pie-chart"), {
            series: pieValues,
            labels: pieLabels,
            colors: ["#853953", "#c26586", "#df90ac", "#efb1c7"],
            chart: { height: 320, type: "donut" },
            legend: { position: "bottom", fontWeight: 700 },
            plotOptions: { pie: { donut: { size: '70%' } } }
        }).render();
    </script>
</x-app-layout>