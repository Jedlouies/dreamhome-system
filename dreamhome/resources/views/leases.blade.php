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

<<<<<<< Updated upstream
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
=======
            {{-- PAGE HEADER --}}
            <div class="mb-8">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-[#853953] mb-1">DreamHome — CDO Branch</p>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">My Lease Agreement</h1>
                <p class="text-sm text-gray-400 font-medium mt-1">Your active rental contract with DreamHome.</p>
            </div>

            @if(!$lease)
            {{-- EMPTY STATE --}}
            <div class="bg-white rounded-2xl p-16 text-center border-2 border-dashed border-gray-200">
                <div class="w-16 h-16 bg-pink-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#853953]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-gray-800 font-black text-lg">No Active Lease</p>
                <p class="text-gray-400 font-medium text-sm mt-1">
                    @if(!Auth::user()->renterno)
                        Your account is not yet linked to a renter record. Please contact DreamHome staff.
                    @else
                        You don't have any lease agreements yet.
                    @endif
                </p>
                <a href="{{ route('home') }}" class="inline-block mt-5 px-5 py-2.5 bg-[#853953] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-[#6e2e44] transition-all">
                    Browse Properties
                </a>
            </div>

            @else
            <div class="flex flex-col lg:flex-row gap-6 items-start">

                {{-- MAIN LEASE DOCUMENT --}}
                <div class="flex-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                        {{-- Document Header --}}
                        <div class="bg-gradient-to-r from-[#853953] to-[#5d273a] px-8 py-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-pink-200 uppercase tracking-[0.2em]">Lease Agreement</p>
                                        <h2 class="text-2xl font-black text-white tracking-tight leading-none mt-0.5">No. {{ $lease->leaseno }}</h2>
                                        <p class="text-pink-200/70 text-xs font-bold mt-1">DreamHome CDO Branch — B001</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <span class="bg-white/20 backdrop-blur-sm text-white text-[10px] px-4 py-1.5 rounded-full font-black uppercase tracking-widest border border-white/30">
                                        ● Active
                                    </span>
                                    <span class="text-pink-200/60 text-[10px] font-bold uppercase tracking-widest">{{ $lease->duration }} contract</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">

                            {{-- SECTION 1: Property Details --}}
                            <div class="mb-7">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="w-1 h-4 bg-[#853953] rounded-full"></span>
                                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Property Details</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Property No.</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease->propertyno }}</p>
                                    </div>
                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Type</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease->property_type }}</p>
                                    </div>
                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100 sm:col-span-2">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Full Address</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease->street }}, {{ $lease->area }}, {{ $lease->city }} {{ $lease->postcode }}</p>
                                    </div>
                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">No. of Rooms</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease->no_of_rooms }} Rooms</p>
                                    </div>
                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Renter Name</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease->renter_name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-dashed border-gray-200 mb-7"></div>

                            {{-- SECTION 2: Lease Terms (all case study fields) --}}
                            <div class="mb-7">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="w-1 h-4 bg-[#853953] rounded-full"></span>
                                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Lease Terms</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Monthly Rent</p>
                                        <p class="text-lg font-black text-[#853953]">&#8369;{{ number_format($lease->monthly_rent, 2) }}</p>
                                    </div>

                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Method of Payment</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease->paymentmethod }}</p>
                                    </div>

                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Lease Duration</p>
                                        <p class="text-sm font-black text-gray-900">{{ $lease->duration }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold mt-0.5">Min 3 months · Max 1 year</p>
                                    </div>

                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Rental Deposit</p>
                                        <p class="text-sm font-black text-gray-900">&#8369;{{ number_format($lease->deposit, 2) }}</p>
                                    </div>

                                    <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Deposit Paid</p>
                                        @if($lease->isdepositpaid)
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
                                        <p class="text-sm font-black text-gray-900">{{ $lease->staff_name }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold mt-0.5">Staff No. {{ $lease->staffno }}</p>
                                    </div>

                                </div>
                            </div>

                            <div class="border-t border-dashed border-gray-200 mb-7"></div>

                            {{-- SECTION 3: Contract Period --}}
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
                                            <p class="text-sm font-black text-gray-900 mt-0.5">{{ \Carbon\Carbon::parse($lease->startdate)->format('F d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 bg-[#853953] rounded-xl p-4">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-pink-200">Rent End Date</p>
                                            <p class="text-sm font-black text-white mt-0.5">{{ \Carbon\Carbon::parse($lease->enddate)->format('F d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-dashed border-gray-200 mb-6"></div>

                            {{-- ACTION BUTTONS --}}
                            <div class="flex flex-wrap gap-3">
                                <button class="flex items-center gap-2 px-5 py-3 bg-gray-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#853953] transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Download PDF
                                </button>
                                <button class="flex items-center gap-2 px-5 py-3 bg-white text-gray-600 border border-gray-200 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-pink-50 hover:text-[#853953] hover:border-pink-100 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Request Renewal
                                </button>
                                <a href="{{ route('home') }}" class="flex items-center gap-2 px-5 py-3 bg-white text-gray-600 border border-gray-200 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-pink-50 hover:text-[#853953] hover:border-pink-100 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    View Property Details
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- SIDEBAR --}}
                <aside class="w-full lg:w-72 shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">

                        <div class="px-6 py-5 border-b border-gray-50">
                            <h2 class="text-sm font-black text-gray-900 tracking-tight">Billing Overview</h2>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Payment Schedule</p>
                        </div>

                        <div class="p-6 space-y-6">

                            {{-- Lease Progress Bar --}}
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
                                    <span class="text-[9px] font-bold text-gray-400">{{ \Carbon\Carbon::parse($lease->startdate)->format('M d, Y') }}</span>
                                    <span class="text-[9px] font-bold text-gray-400">{{ \Carbon\Carbon::parse($lease->enddate)->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-50"></div>

                            {{-- Running Balance (from payment table) --}}
                            @if($payments->isNotEmpty())
                            <div class="bg-pink-50 border border-pink-100 rounded-xl p-4">
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Running Balance</p>
                                <p class="text-lg font-black text-[#853953]">&#8369;{{ number_format($payments->first()->running_balance, 2) }}</p>
                                <p class="text-[10px] text-gray-400 font-bold mt-1">as of {{ \Carbon\Carbon::parse($payments->first()->payment_date)->format('M d, Y') }}</p>
                            </div>
                            @endif

                            {{-- Upcoming Payment --}}
                            <div>
                                <button @click="selectedSection = (selectedSection === 'upcoming' ? null : 'upcoming')"
                                    class="flex items-center justify-between w-full outline-none mb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center transition-all"
                                             :class="selectedSection === 'upcoming' ? 'bg-[#853953] text-white' : 'bg-gray-100 text-gray-400'">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <span class="text-xs font-black uppercase tracking-wider"
                                              :class="selectedSection === 'upcoming' ? 'text-gray-900' : 'text-gray-400'">Next Payment</span>
>>>>>>> Stashed changes
                                    </div>
                                    <span class="text-xs font-black transition-colors uppercase tracking-tighter" :class="selectedDate === '{{ $section }}' ? 'text-gray-900' : 'text-gray-400'">{{ $section }}</span>
                                </button>
<<<<<<< Updated upstream

                                <div x-show="selectedDate === '{{ $section }}'" x-collapse x-cloak class="mt-4 ml-3.5 pl-6 border-l border-pink-100 space-y-5">
                                    @if($section === 'Upcoming Payment')
                                        <div class="relative group/item cursor-pointer">
                                            <div class="absolute -left-[29px] top-1 w-1.5 h-1.5 rounded-full bg-[#853953] ring-4 ring-white"></div>
                                            <p class="text-[11px] font-black text-gray-800">May 01, 2026</p>
                                            <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase tracking-tighter">₱40,134.99 • Rent</p>
=======
                                <div x-show="selectedSection === 'upcoming'" x-cloak>
                                    <div class="bg-pink-50 border border-pink-100 rounded-xl p-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-2 h-2 rounded-full bg-[#853953] mt-1.5 shrink-0 animate-pulse"></div>
                                            <div>
                                                <p class="text-xs font-black text-gray-900">
                                                    {{ \Carbon\Carbon::parse($lease->startdate)->addMonth()->format('M d, Y') }}
                                                </p>
                                                <p class="text-[10px] font-black text-[#853953] mt-0.5">&#8369;{{ number_format($lease->monthly_rent, 2) }}</p>
                                                <p class="text-[10px] text-gray-400 font-bold mt-1">Via {{ $lease->paymentmethod }}</p>
                                            </div>
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
                        <div class="mt-10 pt-6 border-t border-gray-50 px-2 text-center">
                            <p class="text-[10px] font-medium text-gray-400 mb-4 italic">Issues with billing?</p>
                            <button class="w-full bg-gray-900 text-white py-4 rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-gray-200 hover:bg-[#853953] hover:shadow-pink-100 transition-all flex items-center justify-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                Contact Support
                            </button>
=======
                            {{-- Payment History (real data from payment table) --}}
                            <div>
                                <button @click="selectedSection = (selectedSection === 'history' ? null : 'history')"
                                    class="flex items-center justify-between w-full outline-none mb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center transition-all"
                                             :class="selectedSection === 'history' ? 'bg-[#853953] text-white' : 'bg-gray-100 text-gray-400'">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        </div>
                                        <span class="text-xs font-black uppercase tracking-wider"
                                              :class="selectedSection === 'history' ? 'text-gray-900' : 'text-gray-400'">
                                            Payment History
                                            @if($payments->isNotEmpty())
                                                <span class="ml-1 text-[#853953]">({{ $payments->count() }})</span>
                                            @endif
                                        </span>
                                    </div>
                                    <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                                         :class="selectedSection === 'history' ? 'rotate-180' : ''"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="selectedSection === 'history'" x-cloak>
                                    @if($payments->isEmpty())
                                        <p class="text-xs text-gray-400 font-medium text-center py-4">No payments recorded yet.</p>
                                    @else
                                        <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                                            @foreach($payments as $payment)
                                            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div class="flex items-start gap-2">
                                                        <div class="w-2 h-2 rounded-full bg-emerald-500 mt-1.5 shrink-0"></div>
                                                        <div>
                                                            <p class="text-[10px] font-black text-gray-900">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</p>
                                                            <p class="text-[10px] font-black text-emerald-600 mt-0.5">&#8369;{{ number_format($payment->amount_paid, 2) }} ✓</p>
                                                            @if($payment->notes)
                                                                <p class="text-[9px] text-gray-400 mt-0.5 italic">{{ $payment->notes }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <span class="text-[9px] text-gray-400 font-bold shrink-0">{{ $payment->payment_method }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="border-t border-gray-50"></div>

                            {{-- Contact Support --}}
                            <div class="text-center">
                                <p class="text-[10px] font-medium text-gray-400 mb-3 italic">Issues with billing?</p>
                                <button class="w-full flex items-center justify-center gap-2 py-3 bg-gray-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#853953] transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    Contact Support
                                </button>
                            </div>

>>>>>>> Stashed changes
                        </div>
                    </div>
                </aside>

            </div>
            @endif

        </div>
    </div>
</x-app-layout>