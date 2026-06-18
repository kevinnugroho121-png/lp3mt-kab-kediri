<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            {{ __('Master Data Wilayah') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER: JUDUL & TOMBOL --}}
            <div class="flex justify-between items-center mb-4">
                {{-- Bagian Kiri: Judul --}}
                <div>
                    <h3 class="font-bold text-lg text-black-800">Data Kecamatan & Desa</h3>
                    @if(Auth::user()->role == 'korcam')
                        <p class="text-xs text-green-600 font-bold">Wilayah Kerja: {{ Auth::user()->kecamatan->nama_kecamatan ?? '-' }}</p>
                    @else
                        <p class="text-xs text-black-500">Kelola data wilayah administratif Kabupaten Kediri</p>
                    @endif
                </div>
                
                {{-- Bagian Kanan: Search & Tambah (HANYA JIKA BUKAN KORCAM) --}}
                @if(Auth::user()->role != 'korcam')
                    <div class="flex gap-2">
                        {{-- SEARCH BOX DENGAN TOMBOL MANUAL --}}
                        <form action="{{ route('kecamatan.index') }}" method="GET" class="flex items-center gap-1">
                            
                            {{-- Input Pencarian --}}
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kecamatan..." 
                                   class="w-48 sm:w-64 border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 shadow-sm h-9">
                            
                            {{-- Tombol Cari (Biru) --}}
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 rounded-md text-sm font-bold shadow-sm transition h-9 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cari
                            </button>

                            {{-- Tombol Reset (Selalu Tampil / Standby) --}}
                            <a href="{{ route('kecamatan.index') }}" class="bg-gray-100 hover:bg-gray-200 text-black-600 px-3 py-1.5 rounded-lg text-sm font-bold transition shadow-sm border border-gray-300">
                                Reset
                            </a>
                        </form>

                        {{-- TOMBOL TAMBAH (Hijau) --}}
                        <a href="{{ route('kecamatan.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 h-9 rounded-md text-sm font-medium flex items-center gap-1 shadow-sm transition ml-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Data
                        </a>
                    </div>
                @endif
            </div>

            {{-- PESAN SUKSES --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-sm rounded shadow-sm flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900 font-bold">&times;</button>
                </div>
            @endif

            {{-- PESAN ERROR (Jika Korcam coba hapus) --}}
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 text-sm rounded shadow-sm flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900 font-bold">&times;</button>
                </div>
            @endif

            {{-- TABEL ALA EXCEL (BORDERED & COMPACT) --}}
            <div class="bg-white border border-gray-400 overflow-hidden shadow-sm">
                <table class="w-full text-sm border-collapse">
                    {{-- HEADER --}}
                    <thead>


                        <tr class="bg-gray-100 text-black-700 uppercase text-xs tracking-wider">
                            <th class="border border-gray-300 px-2 py-2 text-center w-12 font-bold">No</th>
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold">Nama Kecamatan</th>
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold w-48">Data Desa</th> 
                            {{-- [BARU] Sisipkan kolom kuota insentif di sini --}}
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold w-64">Jatah Kuota Insentif</th> 
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold w-32">Aksi</th>
                        </tr>


                    </thead>
                    
                    {{-- BODY --}}
                    <tbody class="text-black-600">
                        @forelse($kecamatans as $index => $kecamatan)
                            <tr class="hover:bg-yellow-50 transition duration-150">
                                
                                {{-- NO --}}
                                <td class="border border-gray-300 px-2 py-1 text-center bg-gray-50 font-medium">
                                    {{ $kecamatans->firstItem() + $index }}
                                </td>

                                {{-- NAMA KECAMATAN --}}
                                <td class="border border-gray-300 px-3 py-1 font-bold text-black-800">
                                    {{ $kecamatan->nama_kecamatan }}
                                </td>

                                {{-- JUMLAH DESA (LINK KHUSUS) --}}
                                <td class="border border-gray-300 px-2 py-1 text-center p-0">
                                    <a href="{{ route('desa.index', ['kecamatan_id' => $kecamatan->id]) }}" class="group flex items-center justify-between w-full h-full px-3 py-1 text-xs hover:bg-blue-50 transition cursor-pointer">
                                        <span class="font-bold text-blue-600 group-hover:underline">
                                            {{ $kecamatan->desa_count ?? 0 }} Desa
                                        </span>
                                        <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>

                                {{-- [BARU - FASE 2] LOGIKA FORM INPUT KUOTA DINAMIS --}}
                                <td class="border border-gray-300 px-3 py-1 text-center">
                                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'verifikator')
                                        {{-- Jika Superadmin/Verifikator, tampilkan Form Input Instan --}}
                                        <form action="{{ route('kecamatan.update_kuota', $kecamatan->id) }}" method="POST" class="flex items-center gap-1 justify-center">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="kuota_insentif" value="{{ old('kuota_insentif', $kecamatan->kuota_insentif ?? 0) }}" min="0"
                                                   class="w-20 border border-gray-300 rounded px-2 py-0.5 text-center text-xs font-bold text-blue-800 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm transition">
                                                Simpan
                                            </button>
                                        </form>
                                    @else
                                        {{-- Jika Korcam, Cukup Kunci & Tampilkan Angkanya Saja (Read-Only) --}}
                                        <span class="px-2.5 py-0.5 rounded-full bg-blue-50 border border-blue-300 text-blue-700 font-bold text-xs">
                                            🎯 {{ $kecamatan->kuota_insentif ?? 0 }} Kuota Guru
                                        </span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="border border-gray-300 px-2 py-1 text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        {{-- Edit (Korcam Boleh Edit Kecamatannya Sendiri) --}}
                                        <a href="{{ route('kecamatan.edit', $kecamatan->id) }}" class="p-1 rounded hover:bg-orange-100 text-orange-500 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        
                                        {{-- Hapus (HANYA ADMIN) --}}
                                        @if(Auth::user()->role == 'admin')
                                            <form action="{{ route('kecamatan.destroy', $kecamatan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus kecamatan ini? Semua desa didalamnya juga akan terhapus!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 rounded hover:bg-red-100 text-red-500 transition" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border border-gray-300 px-4 py-8 text-center text-black-400 bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 text-black-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <span class="text-xs">Data Belum Ada</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- PAGINATION --}}
            <div class="mt-4">
                {{ $kecamatans->withQueryString()->links() }}
            </div>

        </div>
    </div>
</x-app-layout>