<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Dokumen Lembaga') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER INFO --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $lembaga->nama_lembaga }}</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        <span class="font-semibold text-blue-600">{{ $lembaga->jenis_lembaga }}</span> | 
                        {{ $lembaga->desa->nama_desa }}, Kec. {{ $lembaga->kecamatan->nama_kecamatan }}
                    </p>
                </div>
                <a href="{{ route('lembaga.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    &larr; Kembali
                </a>
            </div>

            <form action="{{ route('lembaga.proses_verifikasi', $lembaga->id) }}" method="POST">
                @csrf
                
                <div class="space-y-8">
                    
                    {{-- 1. KARTU VERIFIKASI IJOP --}}
                    <div class="bg-white rounded-xl shadow-sm border border-blue-200 overflow-hidden">
                        {{-- Header Kartu --}}
                        <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex items-center gap-3">
                                <span class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">1</span>
                                <h3 class="font-bold text-lg text-gray-800">Izin Operasional (IJOP)</h3>
                            </div>
                            
                            {{-- Dropdown Status --}}
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-bold text-gray-600">Status:</label>
                                <select name="status_ijop" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                                    <option value="Pending" {{ $lembaga->status_ijop == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="Disetujui" {{ $lembaga->status_ijop == 'Disetujui' ? 'selected' : '' }}>✅ DISETUJUI</option>
                                    <option value="Ditolak" {{ $lembaga->status_ijop == 'Ditolak' ? 'selected' : '' }}>❌ DITOLAK</option>
                                </select>
                            </div>
                        </div>

                        {{-- Body: Preview PDF --}}
                        <div class="p-6 bg-gray-50">
                            @if($lembaga->file_ijop)
                                {{-- [PERBAIKAN] Tambah type="application/pdf" --}}
                                <iframe src="{{ asset('storage/' . $lembaga->file_ijop) }}" type="application/pdf" class="w-full h-[600px] border border-gray-300 rounded-lg bg-white shadow-inner"></iframe>
                                <div class="mt-2 text-center text-sm text-gray-500">
                                    Masa Berlaku: <b>{{ $lembaga->masa_berlaku_ijop ? $lembaga->masa_berlaku_ijop->format('d F Y') : '-' }}</b> 
                                    s/d 
                                    <b>{{ $lembaga->masa_berlaku_ijop ? $lembaga->masa_berlaku_ijop->addYears(5)->format('d F Y') : '-' }}</b>
                                </div>
                            @else
                                <div class="h-40 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg">
                                    <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-gray-500 font-medium">File IJOP belum diupload oleh lembaga.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. KARTU VERIFIKASI SUPER --}}
                    <div class="bg-white rounded-xl shadow-sm border border-purple-200 overflow-hidden">
                        {{-- Header Kartu --}}
                        <div class="bg-purple-50 px-6 py-4 border-b border-purple-100 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex items-center gap-3">
                                <span class="bg-purple-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">2</span>
                                <h3 class="font-bold text-lg text-gray-800">Surat Pernyataan (SUPER)</h3>
                            </div>
                            
                            {{-- Dropdown Status --}}
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-bold text-gray-600">Status:</label>
                                <select name="status_super" class="border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500 font-bold">
                                    <option value="Pending" {{ $lembaga->status_super == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="Disetujui" {{ $lembaga->status_super == 'Disetujui' ? 'selected' : '' }}>✅ DISETUJUI</option>
                                    <option value="Ditolak" {{ $lembaga->status_super == 'Ditolak' ? 'selected' : '' }}>❌ DITOLAK</option>
                                </select>
                            </div>
                        </div>

                        {{-- Body: Preview PDF --}}
                        <div class="p-6 bg-gray-50">
                            @if($lembaga->file_super)
                                {{-- [PERBAIKAN] Tambah type="application/pdf" --}}
                                <iframe src="{{ asset('storage/' . $lembaga->file_super) }}" type="application/pdf" class="w-full h-[600px] border border-gray-300 rounded-lg bg-white shadow-inner"></iframe>
                            @else
                                <div class="h-40 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg">
                                    <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-gray-500 font-medium">File SUPER belum diupload oleh lembaga.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- FOOTER: CATATAN & SIMPAN --}}
                <div class="mt-8 bg-white p-6 rounded-xl shadow-lg border border-gray-200 sticky bottom-4 z-50">
                    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                        <div class="flex-grow w-full">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan Verifikator (Jika Ditolak/Revisi)</label>
                            <input type="text" name="catatan_verifikasi" value="{{ $lembaga->keterangan }}" 
                                   class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 placeholder-gray-400" 
                                   placeholder="Contoh: Scan IJOP buram, mohon upload ulang...">
                        </div>
                        <button type="submit" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition flex items-center justify-center gap-2 whitespace-nowrap mt-5 md:mt-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            SIMPAN HASIL
                        </button>
                    </div>
                </div>
                {{-- Spacer --}}
                <div class="h-10"></div>

            </form>
        </div>
    </div>
</x-app-layout>