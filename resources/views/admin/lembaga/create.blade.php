<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Lembaga') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER NAVIGASI --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Tambah Lembaga Baru</h1>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi data identitas, statistik, dan dokumen legalitas.</p>
                </div>
                <a href="{{ route('lembaga.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    &larr; Kembali
                </a>
            </div>

            {{-- FORM CARD --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                
                {{-- ENCTYPE WAJIB ADA --}}
                <form action="{{ route('lembaga.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf

                    {{-- SECTION A: IDENTITAS & LOKASI --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <span class="bg-blue-100 text-blue-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">A</span>
                            <h3 class="text-lg font-bold text-gray-800">Identitas & Lokasi</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nama Lembaga (Full Width) --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1 text-center">Nama Lembaga <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga') }}" 
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 text-center uppercase" 
                                       placeholder="CONTOH: TPQ AL-HIDAYAH" required oninput="this.value = this.value.toUpperCase()">
                            </div>

                            {{-- Kecamatan (Logic Role) --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1 text-center">Kecamatan <span class="text-red-500">*</span></label>
                                @if(Auth::user()->role == 'korcam')
                                    <input type="text" value="{{ Auth::user()->kecamatan->nama_kecamatan }}" 
                                           class="w-full bg-gray-100 border-gray-300 rounded-lg text-gray-500 text-sm py-2.5 cursor-not-allowed font-bold text-center" readonly>
                                    <input type="hidden" name="kecamatan_id" value="{{ Auth::user()->kecamatan_id }}">
                                @else
                                    <select name="kecamatan_id" id="kecamatanSelect" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 text-center">
                                        <option value="">-- Pilih Kecamatan --</option>
                                        @foreach($kecamatans as $kec)
                                            <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            {{-- Desa (Dependent Dropdown) --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1 text-center">Desa / Kelurahan <span class="text-red-500">*</span></label>
                                <select name="desa_id" id="desaSelect" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 text-center">
                                    <option value="">-- Pilih Desa --</option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" data-kecamatan="{{ $desa->kecamatan_id }}">{{ $desa->nama_desa }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jenis & Ormas --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1 text-center">Jenis Lembaga <span class="text-red-500">*</span></label>
                                <select name="jenis_lembaga" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 text-center">
                                    <option value="TPQ">TPQ</option>
                                    <option value="MADIN">MADIN</option>
                                    <option value="PONPES">PONPES</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1 text-center">Ormas Afiliasi</label>
                                <select name="ormas" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 text-center">
                                    <option value="">- Tidak Ada / Lainnya -</option>
                                    <option value="NU">Nahdlatul Ulama (NU)</option>
                                    <option value="Muhammadiyah">Muhammadiyah</option>
                                    <option value="LDII">LDII</option>
                                </select>
                            </div>

                            {{-- NSBQ & Alamat --}}
                            {{-- NSBQ & Alamat --}}
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1 text-center">Nomor Statistik (NSBQ)</label>
                                    <input type="text" name="nsbq" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 text-center uppercase" placeholder="OPSIONAL" oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1 text-center">Alamat Lengkap</label>
                                    <input type="text" name="alamat" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 text-center uppercase" placeholder="JALAN, DUSUN, RT/RW" oninput="this.value = this.value.toUpperCase()">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION B: STATISTIK & KONTAK --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <span class="bg-green-100 text-green-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">B</span>
                            <h3 class="text-lg font-bold text-gray-800">Statistik & Kontak</h3>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            {{-- Baris 1: Statistik --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1 text-center">Jml Santri</label>
                                <input type="number" name="jumlah_santri" value="0" class="w-full border-gray-300 bg-gray-50 rounded-lg focus:ring-blue-500 text-sm font-bold text-center">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1 text-center">Total Guru</label>
                                <input type="number" name="jumlah_guru" value="0" class="w-full border-gray-300 bg-gray-50 rounded-lg focus:ring-blue-500 text-sm font-bold text-center">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1 text-center">Guru Insentif</label>
                                <input type="number" name="penerima_insentif" value="0" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 text-sm text-center">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1 text-center">Guru Non-Insentif</label>
                                <input type="number" name="belum_menerima_insentif" value="0" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 text-sm text-center">
                            </div>
                            
                            {{-- Hidden inputs default --}}
                            <input type="hidden" name="jumlah_pns" value="0">
                            <input type="hidden" name="jumlah_pppk" value="0">
                            <input type="hidden" name="jumlah_sertifikasi" value="0">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1 text-center">Kepala Lembaga</label>
                                <input type="text" name="kepala_lembaga" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 text-center uppercase" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1 text-center">No. Telp / WA</label>
                                <input type="text" name="no_telp" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 text-center">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1 text-center">Status Operasional</label>
                                <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 text-center">
                                    <option value="AKTIF">AKTIF</option>
                                    <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION C: DOKUMEN LEGALITAS (LAYOUT BARU: ATAS BAWAH) --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <span class="bg-purple-100 text-purple-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">C</span>
                            <h3 class="text-lg font-bold text-gray-800">Upload Dokumen (PDF)</h3>
                        </div>

                        {{-- Ubah Grid menjadi Space-y (Vertical Stack) agar Full Width --}}
                        <div class="space-y-8">
                            
                            {{-- 1. KOTAK UPLOAD IJOP --}}
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                                <div class="flex justify-between items-center mb-4">
                                    <label class="block text-base font-bold text-gray-800">1. Izin Operasional (IJOP)</label>
                                    <span class="text-[10px] bg-white border border-gray-300 px-2 py-0.5 rounded text-gray-500 font-mono">PDF Max 2MB</span>
                                </div>
                                
                                {{-- Input File Wrapper --}}
                                <div class="relative group mb-4">
                                    <input type="file" name="file_ijop" id="file_ijop" accept="application/pdf"
                                           class="block w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2.5 file:px-6
                                                  file:rounded-full file:border-0
                                                  file:text-sm file:font-bold
                                                  file:bg-blue-600 file:text-white
                                                  hover:file:bg-blue-700 transition cursor-pointer text-center"
                                           onchange="handleFileSelect(this, 'preview_ijop', 'btn_reset_ijop')">
                                    
                                    {{-- Tombol Hapus (Hidden Default) --}}
                                    <div class="text-center">
                                        <button type="button" id="btn_reset_ijop" onclick="resetFile('file_ijop', 'preview_ijop', 'btn_reset_ijop')" 
                                                class="hidden mt-2 text-sm text-red-600 hover:text-red-800 font-bold underline transition">
                                            &times; Hapus File / Batal Upload
                                        </button>
                                    </div>
                                </div>

                                {{-- Preview Iframe (Lebar & Tinggi) --}}
                                <div>
                                    <iframe id="preview_ijop" class="hidden w-full h-[500px] border border-gray-300 rounded-lg bg-white shadow-inner" src=""></iframe>
                                </div>

                                {{-- TANGGAL OTOMATIS 5 TAHUN --}}
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-600 mb-1 text-center md:text-left">Tanggal Terbit IJOP</label>
                                            <input type="date" name="masa_berlaku_ijop" id="tgl_ijop" 
                                                   class="w-full border-gray-300 rounded-md text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 text-center">
                                        </div>
                                        <div>
                                            {{-- Tempat Menampilkan Masa Berlaku --}}
                                            <div id="info_masa_berlaku" class="hidden bg-green-50 border border-green-200 text-green-800 text-sm p-3 rounded-lg text-center font-medium">
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="ijop" value="ADA"> 
                                <input type="hidden" name="status_ijop" value="Pending">
                            </div>

                            {{-- 2. KOTAK UPLOAD SUPER --}}
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                                <div class="flex justify-between items-center mb-4">
                                    <label class="block text-base font-bold text-gray-800">2. Surat Pernyataan Tanggung Jawab Mutlak (SPTJM)</label>
                                    <span class="text-[10px] bg-white border border-gray-300 px-2 py-0.5 rounded text-gray-500 font-mono">PDF Max 2MB</span>
                                </div>

                                <div class="relative group mb-4">
                                    <input type="file" name="file_super" id="file_super" accept="application/pdf"
                                           class="block w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2.5 file:px-6
                                                  file:rounded-full file:border-0
                                                  file:text-sm file:font-bold
                                                  file:bg-purple-600 file:text-white
                                                  hover:file:bg-purple-700 transition cursor-pointer text-center"
                                           onchange="handleFileSelect(this, 'preview_super', 'btn_reset_super')">
                                    
                                    <div class="text-center">
                                        <button type="button" id="btn_reset_super" onclick="resetFile('file_super', 'preview_super', 'btn_reset_super')" 
                                                class="hidden mt-2 text-sm text-red-600 hover:text-red-800 font-bold underline transition">
                                            &times; Hapus File / Batal Upload
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <iframe id="preview_super" class="hidden w-full h-[500px] border border-gray-300 rounded-lg bg-white shadow-inner" src=""></iframe>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                                    
                                    <p class="text-xs text-gray-500 italic">Pastikan dokumen Surat Pernyataan sudah ditandatangani dan stempel basah sebelum di-scan.</p>
                                </div>
                                <input type="hidden" name="status_super" value="Pending">
                            </div>

                            {{-- 3. KOTAK UPLOAD SKAM [BARU] --}}
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                                <div class="flex justify-between items-center mb-4">
                                    <label class="block text-base font-bold text-gray-800">3. Surat Keterangan Aktif Mengajar</label>
                                    <span class="text-[10px] bg-white border border-gray-300 px-2 py-0.5 rounded text-gray-500 font-mono">PDF Max 2MB</span>
                                </div>

                                <div class="relative group mb-4">
                                    <input type="file" name="file_skam" id="file_skam" accept="application/pdf"
                                           class="block w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2.5 file:px-6
                                                  file:rounded-full file:border-0
                                                  file:text-sm file:font-bold
                                                  file:bg-teal-600 file:text-white
                                                  hover:file:bg-teal-700 transition cursor-pointer text-center"
                                           onchange="handleFileSelect(this, 'preview_skam', 'btn_reset_skam')">
                                    
                                    <div class="text-center">
                                        <button type="button" id="btn_reset_skam" onclick="resetFile('file_skam', 'preview_skam', 'btn_reset_skam')" 
                                                class="hidden mt-2 text-sm text-red-600 hover:text-red-800 font-bold underline transition">
                                            &times; Hapus File / Batal Upload
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <iframe id="preview_skam" class="hidden w-full h-[500px] border border-gray-300 rounded-lg bg-white shadow-inner" src=""></iframe>
                                </div>
                                
                                <input type="hidden" name="status_skam" value="Pending">
                            </div>

                        </div>
                        
                        <div class="mt-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-1 text-center">Catatan Tambahan (Opsional)</label>
                            <textarea name="keterangan" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm text-sm text-center uppercase" placeholder="TULIS CATATAN JIKA ADA..." oninput="this.value = this.value.toUpperCase()"></textarea>
                        </div>
                    </div>

                    {{-- FOOTER ACTION --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('lembaga.index') }}" class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-green-600 rounded-lg shadow-md hover:bg-green-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Data
                        </button>
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
    </script>
</x-app-layout>