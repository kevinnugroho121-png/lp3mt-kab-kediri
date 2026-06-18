<nav class="bg-white border-b border-gray-200 shadow-sm z-30 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- BAGIAN KIRI: LOGO & JUDUL --}}
            <div class="flex items-center gap-3">
                {{-- Toggle Sidebar (Mobile Only) - Opsional kalau nanti butuh responsive --}}
                
                {{-- Brand --}}
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🕌</span> {{-- Ikon Masjid --}}
                    <div class="flex flex-col">
                        <span class="font-bold text-lg text-green-700 leading-tight">LP3MT</span>
                        <span class="text-[10px] text-black-500 uppercase tracking-wider font-semibold">Kabupaten Kediri</span>
                    </div>
                </div>
            </div>

            {{-- BAGIAN KANAN: PROFIL USER & LOGOUT --}}
            <div class="flex items-center gap-4">
                
                {{-- Teks Sistem (Disembunyikan di HP biar gak sempit) --}}
                <div class="hidden md:block text-sm text-black-400 font-medium mr-2">
                    Sistem Pendataan Guru Ngaji
                </div>

                {{-- Garis Pemisah --}}
                <div class="h-8 w-px bg-gray-200 hidden md:block"></div>

                {{-- Dropdown Profil --}}
                <div class="relative ml-3 group">
                    <button class="flex items-center gap-2 text-sm focus:outline-none transition duration-150 ease-in-out">
                        <div class="text-right hidden md:block">
                            {{-- Nama User --}}
                            <div class="font-bold text-black-700">
                                {{ Auth::user()->name ?? 'Guest' }}
                            </div>
                            {{-- Role User (Otomatis ambil label role dari Model User) --}}
                            <div class="text-xs text-green-600 font-medium">
                                {{ Auth::user()->role_label ?? 'Tamu' }}
                            </div>
                        </div>

                        {{-- Avatar / Foto Profil Dummy --}}
                        <div class="h-9 w-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold border border-green-200">
                            {{ substr(Auth::user()->name ?? 'G', 0, 1) }}
                        </div>

                        {{-- Panah Kecil --}}
                        <svg class="h-4 w-4 text-black-400 group-hover:text-black-600 transition" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    {{-- Isi Dropdown (Logout) --}}
                    {{-- Menggunakan CSS group-hover untuk hover simpel tanpa JS ribet --}}
                    <div class="absolute right-0 w-48 mt-2 origin-top-right bg-white border border-gray-100 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform z-50">
                        <div class="py-1">
                            {{-- Tombol Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-red-50 hover:text-red-600 transition">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar (Logout)
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</nav>