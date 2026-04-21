<x-app-layout>
    <div class="py-12 bg-[#F3F4F6] min-h-screen" x-data="{ selectedDate: '2026-04-07' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Viewings</h1>
                            <p class="text-sm text-gray-500 mt-1">Manage and track property visits for prospective renters.</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#853953] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </span>
                                <input type="text" placeholder="Search addresses..." class="bg-white border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-[#853953] pl-10 w-full md:w-72 text-sm transition-all">
                            </div>
                            <button class="bg-white p-2.5 rounded-2xl shadow-sm text-gray-600 hover:text-[#853953] hover:bg-pink-50 transition-all border border-transparent hover:border-pink-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-5">
                        @php
                            $mockViewings = [
                                ['id' => 'V1', 'title' => 'Tierra Nava Standard Housing', 'addr' => 'Barra, Opol, Misamis Oriental', 'date' => 'April 06, 2026', 'comment' => 'Client very interested in the kitchen layout but requested a follow-up on the water utility bill history.'],
                                ['id' => 'V2', 'title' => 'Bria Homes Gran Europa', 'addr' => 'Lumbia, CDO City, Mis Or', 'date' => 'April 04, 2026', 'comment' => 'Requested more information about the security patrol frequency in the Uptown area.']
                            ];
                        @endphp

                        @foreach($mockViewings as $viewing)
                        <div class="bg-white rounded-3xl p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-transparent hover:border-pink-50 flex flex-col md:flex-row items-center gap-6 group cursor-pointer">
                            <div class="w-24 h-24 bg-gradient-to-br from-[#853953] to-[#5d273a] rounded-2xl flex-shrink-0 flex items-center justify-center text-white font-bold text-2xl shadow-inner">
                                {{ substr($viewing['title'], 0, 1) }}
                            </div>
                            
                            <div class="flex-1 text-center md:text-left">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-[#853953] transition-colors">{{ $viewing['title'] }}</h3>
                                <p class="text-sm text-gray-500 font-medium">{{ $viewing['addr'] }}</p>
                                <div class="flex items-center justify-center md:justify-start gap-2 mt-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $viewing['date'] }}</p>
                                </div>
                            </div>

                            <div class="hidden md:block w-px h-16 bg-gray-100"></div>

                            <div class="flex-1 bg-gray-50 p-4 rounded-2xl border border-gray-100 group-hover:bg-pink-50 group-hover:border-pink-100 transition-colors">
                                <p class="text-[10px] font-black text-[#853953] uppercase mb-1 tracking-tighter opacity-60">Staff Feedback</p>
                                <p class="text-xs text-gray-600 italic leading-relaxed">{{ $viewing['comment'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            <div class="py-12 bg-[#F3F4F6] min-h-screen" x-data="{ selectedDate: 'April 07, 2026' }">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="flex flex-col lg:flex-row gap-8 items-start">
                            
                            <div class="flex-1">
                                </div>

                            <aside class="w-full lg:w-80 flex-shrink-0">
                                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white sticky top-6 flex flex-col min-h-[600px]">
                                    
                                    <div class="mb-8">
                                        <h2 class="text-xl font-black text-gray-800 tracking-tighter">Timeline</h2>
                                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Upcoming Viewings</p>
                                    </div>

                                    <div class="flex-1 space-y-8">
                                        @foreach(['April 07, 2026', 'April 08, 2026', 'April 09, 2026'] as $date)
                                        <div class="relative">
                                            <button @click="selectedDate = (selectedDate === '{{ $date }}' ? null : '{{ $date }}')" 
                                                class="flex items-center w-full transition-all group outline-none">
                                                <div class="w-7 h-7 rounded-full flex items-center justify-center transition-all mr-3 shadow-sm"
                                                    :class="selectedDate === '{{ $date }}' ? 'bg-[#853953] text-white' : 'bg-gray-50 text-gray-400'">
                                                    <svg class="w-3 h-3 transition-transform duration-300" :class="selectedDate === '{{ $date }}' ? 'rotate-0' : 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-black transition-colors" :class="selectedDate === '{{ $date }}' ? 'text-gray-900' : 'text-gray-400'">{{ $date }}</span>
                                            </button>

                                            <div x-show="selectedDate === '{{ $date }}'" x-collapse x-cloak class="mt-4 ml-3.5 pl-6 border-l border-pink-100 space-y-5">
                                                <div class="relative group/item cursor-pointer">
                                                    <div class="absolute -left-[29px] top-1 w-1.5 h-1.5 rounded-full bg-[#853953] ring-4 ring-white"></div>
                                                    <p class="text-[11px] font-black text-gray-800 group-hover:text-[#853953] transition-colors leading-none">Tierra Nava Standard</p>
                                                    <p class="text-[9px] text-gray-400 font-bold mt-1">10:00 AM • Staff: Jed</p>
                                                </div>
                                                
                                                <div class="relative group/item cursor-pointer">
                                                    <div class="absolute -left-[29px] top-1 w-1.5 h-1.5 rounded-full bg-pink-200 ring-4 ring-white group-hover:bg-[#853953] transition-colors"></div>
                                                    <p class="text-[11px] font-black text-gray-800 group-hover:text-[#853953] transition-colors leading-none">Bria Homes Europa</p>
                                                    <p class="text-[9px] text-gray-400 font-bold mt-1">02:30 PM • Staff: Alice</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-10 pt-6 border-t border-gray-50">
                                        <button class="w-full bg-[#853953] text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-pink-100 hover:bg-pink-900 hover:shadow-pink-200 transition-all flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                            New Viewing
                                        </button>
                                    </div>
                                </div>
                            </aside>

                        </div>
                    </div>
                </div>

                        </div>
                    </div>
                </div>
</x-app-layout>