<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            {{ __('Data Guru ' . $type) }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-full mx-auto px-1 sm:px-1 lg:px-1">
            
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <h3 class="text-sm font-medium text-red-800">Gagal Menyimpan! Periksa hal berikut:</h3>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex justify-between items-center mb-1">
                <div>
                    <h1 class="text-2xl font-bold text-black-800">TAMBAH DATA GURU {{ $type }}</h1>
                    <p class="text-sm text-black-500 mt-1">Lengkapi data identitas.</p>
                </div>
                @php
                    $backRoute = route('guru.index');
                    if($type == 'MADIN') $backRoute = route('guru.madin');
                    if($type == 'TPQ')   $backRoute = route('guru.tpq');
                    if($type == 'PONPES')$backRoute = route('guru.ponpes');
                @endphp
                <a href="{{ $backRoute }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-600 rounded-md font-semibold text-xs text-black-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    &larr; Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-600">
                <form action="{{ route('guru.store') }}" method="POST" enctype="multipart/form-data" class="p-8" id="guruForm">
                    @csrf
                    <input type="hidden" name="jenis_guru" value="{{ $type }}">

                    {{-- SECTION A: DATA PRIBADI --}}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-600">
                            <span class="bg-blue-100 text-blue-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">A</span>
                            <h3 class="text-lg font-bold text-black-800 uppercase">Data Diri Guru</h3>
                        </div>



                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Nama Lengkap (Sesuai KTP) <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm uppercase" required placeholder="CONTOH: AHMAD SYAFI'I" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">NIK (16 Digit) <span class="text-red-500">*</span></label>
                                <input type="text" name="nik" maxlength="16" value="{{ old('nik') }}" class="w-full border {{ $errors->has('nik') ? 'border-red-500 bg-red-50' : 'border-gray-600' }} rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required placeholder="16 digit angka NIK">
                                @error('nik')
                                    <span class="text-[10px] text-red-600 font-bold block mt-1 leading-tight">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="jenis_kelamin" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>LAKI-LAKI</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>PEREMPUAN</option>
                                </select>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Status Guru <span class="text-red-500">*</span></label>
                                <select name="status_kepegawaian" id="status_kepegawaian" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" onchange="checkInsentifEligibility()">
                                    <option value="Non-ASN">NON-ASN</option>
                                    <option value="PNS">PNS</option>
                                    <option value="PPPK">PPPK (P3K)</option>
                                </select>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Status Sertifikasi <span class="text-red-500">*</span></label>
                                <select name="status_sertifikasi" id="status_sertifikasi" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" onchange="checkInsentifEligibility()">
                                    <option value="Belum">BELUM SERTIFIKASI</option>
                                    <option value="Sertifikasi">SUDAH SERTIFIKASI</option>
                                    <option value="Inpassing">SUDAH INPASING</option>
                                </select>
                            </div>

                            <div class="md:col-span-2 bg-yellow-50 px-1 py-1 rounded-md border border-yellow-200 transition-colors flex flex-col justify-center" id="box_insentif">
                                <label class="block text-[10px] font-bold text-black-800 mb-0.5">Apakah Menerima Insentif? <span class="text-red-500">*</span></label>
                                <select name="penerima_insentif" id="penerima_insentif" class="w-full border-yellow-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-700 shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                    <option value="0" {{ old('penerima_insentif') == '0' ? 'selected' : '' }}>❌ TIDAK BERHAK MENERIMA INSENTIF</option>
                                    <option value="1" {{ old('penerima_insentif') == '1' ? 'selected' : '' }}>✅ YA, BERHAK MENERIMA INSENTIF</option>
                                </select>
                                <p class="text-[9px] text-black-500 mt-1 leading-none" id="msg_insentif">*Pilih "YA" jika guru ini berhak menerima insentif.</p>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm uppercase" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Nama Ibu Kandung</label>
                                <input type="text" name="nama_ibu_kandung" value="{{ old('nama_ibu_kandung') }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm uppercase" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Agama</label>
                                <select name="agama" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="Islam">ISLAM</option>
                                    <option value="Kristen">KRISTEN</option>
                                    <option value="Katholik">KATHOLIK</option>
                                    <option value="Hindu">HINDU</option>
                                    <option value="Budha">BUDHA</option>
                                    <option value="Konghucu">KONGHUCU</option>
                                </select>
                            </div>

                            {{-- [BARU/FIX] Dropdown Desa untuk KTP --}}
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Desa / Kelurahan (KTP) <span class="text-red-500">*</span></label>
                                <select name="desa_ktp" id="desaKtpSelect" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                                    <option value="">-- Pilih Kecamatan Dulu --</option>
                                </select>

                                
                                <div id="allDesasDataKtp" class="hidden">
                                    @foreach($semua_desa_kediri as $d)
                                        <div data-kecamatan-id="{{ $d->kecamatan_id }}" data-nama="{{ $d->nama_desa }}"></div>
                                    @endforeach
                                </div>
                            </div>


                            {{-- [BARU/FIX] Dropdown Kecamatan untuk KTP (Bebas se-Kabupaten) --}}
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Kecamatan (KTP) <span class="text-red-500">*</span></label>
                                <select name="kecamatan_ktp" id="kecamatanKtpSelect" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($semua_kecamatan_kediri as $kec)
                                        <option value="{{ $kec->nama_kecamatan }}" data-id="{{ $kec->id }}" {{ old('kecamatan_ktp') == $kec->nama_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Kabupaten</label>
                                <input type="text" name="kabupaten" value="KEDIRI" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] font-bold text-xs bg-gray-100 shadow-sm" readonly>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Nomor HP</label>
                                <input type="number" name="no_hp" value="{{ old('no_hp') }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>
                            

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Alamat Guru (Sesuai KTP) <span class="text-red-500">*</span></label>
                                <input type="text" name="alamat_ktp" value="{{ old('alamat_ktp') }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm uppercase" required oninput="this.value = this.value.toUpperCase()">
                            </div>                         

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Pekerjaan Utama <span class="text-red-500">*</span></label>
                                <input type="text" name="pekerjaan_utama" id="pekerjaan_utama" value="{{ old('pekerjaan_utama') }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm uppercase" placeholder="CONTOH: PETANI / GURU / PNS" required oninput="this.value = this.value.toUpperCase(); autoDetectStatusPegawai(this.value)">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Nomor Rekening BANK JATIM</label>
                                <input type="number" name="nomor_rekening" value="{{ old('nomor_rekening') }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>
                            

                        </div>



                    </div>

                    {{-- SECTION B: KELEMBAGAAN & ALAMAT --}}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-600">
                            <span class="bg-green-100 text-green-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">B</span>
                            <h3 class="text-lg font-bold text-black-800 uppercase">Lembaga Tempat Mengajar</h3>
                        </div>




                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Jenis Lembaga</label>
                                <input type="text" value="{{ $type }}" class="w-full bg-gray-100 border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 cursor-not-allowed shadow-sm" readonly>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Kecamatan Lembaga <span class="text-red-500">*</span></label>
                                <select name="kecamatan" id="kecamatanSelect" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach($kecamatans as $kec)
                                        <option value="{{ $kec->nama_kecamatan }}" data-id="{{ $kec->id }}" {{ old('kecamatan') == $kec->nama_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Desa / Kelurahan Lembaga <span class="text-red-500">*</span></label>
                                <select name="desa" id="desaSelect" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                                    <option value="">-- Pilih Kecamatan Dulu --</option>
                                </select>
                                <div id="allDesasData" class="hidden">
                                    @foreach($desas as $d)
                                        <div data-id="{{ $d->id }}" data-kecamatan-id="{{ $d->kecamatan_id }}" data-nama="{{ $d->nama_desa }}"></div>
                                    @endforeach
                                </div>
                            </div>

                            

                            

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-green-700 uppercase tracking-wider mb-1">Lembaga Tempat Mengajar <span class="text-red-500">*</span></label>
                                <select name="lembaga_id" id="lembagaSelect" class="w-full border-green-500 border-2 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 bg-green-50 shadow-sm focus:ring-green-500" required>
                                    <option value="" disabled selected>-- Pilih Desa Terlebih Dahulu --</option>
                                </select>
                                <div id="allLembagasData" class="hidden">
                                    @foreach($lembagas as $l)
                                        <div data-id="{{ $l->id }}" data-desa-id="{{ $l->desa_id }}" data-nama="{{ $l->nama_lembaga }}"></div>
                                    @endforeach
                                </div>
                            </div>



                        </div>




                    </div>

                    {{-- SECTION C: UPLOAD DOKUMEN --}}
                    <div class="mb-1">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-600">
                            <span class="bg-purple-100 text-purple-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">C</span>
                            <h3 class="text-lg font-bold text-black-800 uppercase">Upload Dokumen</h3>
                        </div>




                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            {{-- Kotak KTP --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-600 shadow-sm flex flex-col items-start">
                                <label class="block text-xs font-bold text-black-800 mb-2">1. Scan KTP Asli *</label>
                                <div class="w-full text-left">
                                    <input type="file" name="file_ktp" id="file_ktp" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer" onchange="handleFileSelect(this, 'preview_ktp', 'btn_reset_ktp')">
                                    <button type="button" id="btn_reset_ktp" onclick="resetFile('file_ktp', 'preview_ktp', 'btn_reset_ktp')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload</button>
                                </div>
                                <div class="mt-2 w-full"><iframe id="preview_ktp" type="application/pdf" class="hidden w-full h-[250px] border border-gray-600 rounded bg-white"></iframe></div>
                            </div>

                            {{-- Kotak KK --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-600 shadow-sm flex flex-col items-start">
                                <label class="block text-xs font-bold text-black-800 mb-2">2. Scan Kartu Keluarga *</label>
                                <div class="w-full text-left">
                                    <input type="file" name="file_kk" id="file_kk" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-green-600 file:text-white hover:file:bg-green-700 transition cursor-pointer" onchange="handleFileSelect(this, 'preview_kk', 'btn_reset_kk')">
                                    <button type="button" id="btn_reset_kk" onclick="resetFile('file_kk', 'preview_kk', 'btn_reset_kk')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload</button>
                                </div>
                                <div class="mt-2 w-full"><iframe id="preview_kk" type="application/pdf" class="hidden w-full h-[250px] border border-gray-600 rounded bg-white"></iframe></div>
                            </div>

                            {{-- Kotak Rekening --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-600 shadow-sm flex flex-col items-start">
                                <label class="block text-xs font-bold text-black-800 mb-2">3. Scan Buku Rekening</label>
                                <div class="w-full text-left">
                                    <input type="file" name="file_bukurekening" id="file_bukurekening" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 transition cursor-pointer" onchange="handleFileSelect(this, 'preview_rekening', 'btn_reset_rekening')">
                                    <button type="button" id="btn_reset_rekening" onclick="resetFile('file_bukurekening', 'preview_rekening', 'btn_reset_rekening')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload</button>
                                </div>
                                <div class="mt-2 w-full"><iframe id="preview_rekening" type="application/pdf" class="hidden w-full h-[250px] border border-gray-600 rounded bg-white"></iframe></div>
                            </div>

                        </div>



                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-600">
                        <a href="{{ $backRoute }}" class="px-5 py-2.5 text-sm font-bold text-black-600 bg-white border border-gray-600 rounded-lg hover:bg-gray-50 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-green-600 rounded-lg shadow-md hover:bg-green-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Data Guru
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // --- 0. AUTO-DETECT STATUS GURU DARI KETIKAN PEKERJAAN UTAMA ---
        function autoDetectStatusPegawai(val) {
            const statusSelect = document.getElementById('status_kepegawaian');
            if (!statusSelect) return;
            const text = val.trim().toUpperCase();

            if (text.includes('PPPK') || text.includes('P3K')) {
                statusSelect.value = 'PPPK';
            } else if (text.includes('PNS') || text.includes('ASN')) {
                statusSelect.value = 'PNS';
            } else {
                statusSelect.value = 'Non-ASN';
            }

            // Jalankan validasi penguncian insentif otomatis
            checkInsentifEligibility();
        }

        // --- 1. LOGIKA VALIDASI INSENTIF (PNS=TIDAK, NON-ASN=YA) ---
        function checkInsentifEligibility() {
            const pegawai = document.getElementById('status_kepegawaian').value.toUpperCase();
            const insentifSelect = document.getElementById('penerima_insentif');
            const insentifBox = document.getElementById('box_insentif');
            const msg = document.getElementById('msg_insentif');

            // Jika PNS atau PPPK -> KUNCI "TIDAK" (0)
            if (pegawai === 'PNS' || pegawai === 'PPPK') {
                insentifSelect.value = '0';
                insentifSelect.style.pointerEvents = 'none'; 
                insentifSelect.style.backgroundColor = '#f3f4f6'; 
                insentifSelect.style.borderColor = '#d1d5db'; 
                insentifBox.className = 'md:col-span-2 p-4 rounded-lg border transition-colors bg-red-50 border-red-200';
                msg.innerHTML = '<span class="text-red-600 font-bold">🚫 Status PNS/PPPK TIDAK BERHAK menerima insentif.</span>';
            } 
            // Jika NON-ASN (Swasta) -> KUNCI "YA" (1)
            else if (pegawai === 'NON-ASN') {
                insentifSelect.value = '1';
                insentifSelect.style.pointerEvents = 'none'; 
                insentifSelect.style.backgroundColor = '#f3f4f6'; 
                insentifSelect.style.borderColor = '#d1d5db'; 
                insentifBox.className = 'md:col-span-2 p-4 rounded-lg border transition-colors bg-emerald-50 border-emerald-200';
                msg.innerHTML = '<span class="text-emerald-600 font-bold">✅ Guru Swasta / Non-ASN OTOMATIS BERHAK menerima insentif.</span>';
            }
            // Sisanya (Jaga-jaga)
            else {
                insentifSelect.style.pointerEvents = 'auto';
                insentifSelect.style.backgroundColor = 'white';
                insentifSelect.style.borderColor = '#facc15';
                insentifBox.className = 'md:col-span-2 p-4 rounded-lg border transition-colors bg-yellow-50 border-yellow-200';
                msg.innerHTML = '*Pilih status penerimaan insentif.';
            }
        }

        // --- 2. LOGIKA DROPDOWN BERTINGKAT (Kecamatan -> Desa -> Lembaga) ---
        document.addEventListener('DOMContentLoaded', function() {
            const kecamatanSelect = document.getElementById('kecamatanSelect');
            const desaSelect = document.getElementById('desaSelect');
            const lembagaSelect = document.getElementById('lembagaSelect');
            
            // Ambil data master yang disembunyikan
            const allDesas = Array.from(document.querySelectorAll('#allDesasData div')).map(div => ({ 
                id: div.getAttribute('data-id'),
                kecamatan_id: div.getAttribute('data-kecamatan-id'), 
                nama: div.getAttribute('data-nama') 
            }));
            const allLembagas = Array.from(document.querySelectorAll('#allLembagasData div')).map(div => ({
                id: div.getAttribute('data-id'),
                desa_id: div.getAttribute('data-desa-id'),
                nama: div.getAttribute('data-nama')
            }));

            // Variabel Old (jika terjadi error validasi)
            const oldKecamatan = "{{ old('kecamatan') }}";
            const oldDesa = "{{ old('desa') }}";
            const oldLembagaId = "{{ old('lembaga_id') }}";

            // Fungsi Isi Desa berdasarkan Kecamatan
            function populateDesa(selectedKecName) {
                const selectedOption = Array.from(kecamatanSelect.options).find(opt => opt.value === selectedKecName);
                const selectedKecId = selectedOption ? selectedOption.getAttribute('data-id') : null;
                
                desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>'; 
                lembagaSelect.innerHTML = '<option value="" disabled selected>-- Pilih Desa Terlebih Dahulu --</option>';

                if(selectedKecId) {
                    const filteredDesas = allDesas.filter(d => d.kecamatan_id == selectedKecId);
                    filteredDesas.forEach(d => {
                        const option = document.createElement('option');
                        option.value = d.nama; 
                        option.text = d.nama;
                        option.setAttribute('data-id', d.id); // Simpan ID Desa untuk filter lembaga
                        if(d.nama === oldDesa) option.selected = true;
                        desaSelect.appendChild(option);
                    });
                    
                    // Jika ada old desa, langsung isi lembaganya juga
                    if(oldDesa) populateLembaga(oldDesa);
                }
            }

            // Fungsi Isi Lembaga berdasarkan Desa
            function populateLembaga(selectedDesaName) {
                const selectedOption = Array.from(desaSelect.options).find(opt => opt.value === selectedDesaName);
                const selectedDesaId = selectedOption ? selectedOption.getAttribute('data-id') : null;

                lembagaSelect.innerHTML = '<option value="" disabled selected>-- Pilih Lembaga --</option>';

                if(selectedDesaId) {
                    // Filter lembaga yang desa_id nya cocok
                    const filteredLembagas = allLembagas.filter(l => l.desa_id == selectedDesaId);
                    
                    if(filteredLembagas.length > 0) {
                        filteredLembagas.forEach(l => {
                            const option = document.createElement('option');
                            option.value = l.id; 
                            option.text = l.nama;
                            if(l.id == oldLembagaId) option.selected = true;
                            lembagaSelect.appendChild(option);
                        });
                    } else {
                        lembagaSelect.innerHTML = '<option value="" disabled selected>-- Tidak ada lembaga di desa ini --</option>';
                    }
                }
            }

            // Event Listeners
            kecamatanSelect.addEventListener('change', function() { populateDesa(this.value); });
            desaSelect.addEventListener('change', function() { populateLembaga(this.value); });
            
            // Run on Load (untuk handling old values)
            if(oldKecamatan) { populateDesa(oldKecamatan); }
            checkInsentifEligibility();

            // ====================================================================
            // 🚀 [BARU] LOGIKA DROPDOWN KECAMATAN -> DESA UNTUK ALAMAT DOMISILI KTP
            // ====================================================================
            const kecamatanKtpSelect = document.getElementById('kecamatanKtpSelect');
            const desaKtpSelect = document.getElementById('desaKtpSelect');
            
            if (kecamatanKtpSelect && desaKtpSelect) {
                const allDesasKtp = Array.from(document.querySelectorAll('#allDesasDataKtp div')).map(div => ({ 
                    kecamatan_id: div.getAttribute('data-kecamatan-id'), 
                    nama: div.getAttribute('data-nama') 
                }));
                const oldDesaKtp = "{{ old('desa_ktp') }}"; // Mengambil old input desa KTP yang benar

                function populateDesaKtp() {
                    const selectedOption = kecamatanKtpSelect.options[kecamatanKtpSelect.selectedIndex];
                    const selectedKecId = selectedOption ? selectedOption.getAttribute('data-id') : null;
                    
                    desaKtpSelect.innerHTML = '<option value="">-- Pilih Desa --</option>'; 
                    
                    if(selectedKecId) {
                        const filteredDesas = allDesasKtp.filter(d => d.kecamatan_id == selectedKecId);
                        filteredDesas.forEach(d => {
                            const option = document.createElement('option');
                            option.value = d.nama; 
                            option.text = d.nama;
                            
                            if(d.nama.toUpperCase() === oldDesaKtp.toUpperCase()) {
                                option.selected = true; 
                            }
                            
                            desaKtpSelect.appendChild(option);
                        });
                    }
                }
                
                kecamatanKtpSelect.addEventListener('change', populateDesaKtp);
                populateDesaKtp(); // Jalankan otomatis saat halaman pertama dimuat
            }
            // ====================================================================
        });

        // --- 3. File Upload (Tetap Sama) ---
        function handleFileSelect(input, iframeId, btnId) {
            const iframe = document.getElementById(iframeId); const btnReset = document.getElementById(btnId);
            if (input.files && input.files[0]) {
                if(input.files[0].type !== 'application/pdf'){ alert("Mohon upload PDF!"); input.value = ""; return; }
                const reader = new FileReader();
                reader.onload = function(e) { iframe.src = e.target.result; iframe.classList.remove('hidden'); btnReset.classList.remove('hidden'); }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function resetFile(inputId, iframeId, btnId) {
            const input = document.getElementById(inputId); const iframe = document.getElementById(iframeId); const btnReset = document.getElementById(btnId);
            input.value = ""; iframe.src = ""; iframe.classList.add('hidden'); btnReset.classList.add('hidden');
        }
    </script>
</x-app-layout>