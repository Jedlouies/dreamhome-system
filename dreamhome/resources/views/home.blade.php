<x-app-layout>

    {{-- ===== HERO SECTION ===== --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="max-w-2xl">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-[#853953] mb-3">DreamHome Property Listings</p>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 leading-tight tracking-tight mb-4">
                    Find Your <span class="text-[#853953]">Dream Home</span><br>in Cagayan de Oro
                </h1>
                <p class="text-base text-gray-500 font-medium leading-relaxed">
                    Browse available houses, flats, and bungalows across CDO. Book a viewing directly and move in faster.
                </p>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ===== SEARCH + FILTER ROW ===== --}}
            <div class="mb-8 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

                {{-- Search --}}
                <div class="relative flex-1 max-w-md">
                    <input
                        type="text"
                        placeholder="Search by location..."
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-white shadow-sm text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#853953]/30 focus:border-[#853953] transition-all"
                    >
                    <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                {{-- Filter Tabs --}}
                <div class="flex gap-2">
                    <button class="px-5 py-2.5 bg-[#853953] text-white rounded-xl text-sm font-bold shadow-sm transition-all hover:bg-[#6e2e44]">All Houses</button>
                    <button class="px-5 py-2.5 bg-white text-gray-500 border border-gray-200 rounded-xl text-sm font-bold hover:bg-pink-50 hover:text-[#853953] hover:border-pink-100 transition-all">Flats</button>
                    <button class="px-5 py-2.5 bg-white text-gray-500 border border-gray-200 rounded-xl text-sm font-bold hover:bg-pink-50 hover:text-[#853953] hover:border-pink-100 transition-all">Bungalows</button>
                </div>
            </div>

            {{-- ===== SECTION LABEL ===== --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-black text-gray-800 tracking-tight">Available Properties</h2>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">4 properties found</p>
                </div>
                <span class="text-xs font-bold text-[#853953] bg-pink-50 px-3 py-1.5 rounded-lg">Sort: Newest</span>
            </div>

            {{-- ===== PROPERTY CARDS ===== --}}
            @php
                $houses = [
                    ['id' => 'P001', 'name' => 'Tierra Nava Modern',    'price' => '15,000', 'rooms' => 3, 'loc' => 'Barra, Opol',         'img' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80'],
                    ['id' => 'P002', 'name' => 'Bria Homes Gran Europa', 'price' => '12,500', 'rooms' => 2, 'loc' => 'Lumbia, CDO',          'img' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80'],
                    ['id' => 'P003', 'name' => 'Valencia Estates',       'price' => '25,000', 'rooms' => 4, 'loc' => 'Uptown, CDO',          'img' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80'],
                    ['id' => 'P004', 'name' => 'Xavier Estates Villa',   'price' => '45,000', 'rooms' => 5, 'loc' => 'Upper Balulang, CDO', 'img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($houses as $house)
                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer hover:-translate-y-1 border border-gray-100 hover:border-pink-100">

                    {{-- Image --}}
                    <div class="relative h-52 overflow-hidden">
                        <img src="{{ $house['img'] }}" alt="{{ $house['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        {{-- Price badge --}}
                        <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-xl shadow-sm">
                            <p class="text-[#853953] font-black text-sm leading-none">&#8369;{{ $house['price'] }}<span class="text-[10px] text-gray-400 font-semibold">/mo</span></p>
                        </div>

                        {{-- Property ID badge --}}
                        <div class="absolute top-3 left-3 bg-black/40 backdrop-blur-sm px-2.5 py-1 rounded-lg">
                            <span class="text-white text-[10px] font-black tracking-widest">{{ $house['id'] }}</span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5">

                        {{-- Title + Location --}}
                        <div class="mb-4">
                            <h3 class="text-base font-black text-gray-900 group-hover:text-[#853953] transition-colors tracking-tight leading-snug">{{ $house['name'] }}</h3>
                            <div class="flex items-center gap-1 mt-1">
                                <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $house['loc'] }}</p>
                            </div>
                        </div>

                        {{-- Amenity Pills --}}
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center gap-1.5 bg-pink-50 text-[#853953] px-3 py-1.5 rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span class="text-xs font-black">{{ $house['rooms'] }} Rooms</span>
                            </div>
                            <div class="flex items-center gap-1.5 bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs font-black">Available Now</span>
                            </div>
                        </div>

                        {{-- CTA Button --}}
                        <button class="w-full py-3 bg-[#853953] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-[#6e2e44] active:scale-95 transition-all shadow-sm shadow-pink-100 group-hover:shadow-pink-200">
                            Book Viewing
                        </button>

                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

</x-app-layout>