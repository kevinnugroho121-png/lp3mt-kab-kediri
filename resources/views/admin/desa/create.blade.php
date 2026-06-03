<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Desa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- KOTAK FORM (CARD STYLE) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- JUDUL FORM --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Formulir Desa Baru</h3>
                        <p class="text-sm text-gray-500">Silakan lengkapi data desa di bawah ini.</p>
                    </div>

                    {{-- START FORM --}}
                    <form action="{{ route('desa.store') }}" method="POST">
                        @csrf

                        {{-- 1. INPUT NAMA DESA --}}
                        <div class="mb-5">
                            <label for="nama_desa" class="block mb-2 text-sm font-bold text-gray-700">Nama Desa / Kelurahan</label>
                            <input type="text" name="nama_desa" id="nama_desa" 
                                   value="{{ old('nama_desa') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" 
                                   placeholder="Contoh: Sukorejo" required autofocus>
                            
                            {{-- Pesan Error Validasi --}}
                            @error('nama_desa')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 2. INPUT KECAMATAN (LOGIKA CERDAS) --}}
                        <div class="mb-6">
                            <label for="kecamatan_id" class="block mb-2 text-sm font-bold text-gray-700">Kecamatan Induk</label>
                            
                            @if(Auth::user()->role == 'korcam')
                                {{-- TAMPILAN KHUSUS KORCAM (READONLY / TERKUNCI) --}}
                                <div class="relative">
                                    {{-- Input Visual (Hanya untuk dilihat, warna abu-abu) --}}
                                    <input type="text" value="{{ Auth::user()->kecamatan->nama_kecamatan ?? 'Error: Wilayah Tidak Ditemukan' }}" 
                                           class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed font-bold" 
                                           readonly>
                                    
                                    {{-- Input Hidden (Nilai Asli yang dikirim ke Controller) --}}
                                    <input type="hidden" name="kecamatan_id" value="{{ Auth::user()->kecamatan_id }}">
                                    
                                    <p class="mt-1 text-xs text-green-600 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Wilayah terkunci sesuai akun login.
                                    </p>
                                </div>
                            @else
                                {{-- TAMPILAN ADMIN (DROPDOWN BEBAS) --}}
                                <select name="kecamatan_id" id="kecamatan_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach($kecamatans as $kec)
                                        <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                            {{ $kec->nama_kecamatan }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            
                            @error('kecamatan_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 3. TOMBOL AKSI --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                            {{-- Tombol Batal --}}
                            <a href="{{ route('desa.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">
                                Batal
                            </a>
                            {{-- Tombol Simpan --}}
                            <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-bold rounded-lg text-sm px-5 py-2.5 shadow-md flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Desa
                            </button>
                        </div>

                    </form>
                    {{-- END FORM --}}

                </div>
            </div>

        </div>
    </div>
</x-app-layout>