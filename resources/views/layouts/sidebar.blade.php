<aside class="z-20 hidden w-64 overflow-y-auto bg-white md:block flex-shrink-0 border-r border-gray-200 shadow-sm">
    <div class="py-4 text-gray-500 dark:text-gray-400">
        
        {{-- LOGO SIDEBAR --}}
        <div class="ml-6 flex items-center mb-6 gap-2">
            <span class="text-2xl">🕌</span>
            <div class="flex flex-col">
                <span class="text-lg font-bold text-gray-800">LP3MT</span>
                <span class="text-[10px] font-semibold text-green-600 uppercase tracking-widest">Admin Panel</span>
            </div>
        </div>

        {{-- LIST MENU --}}
        <ul class="space-y-2">
            
            {{-- [PERBAIKAN 1] PAGAR GAIB UNTUK MENU WILAYAH (HANYA ADMIN & VERIFIKATOR) --}}
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'verifikator')
                {{-- SEPARATOR: MASTER DATA --}}
                <li class="px-6 mt-4 mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    Master Data Wilayah
                </li>

                {{-- MENU 1: DATA KECAMATAN --}}
                <li class="relative px-2">
                    <a href="{{ route('kecamatan.index') }}" 
                       class="inline-flex items-center w-full px-4 py-3 text-sm font-semibold transition-colors duration-150 rounded-lg hover:bg-green-50 hover:text-green-700 
                       {{ request()->routeIs('kecamatan.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600 shadow-sm' : 'text-gray-600' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="ml-1">Data Kecamatan</span>
                    </a>
                </li>

                {{-- MENU 2: DATA DESA --}}
                <li class="relative px-2">
                    <a href="{{ route('desa.index') }}" 
                       class="inline-flex items-center w-full px-4 py-3 text-sm font-semibold transition-colors duration-150 rounded-lg hover:bg-green-50 hover:text-green-700
                       {{ request()->routeIs('desa.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600 shadow-sm' : 'text-gray-600' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="ml-1">Data Desa</span>
                    </a>
                </li>
            @endif
            {{-- AKHIR PAGAR GAIB WILAYAH --}}

            {{-- SEPARATOR: KELEMBAGAAN --}}
            <li class="px-6 mt-6 mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">
                Kelembagaan & Guru
            </li>

            {{-- MENU 3: DATA LEMBAGA (TPQ/MADIN) --}}
            <li class="relative px-2">
                <a href="{{ route('lembaga.index') }}" 
                   class="inline-flex items-center w-full px-4 py-3 text-sm font-semibold transition-colors duration-150 rounded-lg hover:bg-green-50 hover:text-green-700
                   {{ request()->routeIs('lembaga.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600 shadow-sm' : 'text-gray-600' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                    <span class="ml-1">Data Lembaga</span>
                </a>
            </li>

            {{-- ================================================= --}}
            {{-- [PERBAIKAN 2] ROUTING MENU GURU --}}
            {{-- ================================================= --}}

            {{-- 4a. DATA GURU MADIN --}}
            <li class="relative px-2">
                <a href="{{ route('guru.madin') }}" 
                   class="inline-flex items-center w-full px-4 py-3 text-sm font-semibold transition-colors duration-150 rounded-lg hover:bg-green-50 hover:text-green-700
                   {{ request()->routeIs('guru.madin') ? 'bg-green-50 text-green-700 border-l-4 border-green-600 shadow-sm' : 'text-gray-600' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="ml-1">Data Guru Madin</span>
                </a>
            </li>

            {{-- 4b. DATA GURU TPQ --}}
            <li class="relative px-2">
                <a href="{{ route('guru.tpq') }}" 
                   class="inline-flex items-center w-full px-4 py-3 text-sm font-semibold transition-colors duration-150 rounded-lg hover:bg-green-50 hover:text-green-700
                   {{ request()->routeIs('guru.tpq') ? 'bg-green-50 text-green-700 border-l-4 border-green-600 shadow-sm' : 'text-gray-600' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="ml-1">Data Guru TPQ</span>
                </a>
            </li>

            {{-- 4c. DATA GURU PONPES --}}
            <li class="relative px-2">
                <a href="{{ route('guru.ponpes') }}" 
                   class="inline-flex items-center w-full px-4 py-3 text-sm font-semibold transition-colors duration-150 rounded-lg hover:bg-green-50 hover:text-green-700
                   {{ request()->routeIs('guru.ponpes') ? 'bg-green-50 text-green-700 border-l-4 border-green-600 shadow-sm' : 'text-gray-600' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                    <span class="ml-1">Data Guru Ponpes</span>
                </a>
            </li>

            {{-- 4d. SEMUA GURU --}}
            <li class="relative px-2">
                <a href="{{ route('guru.index') }}" 
                   class="inline-flex items-center w-full px-4 py-3 text-sm font-semibold transition-colors duration-150 rounded-lg hover:bg-green-50 hover:text-green-700
                   {{ request()->routeIs('guru.index') ? 'bg-green-50 text-green-700 border-l-4 border-green-600 shadow-sm' : 'text-gray-600' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="ml-1">Semua Guru</span>
                </a>
            </li>

            {{-- 4e. PENERIMA INSENTIF (Highlight Hijau) --}}
            <li class="relative px-2 mt-2">
                <a href="{{ route('guru.insentif') }}" 
                   class="inline-flex items-center w-full px-4 py-3 text-sm font-bold text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors duration-150 shadow-sm
                   {{ request()->routeIs('guru.insentif') ? 'ring-2 ring-green-400' : '' }}">
                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="ml-1">Guru Penerima Insentif</span>
                </a>
            </li>

            {{-- [PERBAIKAN 1] PAGAR GAIB UNTUK MENU USER (HANYA ADMIN & VERIFIKATOR) --}}
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'verifikator')
                {{-- SEPARATOR: PENGATURAN --}}
                <li class="px-6 mt-6 mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    Sistem Admin
                </li>

                {{-- MENU 5: MANAJEMEN USER --}}
                <li class="relative px-2">
                    <a href="{{ route('user.index') }}" 
                       class="inline-flex items-center w-full px-4 py-3 text-sm font-semibold transition-colors duration-150 rounded-lg hover:bg-green-50 hover:text-green-700
                       {{ request()->routeIs('user.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600 shadow-sm' : 'text-gray-600' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="ml-1">Manajemen User</span>
                    </a>
                </li>
            @endif
            {{-- AKHIR PAGAR GAIB MENU USER --}}

        </ul>

        {{-- BANNER BAWAH --}}
        <div class="px-6 my-6">
            <div class="flex items-center justify-between px-4 py-2 text-xs font-bold text-green-100 transition-colors duration-150 bg-green-600 rounded-lg focus:outline-none focus:shadow-outline-green active:bg-green-600">
                <span>Versi 1.0</span>
                <span>Active</span>
            </div>
        </div>
    </div>
</aside>