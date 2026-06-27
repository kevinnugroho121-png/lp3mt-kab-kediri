<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
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
                    <div class="bg-white p-6 rounded-xl border border-gray-600 shadow-sm sticky top-24">
                        <h3 class="font-bold text-black-800 mb-4">Tambah Foto Baru</h3>
                        <form action="{{ route('dokumentasi.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-black-500 uppercase mb-1">Judul / Keterangan</label>
                                <input type="text" name="judul" required class="w-full border border-gray-600 rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-black-500 uppercase mb-1">Pilih Foto</label>
                                <input type="file" name="foto" required accept="image/*" class="w-full text-sm text-black-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md text-sm font-bold hover:bg-blue-700 transition">Upload Foto</button>
                        </form>
                    </div>
                </div>

                {{-- DAFTAR FOTO --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border border-gray-600 shadow-sm overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left w-32">Foto</th>
                                    <th class="px-4 py-3 text-left">Judul Keterangan</th>
                                    <th class="px-4 py-3 text-center w-48">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @forelse($dokumentasis as $dok)
                                    <tr class="hover:bg-gray-100 transition">
                                        {{-- KOLOM FOTO (Bisa diklik untuk Preview) --}}
                                        <td class="px-4 py-3">
                                            <img src="{{ asset('storage/' . $dok->foto_path) }}" alt="Foto" 
                                                 @click="previewSrc = '{{ asset('storage/' . $dok->foto_path) }}'; previewTitle = '{{ $dok->judul }}'; previewOpen = true"
                                                 class="w-24 h-16 object-cover rounded shadow-sm cursor-pointer hover:opacity-80 transition transform hover:scale-105" title="Klik untuk memperbesar">
                                        </td>
                                        
                                        {{-- KOLOM JUDUL --}}
                                        <td class="px-4 py-3 font-semibold text-black-800">{{ $dok->judul }}</td>
                                        
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
                                                <form id="form-delete-dok-{{ $dok->id }}" action="{{ route('dokumentasi.destroy', $dok->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="triggerStatusUpdate('Yakin hapus foto ini?', 'form-delete-dok-{{ $dok->id }}')" class="text-red-600 hover:text-red-800 font-bold text-xs underline">Hapus</button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-12 text-center text-black-400">Belum ada foto yang diupload.</td>
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
                    <h3 class="font-extrabold text-xl text-black-800" x-text="previewTitle"></h3>
                </div>

                {{-- Gambar Ukuran Penuh --}}
                <div class="p-2 flex justify-center bg-slate-100 rounded-xl border border-slate-200 overflow-hidden">
                    <img :src="previewSrc" class="w-auto h-auto max-h-[70vh] object-contain rounded-lg shadow-sm">
                </div>
                
            </div>
        </div>

    </div>


    {{-- ================================================================= --}}
    {{-- 🧩 [MODAL & SCRIPT] CUSTOM CONFIRM UNTUK TOMBOL AKSI              --}}
    {{-- ================================================================= --}}
    
    <div id="custom-confirm-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
        <div class="bg-white rounded-md border border-gray-400 shadow-xl w-full max-w-sm p-4 transform scale-95 transition-transform duration-200">
            <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                <span class="flex items-center justify-center w-5 h-5 rounded-full border border-gray-800 text-[10px] font-bold text-gray-800">?</span>
                <span class="block text-xs font-bold text-black-800 uppercase">Konfirmasi Tindakan</span>
            </div>
            <p id="custom-confirm-message" class="text-xs font-bold text-gray-700 mb-5"></p>
            <div class="flex justify-end gap-2">
                <button id="custom-confirm-cancel" type="button" class="px-3 py-1 h-[32px] border border-gray-400 rounded-md text-[10px] font-bold text-gray-600 hover:bg-gray-100 uppercase transition">Batal</button>
                <button id="custom-confirm-ok" type="button" class="px-3 py-1 h-[32px] border border-green-600 bg-green-600 rounded-md text-[10px] font-bold text-white hover:bg-green-700 uppercase shadow-sm transition">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    <script>
        function showConfirmDialog(message, onConfirmCallback) {
            const modal = document.getElementById('custom-confirm-modal');
            const msgEl = document.getElementById('custom-confirm-message');
            const btnCancel = document.getElementById('custom-confirm-cancel');
            const btnOk = document.getElementById('custom-confirm-ok');

            msgEl.textContent = message;

            modal.classList.remove('hidden');
            setTimeout(() => { modal.firstElementChild.classList.replace('scale-95', 'scale-100'); }, 10);

            const closeModal = () => {
                modal.firstElementChild.classList.replace('scale-100', 'scale-95');
                setTimeout(() => { modal.classList.add('hidden'); }, 150);
                btnCancel.removeEventListener('click', handleCancel);
                btnOk.removeEventListener('click', handleOk);
            };

            const handleCancel = () => closeModal();
            const handleOk = () => {
                closeModal();
                if (typeof onConfirmCallback === 'function') onConfirmCallback(); 
            };

            btnCancel.addEventListener('click', handleCancel);
            btnOk.addEventListener('click', handleOk);
        }

        function triggerStatusUpdate(pesan, formId) {
            showConfirmDialog(pesan, function() {
                const formToSubmit = document.getElementById(formId);
                if(formToSubmit) {
                    formToSubmit.submit();
                } else {
                    console.error("Gagal: Form dengan ID '" + formId + "' tidak ditemukan.");
                }
            });
        }
    </script>
</x-app-layout>