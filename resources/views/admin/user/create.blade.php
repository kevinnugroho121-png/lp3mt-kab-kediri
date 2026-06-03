@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Pengguna Baru</h2>
            <p class="text-sm text-gray-500">Buat akun untuk akses sistem LP3MT</p>
        </div>
        <a href="{{ route('user.index') }}" class="text-gray-600 hover:text-gray-900 font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="bg-blue-50 px-8 py-4 border-b border-blue-100 flex justify-between items-center">
            <h3 class="font-bold text-blue-800">Formulir Data User</h3>
            <div class="p-2 bg-blue-100 rounded-full text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
        </div>

        <div class="p-8">
            <form action="{{ route('user.store') }}" method="POST" id="userForm">
                @csrf

                {{-- FORM UTAMA --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Contoh: Ahmad Admin" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email (Login)</label>
                        <input type="email" name="email" required placeholder="nama@lp3mt.com" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan / Role</label>
                        <select name="role" id="roleSelect" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 bg-white">
                            <option value="" disabled selected>-- Pilih Jabatan --</option>
                            <option value="admin">Super Admin (Pusat)</option>
                            <option value="verifikator">Verifikator Kabupaten</option>
                            <option value="korcam">Koordinator Kecamatan (Korcam)</option>
                        </select>
                    </div>
                </div>

                {{-- AREA KORCAM --}}
                <div id="areaKorcam" class="hidden bg-blue-50 rounded-xl p-6 border border-blue-100 mb-6 transition-all duration-300">
                    <h4 class="font-bold text-blue-900 mb-4 flex items-center gap-2 border-b border-blue-200 pb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Detail Penugasan Wilayah
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Kecamatan --}}
                        <div>
                            <label class="block text-sm font-bold text-blue-900 mb-2">Bertugas di Kecamatan</label>
                            <select name="kecamatan_id" id="kecamatanSelect" class="w-full border-blue-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5">
                                <option value="" disabled selected>-- Pilih Wilayah --</option>
                                @foreach($kecamatans as $kec)
                                    <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Posisi (3 Tombol) --}}
                        <div>
                            <label class="block text-sm font-bold text-blue-900 mb-2">Posisi dalam Tim (Maks 3 Orang)</label>
                            <div class="grid grid-cols-3 gap-2">
                                {{-- Ketua --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="jabatan_korcam" value="Ketua" class="peer sr-only korcam-radio">
                                    <div class="p-2 rounded-lg border border-blue-200 bg-white hover:bg-blue-50 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition text-center shadow-sm flex flex-col justify-center h-full">
                                        <span class="block font-bold text-sm">Ketua</span>
                                    </div>
                                </label>
                                {{-- Anggota 1 --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="jabatan_korcam" value="Anggota 1" class="peer sr-only korcam-radio">
                                    <div class="p-2 rounded-lg border border-blue-200 bg-white hover:bg-blue-50 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition text-center shadow-sm flex flex-col justify-center h-full">
                                        <span class="block font-bold text-sm">Anggota 1</span>
                                    </div>
                                </label>
                                {{-- Anggota 2 --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="jabatan_korcam" value="Anggota 2" class="peer sr-only korcam-radio">
                                    <div class="p-2 rounded-lg border border-blue-200 bg-white hover:bg-blue-50 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition text-center shadow-sm flex flex-col justify-center h-full">
                                        <span class="block font-bold text-sm">Anggota 2</span>
                                    </div>
                                </label>
                            </div>
                            
                            {{-- PESAN ERROR (Hanya Muncul Jika Dobel) --}}
                            <div id="availabilityMessage" class="mt-3 hidden p-2 rounded-md text-xs font-bold text-center bg-red-100 text-red-700 border border-red-200">
                                ⚠️ Posisi ini sudah terisi! Pilih yang lain.
                            </div>
                        </div>

                    </div>
                </div>

                {{-- TOMBOL SUBMIT --}}
                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" id="btnSubmit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Pengguna
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const areaKorcam = document.getElementById('areaKorcam');
        const kecamatanSelect = document.getElementById('kecamatanSelect');
        const korcamRadios = document.querySelectorAll('.korcam-radio');
        const msgBox = document.getElementById('availabilityMessage');
        const btnSubmit = document.getElementById('btnSubmit');

        // 1. Tampilkan Area Korcam
        roleSelect.addEventListener('change', function() {
            if (this.value === 'korcam') {
                areaKorcam.classList.remove('hidden');
                kecamatanSelect.setAttribute('required', 'required');
            } else {
                areaKorcam.classList.add('hidden');
                kecamatanSelect.removeAttribute('required');
                resetState();
            }
        });

        // 2. Fungsi Reset
        function resetState() {
            msgBox.classList.add('hidden'); // Sembunyikan pesan error
            btnSubmit.disabled = false; // Hidupkan tombol
            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        // 3. Fungsi Cek Ketersediaan (Simple & Fail-Safe)
        function checkAvailability() {
            const kecId = kecamatanSelect.value;
            let selectedJabatan = null;
            korcamRadios.forEach(r => { if (r.checked) selectedJabatan = r.value; });

            // Cek hanya jika data lengkap
            if (roleSelect.value === 'korcam' && kecId && selectedJabatan) {
                
                // Gunakan fetch ke route (Pastikan route sudah ada di web.php)
                fetch(`{{ route('user.check-korcam') }}?kecamatan_id=${kecId}&jabatan=${selectedJabatan}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            // JIKA SUDAH ADA -> Tampilkan Error & Matikan Tombol
                            msgBox.classList.remove('hidden');
                            btnSubmit.disabled = true;
                            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            // JIKA AMAN -> Reset
                            resetState();
                        }
                    })
                    .catch(err => {
                        // JIKA ERROR SYSTEM (Route tidak ketemu, internet putus, dll)
                        // Biarkan user tetap bisa klik simpan (Fail-Safe)
                        console.log('Skip check, biarkan backend handle');
                        resetState(); 
                    });
            }
        }

        // Jalankan pengecekan setiap ada perubahan
        kecamatanSelect.addEventListener('change', checkAvailability);
        korcamRadios.forEach(radio => radio.addEventListener('change', checkAvailability));
    });
</script>
@endsection