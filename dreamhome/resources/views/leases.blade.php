<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="py-12 bg-[#F3F4F6] min-h-screen" x-data="{ selectedDate: 'Upcoming Payment' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 tracking-tight">My Leases</h1>
                            <p class="text-sm text-gray-500 mt-1 font-medium">Review your rental agreements and contract milestones.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @php
                            // Mock data - In production, pull this using Auth::user()->leases
                            $myLeases = [
                                [
                                    'id' => 'LS23',
                                    'property' => 'Tierra Nava Standard Housing',
                                    'address' => 'Barra, Opol, Misamis Oriental, Block 09, House No. 145',
                                    'rent' => '₱40,134.99',
                                    'start' => 'January 23, 2026',
                                    'end' => 'April 23, 2026',
                                    'status' => 'Active'
                                ]
                            ];
                        @endphp

                        @forelse($myLeases as $lease)
                        <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden border border-transparent hover:border-pink-50 transition-all duration-300">
                            <div class="bg-gradient-to-r from-[#853953] to-[#5d273a] px-8 py-6 flex justify-between items-center">
                                <div>
                                    <span class="text-[10px] font-black text-pink-200 uppercase tracking-[0.2em]">Contract ID</span>
                                    <h2 class="text-white font-black text-3xl tracking-tighter leading-none">{{ $lease['id'] }}</h2>
                                </div>
                                <span class="bg-white/20 backdrop-blur-md text-white text-[10px] px-4 py-1.5 rounded-full uppercase font-black tracking-widest border border-white/30">
                                    {{ $lease['status'] }}
                                </span>
                            </div>
                            
                            <div class="p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-4">
                                        <div>
                                            <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $lease['property'] }}</h3>
                                            <p class="text-sm text-gray-500 font-medium leading-relaxed mt-1">{{ $lease['address'] }}</p>
                                        </div>
                                        
                                        <div class="inline-flex flex-col bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Monthly Commitment</span>
                                            <span class="text-2xl font-black text-[#853953]">{{ $lease['rent'] }}</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col justify-end space-y-4">
                                        <div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                                            <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center text-[#853953]">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Agreement Start</p>
                                                <p class="text-sm font-black text-gray-800">{{ $lease['start'] }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-4 bg-[#853953] p-4 rounded-2xl shadow-lg shadow-pink-100">
                                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-black text-pink-200 uppercase tracking-tighter">Agreement End</p>
                                                <p class="text-sm font-black text-white">{{ $lease['end'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-10 pt-6 border-t border-gray-50 flex flex-wrap gap-4">
                                    <button class="bg-gray-900 text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#853953] transition-all">Download PDF</button>
                                    <button class="bg-white text-gray-500 border border-gray-100 px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all">Request Renewal</button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white rounded-[2.5rem] p-12 text-center border-2 border-dashed border-gray-200">
                            <p class="text-gray-400 font-bold">You don't have any active leases yet.</p>
                            <a href="{{ route('dashboard') }}" class="text-[#853953] font-black mt-2 inline-block hover:underline">Browse Properties</a>
                        </div>
                        @endforelse
                    </div>
                </div>

                <aside class="w-full lg:w-80 flex-shrink-0">
                    <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white sticky top-6 flex flex-col min-h-[600px]">
                        <div class="mb-10 px-2">
                            <h2 class="text-xl font-black text-gray-800 tracking-tighter">Billing</h2>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Payment Schedule</p>
                        </div>

                        <div class="flex-1 space-y-8 px-2">
                            @foreach(['Upcoming Payment', 'Payment History'] as $section)
                            <div class="relative">
                                <button @click="selectedDate = (selectedDate === '{{ $section }}' ? null : '{{ $section }}')" 
                                    class="flex items-center w-full transition-all group outline-none">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center transition-all mr-3 shadow-sm"
                                         :class="selectedDate === '{{ $section }}' ? 'bg-[#853953] text-white' : 'bg-gray-50 text-gray-400'">
                                        <svg class="w-3 h-3 transition-transform duration-300" :class="selectedDate === '{{ $section }}' ? 'rotate-0' : 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-black transition-colors uppercase tracking-tighter" :class="selectedDate === '{{ $section }}' ? 'text-gray-900' : 'text-gray-400'">{{ $section }}</span>
                                </button>

                                <div x-show="selectedDate === '{{ $section }}'" x-collapse x-cloak class="mt-4 ml-3.5 pl-6 border-l border-pink-100 space-y-5">
                                    @if($section === 'Upcoming Payment')
                                        <div class="relative group/item cursor-pointer">
                                            <div class="absolute -left-[29px] top-1 w-1.5 h-1.5 rounded-full bg-[#853953] ring-4 ring-white"></div>
                                            <p class="text-[11px] font-black text-gray-800">May 01, 2026</p>
                                            <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase tracking-tighter">₱40,134.99 • Rent</p>
                                        </div>
                                    @else
                                        <div class="relative group/item opacity-60">
                                            <div class="absolute -left-[29px] top-1 w-1.5 h-1.5 rounded-full bg-emerald-500 ring-4 ring-white"></div>
                                            <p class="text-[11px] font-black text-gray-800">April 01, 2026</p>
                                            <p class="text-[9px] text-emerald-600 font-bold mt-1 uppercase tracking-tighter">Paid Successfully</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-10 pt-6 border-t border-gray-50 px-2 text-center">
                            <p class="text-[10px] font-medium text-gray-400 mb-4 italic">Issues with billing?</p>
                            <button class="w-full bg-gray-900 text-white py-4 rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-gray-200 hover:bg-[#853953] hover:shadow-pink-100 transition-all flex items-center justify-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                Contact Support
                            </button>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>