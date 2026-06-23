<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            Edit Data Guru
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-full mx-auto px-1 sm:px-1 lg:px-1">
            
            {{-- HEADER: Judul & Tombol Kembali --}}
            <div class="flex justify-between items-center mb-1">
                <div>
                    <h1 class="text-2xl font-bold text-black-800 uppercase">EDIT DATA GURU: {{ $guru->nama_lengkap }}</h1>
                    <p class="text-sm text-black-500 mt-1">Perbarui data identitas atau upload ulang dokumen.</p>
                </div>


                
                {{-- LOGIKA TOMBOL KEMBALI PINTAR --}}
                @php
                    $backRoute = route('guru.index');
                    if($guru->jenis_guru == 'MADIN') $backRoute = route('guru.madin');
                    if($guru->jenis_guru == 'TPQ')   $backRoute = route('guru.tpq');
                    if($guru->jenis_guru == 'PONPES')$backRoute = route('guru.ponpes');
                @endphp
                <a href="{{ $backRoute }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-black-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    &larr; Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <form action="{{ route('guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')
                    


                    {{-- SECTION A: DATA PRIBADI --}}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-600">
                            <span class="bg-blue-100 text-blue-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">A</span>
                            <h3 class="text-lg font-bold text-black-800">Data Pribadi & Kepegawaian</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Nama Lengkap (Sesuai KTP) <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm uppercase" required oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">NIK (16 Digit) <span class="text-red-500">*</span></label>
                                <input type="number" name="nik" value="{{ old('nik', $guru->nik) }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="jenis_kelamin" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="L" {{ $guru->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ $guru->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Status Kepegawaian <span class="text-red-500">*</span></label>
                                <select name="status_kepegawaian" id="status_kepegawaian" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" onchange="checkInsentifEligibility()">
                                    <option value="Non-ASN" {{ $guru->status_kepegawaian == 'Non-ASN' ? 'selected' : '' }}>Non-ASN (Guru Swasta)</option>
                                    <option value="PNS" {{ $guru->status_kepegawaian == 'PNS' ? 'selected' : '' }}>PNS</option>
                                    <option value="PPPK" {{ $guru->status_kepegawaian == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                </select>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Status Sertifikasi <span class="text-red-500">*</span></label>
                                <select name="status_sertifikasi" id="status_sertifikasi" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" onchange="checkInsentifEligibility()">
                                    <option value="Belum" {{ $guru->status_sertifikasi == 'Belum' ? 'selected' : '' }}>Belum Sertifikasi</option>
                                    <option value="Sertifikasi" {{ $guru->status_sertifikasi == 'Sertifikasi' ? 'selected' : '' }}>Sudah Sertifikasi</option>
                                    <option value="Inpassing" {{ $guru->status_sertifikasi == 'Inpassing' ? 'selected' : '' }}>Sudah Inpassing</option>
                                </select>
                            </div>

                            <div class="md:col-span-2 bg-yellow-50 px-1 py-1 rounded-md border border-yellow-200 transition-colors flex flex-col justify-center" id="box_insentif">
                                <label class="block text-[10px] font-bold text-black-800 mb-0.5">Apakah Menerima Insentif? <span class="text-red-500">*</span></label>
                                <select name="penerima_insentif" id="penerima_insentif" class="w-full border-yellow-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-700 shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                    <option value="0" {{ $guru->penerima_insentif == 0 ? 'selected' : '' }}>❌ TIDAK / BELUM MENERIMA</option>
                                    <option value="1" {{ $guru->penerima_insentif == 1 ? 'selected' : '' }}>✅ YA, BERHAK MENERIMA INSENTIF</option>
                                </select>
                                <p class="text-[9px] text-black-500 mt-1 leading-none" id="msg_insentif">*Ubah ke "YA" jika guru ini berhak menerima insentif.</p>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm uppercase" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($guru->tanggal_lahir)->format('Y-m-d')) }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Nama Ibu Kandung</label>
                                <input type="text" name="nama_ibu_kandung" value="{{ old('nama_ibu_kandung', $guru->nama_ibu_kandung) }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm uppercase" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Agama</label>
                                <select name="agama" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="Islam" {{ $guru->agama == 'Islam' ? 'selected' : '' }}>ISLAM</option>
                                    <option value="Kristen" {{ $guru->agama == 'Kristen' ? 'selected' : '' }}>KRISTEN</option>
                                    <option value="Katholik" {{ $guru->agama == 'Katholik' ? 'selected' : '' }}>KATHOLIK</option>
                                    <option value="Hindu" {{ $guru->agama == 'Hindu' ? 'selected' : '' }}>HINDU</option>
                                    <option value="Budha" {{ $guru->agama == 'Budha' ? 'selected' : '' }}>BUDHA</option>
                                    <option value="Konghucu" {{ $guru->agama == 'Konghucu' ? 'selected' : '' }}>KONGHUCU</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION B: KELEMBAGAAN --}}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-600">
                            <span class="bg-green-100 text-green-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">B</span>
                            <h3 class="text-lg font-bold text-black-800">Kelembagaan & Wilayah</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Jenis Lembaga</label>
                                <input type="text" value="{{ $guru->jenis_guru }}" class="w-full bg-gray-100 border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-gray-500 cursor-not-allowed shadow-sm" readonly>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Kecamatan <span class="text-red-500">*</span></label>
                                <select name="kecamatan" id="kecamatanSelect" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                                    @foreach($kecamatans as $kec)
                                        <option value="{{ $kec->nama_kecamatan }}" data-id="{{ $kec->id }}" {{ $guru->kecamatan == $kec->nama_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Desa / Kelurahan <span class="text-red-500">*</span></label>
                                <select name="desa" id="desaSelect" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                                    <option value="{{ $guru->desa }}">{{ $guru->desa }}</option>
                                </select>
                                <div id="allDesasData" class="hidden">
                                    @foreach($desas as $d)
                                        <div data-kecamatan-id="{{ $d->kecamatan_id }}" data-nama="{{ $d->nama_desa }}"></div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-green-700 uppercase tracking-wider mb-1">Lembaga Tempat Mengajar <span class="text-red-500">*</span></label>
                                <input list="list_lembaga" name="lembaga_id_input" value="{{ old('lembaga_id_input', $guru->lembaga->nama_lembaga . ' (' . $guru->lembaga->desa->nama_desa . ')') }}" class="w-full border border-green-500 bg-green-50 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 shadow-sm focus:ring-green-500" placeholder="Ketik nama lembaga..." required onchange="setLembagaId(this)">
                                <datalist id="list_lembaga">
                                    @foreach($lembagas as $l)
                                        <option data-id="{{ $l->id }}" value="{{ $l->nama_lembaga }} ({{ $l->desa->nama_desa }})"></option>
                                    @endforeach
                                </datalist>
                                <input type="hidden" name="lembaga_id" id="lembaga_id_hidden" value="{{ old('lembaga_id', $guru->lembaga_id) }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Alamat Lengkap (KTP)</label>
                                <input type="text" name="alamat_ktp" value="{{ old('alamat_ktp', $guru->alamat_ktp) }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm uppercase" required oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Kabupaten</label>
                                <input type="text" name="kabupaten" value="KEDIRI" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs bg-gray-100 shadow-sm" readonly>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Nomor HP</label>
                                <input type="number" name="no_hp" value="{{ old('no_hp', $guru->no_hp) }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Nomor Rekening BANK JATIM</label>
                                <input type="number" name="nomor_rekening" value="{{ old('nomor_rekening', $guru->nomor_rekening) }}" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION C: UPLOAD DOKUMEN --}}
                    <div class="mb-1">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-600">
                            <span class="bg-purple-100 text-purple-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">C</span>
                            <h3 class="text-lg font-bold text-black-800">Upload Dokumen</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            {{-- Kotak KTP --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-600 shadow-sm flex flex-col items-start">
                                <label class="block text-xs font-bold text-black-800 mb-2">1. Scan KTP Asli *</label>
                                @if($guru->file_ktp)
                                    <p class="text-[10px] text-green-600 font-bold mb-1">✓ File Tersimpan</p>
                                @endif
                                <div class="w-full text-left">
                                    <input type="file" name="file_ktp" id="file_ktp" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer" onchange="handleFileSelect(this, 'preview_ktp_new', 'btn_reset_ktp', 'old_ktp_frame')">
                                    <button type="button" id="btn_reset_ktp" onclick="resetFile('file_ktp', 'preview_ktp_new', 'btn_reset_ktp', 'old_ktp_frame')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload Baru</button>
                                </div>
                                @if($guru->file_ktp)
                                    <div class="mt-2 w-full" id="old_ktp_frame"><iframe src="{{ asset('storage/' . $guru->file_ktp) }}" type="application/pdf" class="w-full h-[250px] border border-gray-400 rounded bg-white"></iframe></div>
                                @endif
                                <div class="mt-2 w-full"><iframe id="preview_ktp_new" type="application/pdf" class="hidden w-full h-[250px] border border-gray-600 rounded bg-white"></iframe></div>
                            </div>

                            {{-- Kotak KK --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-600 shadow-sm flex flex-col items-start">
                                <label class="block text-xs font-bold text-black-800 mb-2">2. Scan Kartu Keluarga *</label>
                                @if($guru->file_kk)
                                    <p class="text-[10px] text-green-600 font-bold mb-1">✓ File Tersimpan</p>
                                @endif
                                <div class="w-full text-left">
                                    <input type="file" name="file_kk" id="file_kk" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-green-600 file:text-white hover:file:bg-green-700 transition cursor-pointer" onchange="handleFileSelect(this, 'preview_kk_new', 'btn_reset_kk', 'old_kk_frame')">
                                    <button type="button" id="btn_reset_kk" onclick="resetFile('file_kk', 'preview_kk_new', 'btn_reset_kk', 'old_kk_frame')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload Baru</button>
                                </div>
                                @if($guru->file_kk)
                                    <div class="mt-2 w-full" id="old_kk_frame"><iframe src="{{ asset('storage/' . $guru->file_kk) }}" type="application/pdf" class="w-full h-[250px] border border-gray-400 rounded bg-white"></iframe></div>
                                @endif
                                <div class="mt-2 w-full"><iframe id="preview_kk_new" type="application/pdf" class="hidden w-full h-[250px] border border-gray-600 rounded bg-white"></iframe></div>
                            </div>

                            {{-- Kotak Rekening --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-600 shadow-sm flex flex-col items-start">
                                <label class="block text-xs font-bold text-black-800 mb-2">3. Scan Buku Rekening</label>
                                @if($guru->file_bukurekening)
                                    <p class="text-[10px] text-green-600 font-bold mb-1">✓ File Tersimpan</p>
                                @endif
                                <div class="w-full text-left">
                                    <input type="file" name="file_bukurekening" id="file_bukurekening" accept="application/pdf" class="block w-full text-[10px] text-black-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 transition cursor-pointer" onchange="handleFileSelect(this, 'preview_rekening_new', 'btn_reset_rekening', 'old_rekening_frame')">
                                    <button type="button" id="btn_reset_rekening" onclick="resetFile('file_bukurekening', 'preview_rekening_new', 'btn_reset_rekening', 'old_rekening_frame')" class="hidden mt-1 text-[10px] text-red-600 font-bold underline">&times; Batal Upload Baru</button>
                                </div>
                                @if($guru->file_bukurekening)
                                    <div class="mt-2 w-full" id="old_rekening_frame"><iframe src="{{ asset('storage/' . $guru->file_bukurekening) }}" type="application/pdf" class="w-full h-[250px] border border-gray-400 rounded bg-white"></iframe></div>
                                @endif
                                <div class="mt-2 w-full"><iframe id="preview_rekening_new" type="application/pdf" class="hidden w-full h-[250px] border border-gray-600 rounded bg-white"></iframe></div>
                            </div>

                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ $backRoute }}" class="px-5 py-2.5 text-sm font-bold text-black-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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

        // --- 2. Filter Lembaga ---
        function setLembagaId(input) {
            const list = document.getElementById('list_lembaga'); const hiddenInput = document.getElementById('lembaga_id_hidden'); const options = list.options; hiddenInput.value = ""; 
            for (let i = 0; i < options.length; i++) { if (options[i].value === input.value) { hiddenInput.value = options[i].getAttribute('data-id'); break; } }
        }

        function filterLembaga() {
            var input, filter, select, options, i, txtValue;
            input = document.getElementById("searchLembaga");
            if(input) { // Cek existensi elemen agar tidak error di console
                filter = input.value.toUpperCase();
                select = document.getElementById("lembagaSelect");
                options = select.getElementsByTagName("option");
                for (i = 0; i < options.length; i++) {
                    txtValue = options[i].textContent || options[i].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) { options[i].style.display = ""; } else { options[i].style.display = "none"; }
                }
            }
        }

        // --- 3. Wilayah ---
        document.addEventListener('DOMContentLoaded', function() {
            const kecamatanSelect = document.getElementById('kecamatanSelect');
            const desaSelect = document.getElementById('desaSelect');
            const allDesas = Array.from(document.querySelectorAll('#allDesasData div')).map(div => ({ kecamatan_id: div.getAttribute('data-kecamatan-id'), nama: div.getAttribute('data-nama') }));
            const oldDesa = "{{ $guru->desa }}";
            const oldKecamatan = "{{ $guru->kecamatan }}"; 

            // Set Initial Kecamatan value
            for(let i=0; i<kecamatanSelect.options.length; i++){
                if(kecamatanSelect.options[i].value === oldKecamatan) { kecamatanSelect.selectedIndex = i; break; }
            }

            function populateDesa() {
                const selectedOption = kecamatanSelect.options[kecamatanSelect.selectedIndex];
                const selectedKecId = selectedOption ? selectedOption.getAttribute('data-id') : null;
                desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>'; 
                if(selectedKecId) {
                    const filteredDesas = allDesas.filter(d => d.kecamatan_id == selectedKecId);
                    filteredDesas.forEach(d => {
                        const option = document.createElement('option');
                        option.value = d.nama; 
                        option.text = d.nama;
                        
                        // [FIX] Samakan jadi huruf besar semua saat dicocokkan
                        if(d.nama.toUpperCase() === oldDesa.toUpperCase()) {
                            option.selected = true; 
                        }
                        
                        desaSelect.appendChild(option);
                    });
                }
            }
            
            kecamatanSelect.addEventListener('change', populateDesa);
            populateDesa(); 
            
            // JALANKAN CEK INSENTIF SAAT PERTAMA LOAD
            checkInsentifEligibility();
        });

        // --- 4. File Upload ---
        function handleFileSelect(input, iframeId, btnId, oldFrameId = null) {
            const iframe = document.getElementById(iframeId); const btnReset = document.getElementById(btnId);
            if(oldFrameId) { const oldFrame = document.getElementById(oldFrameId); if(oldFrame) oldFrame.classList.add('hidden'); }
            if (input.files && input.files[0]) {
                if(input.files[0].type !== 'application/pdf'){ alert("Mohon upload PDF!"); input.value = ""; return; }
                const reader = new FileReader();
                reader.onload = function(e) { iframe.src = e.target.result; iframe.classList.remove('hidden'); btnReset.classList.remove('hidden'); }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function resetFile(inputId, iframeId, btnId, oldFrameId = null) {
            const input = document.getElementById(inputId); const iframe = document.getElementById(iframeId); const btnReset = document.getElementById(btnId);
            if(oldFrameId) { const oldFrame = document.getElementById(oldFrameId); if(oldFrame) oldFrame.classList.remove('hidden'); }
            input.value = ""; iframe.src = ""; iframe.classList.add('hidden'); btnReset.classList.add('hidden');
        }
    </script>
</x-app-layout>