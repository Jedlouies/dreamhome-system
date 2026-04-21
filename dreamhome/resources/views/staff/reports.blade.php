<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="py-12 bg-[#F3F4F6] min-h-screen" x-data="{ selectedCategory: 'All' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Analytics & Reports</h1>
                            <p class="text-sm text-gray-500 mt-1 font-medium">Generate and export system data for administrative review.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $reports = [
                                [
                                    'title' => 'Staff Productivity',
                                    'desc' => 'Export total viewings and leases managed by individual staff members.',
                                    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197',
                                    'type' => 'PDF / CSV',
                                    'color' => 'from-blue-500 to-blue-700'
                                ],
                                [
                                    'title' => 'Revenue Report',
                                    'desc' => 'Consolidated monthly rent collection from all active lease agreements.',
                                    'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'type' => 'Excel',
                                    'color' => 'from-[#853953] to-[#5d273a]'
                                ],
                                [
                                    'title' => 'Property Inventory',
                                    'desc' => 'Current status of all flats and houses including vacancy rates.',
                                    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                                    'type' => 'CSV',
                                    'color' => 'from-emerald-500 to-emerald-700'
                                ],
                                [
                                    'title' => 'Inspection Logs',
                                    'desc' => 'Historical property evaluation data and pending maintenance flags.',
                                    'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                                    'type' => 'PDF',
                                    'color' => 'from-amber-500 to-amber-700'
                                ]
                            ];
                        @endphp

                        @foreach($reports as $report)
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group cursor-pointer border border-transparent hover:border-pink-50 relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-gray-50 rounded-full group-hover:scale-150 transition-transform duration-500 opacity-50"></div>
                            
                            <div class="relative z-10">
                                <div class="w-14 h-14 bg-gradient-to-br {{ $report['color'] }} rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-gray-200">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $report['icon'] }}"></path>
                                    </svg>
                                </div>
                                
                                <h3 class="text-xl font-black text-gray-900 group-hover:text-[#853953] transition-colors mb-2">{{ $report['title'] }}</h3>
                                <p class="text-sm text-gray-500 font-medium leading-relaxed mb-6">{{ $report['desc'] }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 group-hover:text-gray-600 transition-colors">Format: {{ $report['type'] }}</span>
                                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center text-white group-hover:bg-[#853953] transition-all transform group-hover:rotate-12">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <aside class="w-full lg:w-80 flex-shrink-0">
                    <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white sticky top-6 flex flex-col min-h-[600px]">
                        
                        <div class="mb-10 px-2">
                            <h2 class="text-xl font-black text-gray-800 tracking-tighter">Recent Downloads</h2>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Available for 24 hours</p>
                        </div>

                        <div class="flex-1 space-y-8 px-2 overflow-y-auto">
                            @php
                                $history = [
                                    ['name' => 'March_Revenue.xlsx', 'time' => '2 hours ago', 'status' => 'Ready'],
                                    ['name' => 'Staff_Audit_Q1.pdf', 'time' => 'Yesterday', 'status' => 'Ready'],
                                    ['name' => 'Property_List_CDO.csv', 'time' => '2 days ago', 'status' => 'Expired'],
                                ];
                            @endphp

                            @foreach($history as $item)
                            <div class="relative group/item {{ $item['status'] == 'Expired' ? 'opacity-40' : 'cursor-pointer' }}">
                                <div class="flex items-start gap-3">
                                    <div class="mt-1 w-2 h-2 rounded-full {{ $item['status'] == 'Ready' ? 'bg-emerald-500' : 'bg-gray-400' }}"></div>
                                    <div>
                                        <p class="text-[11px] font-black text-gray-800 group-hover:text-[#853953] transition-colors leading-tight truncate w-40">{{ $item['name'] }}</p>
                                        <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase tracking-tighter">{{ $item['time'] }} • {{ $item['status'] }}</p>
                                    </div>
                                </div>
                                @if($item['status'] == 'Ready')
                                <div class="absolute right-0 top-0 text-gray-300 group-hover/item:text-[#853953] transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>




                    </div>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>