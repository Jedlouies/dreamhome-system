<x-guest-layout>
    <div class="rounded min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-white">
        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-transparent  rounded-[2rem] border border-white overflow-hidden">
            
            <div class="flex justify-center mb-8">
                <img src="{{ asset('storage/images/dreamhome-logo-colored.png') }}" 
                     alt="DreamHome Logo" 
                     class="h-20 w-auto object-contain">
            </div>

            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Create Account</h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Join DreamHome to find your next house.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div>
                    <label for="name" class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-1">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        class="block w-full border-gray-100 bg-gray-50 rounded-2xl shadow-sm focus:ring-2 focus:ring-[#853953] focus:border-transparent transition-all">
                    @error('name')
                        <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="email" class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                        class="block w-full border-gray-100 bg-gray-50 rounded-2xl shadow-sm focus:ring-2 focus:ring-[#853953] focus:border-transparent transition-all">
                    @error('email')
                        <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="password" class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="block w-full border-gray-100 bg-gray-50 rounded-2xl shadow-sm focus:ring-2 focus:ring-[#853953] focus:border-transparent transition-all">
                    @error('password')
                        <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="password_confirmation" class="block font-black text-[10px] uppercase tracking-widest text-gray-400 mb-1">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="block w-full border-gray-100 bg-gray-50 rounded-2xl shadow-sm focus:ring-2 focus:ring-[#853953] focus:border-transparent transition-all">
                    @error('password_confirmation')
                        <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-8 space-y-4">
                    <button type="submit" class="w-full bg-[#853953] text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-gray-200 hover:bg-pink-900 hover:shadow-pink-100 transition-all transform active:scale-[0.98]">
                        Register Now
                    </button>
                    
                    <div class="text-center">
                        <p class="text-xs text-gray-500 font-medium">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-[#853953] font-black hover:underline">Login here</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>