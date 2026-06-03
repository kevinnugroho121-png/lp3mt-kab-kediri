<x-app-layout>
    {{-- CONTAINER UTAMA --}}
    <div class="py-4 px-4 w-full"> 
        
        {{-- JUDUL HALAMAN --}}
        <div class="mb-4 flex flex-col sm:flex-row justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-tight leading-none">
                    Data Lembaga
                </h2>
                <p class="text-xs text-gray-500 mt-1">Total: {{ $lembagas->total() }} Lembaga</p>
                @if(Auth::user()->role == 'korcam')
                    <p class="text-xs text-green-600 font-bold mt-1">Wilayah Kerja: Kec. {{ Auth::user()->kecamatan->nama_kecamatan ?? '-' }}</p>
                @endif
            </div>
            
            {{-- TOMBOL TAMBAH --}}
            <a href="{{ route('lembaga.create') }}" class="mt-2 sm:mt-0 inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-sm gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Lembaga
            </a>
        </div>

        {{-- FILTER BAR (DENGAN SELECT2) --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-4">
            <form action="{{ url()->current() }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                    
                    {{-- 1. SEARCH --}}
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Cari Lembaga / Kepala</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 p-2" placeholder="Ketik nama...">
                        </div>
                    </div>

                    {{-- 2. Filter Kecamatan --}}
                    <div class="md:col-span-2 w-full">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Kecamatan</label>
                        <select name="filter_kecamatan" id="filter_kecamatan" class="select2-filter w-full border border-gray-400 rounded-md px-1 py-1 text-sm focus:outline-none focus:border-blue-500 h-8">
                            <option value="">- Semua Kec -</option>
                            @if(isset($data_kecamatan))
                                @foreach($data_kecamatan as $kec)
                                    <option value="{{ $kec->id }}" {{ request('filter_kecamatan') == $kec->id ? 'selected' : '' }}>
                                        {{ $kec->nama_kecamatan }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- 3. Filter Desa --}}
                    <div class="md:col-span-2 w-full">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Desa</label>
                        <select name="filter_desa" id="filter_desa" class="select2-filter w-full border border-gray-400 rounded-md px-1 py-1 text-sm focus:outline-none focus:border-blue-500 h-8">
                            <option value="">- Semua Desa -</option>
                            {{-- Isinya akan diisi otomatis oleh Javascript di bawah --}}
                        </select>
                        
                        {{-- Data Gaib untuk dibaca Javascript --}}
                        <div id="allDesasDataFilter" class="hidden">
                            @if(isset($data_desa))
                                @foreach($data_desa as $d)
                                    <div data-kecamatan-id="{{ $d->kecamatan_id }}" data-id="{{ $d->id }}" data-nama="{{ $d->nama_desa }}"></div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- 4. FILTER JENIS --}}
                    <div class="md:col-span-2 w-full">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Jenis</label>
                        <select name="filter_jenis" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-[7px]">
                            <option value="">- Semua -</option>
                            <option value="TPQ" {{ request('filter_jenis') == 'TPQ' ? 'selected' : '' }}>TPQ</option>
                            <option value="MADIN" {{ request('filter_jenis') == 'MADIN' ? 'selected' : '' }}>MADIN</option>
                            <option value="PONPES" {{ request('filter_jenis') == 'PONPES' ? 'selected' : '' }}>PONPES</option>
                        </select>
                    </div>

                    {{-- 5. FILTER ORMAS --}}
                    <div class="md:col-span-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Ormas</label>
                        <select name="filter_ormas" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-[7px]">
                            <option value="">Semua</option>
                            <option value="NU" {{ request('filter_ormas') == 'NU' ? 'selected' : '' }}>NU</option>
                            <option value="Muhammadiyah" {{ request('filter_ormas') == 'Muhammadiyah' ? 'selected' : '' }}>Muh</option>
                            <option value="LDII" {{ request('filter_ormas') == 'LDII' ? 'selected' : '' }}>LDII</option>
                            <option value="Lainnya" {{ request('filter_ormas') == 'Lainnya' ? 'selected' : '' }}>Lain</option>
                        </select>
                    </div>

                    {{-- 6. TOMBOL RESET --}}
                    <div class="md:col-span-2 flex gap-2 w-full">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-[7px] rounded-lg text-sm font-bold hover:bg-blue-700 w-full">Cari</button>
                        <a href="{{ url()->current() }}" class="bg-gray-100 text-gray-600 border border-gray-300 px-3 py-[7px] rounded-lg text-sm font-bold hover:bg-gray-200 flex items-center justify-center">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- =========================== --}}
        {{-- FORM IMPORT EXCEL LEMBAGA   --}}
        {{-- =========================== --}}
        
        {{-- ============================================== --}}
        {{-- NOTIFIKASI ERROR VALIDASI BARIS EXCEL          --}}
        {{-- ============================================== --}}
        @if (session('excel_errors'))
            <div class="mb-4 bg-red-50 border border-red-300 rounded-lg p-4">
                <div class="flex items-center mb-3 text-red-700">
                    <svg class="w-5 h-5 mr-2 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <strong class="font-bold text-sm">Gagal Import! Ditemukan data yang kosong atau salah format. Seluruh data di file Excel ini BATAL disimpan.</strong>
                </div>
                
                <div class="border-l-4 border-red-600 bg-white p-3 rounded shadow-sm">
                    <p class="font-bold text-red-800 text-xs mb-2">Detail Baris Yang Kosong / Salah (Wajib Diperbaiki di Excel):</p>
                    <div class="max-h-40 overflow-y-auto text-xs text-red-600">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach (session('excel_errors') as $failure)
                                @foreach ($failure->errors() as $error)
                                    <li>Baris Ke-{{ $failure->row() }} (Kolom {{ $failure->attribute() }}): {{ $error }}</li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Notifikasi Error Sistem Biasa --}}
        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
                <strong class="font-bold">Gagal Import!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Kotak Form Hijau --}}
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <form action="{{ route('lembaga.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                @csrf
                <div class="w-full sm:w-auto">
                    <h4 class="font-bold text-green-800 text-lg">Import Data Lembaga via Excel</h4>
                    <p class="text-sm text-green-700 mt-1">Pastikan format kolom sesuai template. Sistem akan memblokir NIK/Lembaga ganda.</p>
                </div>
                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-bold file:bg-white file:text-green-700 file:border file:border-green-300 hover:file:bg-green-100 cursor-pointer transition">
                    
                    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-6 rounded-md transition shadow-sm whitespace-nowrap">
                        Impor
                    </button>
                </div>
            </form>
        </div>

        {{-- =========================== --}}
        {{-- 3. TABEL DATA (FULL COLUMN) --}}
        {{-- =========================== --}}
        <div class="border border-gray-400 bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-xs border-collapse min-w-[1200px]"> {{-- Min width agar bisa scroll horizontal --}}
                    <thead>
                        <tr class="bg-gray-100 text-gray-800 uppercase text-[10px] tracking-wider font-bold h-10 border-b border-gray-300">
                            <th class="border-r border-gray-300 w-10 text-center sticky left-0 bg-gray-100 z-10">No</th>
                            <th class="border-r border-gray-300 px-3 text-left w-56 sticky left-10 bg-gray-100 z-10">Identitas Lembaga</th>
                            <th class="border-r border-gray-300 px-2 text-center w-24">Jenis</th>
                            <th class="border-r border-gray-300 px-3 text-left w-40">Lokasi</th>
                            <th class="border-r border-gray-300 px-2 text-center w-16">Santri</th>
                            <th class="border-r border-gray-300 px-3 text-left w-32">Jumlah Guru</th>
                            <th class="border-r border-gray-300 px-3 text-left w-32">Insentif</th>
                            
                            {{-- KOLOM BARU --}}
                            <th class="border-r border-gray-300 px-3 text-center w-32 bg-blue-50">Legalitas IJOP</th>
                            <th class="border-r border-gray-300 px-3 text-center w-32 bg-purple-50">Legalitas SPTJM</th>
                            
                            <th class="border-l border-gray-300 px-2 text-center w-24 sticky right-0 bg-gray-100 z-10">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-900 text-[11px] divide-y divide-gray-200">
                        @forelse($lembagas as $index => $lembaga)
                            <tr class="hover:bg-yellow-50 transition duration-75 h-16 align-middle">
                                
                                {{-- 1. NO --}}
                                <td class="border-r border-gray-200 text-center font-medium bg-gray-50 py-3 sticky left-0 z-10">{{ $lembagas->firstItem() + $index }}</td>
                                
                                {{-- 2. IDENTITAS --}}
                                <td class="border-r border-gray-200 px-3 py-2 sticky left-10 bg-white z-10 hover:bg-yellow-50">
                                    <div class="font-bold text-sm text-gray-800 uppercase mb-1 leading-tight">{{ $lembaga->nama_lembaga }}</div>
                                    <div class="text-[10px] text-gray-500 leading-tight">
                                        <div class="mb-0.5"><span class="font-semibold">Ka:</span> {{ $lembaga->kepala_lembaga ?? '-' }}</div>
                                        <div>Telp: {{ $lembaga->no_telp ?? '-' }}</div>
                                    </div>
                                </td>

                                {{-- 3. JENIS --}}
                                <td class="border-r border-gray-200 text-center px-1">
                                    @php
                                        $j = $lembaga->jenis_lembaga;
                                        $color = match($j) {
                                            'TPQ' => 'bg-green-100 text-green-800 border-green-200',
                                            'MADIN' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'PONPES' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <div class="px-2 py-1 rounded text-[10px] font-bold mb-1 border {{ $color }}">{{ $j }}</div>
                                    <div class="text-[9px] border border-gray-200 rounded px-1">{{ $lembaga->ormas ?? '-' }}</div>
                                </td>

                                {{-- 4. LOKASI --}}
                                <td class="border-r border-gray-200 px-3">
                                    <div class="font-bold">{{ $lembaga->desa->nama_desa ?? '-' }}</div>
                                    <div class="text-gray-500 text-[10px]">Kec. {{ $lembaga->kecamatan->nama_kecamatan ?? '-' }}</div>
                                </td>

                                {{-- 5. SANTRI --}}
                                <td class="border-r border-gray-200 text-center font-bold text-blue-600 text-sm">
                                    {{ $lembaga->jumlah_santri }}
                                </td>

                                {{-- 6. GURU --}}
                                <td class="border-r border-gray-200 px-3">
                                    <div class="flex justify-between"><span>Total:</span> <b>{{ $lembaga->jumlah_guru }}</b></div>
                                    <div class="flex justify-between text-gray-500"><span>PNS:</span> {{ $lembaga->jumlah_pns }}</div>
                                </td>

                                {{-- 7. INSENTIF --}}
                                <td class="border-r border-gray-200 px-3">
                                    <div class="flex gap-2">
                                        <div class="text-green-600">Ok: <b>{{ $lembaga->penerima_insentif }}</b></div>
                                        <div class="text-red-500">No: <b>{{ $lembaga->belum_menerima_insentif }}</b></div>
                                    </div>
                                </td>

                                {{-- 8. LEGALITAS IJOP (BARU) --}}
                                <td class="border-r border-gray-200 px-2 py-2 text-center align-middle bg-blue-50/30">
                                    <div class="flex flex-col items-center gap-1">
                                        {{-- Ikon File --}}
                                        @if($lembaga->file_ijop)
                                            <div class="flex items-center gap-1 text-green-600 text-[10px] font-bold bg-green-50 px-1.5 py-0.5 rounded border border-green-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                Ada
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1 text-red-500 text-[10px] font-bold bg-red-50 px-1.5 py-0.5 rounded border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                Kosong
                                            </div>
                                        @endif

                                        {{-- Status Verifikasi --}}
                                        @php
                                            $badgeIjop = match($lembaga->status_ijop) {
                                                'Disetujui' => 'text-green-700 bg-green-100',
                                                'Ditolak' => 'text-red-700 bg-red-100',
                                                default => 'text-yellow-700 bg-yellow-100'
                                            };
                                        @endphp
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $badgeIjop }}">{{ $lembaga->status_ijop ?? 'Pending' }}</span>
                                    </div>
                                </td>

                                {{-- 9. LEGALITAS SUPER (BARU) --}}
                                <td class="border-r border-gray-200 px-2 py-2 text-center align-middle bg-purple-50/30">
                                    <div class="flex flex-col items-center gap-1">
                                        {{-- Ikon File --}}
                                        @if($lembaga->file_super)
                                            <div class="flex items-center gap-1 text-green-600 text-[10px] font-bold bg-green-50 px-1.5 py-0.5 rounded border border-green-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                Ada
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1 text-red-500 text-[10px] font-bold bg-red-50 px-1.5 py-0.5 rounded border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                Kosong
                                            </div>
                                        @endif

                                        {{-- Status Verifikasi --}}
                                        @php
                                            $badgeSuper = match($lembaga->status_super) {
                                                'Disetujui' => 'text-green-700 bg-green-100',
                                                'Ditolak' => 'text-red-700 bg-red-100',
                                                default => 'text-yellow-700 bg-yellow-100'
                                            };
                                        @endphp
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $badgeSuper }}">{{ $lembaga->status_super ?? 'Pending' }}</span>
                                    </div>
                                </td>

                                {{-- 10. AKSI (GRID 2x2) --}}
                                <td class="border-l border-gray-200 text-center p-1 align-middle w-24 sticky right-0 bg-white z-10">
                                    <div class="grid grid-cols-2 gap-1 w-full max-w-[80px] mx-auto">
                                        
                                        {{-- 1. Tombol DETAIL (Mata) --}}
                                        <a href="{{ route('lembaga.show', $lembaga->id) }}" class="flex items-center justify-center p-1.5 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition border border-blue-200" title="Lihat Detail">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>

                                        {{-- 2. Tombol VERIFIKASI (Dokumen/Centang) - HANYA ADMIN & VERIFIKATOR --}}
                                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'verifikator')
                                            <a href="{{ route('lembaga.verifikasi', $lembaga->id) }}" class="flex items-center justify-center p-1.5 bg-purple-100 text-purple-600 rounded hover:bg-purple-200 transition border border-purple-200" title="Verifikasi Dokumen">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </a>
                                        @endif

                                        {{-- 3. Tombol EDIT (Pensil) --}}
                                        <a href="{{ route('lembaga.edit', $lembaga->id) }}" class="flex items-center justify-center p-1.5 bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 transition border border-yellow-200" title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        {{-- 4. Tombol HAPUS (Sampah) - HANYA ADMIN --}}
                                        @if(Auth::user()->role == 'admin')
                                            <form action="{{ route('lembaga.destroy', $lembaga->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');" class="w-full">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="flex items-center justify-center w-full p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition border border-red-200" title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="border border-gray-300 py-8 text-center text-gray-400 bg-gray-50">Data lembaga tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-4">{{ $lembagas->withQueryString()->links() }}</div>
    </div>

    {{-- ========================================== --}}
    {{-- LIBRARY & SCRIPT UNTUK FILTER PINTAR       --}}
    {{-- ========================================== --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <style>
        /* CSS Tambahan Biar Select2 Cocok Sama Tailwind */
        .select2-container .select2-selection--single { height: 36px; border-color: #9ca3af; border-radius: 0.5rem; font-size: 0.875rem; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 34px; color: #374151; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 34px; }
    </style>

    <script>
        $(document).ready(function() {
            // 1. Aktifkan Fitur Ketik/Search di Dropdown
            $('.select2-filter').select2({
                width: '100%',
                placeholder: "- Pilih/Ketik -"
            });

            // 2. Logika Kunci Desa (Tampil sesuai Kecamatan)
            const allDesas = Array.from(document.querySelectorAll('#allDesasDataFilter div')).map(div => ({
                kecamatan_id: div.getAttribute('data-kecamatan-id'),
                id: div.getAttribute('data-id'),
                nama: div.getAttribute('data-nama')
            }));

            const oldDesa = "{{ request('filter_desa') }}";

            function updateDesaFilter() {
                const kecId = $('#filter_kecamatan').val();
                const desaSelect = $('#filter_desa');
                
                desaSelect.empty(); // Kosongkan desa

                if (kecId) {
                    desaSelect.append('<option value="">- Semua Desa -</option>');
                    const filteredDesas = allDesas.filter(d => d.kecamatan_id == kecId);
                    filteredDesas.forEach(d => {
                        const isSelected = (d.id == oldDesa) ? 'selected' : '';
                        desaSelect.append(`<option value="${d.id}" ${isSelected}>${d.nama}</option>`);
                    });
                } else {
                    desaSelect.append('<option value="">- Pilih Kecamatan Dulu -</option>');
                }

                // Refresh tampilan Select2
                desaSelect.trigger('change.select2');
            }

            // Jalankan saat kecamatan diubah
            $('#filter_kecamatan').on('change', updateDesaFilter);
            
            // Jalankan saat pertama kali halaman dimuat
            updateDesaFilter();
        });
    </script>
</x-app-layout>