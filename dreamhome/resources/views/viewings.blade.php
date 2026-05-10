<x-app-layout>

    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- PAGE HEADER --}}
            <div class="mb-8">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-[#853953] mb-1">DreamHome — CDO Branch</p>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">My Viewings</h1>
                <p class="text-sm text-gray-400 font-medium mt-1">Your scheduled and past property viewings.</p>
            </div>

            @if($viewings->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="bg-white rounded-2xl p-16 text-center border-2 border-dashed border-gray-200">
                <div class="w-16 h-16 bg-pink-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#853953]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-gray-800 font-black text-lg">No Viewings Yet</p>
                <p class="text-gray-400 font-medium text-sm mt-1">
                    @if(!Auth::user()->renterno)
                        Your account is not linked to a renter record yet. Please contact DreamHome staff.
                    @else
                        You haven't scheduled any property viewings yet.
                    @endif
                </p>
                <a href="{{ route('home') }}"
                    class="inline-block mt-5 px-5 py-2.5 bg-[#853953] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-[#6e2e44] transition-all">
                    Browse Properties
                </a>
            </div>

            @else
            {{-- VIEWINGS LIST --}}
            <div class="space-y-4">
                @foreach($viewings as $viewing)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:border-pink-100 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row">

                        {{-- Date Badge --}}
                        <div class="sm:w-36 bg-gradient-to-b from-[#853953] to-[#5d273a] flex flex-row sm:flex-col items-center justify-center p-5 gap-3 sm:gap-1 shrink-0">
                            <div class="text-center">
                                <p class="text-pink-200 text-[10px] font-black uppercase tracking-widest">
                                    {{ \Carbon\Carbon::parse($viewing->view_date)->format('M') }}
                                </p>
                                <p class="text-white text-3xl font-black leading-none">
                                    {{ \Carbon\Carbon::parse($viewing->view_date)->format('d') }}
                                </p>
                                <p class="text-pink-200/70 text-[10px] font-bold">
                                    {{ \Carbon\Carbon::parse($viewing->view_date)->format('Y') }}
                                </p>
                            </div>
                            <div class="hidden sm:block w-8 h-px bg-white/20 my-1"></div>
                            @php
                                $isPast = \Carbon\Carbon::parse($viewing->view_date)->isPast();
                                $isToday = \Carbon\Carbon::parse($viewing->view_date)->isToday();
                            @endphp
                            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-full
                                {{ $isToday ? 'bg-amber-400 text-amber-900' : ($isPast ? 'bg-white/10 text-white/60' : 'bg-emerald-500 text-white') }}">
                                {{ $isToday ? 'Today' : ($isPast ? 'Done' : 'Upcoming') }}
                            </span>
                        </div>

                        {{-- Viewing Details --}}
                        <div class="flex-1 p-6">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-black bg-pink-50 text-[#853953] px-2.5 py-1 rounded-lg uppercase tracking-widest">
                                            {{ $viewing->viewingid ?? 'Viewing' }}
                                        </span>
                                    </div>
                                    <h3 class="text-base font-black text-gray-900 tracking-tight">
                                        {{ $viewing->street ?? 'Property' }}
                                    </h3>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <p class="text-xs text-gray-400 font-bold">
                                            {{ isset($viewing->area) ? $viewing->area . ', ' . $viewing->city : 'Location not set' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Property No.</p>
                                    <p class="text-sm font-black text-gray-900">{{ $viewing->propertyno }}</p>
                                </div>
                            </div>

                            {{-- Comment --}}
                            @if($viewing->comment)
                            <div class="bg-[#F3F4F6] rounded-xl p-3 border border-gray-100">
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Comments / Notes</p>
                                <p class="text-xs text-gray-600 font-medium leading-relaxed">{{ $viewing->comment }}</p>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>

</x-app-layout>