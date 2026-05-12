<x-app-layout>
    <div class="py-12 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('staff.leases.index') }}" class="p-3 bg-white rounded-2xl shadow-sm text-gray-400 hover:text-[#853953] transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Lease Baseline Breakdown</h1>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                {{-- Lease Info --}}
                <div class="flex-1 space-y-6">
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-white">
                        <h2 class="text-3xl font-black text-gray-900 tracking-tighter">{{ $lease->street }}</h2>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-8">{{ $lease->city }} | Agreement {{ $lease->leaseno }}</p>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100 text-center">
                                <p class="text-[9px] font-black text-[#853953] uppercase mb-1">Contract Start</p>
                                <p class="text-sm font-black text-gray-800">{{ \Carbon\Carbon::parse($lease->startdate)->format('F d, Y') }}</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100 text-center">
                                <p class="text-[9px] font-black text-[#853953] uppercase mb-1">Contract End</p>
                                <p class="text-sm font-black text-gray-800">{{ \Carbon\Carbon::parse($lease->enddate)->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Timeline Sidebar --}}
                <aside class="w-full lg:w-96 shrink-0 sticky top-6">
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-white">
                        <h3 class="text-xl font-black text-gray-800 tracking-tighter mb-6">Payment Schedule</h3>
                        <div class="space-y-6 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($schedule as $item)
                            <div class="relative pl-10 group">
                                @if(!$loop->last)
                                    <div class="absolute left-4 top-8 bottom-[-24px] w-0.5 bg-gray-100"></div>
                                @endif
                                <div class="absolute left-0 top-1 w-8 h-8 rounded-full flex items-center justify-center z-10 border-4 border-white
                                    {{ $item['is_paid'] ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-400' }}">
                                    @if($item['is_paid'])
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                                    @endif
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                    <p class="text-[11px] font-black text-gray-900 uppercase">{{ $item['month'] }}</p>
                                    <p class="text-[9px] font-bold uppercase {{ $item['is_paid'] ? 'text-emerald-600' : 'text-pink-600' }}">
                                        {{ $item['is_paid'] ? 'Paid ✓' : 'Payment Required' }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>