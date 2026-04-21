<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="py-12 bg-[#F3F4F6] min-h-screen" x-data="{ selectedDate: 'April 07, 2026' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Leases</h1>
                            <p class="text-sm text-gray-500 mt-1 font-medium">Manage and monitor active rental agreements.</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#853953] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" placeholder="Search Lease ID or Renter..." class="bg-white border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-[#853953] pl-10 w-full md:w-72 text-sm transition-all">
                            </div>
                            <button class="bg-white p-2.5 rounded-2xl shadow-sm text-gray-600 hover:text-[#853953] hover:bg-pink-50 transition-all border border-transparent hover:border-pink-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            // Mock data for display - replace with $leases from your controller
                            $mockLeases = [
                                [
                                    'id' => 'LS23',
                                    'name' => 'Camara, Jed Louies O.',
                                    'renter_no' => 'CR78',
                                    'property' => 'Tierra Nava Standard Housing',
                                    'address' => 'Barra, Opol, Mis Or, Block 09, House No. 145',
                                    'rent' => '₱40,134.99',
                                    'start' => 'Jan 23, 2026',
                                    'end' => 'Apr 23, 2026'
                                ],
                                [
                                    'id' => 'LS912',
                                    'name' => 'Chembee, Regaton O.',
                                    'renter_no' => 'CR10',
                                    'property' => 'Bria Homes Gran Europa',
                                    'address' => 'Lumbia, CDO City, Mis Or, Block 12, House No. 034',
                                    'rent' => '₱10,134.99',
                                    'start' => 'Feb 10, 2026',
                                    'end' => 'Dec 12, 2026'
                                ]
                            ];
                        @endphp

                        @foreach($mockLeases as $lease)
                        <div class="bg-white rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group cursor-pointer border border-transparent hover:border-pink-50">
                            <div class="bg-gradient-to-r from-[#853953] to-[#5d273a] px-6 py-4 flex justify-between items-center">
                                <span class="text-white font-black text-2xl tracking-tighter">{{ $lease['id'] }}</span>
                                <span class="bg-white/20 text-white text-[10px] px-3 py-1 rounded-full uppercase font-black tracking-widest backdrop-blur-sm">Active</span>
                            </div>
                            
                            <div class="p-6">
                                <div class="flex items-center gap-4 mb-6">
                                    <img class="w-14 h-14 rounded-2xl shadow-inner border-2 border-gray-50 object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($lease['name']) }}&background=853953&color=fff" alt="Renter">
                                    <div>
                                        <h3 class="text-base font-black text-gray-900 group-hover:text-[#853953] transition-colors">{{ $lease['name'] }}</h3>
                                        <p class="text-xs font-bold text-gray-400">Renter No. {{ $lease['renter_no'] }}</p>
                                    </div>
                                </div>

                                <div class="space-y-1 mb-6">
                                    <h4 class="text-sm font-black text-gray-800">{{ $lease['property'] }}</h4>
                                    <p class="text-[11px] text-gray-500 font-medium leading-relaxed">{{ $lease['address'] }}</p>
                                    <div class="pt-2">
                                        <span class="text-lg font-black text-[#853953]">{{ $lease['rent'] }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase ml-1">/ Monthly</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 border-t border-gray-50 pt-4">
                                    <div class="bg-gray-50 p-3 rounded-2xl">
                                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1 text-center">Start Date</p>
                                        <p class="text-xs font-black text-gray-700 text-center">{{ $lease['start'] }}</p>
                                    </div>
                                    <div class="bg-pink-50/50 p-3 rounded-2xl">
                                        <p class="text-[8px] font-black text-[#853953]/60 uppercase tracking-widest mb-1 text-center">End Date</p>
                                        <p class="text-xs font-black text-[#853953] text-center">{{ $lease['end'] }}</p>
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
                            <h2 class="text-xl font-black text-gray-800 tracking-tighter">Contract Alerts</h2>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Upcoming Expirations</p>
                        </div>

                        <div class="flex-1 space-y-8 px-2">
                            @foreach(['April 07, 2026', 'April 08, 2026', 'April 09, 2026'] as $date)
                            <div class="relative">
                                <button @click="selectedDate = (selectedDate === '{{ $date }}' ? null : '{{ $date }}')" 
                                    class="flex items-center w-full transition-all group outline-none">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center transition-all mr-3 shadow-sm"
                                         :class="selectedDate === '{{ $date }}' ? 'bg-[#853953] text-white' : 'bg-gray-100 text-gray-400'">
                                        <svg class="w-3 h-3 transition-transform duration-300" :class="selectedDate === '{{ $date }}' ? 'rotate-0' : 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-black transition-colors" :class="selectedDate === '{{ $date }}' ? 'text-gray-900' : 'text-gray-400'">{{ $date }}</span>
                                </button>

                                <div x-show="selectedDate === '{{ $date }}'" x-collapse x-cloak class="mt-4 ml-3.5 pl-6 border-l border-pink-100 space-y-5">
                                    @php $mockLeaseIds = ['LS752', 'LS0923', 'LS81']; @endphp
                                    @foreach($mockLeaseIds as $id)
                                    <div class="relative group/item cursor-pointer">
                                        <div class="absolute -left-[29px] top-1 w-1.5 h-1.5 rounded-full bg-[#853953] ring-4 ring-white group-hover:scale-125 transition-transform"></div>
                                        
                                        <div class="flex items-center justify-between">
                                            <p class="text-[11px] font-black text-gray-700 group-hover:text-[#853953] transition-colors leading-none">{{ $id }}</p>
                                            <svg class="w-3 h-3 text-gray-300 opacity-0 group-hover/item:opacity-100 transition-all translate-x-[-4px] group-hover/item:translate-x-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase tracking-tighter">Expiring Soon</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-10 pt-6 border-t border-gray-50 px-2">
                            <button class="w-full bg-[#853953] text-white py-4 rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-gray-200 hover:bg-pink-900 hover:shadow-pink-100 transition-all flex items-center justify-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Draft Lease
                            </button>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>