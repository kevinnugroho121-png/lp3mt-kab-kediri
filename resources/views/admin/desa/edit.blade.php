<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Edit Data Desa
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Desa / Kelurahan</h1>
                    <p class="text-sm text-gray-500 mt-1">Ubah data wilayah desa.</p>
                </div>
                {{-- Tombol kembali akan diarahkan ke halaman desa dengan filter kecamatan yang sedang aktif --}}
                <a href="{{ route('desa.index', ['kecamatan_id' => $desa->kecamatan_id]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    &larr; Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <form action="{{ route('desa.update', $desa->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        {{-- 1. Pilih Kecamatan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                            <select name="kecamatan_id" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5" required>
                                @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $desa->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
                                        {{ $kecamatan->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kecamatan_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 2. Nama Desa --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Desa <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_desa" value="{{ old('nama_desa', $desa->nama_desa) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2.5 uppercase" required>
                            @error('nama_desa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('desa.index', ['kecamatan_id' => $desa->kecamatan_id]) }}" class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>