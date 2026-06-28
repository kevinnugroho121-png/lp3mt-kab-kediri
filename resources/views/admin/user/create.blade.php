@extends('layouts.app')

@section('content')


<div class="py-2">
    <div class="max-w-full mx-auto px-1 sm:px-1 lg:px-1">
    
        {{-- HEADER EDGE-TO-EDGE --}}
        <div class="flex justify-between items-end mb-2">
            <div>
                <h1 class="text-2xl font-bold text-black-800 uppercase leading-none">Tambah Pengguna Baru</h1>
                <p class="text-[10px] font-bold text-gray-500 uppercase mt-1">Buat akun untuk akses sistem LP3MT</p>
            </div>
            <a href="{{ route('user.index') }}" class="text-xs font-bold text-gray-600 hover:text-gray-900 flex items-center gap-1 transition mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI
            </a>
        </div>

        <div class="bg-white border border-gray-600 shadow-sm p-3">




            <form action="{{ route('user.store') }}" method="POST" id="userForm">
                @csrf


                {{-- SECTION A: DATA LOGIN --}}
                <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600 mt-2">
                    <span class="bg-blue-100 text-blue-700 w-5 h-5 flex items-center justify-center rounded-full font-bold text-[10px]">A</span>
                    <h3 class="text-xs font-bold text-black-800 uppercase tracking-wide">Data Akun (Login)</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 px-1 mb-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Ahmad Admin" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm uppercase">
                        @error('name') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Email (Login)</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@lp3mt.com" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm lowercase">
                        @error('email') <span class="text-red-500 text-[10px] font-bold mt-1 block">⚠️ {{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Password</label>
                        <div class="relative">
                            <input id="new_password" type="password" name="password" required placeholder="Min 6 Karakter" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm pr-8">
                            <button type="button" onclick="toggleUserPassword()" class="absolute inset-y-0 right-0 px-2 flex items-center text-gray-600 hover:text-blue-600 transition h-[32px]">
                                <svg id="eye_icon_user" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        @error('password') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Jabatan / Role</label>
                        <select name="role" id="roleSelect" required class="w-full border border-gray-600 rounded-md px-1 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm bg-white uppercase">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>- PILIH JABATAN -</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>SUPER ADMIN</option>
                            <option value="verifikator" {{ old('role') == 'verifikator' ? 'selected' : '' }}>VERIFIKATOR</option>
                            <option value="korcam" {{ old('role') == 'korcam' ? 'selected' : '' }}>KORCAM</option>
                        </select>
                        @error('role') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>



                {{-- AREA KORCAM (SECTION B) --}}
                <div id="areaKorcam" class="hidden bg-gray-50 border border-gray-600 p-2 mb-4">
                    <div class="flex items-center gap-2 mb-2 pb-1 border-b border-gray-600">
                        <span class="bg-blue-100 text-blue-700 w-5 h-5 flex items-center justify-center rounded-full font-bold text-[10px]">B</span>
                        <h3 class="text-xs font-bold text-black-800 uppercase tracking-wide">Penugasan Wilayah (Khusus Korcam)</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 px-1">
                        {{-- Kecamatan --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Bertugas di Kecamatan</label>
                            <select name="kecamatan_id" id="kecamatanSelect" class="w-full border border-gray-600 rounded-md px-1 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 shadow-sm" style="width: 100%;">
                                <option value="" disabled selected>- CARI WILAYAH -</option>
                                @foreach($kecamatans as $kec)
                                    @php
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
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Posisi Tim (Maks 3)</label>
                            <div class="grid grid-cols-3 gap-1 h-[32px]">
                                {{-- Ketua --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="jabatan_korcam" value="Ketua" class="peer sr-only korcam-radio">
                                    <div class="border border-gray-600 bg-white hover:bg-blue-50 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition text-center shadow-sm flex items-center justify-center h-full rounded-md">
                                        <span class="block font-bold text-[10px] uppercase">Ketua</span>
                                    </div>
                                </label>
                                {{-- Anggota 1 --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="jabatan_korcam" value="Anggota 1" class="peer sr-only korcam-radio">
                                    <div class="border border-gray-600 bg-white hover:bg-blue-50 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition text-center shadow-sm flex items-center justify-center h-full rounded-md">
                                        <span class="block font-bold text-[10px] uppercase">Anggota 1</span>
                                    </div>
                                </label>
                                {{-- Anggota 2 --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="jabatan_korcam" value="Anggota 2" class="peer sr-only korcam-radio">
                                    <div class="border border-gray-600 bg-white hover:bg-blue-50 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition text-center shadow-sm flex items-center justify-center h-full rounded-md">
                                        <span class="block font-bold text-[10px] uppercase">Anggota 2</span>
                                    </div>
                                </label>
                            </div>
                            
                            {{-- PESAN ERROR --}}
                            <div id="availabilityMessage" class="mt-1 hidden px-2 py-0.5 rounded text-[10px] font-bold text-center bg-red-100 text-red-700 border border-red-300 uppercase">
                                ⚠️ Posisi Terisi! Pilih Lain.
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        {{-- STICKY FOOTER ACTION --}}
        <div class="mt-2 bg-gray-100 px-3 py-2 rounded-md border border-gray-600 sticky bottom-2 z-50 flex justify-end">
            <button type="button" onclick="document.getElementById('userForm').submit();" id="btnSubmit" class="px-6 py-1 h-[32px] text-xs font-bold text-white bg-green-600 rounded-md shadow-sm border border-green-700 hover:bg-green-700 uppercase transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                SIMPAN
            </button>
        </div>

    </div>
</div>




{{-- PANGGIL LIBRARY JQUERY & SELECT2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* CSS Select2 - Pakem Compact Grid UI */
    .select2-container .select2-selection--single { 
        height: 32px; 
        border-color: #9ca3af; /* border-gray-600 */
        border-radius: 0.375rem; /* rounded-md */
        font-size: 0.75rem; /* text-xs */
        font-weight: bold;
        text-transform: uppercase;
        color: #1f2937;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered { 
        line-height: 30px; 
        color: #1f2937; 
        padding-left: 8px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 30px; }
    .select2-dropdown { border-color: #9ca3af; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;}
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