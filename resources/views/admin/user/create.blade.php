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
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Ahmad Admin" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5">
                        @error('name') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email (Login)</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@lp3mt.com" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5">
                        {{-- INI YANG AKAN MEMUNCULKAN PESAN ERROR EMAIL GANDA --}}
                        @error('email') <span class="text-red-500 text-xs font-bold mt-1 block">⚠️ {{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input id="new_password" type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 pr-10">
                            <button type="button" onclick="toggleUserPassword()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-blue-600 transition focus:outline-none">
                                <svg id="eye_icon_user" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        @error('password') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan / Role</label>
                        <select name="role" id="roleSelect" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 bg-white">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Jabatan --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Super Admin (Pusat)</option>
                            <option value="verifikator" {{ old('role') == 'verifikator' ? 'selected' : '' }}>Verifikator Kabupaten</option>
                            <option value="korcam" {{ old('role') == 'korcam' ? 'selected' : '' }}>Koordinator Kecamatan (Korcam)</option>
                        </select>
                        @error('role') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
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
                            <select name="kecamatan_id" id="kecamatanSelect" class="w-full border-blue-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" style="width: 100%;">
                                <option value="" disabled selected>-- Cari & Pilih Wilayah --</option>
                                @foreach($kecamatans as $kec)
                                    @php
                                        // Cek ketersediaan dari relasi user (Pastikan Controller sudah pakai with('users'))
                                        $hasKetua = $kec->users->where('jabatan_korcam', 'Ketua')->isNotEmpty() ? 'true' : 'false';
                                        $hasA1 = $kec->users->where('jabatan_korcam', 'Anggota 1')->isNotEmpty() ? 'true' : 'false';
                                        $hasA2 = $kec->users->where('jabatan_korcam', 'Anggota 2')->isNotEmpty() ? 'true' : 'false';
                                    @endphp
                                    <option value="{{ $kec->id }}" 
                                            data-ketua="{{ $hasKetua }}" 
                                            data-a1="{{ $hasA1 }}" 
                                            data-a2="{{ $hasA2 }}">
                                        {{ $kec->nama_kecamatan }}
                                    </option>
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




{{-- PANGGIL LIBRARY JQUERY & SELECT2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Menyesuaikan gaya Select2 dengan form Tailwind bawaan */
    .select2-container .select2-selection--single {
        height: 46px; 
        border: 1px solid #93C5FD; /* border-blue-300 */
        border-radius: 0.5rem; /* rounded-lg */
        padding-top: 8px;
        padding-left: 8px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow { top: 10px; right: 10px; }
    .select2-dropdown { border: 1px solid #93C5FD; border-radius: 0.5rem; overflow: hidden; }
</style>

<script>
    // 1. FUNGSI TOGGLE MATA PASSWORD (Vanilla JS)
    window.toggleUserPassword = function() {
        const passwordInput = document.getElementById('new_password');
        const eyeIcon = document.getElementById('eye_icon_user');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0l3.29 3.29m0 0l-3.29-3.29m0 0L3 3m18 18l-3.29-3.29m0 0l-3.29-3.29m0 0l3.29 3.29m0 0L21 21"></path>';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        }
    };

    // 2. FUNGSI JQUERY UNTUK SELECT2 DAN LOGIKA FORM
    $(document).ready(function() {
        const roleSelect = $('#roleSelect');
        const areaKorcam = $('#areaKorcam');
        const msgBox = $('#availabilityMessage');
        const btnSubmit = $('#btnSubmit');

        // A. Template Custom untuk Isi Dropdown Select2
        function formatKecamatan (state) {
            if (!state.id) { return state.text; } // Jika opsi default

            var $el = $(state.element);
            var isKetuaFull = $el.data('ketua') === true;
            var isA1Full = $el.data('a1') === true;
            var isA2Full = $el.data('a2') === true;

            var badgeKetua = isKetuaFull ? '<span class="px-1.5 py-0.5 mx-0.5 text-[10px] font-bold bg-red-100 text-red-700 rounded border border-red-200">Ketua ❌</span>' : '<span class="px-1.5 py-0.5 mx-0.5 text-[10px] font-bold bg-green-100 text-green-700 rounded border border-green-200">Ketua ✓</span>';
            var badgeA1 = isA1Full ? '<span class="px-1.5 py-0.5 mx-0.5 text-[10px] font-bold bg-red-100 text-red-700 rounded border border-red-200">A1 ❌</span>' : '<span class="px-1.5 py-0.5 mx-0.5 text-[10px] font-bold bg-green-100 text-green-700 rounded border border-green-200">A1 ✓</span>';
            var badgeA2 = isA2Full ? '<span class="px-1.5 py-0.5 mx-0.5 text-[10px] font-bold bg-red-100 text-red-700 rounded border border-red-200">A2 ❌</span>' : '<span class="px-1.5 py-0.5 mx-0.5 text-[10px] font-bold bg-green-100 text-green-700 rounded border border-green-200">A2 ✓</span>';

            var $state = $(
                '<div class="flex justify-between items-center w-full">' +
                    '<span class="font-bold text-gray-700">' + state.text + '</span>' +
                    '<div class="flex">' + badgeKetua + badgeA1 + badgeA2 + '</div>' +
                '</div>'
            );
            return $state;
        };

        // B. Inisialisasi Select2
        $('#kecamatanSelect').select2({
            templateResult: formatKecamatan,
            placeholder: "Ketik untuk mencari kecamatan...",
            allowClear: true
        });

        // C. Tampilkan Area Korcam berdasarkan Role
        roleSelect.on('change', function() {
            if ($(this).val() === 'korcam') {
                areaKorcam.removeClass('hidden');
                $('#kecamatanSelect').prop('required', true);
            } else {
                areaKorcam.addClass('hidden');
                $('#kecamatanSelect').prop('required', false);
                resetState();
            }
        });

        // D. Fungsi Reset Tombol
        function resetState() {
            msgBox.addClass('hidden');
            btnSubmit.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        }

        // E. Cek Ketersediaan (AJAX)
        function checkAvailability() {
            const kecId = $('#kecamatanSelect').val();
            const selectedJabatan = $('.korcam-radio:checked').val();

            if (roleSelect.val() === 'korcam' && kecId && selectedJabatan) {
                $.ajax({
                    url: `{{ route('user.check-korcam') }}?kecamatan_id=${kecId}&jabatan=${selectedJabatan}`,
                    type: 'GET',
                    success: function(data) {
                        if (data.exists) {
                            msgBox.removeClass('hidden');
                            btnSubmit.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                        } else {
                            resetState();
                        }
                    },
                    error: function() {
                        console.log('Skip check, biarkan backend handle');
                        resetState();
                    }
                });
            }
        }

        // Jalankan pengecekan setiap ada perubahan di Dropdown Select2 atau Radio Button
        $('#kecamatanSelect').on('change', checkAvailability);
        $('.korcam-radio').on('change', checkAvailability);
    });
</script>


@endsection