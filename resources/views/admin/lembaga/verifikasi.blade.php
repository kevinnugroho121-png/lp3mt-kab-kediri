<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            {{ __('Verifikasi Dokumen Lembaga') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-full mx-auto px-1 sm:px-1 lg:px-1">
            
            {{-- HEADER INFO --}}
            <div class="flex justify-between items-center mb-2 px-1">
                <div>
                    <h1 class="text-2xl font-bold text-black-800 uppercase">VERIFIKASI LEMBAGA: {{ $lembaga->nama_lembaga }}</h1>
                    <p class="text-xs font-bold text-blue-600 mt-0.5">
                        {{ $lembaga->jenis_lembaga }} | <span class="text-black-500 font-normal">{{ $lembaga->desa->nama_desa }}, Kec. {{ $lembaga->kecamatan->nama_kecamatan }}</span>
                    </p>
                </div>
                <a href="{{ route('lembaga.index') }}" class="px-4 py-1.5 bg-white border border-gray-400 rounded-md text-xs font-bold text-black-700 shadow-sm hover:bg-gray-100 transition">
                    &larr; Kembali
                </a>
            </div>

            <form action="{{ route('lembaga.proses_verifikasi', $lembaga->id) }}" method="POST">


                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 px-1">
                    
                    {{-- 1. KARTU VERIFIKASI IJOP --}}
                    <div class="bg-gray-50 rounded-lg shadow-sm border border-blue-200 flex flex-col">
                        <div class="bg-blue-50 px-3 py-2 border-b border-blue-200 flex justify-between items-center rounded-t-lg">
                            <div class="flex items-center gap-2">
                                <span class="bg-blue-600 text-white w-5 h-5 flex items-center justify-center rounded-full font-bold text-[10px]">1</span>
                                <h3 class="font-bold text-xs text-blue-900 uppercase">Izin Operasional (IJOP)</h3>
                            </div>
                            <div class="flex items-center gap-1">
                                <label class="text-[10px] font-bold text-black-600">Status:</label>
                                <select name="status_ijop" class="border-gray-400 rounded px-2 py-0.5 text-[10px] font-bold focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer w-28">
                                    <option value="Pending" {{ $lembaga->status_ijop == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="Disetujui" {{ $lembaga->status_ijop == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                    <option value="Ditolak" {{ $lembaga->status_ijop == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-2 flex-grow">
                            @if($lembaga->file_ijop)
                                <div class="flex justify-between items-center bg-white border border-gray-300 rounded px-2 py-1 mb-2">
                                    <span class="text-[9px] font-bold text-black-500">Masa Berlaku: <span class="text-black-800">{{ $lembaga->masa_berlaku_ijop ? $lembaga->masa_berlaku_ijop->format('d/m/Y') : '-' }} s.d {{ $lembaga->masa_berlaku_ijop ? $lembaga->masa_berlaku_ijop->addYears(5)->format('d/m/Y') : '-' }}</span></span>
                                    <span class="text-[9px] font-bold text-black-500">Fisik: <span class="text-black-800">{{ $lembaga->ijop }}</span></span>
                                </div>
                                <iframe src="{{ asset('storage/' . $lembaga->file_ijop) }}#view=FitH" type="application/pdf" class="w-full h-[350px] border border-gray-300 rounded bg-white"></iframe>
                            @else
                                <div class="h-[350px] flex flex-col items-center justify-center border border-dashed border-gray-300 rounded bg-gray-100">
                                    <span class="text-black-500 font-bold text-xs italic">File IJOP belum diupload.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 1B. KARTU VERIFIKASI SKD --}}
                    <div class="bg-gray-50 rounded-lg shadow-sm border border-teal-200 flex flex-col">
                        <div class="bg-teal-50 px-3 py-2 border-b border-teal-200 flex justify-between items-center rounded-t-lg">
                            <div class="flex items-center gap-2">
                                <span class="bg-teal-600 text-white w-5 h-5 flex items-center justify-center rounded-full font-bold text-[10px]">1B</span>
                                <h3 class="font-bold text-xs text-teal-900 uppercase">Surat Ket. Domisili (SKD)</h3>
                            </div>
                            <div class="flex items-center gap-1">
                                <label class="text-[10px] font-bold text-black-600">Status:</label>
                                <select name="status_skd" class="border-gray-400 rounded px-2 py-0.5 text-[10px] font-bold focus:ring-teal-500 focus:border-teal-500 bg-white cursor-pointer w-28">
                                    <option value="Pending" {{ $lembaga->status_skd == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="Disetujui" {{ $lembaga->status_skd == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                    <option value="Ditolak" {{ $lembaga->status_skd == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-2 flex-grow">
                            @if($lembaga->file_skd)
                                <iframe src="{{ asset('storage/' . $lembaga->file_skd) }}#view=FitH" type="application/pdf" class="w-full h-[350px] border border-gray-300 rounded bg-white"></iframe>
                            @else
                                <div class="h-[350px] flex flex-col items-center justify-center border border-dashed border-gray-300 rounded bg-gray-100 p-4 text-center">
                                    <span class="text-black-500 font-bold text-xs italic">File SKD tidak ada/belum diupload.</span>
                                    <span class="text-orange-500 font-bold text-[9px] mt-1">*Hanya wajib jika IJOP belum terbit.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. KARTU VERIFIKASI SUPER --}}
                    <div class="bg-gray-50 rounded-lg shadow-sm border border-purple-200 flex flex-col">
                        <div class="bg-purple-50 px-3 py-2 border-b border-purple-200 flex justify-between items-center rounded-t-lg">
                            <div class="flex items-center gap-2">
                                <span class="bg-purple-600 text-white w-5 h-5 flex items-center justify-center rounded-full font-bold text-[10px]">2</span>
                                <h3 class="font-bold text-xs text-purple-900 uppercase">SPTJM Mutlak</h3>
                            </div>
                            <div class="flex items-center gap-1">
                                <label class="text-[10px] font-bold text-black-600">Status:</label>
                                <select name="status_super" class="border-gray-400 rounded px-2 py-0.5 text-[10px] font-bold focus:ring-purple-500 focus:border-purple-500 bg-white cursor-pointer w-28">
                                    <option value="Pending" {{ $lembaga->status_super == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="Disetujui" {{ $lembaga->status_super == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                    <option value="Ditolak" {{ $lembaga->status_super == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-2 flex-grow">
                            @if($lembaga->file_super)
                                <iframe src="{{ asset('storage/' . $lembaga->file_super) }}#view=FitH" type="application/pdf" class="w-full h-[350px] border border-gray-300 rounded bg-white"></iframe>
                            @else
                                <div class="h-[350px] flex flex-col items-center justify-center border border-dashed border-gray-300 rounded bg-gray-100">
                                    <span class="text-black-500 font-bold text-xs italic">File SPTJM belum diupload.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 3. KARTU VERIFIKASI SKAM --}}
                    <div class="bg-gray-50 rounded-lg shadow-sm border border-orange-200 flex flex-col">
                        <div class="bg-orange-50 px-3 py-2 border-b border-orange-200 flex justify-between items-center rounded-t-lg">
                            <div class="flex items-center gap-2">
                                <span class="bg-orange-500 text-white w-5 h-5 flex items-center justify-center rounded-full font-bold text-[10px]">3</span>
                                <h3 class="font-bold text-xs text-orange-900 uppercase">Surat Ket. Aktif Mengajar (SKAM)</h3>
                            </div>
                            <div class="flex items-center gap-1">
                                <label class="text-[10px] font-bold text-black-600">Status:</label>
                                <select name="status_skam" class="border-gray-400 rounded px-2 py-0.5 text-[10px] font-bold focus:ring-orange-500 focus:border-orange-500 bg-white cursor-pointer w-28">
                                    <option value="Pending" {{ $lembaga->status_skam == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="Disetujui" {{ $lembaga->status_skam == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                    <option value="Ditolak" {{ $lembaga->status_skam == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-2 flex-grow">
                            @if($lembaga->file_skam)
                                <iframe src="{{ asset('storage/' . $lembaga->file_skam) }}#view=FitH" type="application/pdf" class="w-full h-[350px] border border-gray-300 rounded bg-white"></iframe>
                            @else
                                <div class="h-[350px] flex flex-col items-center justify-center border border-dashed border-gray-300 rounded bg-gray-100">
                                    <span class="text-black-500 font-bold text-xs italic">File SKAM belum diupload.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- FOOTER ACTION --}}
                <div class="mt-4 bg-gray-50 px-4 py-3 rounded-lg shadow-md border border-gray-400 sticky bottom-2 z-50">
                    <div class="flex flex-col md:flex-row gap-4 items-end justify-between">
                        <div class="flex-grow w-full md:w-2/3">
                            <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Catatan Verifikasi / Alasan Penolakan (Opsional)</label>
                            <input type="text" name="catatan_verifikasi" value="{{ $lembaga->keterangan }}" class="w-full border border-gray-400 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 shadow-sm focus:border-blue-500 focus:ring-blue-500 uppercase" placeholder="Ketik catatan di sini jika ada file yang ditolak..." oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="flex gap-2 w-full md:w-auto">
                            <a href="{{ route('lembaga.index') }}" class="px-5 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-400 rounded-md hover:bg-gray-100 transition text-center flex items-center">Batal</a>
                            <button type="submit" class="px-6 py-1.5 text-xs font-bold text-white bg-green-600 rounded-md shadow-sm hover:bg-green-700 transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                SIMPAN VERIFIKASI
                            </button>
                        </div>
                    </div>
                </div>
                {{-- Spacer --}}
                <div class="h-10"></div>

            </form>
        </div>
    </div>
</x-app-layout>