<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('CCTV Sistem: Log Aktivitas Operator') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER HALAMAN --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="flex items-center gap-4">
                    {{-- TOMBOL KEMBALI --}}
                    <a href="{{ route('user.index') }}" class="bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 p-2 rounded-lg transition border border-gray-300 shadow-sm" title="Kembali ke Manajemen User">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Buku Catatan Aktivitas</h3>
                        <p class="text-xs text-gray-500">Memantau rekam jejak aksi Tambah, Edit, Hapus, dan Saklar Insentif secara Real-Time.</p>
                    </div>
                </div>
                
                {{-- Tombol Bersihkan Log (Hanya jika ada data) --}}
                @if($logs->count() > 0)
                    <form action="{{ route('activity.log.clear') }}" method="POST" onsubmit="return confirm('Yakin ingin MENGHAPUS PERMANEN seluruh riwayat catatan log ini? Tindakan ini tidak bisa dibatalkan.');">
                        @csrf
                        <button type="submit" class="bg-red-50 text-red-600 border border-red-300 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-md text-xs font-bold transition flex items-center gap-1 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Kosongkan Riwayat Log
                        </button>
                    </form>
                @endif
            </div>

            {{-- PESAN SUKSES --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-sm rounded shadow-sm flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900 font-bold">&times;</button>
                </div>
            @endif

            {{-- FILTER PENCARIAN LOG --}}
            <div class="bg-white p-4 rounded-t-xl border-t border-l border-r border-gray-300 shadow-sm">
                <form action="{{ route('activity.log') }}" method="GET" class="flex gap-2 max-w-md">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama operator, aksi, atau nama guru..." 
                           class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-1.5 rounded-md text-sm font-bold hover:bg-gray-900 transition shadow-sm">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('activity.log') }}" class="bg-gray-100 text-gray-600 border border-gray-300 px-3 py-1.5 rounded-md text-sm font-bold hover:bg-gray-200 transition flex items-center justify-center">Reset</a>
                    @endif
                </form>
            </div>

            {{-- TABEL AUDIT TRAIL / LOG SYSTEM --}}
            <div class="bg-white border border-gray-400 overflow-hidden shadow-sm rounded-b-xl">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 uppercase text-[10px] tracking-wider border-b border-gray-300">
                            <th class="border-r border-gray-300 px-2 py-3 text-center font-bold w-12">No</th>
                            <th class="border-r border-gray-300 px-4 py-3 text-left font-bold w-48">Waktu Kejadian</th>
                            <th class="border-r border-gray-300 px-4 py-3 text-left font-bold w-64">Nama Operator</th>
                            <th class="border-r border-gray-300 px-4 py-3 text-left font-bold w-64">Tindakan / Aksi</th>
                            <th class="px-4 py-3 text-left font-bold">Target Objek (Data Guru)</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 divide-y divide-gray-200">
                        @forelse($logs as $index => $log)
                            <tr class="hover:bg-slate-50 transition duration-150">
                                
                                {{-- NOMOR --}}
                                <td class="border-r border-gray-200 px-2 py-3 text-center bg-gray-50 font-medium text-gray-500">
                                    {{ $logs->firstItem() + $index }}
                                </td>

                                {{-- WAKTU (Jam Aktual Kediri) --}}
                                <td class="border-r border-gray-200 px-4 py-3 text-xs text-gray-600 font-mono">
                                    {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y (H:i:s)') }}
                                </td>

                                {{-- NAMA OPERATOR --}}
                                <td class="border-r border-gray-200 px-4 py-3">
                                    <span class="font-bold text-gray-900">{{ $log->nama_user }}</span>
                                    <span class="text-[10px] text-gray-400 block font-mono">ID: #{{ $log->user_id ?? 'N/A' }}</span>
                                </td>

                                {{-- AKSI / TINDAKAN (Diberi warna biar gampang dibaca) --}}
                                <td class="border-r border-gray-200 px-4 py-3 text-xs">
                                    @if(str_contains($log->aksi, 'Menghapus'))
                                        <span class="px-2 py-0.5 rounded font-bold bg-red-100 text-red-700 border border-red-200">{{ $log->aksi }}</span>
                                    @elseif(str_contains($log->aksi, 'Mengaktifkan'))
                                        <span class="px-2 py-0.5 rounded font-bold bg-green-100 text-green-700 border border-green-200">{{ $log->aksi }}</span>
                                    @elseif(str_contains($log->aksi, 'Menambah'))
                                        <span class="px-2 py-0.5 rounded font-bold bg-blue-100 text-blue-700 border border-blue-200">{{ $log->aksi }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded font-bold bg-amber-100 text-amber-700 border border-amber-200">{{ $log->aksi }}</span>
                                    @endif
                                </td>

                                {{-- TARGET GURU --}}
                                <td class="px-4 py-3 font-semibold text-gray-800">
                                    {{ $log->target }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border border-gray-300 px-4 py-12 text-center text-gray-400 bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <span class="text-xs">Belum ada riwayat aktivitas yang terekam di sistem.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- PAGINATION --}}
            <div class="mt-4">
                {{ $logs->withQueryString()->links() }}
            </div>

        </div>
    </div>
</x-app-layout>