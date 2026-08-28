<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LP3MT') }}</title>

        {{-- Favicon Tab Browser --}}
        <link rel="icon" type="image/png" href="{{ asset('images/logo_lp3mt.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    {{-- PAKSA BACKGROUND HIJAU DISINI --}}
    <body class="font-sans text-gray-900 antialiased h-screen w-full overflow-hidden" 
          style="background: linear-gradient(135deg, #4ade80 0%, #166534 100%);">
        
        <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0">
            
            {{-- Bagian Form Login (Card Putih) --}}
            <div class="w-full sm:max-w-md bg-white shadow-2xl rounded-2xl overflow-hidden relative z-10 p-8">
                {{ $slot }}
            </div>

            {{-- Footer Copyright (Di luar kotak putih) --}}
            <div class="mt-8 text-white text-xs font-medium tracking-wider opacity-80">
                &copy; {{ date('Y') }} LP3MT Kabupaten Kediri. All rights reserved.
            </div>

            {{-- Hiasan Background (Opsional: Lingkaran samar di pojok kanan bawah) --}}
            <div class="absolute bottom-0 right-0 transform translate-x-1/4 translate-y-1/4">
                <svg width="400" height="400" fill="none" viewBox="0 0 400 400">
                    <circle cx="200" cy="200" r="200" fill="white" fill-opacity="0.1"/>
                </svg>
            </div>
        </div>
    </body>
</html>