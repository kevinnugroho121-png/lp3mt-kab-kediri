<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            Edit Data Guru
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER: Judul & Tombol Kembali --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-black-800">Edit Guru: {{ $guru->nama_lengkap }}</h1>
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
                    <div class="mb-10">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <span class="bg-blue-100 text-blue-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">A</span>
                            <h3 class="text-lg font-bold text-black-800">Data Pribadi</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">NIK</label>
                                <input type="number" name="nik" value="{{ old('nik', $guru->nik) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                                    <option value="L" {{ $guru->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ $guru->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            {{-- STATUS KEPEGAWAIAN (Ada ID untuk JS) --}}
                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Status Kepegawaian</label>
                                <select name="status_kepegawaian" id="status_kepegawaian" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center" onchange="checkInsentifEligibility()">
                                    <option value="Non-ASN" {{ $guru->status_kepegawaian == 'Non-ASN' ? 'selected' : '' }}>Non-ASN</option>
                                    <option value="PNS" {{ $guru->status_kepegawaian == 'PNS' ? 'selected' : '' }}>PNS</option>
                                    <option value="PPPK" {{ $guru->status_kepegawaian == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                </select>
                            </div>

                            {{-- STATUS SERTIFIKASI (Ada ID untuk JS) --}}
                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Status Sertifikasi</label>
                                <select name="status_sertifikasi" id="status_sertifikasi" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center" onchange="checkInsentifEligibility()">
                                    <option value="Belum" {{ $guru->status_sertifikasi == 'Belum' ? 'selected' : '' }}>Belum Sertifikasi</option>
                                    <option value="Sertifikasi" {{ $guru->status_sertifikasi == 'Sertifikasi' ? 'selected' : '' }}>Sudah Sertifikasi</option>
                                    <option value="Inpassing" {{ $guru->status_sertifikasi == 'Inpassing' ? 'selected' : '' }}>Sudah Inpassing</option>
                                </select>
                            </div>

                            {{-- [BARU] STATUS INSENTIF OTOMATIS --}}
                            <div class="md:col-span-2 bg-yellow-50 p-4 rounded-lg border border-yellow-200 transition-colors" id="box_insentif">
                                <label class="block text-sm font-bold text-black-800 mb-1 text-center">Apakah Menerima Insentif?</label>
                                <select name="penerima_insentif" id="penerima_insentif" class="w-full border-yellow-400 rounded-lg shadow-sm text-sm py-2.5 text-center font-bold text-black-700 focus:ring-yellow-500 focus:border-yellow-500">
                                    <option value="0" {{ $guru->penerima_insentif == 0 ? 'selected' : '' }}>❌ TIDAK / BELUM MENERIMA</option>
                                    <option value="1" {{ $guru->penerima_insentif == 1 ? 'selected' : '' }}>✅ YA, PENERIMA INSENTIF</option>
                                </select>
                                <p class="text-[10px] text-black-500 text-center mt-1" id="msg_insentif">*Ubah ke "YA" jika guru ini berhak menerima insentif.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($guru->tanggal_lahir)->format('Y-m-d')) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Nama Ibu Kandung</label>
                                <input type="text" name="nama_ibu_kandung" value="{{ old('nama_ibu_kandung', $guru->nama_ibu_kandung) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Agama</label>
                                <select name="agama" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                                    <option value="Islam" {{ $guru->agama == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ $guru->agama == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Lainnya" {{ $guru->agama == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION B: KELEMBAGAAN --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <span class="bg-green-100 text-green-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">B</span>
                            <h3 class="text-lg font-bold text-black-800">Kelembagaan & Kontak</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- LEMBAGA DENGAN DATALIST --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Lembaga Tempat Mengajar <span class="text-red-500">*</span></label>
                                
                                {{-- Input Text --}}
                                <input list="list_lembaga" 
                                       name="lembaga_id_input" 
                                       class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center" 
                                       placeholder="Ketik nama lembaga..." 
                                       required 
                                       onchange="setLembagaId(this)"
                                       value="{{ old('lembaga_id_input', $guru->lembaga->nama_lembaga . ' (' . $guru->lembaga->desa->nama_desa . ')') }}">
                                
                                {{-- Datalist --}}
                                <datalist id="list_lembaga">
                                    @foreach($lembagas as $l)
                                        <option data-id="{{ $l->id }}" value="{{ $l->nama_lembaga }} ({{ $l->desa->nama_desa }})"></option>
                                    @endforeach
                                </datalist>

                                {{-- Hidden ID --}}
                                <input type="hidden" name="lembaga_id" id="lembaga_id_hidden" value="{{ old('lembaga_id', $guru->lembaga_id) }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Alamat Lengkap</label>
                                <input type="text" name="alamat_ktp" value="{{ old('alamat_ktp', $guru->alamat_ktp) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Kecamatan</label>
                                <select name="kecamatan" id="kecamatanSelect" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center" required>
                                    @foreach($kecamatans as $kec)
                                        <option value="{{ $kec->nama_kecamatan }}" data-id="{{ $kec->id }}" {{ $guru->kecamatan == $kec->nama_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Desa / Kelurahan</label>
                                <select name="desa" id="desaSelect" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center" required>
                                    <option value="{{ $guru->desa }}">{{ $guru->desa }}</option>
                                </select>
                                <div id="allDesasData" class="hidden">
                                    @foreach($desas as $d)
                                        <div data-kecamatan-id="{{ $d->kecamatan_id }}" data-nama="{{ $d->nama_desa }}"></div>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Kabupaten</label>
                                <input type="text" name="kabupaten" value="Kediri" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center" readonly>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Nomor HP</label>
                                <input type="number" name="no_hp" value="{{ old('no_hp', $guru->no_hp) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-black-700 mb-1 text-center">Nomor Rekening</label>
                                <input type="number" name="nomor_rekening" value="{{ old('nomor_rekening', $guru->nomor_rekening) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 text-center font-bold">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION C: DOKUMEN --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <span class="bg-purple-100 text-purple-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">C</span>
                            <h3 class="text-lg font-bold text-black-800">Upload Dokumen</h3>
                        </div>

                        <div class="space-y-8">
                            {{-- 1. KTP --}}
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                                <label class="block text-base font-bold text-black-800 mb-3 text-center">1. Scan KTP Asli</label>
                                @if($guru->file_ktp)
                                    <div class="mb-4 text-center">
                                        <p class="text-xs text-green-600 font-bold mb-2">✓ File Tersimpan</p>
                                        <iframe src="{{ asset('storage/' . $guru->file_ktp) }}" type="application/pdf" class="w-full h-[400px] border border-gray-300 rounded-lg bg-white"></iframe>
                                    </div>
                                @endif
                                <div class="relative group text-center">
                                    <input type="file" name="file_ktp" id="file_ktp" accept="application/pdf"
                                           class="block w-full text-sm text-black-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer text-center"
                                           onchange="handleFileSelect(this, 'preview_ktp_new', 'btn_reset_ktp')">
                                    <button type="button" id="btn_reset_ktp" onclick="resetFile('file_ktp', 'preview_ktp_new', 'btn_reset_ktp')" class="hidden mt-2 text-sm text-red-600 font-bold underline">&times; Batal</button>
                                </div>
                                <div class="mt-4"><iframe id="preview_ktp_new" class="hidden w-full h-[500px] border border-gray-300 rounded-lg bg-white"></iframe></div>
                            </div>

                            {{-- 2. KK --}}
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                                <label class="block text-base font-bold text-black-800 mb-3 text-center">2. Scan KK</label>
                                @if($guru->file_kk)
                                    <div class="mb-4 text-center">
                                        <p class="text-xs text-green-600 font-bold mb-2">✓ File Tersimpan</p>
                                        <iframe src="{{ asset('storage/' . $guru->file_kk) }}" type="application/pdf" class="w-full h-[400px] border border-gray-300 rounded-lg bg-white"></iframe>
                                    </div>
                                @endif
                                <div class="relative group text-center">
                                    <input type="file" name="file_kk" id="file_kk" accept="application/pdf"
                                           class="block w-full text-sm text-black-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-green-600 file:text-white hover:file:bg-green-700 cursor-pointer text-center"
                                           onchange="handleFileSelect(this, 'preview_kk_new', 'btn_reset_kk')">
                                    <button type="button" id="btn_reset_kk" onclick="resetFile('file_kk', 'preview_kk_new', 'btn_reset_kk')" class="hidden mt-2 text-sm text-red-600 font-bold underline">&times; Batal</button>
                                </div>
                                <div class="mt-4"><iframe id="preview_kk_new" class="hidden w-full h-[500px] border border-gray-300 rounded-lg bg-white"></iframe></div>
                            </div>

                            {{-- 3. REKENING --}}
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                                <label class="block text-base font-bold text-black-800 mb-3 text-center">3. Scan Rekening</label>
                                @if($guru->file_bukurekening)
                                    <div class="mb-4 text-center">
                                        <p class="text-xs text-green-600 font-bold mb-2">✓ File Tersimpan</p>
                                        <iframe src="{{ asset('storage/' . $guru->file_bukurekening) }}" type="application/pdf" class="w-full h-[400px] border border-gray-300 rounded-lg bg-white"></iframe>
                                    </div>
                                @endif
                                <div class="relative group text-center">
                                    <input type="file" name="file_bukurekening" id="file_bukurekening" accept="application/pdf"
                                           class="block w-full text-sm text-black-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 cursor-pointer text-center"
                                           onchange="handleFileSelect(this, 'preview_rekening_new', 'btn_reset_rekening')">
                                    <button type="button" id="btn_reset_rekening" onclick="resetFile('file_bukurekening', 'preview_rekening_new', 'btn_reset_rekening')" class="hidden mt-2 text-sm text-red-600 font-bold underline">&times; Batal</button>
                                </div>
                                <div class="mt-4"><iframe id="preview_rekening_new" class="hidden w-full h-[500px] border border-gray-300 rounded-lg bg-white"></iframe></div>
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