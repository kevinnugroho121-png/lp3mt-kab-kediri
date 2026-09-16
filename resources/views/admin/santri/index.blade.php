<x-app-layout>
    <div class="py-1 px-1 w-full">
        
        {{-- ============================================== --}}
        {{-- HEADER & FILTER BAR                            --}}
        {{-- ============================================== --}}
        <div class="mb-1 bg-white p-2 rounded-lg border border-gray-600 shadow-sm">
            <form action="{{ url()->current() }}" method="GET">
                {{-- BARIS 1: Judul & Tombol Blangko --}}
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center pb-2 mb-2 border-b border-gray-200 gap-2">
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-bold text-gray-800 uppercase tracking-tight leading-none">
                            Rekap Data Santri
                        </h2>
                        <span class="text-xs bg-green-100 text-green-800 font-bold px-2 py-0.5 rounded-full border border-green-300">
                            Total: {{ $lembagas->total() }} Lembaga
                        </span>
                    </div>

                    {{-- Tombol Download Template Blangko Santri --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ route('lembaga.template_santri') }}" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-md text-xs font-bold shadow-sm gap-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Blangko Santri (Excel)
                        </a>
                    </div>
                </div>

                {{-- BARIS 2: Filter Pencarian (Terkunci 1 Baris Sejajar 5 Kolom) --}}
                <div class="grid gap-1.5 items-end text-xs w-full" style="grid-template-columns: repeat(5, minmax(0, 1fr));">
                    
                    {{-- 1. Cari Lembaga --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider block mb-0.5">Cari Lembaga</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama lembaga..." 
                               class="w-full border border-gray-400 rounded-md px-2 py-1 text-xs h-[32px] focus:outline-none focus:border-green-600">
                    </div>

                    {{-- 2. Jenis Lembaga --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider block mb-0.5">Jenis Lembaga</label>
                        <select name="filter_jenis" class="w-full border border-gray-400 rounded-md px-2 py-1 text-xs h-[32px] focus:outline-none focus:border-green-600 bg-white">
                            <option value="">- Semua Jenis -</option>
                            <option value="TPQ" {{ request('filter_jenis') == 'TPQ' ? 'selected' : '' }}>TPQ</option>
                            <option value="MADIN" {{ request('filter_jenis') == 'MADIN' ? 'selected' : '' }}>MADIN</option>
                            <option value="PONPES" {{ request('filter_jenis') == 'PONPES' ? 'selected' : '' }}>PONPES</option>
                        </select>
                    </div>

                    {{-- 3. Filter Kecamatan --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider block mb-0.5">Kecamatan</label>
                        <select name="filter_kecamatan" id="filter_kecamatan" class="w-full border border-gray-400 rounded-md px-2 py-1 text-xs h-[32px] focus:outline-none focus:border-green-600 bg-white">
                            @if(Auth::user()->role != 'korcam')
                                <option value="">- Semua Kecamatan -</option>
                            @endif
                            @foreach($data_kecamatan as $kec)
                                <option value="{{ $kec->id }}" {{ (request('filter_kecamatan') == $kec->id || Auth::user()->role == 'korcam') ? 'selected' : '' }}>
                                    {{ $kec->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. Desa --}}
                    <div class="w-full">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-wider block mb-0.5">Desa</label>
                        <select name="filter_desa" id="filter_desa" class="w-full border border-gray-400 rounded-md px-2 py-1 text-xs h-[32px] focus:outline-none focus:border-green-600 bg-white">
                            <option value="">- Pilih Kecamatan Dulu -</option>
                        </select>
                        <div id="allDesasDataFilter" class="hidden">
                            @if(isset($data_desa))
                                @foreach($data_desa as $d)
                                    <div data-kecamatan-id="{{ $d->kecamatan_id }}" data-id="{{ $d->id }}" data-nama="{{ $d->nama_desa }}"></div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- 5. Tombol Aksi --}}
                    <div class="w-full flex gap-1 h-[32px]">
                        <button type="submit" class="flex-1 bg-green-600 text-white rounded text-xs font-bold hover:bg-green-700 shadow-sm transition">Cari</button>
                        <a href="{{ route('santri.index') }}" class="flex-1 bg-gray-100 text-gray-700 border border-gray-300 rounded text-xs font-bold hover:bg-gray-200 flex items-center justify-center transition">Reset</a>
                    </div>

                </div>
            </form>
        </div>

        {{-- ============================================== --}}
        {{-- TABEL REKAP SANTRI                             --}}
        {{-- ============================================== --}}
        <div class="border border-gray-600 bg-white overflow-hidden rounded shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-800 uppercase text-[10px] font-bold border-b border-gray-600 h-9">
                            <th class="border border-gray-400 px-2 py-1 text-center w-12">No</th>
                            <th class="border border-gray-400 px-3 py-1 text-left">Nama Lembaga</th>
                            <th class="border border-gray-400 px-2 py-1 text-center w-24">Jenis</th>
                            <th class="border border-gray-400 px-3 py-1 text-left">Kecamatan / Desa</th>
                            <th class="border border-gray-400 px-2 py-1 text-center w-24 bg-blue-50">Santri (L)</th>
                            <th class="border border-gray-400 px-2 py-1 text-center w-24 bg-pink-50">Santri (P)</th>
                            <th class="border border-gray-400 px-2 py-1 text-center w-28 bg-emerald-50">Total Santri</th>
                            <th class="border border-gray-400 px-3 py-1 text-center w-48">Berkas Excel Santri</th>
                            <th class="border border-gray-400 px-2 py-1 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 text-gray-800 font-semibold text-[11px]">
                        @forelse($lembagas as $index => $lembaga)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="border border-gray-400 py-2 text-center text-gray-500">
                                    {{ $lembagas->firstItem() + $index }}
                                </td>
                                <td class="border border-gray-400 px-3 py-2 font-bold text-gray-900">
                                    {{ $lembaga->nama_lembaga }}
                                </td>
                                <td class="border border-gray-400 px-2 py-2 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border 
                                        {{ $lembaga->jenis_lembaga == 'TPQ' ? 'text-green-700 bg-green-50 border-green-300' : ($lembaga->jenis_lembaga == 'MADIN' ? 'text-blue-700 bg-blue-50 border-blue-300' : 'text-purple-700 bg-purple-50 border-purple-300') }}">
                                        {{ $lembaga->jenis_lembaga }}
                                    </span>
                                </td>
                                <td class="border border-gray-400 px-3 py-2">
                                    <div>Kec. {{ $lembaga->kecamatan->nama_kecamatan ?? '-' }}</div>
                                    <div class="text-[10px] text-gray-500 font-normal">Desa {{ $lembaga->desa->nama_desa ?? '-' }}</div>
                                </td>
                                <td class="border border-gray-400 px-2 py-2 text-center font-bold text-blue-700 bg-blue-50/50">
                                    {{ $lembaga->jumlah_santri_l ?? 0 }}
                                </td>
                                <td class="border border-gray-400 px-2 py-2 text-center font-bold text-pink-700 bg-pink-50/50">
                                    {{ $lembaga->jumlah_santri_p ?? 0 }}
                                </td>
                                <td class="border border-gray-400 px-2 py-2 text-center font-black text-emerald-800 bg-emerald-50/50 text-sm">
                                    {{ $lembaga->jumlah_santri ?? 0 }}
                                </td>
                                <td class="border border-gray-400 px-3 py-2 text-center">
                                    @if($lembaga->file_skam)
                                        <a href="{{ route('lembaga.download_santri', $lembaga->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded text-[10px] font-bold hover:bg-emerald-200 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Unduh Excel
                                        </a>
                                    @else
                                        <span class="text-[10px] text-gray-400 italic">Input Manual (Belum Ada File)</span>
                                    @endif
                                </td>
                                <td class="border border-gray-400 py-1 text-center align-middle">
                                    <div class="flex justify-center gap-1 px-1">
                                        {{-- LIHAT (Mata Biru) --}}
                                        @if($lembaga->file_skam)
                                            @php
                                                $urlSantri = route('lembaga.preview_santri', $lembaga->id);
                                                $judulSantri = $lembaga->jenis_lembaga . ' - ' . addslashes($lembaga->nama_lembaga);
                                            @endphp
                                            <button type="button" 
                                                    onclick="bukaPreviewSantri('{{ $urlSantri }}', '{{ $judulSantri }}')" 
                                                    class="p-1 bg-blue-100 text-blue-700 border border-blue-300 rounded hover:bg-blue-200 transition" 
                                                    title="Lihat Preview Excel">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                        @endif

                                        {{-- EDIT (Pensil Kuning) --}}
                                        <a href="{{ route('lembaga.edit', $lembaga->id) }}" 
                                           class="p-1 bg-yellow-100 text-yellow-700 border border-yellow-300 rounded hover:bg-yellow-200 transition" 
                                           title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-6 text-center text-gray-400 bg-gray-50">
                                    Belum ada data lembaga atau santri yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-2 text-xs">
            {{ $lembagas->links() }}
        </div>

    </div>

    {{-- ======================================================== --}}
    {{-- 📑 KOTAK POP-UP MODAL PREVIEW DATA EXCEL SANTRI (COMPACT) --}}
    {{-- ======================================================== --}}
    <div id="modalPreviewSantri" style="z-index: 9999999 !important;" class="fixed inset-0 bg-black bg-opacity-75 hidden flex justify-center items-center p-4 backdrop-blur-sm transition-opacity">
        {{-- Ukuran Modal Dibuat Pas di Laptop (max-w-3xl dan max-h-[65vh]) --}}
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[65vh] flex flex-col border border-gray-400 overflow-hidden" style="z-index: 10000000 !important;">
            
            {{-- Header Pop-Up --}}
            <div class="flex justify-between items-center p-2.5 bg-gray-100 border-b border-gray-400 select-none flex-shrink-0">
                <div class="flex items-center gap-2">
                    <h3 class="text-xs font-bold text-black-800 tracking-wide uppercase">Data Santri</h3>
                    <span id="judulModalSantri" class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-300"></span>
                </div>
                <button type="button" onclick="tutupPreviewSantri()" class="text-black-500 hover:text-red-600 font-black text-xl leading-none px-2 transition cursor-pointer">&times;</button>
            </div>

            {{-- Area Tabel Terbatas (Hanya Bagian Ini yang Scroll) --}}
            <div class="p-2 bg-gray-100 overflow-hidden flex flex-col flex-grow">
                <div id="loadingSantri" class="text-center py-10 text-xs font-bold text-gray-500">
                    Memuat data santri...
                </div>
                <div class="overflow-y-auto overflow-x-auto border border-gray-400 rounded bg-white shadow-sm flex-grow" style="max-height: calc(65vh - 70px);">
                    <table id="tabelPreviewSantri" class="w-full text-xs border-collapse hidden">
                        <thead id="headerPreviewSantri" class="bg-gray-100 text-gray-800 font-bold border-b border-gray-400 sticky top-0 z-20 shadow-sm"></thead>
                        <tbody id="bodyPreviewSantri" class="divide-y divide-gray-300 font-semibold text-[11px]"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function bukaPreviewSantri(urlEndpoint, namaLembaga) {
            const modal = document.getElementById('modalPreviewSantri');
            const judul = document.getElementById('judulModalSantri');
            const loading = document.getElementById('loadingSantri');
            const tabel = document.getElementById('tabelPreviewSantri');
            const thead = document.getElementById('headerPreviewSantri');
            const tbody = document.getElementById('bodyPreviewSantri');

            judul.innerText = namaLembaga;
            loading.classList.remove('hidden');
            loading.innerText = 'Memuat data santri...';
            tabel.classList.add('hidden');
            thead.innerHTML = '';
            tbody.innerHTML = '';

            modal.classList.remove('hidden');

            fetch(urlEndpoint)
                .then(res => res.json())
                .then(res => {
                    loading.classList.add('hidden');
                    if (!res.success || !res.rows || res.rows.length === 0) {
                        loading.innerText = res.message || 'File Excel kosong.';
                        loading.classList.remove('hidden');
                        return;
                    }

                    tabel.classList.remove('hidden');
                    
                    // 1. Deteksi batas kolom terakhir yang memiliki teks (membuang kolom kosong tak terpakai)
                    const headerRow = res.rows[0];
                    let lastValidCol = 0;
                    headerRow.forEach((h, idx) => {
                        if (h !== null && String(h).trim() !== '') {
                            lastValidCol = idx;
                        }
                    });

                    // 2. Render Header (Sticky Tetap Terlihat Saat Di-scroll)
                    let thHtml = '<tr class="h-8">';
                    for (let c = 0; c <= lastValidCol; c++) {
                        thHtml += `<th class="border border-gray-400 px-2 py-1 text-center bg-gray-100 uppercase text-[10px] tracking-wider sticky top-0 z-20 shadow-sm">${headerRow[c] ?? '-'}</th>`;
                    }
                    thHtml += '</tr>';
                    thead.innerHTML = thHtml;

                    // 3. Render Body Baris Data Santri
                    let trHtml = '';
                    for (let i = 1; i < res.rows.length; i++) {
                        const row = res.rows[i];
                        // Lewati jika satu baris kosong semua
                        if (row.slice(0, lastValidCol + 1).every(cell => cell === null || String(cell).trim() === '')) continue;

                        trHtml += '<tr class="hover:bg-blue-50/50 transition">';
                        for (let c = 0; c <= lastValidCol; c++) {
                            const cell = row[c];
                            const isCenter = (c === 0 || c === 2 || c === 3); // Rata tengah kolom No, Gender, Usia
                            trHtml += `<td class="border border-gray-300 px-3 py-1.5 ${isCenter ? 'text-center' : 'text-left'}">${cell ?? '-'}</td>`;
                        }
                        trHtml += '</tr>';
                    }
                    tbody.innerHTML = trHtml;
                })
                .catch(err => {
                    loading.innerText = 'Gagal memuat berkas Excel.';
                    loading.classList.remove('hidden');
                });
        }

        function tutupPreviewSantri() {
            document.getElementById('modalPreviewSantri').classList.add('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                tutupPreviewSantri();
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kecSelect = document.getElementById('filter_kecamatan');
            const desaSelect = document.getElementById('filter_desa');
            const oldDesa = "{{ request('filter_desa') }}";

            const allDesas = Array.from(document.querySelectorAll('#allDesasDataFilter div')).map(div => ({
                kecamatan_id: div.getAttribute('data-kecamatan-id'),
                id: div.getAttribute('data-id'),
                nama: div.getAttribute('data-nama')
            }));

            function updateDesaOptions() {
                const kecId = kecSelect.value;
                desaSelect.innerHTML = '';

                if (kecId) {
                    desaSelect.disabled = false;
                    const optDefault = document.createElement('option');
                    optDefault.value = '';
                    optDefault.textContent = '- Semua Desa -';
                    desaSelect.appendChild(optDefault);

                    const filtered = allDesas.filter(d => d.kecamatan_id === kecId);
                    filtered.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.id;
                        opt.textContent = d.nama;
                        if (d.id === oldDesa) opt.selected = true;
                        desaSelect.appendChild(opt);
                    });
                } else {
                    desaSelect.disabled = true;
                    const optDisabled = document.createElement('option');
                    optDisabled.value = '';
                    optDisabled.textContent = '- Pilih Kecamatan Dulu -';
                    desaSelect.appendChild(optDisabled);
                }
            }

            kecSelect.addEventListener('change', function() {
                updateDesaOptions();
            });

            // Jalankan saat pertama kali halaman terbuka
            updateDesaOptions();
        });
    </script>
</x-app-layout>