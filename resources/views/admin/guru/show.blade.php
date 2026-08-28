<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            Detail Data Guru
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-full mx-auto px-1 sm:px-1 lg:px-1">
            
            {{-- HEADER NAVIGATION --}}
            <div class="flex justify-between items-center mb-2">
                <div>
                    <h1 class="text-2xl font-bold text-black-800 uppercase">DETAIL DATA GURU: {{ $guru->nama_lengkap }}</h1>


                    <p class="text-sm text-black-500 mt-1">
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
                    <a href="{{ $backRoute }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-black-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
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
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                        <span class="bg-gray-200 text-black-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">A</span>
                        <h3 class="text-base font-bold text-black-800">DATA DIRI GURU</h3>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-3 gap-x-4 px-2">
                        <div class="md:col-span-2 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Nama Lengkap (Sesuai KTP)</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->nama_lengkap }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">NIK (16 Digit)</span>
                            <span class="text-sm font-bold text-black-800">{{ $guru->nik }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Jenis Kelamin</span>
                            <span class="text-sm font-bold text-black-800">{{ $guru->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</span>
                        </div>

                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Status Guru</span>
                            <span class="text-sm font-bold text-black-800">{{ $guru->status_kepegawaian }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Status Sertifikasi</span>
                            <span class="text-sm font-bold text-black-800">{{ $guru->status_sertifikasi }}</span>
                        </div>
                        <div class="md:col-span-2 border-b border-gray-200 pb-1 ">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Status Penerima Insentif</span>
                            @if($guru->penerima_insentif == 1)
                                <span class="text-xs font-bold text-green-600">✅ YA, BERHAK MENERIMA INSENTIF</span>
                            @else
                                <span class="text-xs font-bold text-red-600">❌ TIDAK BERHAK MENERIMA INSENTIF</span>
                            @endif
                        </div>

                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Tempat Lahir</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->tempat_lahir }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Tanggal Lahir</span>

                            <span class="text-sm font-bold text-black-800">
                                {{ $guru->tanggal_lahir ? \Carbon\Carbon::parse($guru->tanggal_lahir)->format('d-m-Y') : '-' }}
                            </span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Nama Ibu Kandung</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->nama_ibu_kandung }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Agama</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->agama }}</span>
                        </div>

                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Desa / Kelurahan (KTP)</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->desa }}</span>
                        </div>

                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Kecamatan (KTP)</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->kecamatan }}</span>
                        </div>
                        
                        

                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Kabupaten</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->kabupaten ?? 'KEDIRI' }}</span>
                        </div>

                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Nomor HP</span>
                            <span class="text-sm font-bold text-black-800">{{ $guru->no_hp }}</span>
                        </div>

                        

                        <div class="md:col-span-3  border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Alamat Guru (Sesuai KTP)</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->alamat_ktp }}</span>
                        </div>



                        
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Nomor Rekening BANK JATIM</span>
                            <span class="text-sm font-bold text-black-800">{{ $guru->nomor_rekening }}</span>
                        </div>


                    </div>
                </div>



                {{-- SECTION B: KELEMBAGAAN --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                        <span class="bg-gray-200 text-black-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">B</span>
                        <h3 class="text-base font-bold text-black-800">LEMBAGA TEMPAT MENGAJAR</h3>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-3 gap-x-4 px-2">
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Jenis Lembaga</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->jenis_guru }}</span>
                        </div>

                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Lembaga Tempat Mengajar</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->lembaga->nama_lembaga ?? '-' }}</span>
                        </div>

                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Kecamatan Lembaga</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->lembaga->kecamatan->nama_kecamatan ?? '-' }}</span>
                        </div>

                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Desa / Kelurahan Lembaga</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $guru->lembaga->desa->nama_desa ?? '-' }}</span>
                        </div>    

                        

                                           




                    </div>
                </div>

                {{-- SECTION C: DOKUMEN --}}
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                        <span class="bg-gray-200 text-black-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">C</span>
                        <h3 class="text-base font-bold text-black-800">DOKUMEN LEGALITAS</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        {{-- 1. KTP --}}
                        <div class="bg-gray-50 p-2 rounded-lg border border-gray-300 shadow-sm">
                            <div class="flex justify-between items-center mb-2 border-b border-gray-200 pb-1">
                                <label class="block text-xs font-bold text-black-800">1. Scan KTP Asli</label>
                                @php
                                    $sKtp = $guru->status_ktp;
                                    $colorKtp = ($sKtp == 'Disetujui') ? 'bg-green-100 text-green-700' : (($sKtp == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $colorKtp }}">Status: {{ $sKtp }}</span>
                            </div>
                            @if($guru->file_ktp)
                                <iframe src="{{ asset('dokumen/' . $guru->file_ktp) }}" type="application/pdf" class="w-full h-[250px] border border-gray-300 rounded bg-white"></iframe>
                            @else
                                <div class="h-[250px] flex items-center justify-center bg-gray-100 border border-dashed border-gray-400 rounded text-black-400 text-xs italic">Belum diupload</div>
                            @endif
                        </div>

                        {{-- 2. KK --}}
                        <div class="bg-gray-50 p-2 rounded-lg border border-gray-300 shadow-sm">
                            <div class="flex justify-between items-center mb-2 border-b border-gray-200 pb-1">
                                <label class="block text-xs font-bold text-black-800">2. Scan Kartu Keluarga</label>
                                @php
                                    $sKk = $guru->status_kk;
                                    $colorKk = ($sKk == 'Disetujui') ? 'bg-green-100 text-green-700' : (($sKk == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $colorKk }}">Status: {{ $sKk }}</span>
                            </div>
                            @if($guru->file_kk)
                                <iframe src="{{ asset('dokumen/' . $guru->file_kk) }}" type="application/pdf" class="w-full h-[250px] border border-gray-300 rounded bg-white"></iframe>
                            @else
                                <div class="h-[250px] flex items-center justify-center bg-gray-100 border border-dashed border-gray-400 rounded text-black-400 text-xs italic">Belum diupload</div>
                            @endif
                        </div>

                        {{-- 3. REKENING --}}
                        <div class="bg-gray-50 p-2 rounded-lg border border-gray-300 shadow-sm">
                            <div class="flex justify-between items-center mb-2 border-b border-gray-200 pb-1">
                                <label class="block text-xs font-bold text-black-800">3. Scan Buku Rekening</label>
                                @php
                                    $sRek = $guru->status_bukurekening;
                                    $colorRek = ($sRek == 'Disetujui') ? 'bg-green-100 text-green-700' : (($sRek == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $colorRek }}">Status: {{ $sRek }}</span>
                            </div>
                            @if($guru->file_bukurekening)
                                <iframe src="{{ asset('dokumen/' . $guru->file_bukurekening) }}" type="application/pdf" class="w-full h-[250px] border border-gray-300 rounded bg-white"></iframe>
                            @else
                                <div class="h-[250px] flex items-center justify-center bg-gray-100 border border-dashed border-gray-400 rounded text-black-400 text-xs italic">Belum diupload</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- CATATAN --}}
                @if($guru->keterangan)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg mt-6">
                    <h4 class="font-bold text-yellow-800 text-sm mb-1">Catatan / Keterangan Verifikator:</h4>
                    <p class="text-sm text-black-700">{{ $guru->keterangan }}</p>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>