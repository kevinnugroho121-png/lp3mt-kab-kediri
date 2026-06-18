<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            {{ __('Data Desa') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER: JUDUL & NAVIGASI --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-4 gap-4">
                
                {{-- KIRI: JUDUL & TOMBOL KEMBALI --}}
                <div class="flex flex-col items-start gap-2">
                    {{-- Tombol Kembali ke Kecamatan --}}
                    <a href="{{ route('kecamatan.index') }}" class="inline-flex items-center gap-1 text-black-500 hover:text-black-700 text-sm font-medium transition mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Kecamatan
                    </a>
                    <div>
                        <h3 class="font-bold text-2xl text-black-800 leading-none">Data Desa / Kelurahan</h3>
                        <p class="text-xs text-black-500 mt-1">
                            @if(request('kecamatan_id'))
                                Menampilkan desa di kecamatan terpilih
                            @else
                                Daftar semua desa di Kabupaten Kediri
                            @endif
                        </p>
                    </div>
                </div>
                
                {{-- KANAN: SEARCH & ACTION --}}
                <div class="flex items-center gap-2">
                    
                    {{-- FORM PENCARIAN (Sejajar) --}}
                    <form action="{{ route('desa.index') }}" method="GET" class="flex items-center gap-2">
                        {{-- Simpan filter kecamatan jika ada (Hidden Input) --}}
                        @if(request('kecamatan_id'))
                            <input type="hidden" name="kecamatan_id" value="{{ request('kecamatan_id') }}">
                        @endif

                        {{-- Input Search --}}
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari Desa..." 
                               class="border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-green-500 text-sm w-48 lg:w-64">
                        
                        {{-- Tombol Cari (Manual - Biru) --}}
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm font-bold transition shadow-sm border border-blue-600">
                            Cari
                        </button>

                        {{-- Tombol Reset (Standby - Abu-abu) --}}
                        {{-- Logika: Jika ada kecamatan_id, reset hanya search-nya saja, tetap di kecamatan itu --}}
                        <a href="{{ route('desa.index', request('kecamatan_id') ? ['kecamatan_id' => request('kecamatan_id')] : []) }}" 
                           class="bg-gray-100 hover:bg-gray-200 text-black-600 px-3 py-1.5 rounded-lg text-sm font-bold transition shadow-sm border border-gray-300">
                            Reset
                        </a>
                    </form>

                    {{-- TOMBOL TAMBAH (Hijau) --}}
                    <a href="{{ route('desa.create') }}" class="ml-2 bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg text-sm font-bold flex items-center gap-1 shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah
                    </a>
                </div>
            </div>

            {{-- PESAN SUKSES --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-sm rounded shadow-sm flex justify-between items-center">
                    <span>✅ {{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900 font-bold">&times;</button>
                </div>
            @endif

            {{-- TABEL ALA EXCEL (BORDERED & COMPACT) --}}
            <div class="bg-white border border-gray-400 overflow-hidden shadow-sm">
                <table class="w-full text-sm border-collapse">
                    {{-- HEADER --}}
                    <thead>
                        <tr class="bg-gray-100 text-black-700 uppercase text-xs tracking-wider">
                            <th class="border border-gray-300 px-2 py-2 text-center w-12 font-bold">No</th>
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold">Nama Desa</th>
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold w-1/3">Kecamatan</th>
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold w-32">Aksi</th>
                        </tr>
                    </thead>
                    
                    {{-- BODY --}}
                    <tbody class="text-black-600">
                        @forelse($desas as $index => $desa)
                            <tr class="hover:bg-yellow-50 transition duration-150">
                                
                                {{-- NO --}}
                                <td class="border border-gray-300 px-2 py-1 text-center bg-gray-50 font-medium">
                                    {{ $desas->firstItem() + $index }}
                                </td>

                                {{-- NAMA DESA --}}
                                <td class="border border-gray-300 px-3 py-1 font-bold text-black-800">
                                    {{ $desa->nama_desa }}
                                </td>

                                {{-- KECAMATAN --}}
                                <td class="border border-gray-300 px-3 py-1">
                                    {{ $desa->kecamatan->nama_kecamatan ?? '-' }}
                                </td>

                                {{-- AKSI --}}
                                <td class="border border-gray-300 px-2 py-1 text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        {{-- Edit --}}
                                        <a href="{{ route('desa.edit', $desa->id) }}" class="p-1 rounded hover:bg-orange-100 text-orange-500 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        
                                        {{-- Hapus --}}
                                        <form action="{{ route('desa.destroy', $desa->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus desa ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded hover:bg-red-100 text-red-500 transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border border-gray-300 px-4 py-8 text-center text-black-400 bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 text-black-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <span class="text-xs">Data Desa Belum Ada</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- PAGINATION --}}
            <div class="mt-4">
                {{ $desas->withQueryString()->links() }}
            </div>

        </div>
    </div>
</x-app-layout>