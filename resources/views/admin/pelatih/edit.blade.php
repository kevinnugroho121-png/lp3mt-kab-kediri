<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Coach') }} : {{ $pelatih->nama_lengkap }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Error Message --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    <strong class="font-bold block mb-1">Ada yang salah!</strong>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">

                    {{-- FORM EDIT --}}
                    {{-- PENTING: Tambahkan enctype="multipart/form-data" --}}
                    <form action="{{ route('pelatih.update', $pelatih->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- ================================================= --}}
                        {{-- BAGIAN 1: INFO AKUN LOGIN (READ ONLY) --}}
                        {{-- ================================================= --}}
                        <div class="mb-8 bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <h3 class="text-lg font-bold text-gray-600 mb-4 flex items-center gap-2">
                                🔐 <span>Akun Login (Tidak Dapat Diubah Admin)</span>
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-500 mb-1">Email Login</label>
                                    <input type="text" value="{{ $pelatih->user->email ?? 'Akun terputus' }}" disabled
                                        class="w-full rounded-lg border-gray-300 bg-gray-200 text-gray-500 cursor-not-allowed shadow-sm">
                                </div>
                                <div class="flex items-end pb-1">
                                    <p class="text-xs text-gray-500 italic leading-relaxed">
                                        * Jika Coach lupa password, minta mereka reset sendiri atau hapus akun ini dan buat baru.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- ================================================= --}}
                        {{-- BAGIAN 2: BIODATA COACH (BISA DIEDIT) --}}
                        {{-- ================================================= --}}
                        <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                            📝 <span>Edit Biodata Coach</span>
                        </h3>

                        <div class="grid grid-cols-1 gap-6">
                            
                            {{-- 1. NAMA LENGKAP --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap Coach</label>
                                <input type="text" name="nama_lengkap" 
                                    value="{{ old('nama_lengkap', $pelatih->nama_lengkap) }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition">
                            </div>

                            {{-- 2. FOTO PROFIL (FITUR BARU) --}}
                            <div class="p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <label class="block text-sm font-bold text-gray-700 mb-3">Foto Profil</label>
                                <div class="flex items-center gap-6">
                                    
                                    {{-- Preview Foto Lama --}}
                                    <div class="shrink-0">
                                        @if($pelatih->foto_profil)
                                            <img src="{{ asset('storage/' . $pelatih->foto_profil) }}" alt="Foto Lama" 
                                                class="w-20 h-20 rounded-full object-cover border-2 border-white shadow-md">
                                        @else
                                            <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 border-2 border-white shadow-md">
                                                <span class="text-xs font-bold">No Foto</span>
                                            </div>
                                        @endif
                                        <p class="text-center text-xs text-gray-500 mt-1">Saat Ini</p>
                                    </div>

                                    {{-- Input File Baru --}}
                                    <div class="flex-1">
                                        <input type="file" name="foto_profil" accept="image/*"
                                            class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-green-100 file:text-green-700
                                            hover:file:bg-green-200 transition cursor-pointer">
                                        <p class="text-xs text-gray-500 mt-2">Upload foto baru jika ingin mengganti. Maksimal 2MB.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. TANGGAL LAHIR & NO HP --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" 
                                        value="{{ old('tanggal_lahir', $pelatih->tanggal_lahir) }}" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition">
                                    <p class="text-xs text-green-600 mt-1 font-semibold">*Min. Umur 18 Tahun.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nomor HP (WhatsApp)</label>
                                    <input type="number" name="no_hp" 
                                        value="{{ old('no_hp', $pelatih->no_hp) }}" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition">
                                </div>
                            </div>

                            {{-- 4. STATUS --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition">
                                    <option value="Aktif" {{ $pelatih->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ $pelatih->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="Cuti" {{ $pelatih->status == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                </select>
                            </div>
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div class="flex justify-end mt-8 gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('pelatih.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-bold shadow-lg transform hover:-translate-y-0.5">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>