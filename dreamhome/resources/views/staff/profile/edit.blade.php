<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Settings') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#F3F4F6]">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-8 bg-white shadow-sm border border-white rounded-[2rem]">
                <header class="mb-6">
                    <h2 class="text-lg font-black text-gray-900">Profile Information</h2>
                    <p class="mt-1 text-sm text-gray-600">Update your account's profile information and email address.</p>
                </header>

                <form method="post" action="{{ route('staff.profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <x-input-label for="firstname" :value="__('First Name')" />
                        <x-text-input id="firstname" name="firstname" type="text" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-[#853953] focus:border-[#853953]" :value="old('firstname', $user->firstname)" required autofocus />
                    </div>

                    <div>
                        <x-input-label for="lastname" :value="__('Last Name')" />
                        <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-[#853953] focus:border-[#853953]" :value="old('lastname', $user->lastname)" required />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-[#853953] focus:border-[#853953]" :value="old('email', $user->email)" required />
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="bg-[#853953] text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-pink-900 transition-all">
                            Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-bold">Saved successfully.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>