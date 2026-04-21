<x-app-layout>
    <div class="py-12 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Inspections</h1>
                            <p class="text-sm text-gray-500">Property Evaluation Records</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <input type="text" placeholder="Search Property..." class="bg-white border-none rounded-xl shadow-sm focus:ring-[#853953] pl-10 w-64 text-sm">
                                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <button class="bg-white px-4 py-2 rounded-xl shadow-sm flex items-center text-gray-600 font-medium text-sm hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Filters
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-white rounded-2xl p-4 shadow-sm flex items-start space-x-6 border border-transparent hover:border-[#853953] transition-all cursor-pointer group">
                            <div class="w-24 h-24 bg-[#853953] rounded-xl flex-shrink-0 flex items-center justify-center">
                                <svg class="w-10 h-10 text-white opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-[#853953]">Tierra Nava (P001)</h3>
                                    <span class="text-[10px] font-bold text-[#853953] bg-pink-50 px-2 py-1 rounded">INSP-772</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Inspected by: <span class="font-semibold">S001 (Jed Camara)</span></p>
                                <p class="text-[10px] text-gray-400 mt-2">April 21, 2026</p>
                            </div>
                            <div class="w-px h-16 bg-gray-200 self-center"></div>
                            <div class="flex-1 px-4">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Evaluation</p>
                                <p class="text-xs text-gray-500 italic leading-relaxed line-clamp-3">"Property is in excellent condition. Minor paint touch-ups required in the master bedroom. Plumbing verified."</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-4 shadow-sm flex items-start space-x-6 border border-transparent hover:border-[#853953] transition-all cursor-pointer group">
                            <div class="w-24 h-24 bg-[#853953] rounded-xl flex-shrink-0 opacity-80 flex items-center justify-center">
                                <svg class="w-10 h-10 text-white opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-[#853953]">Bria Homes (P002)</h3>
                                    <span class="text-[10px] font-bold text-[#853953] bg-pink-50 px-2 py-1 rounded">INSP-104</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Inspected by: <span class="font-semibold">S002 (Alice Guo)</span></p>
                                <p class="text-[10px] text-gray-400 mt-2">April 15, 2026</p>
                            </div>
                            <div class="w-px h-16 bg-gray-200 self-center"></div>
                            <div class="flex-1 px-4">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Evaluation</p>
                                <p class="text-xs text-gray-500 italic leading-relaxed line-clamp-3">"Electrical wiring inspected and passed safety standards. Garden maintenance needed."</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-80" x-data="{ selectedDate: '2026-04-25' }">
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 sticky top-6">
                        <h2 class="text-lg font-bold text-gray-800">Pending</h2>
                        <p class="text-xs text-gray-400 mb-6">Upcoming Property Reviews</p>

                        <div class="space-y-4">
                            <div class="border-b border-gray-50 pb-2">
                                <button @click="selectedDate = (selectedDate === '2026-04-25' ? null : '2026-04-25')" 
                                        class="flex items-center w-full text-left transition-colors"
                                        :class="selectedDate === '2026-04-25' ? 'text-[#853953]' : 'text-gray-400 hover:text-gray-600'">
                                    <span class="text-xl mr-2 font-bold" x-text="selectedDate === '2026-04-25' ? '−' : '+' "></span>
                                    <span class="text-sm font-bold">April 25, 2026</span>
                                </button>
                                <div x-show="selectedDate === '2026-04-25'" x-collapse x-cloak
                                     class="ml-6 mt-3 space-y-2 border-l-2 border-pink-100 pl-4">
                                    <a href="#" class="block text-[10px] text-gray-600 font-bold hover:text-[#853953]">Valencia Estates (P012)</a>
                                    <a href="#" class="block text-[10px] text-gray-600 font-bold hover:text-[#853953]">Gran Europa (P034)</a>
                                </div>
                            </div>

                            <div class="flex items-center text-gray-400 opacity-60">
                                <span class="text-xl mr-2 font-bold">+</span>
                                <span class="text-sm font-bold">April 28, 2026</span>
                            </div>
                        </div>
                        
                        <button class="w-full mt-8 bg-[#853953] text-white py-3 rounded-xl text-sm font-bold shadow-lg shadow-pink-200 hover:bg-pink-900 transition-colors">
                            Schedule New
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>