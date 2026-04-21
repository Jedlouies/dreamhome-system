<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-pink-100 text-[#853953]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Properties</p>
                            <h4 class="text-2xl font-bold text-gray-800">24</h4>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Active Renters</p>
                            <h4 class="text-2xl font-bold text-gray-800">156</h4>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Avg. Revenue</p>
                            <h4 class="text-2xl font-bold text-gray-800">₱45,200</h4>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Inspections</p>
                            <h4 class="text-2xl font-bold text-gray-800">8</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                
                <div class="w-full bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-6">
                    <div class="flex justify-between mb-5">
                        <div>
                            <h5 class="leading-none text-3xl font-bold text-gray-900 pb-2">₱12,423</h5>
                            <p class="text-base font-normal text-gray-500">Sales this week</p>
                        </div>
                        <div class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 text-center">
                            12%
                            <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                            </svg>
                        </div>
                    </div>
                    <div id="line-chart"></div>
                </div>

                <div class="w-full bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-6">
                    <div class="flex justify-between items-start w-full">
                        <h5 class="text-xl font-bold leading-none text-gray-900 me-1">Property Distribution</h5>
                    </div>
                    <div class="py-6" id="pie-chart"></div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Line Chart Configuration
        const lineOptions = {
            chart: { height: "100%", maxWidth: "100%", type: "area", fontFamily: "Inter, sans-serif", toolbar: { show: false } },
            tooltip: { enabled: true, x: { show: false } },
            fill: { type: "gradient", gradient: { opacityFrom: 0.55, opacityTo: 0, shade: "#853953", gradientToColors: ["#853953"] } },
            dataLabels: { enabled: false },
            stroke: { width: 6, colors: ["#853953"] },
            grid: { show: false, strokeDashArray: 4, padding: { left: 2, right: 2, top: 0 } },
            series: [{ name: "Sales", data: [6500, 6418, 6456, 6526, 6356, 6456], color: "#853953" }],
            xaxis: { categories: ['01 Feb', '02 Feb', '03 Feb', '04 Feb', '05 Feb', '06 Feb'], labels: { show: false }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: { show: false }
        };

        // Pie Chart Configuration
        const pieOptions = {
            series: [52.8, 26.8, 20.4],
            colors: ["#853953", "#c26586", "#df90ac"],
            chart: { height: 420, width: "100%", type: "pie" },
            stroke: { colors: ["white"], lineCap: "" },
            plotOptions: { pie: { labels: { show: true }, size: "100%", dataLabels: { offset: -25 } } },
            labels: ["Flats", "Houses", "Other"],
            dataLabels: { enabled: true, style: { fontFamily: "Inter, sans-serif" } },
            legend: { position: "bottom", fontFamily: "Inter, sans-serif" }
        };

        if (document.getElementById("line-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("line-chart"), lineOptions);
            chart.render();
        }

        if (document.getElementById("pie-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("pie-chart"), pieOptions);
            chart.render();
        }
    </script>
</x-app-layout>