<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-900 leading-tight tracking-tight">
            {{ __('Staff Overview') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <div class="p-6 bg-white rounded-[2rem] shadow-sm border border-white hover:shadow-md transition-all group">
                            <div class="flex items-center">
                                <div class="p-3 rounded-2xl bg-pink-50 text-[#853953] group-hover:bg-[#853953] group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Properties</p>
                                    <h4 class="text-2xl font-black text-gray-900">24</h4>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 bg-white rounded-[2rem] shadow-sm border border-white group">
                            <div class="flex items-center">
                                <div class="p-3 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Renters</p>
                                    <h4 class="text-2xl font-black text-gray-900">156</h4>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 bg-white rounded-[2rem] shadow-sm border border-white group">
                            <div class="flex items-center">
                                <div class="p-3 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Revenue</p>
                                    <h4 class="text-2xl font-black text-gray-900">₱45k</h4>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 bg-white rounded-[2rem] shadow-sm border border-white group">
                            <div class="flex items-center">
                                <div class="p-3 rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pending</p>
                                    <h4 class="text-2xl font-black text-gray-900">8</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="w-full bg-white rounded-[2.5rem] shadow-sm border border-white p-8">
                            <div class="flex justify-between mb-8">
                                <div>
                                    <h5 class="text-3xl font-black text-gray-900 tracking-tighter">₱12,423</h5>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Weekly Growth</p>
                                </div>
                                <div class="flex items-center px-3 py-1 text-sm font-black text-emerald-500 bg-emerald-50 rounded-full h-fit">
                                    12%
                                    <svg class="w-3 h-3 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7 7 7"></path></svg>
                                </div>
                            </div>
                            <div id="line-chart"></div>
                        </div>

                        <div class="w-full bg-white rounded-[2.5rem] shadow-sm border border-white p-8">
                            <div class="flex justify-between items-start mb-4">
                                <h5 class="text-xl font-black text-gray-900 tracking-tighter">Inventory Mix</h5>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Types Distribution</p>
                            </div>
                            <div class="py-2" id="pie-chart"></div>
                        </div>
                    </div>
                </div>

                <aside class="w-full lg:w-80 flex-shrink-0">
                    <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white sticky top-6 flex flex-col min-h-[600px]">
                        <div class="mb-10 px-2">
                            <h2 class="text-xl font-black text-gray-800 tracking-tighter">Quick Actions</h2>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Common Staff Tasks</p>
                        </div>

                        <div class="flex-1 space-y-4 px-2">
                            <a href="#" class="group flex items-center p-4 rounded-3xl bg-gray-50 hover:bg-[#853953] transition-all duration-300">
                                <div class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-[#853953] shadow-sm group-hover:rotate-12 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-xs font-black text-gray-800 group-hover:text-white leading-none">New Property</p>
                                    <p class="text-[9px] text-gray-400 font-bold group-hover:text-pink-200 mt-1 uppercase">List Flat or House</p>
                                </div>
                            </a>

                            <a href="#" class="group flex items-center p-4 rounded-3xl bg-gray-50 hover:bg-[#853953] transition-all duration-300">
                                <div class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-[#853953] shadow-sm group-hover:rotate-12 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-xs font-black text-gray-800 group-hover:text-white leading-none">Record Viewing</p>
                                    <p class="text-[9px] text-gray-400 font-bold group-hover:text-pink-200 mt-1 uppercase">Track Renter Visits</p>
                                </div>
                            </a>
                            
                            <a href="#" class="group flex items-center p-4 rounded-3xl bg-gray-50 hover:bg-[#853953] transition-all duration-300">
                                <div class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-[#853953] shadow-sm group-hover:rotate-12 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-xs font-black text-gray-800 group-hover:text-white leading-none">Draft Lease</p>
                                    <p class="text-[9px] text-gray-400 font-bold group-hover:text-pink-200 mt-1 uppercase">Generate Contract</p>
                                </div>
                            </a>
                        </div>

                    </div>
                </aside>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const lineOptions = {
            chart: { height: 250, type: "area", fontFamily: "Inter, sans-serif", toolbar: { show: false }, zoom: { enabled: false } },
            tooltip: { enabled: true, theme: 'dark' },
            fill: { type: "gradient", gradient: { opacityFrom: 0.6, opacityTo: 0.05, shade: "#853953", gradientToColors: ["#853953"] } },
            dataLabels: { enabled: false },
            stroke: { width: 4, colors: ["#853953"], curve: 'smooth' },
            grid: { borderColor: '#F3F4F6', strokeDashArray: 4 },
            series: [{ name: "Revenue", data: [4500, 5200, 4800, 5900, 6100, 5800, 6500], color: "#853953" }],
            xaxis: { categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], labels: { style: { colors: '#9CA3AF', fontWeight: 600 } }, axisBorder: { show: false } },
            yaxis: { show: false }
        };

        const pieOptions = {
            series: [52.8, 26.8, 20.4],
            colors: ["#853953", "#c26586", "#df90ac"],
            chart: { height: 320, type: "donut" },
            plotOptions: { pie: { donut: { size: '75%', labels: { show: true, name: { show: true, fontWeight: 900 }, value: { show: true, fontWeight: 900 } } } } },
            labels: ["Flats", "Houses", "Other"],
            dataLabels: { enabled: false },
            legend: { position: "bottom", fontWeight: 700 }
        };

        new ApexCharts(document.getElementById("line-chart"), lineOptions).render();
        new ApexCharts(document.getElementById("pie-chart"), pieOptions).render();
    </script>
</x-app-layout>