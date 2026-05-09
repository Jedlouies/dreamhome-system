<x-app-layout>
    <style>[x-cloak] { display: none !important; }</style>

    <div class="py-10 bg-[#F3F4F6] min-h-screen" x-data="{ selectedSection: 'upcoming' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ===== PAGE HEADER ===== --}}
            <div class="mb-8">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-[#853953] mb-1">DreamHome — CDO Branch</p>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">My Lease Agreements</h1>
                <p class="text-sm text-gray-400 font-medium mt-1">Your active and past rental contracts with DreamHome.</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-6 items-start">

                {{-- ===== MAIN LEASE DOCUMENT AREA ===== --}}
                <div class="flex-1 space-y-6">

                    @php
                        $myLeases = [
                            [
                                // Case study required fields
                                'lease_no'        => 'LS23',
                                'monthly_rent'    => '40,134.99',
                                'payment_method'  => 'Bank Transfer',
                                'deposit'         => '80,269.98',
                                'deposit_paid'    => true,
                                'start'           => 'January 23, 2026',
                                'end'             => 'April 23, 2026',
                                'duration'        => '3 months',
                                'staff_name'      => 'Ann Beech',
                                'staff_no'        => 'SL21',
                                'status'          => 'Active',
                                // Added features
                                'property_no'     => 'PC001',
                                'property_name'   => 'Tierra Nava Modern',
                                'street'          => '6 Lawrence St.',
                                'area'            => 'Patag',
                                'city'            => 'Cagayan de Oro City',
                                'postcode'        => '9000',
                                'type'            => 'Flat',
                                'rooms'           => 3,
                                'progress'        => 60,
                            ],
                        ];
                    @endphp

                    @forelse($myLeases as $lease)

                    {{-- ===== LEASE CONTRACT DOCUMENT ===== --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                        {{-- Document Header --}}
                        <div class="bg-gradient-to-r from-[#853953] to-[#5d273a] px-8 py-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-pink-200 uppercase tracking-[0.2em]">Lease Agreement</p>
                                        <h2 class="text-2xl font-black text-white tracking-tight leading-none mt-0.5">No. {{ $lease['lease_no'] }}</h2>
                                        <p class="text-pink-200/70 text-xs font-bold mt-1">DreamHome CDO Branch — B01</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <span class="bg-white/20 backdrop-blur-sm text-white text-[10px] px-4 py-1.5 rounded-full font-black uppercase tracking-widest border border-white/30">
                                        ● {{ $lease['status'] }}
                                    </span>
                                    <span class="text-pink-200/60 text-[10px] font-bold uppercase tracking-widest">{{ $lease['duration'] }} contract</span>
                                </div>
                            </div>
                        </div>

                        {{-- Document Body --}}
                        <div class="p-8">

                            {{-- Section 1: Property Details --}}
                            <div class="mb-7">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="w-1 h-4 bg-[#853953] rounded-full"></span>
                                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Property Details</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Property No.</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease['property_no'] }}</p>
                                    </div>
                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Property Name</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease['property_name'] }}</p>
                                    </div>
                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100 sm:col-span-2">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Full Address</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease['street'] }}, {{ $lease['area'] }}, {{ $lease['city'] }} {{ $lease['postcode'] }}</p>
                                    </div>
                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Type</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease['type'] }}</p>
                                    </div>
                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">No. of Rooms</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease['rooms'] }} Rooms</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Divider --}}
                            <div class="border-t border-dashed border-gray-200 mb-7"></div>

                            {{-- Section 2: Lease Terms (case study fields) --}}
                            <div class="mb-7">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="w-1 h-4 bg-[#853953] rounded-full"></span>
                                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Lease Terms</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Monthly Rent</p>
                                        <p class="text-lg font-black text-[#853953]">&#8369;{{ $lease['monthly_rent'] }}</p>
                                    </div>

                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Method of Payment</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease['payment_method'] }}</p>
                                    </div>

                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Lease Duration</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease['duration'] }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold mt-0.5">Min 3 months · Max 1 year</p>
                                    </div>

                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Rental Deposit</p>
                                        <p class="text-sm font-black text-gray-900">&#8369;{{ $lease['deposit'] }}</p>
                                    </div>

                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Deposit Paid</p>
                                        @if($lease['deposit_paid'])
                                            <span class="inline-flex items-center gap-1.5 text-emerald-600 font-black text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                Yes — Paid
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-red-500 font-black text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Not Paid
                                            </span>
                                        @endif
                                    </div>

                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Arranged by Staff</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease['staff_name'] }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold mt-0.5">Staff No. {{ $lease['staff_no'] }}</p>
                                    </div>

                                </div>
                            </div>

                            {{-- Divider --}}
                            <div class="border-t border-dashed border-gray-200 mb-7"></div>

                            {{-- Section 3: Contract Period --}}
                            <div class="mb-7">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="w-1 h-4 bg-[#853953] rounded-full"></span>
                                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Contract Period</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex items-center gap-4 bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-[#853953]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Rent Start Date</p>
                                            <p class="text-sm font-black text-gray-900 mt-0.5">{{ $lease['start'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 bg-[#853953] rounded-xl p-4">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-pink-200">Rent End Date</p>
                                            <p class="text-sm font-black text-white mt-0.5">{{ $lease['end'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Divider --}}
                            <div class="border-t border-dashed border-gray-200 mb-6"></div>

                            {{-- Action Buttons --}}
                            <div class="flex flex-wrap gap-3">
                                <button class="flex items-center gap-2 px-5 py-3 bg-gray-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#853953] transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Download PDF
                                </button>
                                <button class="flex items-center gap-2 px-5 py-3 bg-white text-gray-600 border border-gray-200 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-pink-50 hover:text-[#853953] hover:border-pink-100 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Request Renewal
                                </button>
                                <button class="flex items-center gap-2 px-5 py-3 bg-white text-gray-600 border border-gray-200 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-pink-50 hover:text-[#853953] hover:border-pink-100 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    View Property Details
                                </button>
                            </div>

                        </div>{{-- end document body --}}
                    </div>

                    @empty
                    <div class="bg-white rounded-2xl p-12 text-center border-2 border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-pink-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-[#853953]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-gray-800 font-black text-lg">No Active Leases</p>
                        <p class="text-gray-400 font-medium text-sm mt-1">You don't have any lease agreements yet.</p>
                        <a href="{{ route('home') }}" class="inline-block mt-4 px-5 py-2.5 bg-[#853953] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-[#6e2e44] transition-all">
                            Browse Properties
                        </a>
                    </div>
                    @endforelse

                </div>

                {{-- ===== SIDEBAR ===== --}}
                <aside class="w-full lg:w-72 shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">

                        {{-- Sidebar Header --}}
                        <div class="px-6 py-5 border-b border-gray-50">
                            <h2 class="text-sm font-black text-gray-900 tracking-tight">Billing Overview</h2>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Payment Schedule</p>
                        </div>

                        <div class="p-6 space-y-6">

                            {{-- Lease Duration Progress Bar (added feature) --}}
                            @isset($myLeases[0])
                            @php $progress = $myLeases[0]['progress']; @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Lease Progress</p>
                                    <span class="text-[10px] font-black text-[#853953]">{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="h-2.5 rounded-full bg-gradient-to-r from-[#853953] to-[#c4677e] transition-all duration-700"
                                         style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="flex justify-between mt-1.5">
                                    <span class="text-[9px] font-bold text-gray-400">{{ $myLeases[0]['start'] }}</span>
                                    <span class="text-[9px] font-bold text-gray-400">{{ $myLeases[0]['end'] }}</span>
                                </div>
                            </div>
                            @endisset

                            {{-- Divider --}}
                            <div class="border-t border-gray-50"></div>

                            {{-- Upcoming Payment --}}
                            <div>
                                <button @click="selectedSection = (selectedSection === 'upcoming' ? null : 'upcoming')"
                                    class="flex items-center justify-between w-full outline-none mb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all"
                                             :class="selectedSection === 'upcoming' ? 'bg-[#853953] text-white' : 'bg-gray-100 text-gray-400'">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <span class="text-xs font-black uppercase tracking-wider"
                                              :class="selectedSection === 'upcoming' ? 'text-gray-900' : 'text-gray-400'">
                                            Upcoming Payment
                                        </span>
                                    </div>
                                    <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                                         :class="selectedSection === 'upcoming' ? 'rotate-180' : ''"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div x-show="selectedSection === 'upcoming'" x-collapse x-cloak>
                                    <div class="bg-pink-50 border border-pink-100 rounded-xl p-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-2 h-2 rounded-full bg-[#853953] mt-1.5 shrink-0 animate-pulse"></div>
                                            <div>
                                                <p class="text-xs font-black text-gray-900">May 01, 2026</p>
                                                <p class="text-[10px] font-black text-[#853953] mt-0.5">&#8369;40,134.99 — Monthly Rent</p>
                                                <p class="text-[10px] text-gray-400 font-bold mt-1">Via Bank Transfer</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment History --}}
                            <div>
                                <button @click="selectedSection = (selectedSection === 'history' ? null : 'history')"
                                    class="flex items-center justify-between w-full outline-none mb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all"
                                             :class="selectedSection === 'history' ? 'bg-[#853953] text-white' : 'bg-gray-100 text-gray-400'">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        </div>
                                        <span class="text-xs font-black uppercase tracking-wider"
                                              :class="selectedSection === 'history' ? 'text-gray-900' : 'text-gray-400'">
                                            Payment History
                                        </span>
                                    </div>
                                    <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                                         :class="selectedSection === 'history' ? 'rotate-180' : ''"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div x-show="selectedSection === 'history'" x-collapse x-cloak>
                                    <div class="space-y-2">
                                        @foreach([
                                            ['date' => 'April 01, 2026', 'amount' => '40,134.99'],
                                            ['date' => 'March 01, 2026', 'amount' => '40,134.99'],
                                            ['date' => 'February 01, 2026', 'amount' => '40,134.99'],
                                        ] as $payment)
                                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3 flex items-start gap-3">
                                            <div class="w-2 h-2 rounded-full bg-emerald-500 mt-1.5 shrink-0"></div>
                                            <div>
                                                <p class="text-[10px] font-black text-gray-900">{{ $payment['date'] }}</p>
                                                <p class="text-[10px] font-black text-emerald-600 mt-0.5">&#8369;{{ $payment['amount'] }} — Paid ✓</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Divider --}}
                            <div class="border-t border-gray-50"></div>

                            {{-- Contact Support --}}
                            <div class="text-center">
                                <p class="text-[10px] font-medium text-gray-400 mb-3 italic">Issues with billing?</p>
                                <button class="w-full flex items-center justify-center gap-2 py-3 bg-gray-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#853953] transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    Contact Support
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>

</x-app-layout>