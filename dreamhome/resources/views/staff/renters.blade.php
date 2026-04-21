<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Renter Management') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
                
                <div class="flex items-center space-x-4 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" placeholder="Search renters..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#853953] focus:border-[#853953] block w-full pl-10 p-2.5">
                    </div>

                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#853953] focus:border-[#853953] block p-2.5">
                        <option value="">All Preferences</option>
                        <option value="House">Prefers House</option>
                        <option value="Flat">Prefers Flat</option>
                    </select>
                </div>

                <button type="button" class="flex items-center justify-center text-white bg-[#853953] hover:bg-pink-900 focus:ring-4 focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Register New Renter
                </button>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">Renter No</th>
                            <th scope="col" class="px-6 py-4 font-bold">Full Name</th>
                            <th scope="col" class="px-6 py-4 font-bold">Contact</th>
                            <th scope="col" class="px-6 py-4 font-bold">Preference</th>
                            <th scope="col" class="px-6 py-4 font-bold">Max Rent</th>
                            <th scope="col" class="px-6 py-4 font-bold">Witnessed By</th>
                            <th scope="col" class="px-6 py-4 font-bold text-right">Options</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#853953]">R201</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 font-semibold">John Doe</span>
                                    <span class="text-xs text-gray-400">Bulua, CDO</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">0922-777-8888</td>
                            <td class="px-6 py-4">
                                <span class="bg-purple-50 text-purple-700 text-xs font-medium px-2.5 py-0.5 rounded-full border border-purple-100">
                                    House
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">₱20,000</td>
                            <td class="px-6 py-4 text-xs text-gray-600">S001 (Jed Camara)</td>
                            <td class="px-6 py-4 text-right">
                                <button id="renterMenuButton" data-dropdown-toggle="renterAction" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                </button>
                                <div id="renterAction" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 border border-gray-200">
                                    <ul class="py-2 text-sm text-gray-700">
                                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">View Viewings</a></li>
                                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Edit Renter</a></li>
                                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Lease History</a></li>
                                    </ul>
                                    <div class="py-1">
                                        <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete Record</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <span class="text-sm text-gray-700">
                        Showing <span class="font-semibold">1</span> to <span class="font-semibold">10</span> of <span class="font-semibold">156</span> Renters
                    </span>
                    <div class="inline-flex mt-2 xs:mt-0">
                        <button class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100">Prev</button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-r border-gray-300 rounded-r-lg hover:bg-gray-100">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>