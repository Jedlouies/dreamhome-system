<x-app-layout>

    {{-- ===== HERO ===== --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div class="max-w-2xl">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-[#853953] mb-3">DreamHome — CDO Branch</p>
                    <h1 class="text-4xl sm:text-5xl font-black text-gray-900 leading-tight tracking-tight mb-4">
                        Find Your <span class="text-[#853953]">Dream Home</span><br>in Cagayan de Oro
                    </h1>
                    <p class="text-base text-gray-500 font-medium leading-relaxed">
                        Browse available houses and flats for rent across CDO. Create a free account to book a viewing and move in faster.
                    </p>
                </div>
                {{-- Guest CTAs --}}
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('login') }}"
                        class="px-6 py-3 border-2 border-[#853953] text-[#853953] rounded-xl text-sm font-black hover:bg-pink-50 transition-all">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-6 py-3 bg-[#853953] text-white rounded-xl text-sm font-black hover:bg-[#6e2e44] transition-all shadow-sm">
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="py-10 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- SEARCH + FILTER --}}
            <div class="mb-8 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <div class="relative flex-1 max-w-md">
                    <input type="text" placeholder="Search by street, area or postcode..."
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-white shadow-sm text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#853953]/30 focus:border-[#853953] transition-all">
                    <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div class="flex gap-2">
                    <button class="px-5 py-2.5 bg-[#853953] text-white rounded-xl text-sm font-bold shadow-sm hover:bg-[#6e2e44] transition-all">All</button>
                    <button class="px-5 py-2.5 bg-white text-gray-500 border border-gray-200 rounded-xl text-sm font-bold hover:bg-pink-50 hover:text-[#853953] hover:border-pink-100 transition-all">Flats</button>
                    <button class="px-5 py-2.5 bg-white text-gray-500 border border-gray-200 rounded-xl text-sm font-bold hover:bg-pink-50 hover:text-[#853953] hover:border-pink-100 transition-all">Houses</button>
                </div>
            </div>

            {{-- SECTION LABEL --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-black text-gray-800 tracking-tight">Available Properties</h2>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">CDO Branch — 4 properties listed</p>
                </div>
                <span class="text-xs font-bold text-[#853953] bg-pink-50 px-3 py-1.5 rounded-lg">Sort: Newest</span>
            </div>

            {{-- PROPERTY DATA --}}
            @php
                $properties = [
                    [
                        'id'       => 'PC4',
                        'name'     => 'Tierra Nava Modern',
                        'street'   => '6 Lawrence St.',
                        'area'     => 'Patag',
                        'city'     => 'Cagayan de Oro City',
                        'postcode' => '9000',
                        'type'     => 'Flat',
                        'rooms'    => 3,
                        'price'    => '15,000',
                        'img'      => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80',
                    ],
                    [
                        'id'       => 'PC36',
                        'name'     => 'Bria Homes Gran Europa',
                        'street'   => '2 Manor Road',
                        'area'     => 'Carmen',
                        'city'     => 'Cagayan de Oro City',
                        'postcode' => '9000',
                        'type'     => 'Flat',
                        'rooms'    => 3,
                        'price'    => '12,500',
                        'img'      => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80',
                    ],
                    [
                        'id'       => 'PC21',
                        'name'     => 'Valencia Estates',
                        'street'   => '18 Dale Road',
                        'area'     => 'Uptown',
                        'city'     => 'Cagayan de Oro City',
                        'postcode' => '9000',
                        'type'     => 'House',
                        'rooms'    => 5,
                        'price'    => '25,000',
                        'img'      => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
                    ],
                    [
                        'id'       => 'PC16',
                        'name'     => 'Xavier Estates Villa',
                        'street'   => '5 Novar Drive',
                        'area'     => 'Balulang',
                        'city'     => 'Cagayan de Oro City',
                        'postcode' => '9000',
                        'type'     => 'Flat',
                        'rooms'    => 4,
                        'price'    => '18,000',
                        'img'      => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                    ],
                ];
            @endphp

            {{-- PROPERTY CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($properties as $property)
                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 hover:border-pink-100">

                    {{-- Image --}}
                    <div class="relative h-52 overflow-hidden">
                        <img src="{{ $property['img'] }}" alt="{{ $property['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-xl shadow-sm">
                            <p class="text-[#853953] font-black text-sm leading-none">&#8369;{{ $property['price'] }}<span class="text-[10px] text-gray-400 font-semibold">/mo</span></p>
                        </div>
                        <div class="absolute top-3 left-3 flex gap-1.5">
                            <span class="bg-black/40 backdrop-blur-sm text-white text-[10px] font-black px-2.5 py-1 rounded-lg tracking-widest">{{ $property['id'] }}</span>
                            <span class="bg-[#853953]/80 backdrop-blur-sm text-white text-[10px] font-black px-2.5 py-1 rounded-lg">{{ $property['type'] }}</span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5">
                        <div class="mb-4">
                            <h3 class="text-sm font-black text-gray-900 group-hover:text-[#853953] transition-colors tracking-tight">{{ $property['name'] }}</h3>
                            <p class="text-xs text-gray-500 font-bold mt-0.5">{{ $property['street'] }}</p>
                            <div class="flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p class="text-xs font-bold text-gray-400">{{ $property['area'] }}, {{ $property['city'] }} {{ $property['postcode'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center gap-1.5 bg-pink-50 text-[#853953] px-3 py-1.5 rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <span class="text-xs font-black">{{ $property['rooms'] }} Rooms</span>
                            </div>
                            <div class="flex items-center gap-1.5 bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-xs font-black">Available</span>
                            </div>
                        </div>
                        <a href="{{ route('login') }}"
                            class="block w-full py-3 bg-gray-800 text-white rounded-xl font-black text-xs uppercase tracking-widest text-center hover:bg-[#853953] active:scale-95 transition-all">
                            Login to Book
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- GUEST BANNER --}}
            <div class="mt-10 bg-gradient-to-r from-[#853953] to-[#5d273a] rounded-2xl p-8 flex flex-col sm:flex-row items-center justify-between gap-5">
                <div>
                    <h3 class="text-white font-black text-xl tracking-tight mb-1">Ready to find your dream home?</h3>
                    <p class="text-pink-200 text-sm font-medium">Register as a prospective renter to book viewings and manage your lease agreements.</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-white/20 text-white border border-white/30 rounded-xl text-sm font-black hover:bg-white/30 transition-all">Login</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-white text-[#853953] rounded-xl text-sm font-black hover:bg-pink-50 transition-all shadow-sm">Register Free</a>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>