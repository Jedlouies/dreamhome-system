<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="py-12 bg-[#F3F4F6] min-h-screen" x-data="{ selectedDate: 'April 25, 2026' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Inspections</h1>
                            <p class="text-sm text-gray-500 mt-1 font-medium">Detailed property evaluation records and maintenance tracking.</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#853953] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" placeholder="Search Property No or Staff..." class="bg-white border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-[#853953] pl-10 w-full md:w-72 text-sm transition-all">
                            </div>
                            <button class="bg-white p-2.5 rounded-2xl shadow-sm text-gray-600 hover:text-[#853953] hover:bg-pink-50 transition-all border border-transparent hover:border-pink-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-5">
                        @php
                            // Mock data - replace with $inspections from your controller
                            $inspections = [
                                [
                                    'id' => 'INSP-772',
                                    'property' => 'Tierra Nava (P001)',
                                    'staff' => 'S001 (Jed Camara)',
                                    'date' => 'April 21, 2026',
                                    'evaluation' => 'Property is in excellent condition. Minor paint touch-ups required in the master bedroom. Plumbing systems verified and pressure tested.'
                                ],
                                [
                                    'id' => 'INSP-104',
                                    'property' => 'Bria Homes (P002)',
                                    'staff' => 'S002 (Alice Guo)',
                                    'date' => 'April 15, 2026',
                                    'evaluation' => 'Electrical wiring inspected and passed safety standards. Garden maintenance needed to prevent overgrowth into gutters.'
                                ]
                            ];
                        @endphp

                        @foreach($inspections as $item)
                        <div class="bg-white rounded-[2.5rem] p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-transparent hover:border-pink-50 flex flex-col md:flex-row items-center gap-6 group cursor-pointer">
                            <div class="w-24 h-24 bg-gradient-to-br from-[#853953] to-[#5d273a] rounded-[2rem] flex-shrink-0 flex items-center justify-center shadow-inner">
                                <svg class="w-10 h-10 text-white opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            
                            <div class="flex-1 text-center md:text-left">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                                    <h3 class="text-xl font-black text-gray-900 group-hover:text-[#853953] transition-colors tracking-tight">{{ $item['property'] }}</h3>
                                    <span class="inline-block px-3 py-1 bg-pink-50 text-[#853953] text-[10px] font-black uppercase tracking-widest rounded-full border border-pink-100">{{ $item['id'] }}</span>
                                </div>
                                <p class="text-xs font-bold text-gray-500">Inspected by: <span class="text-gray-900 font-black">{{ $item['staff'] }}</span></p>
                                <div class="flex items-center justify-center md:justify-start gap-2 mt-3">
                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $item['date'] }}</p>
                                </div>
                            </div>

                            <div class="hidden md:block w-px h-16 bg-gray-100"></div>

                            <div class="flex-1 bg-gray-50 p-5 rounded-3xl border border-gray-100 group-hover:bg-pink-50 group-hover:border-pink-100 transition-colors">
                                <p class="text-[9px] font-black text-[#853953]/60 uppercase mb-2 tracking-tighter">Evaluation Report</p>
                                <p class="text-xs text-gray-600 italic leading-relaxed line-clamp-3">"{{ $item['evaluation'] }}"</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <aside class="w-full lg:w-80 flex-shrink-0">
                    <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white sticky top-6 flex flex-col min-h-[600px]">
                        
                        <div class="mb-10 px-2">
                            <h2 class="text-xl font-black text-gray-800 tracking-tighter">Pending</h2>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Upcoming Reviews</p>
                        </div>

                        <div class="flex-1 space-y-8 px-2">
                            @foreach(['April 25, 2026', 'April 28, 2026'] as $date)
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
                                    <div class="relative group/item cursor-pointer">
                                        <div class="absolute -left-[29px] top-1 w-1.5 h-1.5 rounded-full bg-[#853953] ring-4 ring-white group-hover:scale-125 transition-transform"></div>
                                        <p class="text-[11px] font-black text-gray-700 group-hover:text-[#853953] transition-colors leading-none">Valencia Estates (P012)</p>
                                        <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase tracking-tighter">Full Structural Check</p>
                                    </div>
                                    
                                    <div class="relative group/item cursor-pointer">
                                        <div class="absolute -left-[29px] top-1 w-1.5 h-1.5 rounded-full bg-pink-200 ring-4 ring-white group-hover:bg-[#853953] transition-colors"></div>
                                        <p class="text-[11px] font-black text-gray-700 group-hover:text-[#853953] transition-colors leading-none">Gran Europa (P034)</p>
                                        <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase tracking-tighter">Post-Lease Review</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-10 pt-6 border-t border-gray-50 px-2">
                            <button class="w-full bg-[#853953] text-white py-4 rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-gray-200 hover:bg-pink-900 hover:shadow-pink-100 transition-all flex items-center justify-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Schedule Review
                            </button>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>