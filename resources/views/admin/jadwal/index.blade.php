<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Jadwal Latihan') }}
        </h2>
    </x-slot>

    {{-- WRAPPER UTAMA: FULL WIDTH (Tanpa max-w-7xl) --}}
    <div class="w-full">
        
        {{-- 1. ALERT PESAN SUKSES --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- 2. ALERT KHUSUS MODE ABSENSI (Jika ada request ?fokus=absensi) --}}
        @if(request('fokus') == 'absensi')
            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 m-4 flex items-center justify-between shadow-sm animate-pulse">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📋</span>
                    <div>
                        <h3 class="font-bold text-indigo-900">Mode Penilaian & Absensi</h3>
                        <p class="text-sm text-indigo-700">
                            Silakan klik tombol <span class="font-bold bg-white border border-indigo-200 px-1 rounded text-indigo-800">📋 ABSEN</span> pada jadwal di bawah.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- CONTAINER TABEL --}}
        <div class="bg-white border-b border-gray-200">
            
            {{-- === TOOLBAR: TOMBOL & FILTER === --}}
            <div class="p-4 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 border-b border-gray-100 bg-gray-50/50">
                
                {{-- KIRI: Tombol Tambah --}}
                <a href="{{ route('jadwal.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition shadow-sm whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Jadwal
                </a>

                {{-- KANAN: Form Filter --}}
                <form method="GET" action="{{ route('jadwal.index') }}" class="flex flex-wrap items-center gap-2 w-full xl:w-auto">
                    
                    {{-- Filter Kategori --}}
                    <select name="kategori" class="h-10 rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white">
                        <option value="">-- Semua Kategori --</option>
                        <option value="KU-10" {{ request('kategori') == 'KU-10' ? 'selected' : '' }}>KU-10</option>
                        <option value="KU-12" {{ request('kategori') == 'KU-12' ? 'selected' : '' }}>KU-12</option>
                        <option value="KU-14" {{ request('kategori') == 'KU-14' ? 'selected' : '' }}>KU-14</option>
                        <option value="KU-16" {{ request('kategori') == 'KU-16' ? 'selected' : '' }}>KU-16</option>
                        <option value="KU-18" {{ request('kategori') == 'KU-18' ? 'selected' : '' }}>KU-18</option>
                    </select>

                    {{-- Filter Tanggal (Start - End) --}}
                    <div class="flex items-center gap-2 bg-white border border-gray-300 rounded-lg p-1 shadow-sm h-10">
                        <input type="date" name="mulai_tanggal" value="{{ request('mulai_tanggal') }}" class="border-none text-sm focus:ring-0 p-1 w-32 h-full text-gray-600">
                        <span class="text-gray-400 font-bold">-</span>
                        <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" class="border-none text-sm focus:ring-0 p-1 w-32 h-full text-gray-600">
                    </div>

                    {{-- Tombol Filter --}}
                    <button type="submit" class="h-10 px-4 bg-gray-800 text-white rounded-lg text-xs font-bold uppercase hover:bg-gray-700 transition">
                        Filter
                    </button>

                    {{-- Tombol Reset --}}
                    @if(request('kategori') || request('mulai_tanggal') || request('sampai_tanggal'))
                        <a href="{{ route('jadwal.index') }}" class="h-10 inline-flex items-center px-3 bg-red-50 text-red-600 border border-red-200 rounded-lg text-xs font-bold uppercase hover:bg-red-100 transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- === TABEL DATA === --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 table-fixed">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-3 py-3 w-12 text-center">No</th>
                            <th scope="col" class="px-3 py-3 w-48">Tanggal & Jam</th>
                            <th scope="col" class="px-3 py-3 w-24 text-center">Kategori</th>
                            <th scope="col" class="px-3 py-3 w-48">Pelatih (Coach)</th>
                            <th scope="col" class="px-3 py-3 w-auto">Lokasi</th> {{-- Lebar Otomatis --}}
                            <th scope="col" class="px-3 py-3 w-28 text-center">Status</th>
                            <th scope="col" class="px-3 py-3 w-48 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($jadwals as $index => $jadwal)
                            <tr class="hover:bg-blue-50/50 transition duration-150">
                                {{-- No --}}
                                <td class="px-3 py-4 text-center font-medium text-gray-900">
                                    {{ $jadwals->firstItem() + $index }}
                                </td>

                                {{-- Tanggal & Jam --}}
                                <td class="px-3 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d M Y') }}
                                        </span>
                                        <div class="flex items-center gap-1 text-xs text-blue-600 font-semibold mt-1 bg-blue-50 w-fit px-2 py-0.5 rounded">
                                            ⏰ {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                        </div>
                                    </div>
                                </td>

                                {{-- Kategori --}}
                                <td class="px-3 py-4 text-center">
                                    <span class="inline-flex text-xs font-bold px-2 py-1 rounded bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $jadwal->kategori }}
                                    </span>
                                </td>

                                {{-- Pelatih (Truncate agar tidak lebar) --}}
                                <td class="px-3 py-4">
                                    @if($jadwal->pelatih)
                                        @if($jadwal->pelatih->trashed())
                                            <div class="flex flex-col">
                                                <span class="text-xs text-red-600 font-bold bg-red-100 px-2 py-0.5 rounded w-fit mb-1">Dihapus</span>
                                                <span class="text-gray-400 italic truncate" title="{{ $jadwal->pelatih->nama_lengkap }}">{{ $jadwal->pelatih->nama_lengkap }}</span>
                                            </div>
                                        @elseif($jadwal->pelatih->status == 'Non-Aktif')
                                            <div class="flex flex-col">
                                                <span class="text-gray-400 line-through truncate" title="{{ $jadwal->pelatih->nama_lengkap }}">{{ $jadwal->pelatih->nama_lengkap }}</span>
                                                <span class="text-[10px] text-yellow-600 font-bold mt-0.5">Sedang Cuti</span>
                                            </div>
                                        @else
                                            <div class="font-semibold text-gray-700 truncate" title="{{ $jadwal->pelatih->nama_lengkap }}">
                                                Coach {{ $jadwal->pelatih->nama_lengkap }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-red-400 italic text-xs">Belum ditentukan</span>
                                    @endif
                                </td>

                                {{-- Lokasi (Truncate) --}}
                                <td class="px-3 py-4 text-gray-600">
                                    <div class="flex items-center gap-1 truncate" title="{{ $jadwal->lokasi }}">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="truncate">{{ $jadwal->lokasi }}</span>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-3 py-4 text-center">
                                    @if($jadwal->status == 'Aktif')
                                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-green-100 text-green-700 border border-green-200">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Aktif
                                        </span>
                                    @elseif($jadwal->status == 'Batal')
                                        <span class="inline-flex text-xs font-bold px-2 py-1 rounded-full bg-red-100 text-red-700 border border-red-200">Dibatalkan</span>
                                    @else
                                        <span class="inline-flex text-xs font-bold px-2 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">Selesai</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-3 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        
                                        {{-- 1. Tombol Absen --}}
                                        <a href="{{ route('absensi.create', $jadwal->id) }}" class="flex items-center gap-1 bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700 hover:shadow transition transform hover:scale-105" title="Input Absensi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            <span class="text-xs font-bold">Absen</span>
                                        </a>

                                        {{-- 2. Tombol Edit --}}
                                        <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="p-1.5 bg-white text-yellow-600 border border-yellow-300 rounded hover:bg-yellow-50 transition" title="Edit Jadwal">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        {{-- 3. Tombol Hapus --}}
                                        <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-white text-red-600 border border-red-300 rounded hover:bg-red-50 transition" title="Hapus Jadwal">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-base font-medium">Belum ada jadwal latihan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER PAGINATION --}}
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $jadwals->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</x-app-layout>