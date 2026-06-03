<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Progres Atlet') }}
        </h2>
    </x-slot>

    {{-- CONTAINER UTAMA: Full Width & Full Height (Tanpa Scroll Window) --}}
    <div class="w-full h-[calc(100vh-70px)] bg-gray-50 p-4 flex flex-col overflow-hidden">

        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex justify-between items-center" role="alert">
                <p class="font-bold">✅ {{ session('success') }}</p>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">&times;</button>
            </div>
        @endif

        {{-- CARD UTAMA --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col h-full overflow-hidden">
            
            {{-- 1. TOOLBAR (Search & Filter) --}}
            <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                
                {{-- Judul Kecil --}}
                <div class="hidden md:block">
                    <h3 class="font-bold text-gray-800 text-lg">Daftar Atlet Binaan</h3>
                    <p class="text-xs text-gray-500">Pilih atlet untuk mengisi rapor.</p>
                </div>

                {{-- Form Filter --}}
                <form action="{{ route('pelatih.progres.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                    
                    {{-- Filter Kategori --}}
                    <select name="kategori" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-green-500 focus:border-green-500 cursor-pointer">
                        <option value="">Semua Kategori</option>
                        <option value="KU-10" {{ request('kategori') == 'KU-10' ? 'selected' : '' }}>KU-10 Mix</option>
                        <option value="KU-12" {{ request('kategori') == 'KU-12' ? 'selected' : '' }}>KU-12</option>
                        <option value="KU-14" {{ request('kategori') == 'KU-14' ? 'selected' : '' }}>KU-14</option>
                        <option value="KU-16" {{ request('kategori') == 'KU-16' ? 'selected' : '' }}>KU-16</option>
                        <option value="KU-18" {{ request('kategori') == 'KU-18' ? 'selected' : '' }}>KU-18</option>
                    </select>

                    {{-- Search Input --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atlet..." 
                               class="pl-10 rounded-lg border-gray-300 text-sm focus:ring-green-500 focus:border-green-500 w-full sm:w-64">
                    </div>

                    {{-- Tombol Cari --}}
                    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                        Cari
                    </button>
                    
                    {{-- Tombol Reset (Muncul jika sedang filter) --}}
                    @if(request('search') || request('kategori'))
                        <a href="{{ route('pelatih.progres.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-lg text-sm font-bold text-center transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- 2. TABEL DATA (Scrollable Internal) --}}
            <div class="flex-1 overflow-y-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th scope="col" class="px-6 py-4 w-12 text-center">No</th>
                            <th scope="col" class="px-6 py-4">Nama Atlet</th>
                            <th scope="col" class="px-6 py-4">Kategori (KU)</th>
                            <th scope="col" class="px-6 py-4">Posisi</th>
                            <th scope="col" class="px-6 py-4 text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($atlets as $index => $atlet)
                            <tr class="hover:bg-green-50 transition duration-150">
                                {{-- Nomor Urut (Sesuai Halaman) --}}
                                <td class="px-6 py-4 text-center font-medium text-gray-900">
                                    {{ $atlets->firstItem() + $index }}
                                </td>
                                
                                {{-- Nama --}}
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800 text-base">{{ $atlet->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-400">Gender: {{ $atlet->jenis_kelamin }}</div>
                                </td>

                                {{-- Kategori --}}
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded border border-blue-200">
                                        {{ $atlet->kategori }}
                                    </span>
                                </td>

                                {{-- Posisi --}}
                                <td class="px-6 py-4">
                                    {{ $atlet->posisi ?? '-' }}
                                </td>

                                {{-- Tombol Aksi --}}
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('pelatih.progres.create', $atlet->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md transform hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Isi Rapor
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 bg-white">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        <p class="text-base font-semibold">Atlet tidak ditemukan.</p>
                                        <p class="text-sm mt-1">Coba ubah kata kunci pencarian atau filter.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 3. FOOTER PAGINATION (Panah Kanan Kiri) --}}
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $atlets->links() }} 
            </div>

        </div>
    </div>
</x-app-layout>