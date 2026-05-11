<x-app-layout>
<style>[x-cloak] { display: none !important; }</style>

<div class="py-10 bg-[#F3F4F6] min-h-screen"
     x-data="{
        selectedSection: 'upcoming',
        showRenewal: false,
        showSupport: false
     }">

    {{-- ===== RENEWAL MODAL ===== --}}
    <div x-show="showRenewal" x-cloak
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click.self="showRenewal = false"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center px-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#853953] to-[#5d273a] px-6 py-5 flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black text-pink-200 uppercase tracking-[0.2em]">Lease Request</p>
                    <h3 class="text-white font-black text-lg tracking-tight mt-0.5">Request Renewal</h3>
                    <p class="text-pink-200/70 text-[11px] font-bold mt-0.5">Lease No. {{ $lease?->leaseno }}</p>
                </div>
                <button @click="showRenewal = false" class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white hover:bg-white/30 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            {{-- Form --}}
            <form method="POST" action="{{ route('leases.renewal') }}" class="p-6 space-y-4">
                @csrf
                {{-- Reason --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Reason for Renewal</label>
                    <div class="space-y-2">
                        @foreach([
                            'Contract Expired'        => 'My lease contract has ended and I wish to continue.',
                            'Extension Needed'        => 'I need more time before moving out.',
                            'Long-term Stay Planned'  => 'I plan to stay for a longer period.',
                            'Other'                   => 'Other reason (please specify below).',
                        ] as $value => $description)
                        <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 cursor-pointer hover:border-[#853953]/30 hover:bg-pink-50/50 transition-all has-[:checked]:border-[#853953] has-[:checked]:bg-pink-50">
                            <input type="radio" name="reason" value="{{ $value }}" class="mt-0.5 accent-[#853953] shrink-0" required>
                            <div>
                                <p class="text-xs font-black text-gray-800">{{ $value }}</p>
                                <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $description }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                {{-- Message --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Additional Message <span class="text-gray-300 normal-case font-medium">(optional)</span></label>
                    <textarea name="message" rows="3" placeholder="Add any details about your renewal request..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#853953]/30 focus:border-[#853953] transition-all resize-none"></textarea>
                </div>
                {{-- Buttons --}}
                <div class="flex gap-3 pt-1">
                    <button type="button" @click="showRenewal = false"
                        class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 bg-[#853953] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-[#6e2e44] active:scale-95 transition-all">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== CONTACT SUPPORT MODAL ===== --}}
    <div x-show="showSupport" x-cloak
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        @click.self="showSupport = false"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center px-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            
            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#853953] to-[#5d273a] px-6 py-5 flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black text-pink-200 uppercase tracking-[0.2em]">Help & Support</p>
                    <h3 class="text-white font-black text-lg tracking-tight mt-0.5">Contact Support</h3>
                    <p class="text-pink-200/70 text-[11px] font-bold mt-0.5">We'll respond as soon as possible</p>
                </div>
                <button @click="showSupport = false" class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white hover:bg-white/30 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('leases.support') }}" class="p-6 space-y-4">
                @csrf
                {{-- Issue Type --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">What is your issue?</label>
                    <div class="space-y-2">
                        @foreach([
                            'Billing Issue'        => 'Problem with payment records or running balance.',
                            'Property Maintenance' => 'Something in the property needs repair or attention.',
                            'Lease Inquiry'        => 'Questions about my lease terms or contract.',
                            'Payment Problem'      => 'Having trouble making or recording a payment.',
                            'Other'                => 'Something else not listed above.',
                        ] as $value => $description)
                        <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 cursor-pointer hover:border-[#853953]/30 hover:bg-pink-50/50 transition-all has-[:checked]:border-[#853953] has-[:checked]:bg-pink-50">
                            <input type="radio" name="issue_type" value="{{ $value }}" class="mt-0.5 accent-[#853953] shrink-0" required>
                            <div>
                                <p class="text-xs font-black text-gray-800">{{ $value }}</p>
                                <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $description }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Message --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Describe Your Issue</label>
                    <textarea name="message" rows="3" placeholder="Please describe the issue in detail..." required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#853953]/30 focus:border-[#853953] transition-all resize-none"></textarea>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 pt-1">
                    <button type="button" @click="showSupport = false"
                        class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 bg-[#853953] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-[#6e2e44] active:scale-95 transition-all">
                        Submit Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>


    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- PAGE HEADER --}}
        <div class="mb-8">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-[#853953] mb-1">DreamHome — CDO Branch</p>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">My Lease Agreement</h1>
            <p class="text-sm text-gray-400 font-medium mt-1">Your active rental contract with DreamHome.</p>
        </div>

        {{-- SUCCESS TOASTS --}}
        @if(session('renewal_success'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-bold text-emerald-700">{{ session('renewal_success') }}</p>
        </div>
        @endif
        @if(session('support_success'))
        <div class="mb-5 bg-blue-50 border border-blue-200 rounded-2xl px-5 py-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-bold text-blue-700">{{ session('support_success') }}</p>
        </div>
        @endif

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
                                @if($lease->payment_status === 'PAID')
                                    <span class="bg-emerald-500/20 backdrop-blur-sm text-emerald-300 text-[10px] px-4 py-1.5 rounded-full font-black uppercase tracking-widest border border-emerald-400/30">
                                        ✓ Fully Paid
                                    </span>
                                @elseif($lease->is_overdue)
                                    <span class="bg-red-500/20 backdrop-blur-sm text-red-300 text-[10px] px-4 py-1.5 rounded-full font-black uppercase tracking-widest border border-red-400/30">
                                        ⚠ Overdue
                                    </span>
                                @else
                                    <span class="bg-white/20 backdrop-blur-sm text-white text-[10px] px-4 py-1.5 rounded-full font-black uppercase tracking-widest border border-white/30">
                                        ● Active
                                    </span>
                                @endif
                                <span class="text-pink-200/60 text-[10px] font-bold uppercase tracking-widest">{{ $lease->duration }} {{ $lease->duration == 1 ? 'month' : 'months' }} contract</span>
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

                        {{-- SECTION 2: Lease Terms --}}
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
                                    <p class="text-sm font-black text-gray-900">{{ $lease->duration }} {{ $lease->duration == 1 ? 'month' : 'months' }}</p>
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
                            {{-- Download PDF --}}
                            <a href="{{ route('leases.pdf') }}"
                                class="flex items-center gap-2 px-5 py-3 bg-gray-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#853953] transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Download PDF
                            </a>
                            {{-- Request Renewal --}}
                            <button @click="showRenewal = true"
                                class="flex items-center gap-2 px-5 py-3 bg-white text-gray-600 border border-gray-200 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-pink-50 hover:text-[#853953] hover:border-pink-100 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Request Renewal
                            </button>
                            {{-- Contact Support --}}
                            <button @click="showSupport = true"
                                class="flex items-center gap-2 px-5 py-3 bg-white text-gray-600 border border-gray-200 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-pink-50 hover:text-[#853953] hover:border-pink-100 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                Contact Support
                            </button>
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

                        {{-- Lease Progress --}}
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

                        {{-- Balance Summary from lease_agreement --}}
                        <div class="space-y-3">
                            <div class="bg-[#F3F4F6] rounded-xl p-4 border border-gray-100">
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Total Paid</p>
                                <p class="text-base font-black text-emerald-600">&#8369;{{ number_format($lease->total_paid, 2) }}</p>
                            </div>
                            <div class="rounded-xl p-4 border {{ $lease->balance == 0 ? 'bg-emerald-50 border-emerald-100' : 'bg-pink-50 border-pink-100' }}">
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Running Balance</p>
                                <p class="text-base font-black {{ $lease->balance == 0 ? 'text-emerald-600' : 'text-[#853953]' }}">
                                    &#8369;{{ number_format($lease->balance, 2) }}
                                </p>
                                @if($lease->balance == 0)
                                <p class="text-[10px] font-black text-emerald-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Fully settled
                                </p>
                                @endif
                            </div>
                        </div>

                        <div class="border-t border-gray-50"></div>

                        {{-- Next Payment / Fully Paid --}}
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
                                </div>
                                <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                                     :class="selectedSection === 'upcoming' ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="selectedSection === 'upcoming'" x-cloak>
                                @if($lease->payment_status === 'PAID')
                                {{-- Fully Paid Badge --}}
                                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-emerald-700">Fully Paid!</p>
                                            <p class="text-[10px] text-emerald-600/70 font-bold mt-0.5">No outstanding balance remaining.</p>
                                            <p class="text-[10px] text-emerald-600/50 font-bold mt-0.5">Contract ends {{ \Carbon\Carbon::parse($lease->enddate)->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                {{-- Next Payment Info --}}
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
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Payment History --}}
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

                    </div>
                </div>
            </aside>

        </div>
        @endif

    </div>
</div>

</x-app-layout>