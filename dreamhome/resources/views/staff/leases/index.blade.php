<x-app-layout>
    <div class="py-12 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Lease Management</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Tracking monthly rent baselines and agreement durations.</p>
                </div>
                
                <div class="flex items-center gap-4">
                    {{-- DYNAMIC SEARCH BAR --}}
                    <form action="{{ route('staff.leases.index') }}" method="GET" class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#853953] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Lease, Street, Client..." 
                               class="bg-white border-none rounded-[1.5rem] shadow-sm focus:ring-2 focus:ring-[#853953] pl-12 h-12 w-full md:w-72 text-sm transition-all h-12">
                    </form>

                    <a href="{{ route('staff.leases.create') }}" 
                       class="bg-[#853953] text-white px-8 h-12 rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest shadow-xl shadow-pink-100 hover:bg-pink-900 hover:-translate-y-1 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        New Agreement
                    </a>
                </div>
            </div>

            {{-- LEASE AGREEMENT CARDS --}}
            <div class="space-y-6">
                @foreach($leases as $lease)
                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white hover:shadow-xl transition-all flex flex-col md:flex-row items-center gap-8 group relative">
                    
                    {{-- CLICKABLE AREA --}}
                    <a href="{{ route('staff.leases.show', $lease->leaseno) }}" class="absolute inset-0 z-0"></a>

                    <div class="w-24 h-24 bg-gradient-to-br from-[#853953] to-[#5d273a] rounded-[2rem] flex-shrink-0 flex flex-col items-center justify-center text-white shadow-lg relative z-10">
                        <span class="text-[8px] font-black uppercase tracking-widest opacity-60">Lease</span>
                        <span class="text-2xl font-black tracking-tighter">{{ $lease->leaseno }}</span>
                    </div>

                    <div class="flex-1 relative z-10">
                        <h3 class="text-2xl font-black text-gray-900 tracking-tighter group-hover:text-[#853953] transition-colors leading-tight">{{ $lease->street }}</h3>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-4">{{ $lease->city }}</p>
                        
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-gray-50 rounded-xl text-[10px] font-black text-gray-600 uppercase border border-gray-100">
                                Renter: {{ $lease->r_fname }} {{ $lease->r_lname }}
                            </span>
                            <span class="px-3 py-1 bg-gray-50 rounded-xl text-[10px] font-black text-gray-600 uppercase border border-gray-100">
                                Ends: {{ \Carbon\Carbon::parse($lease->enddate)->format('M d, Y') }}
                            </span>
                        </div>
                    </div>

                    {{-- DYNAMIC STATUS SIDEBAR --}}
                    <div class="w-full md:w-64 bg-gray-50 p-5 rounded-[1.5rem] border border-gray-100 group-hover:bg-pink-50 transition-colors relative z-10">
                        <div class="flex justify-between items-center mb-3">
                            <p class="text-[10px] font-black text-[#853953] uppercase tracking-tighter opacity-60">Current Month</p>
                            @if($lease->is_paid_this_month)
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-xl text-[9px] font-black uppercase">Paid ✓</span>
                            @else
                                <span class="px-3 py-1 bg-pink-100 text-[#853953] rounded-xl text-[9px] font-black uppercase">Pending !</span>
                            @endif
                        </div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center border-t border-gray-200 pt-2">Click to view all months</p>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="flex md:flex-col gap-3 relative z-20">
                        <a href="{{ route('staff.leases.edit', $lease->leaseno) }}" 
                        class="p-4 bg-white border border-gray-100 rounded-2xl text-gray-400 hover:text-[#853953] transition-all shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>