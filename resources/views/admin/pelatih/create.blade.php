<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Coach Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Pesan Error Validasi (Muncul jika ada input salah) --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <strong class="font-bold block mb-1">Terjadi Kesalahan!</strong>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    {{-- FORM INPUT PELATIH --}}
                    {{-- PENTING: enctype="multipart/form-data" WAJIB ADA AGAR BISA UPLOAD FOTO --}}
                    <form action="{{ route('pelatih.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- ================================================= --}}
                        {{-- BAGIAN 1: AKUN LOGIN --}}
                        {{-- ================================================= --}}
                        <div class="mb-8 bg-green-50 p-6 rounded-xl border border-green-200">
                            <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center gap-2">
                                🔐 <span>Buat Akun Login Coach</span>
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Input Email --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Email Login <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" required 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition"
                                        placeholder="Contoh: coach.budi@gmail.com">
                                    <p class="text-xs text-gray-500 mt-1">Digunakan untuk login ke sistem.</p>
                                </div>

                                {{-- Input Password --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                                    <input type="text" name="password" required value="pelatih123"
                                        class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-600 shadow-sm focus:border-green-500 focus:ring-green-500 transition"
                                        placeholder="Minimal 8 karakter">
                                    <p class="text-xs text-gray-500 mt-1">Default: <b>pelatih123</b> (Bisa diganti).</p>
                                </div>
                            </div>
                        </div>

                        {{-- ================================================= --}}
                        {{-- BAGIAN 2: BIODATA COACH --}}
                        {{-- ================================================= --}}
                        <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                            📝 <span>Biodata Lengkap</span>
                        </h3>

                        <div class="grid grid-cols-1 gap-6">
                            
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap Coach <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition"
                                    placeholder="Contoh: Budi Santoso, S.Pd">
                            </div>

                            {{-- FOTO PROFIL (FITUR BARU) --}}
                            <div class="p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Profil (Opsional)</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="foto_profil" accept="image/*"
                                            class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-green-100 file:text-green-700
                                            hover:file:bg-green-200 transition cursor-pointer
                                            ">
                                        <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG. Maksimal 2MB.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Grid untuk Tgl Lahir & HP --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Tanggal Lahir --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition">
                                    <p class="text-xs text-green-600 mt-1 font-semibold">*Min. Umur 18 Tahun.</p>
                                </div>

                                {{-- No HP --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                                    <input type="number" name="no_hp" value="{{ old('no_hp') }}" required 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition"
                                        placeholder="08xxxxxxxxxx">
                                </div>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Status Coach <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition">
                                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="Cuti" {{ old('status') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                </select>
                            </div>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('pelatih.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-bold shadow-lg transform hover:-translate-y-0.5">
                                Simpan Data & Buat Akun
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>