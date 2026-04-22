<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="py-12 bg-[#F3F4F6] min-h-screen" x-data="{ selectedDate: 'April 25, 2026' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="flex-1">
                    <div class="mb-8">
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">My Viewings</h1>
                        <p class="text-sm text-gray-500 mt-1 font-medium">Track your scheduled visits and review property feedback.</p>
                    </div>

                    <div class="space-y-5">
                        @php
                            // Mock data - Replace with Auth::user()->viewings in production
                            $myViewings = [
                                [
                                    'id' => 'V102',
                                    'property' => 'Tierra Nava Standard Housing',
                                    'address' => 'Barra, Opol, Misamis Oriental',
                                    'date' => 'April 21, 2026',
                                    'status' => 'Completed',
                                    'comment' => 'The property was very spacious. I am interested in the corner lot units if available.'
                                ],
                                [
                                    'id' => 'V098',
                                    'property' => 'Bria Homes Gran Europa',
                                    'address' => 'Lumbia, CDO City',
                                    'date' => 'April 15, 2026',
                                    'status' => 'Completed',
                                    'comment' => 'Quiet neighborhood. The staff was very helpful in explaining the financing options.'
                                ]
                            ];
                        @endphp

                        @foreach($myViewings as $viewing)
                        <div class="bg-white rounded-[2.5rem] p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col md:flex-row items-center gap-6 group border border-transparent hover:border-pink-50">
                            <div class="w-20 h-20 bg-gradient-to-br from-[#853953] to-[#5d273a] rounded-[1.5rem] flex-shrink-0 flex items-center justify-center text-white font-black text-xl shadow-inner">
                                {{ substr($viewing['property'], 0, 1) }}
                            </div>
                            
                            <div class="flex-1 text-center md:text-left">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                                    <h3 class="text-xl font-black text-gray-900 group-hover:text-[#853953] transition-colors tracking-tight">{{ $viewing['property'] }}</h3>
                                    <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-100">
                                        {{ $viewing['status'] }}
                                    </span>
                                </div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $viewing['address'] }}</p>
                                <p class="text-[10px] font-black text-[#853953] mt-2">{{ $viewing['date'] }}</p>
                            </div>

                            <div class="hidden md:block w-px h-12 bg-gray-100"></div>

                            <div class="flex-1 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <p class="text-[9px] font-black text-gray-400 uppercase mb-1 tracking-tighter">My Remarks</p>
                                <p class="text-xs text-gray-600 italic leading-relaxed line-clamp-2">"{{ $viewing['comment'] }}"</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <aside class="w-full lg:w-80 flex-shrink-0">
                    <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white sticky top-6 flex flex-col min-h-[600px]">
                        
                        <div class="mb-10 px-2">
                            <h2 class="text-xl font-black text-gray-900 tracking-tighter">Upcoming</h2>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Your Schedule</p>
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
                                        <div class="absolute -left-[29px] top-1 w-1.5 h-1.5 rounded-full bg-[#853953] ring-4 ring-white"></div>
                                        <p class="text-[11px] font-black text-gray-800 leading-none">Valencia Estates</p>
                                        <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase">10:00 AM • Pending</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-10 pt-6 border-t border-gray-50 px-2">
                            <a href="{{ route('home') }}" class="w-full bg-gray-900 text-white py-4 rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-gray-200 hover:bg-[#853953] hover:shadow-pink-100 transition-all flex items-center justify-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                Schedule New
                            </a>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>