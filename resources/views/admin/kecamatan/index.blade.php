<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-black-800 leading-tight">
            {{ __('Master Data Wilayah') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER: JUDUL & TOMBOL --}}
            <div class="flex justify-between items-center mb-4">
                {{-- Bagian Kiri: Judul --}}
                <div>
                    <h3 class="font-bold text-lg text-black-800">Data Kecamatan & Desa</h3>
                    @if(Auth::user()->role == 'korcam')
                        <p class="text-xs text-green-600 font-bold">Wilayah Kerja: {{ Auth::user()->kecamatan->nama_kecamatan ?? '-' }}</p>
                    @else
                        <p class="text-xs text-black-500">Kelola data wilayah administratif Kabupaten Kediri</p>
                    @endif
                </div>
                
                {{-- Bagian Kanan: Search & Tambah (HANYA JIKA BUKAN KORCAM) --}}
                @if(Auth::user()->role != 'korcam')
                    <div class="flex gap-2">
                        {{-- SEARCH BOX DENGAN TOMBOL MANUAL --}}
                        <form action="{{ route('kecamatan.index') }}" method="GET" class="flex items-center gap-1">
                            
                            {{-- Input Pencarian --}}
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kecamatan..." 
                                   class="w-48 sm:w-64 border border-gray-600 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 shadow-sm h-9">
                            
                            {{-- Tombol Cari (Biru) --}}
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 rounded-md text-sm font-bold shadow-sm transition h-9 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cari
                            </button>

                            {{-- Tombol Reset (Selalu Tampil / Standby) --}}
                            <a href="{{ route('kecamatan.index') }}" class="bg-gray-100 hover:bg-gray-200 text-black-600 px-3 py-1.5 rounded-lg text-sm font-bold transition shadow-sm border border-gray-600">
                                Reset
                            </a>
                        </form>

                        {{-- TOMBOL TAMBAH (Hijau) --}}
                        <a href="{{ route('kecamatan.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 h-9 rounded-md text-sm font-medium flex items-center gap-1 shadow-sm transition ml-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Data
                        </a>
                    </div>
                @endif
            </div>

            {{-- PESAN SUKSES --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-sm rounded shadow-sm flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900 font-bold">&times;</button>
                </div>
            @endif

            {{-- PESAN ERROR (Jika Korcam coba hapus) --}}
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 text-sm rounded shadow-sm flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900 font-bold">&times;</button>
                </div>
            @endif

            {{-- ================================================================= --}}
            {{-- [BARU] SISTEM KONTROL ALOKASI KUOTA INDUK (3 KARTU RINGKASAN)     --}}
            {{-- ================================================================= --}}
            @if(Auth::user()->role != 'korcam')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-1 mb-1">
                    
                    {{-- KARTU 1: TOTAL PAGU KABUPATEN (INPUT MASTER) --}}
                    <div class="bg-white rounded-xl border border-gray-300 p-4 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Pagu Kuota Kabupaten</span>
                            <span class="p-1.5 bg-blue-50 text-blue-600 rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </span>
                        </div>
                        <form action="{{ route('kecamatan.update_pagu_induk') }}" method="POST" class="flex items-center gap-2 mt-1">
                            @csrf
                            <input type="number" name="pagu_induk" value="{{ old('pagu_induk', $totalPagu) }}" min="0" placeholder="0" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-base font-extrabold text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-2 rounded-lg shadow-sm transition whitespace-nowrap">
                                Simpan Pagu
                            </button>
                        </form>
                        <p class="text-[10px] text-slate-400 mt-2 italic">* Angka pagu anggaran resmi Pemkab Kediri</p>
                    </div>

                    {{-- KARTU 2: KUOTA SUDAH TERBAGI --}}
                    <div class="bg-white rounded-xl border border-gray-300 p-4 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kuota Terdistribusi</span>
                            <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-emerald-600">{{ number_format($kuotaTerdistribusi ?? 0) }} <span class="text-xs font-bold text-slate-500">Slot</span></p>
                            <p class="text-[11px] text-slate-500 mt-1 font-medium">Total jatah yang sudah dibagi ke kecamatan</p>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-3 overflow-hidden">
                            @php
                                $persenTerbagi = ($totalPagu > 0) ? min(100, round(($kuotaTerdistribusi / $totalPagu) * 100)) : 0;
                            @endphp
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $persenTerbagi }}%"></div>
                        </div>
                    </div>

                    {{-- KARTU 3: SISA KUOTA BELUM DIBAGI --}}
                    <div class="bg-white rounded-xl border border-gray-300 p-4 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Sisa Kuota Belum Dibagi</span>
                            <span class="p-1.5 {{ $sisaKuota < 0 ? 'bg-red-50 text-red-600' : ($sisaKuota == 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600') }} rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                        </div>
                        <div>
                            <p class="text-2xl font-black {{ $sisaKuota < 0 ? 'text-red-600' : ($sisaKuota == 0 ? 'text-emerald-600' : 'text-amber-600') }}">
                                {{ number_format($sisaKuota ?? 0) }} <span class="text-xs font-bold text-slate-500">Slot</span>
                            </p>
                            @if($sisaKuota == 0)
                                <p class="text-[11px] text-emerald-600 mt-1 font-bold">✅ Sempurna! Kuota terbagi 100% pas.</p>
                            @elseif($sisaKuota > 0)
                                <p class="text-[11px] text-amber-600 mt-1 font-bold">🎯 Masih ada {{ number_format($sisaKuota) }} slot yang siap dialokasikan.</p>
                            @else
                                <p class="text-[11px] text-red-600 mt-1 font-bold">⚠️ Over Kuota! Jatah kecamatan melebihi pagu.</p>
                            @endif
                        </div>
                        <div class="mt-2 text-right">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $sisaKuota < 0 ? 'bg-red-100 text-red-700' : ($sisaKuota == 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ $sisaKuota < 0 ? 'Defisit' : ($sisaKuota == 0 ? 'Pas' : 'Tersedia') }}
                            </span>
                        </div>
                    </div>

                </div>
            @endif

            {{-- TABEL ALA EXCEL (BORDERED & COMPACT) --}}
            <div class="bg-white border border-gray-600 overflow-hidden shadow-sm">
                <table class="w-full text-sm border-collapse">
                    {{-- HEADER --}}
                    <thead>


                        <tr class="bg-gray-100 text-black-700 uppercase text-xs tracking-wider">
                            <th class="border border-gray-600 px-2 py-2 text-center w-12 font-bold">No</th>
                            <th class="border border-gray-600 px-3 py-2 text-center font-bold">Nama Kecamatan</th>
                            <th class="border border-gray-600 px-2 py-2 text-center font-bold w-28">Data Desa</th> 
                            {{-- Kunci lebar pas 310px agar rapat dan tidak membuang ruang di kanan-kiri --}}
                            <th class="border border-gray-600 px-1 py-2 text-center font-bold w-[310px] whitespace-nowrap">Alokasi & Realisasi Kuota</th> 
                            <th class="border border-gray-600 px-2 py-2 text-center font-bold w-20">Aksi</th>
                        </tr>


                    </thead>
                    
                    {{-- BODY --}}
                    <tbody class="text-black-600">
                        @forelse($kecamatans as $index => $kecamatan)
                            <tr class="hover:bg-yellow-50 transition duration-150">
                                
                                {{-- NO --}}
                                <td class="border border-gray-600 px-2 py-1 text-center bg-gray-50 font-medium">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- NAMA KECAMATAN --}}
                                <td class="border border-gray-600 px-3 py-1 font-bold text-black-800">
                                    {{ $kecamatan->nama_kecamatan }}
                                </td>

                                {{-- JUMLAH DESA (LINK KHUSUS) --}}
                                <td class="border border-gray-600 px-2 py-1 text-center p-0">
                                    <a href="{{ route('desa.index', ['kecamatan_id' => $kecamatan->id]) }}" class="group flex items-center justify-between w-full h-full px-3 py-1 text-xs hover:bg-blue-50 transition cursor-pointer">
                                        <span class="font-bold text-blue-600 group-hover:underline">
                                            {{ $kecamatan->desa_count ?? 0 }} Desa
                                        </span>
                                        <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>

                                {{-- [BARU] FORM INPUT KUOTA + BADGE REALISASI SEJAJAR PRESISI & RAPI --}}
                                <td class="border border-gray-600 px-1 py-1 text-center w-[310px]">
                                    @php
                                        $terpakai = $terpakaiMap[$kecamatan->id] ?? 0;
                                        $sisaKec = ($kecamatan->kuota_insentif ?? 0) - $terpakai;
                                    @endphp

                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- 1. Input Group Menyatu (Input + Tombol Simpan Tergabung Rapi) --}}
                                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'verifikator')
                                            <form action="{{ route('kecamatan.update_kuota', $kecamatan->id) }}" method="POST" class="inline-flex shadow-sm rounded-md overflow-hidden">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="current_page_url" value="{{ request()->fullUrl() }}">
                                                <input type="number" name="kuota_insentif" value="{{ old('kuota_insentif', $kecamatan->kuota_insentif ?? 0) }}" min="0"
                                                    class="w-16 border border-r-0 border-gray-400 rounded-l-md px-1 py-0.5 text-center text-xs font-bold text-slate-800 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 h-7 bg-white">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold px-2.5 h-7 rounded-r-md transition flex items-center justify-center">
                                                    Simpan
                                                </button>
                                            </form>
                                        @else
                                            <span class="w-24 h-7 inline-flex items-center justify-center rounded-md bg-blue-50 border border-blue-200 text-blue-700 font-bold text-xs">
                                                {{ $kecamatan->kuota_insentif ?? 0 }} Kuota
                                            </span>
                                        @endif

                                        {{-- 2. Badge Kuota Terpakai (Lebar Dikunci w-24 agar Sumbu Tegak Lurus) --}}
                                        <span class="w-24 h-7 inline-flex items-center justify-center px-1.5 rounded-md bg-emerald-50 border border-emerald-300 text-emerald-700 text-[11px] font-medium whitespace-nowrap shadow-sm" title="Guru yang sudah diajukan">
                                            Terpakai:&nbsp;<b class="font-bold text-emerald-800">{{ $terpakai }}</b>
                                        </span>

                                        {{-- 3. Badge Sisa Kuota (Lebar Dikunci w-20 agar Sumbu Tegak Lurus) --}}
                                        <span class="w-20 h-7 inline-flex items-center justify-center px-1.5 rounded-md border text-[11px] font-medium whitespace-nowrap shadow-sm {{ $sisaKec < 0 ? 'bg-red-50 border-red-300 text-red-600 font-bold' : ($sisaKec == 0 ? 'bg-gray-100 border-gray-300 text-gray-600' : 'bg-amber-50 border-amber-300 text-amber-800 font-semibold') }}" title="Sisa slot kuota">
                                            Sisa:&nbsp;<b class="font-bold">{{ $sisaKec }}</b>
                                        </span>
                                    </div>
                                </td>

                                {{-- AKSI --}}
                                <td class="border border-gray-600 px-2 py-1 text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        {{-- Edit (Korcam Boleh Edit Kecamatannya Sendiri) --}}
                                        <a href="{{ route('kecamatan.edit', $kecamatan->id) }}" class="p-1 rounded hover:bg-orange-100 text-orange-500 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        
                                        {{-- Hapus (HANYA ADMIN) --}}
                                        @if(Auth::user()->role == 'admin')
                                            <form id="form-delete-kecamatan-{{ $kecamatan->id }}" action="{{ route('kecamatan.destroy', $kecamatan->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="triggerStatusUpdate('Yakin hapus kecamatan ini? Semua desa didalamnya juga akan terhapus!', 'form-delete-kecamatan-{{ $kecamatan->id }}')" class="p-1 rounded hover:bg-red-100 text-red-500 transition" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border border-gray-600 px-4 py-8 text-center text-black-400 bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 text-black-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <span class="text-xs">Data Belum Ada</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- INFORMASI TOTAL DATA (PAGINATION DIHAPUS) --}}
            <div class="mt-2 text-xs text-slate-500 font-semibold">
                Menampilkan total {{ $kecamatans->count() }} Kecamatan di Kabupaten Kediri
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
        // 1. Fungsi Inti untuk Membangun & Menampilkan Modal
        function showConfirmDialog(message, onConfirmCallback) {
            const modal = document.getElementById('custom-confirm-modal');
            const msgEl = document.getElementById('custom-confirm-message');
            const btnCancel = document.getElementById('custom-confirm-cancel');
            const btnOk = document.getElementById('custom-confirm-ok');

            // Set pesan teks secara dinamis
            msgEl.textContent = message;

            // Tampilkan modal
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.firstElementChild.classList.replace('scale-95', 'scale-100');
            }, 10);

            // Fungsi untuk menutup modal
            const closeModal = () => {
                modal.firstElementChild.classList.replace('scale-100', 'scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 150);
                
                // Hapus event listener agar tidak menumpuk
                btnCancel.removeEventListener('click', handleCancel);
                btnOk.removeEventListener('click', handleOk);
            };

            const handleCancel = () => closeModal();
            const handleOk = () => {
                closeModal();
                if (typeof onConfirmCallback === 'function') {
                    onConfirmCallback(); // Eksekusi submit form
                }
            };

            btnCancel.addEventListener('click', handleCancel);
            btnOk.addEventListener('click', handleOk);
        }

        // 2. Fungsi Pemicu (Trigger) yang dipanggil oleh Tombol HTML
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