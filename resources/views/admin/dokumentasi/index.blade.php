<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Dokumentasi Landing Page') }}
        </h2>
    </x-slot>

    {{-- KITA TAMBAHKAN x-data UNTUK FITUR PREVIEW FOTO --}}
    <div class="py-6" x-data="{ previewOpen: false, previewSrc: '', previewTitle: '' }">
        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- PESAN SUKSES --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-sm rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- FORM TAMBAH FOTO --}}
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm sticky top-24">
                        <h3 class="font-bold text-gray-800 mb-4">Tambah Foto Baru</h3>
                        <form action="{{ route('dokumentasi.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Judul / Keterangan</label>
                                <input type="text" name="judul" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pilih Foto</label>
                                <input type="file" name="foto" required accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md text-sm font-bold hover:bg-blue-700 transition">Upload Foto</button>
                        </form>
                    </div>
                </div>

                {{-- DAFTAR FOTO --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left w-32">Foto</th>
                                    <th class="px-4 py-3 text-left">Judul Keterangan</th>
                                    <th class="px-4 py-3 text-center w-48">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($dokumentasis as $dok)
                                    <tr class="hover:bg-gray-50 transition">
                                        {{-- KOLOM FOTO (Bisa diklik untuk Preview) --}}
                                        <td class="px-4 py-3">
                                            <img src="{{ asset('storage/' . $dok->foto_path) }}" alt="Foto" 
                                                 @click="previewSrc = '{{ asset('storage/' . $dok->foto_path) }}'; previewTitle = '{{ $dok->judul }}'; previewOpen = true"
                                                 class="w-24 h-16 object-cover rounded shadow-sm cursor-pointer hover:opacity-80 transition transform hover:scale-105" title="Klik untuk memperbesar">
                                        </td>
                                        
                                        {{-- KOLOM JUDUL --}}
                                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $dok->judul }}</td>
                                        
                                        {{-- KOLOM AKSI (Preview, Edit, Hapus) --}}
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                
                                                {{-- Tombol Preview (Mata) --}}
                                                <button type="button" @click="previewSrc = '{{ asset('storage/' . $dok->foto_path) }}'; previewTitle = '{{ $dok->judul }}'; previewOpen = true" class="text-emerald-600 hover:text-emerald-800 font-bold text-xs underline">
                                                    Lihat
                                                </button>

                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('dokumentasi.edit', $dok->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs underline">
                                                    Edit
                                                </a>

                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('dokumentasi.destroy', $dok->id) }}" method="POST" onsubmit="return confirm('Yakin hapus foto ini?');" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs underline">Hapus</button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-12 text-center text-gray-400">Belum ada foto yang diupload.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- MODAL PREVIEW FOTO (Pop-Up) --}}
        {{-- ========================================================= --}}
        <div x-show="previewOpen" x-cloak class="fixed inset-0 z-[99] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4 transition-opacity">
            <div @click.outside="previewOpen = false" class="bg-white rounded-2xl p-4 max-w-4xl w-full shadow-2xl transform transition-all relative">
                
                {{-- Tombol Silang (Tutup) --}}
                <button @click="previewOpen = false" class="absolute -top-4 -right-4 bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-xl shadow-lg hover:bg-red-700 border-2 border-white transition">
                    &times;
                </button>

                {{-- Judul Modal --}}
                <div class="mb-4 text-center border-b pb-3">
                    <h3 class="font-extrabold text-xl text-gray-800" x-text="previewTitle"></h3>
                </div>

                {{-- Gambar Ukuran Penuh --}}
                <div class="p-2 flex justify-center bg-slate-100 rounded-xl border border-slate-200 overflow-hidden">
                    <img :src="previewSrc" class="w-auto h-auto max-h-[70vh] object-contain rounded-lg shadow-sm">
                </div>
                
            </div>
        </div>

    </div>
</x-app-layout>