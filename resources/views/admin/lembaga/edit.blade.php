<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            {{ __('Edit Data Lembaga') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-black-800">Edit Lembaga</h1>
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

                    {{-- SECTION A: IDENTITAS --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <span class="bg-blue-100 text-blue-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">A</span>
                            <h3 class="text-lg font-bold text-black-800">Identitas & Lokasi</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Nama Lembaga <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $lembaga->nama_lembaga) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center" required>
                            </div>

                            {{-- Kecamatan & Desa --}}
                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Kecamatan</label>
                                @if(Auth::user()->role == 'korcam')
                                    <input type="text" value="{{ $lembaga->kecamatan->nama_kecamatan }}" class="w-full bg-gray-100 border-gray-300 rounded-lg text-black-500 text-sm py-2.5 text-center font-bold cursor-not-allowed" readonly>
                                @else
                                    <select name="kecamatan_id" id="kecamatanSelect" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                                        @foreach($kecamatans as $kec)
                                            <option value="{{ $kec->id }}" {{ $lembaga->kecamatan_id == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Desa / Kelurahan</label>
                                <select name="desa_id" id="desaSelect" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" {{ $lembaga->desa_id == $desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Jenis Lembaga</label>
                                <select name="jenis_lembaga" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                                    <option value="TPQ" {{ $lembaga->jenis_lembaga == 'TPQ' ? 'selected' : '' }}>TPQ</option>
                                    <option value="MADIN" {{ $lembaga->jenis_lembaga == 'MADIN' ? 'selected' : '' }}>MADIN</option>
                                    <option value="PONPES" {{ $lembaga->jenis_lembaga == 'PONPES' ? 'selected' : '' }}>PONPES</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Ormas Afiliasi</label>
                                <select name="ormas" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                                    <option value="">- Tidak Ada -</option>
                                    <option value="NU" {{ $lembaga->ormas == 'NU' ? 'selected' : '' }}>Nahdlatul Ulama (NU)</option>
                                    <option value="Muhammadiyah" {{ $lembaga->ormas == 'Muhammadiyah' ? 'selected' : '' }}>Muhammadiyah</option>
                                    <option value="LDII" {{ $lembaga->ormas == 'LDII' ? 'selected' : '' }}>LDII</option>
                                </select>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Nomor Statistik (NSBQ)</label>
                                <input type="text" name="nsbq" value="{{ old('nsbq', $lembaga->nsbq) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Alamat Lengkap</label>
                                <input type="text" name="alamat" value="{{ old('alamat', $lembaga->alamat) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION B: STATISTIK --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <span class="bg-green-100 text-green-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">B</span>
                            <h3 class="text-lg font-bold text-black-800">Statistik & Kontak</h3>
                        </div>


                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-black-500 uppercase mb-1 text-center">Jml Santri</label>
                                <input type="number" name="jumlah_santri" value="{{ old('jumlah_santri', $lembaga->jumlah_santri) }}" class="w-full border-gray-300 bg-gray-50 rounded-lg text-sm font-bold text-center">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-black-500 uppercase mb-1 text-center">Total Guru</label>
                                <input type="number" name="jumlah_guru" value="{{ old('jumlah_guru', $lembaga->jumlah_guru) }}" class="w-full border-gray-300 bg-gray-50 rounded-lg text-sm font-bold text-center">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-black-500 uppercase mb-1 text-center">Guru Insentif</label>
                                <input type="number" name="penerima_insentif" value="{{ old('penerima_insentif', $lembaga->penerima_insentif) }}" class="w-full border-gray-300 rounded-lg text-sm text-center">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-black-500 uppercase mb-1 text-center">Non-Insentif</label>
                                <input type="number" name="belum_menerima_insentif" value="{{ old('belum_menerima_insentif', $lembaga->belum_menerima_insentif) }}" class="w-full border-gray-300 rounded-lg text-sm text-center">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-black-500 uppercase mb-1 text-center">Jml PNS</label>
                                <input type="number" name="jumlah_pns" value="{{ old('jumlah_pns', $lembaga->jumlah_pns) }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 text-sm text-center">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-black-500 uppercase mb-1 text-center">Jml PPPK</label>
                                <input type="number" name="jumlah_pppk" value="{{ old('jumlah_pppk', $lembaga->jumlah_pppk) }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 text-sm text-center">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-black-500 uppercase mb-1 text-center">Sertifikasi</label>
                                <input type="number" name="jumlah_sertifikasi" value="{{ old('jumlah_sertifikasi', $lembaga->jumlah_sertifikasi) }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 text-sm text-center">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Kepala Lembaga</label>
                                <input type="text" name="kepala_lembaga" value="{{ old('kepala_lembaga', $lembaga->kepala_lembaga) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">No. Telp / WA</label>
                                <input type="text" name="no_telp" value="{{ old('no_telp', $lembaga->no_telp) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Status</label>
                                <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                                    <option value="AKTIF" {{ $lembaga->status == 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                                    <option value="TIDAK AKTIF" {{ $lembaga->status == 'TIDAK AKTIF' ? 'selected' : '' }}>TIDAK AKTIF</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION C: DOKUMEN --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <span class="bg-purple-100 text-purple-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">C</span>
                            <h3 class="text-lg font-bold text-black-800">Dokumen Legalitas</h3>
                        </div>

                        <div class="space-y-8">
                            {{-- IJOP --}}
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                                <label class="block text-base font-bold text-black-800 mb-3">1. Surat Izin Operasional (IJOP)</label>
                                
                                @if($lembaga->file_ijop)
                                    <div class="mb-4 text-center">
                                        <p class="text-xs text-green-600 font-bold mb-2">✓ File tersimpan saat ini:</p>
                                        {{-- ADDED: type="application/pdf" --}}
                                        <iframe src="{{ asset('storage/' . $lembaga->file_ijop) }}" type="application/pdf" class="w-full h-[600px] border border-gray-300 rounded-lg bg-white shadow-inner"></iframe>
                                        <p class="text-xs text-black-500 mt-2">Untuk mengganti, silakan upload file baru di bawah ini:</p>
                                    </div>
                                @else
                                    <div class="mb-4 text-center text-black-400 italic border border-dashed border-gray-300 p-4 rounded-lg bg-white">Belum ada file IJOP.</div>
                                @endif

                                <div class="relative group text-center">
                                    <input type="file" name="file_ijop" id="file_ijop" accept="application/pdf"
                                           class="block w-full text-sm text-black-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer text-center"
                                           onchange="handleFileSelect(this, 'preview_ijop_edit', 'btn_reset_ijop_edit')">
                                    
                                    <button type="button" id="btn_reset_ijop_edit" onclick="resetFile('file_ijop', 'preview_ijop_edit', 'btn_reset_ijop_edit')" 
                                            class="hidden mt-2 text-sm text-red-600 hover:text-red-800 font-bold underline transition">
                                        &times; Batal Upload File Baru
                                    </button>
                                </div>

                                <div class="mt-4">
                                    <iframe id="preview_ijop_edit" class="hidden w-full h-[600px] border border-gray-300 rounded-lg bg-white shadow-inner" src=""></iframe>
                                </div>
                                
                                {{-- LOGIKA TANGGAL & ID --}}

                                {{-- [BARU POIN 1] KOTAK UPLOAD SKD --}}
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm mt-8">
                                <div class="flex justify-between items-center mb-4">
                                    <label class="block text-base font-bold text-black-800">2. Surat Keterangan Domisili (SKD)</label>
                                    <span class="text-[10px] bg-white border border-gray-300 px-2 py-0.5 rounded text-black-500 font-mono">PDF Max 2MB</span>
                                </div>
                                <p class="text-xs text-orange-600 mb-4 font-bold italic">*Hanya diisi jika belum memiliki IJOP resmi.</p>

                                @if($lembaga->file_skd)
                                    <div class="mb-4 text-center">
                                        <p class="text-xs text-green-600 font-bold mb-2">✓ File tersimpan saat ini:</p>
                                        <iframe src="{{ asset('storage/' . $lembaga->file_skd) }}" type="application/pdf" class="w-full h-[500px] border border-gray-300 rounded-lg bg-white shadow-inner"></iframe>
                                    </div>
                                @endif

                                <div class="relative group mb-4">
                                    <input type="file" name="file_skd" id="file_skd" accept="application/pdf"
                                           class="block w-full text-sm text-black-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-gray-600 file:text-white hover:file:bg-gray-700 transition cursor-pointer text-center"
                                           onchange="handleFileSelect(this, 'preview_skd_edit', 'btn_reset_skd_edit')">
                                    
                                    <div class="text-center">
                                        <button type="button" id="btn_reset_skd_edit" onclick="resetFile('file_skd', 'preview_skd_edit', 'btn_reset_skd_edit')" 
                                                class="hidden mt-2 text-sm text-red-600 hover:text-red-800 font-bold underline transition">
                                            &times; Batal Upload File Baru
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <iframe id="preview_skd_edit" class="hidden w-full h-[500px] border border-gray-300 rounded-lg bg-white shadow-inner" src=""></iframe>
                                </div>
                            </div>

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                        <div>
                                            <label class="block text-sm font-bold text-black-600 mb-1 text-center md:text-left">Tanggal Terbit IJOP</label>
                                            {{-- VALUE MENGGUNAKAN HELPER OLD & OPTIONAL --}}
                                            <input type="date" name="masa_berlaku_ijop" id="tgl_ijop" 
                                                   value="{{ old('masa_berlaku_ijop', optional($lembaga->masa_berlaku_ijop)->format('Y-m-d')) }}" 
                                                   class="w-full border-gray-300 rounded-md text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 text-center">
                                        </div>
                                        <div>

                                            {{-- 1. Div untuk info masa berlaku (tetap hidden, akan muncul via JS) --}}
                                            <div id="info_masa_berlaku" class="hidden bg-green-50 border border-green-200 text-green-800 text-sm p-3 rounded-lg text-center font-medium mt-4">
                                            </div>

                                            {{-- 2. Div untuk Input Status Fisik IJOP (Dikeluarkan agar SELALU MUNCUL) --}}
                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                <label class="block text-sm font-bold text-black-600 mb-1">Status Fisik Dokumen IJOP</label>
                                                <input type="text" name="ijop" 
                                                    value="{{ old('ijop', $lembaga->ijop) }}" 
                                                    class="w-full border-gray-300 rounded-md text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 uppercase" 
                                                    placeholder="CONTOH: ADA / TIDAK ADA / SUKET DOMISILI" 
                                                    oninput="this.value = this.value.toUpperCase()">
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SUPER --}}
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                                <label class="block text-base font-bold text-black-800 mb-3">3. Surat Pernyataan Tanggung Jawab Mutlak (SPTJM)</label>
                                
                                @if($lembaga->file_super)
                                    <div class="mb-4 text-center">
                                        <p class="text-xs text-green-600 font-bold mb-2">✓ File tersimpan saat ini:</p>
                                        {{-- ADDED: type="application/pdf" --}}
                                        <iframe src="{{ asset('storage/' . $lembaga->file_super) }}" type="application/pdf" class="w-full h-[600px] border border-gray-300 rounded-lg bg-white shadow-inner"></iframe>
                                        <p class="text-xs text-black-500 mt-2">Untuk mengganti, silakan upload file baru di bawah ini:</p>
                                    </div>
                                @else
                                    <div class="mb-4 text-center text-black-400 italic border border-dashed border-gray-300 p-4 rounded-lg bg-white">Belum ada file SUPER.</div>
                                @endif

                                <div class="relative group text-center">
                                    <input type="file" name="file_super" id="file_super" accept="application/pdf"
                                           class="block w-full text-sm text-black-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 transition cursor-pointer text-center"
                                           onchange="handleFileSelect(this, 'preview_super_edit', 'btn_reset_super_edit')">
                                    
                                    <button type="button" id="btn_reset_super_edit" onclick="resetFile('file_super', 'preview_super_edit', 'btn_reset_super_edit')" 
                                            class="hidden mt-2 text-sm text-red-600 hover:text-red-800 font-bold underline transition">
                                        &times; Batal Upload File Baru
                                    </button>
                                </div>

                                <div class="mt-4">
                                    <iframe id="preview_super_edit" class="hidden w-full h-[600px] border border-gray-300 rounded-lg bg-white shadow-inner" src=""></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. SKAM (BARU) --}}
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm mt-8">
                                <label class="block text-base font-bold text-black-800 mb-3">4. Surat Keterangan Aktif Mengajar (SKAM)</label>
                                
                                @if($lembaga->file_skam)
                                    <div class="mb-4 text-center">
                                        <p class="text-xs text-green-600 font-bold mb-2">✓ File tersimpan saat ini:</p>
                                        <iframe src="{{ asset('storage/' . $lembaga->file_skam) }}" type="application/pdf" class="w-full h-[600px] border border-gray-300 rounded-lg bg-white shadow-inner"></iframe>
                                        <p class="text-xs text-black-500 mt-2">Untuk mengganti, silakan upload file baru di bawah ini:</p>
                                    </div>
                                @else
                                    <div class="mb-4 text-center text-black-400 italic border border-dashed border-gray-300 p-4 rounded-lg bg-white">Belum ada file SKAM.</div>
                                @endif

                                <div class="relative group text-center">
                                    <input type="file" name="file_skam" id="file_skam" accept="application/pdf"
                                           class="block w-full text-sm text-black-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-orange-500 file:text-white hover:file:bg-orange-600 transition cursor-pointer text-center"
                                           onchange="handleFileSelect(this, 'preview_skam_edit', 'btn_reset_skam_edit')">
                                    
                                    <button type="button" id="btn_reset_skam_edit" onclick="resetFile('file_skam', 'preview_skam_edit', 'btn_reset_skam_edit')" 
                                            class="hidden mt-2 text-sm text-red-600 hover:text-red-800 font-bold underline transition">
                                        &times; Batal Upload File Baru
                                    </button>
                                </div>

                                <div class="mt-4">
                                    <iframe id="preview_skam_edit" class="hidden w-full h-[600px] border border-gray-300 rounded-lg bg-white shadow-inner" src=""></iframe>
                                </div>


                            </div>

                        {{-- ================================================================= --}}
                        {{-- 📸 [BARU - FASE 3] PANEL 4 FOTO EDIT DENGAN PREVIEW DATABASE      --}}
                        {{-- ================================================================= --}}
                        <div class="mt-8 bg-white p-6 rounded-xl border border-gray-300 shadow-sm">
                            <h3 class="text-sm font-bold text-black-800 uppercase tracking-wide mb-1">4. Perbarui Dokumentasi Lembaga (Foto Lapangan)</h3>
                            <p class="text-[11px] text-black-500 mb-4">Abaikan jika tidak ingin mengganti foto lama.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                {{-- Kotak A: Profil Lembaga --}}
                                <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                    <label class="text-[11px] font-bold text-black-700 uppercase mb-1 w-full text-center">A. Foto Profil Lembaga</label>
                                    <div class="w-full h-32 bg-gray-200 border border-gray-400 rounded-md mb-2 overflow-hidden flex justify-center items-center relative">
                                        <img id="preview_lembaga" src="{{ $lembaga->foto_lembaga ? asset('storage/' . $lembaga->foto_lembaga) : '#' }}" 
                                             class="{{ $lembaga->foto_lembaga ? '' : 'hidden' }} object-cover w-full h-full absolute inset-0 z-10" />
                                        <span class="text-black-400 text-[11px] z-0">Tidak Ada Foto Terunggah</span>
                                    </div>
                                    <input type="file" name="foto_lembaga" accept="image/*" onchange="previewImageFase3(this, 'preview_lembaga')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-orange-500 file:text-white hover:file:bg-orange-600 cursor-pointer">
                                </div>

                                {{-- Kotak B: Papan Nama --}}
                                <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                    <label class="text-[11px] font-bold text-black-700 uppercase mb-1 w-full text-center">B. Papan Nama / Nambor</label>
                                    <div class="w-full h-32 bg-gray-200 border border-gray-400 rounded-md mb-2 overflow-hidden flex justify-center items-center relative">
                                        <img id="preview_nambor" src="{{ $lembaga->foto_nambor ? asset('storage/' . $lembaga->foto_nambor) : '#' }}" 
                                             class="{{ $lembaga->foto_nambor ? '' : 'hidden' }} object-cover w-full h-full absolute inset-0 z-10" />
                                        <span class="text-black-400 text-[11px] z-0">Tidak Ada Foto Terunggah</span>
                                    </div>
                                    <input type="file" name="foto_nambor" accept="image/*" onchange="previewImageFase3(this, 'preview_nambor')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-orange-500 file:text-white hover:file:bg-orange-600 cursor-pointer">
                                </div>

                                {{-- Kotak C: Gedung --}}
                                <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                    <label class="text-[11px] font-bold text-black-700 uppercase mb-1 w-full text-center">C. Gedung / Bangunan</label>
                                    <div class="w-full h-32 bg-gray-200 border border-gray-400 rounded-md mb-2 overflow-hidden flex justify-center items-center relative">
                                        <img id="preview_bangunan" src="{{ $lembaga->foto_bangunan ? asset('storage/' . $lembaga->foto_bangunan) : '#' }}" 
                                             class="{{ $lembaga->foto_bangunan ? '' : 'hidden' }} object-cover w-full h-full absolute inset-0 z-10" />
                                        <span class="text-black-400 text-[11px] z-0">Tidak Ada Foto Terunggah</span>
                                    </div>
                                    <input type="file" name="foto_bangunan" accept="image/*" onchange="previewImageFase3(this, 'preview_bangunan')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-orange-500 file:text-white hover:file:bg-orange-600 cursor-pointer">
                                </div>

                                {{-- Kotak D: KBM --}}
                                <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                    <label class="text-[11px] font-bold text-black-700 uppercase mb-1 w-full text-center">D. Aktivitas Belajar (KBM)</label>
                                    <div class="w-full h-32 bg-gray-200 border border-gray-400 rounded-md mb-2 overflow-hidden flex justify-center items-center relative">
                                        <img id="preview_kbm" src="{{ $lembaga->foto_kbm ? asset('storage/' . $lembaga->foto_kbm) : '#' }}" 
                                             class="{{ $lembaga->foto_kbm ? '' : 'hidden' }} object-cover w-full h-full absolute inset-0 z-10" />
                                        <span class="text-black-400 text-[11px] z-0">Tidak Ada Foto Terunggah</span>
                                    </div>
                                    <input type="file" name="foto_kbm" accept="image/*" onchange="previewImageFase3(this, 'preview_kbm')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-orange-500 file:text-white hover:file:bg-orange-600 cursor-pointer">
                                </div>

                            </div>
                        </div>
                            
                            <div class="mt-8 bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Catatan Tambahan / Keterangan</label>
                                
                                
                                <textarea name="keterangan" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm text-sm text-center uppercase" placeholder="TULIS CATATAN JIKA ADA..." oninput="this.value = this.value.toUpperCase()">{{ old('keterangan', $lembaga->keterangan) }}</textarea>
                            </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('lembaga.index') }}" class="px-5 py-2.5 text-sm font-bold text-black-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Simpan Perubahan
                        </button>
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

        // 2. PREVIEW PDF
        function handleFileSelect(input, iframeId, btnId) {
            const iframe = document.getElementById(iframeId);
            const btnReset = document.getElementById(btnId);
            if (input.files && input.files[0]) {
                if(input.files[0].type !== 'application/pdf'){
                    alert("Mohon upload file berformat PDF!");
                    input.value = ""; 
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    iframe.src = e.target.result;
                    iframe.classList.remove('hidden'); 
                    btnReset.classList.remove('hidden'); 
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function resetFile(inputId, iframeId, btnId) {
            const input = document.getElementById(inputId);
            const iframe = document.getElementById(iframeId);
            const btnReset = document.getElementById(btnId);
            input.value = ""; 
            iframe.src = "";  
            iframe.classList.add('hidden'); 
            btnReset.classList.add('hidden'); 
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
</x-app-layout>