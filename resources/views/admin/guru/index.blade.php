<x-app-layout>
    {{-- CONTAINER UTAMA --}}
    <div class="py-1 px-1 w-full"> 
        {{-- ============================================================ --}}
        {{-- 📊 [BARU - FASE 2] DASHBOARD TRANSPARAN INDIKATOR KUOTA KORCAM --}}
        {{-- ============================================================ --}}
        @if(Auth::user()->role == 'korcam' && isset($filterType) && $filterType == 'INSENTIF' && isset($kuotaSistem))
            <div class="mb-1 grid grid-cols-1 sm:grid-cols-5 gap-1">
                
                {{-- KOTAK 1: BIRU --}}
                {{-- Ganti p-4 menjadi px-4 py-2 (atau py-1 kalau mau lebih mepet lagi) --}}
                <div class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider leading-tight w-2/3">Kuota Insentif</p>
                    <h4 class="text-4xl font-black leading-none">{{ $kuotaSistem['total'] }}</h4>
                </div>

                {{-- KOTAK 2: HIJAU --}}
                <div class="bg-emerald-600 text-white px-4 py-2 rounded-lg shadow-sm flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider leading-tight w-2/3">Kuota Terpakai</p>
                    <h4 class="text-4xl font-black leading-none">{{ $kuotaSistem['terpakai'] }}</h4>
                </div>

                {{-- KOTAK 3: MERAH --}}
                <div class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-sm flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider leading-tight w-2/3">Sisa Kuota</p>
                    <h4 class="text-5xl font-black leading-none">{{ $kuotaSistem['sisa'] }}</h4>
                </div>
                
            </div>
        @endif
        



        {{-- ============================================== --}}
        {{-- 1. HEADER & CONTROL BAR (SATU KOTAK KOMPAK)    --}}
        {{-- ============================================== --}}
        <div class="mb-1 bg-white p-1 rounded-lg border border-gray-600 shadow-sm">
            <form action="{{ url()->current() }}" method="GET"> 
                
                {{-- BARIS 1: Judul Sejajar & Tombol Aksi --}}
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-0 pb-0 border-b border-gray-200">
                    
                    {{-- Judul & Total (Diubah jadi sejajar pakai Flexbox) --}}
                    <div class="flex flex-wrap items-end gap-1 mb-0 lg:mb-0">
                        <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight leading-none">
                            {{ $title ?? 'Data Guru' }}
                        </h2>
                        <p class="text-xl text-gray-800 font-bold leading-none mb-0">
                            TOTAL : <span class="font-bold text-gray-700">{{ $gurus->total() }}</span> GURU
                        </p>
                    </div>

                    @php
                        $createType = (isset($filterType) && ($filterType == 'ALL' || $filterType == 'INSENTIF')) ? 'MADIN' : ($filterType ?? 'MADIN');
                    @endphp
                    
                    {{-- Tombol Export & Tambah --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('guru.export', request()->all() + ['type' => $filterType ?? 'ALL']) }}" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5 rounded-md text-sm font-bold shadow-sm gap-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Excel
                        </a>
                        
                        {{-- Logika untuk menyembunyikan tombol di menu ALL dan INSENTIF --}}
                        @if(!in_array($filterType ?? '', ['ALL', 'INSENTIF']))
                            <a href="{{ route('guru.create', ['type' => $createType ?? 'MADIN']) }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-md text-sm font-bold shadow-sm gap-1 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Guru
                            </a>
                        @endif
                    </div>



                </div>

                {{-- BARIS 2: Jajaran Filter (Grid 7 Kolom Lurus 1 Baris) --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-7 gap-1 items-end">
                    
                    {{-- 1. Search --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider ml-1">Cari Nama / NIK</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIK..." 
                               class="w-full border border-gray-600 rounded-md px-2 py-1 text-sm focus:outline-none focus:border-blue-500 h-[32px]">
                    </div>

                    {{-- 2. Filter Kecamatan --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider ml-1">Kecamatan</label>
                        <select name="filter_kecamatan" id="filter_kecamatan" class="select2-filter w-full border border-gray-600 rounded-md px-1 py-1 text-sm focus:outline-none focus:border-blue-500 h-[36px]">
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
                        <select name="filter_desa" id="filter_desa" class="select2-filter w-full border border-gray-600 rounded-md px-1 py-1 text-sm focus:outline-none focus:border-blue-500 h-[36px]">
                            <option value="">- Semua Desa -</option>
                        </select>
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
                        <select name="filter_lembaga" class="select2-filter w-full border border-gray-600 rounded-md px-1 py-1 text-sm focus:outline-none focus:border-blue-500 h-[36px]">
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

                    {{-- 5. Filter Berkas --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider ml-1">Status Berkas</label>
                        <select name="filter_berkas" class="bg-gray-50 border border-gray-600 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full h-[32px] px-1 py-1">
                            <option value="">- Semua Status -</option>
                            <option value="kosong" {{ request('filter_berkas') == 'kosong' ? 'selected' : '' }}>📄 Kosong</option>
                            <option value="pending" {{ request('filter_berkas') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="ditolak" {{ request('filter_berkas') == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                            <option value="disetujui" {{ request('filter_berkas') == 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                        </select>
                    </div>

                    {{-- 6. Filter Insentif --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider ml-1">Status Insentif</label>
                        <select name="filter_insentif" class="bg-gray-50 border border-gray-600 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full h-[32px] px-1 py-1">
                            <option value="">- Semua -</option>
                            <option value="1" {{ request('filter_insentif') == '1' ? 'selected' : '' }}>✅ Diajukan</option>
                            <option value="0" {{ request('filter_insentif') == '0' ? 'selected' : '' }}>❌ Tidak Diajukan</option>
                        </select>
                    </div>

                    {{-- 7. Tombol Aksi (Cari & Reset) --}}
                    <div class="w-full flex gap-1 h-[32px]">
                        <button type="submit" class="flex-1 bg-blue-600 text-white rounded text-xs font-bold hover:bg-blue-700 flex items-center justify-center gap-1 shadow-sm transition">Cari</button>
                        <a href="{{ url()->current() }}" class="flex-1 bg-red-50 text-red-600 border border-red-200 rounded text-xs font-bold hover:bg-red-100 flex items-center justify-center shadow-sm transition">Reset</a>
                    </div>

                </div>
            </form>
        </div>

        {{-- ============================================================ --}}
        {{-- [BARU] PANEL IMPORT EXCEL SUPER KETAT                        --}}
        {{-- ============================================================ --}}
        {{-- TAMPILKAN PANEL HANYA DI MENU MADIN, TPQ, DAN PONPES --}}
        @if(isset($filterType) && in_array($filterType, ['MADIN', 'TPQ', 'PONPES']))
            <div class="mb-1 bg-emerald-50 border border-emerald-300 p-1 rounded-lg shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-sm font-bold italic text-emerald-600 flex items-center gap-1">
                        * Import Data Guru via Excel. Pastikan semua kolom terisi lengkap. Sistem akan memblokir NIK ganda.
                    </h3>
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

            {{-- BLOK NOTIFIKASI ERROR EXCEL CUSTOM (REJECT-ALL) --}}
            @if (session('custom_excel_errors'))
                <div class="mt-3 bg-red-50 border-2 border-red-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center mb-2 text-red-800">
                        <span class="text-lg mr-2">⚠️</span>
                        <strong class="font-bold text-xs tracking-tight">Sistem Menolak File! Terdeteksi Data Kosong / NIK Duplikat. Seluruh baris BATAL disimpan.</strong>
                    </div>
                    
                    <div class="border-l-4 border-red-600 bg-white p-3 rounded shadow-inner">
                        <p class="font-bold text-red-900 text-[10px] mb-1 uppercase tracking-wide">Daftar Baris yang Bermasalah (Wajib Diperbaiki di Excel):</p>
                        <div class="max-h-40 overflow-y-auto text-[11px] text-red-700 font-medium custom-scrollbar">
                            <ul class="list-disc pl-4 space-y-1">
                                @foreach (session('custom_excel_errors') as $errorPesan)
                                    <li>{!! $errorPesan !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
        @if(isset($filterType) && in_array($filterType, ['MADIN', 'TPQ', 'PONPES']))
            </div>
        @endif

        {{-- =========================== --}}
        {{-- 2. TABEL DATA --}}
        {{-- =========================== --}}
        <div class="border border-gray-600 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                {{-- PERLEBAR TABEL AGAR MUAT BANYAK KOLOM --}}
                <table class="w-full text-xs border-collapse min-w-[3000px]"> 
                    


                
                    {{-- HEADER TABEL --}}


                    {{-- HEADER TABEL DENGAN FILTER ALA EXCEL --}}
                    <style>
                        /* CSS kecil untuk styling scrollbar pop-up jika kepanjangan */
                        .excel-filter-popup { display: none; }
                        .excel-filter-popup.show { display: block; }
                    </style>

                    <thead>
                        <tr class="bg-gray-100 text-gray-800 uppercase text-[10px] tracking-wider font-bold h-9">
                            <th class="border border-gray-600 w-10 text-center sticky left-0 bg-gray-100 z-30">No</th>
                            
                            {{-- 1. NAMA LENGKAP (Dengan Filter) --}}
                            <th class="border border-gray-600 px-2 text-left w-48 sticky left-10 bg-gray-100 z-30 shadow-r relative">
                                <div class="flex items-center justify-between cursor-pointer hover:text-blue-600 transition" onclick="toggleExcelFilter('filter-nama')">
                                    <span>Nama Lengkap</span>
                                    <svg class="w-3 h-3 {{ request('col_nama') || (request('sort_col') == 'nama_lengkap') ? 'text-blue-600' : 'text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3 5a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm2 5a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z"></path></svg>
                                </div>
                                <div id="filter-nama" class="excel-filter-popup absolute top-full left-0 mt-1 w-48 bg-white border border-gray-400 shadow-2xl rounded-md z-[99] p-2 text-left font-normal normal-case">
                                    <form action="{{ url()->current() }}" method="GET">
                                        {{-- Pertahankan filter atas yang sedang aktif --}}
                                        @foreach(request()->except(['col_nama', 'sort_col', 'sort_dir', 'page']) as $key => $value) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endforeach
                                        
                                        <div class="mb-2 pb-2 border-b border-gray-300 flex flex-col gap-1">
                                            <button type="submit" name="sort_dir" value="asc" onclick="document.getElementById('sort_nama').value='nama_lengkap'" class="w-full text-left px-2 py-1 hover:bg-blue-50 text-[10px] rounded flex items-center gap-2"><span class="text-blue-600 font-bold">A↓</span> Urutkan A-Z</button>
                                            <button type="submit" name="sort_dir" value="desc" onclick="document.getElementById('sort_nama').value='nama_lengkap'" class="w-full text-left px-2 py-1 hover:bg-blue-50 text-[10px] rounded flex items-center gap-2"><span class="text-blue-600 font-bold">Z↑</span> Urutkan Z-A</button>
                                            <input type="hidden" id="sort_nama" name="sort_col" value="{{ request('sort_col') }}">
                                        </div>
                                        <div>
                                            <label class="text-[9px] font-bold text-gray-500 uppercase mb-1 block">Cari Nama Spesifik:</label>
                                            <input type="text" name="col_nama" value="{{ request('col_nama') }}" class="w-full border border-gray-400 rounded px-2 py-1 text-xs focus:border-blue-500" placeholder="Ketik nama...">
                                        </div>
                                        <div class="mt-2 flex gap-1">
                                            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded text-[10px] w-full font-bold hover:bg-blue-700">Terapkan</button>
                                            @if(request('col_nama') || request('sort_col') == 'nama_lengkap')
                                                <a href="{{ request()->fullUrlWithQuery(['col_nama' => null, 'sort_col' => null, 'sort_dir' => null]) }}" class="bg-red-50 text-red-600 border border-red-200 px-2 py-1 rounded text-[10px] font-bold hover:bg-red-100 text-center">Clear</a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </th>

                            {{-- 2. NIK (Dengan Filter) --}}
                            <th class="border border-gray-600 px-2 text-left w-32 relative">
                                <div class="flex items-center justify-between cursor-pointer hover:text-blue-600 transition" onclick="toggleExcelFilter('filter-nik')">
                                    <span>NIK</span>
                                    <svg class="w-3 h-3 {{ request('col_nik') || (request('sort_col') == 'nik') ? 'text-blue-600' : 'text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3 5a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm2 5a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z"></path></svg>
                                </div>
                                <div id="filter-nik" class="excel-filter-popup absolute top-full left-0 mt-1 w-48 bg-white border border-gray-400 shadow-2xl rounded-md z-[99] p-2 text-left font-normal normal-case">
                                    <form action="{{ url()->current() }}" method="GET">
                                        @foreach(request()->except(['col_nik', 'sort_col', 'sort_dir', 'page']) as $key => $value) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endforeach
                                        <div class="mb-2 pb-2 border-b border-gray-300 flex flex-col gap-1">
                                            <button type="submit" name="sort_dir" value="asc" onclick="document.getElementById('sort_nik').value='nik'" class="w-full text-left px-2 py-1 hover:bg-blue-50 text-[10px] rounded flex items-center gap-2"><span class="text-blue-600 font-bold">0↓</span> Urutkan 0-9</button>
                                            <button type="submit" name="sort_dir" value="desc" onclick="document.getElementById('sort_nik').value='nik'" class="w-full text-left px-2 py-1 hover:bg-blue-50 text-[10px] rounded flex items-center gap-2"><span class="text-blue-600 font-bold">9↑</span> Urutkan 9-0</button>
                                            <input type="hidden" id="sort_nik" name="sort_col" value="{{ request('sort_col') }}">
                                        </div>
                                        <div>
                                            <label class="text-[9px] font-bold text-gray-500 uppercase mb-1 block">Cari NIK Spesifik:</label>
                                            <input type="text" name="col_nik" value="{{ request('col_nik') }}" class="w-full border border-gray-400 rounded px-2 py-1 text-xs focus:border-blue-500" placeholder="Ketik NIK...">
                                        </div>
                                        <div class="mt-2 flex gap-1">
                                            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded text-[10px] w-full font-bold hover:bg-blue-700">Terapkan</button>
                                            @if(request('col_nik') || request('sort_col') == 'nik')
                                                <a href="{{ request()->fullUrlWithQuery(['col_nik' => null, 'sort_col' => null, 'sort_dir' => null]) }}" class="bg-red-50 text-red-600 border border-red-200 px-2 py-1 rounded text-[10px] font-bold hover:bg-red-100 text-center">Clear</a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </th>

                            {{-- 3. STATUS GURU (Dengan Filter) --}}
                            <th class="border border-gray-600 px-2 text-left w-32 relative">
                                <div class="flex items-center justify-between cursor-pointer hover:text-blue-600 transition" onclick="toggleExcelFilter('filter-status')">
                                    <span>Status Pegawai</span>
                                    <svg class="w-3 h-3 {{ request('col_status_pegawai') || (request('sort_col') == 'status_kepegawaian') ? 'text-blue-600' : 'text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3 5a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm2 5a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z"></path></svg>
                                </div>
                                <div id="filter-status" class="excel-filter-popup absolute top-full left-0 mt-1 w-48 bg-white border border-gray-400 shadow-2xl rounded-md z-[99] p-2 text-left font-normal normal-case">
                                    <form action="{{ url()->current() }}" method="GET">
                                        @foreach(request()->except(['col_status_pegawai', 'sort_col', 'sort_dir', 'page']) as $key => $value) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endforeach
                                        <div class="mb-2 pb-2 border-b border-gray-300 flex flex-col gap-1">
                                            <button type="submit" name="sort_dir" value="asc" onclick="document.getElementById('sort_status').value='status_kepegawaian'" class="w-full text-left px-2 py-1 hover:bg-blue-50 text-[10px] rounded flex items-center gap-2"><span class="text-blue-600 font-bold">A↓</span> Urutkan A-Z</button>
                                            <button type="submit" name="sort_dir" value="desc" onclick="document.getElementById('sort_status').value='status_kepegawaian'" class="w-full text-left px-2 py-1 hover:bg-blue-50 text-[10px] rounded flex items-center gap-2"><span class="text-blue-600 font-bold">Z↑</span> Urutkan Z-A</button>
                                            <input type="hidden" id="sort_status" name="sort_col" value="{{ request('sort_col') }}">
                                        </div>
                                        <div>
                                            <label class="text-[9px] font-bold text-gray-500 uppercase mb-1 block">Cari Status (PNS/NON-ASN DLL):</label>
                                            <input type="text" name="col_status_pegawai" value="{{ request('col_status_pegawai') }}" class="w-full border border-gray-400 rounded px-2 py-1 text-xs focus:border-blue-500" placeholder="Ketik status...">
                                        </div>
                                        <div class="mt-2 flex gap-1">
                                            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded text-[10px] w-full font-bold hover:bg-blue-700">Terapkan</button>
                                            @if(request('col_status_pegawai') || request('sort_col') == 'status_kepegawaian')
                                                <a href="{{ request()->fullUrlWithQuery(['col_status_pegawai' => null, 'sort_col' => null, 'sort_dir' => null]) }}" class="bg-red-50 text-red-600 border border-red-200 px-2 py-1 rounded text-[10px] font-bold hover:bg-red-100 text-center">Clear</a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </th>

                            <th class="border border-gray-600 px-2 text-center w-24">Insentif</th>
                            <th class="border border-gray-600 px-2 text-center w-48">Nama Lembaga Tempat Mengajar</th>
                            <th class="border border-gray-600 px-2 text-center w-36">Desa Lembaga</th>
                            <th class="border border-gray-600 px-2 text-center w-36">Kecamatan Lembaga</th>
                            <th class="border border-gray-600 px-2 text-center w-24">Jenis Lembaga</th>
                            <th class="border border-gray-600 px-2 text-center w-32">File KTP</th> 
                            <th class="border border-gray-600 px-2 text-center w-32">File KK</th> 
                            <th class="border border-gray-600 px-2 text-center w-32">File Rekening</th> 

                            {{-- 4. ALAMAT GURU (Dengan Filter) --}}
                            <th class="border border-gray-600 px-2 text-left w-64 relative">
                                <div class="flex items-center justify-between cursor-pointer hover:text-blue-600 transition" onclick="toggleExcelFilter('filter-alamat')">
                                    <span>Alamat Guru Sesuai KTP</span>
                                    <svg class="w-3 h-3 {{ request('col_alamat') ? 'text-blue-600' : 'text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3 5a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm2 5a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z"></path></svg>
                                </div>
                                <div id="filter-alamat" class="excel-filter-popup absolute top-full right-0 mt-1 w-48 bg-white border border-gray-400 shadow-2xl rounded-md z-[99] p-2 text-left font-normal normal-case">
                                    <form action="{{ url()->current() }}" method="GET">
                                        @foreach(request()->except(['col_alamat', 'page']) as $key => $value) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endforeach
                                        <div>
                                            <label class="text-[9px] font-bold text-gray-500 uppercase mb-1 block">Cari Alamat Spesifik:</label>
                                            <input type="text" name="col_alamat" value="{{ request('col_alamat') }}" class="w-full border border-gray-400 rounded px-2 py-1 text-xs focus:border-blue-500" placeholder="Ketik jalan/dusun...">
                                        </div>
                                        <div class="mt-2 flex gap-1">
                                            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded text-[10px] w-full font-bold hover:bg-blue-700">Terapkan</button>
                                            @if(request('col_alamat'))
                                                <a href="{{ request()->fullUrlWithQuery(['col_alamat' => null]) }}" class="bg-red-50 text-red-600 border border-red-200 px-2 py-1 rounded text-[10px] font-bold hover:bg-red-100 text-center">Clear</a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </th>

                            <th class="border border-gray-600 px-2 text-center w-36">Tempat Tanggal Lahir</th>
                            <th class="border border-gray-600 px-2 text-center w-20">Jenis Kelamin</th>
                            <th class="border border-gray-600 px-2 text-center w-32">Desa Guru</th>
                            <th class="border border-gray-600 px-2 text-center w-32">Kecamatan Guru</th>
                            <th class="border border-gray-600 px-2 text-center w-28">Kabupaten Guru</th>
                            <th class="border border-gray-600 px-2 text-center w-20">Agama</th>
                            <th class="border border-gray-600 px-2 text-center w-28">Pekerjaan Utama</th>
                            <th class="border border-gray-600 px-2 text-center w-28">No HP</th>
                            <th class="border border-gray-600 px-2 text-center w-40">Nama Ibu Kandung</th>
                            <th class="border border-gray-600 px-2 text-center w-40">Nomer Rekening</th>
                            <th class="border border-gray-600 px-2 text-center w-40">Keterangan</th>
                            <th class="border border-gray-600 px-2 text-center w-40 sticky right-0 bg-gray-100 z-30">Aksi</th>
                        </tr>
                    </thead>
                    
                    {{-- BODY TABEL --}}
                    <tbody class="text-gray-900 font-semibold text-[11px] divide-y divide-gray-600">

                        @forelse($gurus as $index => $guru)
                            {{-- LOGIKA WARNA BARIS SUPER SIMPEL (FASE 2) --}}
                            @php
                                // 1. Cek Kelayakan (PNS/PPPK/Inpassing = Merah)
                                $isTidakLayak = in_array(strtoupper($guru->status_kepegawaian ?? ''), ['PNS', 'PPPK']) || strtoupper($guru->status_sertifikasi ?? '') == 'INPASSING';
                                
                                // Penentuan Warna Default (Standby = Putih)
                                $rowClass = 'hover:bg-gray-50 bg-white';
                                $stickyClass = 'bg-white';

                                if ($isTidakLayak) {
                                    $rowClass = 'bg-red-50 hover:bg-red-100';
                                    $stickyClass = 'bg-red-50';
                                } elseif ($guru->penerima_insentif == 1) {
                                    $rowClass = 'bg-emerald-50 hover:bg-emerald-100'; 
                                    $stickyClass = 'bg-emerald-50';
                                }
                            @endphp

                            <tr class="{{ $rowClass }} transition duration-75 whitespace-nowrap">
                              
                            


                                {{-- 1. NO --}}
                                <td class="border border-gray-600 py-1 text-center font-medium sticky left-0 z-10 {{ $isTidakLayak ? 'bg-red-100' : 'bg-gray-50' }}">
                                    {{ $gurus->firstItem() + $index }}
                                </td>




                                {{-- 2. NAMA --}}
                                <td class="border border-gray-600 px-2 py-1 font-semibold sticky left-10 z-10 shadow-r {{ $stickyClass }}">
                                    {{ $guru->nama_lengkap }}
                                    @if($isTidakLayak)
                                        <span class="block text-[9px] text-red-600 font-normal italic">(Tidak Berhak Mendapat Insentif)</span>
                                    @endif

                                    {{-- [BARU] BADGE INDIKATOR STATUS BERKAS GURU --}}
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @if($guru->status_berkas == 'bermasalah')
                                            <span class="inline-flex items-center bg-orange-50 text-orange-700 text-[9px] font-bold px-1 py-0.5 rounded border border-orange-200 tracking-wide">
                                                File Belum Lengkap
                                            </span>
                                        @elseif($guru->status_berkas == 'pending')
                                            <span class="inline-flex items-center bg-blue-50 text-blue-700 text-[9px] font-bold px-1 py-0.5 rounded border border-blue-200 tracking-wide">
                                                Sedang Diverifikasi
                                            </span>
                                        @endif
                                    </div>
                                </td>



                                {{-- 3. NIK --}}
                                <td class="border border-gray-600 font-semibold px-2 py-1 text-gray-600">
                                    {{ $guru->nik }}
                                </td>

                                {{-- 4. STATUS GURU --}}
                                <td class="border border-gray-600 px-2 py-1 text-center">
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

                                {{-- 5. INSENTIF (SINKRONISASI FASE 2) --}}
                                <td class="border border-gray-600 px-2 py-1 text-center align-middle">
                                    
                                    @if($isTidakLayak)
                                        {{-- JIKA PNS/PPPK/INPASSING: TAMPILKAN SILANG MERAH DI MENU MANAPUN --}}
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-300">
                                            TIDAK BERHAK
                                        </span>
                                    
                                    @else
                                        {{-- JIKA NON-ASN (GURU BERHAK) --}}
                                        @if(Auth::user()->role == 'korcam' && isset($filterType) && $filterType == 'INSENTIF')
                                            

                                            {{-- SAKLAR KHUSUS KORCAM (HANYA DI MENU INSENTIF) --}}
                                            <form id="form-toggle-{{ $guru->id }}" action="{{ route('guru.toggle_insentif', $guru->id) }}" method="POST">
                                                @csrf
                                                @if($guru->penerima_insentif == 1)
                                                    <button type="button" onclick="triggerStatusUpdate('Apakah Anda yakin ingin mengubah status alokasi insentif untuk guru ini?', 'form-toggle-{{ $guru->id }}')" class="text-[10px] font-bold px-2 py-1 rounded-md bg-green-600 text-white border border-green-700 hover:bg-red-600 hover:text-white transition duration-200 shadow-sm group w-full text-center">
                                                        <span class="group-hover:hidden">BERHAK & DIAJUKAN</span>
                                                        <span class="hidden group-hover:inline">COPOT JATAH</span>
                                                    </button>
                                                @else
                                                    <button type="button" onclick="triggerStatusUpdate('Apakah Anda yakin ingin mengubah status alokasi insentif untuk guru ini?', 'form-toggle-{{ $guru->id }}')" class="text-[10px] font-bold px-2 py-1 rounded-md bg-yellow-500 text-white border border-yellow-600 hover:bg-emerald-600 hover:text-white transition duration-200 shadow-sm w-full text-center">
                                                        BERHAK (tidak diajukan)
                                                    </button>
                                                @endif
                                            </form>
                                            

                                        @else
                                            
                                            {{-- TAMPILAN STATIS (UNTUK SUPERADMIN/VERIFIKATOR ATAU DI MENU LAINNYA) --}}
                                            @if($guru->penerima_insentif == 1)
                                                <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-green-100 text-green-800 border border-green-500 block text-center">
                                                    BERHAK & DIAJUKAN
                                                </span>
                                            @else
                                                <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-yellow-100 text-yellow-800 border border-yellow-500 block text-center">
                                                    BERHAK (tidak diajukan)
                                                </span>
                                            @endif

                                        @endif
                                    @endif
                                </td>




                                {{-- 6. NAMA LEMBAGA --}}
                                <td class="border border-gray-600 px-2 py-1">
                                    {{ $guru->lembaga->nama_lembaga ?? '-' }}
                                </td>

                                {{-- DESA LEMBAGA --}}
                                <td class="border border-gray-600 px-2 py-1">
                                    {{ $guru->lembaga->desa->nama_desa ?? '-' }}
                                </td>

                                {{-- KECAMATAN LEMBAGA --}}
                                <td class="border border-gray-600 px-2 py-1">
                                    {{ $guru->lembaga->kecamatan->nama_kecamatan ?? '-' }}
                                </td>

                                {{-- 7. JENIS LEMBAGA --}}
                                <td class="border border-gray-600 px-2 py-1 text-center">
                                    @php
                                        $j = $guru->lembaga->jenis_lembaga ?? '-';
                                        $color = match($j) {
                                            'TPQ' => 'text-green-700 bg-green-100 border-green-300',
                                            'MADIN' => 'text-blue-700 bg-blue-100 border-blue-300',
                                            'PONPES' => 'text-purple-700 bg-purple-100 border-purple-300',
                                            default => 'text-gray-600 bg-gray-100 border-gray-600'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $color }}">
                                        {{ $j }}
                                    </span>
                                </td>

                                {{-- 8. FILE KTP --}}
                                <td class="border border-gray-600 px-2 py-1 text-center {{ $isTidakLayak ? 'bg-red-100' : 'bg-gray-50' }}">
                                    @if($guru->file_ktp)
                                        <div class="flex flex-col items-center">
                                            <button type="button" onclick="bukaModalPdf('{{ asset('dokumen/' . $guru->file_ktp) }}', 'KTP - {{ addslashes($guru->nama_lengkap) }}')" class="text-blue-600 underline font-bold hover:text-blue-800 mb-1 cursor-pointer">
                                                Lihat
                                            </button>
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
                                <td class="border border-gray-600 px-2 py-1 text-center {{ $isTidakLayak ? 'bg-red-100' : 'bg-white' }}">
                                    @if($guru->file_kk)
                                        <div class="flex flex-col items-center">
                                            <button type="button" onclick="bukaModalPdf('{{ asset('dokumen/' . $guru->file_kk) }}', 'KK - {{ addslashes($guru->nama_lengkap) }}')" class="text-green-600 underline font-bold hover:text-green-800 mb-1 cursor-pointer">
                                                Lihat
                                            </button>
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
                                <td class="border border-gray-600 px-2 py-1 text-center {{ $isTidakLayak ? 'bg-red-100' : 'bg-gray-50' }}">
                                    @if($guru->file_bukurekening)
                                        <div class="flex flex-col items-center">
                                            <button type="button" onclick="bukaModalPdf('{{ asset('dokumen/' . $guru->file_bukurekening) }}', 'Buku Rekening - {{ addslashes($guru->nama_lengkap) }}')" class="text-purple-600 underline font-bold hover:text-purple-800 mb-1 cursor-pointer">
                                                Lihat
                                            </button>
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
                                <td class="border border-gray-600 px-2 py-1 truncate max-w-xs" title="{{ $guru->alamat_ktp }}">
                                    {{ Str::limit($guru->alamat_ktp, 30) }}
                                </td>

                                {{-- 12. TTL --}}
                                <td class="border border-gray-600 px-2 py-1">
                                    {{ $guru->tempat_lahir ?? 'KEDIRI' }}{{ !empty($guru->tanggal_lahir) ? ', ' . \Carbon\Carbon::parse($guru->tanggal_lahir)->format('d-m-Y') : '' }}
                                </td>

                                {{-- 13. L/P --}}
                                <td class="border border-gray-600 px-2 py-1 text-center">
                                    {{ $guru->jenis_kelamin }}
                                </td>

                                {{-- 14-18. WILAYAH & KONTAK --}}
                                <td class="border border-gray-600 px-2 py-1">{{ $guru->desa ?? '-' }}</td>
                                <td class="border border-gray-600 px-2 py-1">{{ $guru->kecamatan ?? '-' }}</td>
                                <td class="border border-gray-600 px-2 py-1 text-center">{{ $guru->kabupaten }}</td>
                                <td class="border border-gray-600 px-2 py-1 text-center">{{ $guru->agama }}</td>
                                <td class="border border-gray-600 px-2 py-1 text-center font-bold text-gray-700">{{ $guru->pekerjaan_utama ?: ($guru->status_kepegawaian ?? 'GURU') }}</td>
                                <td class="border border-gray-600 px-2 py-1">{{ $guru->no_hp }}</td>

                                {{-- 19-21. DETAIL LAIN --}}
                                <td class="border border-gray-600 px-2 py-1">{{ $guru->nama_ibu_kandung }}</td>
                                <td class="border border-gray-600 px-2 py-1 ">{{ $guru->nomor_rekening }}</td>
                                <td class="border border-gray-600 px-2 py-1 text-gray-500 italic">{{ $guru->keterangan ?? '-' }}</td>

                                {{-- 22. AKSI --}}
                                <td class="border border-gray-600 py-1 text-center sticky right-0 z-10 shadow-l {{ $stickyClass }}">
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
                                        <form id="form-delete-{{ $guru->id }}" action="{{ route('guru.destroy', $guru->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="triggerStatusUpdate('Yakin ingin menghapus data ini beserta file dokumennya?', 'form-delete-{{ $guru->id }}')" class="p-1 bg-red-100 text-red-700 border border-red-300 rounded hover:bg-red-200 transition" title="Hapus Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="25" class="border border-gray-600 px-4 py-8 text-center bg-gray-50 text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <span class="text-2xl mb-1">📂</span>
                                        <p>Belum ada data guru. Silakan klik tombol+ Tambah.</p>
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
    {{-- ======================================================== --}}
    {{-- 📄 KOTAK POP-UP MODAL PREVIEW PDF (IDENTIK DENGAN LEMBAGA) --}}
    {{-- ======================================================== --}}
    <div id="modalPdf" style="z-index: 9999999 !important;" class="fixed inset-0 bg-black bg-opacity-75 hidden flex justify-center items-center p-4 backdrop-blur-sm transition-opacity">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-5xl h-[90vh] flex flex-col border border-gray-400 overflow-hidden" style="z-index: 10000000 !important;">
            {{-- Header Pop-Up Abu-abu Terang --}}
            <div class="flex justify-between items-center p-3 bg-gray-100 border-b border-gray-400 select-none">
                <h3 id="judulModalPdf" class="text-sm font-bold text-black-800 tracking-wide uppercase">Preview Dokumen</h3>
                <button type="button" onclick="tutupModalPdf()" class="text-black-500 hover:text-red-600 font-black text-xl leading-none px-2 transition cursor-pointer">&times;</button>
            </div>
            {{-- Area PDF (Iframe) --}}
            <div class="flex-grow bg-gray-200 w-full h-full">
                <iframe id="sumberModalPdf" src="" class="w-full h-full border-none"></iframe>
            </div>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- 🧩 [DIKEMBALIKAN] CUSTOM CONFIRM MODAL UNTUK HAPUS & STATUS       --}}
    {{-- ================================================================= --}}
    <div id="custom-confirm-modal" style="z-index: 999998;" class="hidden fixed inset-0 flex items-center justify-center bg-black/60 p-4">
        <div class="bg-white rounded-md border border-gray-400 shadow-xl w-full max-w-sm p-4 transform scale-95 transition-transform duration-200">
            <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                <span class="flex items-center justify-center w-5 h-5 rounded-full border border-gray-800 text-[10px] font-bold text-gray-800">?</span>
                <span class="block text-xs font-bold text-black-800 uppercase">Konfirmasi Tindakan</span>
            </div>
            <p id="custom-confirm-message" class="text-xs font-bold text-gray-700 mb-5"></p>
            <div class="flex justify-end gap-2">
                <button id="custom-confirm-cancel" type="button" class="px-3 py-1 h-[32px] border border-gray-400 rounded-md text-[10px] font-bold text-gray-600 hover:bg-gray-100 uppercase transition">Batal</button>
                <button id="custom-confirm-ok" type="button" class="px-3 py-1 h-[32px] border border-green-600 bg-green-600 rounded-md text-[10px] font-bold text-white hover:bg-green-700 uppercase shadow-sm transition">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    <script>
        // 1. Fungsi Inti untuk Membangun & Menampilkan Modal
        function showConfirmDialog(message, onConfirmCallback) {
            const modal = document.getElementById('custom-confirm-modal');
            const msgEl = document.getElementById('custom-confirm-message');
            const btnCancel = document.getElementById('custom-confirm-cancel');
            const btnOk = document.getElementById('custom-confirm-ok');

            // Set pesan teks secara dinamis
            msgEl.textContent = message;

            // Tampilkan modal
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.firstElementChild.classList.replace('scale-95', 'scale-100');
            }, 10);

            // Fungsi untuk menutup modal
            const closeModal = () => {
                modal.firstElementChild.classList.replace('scale-100', 'scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 150);
                
                // Hapus event listener agar tidak menumpuk
                btnCancel.removeEventListener('click', handleCancel);
                btnOk.removeEventListener('click', handleOk);
            };

            const handleCancel = () => closeModal();
            const handleOk = () => {
                closeModal();
                if (typeof onConfirmCallback === 'function') {
                    onConfirmCallback(); // Eksekusi submit form
                }
            };

            btnCancel.addEventListener('click', handleCancel);
            btnOk.addEventListener('click', handleOk);
        }

        // 2. Fungsi Pemicu (Trigger) yang dipanggil oleh Tombol HTML
        function triggerStatusUpdate(pesan, formId) {
            showConfirmDialog(pesan, function() {
                const formToSubmit = document.getElementById(formId);
                if(formToSubmit) {
                    formToSubmit.submit();
                } else {
                    console.error("Gagal: Form dengan ID '" + formId + "' tidak ditemukan.");
                }
            });
        }
    </script>

    <script>
        // Fungsi buka tutup pop-up per kolom
        function toggleExcelFilter(id) {
            // Tutup semua popup lain yang sedang terbuka
            document.querySelectorAll('.excel-filter-popup').forEach(el => {
                if(el.id !== id) el.classList.remove('show');
            });
            
            // Toggle (Buka/Tutup) popup yang diklik
            const popup = document.getElementById(id);
            popup.classList.toggle('show');
        }

        // Fungsi otomatis tutup popup jika user klik area kosong di luar tabel
        document.addEventListener('click', function(event) {
            // Cek apakah area yang diklik bukan bagian dari header filter
            const isClickInside = event.target.closest('th');
            if (!isClickInside) {
                document.querySelectorAll('.excel-filter-popup').forEach(el => {
                    el.classList.remove('show');
                });
            }
        });
    </script>

    <script>
        // Logika Buka/Tutup Modal PDF (Identik Lembaga + Support Tombol Escape)
        function bukaModalPdf(urlPdf, judul) {
            document.getElementById('sumberModalPdf').src = urlPdf;
            document.getElementById('judulModalPdf').innerText = judul;
            document.getElementById('modalPdf').classList.remove('hidden');
        }

        function tutupModalPdf() {
            document.getElementById('modalPdf').classList.add('hidden');
            document.getElementById('sumberModalPdf').src = ""; // Clear memori iframe
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                tutupModalPdf();
            }
        });
    </script>
</x-app-layout>