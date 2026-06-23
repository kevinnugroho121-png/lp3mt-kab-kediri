<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">Verifikasi Guru</h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-full mx-auto px-1 sm:px-1 lg:px-1">
            
            {{-- Header Nama Guru --}}
            <div class="flex justify-between items-center mb-2">
                <div>
                    <h1 class="text-2xl font-bold text-black-800 uppercase">VERIFIKASI GURU: {{ $guru->nama_lengkap }}</h1>


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


                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    
                    {{-- 1. KTP --}}
                    <div class="bg-gray-50 rounded-lg shadow-sm border border-blue-200 flex flex-col">
                        <div class="bg-blue-50 px-3 py-2 border-b border-blue-200 flex justify-between items-center rounded-t-lg">
                            <h3 class="font-bold text-xs text-blue-900 flex items-center gap-1">
                                <span class="bg-blue-600 text-white w-4 h-4 rounded-full flex items-center justify-center text-[9px]">1</span> KTP
                            </h3>
                            <select name="status_ktp" class="border-gray-400 rounded px-2 py-0.5 text-[10px] font-bold focus:ring-blue-500 focus:border-blue-500 w-28 bg-white cursor-pointer">
                                <option value="Pending" {{ $guru->status_ktp == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="Disetujui" {{ $guru->status_ktp == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                <option value="Ditolak" {{ $guru->status_ktp == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                            </select>
                        </div>
                        <div class="p-2 flex-grow">
                            @if($guru->file_ktp)
                                <iframe src="{{ asset('storage/' . $guru->file_ktp) }}" type="application/pdf" class="w-full h-[350px] border border-gray-300 rounded bg-white"></iframe>
                            @else
                                <div class="h-[350px] flex items-center justify-center border border-dashed border-gray-300 rounded bg-gray-100 text-gray-500 text-xs italic">Belum diupload</div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. KK --}}
                    <div class="bg-gray-50 rounded-lg shadow-sm border border-green-200 flex flex-col">
                        <div class="bg-green-50 px-3 py-2 border-b border-green-200 flex justify-between items-center rounded-t-lg">
                            <h3 class="font-bold text-xs text-green-900 flex items-center gap-1">
                                <span class="bg-green-600 text-white w-4 h-4 rounded-full flex items-center justify-center text-[9px]">2</span> KK
                            </h3>
                            <select name="status_kk" class="border-gray-400 rounded px-2 py-0.5 text-[10px] font-bold focus:ring-green-500 focus:border-green-500 w-28 bg-white cursor-pointer">
                                <option value="Pending" {{ $guru->status_kk == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="Disetujui" {{ $guru->status_kk == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                <option value="Ditolak" {{ $guru->status_kk == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                            </select>
                        </div>
                        <div class="p-2 flex-grow">
                            @if($guru->file_kk)
                                <iframe src="{{ asset('storage/' . $guru->file_kk) }}" type="application/pdf" class="w-full h-[350px] border border-gray-300 rounded bg-white"></iframe>
                            @else
                                <div class="h-[350px] flex items-center justify-center border border-dashed border-gray-300 rounded bg-gray-100 text-gray-500 text-xs italic">Belum diupload</div>
                            @endif
                        </div>
                    </div>

                    {{-- 3. REKENING --}}
                    <div class="bg-gray-50 rounded-lg shadow-sm border border-purple-200 flex flex-col">
                        <div class="bg-purple-50 px-3 py-2 border-b border-purple-200 flex justify-between items-center rounded-t-lg">
                            <h3 class="font-bold text-xs text-purple-900 flex items-center gap-1">
                                <span class="bg-purple-600 text-white w-4 h-4 rounded-full flex items-center justify-center text-[9px]">3</span> Rekening
                            </h3>
                            <select name="status_bukurekening" class="border-gray-400 rounded px-2 py-0.5 text-[10px] font-bold focus:ring-purple-500 focus:border-purple-500 w-28 bg-white cursor-pointer">
                                <option value="Pending" {{ $guru->status_bukurekening == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="Disetujui" {{ $guru->status_bukurekening == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                <option value="Ditolak" {{ $guru->status_bukurekening == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                            </select>
                        </div>
                        <div class="p-2 flex-grow">
                            @if($guru->file_bukurekening)
                                <iframe src="{{ asset('storage/' . $guru->file_bukurekening) }}" type="application/pdf" class="w-full h-[350px] border border-gray-300 rounded bg-white"></iframe>
                            @else
                                <div class="h-[350px] flex items-center justify-center border border-dashed border-gray-300 rounded bg-gray-100 text-gray-500 text-xs italic">Belum diupload</div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Footer Action --}}
                <div class="mt-4 bg-gray-50 px-4 py-3 rounded-lg shadow-md border border-gray-400 sticky bottom-2 z-50">
                    <div class="flex flex-col md:flex-row gap-4 items-end justify-between">
                        <div class="flex-grow w-full md:w-2/3">
                            <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Catatan Verifikasi / Alasan Penolakan (Opsional)</label>
                            <input type="text" name="catatan_verifikasi" value="{{ $guru->keterangan }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ketik catatan di sini jika ada file yang ditolak...">
                        </div>
                        <div class="flex gap-2 w-full md:w-auto">
                            <a href="{{ $backRoute }}" class="px-5 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-400 rounded-md hover:bg-gray-100 transition text-center flex items-center">Batal</a>
                            <button type="submit" class="px-6 py-1.5 text-xs font-bold text-white bg-green-600 rounded-md shadow-sm hover:bg-green-700 transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                SIMPAN VERIFIKASI
                            </button>
                        </div>
                    </div>
                </div>


                <div class="h-10"></div> {{-- Spacer bawah agar tombol tidak tertutup --}}
            </form>
        </div>
    </div>
</x-app-layout>