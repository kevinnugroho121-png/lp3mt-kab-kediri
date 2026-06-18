<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            {{ __('Edit Foto Dokumentasi') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
                
                {{-- KOTAK INFORMASI --}}
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
                    <p class="text-sm text-blue-700 font-medium">Ubah keterangan atau ganti file gambar. Biarkan kolom foto kosong jika hanya ingin mengubah judulnya saja.</p>
                </div>

                <form action="{{ route('dokumentasi.update', $dok->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        {{-- KIRI: TAMPILAN FOTO LAMA --}}
                        <div class="flex flex-col items-center justify-center bg-slate-100 p-4 rounded-xl border border-slate-200 border-dashed">
                            <span class="text-xs font-bold text-slate-400 uppercase mb-3">Preview Foto Saat Ini</span>
                            <img src="{{ asset('storage/' . $dok->foto_path) }}" alt="Preview" class="w-full h-auto max-h-56 object-contain rounded shadow-sm bg-white p-1 border">
                        </div>

                        {{-- KANAN: FORM INPUT --}}
                        <div class="flex flex-col justify-center">
                            
                            {{-- Judul --}}
                            <div class="mb-5">
                                <label class="block text-xs font-bold text-black-600 uppercase mb-2">Judul / Keterangan Baru</label>
                                <input type="text" name="judul" value="{{ $dok->judul }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition">
                            </div>

                            {{-- Foto Baru --}}
                            <div class="mb-8">
                                <label class="block text-xs font-bold text-black-600 uppercase mb-1">Ganti Foto Baru <span class="text-black-400 lowercase font-normal">(Opsional)</span></label>
                                <input type="file" name="foto" accept="image/*" class="w-full text-sm text-black-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition mt-2 cursor-pointer">
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="flex gap-3 mt-auto">
                                <a href="{{ route('dokumentasi.index') }}" class="w-1/3 text-center bg-gray-200 text-black-700 py-2.5 rounded-lg text-sm font-bold hover:bg-gray-300 transition shadow-sm">Kembali</a>
                                <button type="submit" class="w-2/3 bg-blue-600 text-white py-2.5 rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-sm">Simpan Perubahan</button>
                            </div>
                            
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>