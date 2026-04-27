<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('staff.properties.properties') }}" class="text-sm font-medium text-gray-500 hover:text-[#853953] transition-colors">
                &larr; Back to Properties
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900">{{ $property->street }}</h1>
                <p class="text-lg text-gray-500">{{ $property->area }}, {{ $property->city }} {{ $property->postcode }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2">
                    <div class="bg-white p-3 rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                        @if($property->main_image)
                            <img src="{{ asset('storage/images/' . $property->main_image) }}" 
                                 alt="Property Hero Image" 
                                 class="w-full h-[600px] object-cover rounded-2xl shadow-inner">
                        @else
                            <div class="w-full h-[600px] bg-gray-100 flex items-center justify-center rounded-2xl">
                                <span class="text-gray-400">No Image Available</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">


                    <div class="bg-[#853953] p-6 rounded-3xl text-white shadow-lg flex items-start space-x-4">
                        <svg class="w-6 h-6 mt-1 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-bold text-xs uppercase tracking-widest opacity-80">Branch Office</h4>
                            <p class="text-md font-semibold">{{ $property->branch_info }}</p>
                        </div>
                    </div>

                                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-200 sticky top-6">
                        <div class="mb-6">
                            <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Rate Per Month</span>
                            <h3 class="text-3xl font-black text-gray-900 mt-1">
                                ₱{{ number_format($property->monthly_rate, 2) }}
                            </h3>
                        </div>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Type</span>
                                <span class="text-gray-900 font-bold bg-pink-50 px-3 py-1 rounded-lg text-xs">{{ $property->property_type }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">Bedrooms</span>
                                <span class="text-gray-900 font-bold">{{ $property->no_of_rooms }} Rooms</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-500 font-medium">Property Number</span>
                                <span class="text-gray-900 font-bold text-xs font-mono">{{ $property->propertyno }}</span>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-2xl mb-6">
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Managed By</p>
                            <p class="text-gray-900 font-bold">{{ $property->staff_name }}</p>
                            <p class="text-xs text-gray-400 mt-2 uppercase font-bold mb-1">Owner</p>
                            <p class="text-gray-700 text-sm font-medium">{{ $property->owner_name }}</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>