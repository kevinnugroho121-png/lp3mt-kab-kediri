<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LP3MT') }}</title>

    {{-- 1. FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- 2. VITE --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- 3. LIBRARY --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar (Sembunyikan tapi tetap bisa scroll) */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    
    <div class="min-h-screen flex flex-col">
        
        {{-- ======================================================== --}}
        {{-- NAVBAR ATAS --}}
        {{-- ======================================================== --}}
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05)]">
            <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    
                    {{-- 1. LOGO KIRI --}}
                    <a href="{{ route('dashboard') }}" class="transform hover:scale-105 transition duration-200">
                        <img src="{{ asset('images/logo_lp3mt.png') }}" 
                             alt="Logo LP3MT" 
                             class="h-12 w-auto object-contain">
                    </a>

                    {{-- 2. MENU UTAMA (SAMA UNTUK SEMUA ROLE KECUALI WILAYAH & USER) --}}
                    <div class="flex-1 flex items-center justify-start overflow-x-auto hide-scroll px-4 py-2">
                        <div class="flex items-center gap-2"> 
                            
                            {{-- A. DASHBOARD (SEMUA ROLE) --}}
                            <a href="{{ route('dashboard') }}" 
                               class="flex items-center justify-center h-12 px-5 rounded-lg transition-all duration-200 border border-transparent
                               {{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-700 border-green-200 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-green-700' }}">
                               <span class="text-sm font-bold">DASHBOARD</span>
                            </a>
                            
                            {{-- B. DATA WILAYAH (HANYA ADMIN PUSAT) --}}
                            @if(Auth::user()->role == 'admin')
                                <a href="{{ route('kecamatan.index') }}" 
                                   class="flex items-center justify-center h-12 px-4 rounded-lg transition-all duration-200 border border-transparent
                                   {{ request()->routeIs('kecamatan.*', 'desa.*') ? 'bg-green-50 text-green-700 border-green-200 shadow-sm font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-green-700 font-medium' }}">
                                   <span class="text-sm font-bold">DATA WILAYAH</span>
                                </a>

                                {{-- DIVIDER --}}
                                <div class="h-8 w-px bg-gray-200 mx-1"></div>
                            @endif

                            {{-- C. DATA LEMBAGA (SEMUA ROLE) --}}
                            <a href="{{ route('lembaga.index') }}" 
                               class="flex flex-col items-center justify-center h-12 px-4 rounded-lg transition-all duration-200 border border-transparent leading-none
                               {{ request()->routeIs('lembaga.*') ? 'bg-green-50 text-green-700 border-green-200 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-green-700' }}">
                               <span class="text-[10px] font-medium uppercase tracking-wide opacity-80 mb-0.5">DATA</span>
                               <span class="text-sm font-bold">LEMBAGA</span>
                            </a>

                            {{-- D. GURU MADIN (SEMUA ROLE) --}}
                            <a href="{{ route('guru.madin') }}" 
                               class="flex flex-col items-center justify-center h-12 px-4 rounded-lg transition-all duration-200 border border-transparent leading-none
                               {{ request()->routeIs('guru.madin') ? 'bg-green-50 text-green-700 border-green-200 shadow-sm font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-green-700 font-medium' }}">
                               <span class="text-[10px] font-medium uppercase tracking-wide opacity-70 mb-0.5">GURU</span>
                               <span class="text-sm font-bold">MADIN</span>
                            </a>

                            {{-- E. GURU TPQ (SEMUA ROLE) --}}
                            <a href="{{ route('guru.tpq') }}" 
                               class="flex flex-col items-center justify-center h-12 px-4 rounded-lg transition-all duration-200 border border-transparent leading-none
                               {{ request()->routeIs('guru.tpq') ? 'bg-green-50 text-green-700 border-green-200 shadow-sm font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-green-700 font-medium' }}">
                               <span class="text-[10px] font-medium uppercase tracking-wide opacity-70 mb-0.5">GURU</span>
                               <span class="text-sm font-bold">TPQ</span>
                            </a>

                            {{-- F. GURU PONPES (SEMUA ROLE) --}}
                            <a href="{{ route('guru.ponpes') }}" 
                               class="flex flex-col items-center justify-center h-12 px-4 rounded-lg transition-all duration-200 border border-transparent leading-none
                               {{ request()->routeIs('guru.ponpes') ? 'bg-green-50 text-green-700 border-green-200 shadow-sm font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-green-700 font-medium' }}">
                               <span class="text-[10px] font-medium uppercase tracking-wide opacity-70 mb-0.5">GURU</span>
                               <span class="text-sm font-bold">PONPES</span>
                            </a>

                            {{-- G. SEMUA GURU (SEMUA ROLE) --}}
                            <a href="{{ route('guru.index') }}" 
                               class="flex flex-col items-center justify-center h-12 px-4 rounded-lg transition-all duration-200 border border-transparent leading-none
                               {{ request()->routeIs('guru.index') ? 'bg-green-50 text-green-700 border-green-200 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-green-700 font-medium' }}">
                               <span class="text-[10px] font-medium uppercase tracking-wide opacity-80 mb-0.5">Semua</span>
                               <span class="text-sm font-bold">GURU</span>
                            </a>

                            {{-- H. INSENTIF (SEMUA ROLE) --}}
                            <a href="{{ route('guru.insentif') }}" 
                               class="flex flex-col items-center justify-center h-12 px-4 rounded-lg transition-all duration-200 border border-transparent leading-none
                               {{ request()->routeIs('guru.insentif') ? 'bg-green-50 text-green-700 border-green-200 shadow-sm font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-green-700 font-medium' }}">
                               <span class="text-[10px] font-medium uppercase tracking-wide opacity-70 mb-0.5">MENU</span>
                               <span class="text-sm font-bold flex items-center gap-1">INSENTIF</span>
                            </a>

                            {{-- I. MANAJEMEN USER & GALERI FOTO --}}
                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'verifikator' || Auth::user()->role == 'superadmin')
                                {{-- DIVIDER --}}
                                <div class="h-8 w-px bg-gray-200 mx-1"></div>
                                
                                <a href="{{ route('user.index') }}" 
                                   class="flex flex-col items-center justify-center h-12 px-4 rounded-lg transition-all duration-200 border border-transparent leading-none
                                   {{ request()->routeIs('user.*') ? 'bg-blue-50 text-blue-700 border-blue-200 shadow-sm' : 'text-gray-500 hover:bg-blue-50 hover:text-blue-700' }}">
                                   <span class="text-[10px] font-medium uppercase tracking-wide opacity-80 mb-0.5">MANAJEMEN</span>
                                   <span class="text-sm font-bold">USER</span>
                                </a>

                                {{-- J. [BARU] MENU GALERI DOKUMENTASI --}}
                                <a href="{{ route('dokumentasi.index') }}" 
                                   class="flex flex-col items-center justify-center h-12 px-4 rounded-lg transition-all duration-200 border border-transparent leading-none
                                   {{ request()->routeIs('dokumentasi.*') ? 'bg-blue-50 text-blue-700 border-blue-200 shadow-sm' : 'text-gray-500 hover:bg-blue-50 hover:text-blue-700' }}">
                                   <span class="text-[10px] font-medium uppercase tracking-wide opacity-80 mb-0.5">GALERI</span>
                                   <span class="text-sm font-bold">FOTO</span>
                                </a>
                            @endif

                        </div>
                    </div>

                    {{-- 3. PROFIL AVATAR (KANAN) --}}
                    <div class="shrink-0 flex items-center ml-4">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 focus:outline-none group">
                                <div class="text-right hidden xl:block">
                                    <p class="text-sm font-bold text-gray-700 group-hover:text-green-700 transition">{{ Auth::user()->name }}</p>
                                </div>
                                <div class="relative">
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-white ring-2 ring-gray-100 group-hover:ring-green-200 transition shadow-sm" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff&bold=true&size=128" 
                                         alt="Profile" />
                                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                                </div>
                            </button>
                            {{-- Dropdown Profil & Logout --}}
                            <div x-show="open" x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 origin-top-right">
                                
                                {{-- Info User --}}
                                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                {{-- Tombol Profil --}}
                                <div class="p-1 border-b border-gray-50">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Edit Profil & Password
                                    </a>
                                </div>

                                {{-- Tombol Logout --}}
                                <form method="POST" action="{{ route('logout') }}" class="p-1">
                                    @csrf
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 rounded-lg hover:bg-red-50 transition font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Log Out
                                    </a>
                                </form>
                            </div>



                        </div>
                    </div>

                </div>
            </div>
        </nav>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 w-full max-w-[1500px] mx-auto py-8 px-4 sm:px-6 lg:px-8">
            {{ $slot ?? '' }} 
            @yield('content')
        </main>
        
        <footer class="py-6 text-center text-xs text-gray-400 border-t border-gray-100 mt-auto">
            &copy; {{ date('Y') }} LP3MT Kabupaten Kediri. All rights reserved.
        </footer>

    </div>

    {{-- SWEETALERT --}}
    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", showConfirmButton: false, timer: 3000, timerProgressBar: true }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#d33', }); @endif
        @if(session('warning')) Swal.fire({ icon: 'warning', title: 'Perhatian', text: "{{ session('warning') }}", confirmButtonColor: '#f59e0b', }); @endif
    </script>

</body>
</html>