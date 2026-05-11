<x-app-layout>
    <div class="py-12 bg-[#F3F4F6] min-h-screen" x-data="{ selectedDate: '{{ now()->format('Y-m-d') }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="flex-1">
                    <div class="mb-8">
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Viewing Center</h1>
                    </div>

                    {{-- Scheduled Table --}}
                    <div class="space-y-4">
                        <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest">Scheduled Viewings</h2>
                        @foreach($viewings as $viewing)
                        <div class="bg-white rounded-3xl p-5 shadow-sm flex items-center gap-6">
                            <div class="w-16 h-16 bg-[#853953] rounded-2xl flex items-center justify-center text-white font-bold text-xl">
                                {{ substr($viewing->title, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900">{{ $viewing->title }}</h3>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($viewing->date)->format('M d, Y') }}</p>
                            </div>
                            <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
                                <p class="text-[9px] font-black text-[#853953] uppercase">Staff</p>
                                <p class="text-xs font-bold text-gray-800">{{ $viewing->staff_name }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <aside class="w-full lg:w-80 flex-shrink-0">
                    <div class="bg-white rounded-[2.5rem] p-6 shadow-sm sticky top-6 min-h-[500px]">
                        <h2 class="text-xl font-black text-gray-800 mb-6">Timeline</h2>
                        <p class="text-sm text-gray-500 mb-4">Click on a date to view upcoming viewings.</p>

                        <div class="space-y-6">
                            @foreach($timeline as $date => $items)
                            <div>
                                <button @click="selectedDate = (selectedDate === '{{ $date }}' ? null : '{{ $date }}')" 
                                    class="flex items-center w-full outline-none">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center mr-2 text-[10px]"
                                        :class="selectedDate === '{{ $date }}' ? 'bg-[#853953] text-white' : 'bg-gray-100 text-gray-400'">
                                        {{ \Carbon\Carbon::parse($date)->format('d') }}
                                    </div>
                                    <span class="text-xs font-black" :class="selectedDate === '{{ $date }}' ? 'text-gray-900' : 'text-gray-400'">
                                        {{ \Carbon\Carbon::parse($date)->format('M Y') }}
                                    </span>
                                </button>

                                <div x-show="selectedDate === '{{ $date }}'" x-collapse class="mt-4 ml-3 pl-4 border-l border-pink-100 space-y-4">
                                    @foreach($items as $item)
                                    <a href="{{ route('staff.viewings.process', $item->id) }}" class="block group">
                                        <p class="text-[11px] font-black text-gray-800 group-hover:text-[#853953] transition-colors">{{ $item->street }}</p>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase">Guide: {{ $item->staff_name ?? 'Unassigned' }}</p>
                                    </a>
                                    @endforeach
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