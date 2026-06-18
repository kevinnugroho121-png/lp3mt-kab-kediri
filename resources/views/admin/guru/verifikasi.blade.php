<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">Verifikasi Guru</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Nama Guru --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-black-800">{{ $guru->nama_lengkap }}</h1>
                    <p class="text-sm text-black-500 mt-1">
                        {{ $guru->lembaga->nama_lembaga }} &bull; 
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-800">
                            {{ $guru->jenis_guru }}
                        </span>
                    </p>
                </div>

                {{-- [PERBAIKAN] TOMBOL KEMBALI PINTAR --}}
                @php
                    $backRoute = route('guru.index'); // Default
                    if($guru->jenis_guru == 'MADIN') $backRoute = route('guru.madin');
                    if($guru->jenis_guru == 'TPQ')   $backRoute = route('guru.tpq');
                    if($guru->jenis_guru == 'PONPES')$backRoute = route('guru.ponpes');
                @endphp
                <a href="{{ $backRoute }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-xs font-bold text-black-700 shadow-sm hover:bg-gray-50 transition">
                    &larr; Kembali
                </a>
            </div>

            <form action="{{ route('guru.proses_verifikasi', $guru->id) }}" method="POST">
                @csrf
                <div class="space-y-8">
                    
                    {{-- 1. KTP --}}
                    <div class="bg-white rounded-xl shadow-sm border border-blue-200 overflow-hidden">
                        <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-black-800 flex items-center gap-2">
                                <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                                Dokumen KTP
                            </h3>
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-bold text-black-500 uppercase">Status:</label>
                                <select name="status_ktp" class="border-gray-300 rounded-lg text-sm font-bold focus:ring-blue-500 focus:border-blue-500">
                                    <option value="Pending" {{ $guru->status_ktp == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="Disetujui" {{ $guru->status_ktp == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                    <option value="Ditolak" {{ $guru->status_ktp == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-6 bg-gray-50 text-center">
                            @if($guru->file_ktp)
                                <iframe src="{{ asset('storage/' . $guru->file_ktp) }}" type="application/pdf" class="w-full h-[600px] border border-gray-300 rounded-lg bg-white shadow-inner"></iframe>
                            @else
                                <div class="h-40 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg text-black-400 italic bg-white">
                                    File KTP belum diupload.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. KK --}}
                    <div class="bg-white rounded-xl shadow-sm border border-green-200 overflow-hidden">
                        <div class="bg-green-50 px-6 py-4 border-b border-green-100 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-black-800 flex items-center gap-2">
                                <span class="bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                                Dokumen Kartu Keluarga
                            </h3>
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-bold text-black-500 uppercase">Status:</label>
                                <select name="status_kk" class="border-gray-300 rounded-lg text-sm font-bold focus:ring-green-500 focus:border-green-500">
                                    <option value="Pending" {{ $guru->status_kk == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="Disetujui" {{ $guru->status_kk == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                    <option value="Ditolak" {{ $guru->status_kk == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-6 bg-gray-50 text-center">
                            @if($guru->file_kk)
                                <iframe src="{{ asset('storage/' . $guru->file_kk) }}" type="application/pdf" class="w-full h-[600px] border border-gray-300 rounded-lg bg-white shadow-inner"></iframe>
                            @else
                                <div class="h-40 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg text-black-400 italic bg-white">
                                    File KK belum diupload.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 3. REKENING --}}
                    <div class="bg-white rounded-xl shadow-sm border border-purple-200 overflow-hidden">
                        <div class="bg-purple-50 px-6 py-4 border-b border-purple-100 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-black-800 flex items-center gap-2">
                                <span class="bg-purple-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                                Dokumen Buku Rekening
                            </h3>
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-bold text-black-500 uppercase">Status:</label>
                                <select name="status_bukurekening" class="border-gray-300 rounded-lg text-sm font-bold focus:ring-purple-500 focus:border-purple-500">
                                    <option value="Pending" {{ $guru->status_bukurekening == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="Disetujui" {{ $guru->status_bukurekening == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                    <option value="Ditolak" {{ $guru->status_bukurekening == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-6 bg-gray-50 text-center">
                            @if($guru->file_bukurekening)
                                <iframe src="{{ asset('storage/' . $guru->file_bukurekening) }}" type="application/pdf" class="w-full h-[600px] border border-gray-300 rounded-lg bg-white shadow-inner"></iframe>
                            @else
                                <div class="h-40 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg text-black-400 italic bg-white">
                                    File Rekening belum diupload.
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Footer Action --}}
                <div class="mt-8 bg-white p-6 rounded-xl shadow-lg border border-gray-200 sticky bottom-4 z-50">
                    <div class="flex flex-col md:flex-row gap-4 items-center">
                        <div class="flex-grow w-full">
                            <label class="block text-xs font-bold text-black-500 uppercase mb-1">Catatan Verifikasi (Opsional)</label>
                            <input type="text" name="catatan_verifikasi" value="{{ $guru->keterangan }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Tulis catatan jika ada dokumen yang ditolak atau perlu revisi...">
                        </div>
                        <div class="flex gap-3 w-full md:w-auto">
                            <a href="{{ $backRoute }}" class="flex-1 md:flex-none px-6 py-3 bg-gray-100 text-black-700 font-bold rounded-lg hover:bg-gray-200 transition text-center border border-gray-300">
                                Batal
                            </a>
                            <button type="submit" class="flex-1 md:flex-none px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-md transition transform hover:scale-105">
                                SIMPAN
                            </button>
                        </div>
                    </div>
                </div>
                <div class="h-10"></div> {{-- Spacer bawah agar tombol tidak tertutup --}}
            </form>
        </div>
    </div>
</x-app-layout>