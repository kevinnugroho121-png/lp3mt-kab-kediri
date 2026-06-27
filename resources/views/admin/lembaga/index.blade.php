<x-app-layout>
    {{-- CONTAINER UTAMA --}}
    {{-- CONTAINER UTAMA --}}
    <div class="pb-1 pt-1 px-1 sm:px-1 w-full">
        
        {{-- ============================================== --}}
        {{-- 1. HEADER & CONTROL BAR (SATU KOTAK KOMPAK)    --}}
        {{-- ============================================== --}}
        <div class="mb-1 bg-white p-1 rounded-lg border border-gray-400 shadow-sm">
            <form action="{{ url()->current() }}" method="GET"> 
                
                {{-- BARIS 1: Judul Sejajar & Tombol Aksi --}}
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-0 pb-0 border-b border-gray-200">
                    
                    {{-- Judul & Total (Diubah jadi sejajar pakai Flexbox) --}}
                    <div class="flex flex-wrap items-end gap-1 mb-0 lg:mb-0">
                        <h2 class="text-xl font-bold text-black-800 uppercase tracking-tight leading-none">
                            Data Lembaga - Jumlah
                        </h2>
                        <p class="text-xl font-bold text-black-800 uppercase tracking-tight leading-none">
                        : <span class="font-bold text-black-700">{{ $lembagas->total() }}</span> Lembaga
                        </p>
                        @if(Auth::user()->role == 'korcam')
                            <p class="text-xl text-green-600 font-bold leading-none mb-0 border-l-2 border-gray-300 pl-1">
                                WILAYAH KECAMATAN {{ Auth::user()->kecamatan->nama_kecamatan ?? '-' }}
                            </p>
                        @endif
                    </div>
                    
                    {{-- Tombol Export & Tambah (Diperkecil sedikit) --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('lembaga.export', request()->all()) }}" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-1 py-1 rounded-md text-sm font-bold shadow-sm gap-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Excel
                        </a>
                        <a href="{{ route('lembaga.create') }}" class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-1 py-1 rounded-md text-sm font-bold shadow-sm gap-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Lembaga
                        </a>
                    </div>
                </div>

                {{-- BARIS 2: Jajaran Filter (Grid 7 Kolom Lurus 1 Baris) --}}
                <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-7 gap-1 items-end">
                    
                    {{-- 1. Search --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-black-500 uppercase tracking-wider ml-1">Cari Lembaga</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama..." 
                               class="w-full border border-gray-400 rounded-md px-2 py-1 text-sm focus:outline-none focus:border-blue-500 h-8">
                    </div>

                    {{-- 2. Filter Kecamatan --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-black-500 uppercase tracking-wider ml-1">Kecamatan</label>
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
                        <label class="text-[10px] font-bold text-black-500 uppercase tracking-wider ml-1">Desa</label>
                        <select name="filter_desa" id="filter_desa" class="select2-filter w-full border border-gray-400 rounded-md px-1 py-1 text-sm focus:outline-none focus:border-blue-500 h-8">
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

                    {{-- 4. Filter Jenis --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-black-500 uppercase tracking-wider ml-1">Jenis</label>
                        <select name="filter_jenis" class="bg-gray-50 border border-gray-400 text-black-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full h-8 px-1 py-1">
                            <option value="">- Semua -</option>
                            <option value="TPQ" {{ request('filter_jenis') == 'TPQ' ? 'selected' : '' }}>TPQ</option>
                            <option value="MADIN" {{ request('filter_jenis') == 'MADIN' ? 'selected' : '' }}>MADIN</option>
                            <option value="PONPES" {{ request('filter_jenis') == 'PONPES' ? 'selected' : '' }}>PONPES</option>
                        </select>
                    </div>

                    {{-- 5. Filter Ormas --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-black-500 uppercase tracking-wider ml-1">Ormas</label>
                        <select name="filter_ormas" class="bg-gray-50 border border-gray-400 text-black-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full h-8 px-1 py-1">
                            <option value="">- Semua -</option>
                            <option value="NU" {{ request('filter_ormas') == 'NU' ? 'selected' : '' }}>NU</option>
                            <option value="Muhammadiyah" {{ request('filter_ormas') == 'Muhammadiyah' ? 'selected' : '' }}>Muh</option>
                            <option value="LDII" {{ request('filter_ormas') == 'LDII' ? 'selected' : '' }}>LDII</option>
                            <option value="Lainnya" {{ request('filter_ormas') == 'Lainnya' ? 'selected' : '' }}>Lain</option>
                        </select>
                    </div>

                    {{-- 6. Filter Berkas --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-black-500 uppercase tracking-wider ml-1">Status Berkas</label>
                        <select name="filter_berkas" class="bg-gray-50 border border-gray-400 text-black-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full h-8 px-1 py-1">
                            <option value="">- Semua Status -</option>
                            <option value="kosong" {{ request('filter_berkas') == 'kosong' ? 'selected' : '' }}>📄 Kosong</option>
                            <option value="pending" {{ request('filter_berkas') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="ditolak" {{ request('filter_berkas') == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                            <option value="disetujui" {{ request('filter_berkas') == 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                        </select>
                    </div>

                    {{-- 7. Tombol Cari & Reset --}}
                    <div class="w-full flex gap-1 h-8">
                        <button type="submit" class="flex-1 bg-blue-600 text-white rounded text-xs font-bold hover:bg-blue-700 flex items-center justify-center gap-1 shadow-sm transition">Cari</button>
                        <a href="{{ url()->current() }}" class="flex-1 bg-red-50 text-red-600 border border-red-200 rounded text-xs font-bold hover:bg-red-100 flex items-center justify-center shadow-sm transition">Reset</a>
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


        {{-- BLOK NOTIFIKASI ERROR EXCEL CUSTOM (REJECT-ALL) --}}
        @if (session('custom_excel_errors'))
            <div class="mb-4 bg-red-50 border-2 border-red-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center mb-3 text-red-800">
                    <span class="text-xl mr-2">⚠️</span>
                    <strong class="font-extrabold text-sm tracking-tight">Sistem Menolak File! Terdeteksi Data Kosong / Duplikat. Seluruh baris BATAL disimpan demi keamanan data.</strong>
                </div>
                
                <div class="border-l-4 border-red-600 bg-white p-4 rounded-xl shadow-inner">
                    <p class="font-bold text-red-900 text-xs mb-2 uppercase tracking-wide">Daftar Baris yang Bermasalah (Wajib Diperbaiki di Excel):</p>
                    <div class="max-h-60 overflow-y-auto text-xs text-red-700 font-medium">
                        <ul class="list-disc pl-5 space-y-1.5">
                            @foreach (session('custom_excel_errors') as $errorPesan)
                                <li>{!! $errorPesan !!}</li>
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

        {{-- Kotak Form Hijau (Diperkecil & Dirapatkan) --}}
        <div class="mb-1 bg-emerald-50 border border-emerald-300 rounded-lg py-1 px-1">
            <form action="{{ route('lembaga.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center justify-between gap-2">
                @csrf
                <div class="w-full sm:w-auto">
                    <h4 class="font-bold italic text-emerald-600 text-sm">* Import Data Lembaga via Excel. Pastikan format sesuai template.</h4>

                </div>
                <div class="flex items-center space-x-2 w-full sm:w-auto justify-end">

                    <input type="file" name="file" accept=".xlsx, .xls" required class="block w-full text-xs text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-white file:text-emerald-700 file:border file:border-emerald-300 hover:file:bg-emerald-100 cursor-pointer transition">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1.5 px-4 rounded text-xs transition shadow-sm whitespace-nowrap">
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
                        <tr class="bg-gray-100 text-black-800 uppercase text-[10px] tracking-wider font-bold h-10 border-b border-gray-400">
                            <th class="border-r border-gray-400 w-10 text-center sticky left-0 bg-gray-100 z-10">No</th>

                            <th class="border-r border-gray-400 px-3 text-center w-48 sticky left-10 bg-gray-100 z-10">Identitas Lembaga</th>
                            
                            <th class="border-r border-gray-400 px-2 text-center w-24">Jenis lembaga</th>
                            
                            <th class="border-r border-gray-400 px-3 text-center w-32">Alamat lembaga</th>
                            <th class="border border-gray-400 px-1 py-2 text-center w-24">Dokumentasi lembaga</th>
                            <th class="border-r border-gray-400 px-2 text-center w-16">Jumlah Santri</th>
                            <th class="border-r border-gray-400 px-3 text-center w-32">Jumlah Guru</th>
                            <th class="border-r border-gray-400 px-3 text-center w-32">Status Insentif</th>
                            
                            {{-- KOLOM LEGALITAS --}}
                            <th class="border-r border-gray-400 px-3 text-center w-32 bg-teal-50">Surat IJOP Lembaga</th>
                            <th class="border-r border-gray-400 px-3 text-center w-32 bg-teal-50">Surat Keterangan Domisili</th> {{-- [BARU] Kolom SKD --}}
                            <th class="border-r border-gray-400 px-3 text-center w-32 bg-teal-50">Surat Pernyataan Tanggung Jawab Mutlak</th>
                            <th class="border-r border-gray-400 px-3 text-center w-32 bg-teal-50">SK Aktif Mengajar</th>
                            
                            <th class="border-l border-gray-400 px-2 text-center w-24 sticky right-0 bg-gray-100 z-10">Aksi</th>
                        </tr>
                    </thead>
                    
                    {{-- Ubah warna horizontal line (divide) agar setara dengan vertical line (gray-400) --}}
                    <tbody class="text-black-900 text-[11px] divide-y divide-gray-600">
                        @forelse($lembagas as $index => $lembaga)
                            <tr class="hover:bg-yellow-50 transition duration-75 h-16 align-top">
                                
                                {{-- 1. NO --}}
                                <td class="border-r border-gray-400 text-center font-medium bg-gray-50 py-1 sticky left-0 z-10">{{ $lembagas->firstItem() + $index }}</td>
                                


                                {{-- 2. IDENTITAS --}}
                                <td class="border-r border-gray-400 px-0.5 py-0.5 sticky left-10 bg-white z-10 hover:bg-yellow-50">
                                    <div class="font-bold text-sm text-black-800 uppercase mb-0.5 leading-tight flex items-center gap-2">
                                        {{ $lembaga->nama_lembaga }}                                       
                                    </div>
                                    <div class="text-[10px] text-black-500 leading-tight">
                                        <div class="mb-0.5 text-[11px] font-semibold"><span class="font-semibold text-[11px]">Kepala :</span> {{ $lembaga->kepala_lembaga ?? '-' }}</div>
                                        <div class="mb-0.5 text-[11px] font-semibold">No.Hp : {{ $lembaga->no_telp ?? '-' }}</div>
                                    </div>

                                    {{-- [BARU] BADGE INDIKATOR STATUS BERKAS LEMBAGA --}}
                                    <div class="mt-1.5 flex flex-wrap gap-1">
                                        @if($lembaga->status_berkas == 'bermasalah')
                                            <span class="inline-flex items-center bg-orange-50 text-orange-700 text-[10px] font-bold px-1 py-0.5 rounded border border-orange-200 tracking-wide">
                                                File Belum Lengkap
                                            </span>
                                        @elseif($lembaga->status_berkas == 'pending')
                                            <span class="inline-flex items-center bg-blue-50 text-blue-700 text-[10px] font-bold px-1 py-0.5 rounded border border-blue-200 tracking-wide">
                                                Sedang Diverifikasi
                                            </span>
                                        @endif
                                    </div>




                                   
                                </td>




                                {{-- 3. JENIS --}}
                                <td class="border-r border-gray-400 text-center px-1 py-1">
                                    @php
                                        $j = $lembaga->jenis_lembaga;
                                        $color = match($j) {
                                            'TPQ' => 'bg-green-100 text-green-800 border-green-200',
                                            'MADIN' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'PONPES' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            default => 'bg-gray-100 text-black-800'
                                        };
                                    @endphp
                                    <div class="px-2 py-1 rounded text-[10px] font-bold mb-1 border {{ $color }}">{{ $j }}</div>
                                    <div class="text-[9px] border border-gray-400 rounded px-1">{{ $lembaga->ormas ?? '-' }}</div>
                                    
                                </td>

                                {{-- 4. LOKASI --}}
                                <td class="border-r border-gray-400 px-1 py-1">
                                    <div class="font-semibold text-[12px] mb-0.5">{{ $lembaga->desa->nama_desa ?? '-' }}</div>
                                    <div class="text-black-500 text-[12px] mb-1 border-b border-gray-400 pb-0 font-semibold">KEC. {{ $lembaga->kecamatan->nama_kecamatan ?? '-' }}</div>
                                    
                                    {{-- Badge Status Aktif Dipindah ke Sini --}}
                                    <div class="flex items-center">
                                        <span class="text-[9px] px-1.5 py-0.5 rounded font-bold tracking-wider w-full text-center border {{ strtoupper($lembaga->status) == 'AKTIF' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                            {{ $lembaga->status ?? 'AKTIF' }}
                                        </span>
                                    </div>

                                    
                                </td>


                                {{-- 📸 [BARU] ICON GRID MINI INTERAKTIF DENGAN POP-UP MODAL --}}
                                <td class="border border-gray-400 px-1 py-1 text-center align-top bg-gray-50">
                                    <div class="grid grid-cols-2 gap-1 w-12 mx-auto">
                                        {{-- 1. Profil Lembaga --}}
                                        @if($lembaga->foto_lembaga)
                                            <button onclick="bukaModalGambar('{{ asset('storage/' . $lembaga->foto_lembaga) }}', 'A. Foto Profil Lembaga')" title="Lihat Profil Lembaga" class="bg-blue-100 text-blue-700 border border-blue-300 p-0.5 rounded hover:bg-blue-600 hover:text-white transition text-[10px]">📸</button>
                                        @else
                                            <span title="Kosong" class="bg-gray-100 text-black-300 border border-gray-400 p-0.5 rounded text-[10px] cursor-not-allowed">📸</span>
                                        @endif

                                        {{-- 2. Nambor --}}
                                        @if($lembaga->foto_nambor)
                                            <button onclick="bukaModalGambar('{{ asset('storage/' . $lembaga->foto_nambor) }}', 'B. Papan Nama / Nambor')" title="Lihat Papan Nama" class="bg-indigo-100 text-indigo-700 border border-indigo-300 p-0.5 rounded hover:bg-indigo-600 hover:text-white transition text-[10px]">🏷️</button>
                                        @else
                                            <span title="Kosong" class="bg-gray-100 text-black-300 border border-gray-400 p-0.5 rounded text-[10px] cursor-not-allowed">🏷️</span>
                                        @endif

                                        {{-- 3. Gedung Bangunan --}}
                                        @if($lembaga->foto_bangunan)
                                            <button onclick="bukaModalGambar('{{ asset('storage/' . $lembaga->foto_bangunan) }}', 'C. Gedung Bangunan')" title="Lihat Bangunan" class="bg-amber-100 text-amber-700 border border-amber-300 p-0.5 rounded hover:bg-amber-600 hover:text-white transition text-[10px]">🏢</button>
                                        @else
                                            <span title="Kosong" class="bg-gray-100 text-black-300 border border-gray-400 p-0.5 rounded text-[10px] cursor-not-allowed">🏢</span>
                                        @endif

                                        {{-- 4. KBM --}}
                                        @if($lembaga->foto_kbm)
                                            <button onclick="bukaModalGambar('{{ asset('storage/' . $lembaga->foto_kbm) }}', 'D. Kegiatan Belajar (KBM)')" title="Lihat KBM" class="bg-green-100 text-green-700 border border-green-300 p-0.5 rounded hover:bg-green-600 hover:text-white transition text-[10px]">👥</button>
                                        @else
                                            <span title="Kosong" class="bg-gray-100 text-black-300 border border-gray-400 p-0.5 rounded text-[10px] cursor-not-allowed">👥</span>
                                        @endif
                                    </div>
                                </td>




                                {{-- 5. SANTRI --}}
                                <td class="border-r border-gray-400 py-0 text-center font-bold text-blue-600 text-sm">
                                    {{ $lembaga->jumlah_santri }}
                                </td>

                                {{-- 6. GURU --}}
                                <td class="border-r border-gray-400 px-1 py-0">
                                    <div class="flex justify-between font-bold text-blue-700 border-b border-gray-400 mb-1">
                                        <span>TOTAL :</span> <span>{{ $lembaga->jumlah_guru }}</span>
                                    </div>
                                    <div class="flex justify-between text-black-500 font-semibold text-[10px]">
                                        <span>PNS :</span> <span class="font-bold">{{ $lembaga->jumlah_pns }}</span>
                                    </div>
                                    <div class="flex justify-between text-black-500 font-semibold text-[10px]">
                                        <span>PPPK :</span> <span class="font-bold">{{ $lembaga->jumlah_pppk }}</span>
                                    </div>
                                    <div class="flex justify-between text-black-500 font-semibold text-[10px]">
                                        <span>Sesuai Kriteria :</span> <span class="font-bold">{{ $lembaga->jumlah_sertifikasi }}</span>
                                    </div>
                                </td>

                                {{-- 7. INSENTIF [REVISI POIN 7] --}}
                                <td class="border-r border-gray-400 px-1 text-[10px]">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-green-600 font-semibold text-[10px]">Diajukan : <b>{{ $lembaga->hitung_guru_diajukan }}</b></div>
                                        <div class="text-red-500 font-semibold text-[10px]">Tidak Diajukan : <b>{{ $lembaga->hitung_guru_tidak_diajukan }}</b></div>
                                    </div>

                                    {{-- Keterangan --}}
                                    @if($lembaga->keterangan)
                                        <div class="text-[10px] text-black-400 mt-1 italic leading-tight border-t border-gray-400 pt-1 font-semibold">Ket : {{ \Illuminate\Support\Str::limit($lembaga->keterangan, 40) }}</div>
                                    @endif
                                </td>



                                {{-- 8. LEGALITAS IJOP (MURNI) --}}
                                <td class="border-r border-gray-400 py-1 text-center align-top bg-blue-50/30">
                                    <div class="flex flex-col items-center gap-1">
                                        @if($lembaga->file_ijop)
                                            <button onclick="bukaModalPdf('{{ asset('storage/' . $lembaga->file_ijop) }}', 'IJOP - {{ addslashes($lembaga->nama_lembaga) }}')" class="flex items-center gap-1 text-green-600 text-[10px] font-bold bg-green-50 px-2 py-1 rounded border border-green-300 hover:bg-green-600 hover:text-white transition shadow-sm" title="Lihat IJOP">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> Cek File
                                            </button>
                                        @else
                                            <div class="flex items-center gap-1 text-red-500 text-[10px] font-bold bg-red-50 px-1.5 py-0.5 rounded border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg> Kosong
                                            </div>
                                        @endif
                                        @php
                                            $badgeIjop = match($lembaga->status_ijop) {
                                                'Disetujui' => 'text-green-700 bg-green-100', 'Ditolak' => 'text-red-700 bg-red-100', default => 'text-yellow-700 bg-yellow-100'
                                            };
                                        @endphp
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $badgeIjop }}">{{ $lembaga->status_ijop ?? 'Pending' }}</span>
                                        <div class="mt-1 pt-1 border-t border-blue-100 text-[8px] text-black-500 leading-tight w-full">
                                            <div class="text-[10px] font-semibold"">Exp: <span class="font-bold text-black-600">{{ $lembaga->masa_berlaku_ijop ? \Carbon\Carbon::parse($lembaga->masa_berlaku_ijop)->format('d/m/Y') : '-' }}</span></div>
                                        </div>
                                    </div>

                                 
                                </td>

                                {{-- 8B. LEGALITAS SKD SEMENTARA [BARU] --}}
                                <td class="border-r border-gray-400 py-1 text-center align-top bg-teal-50/30">
                                    <div class="flex flex-col items-center gap-1">
                                        @if($lembaga->file_skd)
                                            <button onclick="bukaModalPdf('{{ asset('storage/' . $lembaga->file_skd) }}', 'SKD - {{ addslashes($lembaga->nama_lembaga) }}')" class="flex items-center gap-1 text-green-600 text-[10px] font-bold bg-green-50 px-2 py-1 rounded border border-green-300 hover:bg-green-600 hover:text-white transition shadow-sm" title="Lihat SKD">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> Cek File
                                            </button>
                                        @else
                                            <div class="flex items-center gap-1 text-red-500 text-[10px] font-bold bg-red-50 px-1.5 py-0.5 rounded border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg> Kosong
                                            </div>
                                        @endif
                                        @php
                                            $badgeSkd = match($lembaga->status_skd) {
                                                'Disetujui' => 'text-green-700 bg-green-100', 'Ditolak' => 'text-red-700 bg-red-100', default => 'text-yellow-700 bg-yellow-100'
                                            };
                                        @endphp
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $badgeSkd }}">{{ $lembaga->status_skd ?? 'Pending' }}</span>
                                    </div>
                                </td>

                                {{-- 9. LEGALITAS SUPER (SPTJM) --}}
                                <td class="border-r border-gray-400 py-1 text-center align-top bg-purple-50/30">
                                    <div class="flex flex-col items-center gap-1">
                                        @if($lembaga->file_super)
                                            <button onclick="bukaModalPdf('{{ asset('storage/' . $lembaga->file_super) }}', 'SPTJM - {{ addslashes($lembaga->nama_lembaga) }}')" class="flex items-center gap-1 text-green-600 text-[10px] font-bold bg-green-50 px-2 py-1 rounded border border-green-300 hover:bg-green-600 hover:text-white transition shadow-sm" title="Lihat SPTJM">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> Cek File
                                            </button>
                                        @else
                                            <div class="flex items-center gap-1 text-red-500 text-[10px] font-bold bg-red-50 px-1.5 py-0.5 rounded border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg> Kosong
                                            </div>
                                        @endif
                                        @php
                                            $badgeSuper = match($lembaga->status_super) {
                                                'Disetujui' => 'text-green-700 bg-green-100', 'Ditolak' => 'text-red-700 bg-red-100', default => 'text-yellow-700 bg-yellow-100'
                                            };
                                        @endphp
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $badgeSuper }}">{{ $lembaga->status_super ?? 'Pending' }}</span>
                                    </div>
                                </td>

                                {{-- 10. LEGALITAS SKAM --}}
                                <td class="border-r border-gray-400 py-1 text-center align-top bg-orange-50/30">
                                    <div class="flex flex-col items-center gap-1">
                                        @if($lembaga->file_skam)
                                            <button onclick="bukaModalPdf('{{ asset('storage/' . $lembaga->file_skam) }}', 'SKAM - {{ addslashes($lembaga->nama_lembaga) }}')" class="flex items-center gap-1 text-green-600 text-[10px] font-bold bg-green-50 px-2 py-1 rounded border border-green-300 hover:bg-green-600 hover:text-white transition shadow-sm" title="Lihat SKAM">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> Cek File
                                            </button>
                                        @else
                                            <div class="flex items-center gap-1 text-red-500 text-[10px] font-bold bg-red-50 px-1.5 py-0.5 rounded border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>Kosong
                                            </div>
                                        @endif
                                        @php
                                            $badgeSkam = match($lembaga->status_skam) {
                                                'Disetujui' => 'text-green-700 bg-green-100', 'Ditolak' => 'text-red-700 bg-red-100', default => 'text-yellow-700 bg-yellow-100'
                                            };
                                        @endphp
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $badgeSkam }}">{{ $lembaga->status_skam ?? 'Pending' }}</span>
                                    </div>
                                </td>



                                {{-- 10. AKSI (GRID 2x2) --}}
                                <td class="border-l border-gray-400 text-center p-1 align-top w-24 sticky right-0 bg-white z-10">
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
                                            <form id="form-delete-lembaga-{{ $lembaga->id }}" action="{{ route('lembaga.destroy', $lembaga->id) }}" method="POST" class="w-full">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="triggerStatusUpdate('Yakin hapus lembaga ini beserta dokumennya?', 'form-delete-lembaga-{{ $lembaga->id }}')" class="flex items-center justify-center w-full p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition border border-red-200" title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="border border-gray-400 py-8 text-center text-black-400 bg-gray-50">Data lembaga tidak ditemukan.</td></tr>
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
        .select2-container .select2-selection--single { height: 32px; border-color: #9ca3af; border-radius: 0.5rem; font-size: 0.875rem; }
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

    {{-- ======================================================== --}}
    {{-- 🖼️ KOTAK POP-UP MODAL PREVIEW GAMBAR (TIDAK FULL SCREEN) --}}
    {{-- ======================================================== --}}
    <div id="modalGambar" class="fixed inset-0 z-[9999] bg-black bg-opacity-70 hidden flex justify-center items-center p-4 backdrop-blur-sm transition-opacity">
        <div class="relative bg-white rounded-xl shadow-2xl max-w-3xl w-full flex flex-col border border-gray-400 overflow-hidden transform scale-100">
            
            {{-- Header Pop-Up --}}
            <div class="flex justify-between items-center p-3 bg-gray-100 border-b border-gray-400">
                <h3 id="judulModalGambar" class="text-sm font-bold text-black-800 tracking-wide uppercase">Preview Gambar</h3>
                <button onclick="tutupModalGambar()" class="text-black-500 hover:text-red-600 font-black text-xl leading-none px-2 transition">&times;</button>
            </div>
            
            {{-- Area Gambar --}}
            <div class="p-4 flex justify-center items-center bg-gray-200">
                <img id="sumberModalGambar" src="" alt="Preview" class="max-w-full max-h-[70vh] object-contain rounded shadow-sm border border-gray-400">
            </div>
        </div>
    </div>

    {{-- Script Pembuka & Penutup Pop-Up --}}
    <script>
        function bukaModalGambar(urlGambar, judul) {
            document.getElementById('sumberModalGambar').src = urlGambar;
            document.getElementById('judulModalGambar').innerText = judul;
            document.getElementById('modalGambar').classList.remove('hidden');
        }
        function tutupModalGambar() {
            document.getElementById('modalGambar').classList.add('hidden');
            document.getElementById('sumberModalGambar').src = "";
        }
        
        // =======================================================
        // [BARU POIN 6] JAVASCRIPT ANTI-LOMPAT (STAY ON PAGE)
        // =======================================================
        
        // 1. Simpan posisi scroll saat di-scroll
        window.addEventListener('scroll', function() {
            localStorage.setItem('scrollPositionLembaga', window.scrollY);
        });

        // 2. Kembalikan posisi scroll saat halaman selesai di-load (refresh)
        const savedScrollPosition = localStorage.getItem('scrollPositionLembaga');
        if (savedScrollPosition) {
            // Gunakan setTimeout kecil untuk memastikan DOM sudah ter-render sempurna
            setTimeout(() => {
                window.scrollTo({
                    top: parseInt(savedScrollPosition),
                    behavior: 'instant' // Langsung lompat tanpa animasi (smooth) agar tidak pusing
                });
            }, 100);
        }

        // 3. (Opsional/Jaga-jaga) Jika ada tombol yang di-klik dan melakukan submit/refresh,
        // paksa simpan posisi terakhir detik itu juga
        $('form').on('submit', function() {
            localStorage.setItem('scrollPositionLembaga', window.scrollY);
        });
    </script>

    {{-- ======================================================== --}}
    {{-- 📄 KOTAK POP-UP MODAL PREVIEW PDF (FULL SCREEN)          --}}
    {{-- ======================================================== --}}
    <div id="modalPdf" class="fixed inset-0 z-[9999] bg-black bg-opacity-75 hidden flex justify-center items-center p-4 backdrop-blur-sm transition-opacity">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-5xl h-[90vh] flex flex-col border border-gray-400 overflow-hidden">
            {{-- Header Pop-Up --}}
            <div class="flex justify-between items-center p-3 bg-gray-100 border-b border-gray-400">
                <h3 id="judulModalPdf" class="text-sm font-bold text-black-800 tracking-wide uppercase">Preview Dokumen</h3>
                <button onclick="tutupModalPdf()" class="text-black-500 hover:text-red-600 font-black text-xl leading-none px-2 transition">&times;</button>
            </div>
            {{-- Area PDF (Pake Iframe) --}}
            <div class="flex-grow bg-gray-200 w-full h-full">
                <iframe id="sumberModalPdf" src="" class="w-full h-full border-none"></iframe>
            </div>
        </div>
    </div>

    <script>
        // Logika Buka/Tutup Modal PDF
        function bukaModalPdf(urlPdf, judul) {
            document.getElementById('sumberModalPdf').src = urlPdf;
            document.getElementById('judulModalPdf').innerText = judul;
            document.getElementById('modalPdf').classList.remove('hidden');
        }
        function tutupModalPdf() {
            document.getElementById('modalPdf').classList.add('hidden');
            document.getElementById('sumberModalPdf').src = ""; // Clear memori iframe
        }
    </script>

    {{-- ================================================================= --}}
    {{-- 🧩 [MODAL & SCRIPT] CUSTOM CONFIRM UNTUK TOMBOL AKSI              --}}
    {{-- ================================================================= --}}
    
    <div id="custom-confirm-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
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



</x-app-layout>