<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'DreamHome') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">

            {{-- ===== LEFT BRANDED PANEL ===== --}}
            <div class="hidden lg:flex lg:w-1/2 relative flex-col justify-between overflow-hidden"
                 style="background-image: url('{{ asset('images/background.jpg') }}'); background-size: cover; background-position: center;">

                {{-- Overlay --}}
                <div class="absolute inset-0 bg-[#853953]/90"></div>

                {{-- Top: Logo --}}
                <div class="relative z-10 p-10">
                    @php $logoPath = public_path('images/dreamhome-logo-white.png'); @endphp
                    @if(file_exists($logoPath))
                        <img src="{{ asset('images/dreamhome-logo-white.png') }}" alt="DreamHome" class="h-10 w-auto object-contain">
                    @else
                        <div class="flex items-center gap-2">
                            <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                            </div>
                            <span class="font-black text-xl tracking-tight text-white">Dream<span class="text-pink-200">Home</span></span>
                        </div>
                    @endif
                </div>

                {{-- Center: Headline --}}
                <div class="relative z-10 px-10 py-12">
                    <p class="text-[10px] font-black uppercase tracking-[0.25em] text-pink-200 mb-4">Cagayan de Oro</p>
                    <h1 class="text-4xl font-black text-white leading-tight tracking-tight mb-4">
                        Find Your<br><span class="text-pink-200">Dream Home</span><br>Today.
                    </h1>
                    <p class="text-sm text-pink-100/80 font-medium leading-relaxed max-w-xs">
                        Browse available houses, flats, and bungalows across CDO. Book a viewing and move in faster.
                    </p>

                    {{-- Stats row --}}
                    <div class="flex items-center gap-6 mt-10">
                        <div>
                            <p class="text-2xl font-black text-white">50+</p>
                            <p class="text-[10px] font-bold text-pink-200 uppercase tracking-wider">Properties</p>
                        </div>
                        <div class="w-px h-8 bg-white/20"></div>
                        <div>
                            <p class="text-2xl font-black text-white">3</p>
                            <p class="text-[10px] font-bold text-pink-200 uppercase tracking-wider">Branches</p>
                        </div>
                        <div class="w-px h-8 bg-white/20"></div>
                        <div>
                            <p class="text-2xl font-black text-white">CDO</p>
                            <p class="text-[10px] font-bold text-pink-200 uppercase tracking-wider">City</p>
                        </div>
                    </div>
                </div>

                {{-- Bottom: Decorative pattern --}}
                <div class="relative z-10 p-10">
                    <div class="flex gap-2">
                        @foreach(range(1,5) as $i)
                        <div class="h-1 rounded-full bg-white/{{ $i === 1 ? '80' : '20' }} flex-{{ $i === 1 ? '2' : '1' }}"></div>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-pink-200/60 font-bold uppercase tracking-widest mt-4">DreamHome Property Management</p>
                </div>

                {{-- Decorative circle --}}
                <div class="absolute -bottom-32 -right-32 w-80 h-80 rounded-full bg-white/5 border border-white/10"></div>
                <div class="absolute -top-20 -left-20 w-60 h-60 rounded-full bg-white/5 border border-white/10"></div>
            </div>

            {{-- ===== RIGHT FORM PANEL ===== --}}
            <div class="w-full lg:w-1/2 flex items-center justify-center bg-[#F3F4F6] px-6 py-12">
                <div class="w-full max-w-md">

                    {{-- Mobile logo (only on small screens) --}}
                    <div class="flex justify-center mb-8 lg:hidden">
                        @php $logoPath = public_path('images/dreamhome-logo-colored.png'); @endphp
                        @if(file_exists($logoPath))
                            <img src="{{ asset('images/dreamhome-logo-colored.png') }}" alt="DreamHome" class="h-12 w-auto object-contain">
                        @else
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 bg-[#853953] rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                                </div>
                                <span class="font-black text-xl tracking-tight"><span class="text-[#853953]">Dream</span><span class="text-gray-800">Home</span></span>
                            </div>
                        @endif
                    </div>

                    {{-- Form Card --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 px-8 py-10">
                        {{ $slot }}
                    </div>

                </div>
            </div>

        </div>
    </body>
</html>