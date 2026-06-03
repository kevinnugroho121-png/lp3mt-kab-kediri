<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Atlet Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Error Validasi --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Gagal menyimpan data:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FORM UTAMA --}}
                    <form action="{{ route('atlet.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- ================================================= --}}
                        {{-- BAGIAN 1: AKUN LOGIN (WAJIB DIISI ADMIN) --}}
                        {{-- ================================================= --}}
                        <div class="mb-8 bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                                🔐 Buat Akun Login (Wajib)
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Input Email --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Login <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" required 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Contoh: kevin@gmail.com">
                                    <p class="text-xs text-gray-500 mt-1">Email ini akan digunakan atlet untuk login.</p>
                                </div>

                                {{-- Input Password --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                                    <input type="text" name="password" required 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Minimal 8 karakter">
                                    <p class="text-xs text-gray-500 mt-1">Saran: Gunakan password default (misal: 12345678).</p>
                                </div>
                            </div>
                        </div>
                        {{-- ================================================= --}}


                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            
                            {{-- KOLOM KIRI: AREA UPLOAD FOTO (Lebar 1 kolom) --}}
                            <div class="md:col-span-1 flex flex-col items-center">
                                <label class="block text-lg font-bold text-gray-700 mb-4 border-b pb-2 w-full text-center">Foto Profil</label>
                                
                                {{-- Frame Preview Foto (Rasio 3:4) --}}
                                <div class="w-48 h-64 bg-gray-100 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center overflow-hidden relative mb-4 shadow-sm group hover:border-blue-500 transition">
                                    {{-- Image Preview (Default Hidden) --}}
                                    <img id="preview-foto" src="#" alt="Preview Foto" class="absolute inset-0 w-full h-full object-cover hidden">
                                    
                                    {{-- Placeholder Teks --}}
                                    <div id="placeholder-foto" class="text-center p-4">
                                        <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="mt-1 text-xs text-gray-500">Upload Foto 3x4</p>
                                        <p class="text-[10px] text-gray-400">(Max 2MB)</p>
                                    </div>
                                </div>

                                {{-- Input File --}}
                                <input type="file" name="foto_profil" id="foto_profil" accept="image/*" 
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                                    onchange="previewImage(event)">
                                <p class="text-xs text-gray-500 mt-2 text-center italic">*Opsional. Format JPG/PNG.</p>
                            </div>

                            {{-- KOLOM KANAN: DATA DIRI (Lebar 2 kolom) --}}
                            <div class="md:col-span-2 space-y-6">
                                
                                {{-- BAGIAN A: DATA DIRI --}}
                                <div>
                                    <h3 class="font-bold text-lg text-blue-600 mb-4 border-b pb-2">A. Data Diri Atlet</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="mb-4">
                                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                        </div>

                                        <div class="mb-4">
                                            <label for="nama_panggilan" class="block text-sm font-medium text-gray-700">Nama Panggilan</label>
                                            <input type="text" name="nama_panggilan" id="nama_panggilan" value="{{ old('nama_panggilan') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="mb-4">
                                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                        </div>

                                        {{-- FITUR HITUNG UMUR OTOMATIS --}}
                                        <div class="mb-4">
                                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                            
                                            <div id="info-umur" class="mt-2 text-sm p-2 rounded hidden">
                                                {{-- Isi akan diupdate oleh Javascript --}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="mb-4">
                                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label for="no_hp_atlet" class="block text-sm font-medium text-gray-700">No. HP Atlet (WA)</label>
                                            <input type="number" name="no_hp_atlet" id="no_hp_atlet" value="{{ old('no_hp_atlet') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                        <textarea name="alamat" id="alamat" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('alamat') }}</textarea>
                                    </div>
                                </div>

                                {{-- BAGIAN B: DATA SEKOLAH --}}
                                <div>
                                    <h3 class="font-bold text-lg text-blue-600 mb-4 border-b pb-2">B. Data Sekolah</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="mb-4">
                                            <label for="jenjang_sekolah" class="block text-sm font-medium text-gray-700">Jenjang Sekolah</label>
                                            <select name="jenjang_sekolah" id="jenjang_sekolah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                                <option value="">-- Pilih Jenjang --</option>
                                                <option value="SD" {{ old('jenjang_sekolah') == 'SD' ? 'selected' : '' }}>SD / MI</option>
                                                <option value="SMP" {{ old('jenjang_sekolah') == 'SMP' ? 'selected' : '' }}>SMP / MTs</option>
                                                <option value="SMA" {{ old('jenjang_sekolah') == 'SMA' ? 'selected' : '' }}>SMA / SMK / MA</option>
                                                <option value="Kuliah" {{ old('jenjang_sekolah') == 'Kuliah' ? 'selected' : '' }}>Kuliah</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label for="nama_sekolah" class="block text-sm font-medium text-gray-700">Nama Sekolah</label>
                                            <input type="text" name="nama_sekolah" id="nama_sekolah" value="{{ old('nama_sekolah') }}" placeholder="Contoh: SMAN 2 Kediri" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                        </div>
                                    </div>
                                </div>

                                {{-- BAGIAN C: DATA AKADEMI --}}
                                <div>
                                    <h3 class="font-bold text-lg text-blue-600 mb-4 border-b pb-2">C. Data Akademi (Basket)</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="mb-4">
                                            <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                                            <select name="kategori" id="kategori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                <option value="KU-10" {{ old('kategori') == 'KU-10' ? 'selected' : '' }}>KU-10 (SD)</option>
                                                <option value="KU-12" {{ old('kategori') == 'KU-12' ? 'selected' : '' }}>KU-12 (SMP Awal)</option>
                                                <option value="KU-14" {{ old('kategori') == 'KU-14' ? 'selected' : '' }}>KU-14 (SMP)</option>
                                                <option value="KU-16" {{ old('kategori') == 'KU-16' ? 'selected' : '' }}>KU-16 (SMA)</option>
                                                <option value="KU-18" {{ old('kategori') == 'KU-18' ? 'selected' : '' }}>KU-18 (SMA Akhir)</option>
                                            </select>
                                            <p class="text-xs text-gray-500 mt-1">*Otomatis dari Tgl Lahir.</p>
                                        </div>
                                        <div class="mb-4">
                                            <label for="posisi" class="block text-sm font-medium text-gray-700">Posisi</label>
                                            <select name="posisi" id="posisi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <option value="Belum Ditentukan">-- Pilih Posisi --</option>
                                                <option value="Point Guard" {{ old('posisi') == 'Point Guard' ? 'selected' : '' }}>Point Guard</option>
                                                <option value="Shooting Guard" {{ old('posisi') == 'Shooting Guard' ? 'selected' : '' }}>Shooting Guard</option>
                                                <option value="Small Forward" {{ old('posisi') == 'Small Forward' ? 'selected' : '' }}>Small Forward</option>
                                                <option value="Power Forward" {{ old('posisi') == 'Power Forward' ? 'selected' : '' }}>Power Forward</option>
                                                <option value="Center" {{ old('posisi') == 'Center' ? 'selected' : '' }}>Center</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                                <option value="Aktif" selected>Aktif</option>
                                                <option value="Non-Aktif">Non-Aktif</option>
                                                <option value="Keluar">Keluar</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- BAGIAN D: DATA ORANG TUA --}}
                                <div>
                                    <h3 class="font-bold text-lg text-blue-600 mb-4 border-b pb-2">D. Data Orang Tua / Wali</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="mb-4">
                                            <label for="nama_orang_tua" class="block text-sm font-medium text-gray-700">Nama Orang Tua</label>
                                            <input type="text" name="nama_orang_tua" id="nama_orang_tua" value="{{ old('nama_orang_tua') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="no_hp_orang_tua" class="block text-sm font-medium text-gray-700">No. HP Orang Tua (WA)</label>
                                            <input type="number" name="no_hp_orang_tua" id="no_hp_orang_tua" value="{{ old('no_hp_orang_tua') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                        </div>
                                    </div>
                                </div>

                                {{-- TOMBOL AKSI --}}
                                <div class="flex justify-end mt-6">
                                    <a href="{{ route('atlet.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 mr-2">
                                        Batal
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                        Simpan Data & Buat Akun
                                    </button>
                                </div>

                            </div> {{-- End Kolom Kanan --}}
                        </div> {{-- End Grid Utama --}}
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT: LOGIKA UMUR + PREVIEW FOTO --}}
    <script>
        // 1. Script Preview Foto
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e){
                    const output = document.getElementById('preview-foto');
                    const placeholder = document.getElementById('placeholder-foto');
                    
                    output.src = e.target.result;
                    output.classList.remove('hidden'); // Munculkan gambar
                    placeholder.classList.add('hidden'); // Sembunyikan tulisan placeholder
                };
                reader.readAsDataURL(file);
            }
        }

        // 2. Script Validasi Umur & Kategori
        document.getElementById('tanggal_lahir').addEventListener('change', function() {
            var dob = new Date(this.value);
            var today = new Date();
            
            var infoDiv = document.getElementById('info-umur');
            var kategoriSelect = document.getElementById('kategori');
            
            infoDiv.className = "mt-2 text-sm p-2 rounded hidden"; 
            infoDiv.innerHTML = "";

            if(isNaN(dob.getTime())) return; 

            // === 1. VALIDASI TANGGAL MASA DEPAN ===
            if (dob > today) {
                infoDiv.classList.remove('hidden');
                infoDiv.className = "mt-2 text-sm p-2 bg-red-100 border border-red-300 rounded text-red-800";
                infoDiv.innerHTML = '❌ <strong>Error:</strong> Tanggal lahir tidak boleh di masa depan.';
                kategoriSelect.value = ""; // Reset dropdown
                return; // Stop proses
            }

            // === 2. HITUNG UMUR DETAIL ===
            var age = today.getFullYear() - dob.getFullYear();
            var m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
                var months = 12 + m;
                if(today.getDate() < dob.getDate()) months--;
            } else {
                var months = m;
            }

            // === 3. LOGIKA KATEGORI & VALIDASI USIA ===
            var pesanHtml = '<strong>Usia Saat Ini:</strong> ' + age + ' Tahun ' + months + ' Bulan.';
            var kelasWarna = "bg-blue-50 border border-blue-200 text-blue-800"; // Default Biru

            if (age < 5) {
                // KASUS: BALITA (DI BAWAH 5 TAHUN)
                kelasWarna = "bg-red-50 border border-red-200 text-red-800";
                pesanHtml += '<br><span class="font-bold">⛔ Belum Cukup Umur (Minimal 5 Tahun).</span>';
                kategoriSelect.value = ""; 
            } 
            else if (age > 18) {
                // KASUS: KETUAAN (DI ATAS 18 TAHUN)
                kelasWarna = "bg-red-50 border border-red-200 text-red-800";
                pesanHtml += '<br><span class="font-bold">⛔ Usia Melebihi Batas Akademi (Maksimal 18 Tahun).</span>';
                kategoriSelect.value = ""; 
            }
            else {
                // KASUS: USIA NORMAL (5 - 18 TAHUN)
                var saran = "";
                if (age <= 10) { kategoriSelect.value = "KU-10"; saran = "KU-10"; } 
                else if (age <= 12) { kategoriSelect.value = "KU-12"; saran = "KU-12"; } 
                else if (age <= 14) { kategoriSelect.value = "KU-14"; saran = "KU-14"; } 
                else if (age <= 16) { kategoriSelect.value = "KU-16"; saran = "KU-16"; } 
                else { kategoriSelect.value = "KU-18"; saran = "KU-18"; }
                pesanHtml += '<br><span class="text-green-700 font-bold">✅ Rekomendasi Sistem: Masuk ' + saran + '</span>';
            }

            infoDiv.className = "mt-2 text-sm p-2 rounded " + kelasWarna;
            infoDiv.innerHTML = pesanHtml;
            infoDiv.classList.remove('hidden');

            if (age >= 5 && age <= 18) {
                kategoriSelect.classList.add('ring', 'ring-green-300');
                setTimeout(() => {
                    kategoriSelect.classList.remove('ring', 'ring-green-300');
                }, 1000);
            }
        });
    </script>
</x-app-layout>