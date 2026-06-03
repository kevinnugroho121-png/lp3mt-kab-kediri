<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data Lembaga') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER NAVIGASI --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Lembaga</h1>
                    <p class="text-sm text-gray-500 mt-1">Mode Hanya Baca (Read Only)</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('lembaga.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                        &larr; Kembali
                    </a>
                    <a href="{{ route('lembaga.edit', $lembaga->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-yellow-600 transition">
                        Edit Data
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-8">
                
                {{-- SECTION A: IDENTITAS --}}
                <div class="mb-10">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                        <span class="bg-blue-100 text-blue-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">A</span>
                        <h3 class="text-lg font-bold text-gray-800">Identitas & Lokasi</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Lembaga --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Nama Lembaga</label>
                            <input type="text" value="{{ $lembaga->nama_lembaga }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center font-bold text-gray-800" readonly>
                        </div>

                        {{-- Kecamatan & Desa --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Kecamatan</label>
                            <input type="text" value="{{ $lembaga->kecamatan->nama_kecamatan ?? '-' }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center text-gray-700" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Desa / Kelurahan</label>
                            <input type="text" value="{{ $lembaga->desa->nama_desa ?? '-' }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center text-gray-700" readonly>
                        </div>

                        {{-- Jenis & Ormas --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Jenis Lembaga</label>
                            <input type="text" value="{{ $lembaga->jenis_lembaga }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center text-gray-700" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Ormas Afiliasi</label>
                            <input type="text" value="{{ $lembaga->ormas ?? '-' }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center text-gray-700" readonly>
                        </div>

                        {{-- NSBQ & Alamat --}}
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Nomor Statistik (NSBQ)</label>
                            <input type="text" value="{{ $lembaga->nsbq ?? '-' }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center text-gray-700" readonly>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Alamat Lengkap</label>
                            <input type="text" value="{{ $lembaga->alamat ?? '-' }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center text-gray-700" readonly>
                        </div>
                    </div>
                </div>

                {{-- SECTION B: STATISTIK --}}
                <div class="mb-10">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                        <span class="bg-green-100 text-green-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">B</span>
                        <h3 class="text-lg font-bold text-gray-800">Statistik & Kontak</h3>
                    </div>
                    
                    {{-- Grid 4 Kolom Statistik --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 text-center">Jml Santri</label>
                            <input type="text" value="{{ $lembaga->jumlah_santri }}" class="w-full bg-blue-50 border-blue-200 rounded-lg text-sm font-bold text-center text-blue-700" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 text-center">Total Guru</label>
                            <input type="text" value="{{ $lembaga->jumlah_guru }}" class="w-full bg-green-50 border-green-200 rounded-lg text-sm font-bold text-center text-green-700" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 text-center">Guru Insentif</label>
                            <input type="text" value="{{ $lembaga->penerima_insentif }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm text-center" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 text-center">Non-Insentif</label>
                            <input type="text" value="{{ $lembaga->belum_menerima_insentif }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm text-center" readonly>
                        </div>
                    </div>
                    
                    {{-- Grid 3 Kolom Kontak --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Kepala Lembaga</label>
                            <input type="text" value="{{ $lembaga->kepala_lembaga }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center text-gray-700" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">No. Telp / WA</label>
                            <input type="text" value="{{ $lembaga->no_telp }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center text-gray-700" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Status</label>
                            <input type="text" value="{{ $lembaga->status }}" class="w-full {{ $lembaga->status == 'AKTIF' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} border-transparent rounded-lg text-sm py-2.5 text-center font-bold" readonly>
                        </div>
                    </div>
                </div>

                {{-- SECTION C: DOKUMEN --}}
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                        <span class="bg-purple-100 text-purple-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">C</span>
                        <h3 class="text-lg font-bold text-gray-800">Dokumen Legalitas</h3>
                    </div>

                    <div class="space-y-8">
                        {{-- 1. IJOP --}}
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-base font-bold text-gray-800">1. Izin Operasional (IJOP)</label>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ ($lembaga->status_ijop == 'Disetujui') ? 'bg-green-100 text-green-700' : (($lembaga->status_ijop == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    Status: {{ $lembaga->status_ijop ?? 'Pending' }}
                                </span>
                            </div>
                            
                            @if($lembaga->file_ijop)
                                <div class="w-full h-[600px] bg-white rounded-lg border border-gray-300">
                                    {{-- [PENTING] type="application/pdf" MENCEGAH DOWNLOAD OTOMATIS --}}
                                    <iframe src="{{ asset('storage/' . $lembaga->file_ijop) }}" type="application/pdf" class="w-full h-full rounded-lg"></iframe>
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-600 font-semibold bg-white inline-block px-4 py-1 rounded border">
                                        Masa Berlaku: {{ $lembaga->masa_berlaku_ijop ? $lembaga->masa_berlaku_ijop->format('d F Y') : '-' }} 
                                        s/d 
                                        {{ $lembaga->masa_berlaku_ijop ? $lembaga->masa_berlaku_ijop->addYears(5)->format('d F Y') : '-' }}
                                    </p>
                                </div>
                            @else
                                <div class="h-40 flex items-center justify-center bg-white border-2 border-dashed border-gray-300 rounded-lg text-gray-400 italic">
                                    File IJOP belum diupload.
                                </div>
                            @endif
                        </div>

                        {{-- 2. SUPER --}}
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-base font-bold text-gray-800">2. Surat Pernyataan (SUPER)</label>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ ($lembaga->status_super == 'Disetujui') ? 'bg-green-100 text-green-700' : (($lembaga->status_super == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    Status: {{ $lembaga->status_super ?? 'Pending' }}
                                </span>
                            </div>
                            
                            @if($lembaga->file_super)
                                <div class="w-full h-[600px] bg-white rounded-lg border border-gray-300">
                                    {{-- [PENTING] type="application/pdf" MENCEGAH DOWNLOAD OTOMATIS --}}
                                    <iframe src="{{ asset('storage/' . $lembaga->file_super) }}" type="application/pdf" class="w-full h-full rounded-lg"></iframe>
                                </div>
                            @else
                                <div class="h-40 flex items-center justify-center bg-white border-2 border-dashed border-gray-300 rounded-lg text-gray-400 italic">
                                    File SUPER belum diupload.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- CATATAN --}}
                @if($lembaga->keterangan)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg mt-6">
                    <h4 class="font-bold text-yellow-800 text-sm mb-1">Catatan / Keterangan:</h4>
                    <p class="text-sm text-gray-700">{{ $lembaga->keterangan }}</p>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>