<x-app-layout>
    {{-- CONTAINER UTAMA --}}
    <div class="py-2 px-2 w-full"> 
        
        {{-- =========================== --}}
        {{-- 1. HEADER & CONTROL BAR KOMPLIT --}}
        {{-- =========================== --}}
        <div class="mb-3 bg-white p-3 rounded-lg border border-gray-300 shadow-sm">
            <form action="{{ url()->current() }}" method="GET"> 
                
                {{-- BARIS 1: Judul & Tombol Tambah --}}
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-3 pb-2 border-b border-gray-100">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">
                            {{ $title ?? 'Data Guru' }}
                        </h2>
                        <p class="text-xs text-gray-500">Total Data: <b>{{ $gurus->total() }}</b> Guru</p>
                    </div>

                    @php
                        $createType = (isset($filterType) && ($filterType == 'ALL' || $filterType == 'INSENTIF')) ? 'MADIN' : ($filterType ?? 'MADIN');
                    @endphp
                    <a href="{{ route('guru.create', ['type' => $createType]) }}" class="mt-2 lg:mt-0 inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Data
                    </a>
                </div>

                {{-- BARIS 2: Jajaran Kolom Filter (Grid 5 Kolom) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2 items-end">
                    
                    {{-- 1. Search --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider ml-1">Cari Nama / NIK</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIK..." 
                               class="w-full border border-gray-400 rounded-md px-2 py-1 text-sm focus:outline-none focus:border-blue-500 h-8">
                    </div>

                    {{-- 2. Filter Kecamatan --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider ml-1">Kecamatan</label>
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
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider ml-1">Desa</label>
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

                    {{-- 4. Filter Lembaga --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider ml-1">Lembaga</label>
                        <select name="filter_lembaga" class="select2-filter w-full border border-gray-400 rounded-md px-1 py-1 text-sm focus:outline-none focus:border-blue-500 h-8">
                            <option value="">- Semua Lembaga -</option>
                            @if(isset($list_lembaga))
                                @foreach($list_lembaga as $l)
                                    <option value="{{ $l->id }}" {{ request('filter_lembaga') == $l->id ? 'selected' : '' }}>
                                        {{ $l->nama_lembaga }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- 5. Tombol Aksi (Cari & Reset) --}}
                    <div class="w-full flex gap-1 h-8">
                        <button type="submit" class="flex-1 bg-blue-600 text-white rounded-md text-xs font-bold hover:bg-blue-700 flex items-center justify-center gap-1 shadow-sm transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Filter
                        </button>
                        <a href="{{ url()->current() }}" class="flex-1 bg-red-50 text-red-600 border border-red-200 rounded-md text-xs font-bold hover:bg-red-100 flex items-center justify-center shadow-sm transition">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>

        {{-- ============================================================ --}}
        {{-- [BARU] PANEL IMPORT EXCEL SUPER KETAT                        --}}
        {{-- ============================================================ --}}
        {{-- TAMPILKAN PANEL HANYA DI MENU MADIN, TPQ, DAN PONPES --}}
        @if(isset($filterType) && in_array($filterType, ['MADIN', 'TPQ', 'PONPES']))
            <div class="mb-4 bg-emerald-50 border border-emerald-300 p-4 rounded-lg shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-sm font-bold text-emerald-900 flex items-center gap-1">
                        Import Data Guru via Excel
                    </h3>
                    <p class="text-[11px] text-emerald-700 mt-0.5">Pastikan semua kolom terisi lengkap (Tidak boleh ada sel kosong). Sistem akan otomatis menolak seluruh file jika ada data cacat.</p>
                </div>
                
                {{-- Form Upload --}}
                <form action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2 w-full md:w-auto justify-end">
                    @csrf
                    <input type="file" name="file_excel" accept=".xlsx, .xls" required
                           class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded file:border file:border-emerald-300 file:text-xs file:font-bold file:bg-white file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded text-xs font-bold shadow-sm flex items-center gap-1 transition">
                        Impor
                    </button>
                </form>
            </div>
        @endif

            {{-- Tempat Menampilkan Notifikasi Sukses / Error Massal ala Simkopdes --}}
            @if(session('success'))
                <div class="mt-3 bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded text-xs font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mt-3 bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-xs font-bold">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @if ($errors->any() && !request()->is('*/create') && !request()->is('*/edit'))
                <div class="mt-2 bg-white border-l-4 border-red-500 p-3 rounded shadow-inner max-h-40 overflow-y-auto">
                    <h4 class="text-xs font-bold text-red-800 mb-1">Detail Baris Yang Kosong / Salah (Wajib Diperbaiki di Excel):</h4>
                    <ul class="list-disc list-inside text-[11px] text-red-600 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
        @if(isset($filterType) && in_array($filterType, ['MADIN', 'TPQ', 'PONPES']))
            </div>
        @endif

        {{-- =========================== --}}
        {{-- 2. TABEL DATA --}}
        {{-- =========================== --}}
        <div class="border border-gray-400 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                {{-- PERLEBAR TABEL AGAR MUAT BANYAK KOLOM --}}
                <table class="w-full text-xs border-collapse min-w-[3000px]"> 
                    
                    {{-- HEADER TABEL --}}
                    <thead>
                        <tr class="bg-gray-100 text-gray-800 uppercase text-[10px] tracking-wider font-bold h-9">
                            <th class="border border-gray-300 w-10 text-center sticky left-0 bg-gray-100 z-10">No</th>
                            <th class="border border-gray-300 px-2 text-center w-48 sticky left-10 bg-gray-100 z-10 shadow-r">Nama Lengkap</th>
                            <th class="border border-gray-300 px-2 text-center w-32">NIK</th>
                            <th class="border border-gray-300 px-2 text-center w-32">Status Guru</th>
                            <th class="border border-gray-300 px-2 text-center w-24">Insentif</th>
                            <th class="border border-gray-300 px-2 text-center w-48">Nama Lembaga</th>
                            <th class="border border-gray-300 px-2 text-center w-24">Jenis</th>
                            <th class="border border-gray-300 px-2 text-center w-32">File KTP</th> 
                            <th class="border border-gray-300 px-2 text-center w-32">File KK</th> 
                            <th class="border border-gray-300 px-2 text-center w-32">File Rekening</th> 
                            <th class="border border-gray-300 px-2 text-center w-64">Alamat (Sesuai KTP)</th>
                            <th class="border border-gray-300 px-2 text-center w-32">Tempat Tanggal Lahir</th>
                            <th class="border border-gray-300 px-2 text-center w-10">L/P</th>
                            <th class="border border-gray-300 px-2 text-center w-32">Desa</th>
                            <th class="border border-gray-300 px-2 text-center w-32">Kecamatan</th>
                            <th class="border border-gray-300 px-2 text-center w-24">Kabupaten</th>
                            <th class="border border-gray-300 px-2 text-center w-20">Agama</th>
                            <th class="border border-gray-300 px-2 text-center w-28">Nomor HP</th>
                            <th class="border border-gray-300 px-2 text-center w-40">Nama Ibu Kandung</th>
                            <th class="border border-gray-300 px-2 text-center w-40">Nomor Rekening</th>
                            <th class="border border-gray-300 px-2 text-center w-40">Keterangan</th>
                            <th class="border border-gray-300 px-2 text-center w-40 sticky right-0 bg-gray-100 z-10">Aksi</th>
                        </tr>
                    </thead>
                    
                    {{-- BODY TABEL --}}
                    <tbody class="text-gray-900 text-[11px] divide-y divide-gray-200">
                        @php $kuotaTracker = []; @endphp {{-- [BARU] Variabel untuk menghitung urutan per lembaga --}}
                        
                        @forelse($gurus as $index => $guru)
                            {{-- LOGIKA WARNA BARIS (MERAH / HIJAU / KUNING) --}}
                            @php
                                // 1. Cek Kelayakan (PNS/PPPK/Inpassing = Merah)
                                $isTidakLayak = in_array($guru->status_kepegawaian, ['PNS', 'PPPK']) || strtoupper($guru->status_sertifikasi) == 'INPASSING';
                                
                                $rowClass = 'hover:bg-gray-50 bg-white';
                                $stickyClass = 'bg-white';
                                $badgeKuota = '';

                                if ($isTidakLayak) {
                                    $rowClass = 'bg-red-100 hover:bg-red-200';
                                    $stickyClass = 'bg-red-100';
                                } 
                                // 2. Logika Kuota Insentif (Hanya jalan di Menu Insentif)
                                elseif (isset($filterType) && $filterType == 'INSENTIF') {
                                    $l_id = $guru->lembaga_id;
                                    
                                    // Hitung urutan guru di lembaga ini
                                    if (!isset($kuotaTracker[$l_id])) { $kuotaTracker[$l_id] = 0; }
                                    $kuotaTracker[$l_id]++;
                                    
                                    // Ambil jatah kuota dari tabel lembaga
                                    $jatahLembaga = $guru->lembaga->penerima_insentif ?? 0;

                                    if ($kuotaTracker[$l_id] <= $jatahLembaga) {
                                        // MASUK KUOTA -> HIJAU
                                        $rowClass = 'bg-emerald-50 hover:bg-emerald-100'; 
                                        $stickyClass = 'bg-emerald-50';
                                        $badgeKuota = '<div class="mt-1.5"><span class="px-1.5 py-0.5 rounded shadow-sm bg-emerald-500 text-white text-[9px] font-bold border border-emerald-600">MASUK KUOTA</span></div>';
                                    } else {
                                        // LUAR KUOTA (DAFTAR TUNGGU) -> KUNING
                                        $rowClass = 'bg-amber-50 hover:bg-amber-100'; 
                                        $stickyClass = 'bg-amber-50';
                                        $badgeKuota = '<div class="mt-1.5"><span class="px-1.5 py-0.5 rounded shadow-sm bg-amber-400 text-amber-900 text-[9px] font-bold border border-amber-500">TIDAK MASUK KUOTA</span></div>';
                                    }
                                }
                            @endphp

                            <tr class="{{ $rowClass }} transition duration-75 whitespace-nowrap">
                                
                                {{-- 1. NO --}}
                                <td class="border border-gray-300 py-1 text-center font-medium sticky left-0 z-10 {{ $isTidakLayak ? 'bg-red-100' : 'bg-gray-50' }}">
                                    {{ $gurus->firstItem() + $index }}
                                </td>

                                {{-- 2. NAMA --}}
                                <td class="border border-gray-300 px-2 py-1 font-bold sticky left-10 z-10 shadow-r {{ $stickyClass }}">
                                    {{ $guru->nama_lengkap }}
                                    @if($isTidakLayak)
                                        <span class="block text-[9px] text-red-600 font-normal italic">(Tidak Berhak Insentif)</span>
                                    @endif
                                </td>

                                {{-- 3. NIK --}}
                                <td class="border border-gray-300 px-2 py-1 font-mono text-gray-600">
                                    {{ $guru->nik }}
                                </td>

                                {{-- 4. STATUS GURU --}}
                                <td class="border border-gray-300 px-2 py-1 text-center">
                                    <div class="flex flex-col gap-1 items-center">
                                        {{-- Badge Status Kepegawaian (PNS/NON-ASN) --}}
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-gray-100 border text-gray-600">
                                            {{ $guru->status_kepegawaian ?? '-' }}
                                        </span>
                                        
                                        {{-- Badge Sertifikasi HANYA MUNCUL kalau sudah Sertifikasi/Inpassing --}}
                                        @if(strtoupper($guru->status_sertifikasi) == 'SERTIFIKASI' || strtoupper($guru->status_sertifikasi) == 'INPASSING')
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 border border-blue-200">
                                                {{ $guru->status_sertifikasi }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- 5. INSENTIF --}}
                                <td class="border border-gray-300 px-2 py-1 text-center align-middle">
                                    @if($guru->penerima_insentif == 1)
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700 border border-green-300">
                                            BERHAK DAPAT
                                        </span>
                                        {{-- Memunculkan Label Hijau/Kuning khusus di Menu Insentif --}}
                                        {!! $badgeKuota !!} 
                                    @else
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-400 border border-gray-300">
                                            ❌ Tidak
                                        </span>
                                    @endif
                                </td>

                                {{-- 6. NAMA LEMBAGA --}}
                                <td class="border border-gray-300 px-2 py-1">
                                    {{ $guru->lembaga->nama_lembaga ?? '-' }}
                                </td>

                                {{-- 7. JENIS LEMBAGA --}}
                                <td class="border border-gray-300 px-2 py-1 text-center">
                                    @php
                                        $j = $guru->lembaga->jenis_lembaga ?? '-';
                                        $color = match($j) {
                                            'TPQ' => 'text-green-700 bg-green-100 border-green-300',
                                            'MADIN' => 'text-blue-700 bg-blue-100 border-blue-300',
                                            'PONPES' => 'text-purple-700 bg-purple-100 border-purple-300',
                                            default => 'text-gray-600 bg-gray-100 border-gray-300'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $color }}">
                                        {{ $j }}
                                    </span>
                                </td>

                                {{-- 8. FILE KTP --}}
                                <td class="border border-gray-300 px-2 py-1 text-center {{ $isTidakLayak ? 'bg-red-100' : 'bg-gray-50' }}">
                                    @if($guru->file_ktp)
                                        <div class="flex flex-col items-center">
                                            <a href="{{ asset('storage/' . $guru->file_ktp) }}" target="_blank" class="text-blue-600 underline font-bold hover:text-blue-800 mb-1">
                                                Lihat
                                            </a>
                                            @php
                                                $s = $guru->status_ktp;
                                                $c = ($s == 'Disetujui') ? 'bg-green-100 text-green-700' : (($s == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                            @endphp
                                            <span class="text-[9px] px-1.5 rounded border {{ $c }}">{{ $s }}</span>
                                        </div>
                                    @else
                                        <span class="text-red-400 italic">-</span>
                                    @endif
                                </td>

                                {{-- 9. FILE KK --}}
                                <td class="border border-gray-300 px-2 py-1 text-center {{ $isTidakLayak ? 'bg-red-100' : 'bg-white' }}">
                                    @if($guru->file_kk)
                                        <div class="flex flex-col items-center">
                                            <a href="{{ asset('storage/' . $guru->file_kk) }}" target="_blank" class="text-green-600 underline font-bold hover:text-green-800 mb-1">
                                                Lihat
                                            </a>
                                            @php
                                                $s = $guru->status_kk;
                                                $c = ($s == 'Disetujui') ? 'bg-green-100 text-green-700' : (($s == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                            @endphp
                                            <span class="text-[9px] px-1.5 rounded border {{ $c }}">{{ $s }}</span>
                                        </div>
                                    @else
                                        <span class="text-red-400 italic">-</span>
                                    @endif
                                </td>

                                {{-- 10. FILE REKENING --}}
                                <td class="border border-gray-300 px-2 py-1 text-center {{ $isTidakLayak ? 'bg-red-100' : 'bg-gray-50' }}">
                                    @if($guru->file_bukurekening)
                                        <div class="flex flex-col items-center">
                                            <a href="{{ asset('storage/' . $guru->file_bukurekening) }}" target="_blank" class="text-purple-600 underline font-bold hover:text-purple-800 mb-1">
                                                Lihat
                                            </a>
                                            @php
                                                $s = $guru->status_bukurekening;
                                                $c = ($s == 'Disetujui') ? 'bg-green-100 text-green-700' : (($s == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                            @endphp
                                            <span class="text-[9px] px-1.5 rounded border {{ $c }}">{{ $s }}</span>
                                        </div>
                                    @else
                                        <span class="text-red-400 italic">-</span>
                                    @endif
                                </td>

                                {{-- 11. ALAMAT --}}
                                <td class="border border-gray-300 px-2 py-1 truncate max-w-xs" title="{{ $guru->alamat_ktp }}">
                                    {{ Str::limit($guru->alamat_ktp, 30) }}
                                </td>

                                {{-- 12. TTL --}}
                                <td class="border border-gray-300 px-2 py-1">
                                    {{ $guru->tempat_lahir }}, {{ \Carbon\Carbon::parse($guru->tanggal_lahir)->format('d-m-Y') }}
                                </td>

                                {{-- 13. L/P --}}
                                <td class="border border-gray-300 px-2 py-1 text-center">
                                    {{ $guru->jenis_kelamin }}
                                </td>

                                {{-- 14-18. WILAYAH & KONTAK --}}
                                <td class="border border-gray-300 px-2 py-1">{{ $guru->desa ?? '-' }}</td>
                                <td class="border border-gray-300 px-2 py-1">{{ $guru->kecamatan ?? '-' }}</td>
                                <td class="border border-gray-300 px-2 py-1 text-center">{{ $guru->kabupaten }}</td>
                                <td class="border border-gray-300 px-2 py-1 text-center">{{ $guru->agama }}</td>
                                <td class="border border-gray-300 px-2 py-1 text-center">{{ $guru->no_hp }}</td>

                                {{-- 19-21. DETAIL LAIN --}}
                                <td class="border border-gray-300 px-2 py-1">{{ $guru->nama_ibu_kandung }}</td>
                                <td class="border border-gray-300 px-2 py-1 font-mono font-bold text-gray-700">{{ $guru->nomor_rekening }}</td>
                                <td class="border border-gray-300 px-2 py-1 text-gray-500 italic">{{ $guru->keterangan ?? '-' }}</td>

                                {{-- 22. AKSI --}}
                                <td class="border border-gray-300 py-1 text-center sticky right-0 z-10 shadow-l {{ $stickyClass }}">
                                    <div class="flex justify-center gap-1 px-1">
                                        
                                        {{-- LIHAT --}}
                                        <a href="{{ route('guru.show', $guru->id) }}" class="p-1 bg-blue-100 text-blue-700 border border-blue-300 rounded hover:bg-blue-200 transition" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>

                                        {{-- VERIFIKASI (Hanya Admin dan Verifikator) --}}
                                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'verifikator')
                                            <a href="{{ route('guru.verifikasi', $guru->id) }}" class="p-1 bg-purple-100 text-purple-700 border border-purple-300 rounded hover:bg-purple-200 transition" title="Verifikasi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </a>
                                        @endif

                                        {{-- EDIT --}}
                                        <a href="{{ route('guru.edit', $guru->id) }}" class="p-1 bg-yellow-100 text-yellow-700 border border-yellow-300 rounded hover:bg-yellow-200 transition" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        
                                        {{-- HAPUS (Dapat diakses oleh semua role) --}}
                                        <form action="{{ route('guru.destroy', $guru->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini beserta file dokumennya?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 bg-red-100 text-red-700 border border-red-300 rounded hover:bg-red-200 transition" title="Hapus Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="22" class="border border-gray-300 px-4 py-8 text-center bg-gray-50 text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <span class="text-2xl mb-1">📂</span>
                                        <p>Belum ada data guru. Silakan klik tombol <b>+ Tambah</b>.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- PAGINATION --}}
        <div class="mt-2 text-xs">
            {{ $gurus->withQueryString()->links() }}
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- LIBRARY & SCRIPT UNTUK FILTER PINTAR       --}}
    {{-- ========================================== --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <style>
        /* CSS Tambahan Biar Select2 Cocok Sama Tailwind */
        .select2-container .select2-selection--single { height: 32px; border-color: #9ca3af; border-radius: 0.375rem; font-size: 0.875rem; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 30px; color: #374151; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 30px; }
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