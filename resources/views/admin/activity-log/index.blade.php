<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            {{ __('CCTV Sistem: Log Aktivitas Operator') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-[1500px] mx-auto px-1 sm:px-1 lg:px-1">
            
            {{-- HEADER HALAMAN --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-1 gap-4">
                <div class="flex items-center gap-4">
                    {{-- TOMBOL KEMBALI --}}
                    <a href="{{ route('user.index') }}" class="bg-gray-100 text-black-600 hover:bg-gray-200 hover:text-black-900 p-2 rounded-lg transition border border-gray-600 shadow-sm" title="Kembali ke Manajemen User">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    
                    <div>
                        <h3 class="font-bold text-lg text-black-800">Buku Catatan Aktivitas</h3>
                        <p class="text-xs text-black-500">Memantau rekam jejak aksi Tambah, Edit, Hapus, dan Saklar Insentif secara Real-Time.</p>
                    </div>
                </div>
                
                {{-- Tombol Bersihkan Log (Hanya jika ada data) --}}
                @if($logs->count() > 0)
                    <form id="form-clear-log" action="{{ route('activity.log.clear') }}" method="POST">
                        @csrf
                        <button type="button" onclick="triggerStatusUpdate('Yakin ingin MENGHAPUS PERMANEN seluruh riwayat catatan log ini? Tindakan ini tidak bisa dibatalkan.', 'form-clear-log')" class="bg-red-50 text-red-600 border border-red-300 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-md text-xs font-bold transition flex items-center gap-1 shadow-sm">
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
            <div class="bg-white px-3 py-2 rounded-t-xl border-t border-l border-r border-gray-600 shadow-sm">
                <form action="{{ route('activity.log') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    {{-- Input Pencarian Teks --}}
                    <div class="flex-1 min-w-[280px]">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama operator, aksi, atau nama guru..." 
                               class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    </div>

                    {{-- Dropdown Filter Kecamatan --}}
                    <div class="w-56">
                        <select name="filter_kecamatan" class="w-full border border-gray-600 rounded-md px-2 py-1 h-[32px] text-xs font-bold text-black-800 focus:border-blue-500 focus:ring-blue-500 shadow-sm bg-white uppercase">
                            <option value="">- SEMUA KECAMATAN -</option>
                            @foreach($kecamatans as $kec)
                                <option value="{{ $kec->id }}" {{ request('filter_kecamatan') == $kec->id ? 'selected' : '' }}>
                                    KEC. {{ strtoupper($kec->nama_kecamatan) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Cari / Filter --}}
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-1 h-[32px] rounded-md text-xs font-bold uppercase transition shadow-sm">
                        Filter
                    </button>

                    {{-- Tombol Reset (Otomatis muncul jika sedang mencari atau memfilter kecamatan) --}}
                    @if(request('search') || request('filter_kecamatan'))
                        <a href="{{ route('activity.log') }}" class="bg-gray-100 text-black-600 hover:bg-gray-200 border border-gray-600 px-3 py-1 h-[32px] rounded-md text-xs font-bold uppercase transition flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- TABEL AUDIT TRAIL / LOG SYSTEM --}}
            <div class="bg-white border border-gray-600 overflow-hidden shadow-sm rounded-b-xl">
                <table class="w-full text-sm border-collapse">


                    <thead>
                        <tr class="bg-slate-100 text-slate-700 uppercase text-[10px] tracking-wider border-b border-gray-600">
                            <th class="border-r border-gray-600 px-2 py-1.5 text-center font-bold w-12">No</th>
                            <th class="border-r border-gray-600 px-2 py-1.5 text-center font-bold w-48">Waktu Kejadian</th>
                            <th class="border-r border-gray-600 px-2 py-1.5 text-center font-bold w-64">Nama Operator</th>
                            <th class="border-r border-gray-600 px-2 py-1.5 text-center font-bold w-64">Tindakan / Aksi</th>
                            <th class="px-2 py-1.5 text-center font-bold">Target Objek (Data Guru)</th>
                        </tr>
                    </thead>


                   
                    <tbody class="text-black-700 text-[11px] divide-y divide-gray-400">
                        @forelse($logs as $index => $log)
                            <tr class="hover:bg-slate-50 transition duration-150">
                                
                                {{-- NOMOR --}}
                                <td class="border-r border-gray-600 px-2 py-1.5 text-center bg-gray-50 font-medium text-black-500">
                                    {{ $logs->firstItem() + $index }}
                                </td>

                                {{-- WAKTU (Jam Aktual Kediri) --}}
                                <td class="border-r border-gray-600 px-2 py-1.5 text-[11px] text-black-600 font-mono">
                                    {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y (H:i:s)') }}
                                </td>

                                {{-- NAMA OPERATOR --}}
                                <td class="border-r border-gray-600 px-2 py-1.5">
                                    {{-- Tambah leading-tight agar jarak teks atas dan bawah makin mepet --}}
                                    <div class="leading-tight">
                                        <span class="font-bold text-[11px] text-black-900 block">{{ $log->nama_user }}</span>
                                        <span class="text-[9px] text-black-500 block font-mono mt-0.5">ID: #{{ $log->user_id ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                {{-- AKSI / TINDAKAN --}}
                                <td class="border-r border-gray-600 px-2 py-1.5 text-[10px]">
                                    @if(str_contains($log->aksi, 'Menghapus'))
                                        <span class="px-1.5 py-0.5 rounded font-bold bg-red-100 text-red-700 border border-red-200">{{ $log->aksi }}</span>
                                    @elseif(str_contains($log->aksi, 'Mengaktifkan'))
                                        <span class="px-1.5 py-0.5 rounded font-bold bg-green-100 text-green-700 border border-green-200">{{ $log->aksi }}</span>
                                    @elseif(str_contains($log->aksi, 'Menambah'))
                                        <span class="px-1.5 py-0.5 rounded font-bold bg-blue-100 text-blue-700 border border-blue-200">{{ $log->aksi }}</span>
                                    @else
                                        <span class="px-1.5 py-0.5 rounded font-bold bg-amber-100 text-amber-700 border border-amber-200">{{ $log->aksi }}</span>
                                    @endif
                                </td>

                                {{-- TARGET GURU --}}
                                <td class="px-2 py-1.5 text-[11px] font-semibold text-black-800">
                                    {{ $log->target }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border border-gray-600 px-4 py-12 text-center text-black-400 bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 text-black-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
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