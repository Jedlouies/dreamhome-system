<x-app-layout>
    <div class="py-12 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-pink-50">
                
                <div class="mb-10 flex justify-between items-end">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 tracking-tighter mb-1">Finalize Lease</h2>
                        <p class="text-sm text-gray-400 font-medium">Create a binding agreement between renter and property.</p>
                    </div>
                    <p class="text-[10px] font-black text-[#853953] uppercase tracking-[0.2em] bg-pink-50 px-4 py-2 rounded-xl">Lease ID: {{ $autoLeaseNo }}</p>
                </div>

                <form action="{{ route('staff.leases.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <input type="hidden" name="leaseno" value="{{ $autoLeaseNo }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Selection Section --}}
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black text-[#853953] uppercase mb-2 tracking-widest">Target Property</label>
                                <select name="propertyno" class="w-full bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#853953] text-sm" required>
                                    <option value="" disabled selected>Select Property...</option>
                                    @foreach($properties as $property)
                                        <option value="{{ $property->propertyno }}">{{ $property->street }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-[#853953] uppercase mb-2 tracking-widest">Assigned Renter</label>
                                <select name="renterno" class="w-full bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#853953] text-sm" required>
                                    <option value="" disabled selected>Select Renter...</option>
                                    @foreach($renters as $renter)
                                        <option value="{{ $renter->renterno }}">{{ $renter->firstname }} {{ $renter->lastname }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-[#853953] uppercase mb-2 tracking-widest">Lease Managed By (Staff)</label>
                                <select name="staffno" class="w-full bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#853953] text-sm" required>
                                    <option value="" disabled selected>Select Staff...</option>
                                    @foreach($staffList as $staff)
                                        <option value="{{ $staff->staffno }}">{{ $staff->firstname }} {{ $staff->lastname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Financials Section --}}
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-[#853953] uppercase mb-2 tracking-widest">Monthly Rent</label>
                                    <input type="number" name="monthly_rent" step="0.01" required class="w-full bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#853953] text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-[#853953] uppercase mb-2 tracking-widest">Payment Method</label>
                                    <select name="paymentmethod" class="w-full bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#853953] text-sm">
                                        <option value="Cash">Cash</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-[#853953] uppercase mb-2 tracking-widest">Deposit Amount</label>
                                    <input type="number" name="deposit" step="0.01" required class="w-full bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#853953] text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-[#853953] uppercase mb-2 tracking-widest">Deposit Received?</label>
                                    <select name="isdepositpaid" class="w-full bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#853953] text-sm">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-[#853953] uppercase mb-2 tracking-widest">Start Date</label>
                                    <input type="date" name="startdate" required class="w-full bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#853953] text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-[#853953] uppercase mb-2 tracking-widest">End Date</label>
                                    <input type="date" name="enddate" required class="w-full bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#853953] text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-50">
                        <button type="submit" class="w-full bg-[#853953] text-white py-5 rounded-[2rem] font-black text-[12px] uppercase tracking-widest shadow-xl shadow-pink-100 hover:bg-pink-900 hover:-translate-y-1 transition-all">
                            Complete Lease Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>