<x-app-layout>

    <div class="py-12 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative w-full md:w-96">
                    <input type="text" placeholder="Search by location..." 
                        class="w-full pl-12 pr-4 py-3 rounded-2xl border-none shadow-sm focus:ring-2 focus:ring-[#853953] transition-all">
                    <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                
                <div class="flex gap-2 overflow-x-auto pb-2 w-full md:w-auto">
                    <button class="bg-[#853953] text-white px-6 py-2 rounded-xl text-sm font-bold shadow-md shadow-pink-100">All Houses</button>
                    <button class="bg-white text-gray-600 px-6 py-2 rounded-xl text-sm font-bold hover:bg-pink-50 hover:text-[#853953] transition-all">Flats</button>
                    <button class="bg-white text-gray-600 px-6 py-2 rounded-xl text-sm font-bold hover:bg-pink-50 hover:text-[#853953] transition-all">Bungalows</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @php
                    // Example data - This will eventually come from your Properties table
                    $houses = [
                        ['id' => 'P001', 'name' => 'Tierra Nava Modern', 'price' => '15,000', 'rooms' => 3, 'loc' => 'Barra, Opol', 'img' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80'],
                        ['id' => 'P002', 'name' => 'Bria Homes Gran Europa', 'price' => '12,500', 'rooms' => 2, 'loc' => 'Lumbia, CDO', 'img' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80'],
                        ['id' => 'P003', 'name' => 'Valencia Estates', 'price' => '25,000', 'rooms' => 4, 'loc' => 'Uptown, CDO', 'img' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80'],
                        ['id' => 'P004', 'name' => 'Xavier Estates Villa', 'price' => '45,000', 'rooms' => 5, 'loc' => 'Upper Balulang, CDO', 'img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80'],
                    ];
                @endphp

                @foreach($houses as $house)
                <div class="group bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 cursor-pointer hover:-translate-y-2 border border-transparent hover:border-pink-50">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $house['img'] }}" alt="House Image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-4 py-2 rounded-2xl shadow-sm">
                            <p class="text-[#853953] font-black text-sm">₱{{ $house['price'] }}<span class="text-[10px] text-gray-400">/mo</span></p>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-black text-gray-900 group-hover:text-[#853953] transition-colors tracking-tight">{{ $house['name'] }}</h3>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $house['loc'] }}</p>
                            </div>
                            <span class="bg-gray-50 text-gray-400 text-[10px] font-black px-2 py-1 rounded-lg uppercase tracking-tighter">{{ $house['id'] }}</span>
                        </div>

                        <div class="flex items-center gap-6 border-t border-gray-50 pt-6">
                            <div class="flex items-center gap-2">
                                <div class="p-2 bg-pink-50 rounded-lg">
                                    <svg class="w-4 h-4 text-[#853953]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                </div>
                                <span class="text-xs font-black text-gray-700">{{ $house['rooms'] }} Rooms</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="p-2 bg-blue-50 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-xs font-black text-gray-700">Available Now</span>
                            </div>
                        </div>

                        <button class="w-full mt-8 py-4 bg-[#853953] text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#853953] transition-all transform active:scale-95 shadow-xl shadow-gray-100 group-hover:shadow-pink-100">
                            Book Viewing
                        </button>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>