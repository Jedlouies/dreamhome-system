<x-app-layout>
    <div class="py-12 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('staff.viewings.store') }}" method="POST" class="bg-white rounded-[2.5rem] shadow-sm border border-white overflow-hidden">
                @csrf
                <div class="p-8 bg-gradient-to-r from-[#853953] to-[#5d273a] text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-black tracking-tight">Schedule Viewing</h3>
                        <p class="text-xs font-bold opacity-80 uppercase tracking-widest">New Entry</p>
                    </div>
                    <a href="{{ route('staff.viewings.index') }}" class="text-xs font-bold uppercase tracking-widest bg-white/20 px-4 py-2 rounded-full hover:bg-white/30 transition">&larr; Back</a>
                </div>

                <div class="p-10 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Viewing ID</label>
                            <input type="text" name="viewingid" value="{{ $autoViewingId }}" readonly class="w-full rounded-2xl border-none bg-gray-50 text-[#853953] font-black text-sm ring-1 ring-gray-100">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Viewing Date</label>
                            <input type="date" name="view_date" required class="w-full rounded-2xl border-none bg-gray-50 text-gray-700 font-bold text-sm ring-1 ring-gray-100 focus:ring-2 focus:ring-[#853953]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Property</label>
                            <select name="propertyno" required class="w-full rounded-2xl border-none bg-gray-50 text-gray-700 font-bold text-sm ring-1 ring-gray-100 focus:ring-2 focus:ring-[#853953]">
                                @foreach($properties as $property)
                                    <option value="{{ $property->propertyno }}">{{ $property->street }} ({{ $property->propertyno }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Renter</label>
                            <select name="renterno" required class="w-full rounded-2xl border-none bg-gray-50 text-gray-700 font-bold text-sm ring-1 ring-gray-100 focus:ring-2 focus:ring-[#853953]">
                                @foreach($renters as $renter)
                                    <option value="{{ $renter->renterno }}">{{ $renter->firstname }} {{ $renter->lastname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Internal Staff Comments</label>
                        <textarea name="comment" rows="4" placeholder="Enter notes about the viewing..." class="w-full rounded-2xl border-none bg-gray-50 text-gray-700 font-medium text-sm ring-1 ring-gray-100 focus:ring-2 focus:ring-[#853953]"></textarea>
                    </div>
                </div>

                <div class="px-10 py-8 bg-gray-50/50 border-t border-gray-50 flex justify-end">
                    <button type="submit" class="bg-[#853953] text-white px-10 py-4 rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-pink-100 hover:bg-pink-900 transition-all">
                        Register Viewing
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>