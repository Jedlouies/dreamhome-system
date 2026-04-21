<x-guest-layout>
    <div class="rounded min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-white ">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-black  overflow-hidden sm:rounded-lg" style="background-color: white">
            
            <div class="flex justify-center mb-6">
                <img src="{{ asset('storage/images/dreamhome-logo-colored.png') }}" 
                     alt="Logo" 
                     class="h-20 w-auto object-contain">
            </div>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('staff.login') }}" >
                @csrf

                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">Staff Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-600">Remember Session</span>
                    </label>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <button type="submit" class="rounded text-white bg-gradient-to-r from-pink-700 via-pink-700 to-green-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">Login</button>               
                </div>
                
            </form>
        </div>
    </div>
</x-guest-layout>