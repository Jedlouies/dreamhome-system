<x-app-layout>
    <div class="py-12 bg-[#F3F4F6] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Staff Registration</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Registering new authorized personnel for DreamHome CDO.</p>
            </div>

            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-white">
                <form method="POST" action="{{ route('staff.store') }}" class="space-y-10">
                    @csrf

                    <div>
                        <h3 class="text-xs font-black text-[#853953] uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                            <span class="w-8 h-px bg-pink-100"></span> Professional Assignment
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-2">Staff No. (Auto)</label>
                                <input type="text" name="staffno" value="{{ $autoStaffNo }}" readonly
                                    class="w-full bg-gray-100 border-none rounded-2xl py-4 px-5 text-sm font-black text-[#853953] cursor-not-allowed shadow-inner">
                            </div>
                            
                            <div>
                                <label class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-2">Assign Branch</label>
                                <select name="branchno" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 focus:ring-2 focus:ring-[#853953] text-sm font-bold appearance-none">
                                    <option value="" disabled selected>Select location</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->branchno }}">{{ $branch->city }} ({{ $branch->branchno }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-2">Position</label>
                                <select name="position" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 focus:ring-2 focus:ring-[#853953] text-sm font-bold appearance-none">
                                    <option value="Secretary">Secretary</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Regular">Regular</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-[#853953] uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                            <span class="w-8 h-px bg-pink-100"></span> Bio-Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-2">First Name</label>
                                <input type="text" name="firstname" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 text-sm font-bold focus:ring-[#853953]">
                            </div>
                            <div>
                                <label class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-2">Last Name</label>
                                <input type="text" name="lastname" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 text-sm font-bold focus:ring-[#853953]">
                            </div>
                            <div>
                                <label class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-2">Gender</label>
                                <div class="flex items-center gap-6 mt-4">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="sex" value="M" checked class="w-4 h-4 text-[#853953] border-gray-300 focus:ring-[#853953]">
                                        <span class="ml-2 text-xs font-bold text-gray-600 group-hover:text-[#853953]">Male</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="sex" value="F" class="w-4 h-4 text-[#853953] border-gray-300 focus:ring-[#853953]">
                                        <span class="ml-2 text-xs font-bold text-gray-600 group-hover:text-[#853953]">Female</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-2">Date of Birth</label>
                                <input type="date" name="date_of_birth" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 text-sm font-bold focus:ring-[#853953]">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-2">Email Address</label>
                                <input type="email" name="email" required placeholder="staff@dreamhome.com" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 text-sm font-bold focus:ring-[#853953]">
                            </div>
                            <div>
                                <label class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-2">Temporary Password</label>
                                <input type="password" name="password" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 text-sm font-bold focus:ring-[#853953]">
                            </div>
                            <div>
                                <label class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-2">NIN (National Insurance)</label>
                                <input type="text" name="nin" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 text-sm font-bold focus:ring-[#853953]">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full bg-gray-900 text-white py-5 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-[#853953] transition-all">
                            Complete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>