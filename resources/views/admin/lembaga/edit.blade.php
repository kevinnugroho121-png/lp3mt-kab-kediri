<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            {{ __('Edit Data Lembaga') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-full mx-auto px-1 sm:px-1 lg:px-1">
            
            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-2">
                <div>
                    <h1 class="text-2xl font-bold text-black-800 uppercase">EDIT DATA LEMBAGA: {{ $lembaga->nama_lembaga }}</h1>


                    <p class="text-sm text-black-500 mt-1">Perbarui data identitas, statistik, atau dokumen.</p>
                </div>
                <a href="{{ route('lembaga.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-black-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    &larr; Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">

                {{-- FORM START --}}
                <form action="{{ route('lembaga.update', $lembaga->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    {{-- [BARU] ALERT NOTIFIKASI JIKA ADA ERROR VALIDASI / NAMA GANDA --}}
                    @if ($errors->any())
                        <div class="mb-5 bg-red-50 border-l-4 border-red-600 p-4 rounded-r-md shadow-sm">
                            <div class="flex items-center gap-2 mb-2 text-red-800">
                                <span class="text-lg font-black">⚠️</span>
                                <strong class="text-xs font-bold uppercase tracking-tight">Gagal Memperbarui Lembaga! Mohon periksa kembali:</strong>
                            </div>
                            <ul class="list-disc pl-6 text-xs text-red-700 font-semibold space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- SECTION A: IDENTITAS --}}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                            <span class="bg-blue-100 text-blue-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">A</span>
                            <h3 class="text-base font-bold text-black-800">Identitas & Lokasi</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 px-1">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Nama Lembaga <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $lembaga->nama_lembaga) }}" class="w-full border {{ $errors->has('nama_lembaga') ? 'border-red-500 bg-red-50' : 'border-gray-400' }} rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm uppercase" required oninput="this.value = this.value.toUpperCase()">
                                @error('nama_lembaga')
                                    <span class="text-[10px] text-red-600 font-bold block mt-1 leading-tight">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Jenis Lembaga <span class="text-red-500">*</span></label>
                                <select name="jenis_lembaga" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                                    <option value="TPQ" {{ old('jenis_lembaga', $lembaga->jenis_lembaga) == 'TPQ' ? 'selected' : '' }}>TPQ</option>
                                    <option value="MADIN" {{ old('jenis_lembaga', $lembaga->jenis_lembaga) == 'MADIN' ? 'selected' : '' }}>MADIN</option>
                                    <option value="PONPES" {{ old('jenis_lembaga', $lembaga->jenis_lembaga) == 'PONPES' ? 'selected' : '' }}>PONPES</option>
                                </select>
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Ormas Afiliasi</label>
                                <select name="ormas" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                                    <option value="">- Tidak Ada -</option>
                                    <option value="NU" {{ old('ormas', $lembaga->ormas) == 'NU' ? 'selected' : '' }}>NU</option>
                                    <option value="Muhammadiyah" {{ old('ormas', $lembaga->ormas) == 'Muhammadiyah' ? 'selected' : '' }}>Muhammadiyah</option>
                                    <option value="LDII" {{ old('ormas', $lembaga->ormas) == 'LDII' ? 'selected' : '' }}>LDII</option>
                                </select>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Kecamatan <span class="text-red-500">*</span></label>
                                @if(Auth::user()->role == 'korcam')
                                    <input type="text" value="{{ Auth::user()->kecamatan->nama_kecamatan }}" class="w-full bg-gray-100 border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-gray-500 cursor-not-allowed shadow-sm" readonly>
                                    <input type="hidden" name="kecamatan_id" value="{{ Auth::user()->kecamatan_id }}">
                                @else
                                    <select name="kecamatan_id" id="kecamatanSelect" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                                        @foreach($kecamatans as $kec)
                                            <option value="{{ $kec->id }}" {{ $lembaga->kecamatan_id == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Desa / Kelurahan <span class="text-red-500">*</span></label>
                                <select name="desa_id" id="desaSelect" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" {{ $lembaga->desa_id == $desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- NSBQ dibuat 2 kolom agar seimbang --}}
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Nomor Statistik (NSBQ)</label>
                                <input type="text" name="nsbq" value="{{ old('nsbq', $lembaga->nsbq) }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm uppercase" placeholder="OPSIONAL" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            {{-- Alamat Lengkap (2 Kolom) --}}
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Alamat Lengkap</label>
                                @php
                                    $valAlamat = old('alamat', (!empty($lembaga->alamat) && $lembaga->alamat !== '-') ? $lembaga->alamat : (($lembaga->desa->nama_desa ?? '') . ', KEC. ' . ($lembaga->kecamatan->nama_kecamatan ?? '')));
                                @endphp
                                <input type="text" name="alamat" value="{{ $valAlamat }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm uppercase" placeholder="DUSUN / JL / RT RW (OPSIONAL)" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            {{-- [BARU] Input Titik Koordinat / Link Google Maps (2 Kolom) --}}
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Titik Koordinat / Link Google Maps</label>
                                <input type="text" name="link_gmaps" value="{{ old('link_gmaps', $lembaga->link_gmaps) }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm" placeholder="CONTOH: https://maps.app.goo.gl/... ATAU -7.8123, 112.0123 (OPSIONAL)">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION B: STATISTIK --}}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                            <span class="bg-green-100 text-green-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">B</span>
                            <h3 class="text-base font-bold text-black-800">Statistik & Kontak</h3>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 px-1">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Jml Santri</label>
                                <input type="number" name="jumlah_santri" value="{{ old('jumlah_santri', $lembaga->jumlah_santri) }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 bg-gray-50 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Total Guru</label>
                                <input type="number" name="jumlah_guru" value="{{ old('jumlah_guru', $lembaga->jumlah_guru) }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 bg-gray-50 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Guru Insentif</label>
                                <input type="number" name="penerima_insentif" value="{{ old('penerima_insentif', $lembaga->penerima_insentif) }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Non-Insentif</label>
                                <input type="number" name="belum_menerima_insentif" value="{{ old('belum_menerima_insentif', $lembaga->belum_menerima_insentif) }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Jml PNS</label>
                                <input type="number" name="jumlah_pns" value="{{ old('jumlah_pns', $lembaga->jumlah_pns) }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Jml PPPK</label>
                                <input type="number" name="jumlah_pppk" value="{{ old('jumlah_pppk', $lembaga->jumlah_pppk) }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Sertifikasi</label>
                                <input type="number" name="jumlah_sertifikasi" value="{{ old('jumlah_sertifikasi', $lembaga->jumlah_sertifikasi) }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Status Operasional</label>
                                <select name="status" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                                    <option value="AKTIF" {{ $lembaga->status == 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                                    <option value="TIDAK AKTIF" {{ $lembaga->status == 'TIDAK AKTIF' ? 'selected' : '' }}>TIDAK AKTIF</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Kepala Lembaga</label>
                                <input type="text" name="kepala_lembaga" value="{{ old('kepala_lembaga', $lembaga->kepala_lembaga) }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm uppercase" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">No. Telp / WA</label>
                                <input type="text" name="no_telp" value="{{ old('no_telp', $lembaga->no_telp) }}" placeholder="Contoh: 081234567890" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION C: DOKUMEN & FOTO --}}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                            <span class="bg-purple-100 text-purple-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">C</span>
                            <h3 class="text-base font-bold text-black-800">Dokumen & Foto Lapangan</h3>
                        </div>

                        {{-- DOKUMEN PDF (GRID 2x2) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            
                            {{-- 1. IJOP --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-400 shadow-sm flex flex-col">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-bold text-black-800">1. Scan IJOP Asli</label>
                                    @if($lembaga->file_ijop)
                                        <button type="button" onclick="triggerStatusUpdate('Yakin ingin menghapus permanen berkas IJOP ini sekarang?', 'form-delete-file-ijop')" class="text-[10px] text-red-600 hover:text-white font-bold bg-red-50 hover:bg-red-600 border border-red-300 px-2 py-0.5 rounded transition cursor-pointer">
                                            Hapus File
                                        </button>
                                    @endif
                                </div>
                                <div class="w-full text-left mb-3">
                                    <input type="file" name="file_ijop" id="file_ijop" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer transition" onchange="handleFileSelect(this, 'preview_ijop_edit', 'btn_reset_ijop_edit', 'old_ijop')">
                                    <button type="button" id="btn_reset_ijop_edit" onclick="resetFile('file_ijop', 'preview_ijop_edit', 'btn_reset_ijop_edit', 'old_ijop')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload File Baru</button>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-600 uppercase">Tgl Terbit</label>
                                        <input type="date" name="masa_berlaku_ijop" id="tgl_ijop" value="{{ old('masa_berlaku_ijop', optional($lembaga->masa_berlaku_ijop)->format('Y-m-d')) }}" class="w-full border border-gray-400 rounded-md h-[28px] text-[10px] font-bold px-2">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-600 uppercase">Fisik IJOP</label>
                                        <input type="text" name="ijop" id="status_fisik_ijop_edit" value="{{ $lembaga->file_ijop ? 'ADA' : 'TIDAK ADA' }}" readonly class="w-full bg-gray-100 border border-gray-400 rounded-md h-[28px] text-[10px] font-bold px-2 uppercase text-gray-700 cursor-not-allowed shadow-inner">
                                    </div>
                                    <div class="col-span-2 hidden bg-green-100 text-green-800 text-[10px] py-1.5 rounded text-center font-bold" id="info_masa_berlaku"></div>
                                </div>
                                <input type="hidden" name="status_ijop" value="Pending">
                                
                                @if($lembaga->file_ijop)
                                    <iframe id="old_ijop" src="{{ asset('dokumen/' . $lembaga->file_ijop) }}" class="w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                                @endif
                                <iframe id="preview_ijop_edit" class="hidden w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                            </div>

                            {{-- 2. SKD --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-400 shadow-sm flex flex-col">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-bold text-black-800">2. Scan SKD (Opsional)</label>
                                    @if($lembaga->file_skd)
                                        <button type="button" onclick="triggerStatusUpdate('Yakin ingin menghapus permanen berkas SKD ini sekarang?', 'form-delete-file-skd')" class="text-[10px] text-red-600 hover:text-white font-bold bg-red-50 hover:bg-red-600 border border-red-300 px-2 py-0.5 rounded transition cursor-pointer">
                                             Hapus File
                                        </button>
                                    @endif
                                </div>
                                <div class="w-full text-left mb-3">
                                    <input type="file" name="file_skd" id="file_skd" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-gray-600 file:text-white hover:file:bg-gray-700 cursor-pointer transition" onchange="handleFileSelect(this, 'preview_skd_edit', 'btn_reset_skd_edit', 'old_skd')">
                                    <button type="button" id="btn_reset_skd_edit" onclick="resetFile('file_skd', 'preview_skd_edit', 'btn_reset_skd_edit', 'old_skd')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload File Baru</button>
                                </div>
                                @if($lembaga->file_skd)
                                    <iframe id="old_skd" src="{{ asset('dokumen/' . $lembaga->file_skd) }}" class="w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                                @endif
                                <iframe id="preview_skd_edit" class="hidden w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                            </div>

                            {{-- 3. SPTJM --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-400 shadow-sm flex flex-col">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-bold text-black-800">3. Scan SPTJM Mutlak</label>
                                    @if($lembaga->file_super)
                                        <button type="button" onclick="triggerStatusUpdate('Yakin ingin menghapus permanen berkas SPTJM ini sekarang?', 'form-delete-file-super')" class="text-[10px] text-red-600 hover:text-white font-bold bg-red-50 hover:bg-red-600 border border-red-300 px-2 py-0.5 rounded transition cursor-pointer">
                                             Hapus File
                                        </button>
                                    @endif
                                </div>
                                <div class="w-full text-left mb-3">
                                    <input type="file" name="file_super" id="file_super" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 cursor-pointer transition" onchange="handleFileSelect(this, 'preview_super_edit', 'btn_reset_super_edit', 'old_super')">
                                    <button type="button" id="btn_reset_super_edit" onclick="resetFile('file_super', 'preview_super_edit', 'btn_reset_super_edit', 'old_super')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload File Baru</button>
                                </div>
                                <input type="hidden" name="status_super" value="Pending">
                                @if($lembaga->file_super)
                                    <iframe id="old_super" src="{{ asset('dokumen/' . $lembaga->file_super) }}" class="w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                                @endif
                                <iframe id="preview_super_edit" class="hidden w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                            </div>

                            {{-- 4. SKAM --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-400 shadow-sm flex flex-col">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-bold text-black-800">4. Scan SKAM</label>
                                    @if($lembaga->file_skam)
                                        <button type="button" onclick="triggerStatusUpdate('Yakin ingin menghapus permanen berkas SKAM ini sekarang?', 'form-delete-file-skam')" class="text-[10px] text-red-600 hover:text-white font-bold bg-red-50 hover:bg-red-600 border border-red-300 px-2 py-0.5 rounded transition cursor-pointer">
                                             Hapus File
                                        </button>
                                    @endif
                                </div>
                                <div class="w-full text-left mb-3">
                                    <input type="file" name="file_skam" id="file_skam" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-teal-600 file:text-white hover:file:bg-teal-700 cursor-pointer transition" onchange="handleFileSelect(this, 'preview_skam_edit', 'btn_reset_skam_edit', 'old_skam')">
                                    <button type="button" id="btn_reset_skam_edit" onclick="resetFile('file_skam', 'preview_skam_edit', 'btn_reset_skam_edit', 'old_skam')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload File Baru</button>
                                </div>
                                <input type="hidden" name="status_skam" value="Pending">
                                @if($lembaga->file_skam)
                                    <iframe id="old_skam" src="{{ asset('dokumen/' . $lembaga->file_skam) }}" class="w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                                @endif
                                <iframe id="preview_skam_edit" class="hidden w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                            </div>

                        </div>

                        {{-- FOTO LAMA (Jika Ada) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                <div class="flex items-center justify-between w-full mb-2">
                                    <label class="text-xs font-bold text-black-700 uppercase">A. Profil Lembaga</label>
                                    @if($lembaga->foto_lembaga)
                                        <button type="button" onclick="triggerStatusUpdate('Yakin ingin menghapus permanen Foto Profil ini sekarang?', 'form-delete-file-foto_lembaga')" class="text-[10px] text-red-600 hover:text-white font-bold bg-red-50 hover:bg-red-600 border border-red-300 px-2 py-0.5 rounded transition cursor-pointer">
                                            Hapus Foto
                                        </button>
                                    @endif
                                </div>
                                <div class="w-full h-40 bg-gray-200 border border-gray-400 rounded-md mb-3 overflow-hidden flex justify-center items-center relative">
                                    <img id="preview_lembaga" src="{{ $lembaga->foto_lembaga ? asset('dokumen/' . $lembaga->foto_lembaga) : '#' }}" class="{{ $lembaga->foto_lembaga ? '' : 'hidden' }} object-cover w-full h-full absolute inset-0 z-10" />
                                    <span class="text-black-400 text-[11px] z-0">Tidak Ada Foto</span>
                                </div>
                                <input type="file" name="foto_lembaga" accept="image/*" onchange="previewImageFase3(this, 'preview_lembaga')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-orange-500 file:text-white hover:file:bg-orange-600 cursor-pointer transition">
                            </div>
                            
                            <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                <div class="flex items-center justify-between w-full mb-2">
                                    <label class="text-xs font-bold text-black-700 uppercase">B. Papan Nama / Nambor</label>
                                    @if($lembaga->foto_nambor)
                                        <button type="button" onclick="triggerStatusUpdate('Yakin ingin menghapus permanen Foto Papan Nama ini sekarang?', 'form-delete-file-foto_nambor')" class="text-[10px] text-red-600 hover:text-white font-bold bg-red-50 hover:bg-red-600 border border-red-300 px-2 py-0.5 rounded transition cursor-pointer">
                                             Hapus Foto
                                        </button>
                                    @endif
                                </div>
                                <div class="w-full h-40 bg-gray-200 border border-gray-400 rounded-md mb-3 overflow-hidden flex justify-center items-center relative">
                                    <img id="preview_nambor" src="{{ $lembaga->foto_nambor ? asset('dokumen/' . $lembaga->foto_nambor) : '#' }}" class="{{ $lembaga->foto_nambor ? '' : 'hidden' }} object-cover w-full h-full absolute inset-0 z-10" />
                                    <span class="text-black-400 text-[11px] z-0">Tidak Ada Foto</span>
                                </div>
                                <input type="file" name="foto_nambor" accept="image/*" onchange="previewImageFase3(this, 'preview_nambor')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-orange-500 file:text-white hover:file:bg-orange-600 cursor-pointer transition">
                            </div>
                            
                            <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                <div class="flex items-center justify-between w-full mb-2">
                                    <label class="text-xs font-bold text-black-700 uppercase">C. Gedung / Bangunan</label>
                                    @if($lembaga->foto_bangunan)
                                        <button type="button" onclick="triggerStatusUpdate('Yakin ingin menghapus permanen Foto Bangunan ini sekarang?', 'form-delete-file-foto_bangunan')" class="text-[10px] text-red-600 hover:text-white font-bold bg-red-50 hover:bg-red-600 border border-red-300 px-2 py-0.5 rounded transition cursor-pointer">
                                             Hapus Foto
                                        </button>
                                    @endif
                                </div>
                                <div class="w-full h-40 bg-gray-200 border border-gray-400 rounded-md mb-3 overflow-hidden flex justify-center items-center relative">
                                    <img id="preview_bangunan" src="{{ $lembaga->foto_bangunan ? asset('dokumen/' . $lembaga->foto_bangunan) : '#' }}" class="{{ $lembaga->foto_bangunan ? '' : 'hidden' }} object-cover w-full h-full absolute inset-0 z-10" />
                                    <span class="text-black-400 text-[11px] z-0">Tidak Ada Foto</span>
                                </div>
                                <input type="file" name="foto_bangunan" accept="image/*" onchange="previewImageFase3(this, 'preview_bangunan')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-orange-500 file:text-white hover:file:bg-orange-600 cursor-pointer transition">
                            </div>
                            
                            <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                <div class="flex items-center justify-between w-full mb-2">
                                    <label class="text-xs font-bold text-black-700 uppercase">D. Aktivitas KBM</label>
                                    @if($lembaga->foto_kbm)
                                        <button type="button" onclick="triggerStatusUpdate('Yakin ingin menghapus permanen Foto KBM ini sekarang?', 'form-delete-file-foto_kbm')" class="text-[10px] text-red-600 hover:text-white font-bold bg-red-50 hover:bg-red-600 border border-red-300 px-2 py-0.5 rounded transition cursor-pointer">
                                             Hapus Foto
                                        </button>
                                    @endif
                                </div>
                                <div class="w-full h-40 bg-gray-200 border border-gray-400 rounded-md mb-3 overflow-hidden flex justify-center items-center relative">
                                    <img id="preview_kbm" src="{{ $lembaga->foto_kbm ? asset('dokumen/' . $lembaga->foto_kbm) : '#' }}" class="{{ $lembaga->foto_kbm ? '' : 'hidden' }} object-cover w-full h-full absolute inset-0 z-10" />
                                    <span class="text-black-400 text-[11px] z-0">Tidak Ada Foto</span>
                                </div>
                                <input type="file" name="foto_kbm" accept="image/*" onchange="previewImageFase3(this, 'preview_kbm')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-orange-500 file:text-white hover:file:bg-orange-600 cursor-pointer transition">
                            </div>
                        </div>

                    </div>

                    {{-- FOOTER ACTION DENGAN CATATAN --}}
                    <div class="mt-4 bg-gray-50 px-4 py-3 rounded-lg shadow-sm border border-gray-400 flex flex-col md:flex-row gap-4 items-end justify-between">
                        <div class="flex-grow w-full md:w-1/2">
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Catatan Tambahan (Opsional)</label>
                            <input type="text" name="keterangan" value="{{ old('keterangan', $lembaga->keterangan) }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 shadow-sm focus:border-blue-500 uppercase" placeholder="TULIS JIKA ADA..." oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="flex gap-2 w-full md:w-auto">
                            <a href="{{ route('lembaga.index') }}" class="px-5 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-400 rounded-md hover:bg-gray-100 transition flex items-center">Batal</a>
                            <button type="submit" class="px-6 py-1.5 text-xs font-bold text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                SIMPAN PERUBAHAN
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- SCRIPT LENGKAP --}}
    <script>
        // 1. HITUNG MASA BERLAKU IJOP (5 TAHUN)
        document.addEventListener('DOMContentLoaded', function() {
            const tglIjopInput = document.getElementById('tgl_ijop');
            const infoText = document.getElementById('info_masa_berlaku');

            function hitungMasaBerlaku() {
                if(!tglIjopInput.value) return; // Guard clause jika kosong

                const startDate = new Date(tglIjopInput.value);
                if (!isNaN(startDate.getTime())) {
                    // Tambah 5 Tahun
                    const endDate = new Date(startDate);
                    endDate.setFullYear(endDate.getFullYear() + 5);

                    // Format Tanggal Indonesia (dd-mm-yyyy)
                    const options = { day: '2-digit', month: 'long', year: 'numeric' };
                    const startStr = startDate.toLocaleDateString('id-ID', options);
                    const endStr = endDate.toLocaleDateString('id-ID', options);

                    infoText.innerHTML = `Masa Berlaku: <b>${startStr}</b> s.d <b>${endStr}</b> (5 Tahun)`;
                    infoText.classList.remove('hidden');
                } else {
                    infoText.classList.add('hidden');
                }
            }

            if(tglIjopInput){
                tglIjopInput.addEventListener('change', hitungMasaBerlaku);
                // Jalankan saat halaman load jika sudah ada isinya
                hitungMasaBerlaku();
            }
        });

        // 2. PREVIEW & RESET PDF (Disesuaikan untuk form EDIT)
        const hasExistingIjopFile = {{ $lembaga->file_ijop ? 'true' : 'false' }};

        function handleFileSelect(input, iframeId, btnId, oldFrameId = null) {
            const iframe = document.getElementById(iframeId); const btnReset = document.getElementById(btnId);
            if(oldFrameId) { const oldFrame = document.getElementById(oldFrameId); if(oldFrame) oldFrame.classList.add('hidden'); }
            if (input.files && input.files[0]) {
                if(input.files[0].type !== 'application/pdf'){ alert("Mohon upload PDF!"); input.value = ""; return; }

                if (input.name === 'file_ijop') {
                    const statusFisik = document.getElementById('status_fisik_ijop_edit');
                    if (statusFisik) statusFisik.value = 'ADA';
                }

                const reader = new FileReader();
                reader.onload = function(e) { iframe.src = e.target.result; iframe.classList.remove('hidden'); btnReset.classList.remove('hidden'); }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function resetFile(inputId, iframeId, btnId, oldFrameId = null) {
            const input = document.getElementById(inputId); const iframe = document.getElementById(iframeId); const btnReset = document.getElementById(btnId);
            if(oldFrameId) { const oldFrame = document.getElementById(oldFrameId); if(oldFrame) oldFrame.classList.remove('hidden'); }
            input.value = ""; iframe.src = ""; iframe.classList.add('hidden'); btnReset.classList.add('hidden');

            if (inputId === 'file_ijop') {
                const statusFisik = document.getElementById('status_fisik_ijop_edit');
                if (statusFisik) statusFisik.value = hasExistingIjopFile ? 'ADA' : 'TIDAK ADA';
            }
        }

        // [BARU] FUNGSI PREVIEW GAMBAR UNTUK MENU EDIT
        function previewImageFase3(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            
            if (file) {
                // 1. Validasi Ukuran (Maksimal 1 MB = 1.048.576 Bytes)
                if (file.size > 1048576) {
                    alert("GAGAL! Ukuran gambar [" + file.name + "] terlalu besar.\n\nMaksimal hanya 1 MB. Silakan kompres gambar Anda terlebih dahulu.");
                    input.value = ""; 
                    return;
                }

                // 2. Validasi Format (Wajib Image)
                if (!file.type.startsWith('image/')) {
                    alert("GAGAL! File yang dipilih bukan gambar.\n\nSilakan pilih file berformat JPG, JPEG, PNG, atau JFIF.");
                    input.value = ""; 
                    return;
                }

                // 3. Tampilkan Preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>

    {{-- ================================================================= --}}
    {{--  FORM EKSEKUTOR HAPUS INSTAN PDF & FOTO (DI LUAR FORM UTAMA)     --}}
    {{-- ================================================================= --}}
    <form id="form-delete-file-ijop" action="{{ route('lembaga.delete_file', ['id' => $lembaga->id, 'type' => 'ijop']) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
    <form id="form-delete-file-skd" action="{{ route('lembaga.delete_file', ['id' => $lembaga->id, 'type' => 'skd']) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
    <form id="form-delete-file-super" action="{{ route('lembaga.delete_file', ['id' => $lembaga->id, 'type' => 'super']) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
    <form id="form-delete-file-skam" action="{{ route('lembaga.delete_file', ['id' => $lembaga->id, 'type' => 'skam']) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
    
    <form id="form-delete-file-foto_lembaga" action="{{ route('lembaga.delete_file', ['id' => $lembaga->id, 'type' => 'foto_lembaga']) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
    <form id="form-delete-file-foto_nambor" action="{{ route('lembaga.delete_file', ['id' => $lembaga->id, 'type' => 'foto_nambor']) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
    <form id="form-delete-file-foto_bangunan" action="{{ route('lembaga.delete_file', ['id' => $lembaga->id, 'type' => 'foto_bangunan']) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
    <form id="form-delete-file-foto_kbm" action="{{ route('lembaga.delete_file', ['id' => $lembaga->id, 'type' => 'foto_kbm']) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>

    {{-- 🧩 POP-UP MODAL KONFIRMASI TINDAKAN --}}
    <div id="custom-confirm-modal" style="z-index: 999998;" class="hidden fixed inset-0 flex items-center justify-center bg-black/60 p-4">
        <div class="bg-white rounded-md border border-gray-400 shadow-xl w-full max-w-sm p-4 transform scale-95 transition-transform duration-200">
            <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                <span class="flex items-center justify-center w-5 h-5 rounded-full border border-gray-800 text-[10px] font-bold text-gray-800">?</span>
                <span class="block text-xs font-bold text-black-800 uppercase">Konfirmasi Tindakan</span>
            </div>
            <p id="custom-confirm-message" class="text-xs font-bold text-gray-700 mb-5"></p>
            <div class="flex justify-end gap-2">
                <button id="custom-confirm-cancel" type="button" class="px-3 py-1 h-[32px] border border-gray-400 rounded-md text-[10px] font-bold text-gray-600 hover:bg-gray-100 uppercase transition">Batal</button>
                <button id="custom-confirm-ok" type="button" class="px-3 py-1 h-[32px] border border-red-600 bg-red-600 rounded-md text-[10px] font-bold text-white hover:bg-red-700 uppercase shadow-sm transition">Ya, Hapus Sekarang</button>
            </div>
        </div>
    </div>

    <script>
        function showConfirmDialog(message, onConfirmCallback) {
            const modal = document.getElementById('custom-confirm-modal');
            const msgEl = document.getElementById('custom-confirm-message');
            const btnCancel = document.getElementById('custom-confirm-cancel');
            const btnOk = document.getElementById('custom-confirm-ok');

            msgEl.textContent = message;
            modal.classList.remove('hidden');
            setTimeout(() => { modal.firstElementChild.classList.replace('scale-95', 'scale-100'); }, 10);

            const closeModal = () => {
                modal.firstElementChild.classList.replace('scale-100', 'scale-95');
                setTimeout(() => { modal.classList.add('hidden'); }, 150);
                btnCancel.removeEventListener('click', handleCancel);
                btnOk.removeEventListener('click', handleOk);
            };

            const handleCancel = () => closeModal();
            const handleOk = () => {
                closeModal();
                if (typeof onConfirmCallback === 'function') onConfirmCallback();
            };

            btnCancel.addEventListener('click', handleCancel);
            btnOk.addEventListener('click', handleOk);
        }

        function triggerStatusUpdate(pesan, formId) {
            showConfirmDialog(pesan, function() {
                const formToSubmit = document.getElementById(formId);
                if (formToSubmit) formToSubmit.submit();
            });
        }
    </script>
</x-app-layout>