<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Detail Data Guru
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER NAVIGATION --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $guru->nama_lengkap }}</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $guru->lembaga->nama_lembaga ?? '-' }} &bull; {{ $guru->jenis_guru }}
                    </p>
                </div>
                <div class="flex gap-2">
                    {{-- TOMBOL KEMBALI PINTAR --}}
                    @php
                        $backRoute = route('guru.index');
                        if($guru->jenis_guru == 'MADIN') $backRoute = route('guru.madin');
                        if($guru->jenis_guru == 'TPQ')   $backRoute = route('guru.tpq');
                        if($guru->jenis_guru == 'PONPES')$backRoute = route('guru.ponpes');
                    @endphp
                    <a href="{{ $backRoute }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                        &larr; Kembali
                    </a>
                    
                    {{-- Tombol Edit (Hanya jika bukan Verifikator) --}}
                    @if(Auth::user()->role != 'verifikator')
                        <a href="{{ route('guru.edit', $guru->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-yellow-600 transition">
                            Edit Data
                        </a>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-8">
                
                {{-- SECTION A: DATA PRIBADI --}}
                <div class="mb-10">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                        <span class="bg-gray-200 text-gray-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">A</span>
                        <h3 class="text-lg font-bold text-gray-800">Data Pribadi & Kepegawaian</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Nama Lengkap</label>
                            <input type="text" value="{{ $guru->nama_lengkap }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center font-bold text-gray-800" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">NIK</label>
                            <input type="text" value="{{ $guru->nik }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Jenis Kelamin</label>
                            <input type="text" value="{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Status Kepegawaian</label>
                            <input type="text" value="{{ $guru->status_kepegawaian }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Status Sertifikasi</label>
                            <input type="text" value="{{ $guru->status_sertifikasi }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>

                        {{-- [BARU] STATUS INSENTIF --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Status Penerima Insentif</label>
                            @if($guru->penerima_insentif == 1)
                                <div class="w-full bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm py-2.5 text-center font-bold">
                                    ✅ YA, MENERIMA INSENTIF
                                </div>
                            @else
                                <div class="w-full bg-gray-50 border border-gray-300 text-gray-500 rounded-lg text-sm py-2.5 text-center font-bold">
                                    ❌ TIDAK / BELUM MENERIMA
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Tempat Lahir</label>
                            <input type="text" value="{{ $guru->tempat_lahir }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Tanggal Lahir</label>
                            <input type="text" value="{{ $guru->tanggal_lahir ? $guru->tanggal_lahir->format('d-m-Y') : '-' }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Nama Ibu Kandung</label>
                            <input type="text" value="{{ $guru->nama_ibu_kandung }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Agama</label>
                            <input type="text" value="{{ $guru->agama }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>
                    </div>
                </div>

                {{-- SECTION B: KELEMBAGAAN --}}
                <div class="mb-10">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                        <span class="bg-gray-200 text-gray-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">B</span>
                        <h3 class="text-lg font-bold text-gray-800">Kelembagaan & Kontak</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Lembaga Tempat Mengajar</label>
                            <input type="text" value="{{ $guru->lembaga->nama_lembaga ?? '-' }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center font-bold text-blue-800" readonly>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Alamat Lengkap</label>
                            <input type="text" value="{{ $guru->alamat_ktp }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Desa</label>
                            <input type="text" value="{{ $guru->desa }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Kecamatan</label>
                            <input type="text" value="{{ $guru->kecamatan }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Nomor HP</label>
                            <input type="text" value="{{ $guru->no_hp }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1 text-center">Nomor Rekening</label>
                            <input type="text" value="{{ $guru->nomor_rekening }}" class="w-full bg-gray-50 border-gray-300 rounded-lg text-sm py-2.5 text-center font-mono font-bold" readonly>
                        </div>
                    </div>
                </div>

                {{-- SECTION C: DOKUMEN --}}
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                        <span class="bg-gray-200 text-gray-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">C</span>
                        <h3 class="text-lg font-bold text-gray-800">Dokumen Legalitas</h3>
                    </div>

                    <div class="space-y-8">
                        {{-- 1. KTP --}}
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-base font-bold text-gray-800">1. Scan KTP Asli</label>
                                @php
                                    $sKtp = $guru->status_ktp;
                                    $colorKtp = ($sKtp == 'Disetujui') ? 'bg-green-100 text-green-700' : (($sKtp == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $colorKtp }}">
                                    Status: {{ $sKtp }}
                                </span>
                            </div>
                            
                            @if($guru->file_ktp)
                                <iframe src="{{ asset('storage/' . $guru->file_ktp) }}" type="application/pdf" class="w-full h-[500px] border border-gray-300 rounded-lg bg-white"></iframe>
                            @else
                                <div class="h-32 flex items-center justify-center bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg text-gray-400 italic">File KTP belum diupload.</div>
                            @endif
                        </div>

                        {{-- 2. KK --}}
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-base font-bold text-gray-800">2. Scan Kartu Keluarga</label>
                                @php
                                    $sKk = $guru->status_kk;
                                    $colorKk = ($sKk == 'Disetujui') ? 'bg-green-100 text-green-700' : (($sKk == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $colorKk }}">
                                    Status: {{ $sKk }}
                                </span>
                            </div>
                            
                            @if($guru->file_kk)
                                <iframe src="{{ asset('storage/' . $guru->file_kk) }}" type="application/pdf" class="w-full h-[500px] border border-gray-300 rounded-lg bg-white"></iframe>
                            @else
                                <div class="h-32 flex items-center justify-center bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg text-gray-400 italic">File KK belum diupload.</div>
                            @endif
                        </div>

                        {{-- 3. REKENING --}}
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-base font-bold text-gray-800">3. Scan Buku Rekening</label>
                                @php
                                    $sRek = $guru->status_bukurekening;
                                    $colorRek = ($sRek == 'Disetujui') ? 'bg-green-100 text-green-700' : (($sRek == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $colorRek }}">
                                    Status: {{ $sRek }}
                                </span>
                            </div>
                            
                            @if($guru->file_bukurekening)
                                <iframe src="{{ asset('storage/' . $guru->file_bukurekening) }}" type="application/pdf" class="w-full h-[500px] border border-gray-300 rounded-lg bg-white"></iframe>
                            @else
                                <div class="h-32 flex items-center justify-center bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg text-gray-400 italic">File Rekening belum diupload.</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- CATATAN --}}
                @if($guru->keterangan)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg mt-6">
                    <h4 class="font-bold text-yellow-800 text-sm mb-1">Catatan / Keterangan Verifikator:</h4>
                    <p class="text-sm text-gray-700">{{ $guru->keterangan }}</p>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>