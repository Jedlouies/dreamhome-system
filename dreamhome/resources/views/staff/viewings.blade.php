<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="py-12 bg-[#F3F4F6] min-h-screen" x-data="{ selectedDate: '{{ now()->format('M d, Y') }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                {{-- MAIN CONTENT AREA --}}
                <div class="flex-1">
                    {{-- PAGE HEADER (Matches Inspections Style) --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Viewing Center</h1>
                            <p class="text-sm text-gray-500 mt-1 font-medium">Manage property visit requests and guide assignments.</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#853953] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" placeholder="Search addresses..." class="bg-white border-none rounded-[1.5rem] shadow-sm focus:ring-2 focus:ring-[#853953] pl-10 w-full md:w-72 text-sm transition-all h-12">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 1: INCOMING REQUESTS --}}
                    @if($requests->isNotEmpty())
                    <div class="mb-10">
                        <div class="flex items-center gap-2 mb-4 px-2">
                            <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#853953]">Pending Requests</h2>
                            <div class="h-px flex-1 bg-pink-100"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach($requests as $request)
                            <a href="{{ route('staff.viewings.create', ['request_id' => $request->id]) }}" 
                               class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white hover:shadow-xl hover:-translate-y-1 transition-all group flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="w-12 h-12 bg-pink-50 rounded-2xl flex items-center justify-center text-[#853953]">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                        </div>
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($request->view_date)->format('M d') }}</span>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-800 tracking-tighter group-hover:text-[#853953] transition-colors line-clamp-1">{{ $request->title }}</h3>
                                    <p class="text-xs text-gray-500 font-bold mt-1 uppercase tracking-tighter">Request by: {{ $request->firstname }} {{ $request->lastname }}</p>
                                </div>
                                <div class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between">
                                    <span class="text-[10px] font-black text-[#853953] uppercase tracking-widest">Schedule & Assign</span>
                                    <svg class="w-4 h-4 text-[#853953] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- SECTION 2: SCHEDULED VIEWINGS --}}
                    <div class="flex items-center gap-2 mb-6 px-2">
                        <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#853953]">Confirmed Schedule</h2>
                        <div class="h-px flex-1 bg-pink-100"></div>
                    </div>

                    <div class="space-y-6">
                        @foreach($viewings as $viewing)
                        <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white hover:shadow-xl transition-all flex flex-col md:flex-row items-center gap-8 group">
                            
                            {{-- Visual Icon --}}
                            <div class="w-24 h-24 bg-gradient-to-br from-[#853953] to-[#5d273a] rounded-[2rem] flex-shrink-0 flex items-center justify-center text-white font-black text-3xl shadow-lg">
                                {{ substr($viewing->title, 0, 1) }}
                            </div>
                            
                            {{-- Info --}}
                            <div class="flex-1 text-center md:text-left">
                                <h3 class="text-2xl font-black text-gray-900 tracking-tighter group-hover:text-[#853953] transition-colors leading-tight">{{ $viewing->title }}</h3>
                                <div class="flex items-center justify-center md:justify-start gap-2 mt-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <p class="text-sm text-gray-400 font-bold tracking-tight">{{ $viewing->addr }}</p>
                                </div>
                                <div class="flex items-center justify-center md:justify-start gap-2 mt-3">
                                    <div class="bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($viewing->date)->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="hidden md:block w-px h-16 bg-gray-100"></div>

                            {{-- Staff Assignment Form/Display --}}
                            <div class="w-full md:w-72 bg-gray-50 p-5 rounded-[1.5rem] border border-gray-100 group-hover:bg-pink-50 group-hover:border-pink-100 transition-colors">
                                @if(isset($viewing->staff_name))
                                    <p class="text-[10px] font-black text-[#853953] uppercase mb-1 tracking-widest opacity-60">Assigned Guide</p>
                                    <p class="text-sm text-gray-800 font-black italic">{{ $viewing->staff_name }}</p>
                                @else
                                    <p class="text-[10px] font-black text-red-500 uppercase mb-2 tracking-widest">Awaiting Guide</p>
                                    <form action="{{ route('staff.viewings.assign', $viewing->id) }}" method="POST" class="flex gap-2">
                                        @csrf @method('PATCH')
                                        <select name="staffno" class="text-[10px] py-1 border-gray-200 rounded-xl focus:ring-[#853953] flex-1 font-bold">
                                            <option value="">Select Staff...</option>
                                            @foreach($allStaff as $staff)
                                                <option value="{{ $staff->staffno }}">{{ $staff->firstname }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="bg-[#853953] text-white px-3 rounded-xl text-[9px] font-black uppercase shadow-md hover:bg-pink-900 transition-colors">Go</button>
                                    </form>
                                @endif
                                <hr class="my-3 border-gray-200">
                                <p class="text-[10px] text-gray-500 italic leading-tight">{{ $viewing->comment ?? 'No additional instructions provided.' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- SIDEBAR TIMELINE (Matches Inspections Style) --}}
                <aside class="w-full lg:w-80 shrink-0 sticky top-6">
                    <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-white flex flex-col min-h-[600px]">
                        <div class="mb-8">
                            <h2 class="text-xl font-black text-gray-800 tracking-tighter">Timeline</h2>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Upcoming Viewings</p>
                        </div>

                        <div class="flex-1 space-y-8 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($timeline as $date => $items)
                            <div class="relative">
                                <button @click="selectedDate = (selectedDate === '{{ $date }}' ? null : '{{ $date }}')" 
                                    class="flex items-center w-full transition-all group outline-none">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all mr-3 shadow-md"
                                        :class="selectedDate === '{{ $date }}' ? 'bg-[#853953] text-white' : 'bg-gray-100 text-gray-400'">
                                        <span class="text-xs font-black">{{ \Carbon\Carbon::parse($date)->format('d') }}</span>
                                    </div>
                                    <span class="text-xs font-black transition-colors" :class="selectedDate === '{{ $date }}' ? 'text-gray-900' : 'text-gray-400'">
                                        {{ \Carbon\Carbon::parse($date)->format('M Y') }}
                                    </span>
                                </button>

                                <div x-show="selectedDate === '{{ $date }}'" x-collapse x-cloak class="mt-4 ml-4 pl-6 border-l-2 border-pink-100 space-y-5">
                                    @foreach($items as $item)
                                    <div class="relative group/item">
                                        <div class="absolute -left-[31px] top-1 w-2 h-2 rounded-full bg-[#853953] ring-4 ring-white shadow-sm"></div>
                                        <p class="text-[11px] font-black text-gray-800 group-hover:text-[#853953] transition-colors leading-none tracking-tight">{{ $item->street }}</p>
                                        <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase">Guide: {{ $item->staff_name ?? 'Pending' }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-10 pt-6 border-t border-gray-50">
                            <a href="{{ route('staff.viewings.create') }}" class="w-full bg-[#853953] text-white py-4 rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest shadow-xl shadow-pink-100 hover:bg-pink-900 hover:shadow-pink-200 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                New Viewing
                            </a>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>