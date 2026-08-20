<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            {{ __('Detail Data Lembaga') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-full mx-auto px-1 sm:px-1 lg:px-1">
            
            {{-- HEADER NAVIGASI --}}
            <div class="flex justify-between items-center mb-2">
                <div>
                    <h1 class="text-2xl font-bold text-black-800 uppercase">DETAIL LEMBAGA: {{ $lembaga->nama_lembaga }}</h1>
                    <p class="text-sm text-black-500 mt-1">Mode Hanya Baca (Read Only)</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('lembaga.index') }}" class="inline-flex items-center px-4 py-1.5 bg-white border border-gray-400 rounded-md font-bold text-xs text-black-700 uppercase shadow-sm hover:bg-gray-100 transition">&larr; Kembali</a>
                    @if(Auth::user()->role != 'verifikator')
                        <a href="{{ route('lembaga.edit', $lembaga->id) }}" class="inline-flex items-center px-4 py-1.5 bg-yellow-500 border border-transparent rounded-md font-bold text-xs text-white uppercase shadow-sm hover:bg-yellow-600 transition">Edit Data</a>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm border border-gray-400 rounded-lg p-3">
                
                {{-- SECTION A: IDENTITAS & LOKASI --}}
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                        <span class="bg-gray-200 text-black-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">A</span>
                        <h3 class="text-base font-bold text-black-800">Identitas & Lokasi</h3>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-3 gap-x-4 px-2">
                        <div class="md:col-span-2 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Nama Lembaga</span>
                            <span class="text-sm font-black text-black-800 uppercase">{{ $lembaga->nama_lembaga }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Jenis Lembaga</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $lembaga->jenis_lembaga }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Ormas Afiliasi</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $lembaga->ormas ?? '-' }}</span>
                        </div>

                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Kecamatan</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $lembaga->kecamatan->nama_kecamatan ?? '-' }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Desa / Kelurahan</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $lembaga->desa->nama_desa ?? '-' }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Nomor Statistik (NSBQ)</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $lembaga->nsbq ?? '-' }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Alamat Lengkap</span>
                            <span class="text-sm font-bold text-black-800 uppercase">
                                {{ (!empty($lembaga->alamat) && $lembaga->alamat !== '-') ? $lembaga->alamat : (($lembaga->desa->nama_desa ?? '-') . ', KEC. ' . ($lembaga->kecamatan->nama_kecamatan ?? '-')) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- SECTION B: STATISTIK & KONTAK --}}
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                        <span class="bg-gray-200 text-black-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">B</span>
                        <h3 class="text-base font-bold text-black-800">Statistik & Kontak</h3>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-3 gap-x-4 px-2">
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Jumlah Santri</span>
                            <span class="text-sm font-black text-blue-700">{{ $lembaga->jumlah_santri }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Total Guru</span>
                            <span class="text-sm font-black text-green-700">{{ $lembaga->jumlah_guru }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Guru Insentif</span>
                            <span class="text-sm font-bold text-black-800">{{ $lembaga->penerima_insentif }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Non-Insentif</span>
                            <span class="text-sm font-bold text-black-800">{{ $lembaga->belum_menerima_insentif }}</span>
                        </div>

                        <div class="md:col-span-2 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Kepala Lembaga</span>
                            <span class="text-sm font-bold text-black-800 uppercase">{{ $lembaga->kepala_lembaga }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">No. Telp / WA</span>
                            <span class="text-sm font-bold text-black-800">{{ $lembaga->no_telp }}</span>
                        </div>
                        <div class="md:col-span-1 border-b border-gray-200 pb-1">
                            <span class="block text-[10px] font-bold text-gray-500 uppercase">Status Operasional</span>
                            <span class="text-xs font-black {{ $lembaga->status == 'AKTIF' ? 'text-green-600' : 'text-red-600' }}">{{ $lembaga->status }}</span>
                        </div>
                    </div>
                </div>

                {{-- SECTION C: DOKUMEN & FOTO --}}
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-3 pb-1 border-b border-gray-600">
                        <span class="bg-gray-200 text-black-700 w-6 h-6 flex items-center justify-center rounded-full font-bold text-[10px]">C</span>
                        <h3 class="text-base font-bold text-black-800">Dokumen & Foto Lapangan</h3>
                    </div>

                    {{-- DOKUMEN PDF (GRID 2x2) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        {{-- 1. IJOP --}}
                        <div class="bg-gray-50 p-2 rounded-lg border border-gray-300 shadow-sm">
                            <div class="flex justify-between items-center mb-2 border-b border-gray-200 pb-1">
                                <label class="block text-xs font-bold text-black-800">1. Scan IJOP Asli</label>
                                @php $cIjop = ($lembaga->status_ijop == 'Disetujui') ? 'bg-green-100 text-green-700' : (($lembaga->status_ijop == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'); @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $cIjop }}">Status: {{ $lembaga->status_ijop ?? 'Pending' }}</span>
                            </div>
                            <div class="mb-2 text-[10px] font-bold text-black-600">
                                Fisik: <span class="text-black-800">{{ $lembaga->ijop }}</span> | Berlaku: <span class="text-black-800">{{ $lembaga->masa_berlaku_ijop ? $lembaga->masa_berlaku_ijop->format('d/m/Y') : '-' }}</span>
                            </div>
                            @if($lembaga->file_ijop)
                                <iframe src="{{ asset('storage/' . $lembaga->file_ijop) }}#view=FitH" type="application/pdf" class="w-full h-[250px] border border-gray-400 rounded bg-white"></iframe>
                            @else
                                <div class="h-[250px] flex items-center justify-center bg-gray-100 border border-dashed border-gray-400 rounded text-black-400 text-xs italic">Belum diupload</div>
                            @endif
                        </div>

                        {{-- 2. SKD --}}
                        <div class="bg-gray-50 p-2 rounded-lg border border-gray-300 shadow-sm">
                            <div class="flex justify-between items-center mb-2 border-b border-gray-200 pb-1">
                                <label class="block text-xs font-bold text-black-800">2. Scan SKD</label>
                                @php $cSkd = ($lembaga->status_skd == 'Disetujui') ? 'bg-green-100 text-green-700' : (($lembaga->status_skd == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'); @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $cSkd }}">Status: {{ $lembaga->status_skd ?? 'Pending' }}</span>
                            </div>
                            <div class="mb-2 h-[15px]"></div>
                            @if($lembaga->file_skd)
                                <iframe src="{{ asset('storage/' . $lembaga->file_skd) }}#view=FitH" type="application/pdf" class="w-full h-[250px] border border-gray-400 rounded bg-white"></iframe>
                            @else
                                <div class="h-[250px] flex items-center justify-center bg-gray-100 border border-dashed border-gray-400 rounded text-black-400 text-xs italic">Belum diupload</div>
                            @endif
                        </div>

                        {{-- 3. SPTJM --}}
                        <div class="bg-gray-50 p-2 rounded-lg border border-gray-300 shadow-sm">
                            <div class="flex justify-between items-center mb-2 border-b border-gray-200 pb-1">
                                <label class="block text-xs font-bold text-black-800">3. Scan SPTJM Mutlak</label>
                                @php $cSuper = ($lembaga->status_super == 'Disetujui') ? 'bg-green-100 text-green-700' : (($lembaga->status_super == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'); @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $cSuper }}">Status: {{ $lembaga->status_super ?? 'Pending' }}</span>
                            </div>
                            <div class="mb-2 h-[15px]"></div>
                            @if($lembaga->file_super)
                                <iframe src="{{ asset('storage/' . $lembaga->file_super) }}#view=FitH" type="application/pdf" class="w-full h-[250px] border border-gray-400 rounded bg-white"></iframe>
                            @else
                                <div class="h-[250px] flex items-center justify-center bg-gray-100 border border-dashed border-gray-400 rounded text-black-400 text-xs italic">Belum diupload</div>
                            @endif
                        </div>

                        {{-- 4. SKAM --}}
                        <div class="bg-gray-50 p-2 rounded-lg border border-gray-300 shadow-sm">
                            <div class="flex justify-between items-center mb-2 border-b border-gray-200 pb-1">
                                <label class="block text-xs font-bold text-black-800">4. Scan SKAM</label>
                                @php $cSkam = ($lembaga->status_skam == 'Disetujui') ? 'bg-green-100 text-green-700' : (($lembaga->status_skam == 'Ditolak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'); @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $cSkam }}">Status: {{ $lembaga->status_skam ?? 'Pending' }}</span>
                            </div>
                            <div class="mb-2 h-[15px]"></div>
                            @if($lembaga->file_skam)
                                <iframe src="{{ asset('storage/' . $lembaga->file_skam) }}#view=FitH" type="application/pdf" class="w-full h-[250px] border border-gray-400 rounded bg-white"></iframe>
                            @else
                                <div class="h-[250px] flex items-center justify-center bg-gray-100 border border-dashed border-gray-400 rounded text-black-400 text-xs italic">Belum diupload</div>
                            @endif
                        </div>
                    </div>

                    {{-- FOTO LAMA (2x2) --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="border border-gray-300 rounded-lg p-2 bg-gray-50 flex flex-col items-center">
                            <label class="text-[10px] font-bold text-black-700 uppercase mb-1">A. Profil Lembaga</label>
                            <div class="w-full h-32 bg-gray-200 border border-gray-400 rounded-md overflow-hidden flex justify-center items-center">
                                @if($lembaga->foto_lembaga)
                                    <img src="{{ asset('storage/' . $lembaga->foto_lembaga) }}" class="object-cover w-full h-full cursor-pointer hover:scale-105 transition" onclick="bukaModalGambar('{{ asset('storage/' . $lembaga->foto_lembaga) }}', 'A. Foto Profil Lembaga')">
                                @else
                                    <span class="text-black-400 text-[11px]">Belum Ada</span>
                                @endif
                            </div>
                        </div>
                        <div class="border border-gray-300 rounded-lg p-2 bg-gray-50 flex flex-col items-center">
                            <label class="text-[10px] font-bold text-black-700 uppercase mb-1">B. Papan Nama</label>
                            <div class="w-full h-32 bg-gray-200 border border-gray-400 rounded-md overflow-hidden flex justify-center items-center">
                                @if($lembaga->foto_nambor)
                                    <img src="{{ asset('storage/' . $lembaga->foto_nambor) }}" class="object-cover w-full h-full cursor-pointer hover:scale-105 transition" onclick="bukaModalGambar('{{ asset('storage/' . $lembaga->foto_nambor) }}', 'B. Papan Nama / Nambor')">
                                @else
                                    <span class="text-black-400 text-[11px]">Belum Ada</span>
                                @endif
                            </div>
                        </div>
                        <div class="border border-gray-300 rounded-lg p-2 bg-gray-50 flex flex-col items-center">
                            <label class="text-[10px] font-bold text-black-700 uppercase mb-1">C. Gedung</label>
                            <div class="w-full h-32 bg-gray-200 border border-gray-400 rounded-md overflow-hidden flex justify-center items-center">
                                @if($lembaga->foto_bangunan)
                                    <img src="{{ asset('storage/' . $lembaga->foto_bangunan) }}" class="object-cover w-full h-full cursor-pointer hover:scale-105 transition" onclick="bukaModalGambar('{{ asset('storage/' . $lembaga->foto_bangunan) }}', 'C. Gedung / Bangunan')">
                                @else
                                    <span class="text-black-400 text-[11px]">Belum Ada</span>
                                @endif
                            </div>
                        </div>
                        <div class="border border-gray-300 rounded-lg p-2 bg-gray-50 flex flex-col items-center">
                            <label class="text-[10px] font-bold text-black-700 uppercase mb-1">D. KBM</label>
                            <div class="w-full h-32 bg-gray-200 border border-gray-400 rounded-md overflow-hidden flex justify-center items-center">
                                @if($lembaga->foto_kbm)
                                    <img src="{{ asset('storage/' . $lembaga->foto_kbm) }}" class="object-cover w-full h-full cursor-pointer hover:scale-105 transition" onclick="bukaModalGambar('{{ asset('storage/' . $lembaga->foto_kbm) }}', 'D. Kegiatan Belajar')">
                                @else
                                    <span class="text-black-400 text-[11px]">Belum Ada</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CATATAN --}}
                @if($lembaga->keterangan)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-2 rounded-r mt-2">
                    <h4 class="font-bold text-yellow-800 text-[10px] mb-0.5 uppercase">Catatan / Keterangan:</h4>
                    <p class="text-xs font-bold text-black-800 uppercase">{{ $lembaga->keterangan }}</p>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>