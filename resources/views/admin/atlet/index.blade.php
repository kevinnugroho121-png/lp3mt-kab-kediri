<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Atlet') }}
        </h2>
    </x-slot>

    {{-- HAPUS py-12 dan max-w-7xl agar FULL WIDTH --}}
    <div class="w-full"> 
        
        {{-- Pesan Sukses (Full Width) --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Container Utama Tanpa Margin Kanan-Kiri --}}
        <div class="bg-white border-b border-gray-200">
            
            {{-- === TOOLBAR (TOMBOL & FILTER) === --}}
            <div class="p-4 flex flex-col lg:flex-row justify-between items-center gap-4 border-b border-gray-100 bg-gray-50/50">
                
                {{-- KIRI: Tombol Tambah --}}
                <a href="{{ route('atlet.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 transition ease-in-out duration-150 shadow-sm whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Atlet
                </a>

                {{-- KANAN: Search & Filter --}}
                <form method="GET" action="{{ route('atlet.index') }}" class="flex flex-wrap justify-end items-center gap-2 w-full lg:w-auto">
                    
                    {{-- Input Pencarian --}}
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..." class="pl-10 block w-full rounded-lg border-gray-300 bg-white text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm h-10">
                    </div>

                    {{-- Filter Dropdowns (Lebih Kompak) --}}
                    <select name="kategori" class="h-10 rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        <option value="KU-10" {{ request('kategori') == 'KU-10' ? 'selected' : '' }}>KU-10</option>
                        <option value="KU-12" {{ request('kategori') == 'KU-12' ? 'selected' : '' }}>KU-12</option>
                        <option value="KU-14" {{ request('kategori') == 'KU-14' ? 'selected' : '' }}>KU-14</option>
                        <option value="KU-16" {{ request('kategori') == 'KU-16' ? 'selected' : '' }}>KU-16</option>
                        <option value="KU-18" {{ request('kategori') == 'KU-18' ? 'selected' : '' }}>KU-18</option>
                    </select>

                    <select name="status" class="h-10 rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        <option value="Keluar" {{ request('status') == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>

                    {{-- Tombol Reset --}}
                    @if(request('kategori') || request('status') || request('search'))
                        <a href="{{ route('atlet.index') }}" class="h-10 inline-flex items-center px-3 bg-gray-100 border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- === TABEL DATA (FULL WIDTH & FIXED LAYOUT) === --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 table-fixed">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b border-gray-200">
                        <tr>
                            {{-- Lebar kolom diatur manual agar pas 1 layar --}}
                            <th scope="col" class="px-3 py-3 w-12 text-center">No</th>
                            <th scope="col" class="px-3 py-3 w-1/4">Nama Lengkap</th>
                            <th scope="col" class="px-3 py-3 w-32">Umur</th>
                            <th scope="col" class="px-3 py-3 w-1/4">Sekolah</th>
                            <th scope="col" class="px-3 py-3 w-24 text-center">Kategori</th>
                            <th scope="col" class="px-3 py-3 w-28 text-center">Status</th>
                            <th scope="col" class="px-3 py-3 w-32 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($atlets as $index => $atlet)
                            <tr class="hover:bg-blue-50/50 transition duration-150">
                                {{-- No --}}
                                <td class="px-3 py-3 text-center font-medium text-gray-900">
                                    {{ $atlets->firstItem() + $index }}
                                </td>
                                
                                {{-- Nama (Bold & Truncate) --}}
                                <td class="px-3 py-3 font-bold text-gray-800 truncate pr-4" title="{{ $atlet->nama_lengkap }}">
                                    {{ $atlet->nama_lengkap }}
                                </td>
                                
                                {{-- Umur (Compact) --}}
                                <td class="px-3 py-3">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($atlet->tanggal_lahir)->translatedFormat('d M Y') }}</span>
                                        <span class="text-xs text-blue-700 font-bold bg-blue-100 px-2 py-0.5 rounded-full w-fit mt-0.5">
                                            {{ \Carbon\Carbon::parse($atlet->tanggal_lahir)->age }} Thn
                                        </span>
                                    </div>
                                </td>

                                {{-- Sekolah (Truncate agar tidak lebar) --}}
                                <td class="px-3 py-3 truncate pr-4 text-gray-600" title="{{ $atlet->nama_sekolah }}">
                                    <span class="font-medium text-xs border border-gray-200 px-1 rounded mr-1">{{ $atlet->jenjang_sekolah }}</span>
                                    {{ $atlet->nama_sekolah }}
                                </td>
                                
                                {{-- Kategori --}}
                                <td class="px-3 py-3 text-center">
                                    <span class="inline-flex text-xs font-bold px-2 py-1 rounded bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $atlet->kategori }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-3 py-3 text-center">
                                    @if($atlet->status == 'Aktif')
                                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-green-100 text-green-700 border border-green-200">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Aktif
                                        </span>
                                    @elseif($atlet->status == 'Non-Aktif')
                                        <span class="inline-flex text-xs font-bold px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200">Cuti/Non</span>
                                    @else
                                        <span class="inline-flex text-xs font-bold px-2 py-1 rounded-full bg-red-100 text-red-700 border border-red-200">Keluar</span>
                                    @endif
                                </td>
                                
                                {{-- Aksi (Icon Only agar hemat tempat) --}}
                                <td class="px-3 py-3 text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        {{-- View --}}
                                        <a href="{{ route('atlet.show', $atlet->id) }}" class="p-1.5 bg-gray-100 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition" title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        
                                        {{-- Edit --}}
                                        <a href="{{ route('atlet.edit', $atlet->id) }}" class="p-1.5 bg-gray-100 text-yellow-600 rounded hover:bg-yellow-500 hover:text-white transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        {{-- Hapus --}}
                                        <form action="{{ route('atlet.destroy', $atlet->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-gray-100 text-red-600 rounded hover:bg-red-600 hover:text-white transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>Tidak ada data atlet ditemukan.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer / Pagination --}}
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $atlets->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</x-app-layout>