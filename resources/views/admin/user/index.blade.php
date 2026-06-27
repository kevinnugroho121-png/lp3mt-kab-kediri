<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen User System') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER: JUDUL & TOMBOL --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-4 gap-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800">Daftar Pengguna & Pemantauan</h3>
                    <p class="text-xs text-gray-500">Kelola akun dan pantau aktivitas perangkat secara Real-Time.</p>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    
                    {{-- 💾 TOMBOL BACKUP DATABASE (FLEX UNTUK SIDANG) --}}
                    <form id="form-backup-db" action="{{ route('backup.database') }}" method="POST" class="inline">
                        @csrf
                        <button type="button" onclick="triggerStatusUpdate('Proses ini akan mengunduh seluruh database sistem. Lanjutkan?', 'form-backup-db')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Backup Database
                        </button>
                    </form>

                    {{-- 🕵️‍♂️ [BARU] TOMBOL BUKA CCTV LOG --}}
                    <a href="{{ route('activity.log') }}" class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Lihat Log Aktivitas
                    </a>

                    {{-- TOMBOL TAMBAH USER --}}
                    <a href="{{ route('user.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah User Baru
                    </a>
                </div>


            </div>

            {{-- PESAN SUKSES --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-sm rounded shadow-sm flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900 font-bold">&times;</button>
                </div>
            @endif

            {{-- ================================================= --}}
            {{-- 1. [BARU] PANEL FILTER PINTAR --}}
            {{-- ================================================= --}}
            <div class="bg-white p-4 rounded-t-xl border-t border-l border-r border-gray-600 shadow-sm">
                <form action="{{ route('user.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                    
                    {{-- Pencarian Nama/Email --}}
                    <div class="md:col-span-4">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Cari Pengguna</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email..." 
                               class="w-full border border-gray-600 rounded-md px-3 py-1.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Filter Role --}}
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Filter Jabatan</label>
                        <select name="filter_role" class="w-full border border-gray-600 rounded-md px-3 py-1.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">- Semua Jabatan -</option>
                            <option value="admin" {{ request('filter_role') == 'admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="verifikator" {{ request('filter_role') == 'verifikator' ? 'selected' : '' }}>Verifikator Kab</option>
                            <option value="korcam" {{ request('filter_role') == 'korcam' ? 'selected' : '' }}>Korcam</option>
                        </select>
                    </div>

                    {{-- Filter Kecamatan --}}
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Filter Wilayah</label>
                        <select name="filter_kecamatan" class="w-full border border-gray-600 rounded-md px-3 py-1.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">- Semua Kecamatan -</option>
                            @foreach($data_kecamatan as $kec)
                                <option value="{{ $kec->id }}" {{ request('filter_kecamatan') == $kec->id ? 'selected' : '' }}>Kec. {{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Filter --}}
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-1.5 rounded-md text-sm font-bold w-full hover:bg-gray-900 transition">Filter</button>
                        <a href="{{ route('user.index') }}" class="bg-gray-100 text-gray-600 border border-gray-600 px-3 py-1.5 rounded-md text-sm font-bold hover:bg-gray-400 transition text-center flex items-center justify-center">Reset</a>
                    </div>
                </form>
            </div>

            {{-- ================================================= --}}
            {{-- 2. TABEL DATA & INTELIJEN SESI --}}
            {{-- ================================================= --}}
            <div class="bg-white border border-gray-600 overflow-hidden shadow-sm rounded-b-xl">
                <table class="w-full text-sm border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 uppercase text-[10px] tracking-wider border-b border-gray-600">
                            <th class="border-r border-gray-600 px-2 py-3 text-center font-bold w-12">No</th>
                            <th class="border-r border-gray-600 px-3 py-3 text-left font-bold w-64">Nama & Kontak</th>
                            <th class="border-r border-gray-600 px-3 py-3 text-center font-bold w-40">Jabatan & Wilayah</th>
                            
                            {{-- [BARU] Kolom Mata-Mata --}}
                            <th class="border-r border-gray-600 px-3 py-3 text-left font-bold bg-blue-50/50">Aktivitas Terakhir (Intelijen)</th>
                            
                            <th class="px-3 py-3 text-center font-bold w-32">Aksi Pengelola</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 divide-y divide-gray-600">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-yellow-50 transition duration-150 align-top">
                                
                                <td class="border-r border-gray-600 px-2 py-3 text-center bg-gray-50 font-medium">
                                    {{ $users->firstItem() + $index }}
                                </td>

                                <td class="border-r border-gray-600 px-3 py-3">
                                    <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</div>
                                </td>

                                <td class="border-r border-gray-600 px-3 py-3 text-center">
                                    @if($user->role == 'admin')
                                        <div class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 border border-purple-200 mb-1">Super Admin</div>
                                    @elseif($user->role == 'verifikator')
                                        <div class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200 mb-1">Verifikator Kab</div>
                                    @elseif($user->role == 'korcam')
                                        <div class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700 border border-orange-200 mb-1">Korcam - {{ $user->jabatan_korcam ?? 'Tim' }}</div>
                                    @endif
                                    
                                    <div class="text-[10px] text-gray-600 font-semibold mt-1">
                                        {{ $user->kecamatan ? 'Kec. ' . $user->kecamatan->nama_kecamatan : 'Pusat Kabupaten' }}
                                    </div>
                                </td>

                                {{-- [BARU] KOLOM INTELIJEN --}}
                                <td class="border-r border-gray-600 px-3 py-3 bg-blue-50/20">
                                    <div class="flex items-center gap-2 mb-1">
                                        @if($user->is_online)
                                            <span class="relative flex h-3 w-3">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                            </span>
                                            <span class="text-xs font-bold text-green-600">SEDANG ONLINE</span>
                                        @else
                                            <span class="h-2.5 w-2.5 rounded-full bg-gray-600 border border-gray-500"></span>
                                            <span class="text-xs font-bold text-gray-500">Offline</span>
                                            <span class="text-[10px] text-gray-600 ml-1">({{ $user->last_seen }})</span>
                                        @endif
                                    </div>
                                    
                                    @if($user->last_seen != 'Offline')
                                        <div class="text-[10px] text-gray-500 mt-1">
                                            <span class="font-semibold text-gray-700">Device:</span> {{ $user->perangkat }} <br>
                                            <span class="font-semibold text-gray-700">IP:</span> {{ $user->ip_address }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-2 py-3 text-center align-middle">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        
                                        {{-- 🔑 Tombol Reset Password --}}
                                        <form id="form-reset-pw-{{ $user->id }}" action="{{ route('user.reset-password', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="button" onclick="triggerStatusUpdate('Yakin ingin mereset password user ini menjadi: kediri2026 ?', 'form-reset-pw-{{ $user->id }}')" class="p-1.5 bg-gray-100 text-gray-700 border border-gray-600 rounded hover:bg-gray-700 hover:text-white transition" title="Reset Password ke Default">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                            </button>
                                        </form>

                                        {{-- 📱 Tombol Sapu Jagat (Hanya Korcam) --}}
                                        @if($user->role == 'korcam')
                                            <form id="form-reset-device-{{ $user->id }}" action="{{ route('user.reset-device', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="button" onclick="triggerStatusUpdate('Yakin logout paksa seluruh perangkat Korcam ini?', 'form-reset-device-{{ $user->id }}')" class="p-1.5 bg-amber-100 text-amber-700 border border-amber-300 rounded hover:bg-amber-600 hover:text-white transition" title="Kosongkan Slot Login">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- 🗑️ Tombol Hapus --}}
                                        <form id="form-delete-user-{{ $user->id }}" action="{{ route('user.destroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="triggerStatusUpdate('Yakin hapus user ini permanen?', 'form-delete-user-{{ $user->id }}')" class="p-1.5 bg-red-50 text-red-500 border border-red-200 rounded hover:bg-red-500 hover:text-white transition" title="Hapus User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border border-gray-600 px-4 py-8 text-center text-gray-600 bg-gray-50">
                                    Belum ada data user / Tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">{{ $users->withQueryString()->links() }}</div>
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