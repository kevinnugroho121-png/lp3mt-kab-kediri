<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            {{ __('Master Data Lembaga') }}
        </h2>
    </x-slot>


    <div class="py-2">
        <div class="max-w-full mx-auto px-1 sm:px-1 lg:px-1">
            
            {{-- HEADER NAVIGASI --}}
            <div class="flex justify-between items-center mb-2">
                <div>
                    <h1 class="text-2xl font-bold text-black-800 uppercase">TAMBAH LEMBAGA BARU</h1>


                    <p class="text-sm text-black-500 mt-1">Lengkapi data identitas, statistik, dan dokumen legalitas.</p>
                </div>
                <a href="{{ route('lembaga.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-black-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    &larr; Kembali
                </a>
            </div>

            {{-- FORM CARD --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                
                {{-- ENCTYPE WAJIB ADA --}}
                <form action="{{ route('lembaga.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf

                    {{-- SECTION A: IDENTITAS & LOKASI --}}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                            <span class="bg-blue-100 text-blue-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">A</span>
                            <h3 class="text-base font-bold text-black-800">Identitas & Lokasi</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 px-1">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Nama Lembaga <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga') }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm uppercase" placeholder="CONTOH: TPQ AL-HIDAYAH" required oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Jenis Lembaga <span class="text-red-500">*</span></label>
                                <select name="jenis_lembaga" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                                    <option value="TPQ">TPQ</option><option value="MADIN">MADIN</option><option value="PONPES">PONPES</option>
                                </select>
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Ormas Afiliasi</label>
                                <select name="ormas" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                                    <option value="">- Tidak Ada -</option><option value="NU">NU</option><option value="Muhammadiyah">Muhammadiyah</option><option value="LDII">LDII</option>
                                </select>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Kecamatan <span class="text-red-500">*</span></label>
                                @if(Auth::user()->role == 'korcam')
                                    <input type="text" value="{{ Auth::user()->kecamatan->nama_kecamatan }}" class="w-full bg-gray-100 border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-gray-500 cursor-not-allowed shadow-sm" readonly>
                                    <input type="hidden" name="kecamatan_id" value="{{ Auth::user()->kecamatan_id }}">
                                @else
                                    <select name="kecamatan_id" id="kecamatanSelect" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach($kecamatans as $kec) <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option> @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Desa / Kelurahan <span class="text-red-500">*</span></label>
                                <select name="desa_id" id="desaSelect" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($desas as $desa) <option value="{{ $desa->id }}" data-kecamatan="{{ $desa->kecamatan_id }}">{{ $desa->nama_desa }}</option> @endforeach
                                </select>
                            </div>
                            {{-- NSBQ dibuat melebar 2 kolom agar seimbang --}}
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Nomor Statistik (NSBQ)</label>
                                <input type="text" name="nsbq" value="{{ old('nsbq') }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm uppercase" placeholder="OPSIONAL" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            {{-- Alamat Lengkap (2 Kolom) --}}
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Alamat Lengkap</label>
                                <input type="text" name="alamat" value="{{ old('alamat') }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm uppercase" placeholder="DUSUN / JL / RT RW (OPSIONAL)" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            {{-- [BARU] Input Link / Titik Koordinat Google Maps (2 Kolom) --}}
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Titik Koordinat / Link Google Maps</label>
                                <input type="text" name="link_gmaps" value="{{ old('link_gmaps') }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm" placeholder="CONTOH: https://maps.app.goo.gl/... ATAU -7.8123, 112.0123 (OPSIONAL)">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION B: STATISTIK & KONTAK --}}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                            <span class="bg-green-100 text-green-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">B</span>
                            <h3 class="text-base font-bold text-black-800">Statistik & Kontak</h3>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 px-1">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Jml Santri</label>
                                <input type="number" name="jumlah_santri" value="0" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 bg-gray-50 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Total Guru</label>
                                <input type="number" name="jumlah_guru" value="0" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 bg-gray-50 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Guru Insentif</label>
                                <input type="number" name="penerima_insentif" value="0" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Non-Insentif</label>
                                <input type="number" name="belum_menerima_insentif" value="0" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Jml PNS</label>
                                <input type="number" name="jumlah_pns" value="0" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Jml PPPK</label>
                                <input type="number" name="jumlah_pppk" value="0" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Sertifikasi</label>
                                <input type="number" name="jumlah_sertifikasi" value="0" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Status Operasional</label>
                                <select name="status" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                                    <option value="AKTIF">AKTIF</option><option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Kepala Lembaga</label>
                                <input type="text" name="kepala_lembaga" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm uppercase" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">No. Telp / WA</label>
                                <input type="number" name="no_telp" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm">
                            </div>
                        </div>
                    </div>



                    {{-- SECTION C: DOKUMEN & DOKUMENTASI --}}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                            <span class="bg-purple-100 text-purple-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">C</span>
                            <h3 class="text-base font-bold text-black-800 ">Upload Dokumen & Foto</h3>
                        </div>

                        {{-- DOKUMEN PDF (GRID 2x2) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            
                            {{-- 1. IJOP --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-400 shadow-sm flex flex-col">
                                <label class="block text-xs font-bold text-black-800 mb-2">1. Scan IJOP Asli</label>
                                <div class="w-full text-left mb-3">
                                    <input type="file" name="file_ijop" id="file_ijop" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer transition" onchange="handleFileSelect(this, 'preview_ijop', 'btn_reset_ijop')">
                                    <button type="button" id="btn_reset_ijop" onclick="resetFile('file_ijop', 'preview_ijop', 'btn_reset_ijop')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload</button>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-600 uppercase">Tgl Terbit</label>
                                        <input type="date" name="masa_berlaku_ijop" id="tgl_ijop" class="w-full border border-gray-400 rounded-md h-[28px] text-[10px] font-bold px-2">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-600 uppercase">Fisik IJOP</label>
                                        <input type="text" name="ijop" value="ADA" class="w-full border border-gray-400 rounded-md h-[28px] text-[10px] font-bold px-2 uppercase" oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                    <div class="col-span-2 hidden bg-green-100 text-green-800 text-[10px] py-1.5 rounded text-center font-bold" id="info_masa_berlaku"></div>
                                </div>
                                <input type="hidden" name="status_ijop" value="Pending">
                                <iframe id="preview_ijop" class="hidden w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                            </div>

                            {{-- 2. SKD --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-400 shadow-sm flex flex-col">
                                <label class="block text-xs font-bold text-black-800 mb-2">2. Scan SKD (Opsional)</label>
                                <div class="w-full text-left mb-3">
                                    <input type="file" name="file_skd" id="file_skd" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-gray-600 file:text-white hover:file:bg-gray-700 cursor-pointer transition" onchange="handleFileSelect(this, 'preview_skd', 'btn_reset_skd')">
                                    <button type="button" id="btn_reset_skd" onclick="resetFile('file_skd', 'preview_skd', 'btn_reset_skd')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload</button>
                                </div>
                                <iframe id="preview_skd" class="hidden w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                            </div>

                            {{-- 3. SPTJM --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-400 shadow-sm flex flex-col">
                                <label class="block text-xs font-bold text-black-800 mb-2">3. Scan SPTJM Mutlak</label>
                                <div class="w-full text-left mb-3">
                                    <input type="file" name="file_super" id="file_super" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 cursor-pointer transition" onchange="handleFileSelect(this, 'preview_super', 'btn_reset_super')">
                                    <button type="button" id="btn_reset_super" onclick="resetFile('file_super', 'preview_super', 'btn_reset_super')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload</button>
                                </div>
                                <input type="hidden" name="status_super" value="Pending">
                                <iframe id="preview_super" class="hidden w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                            </div>

                            {{-- 4. SKAM --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-400 shadow-sm flex flex-col">
                                <label class="block text-xs font-bold text-black-800 mb-2">4. Scan SKAM</label>
                                <div class="w-full text-left mb-3">
                                    <input type="file" name="file_skam" id="file_skam" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-teal-600 file:text-white hover:file:bg-teal-700 cursor-pointer transition" onchange="handleFileSelect(this, 'preview_skam', 'btn_reset_skam')">
                                    <button type="button" id="btn_reset_skam" onclick="resetFile('file_skam', 'preview_skam', 'btn_reset_skam')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload</button>
                                </div>
                                <input type="hidden" name="status_skam" value="Pending">
                                <iframe id="preview_skam" class="hidden w-full h-[250px] border border-gray-300 rounded bg-white mt-auto"></iframe>
                            </div>

                        </div>

                        {{-- FOTO (GRID 2x2) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                <label class="text-xs font-bold text-black-700 uppercase mb-2">A. Profil Lembaga</label>
                                <div class="w-full h-40 bg-gray-200 border border-dashed border-gray-400 rounded-md mb-3 overflow-hidden flex justify-center items-center relative">
                                    <img id="preview_lembaga" src="#" class="hidden object-cover w-full h-full absolute inset-0 z-10" />
                                    <span class="text-black-400 text-[11px] z-0">Belum Ada Gambar</span>
                                </div>
                                <input type="file" name="foto_lembaga" accept="image/*" onchange="previewImageFase3(this, 'preview_lembaga')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer transition">
                            </div>
                            
                            <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                <label class="text-xs font-bold text-black-700 uppercase mb-2">B. Papan Nama / Nambor</label>
                                <div class="w-full h-40 bg-gray-200 border border-dashed border-gray-400 rounded-md mb-3 overflow-hidden flex justify-center items-center relative">
                                    <img id="preview_nambor" src="#" class="hidden object-cover w-full h-full absolute inset-0 z-10" />
                                    <span class="text-black-400 text-[11px] z-0">Belum Ada Gambar</span>
                                </div>
                                <input type="file" name="foto_nambor" accept="image/*" onchange="previewImageFase3(this, 'preview_nambor')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer transition">
                            </div>
                            
                            <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                <label class="text-xs font-bold text-black-700 uppercase mb-2">C. Gedung / Bangunan</label>
                                <div class="w-full h-40 bg-gray-200 border border-dashed border-gray-400 rounded-md mb-3 overflow-hidden flex justify-center items-center relative">
                                    <img id="preview_bangunan" src="#" class="hidden object-cover w-full h-full absolute inset-0 z-10" />
                                    <span class="text-black-400 text-[11px] z-0">Belum Ada Gambar</span>
                                </div>
                                <input type="file" name="foto_bangunan" accept="image/*" onchange="previewImageFase3(this, 'preview_bangunan')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer transition">
                            </div>
                            
                            <div class="border border-gray-300 rounded-lg p-3 bg-gray-50 flex flex-col items-center">
                                <label class="text-xs font-bold text-black-700 uppercase mb-2">D. Aktivitas KBM</label>
                                <div class="w-full h-40 bg-gray-200 border border-dashed border-gray-400 rounded-md mb-3 overflow-hidden flex justify-center items-center relative">
                                    <img id="preview_kbm" src="#" class="hidden object-cover w-full h-full absolute inset-0 z-10" />
                                    <span class="text-black-400 text-[11px] z-0">Belum Ada Gambar</span>
                                </div>
                                <input type="file" name="foto_kbm" accept="image/*" onchange="previewImageFase3(this, 'preview_kbm')" class="text-[10px] w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer transition">
                            </div>
                        </div>

                    </div>

                    {{-- FOOTER ACTION DENGAN CATATAN --}}
                    <div class="mt-4 bg-gray-50 px-4 py-3 rounded-lg shadow-sm border border-gray-400 flex flex-col md:flex-row gap-4 items-end justify-between">
                        <div class="flex-grow w-full md:w-1/2">
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Catatan Tambahan (Opsional)</label>
                            <input type="text" name="keterangan" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 shadow-sm focus:border-blue-500 uppercase" placeholder="TULIS JIKA ADA..." oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="flex gap-2 w-full md:w-auto">
                            <a href="{{ route('lembaga.index') }}" class="px-5 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-400 rounded-md hover:bg-gray-100 transition flex items-center">Batal</a>
                            <button type="submit" class="px-6 py-1.5 text-xs font-bold text-white bg-green-600 rounded-md shadow-sm hover:bg-green-700 transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                SIMPAN DATA
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT: LOGIKA UI/UX --}}
    <script>
        // 1. FILTER DESA (Dependent Dropdown)
        document.addEventListener('DOMContentLoaded', function() {
            const kecamatanSelect = document.getElementById('kecamatanSelect');
            const desaSelect = document.getElementById('desaSelect');
            
            if(kecamatanSelect && desaSelect){
                const allDesaOptions = Array.from(desaSelect.querySelectorAll('option'));
                const defaultOption = desaSelect.querySelector('option[value=""]');
                desaSelect.innerHTML = '';
                desaSelect.appendChild(defaultOption);

                kecamatanSelect.addEventListener('change', function() {
                    const selectedKecId = this.value;
                    desaSelect.innerHTML = '';
                    desaSelect.appendChild(defaultOption);
                    if(selectedKecId) {
                        const filteredDesas = allDesaOptions.filter(option => option.dataset.kecamatan === selectedKecId);
                        filteredDesas.forEach(option => desaSelect.appendChild(option));
                        defaultOption.text = "-- Pilih Desa --";
                    } else {
                        defaultOption.text = "-- Pilih Kecamatan Dulu --";
                    }
                });
            }

            // 2. HITUNG MASA BERLAKU IJOP (5 TAHUN)
            const tglIjopInput = document.getElementById('tgl_ijop');
            const infoText = document.getElementById('info_masa_berlaku');

            if(tglIjopInput){
                tglIjopInput.addEventListener('change', function() {
                    const startDate = new Date(this.value);
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
                });
            }
        });

        // 3. PREVIEW & RESET PDF
        function handleFileSelect(input, iframeId, btnId) {
            const iframe = document.getElementById(iframeId);
            const btnReset = document.getElementById(btnId);
            
            if (input.files && input.files[0]) {
                // Cek apakah PDF
                if(input.files[0].type !== 'application/pdf'){
                    alert("Mohon upload file berformat PDF!");
                    input.value = ""; // Reset input
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    iframe.src = e.target.result;
                    iframe.classList.remove('hidden'); // Munculkan Iframe
                    btnReset.classList.remove('hidden'); // Munculkan Tombol Hapus
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function resetFile(inputId, iframeId, btnId) {
            const input = document.getElementById(inputId);
            const iframe = document.getElementById(iframeId);
            const btnReset = document.getElementById(btnId);

            input.value = ""; // Kosongkan Input File
            iframe.src = "";  // Kosongkan Preview
            iframe.classList.add('hidden'); // Sembunyikan Iframe
            btnReset.classList.add('hidden'); // Sembunyikan Tombol Hapus
        }


        function previewImageFase3(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            
            if (file) {
                // 1. Validasi Ukuran (Maksimal 1 MB = 1.048.576 Bytes)
                if (file.size > 1048576) {
                    alert("GAGAL! Ukuran gambar [" + file.name + "] terlalu besar.\n\nMaksimal hanya 1 MB. Silakan kompres gambar Anda terlebih dahulu.");
                    input.value = ""; // Langsung tolak dan hapus file dari input
                    return;
                }

                // 2. Validasi Format (Wajib Image, tolak PDF)
                if (!file.type.startsWith('image/')) {
                    alert("GAGAL! File yang dipilih bukan gambar.\n\nSilakan pilih file berformat JPG, JPEG, atau PNG.");
                    input.value = ""; // Langsung tolak
                    return;
                }

                // 3. Jika Lolos Filter, Tampilkan Preview
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