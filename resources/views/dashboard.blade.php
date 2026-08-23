<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Dashboard Eksekutif') }}
        </h2>
        <p class="text-sm text-slate-500 mt-1">Wilayah Data: <b>{{ $wilayahKerja }}</b></p>
    </x-slot>

    <div class="py-0">
        {{-- ==================================================== --}}
        {{-- BAGIAN ATAS: 3 KARTU RINGKASAN --}}
        {{-- ==================================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0 mb-0">
            
            {{-- KARTU 1: DATA LEMBAGA --}}
            <div class="bg-white rounded-2xl border border-gray-300 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] hover:shadow-lg transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-center mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">KOMPOSISI JENIS LEMBAGA</h3>
                        </div>
                    </div>
                    <div class="relative h-48 w-full mb-6">
                        <canvas id="chartLembaga"></canvas>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-300">
                    <div class="grid grid-cols-3 gap-2 mb-4 text-center border-b border-gray-200 pb-4">
                        <div>
                            <span class="block text-[10px] text-slate-600 font-bold uppercase">TPQ</span>
                            <span class="text-lg font-bold text-emerald-700">{{ number_format($lembagaTPQ) }}</span>
                        </div>
                        <div class="border-x border-gray-200">
                            <span class="block text-[10px] text-slate-600 font-bold uppercase">Madin</span>
                            <span class="text-lg font-bold text-blue-700">{{ number_format($lembagaMadin) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-slate-600 font-bold uppercase">Ponpes</span>
                            <span class="text-lg font-bold text-purple-700">{{ number_format($lembagaPonpes) }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-orange-100 text-orange-600 rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-600 font-bold">TOTAL GURU</p>
                                <p class="text-sm font-extrabold text-slate-800">{{ number_format($totalGuru) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-teal-100 text-teal-600 rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-600 font-bold">TOTAL SANTRI</p>
                                <p class="text-sm font-extrabold text-slate-800">{{ number_format($totalSantri) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU 2: STATUS GURU --}}
            <div class="bg-white rounded-2xl border border-gray-300 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] hover:shadow-lg transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-center mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">STATUS KEPEGAWAIAN GURU</h3>
                        </div>
                    </div>
                    <div class="relative h-48 w-full mb-6">
                        <canvas id="chartGuru"></canvas>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-300">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center justify-between bg-white p-2 rounded border border-gray-300">
                            <span class="text-[10px] text-slate-600 font-bold">PNS</span>
                            <span class="text-sm font-bold text-blue-600">{{ number_format($guruPNS) }}</span>
                        </div>
                        <div class="flex items-center justify-between bg-white p-2 rounded border border-gray-300">
                            <span class="text-[10px] text-slate-600 font-bold">PPPK</span>
                            <span class="text-sm font-bold text-emerald-600">{{ number_format($guruP3KFull) }}</span>
                        </div>
                        <div class="flex items-center justify-between bg-white p-2 rounded border border-gray-300">
                            <span class="text-[10px] text-slate-600 font-bold">P3K Paruh</span>
                            <span class="text-sm font-bold text-amber-600">{{ number_format($guruP3KParuh) }}</span>
                        </div>
                        <div class="flex items-center justify-between bg-white p-2 rounded border border-gray-300">
                            <span class="text-[10px] text-slate-600 font-bold">Non ASN</span>
                            <span class="text-sm font-bold text-purple-600">{{ number_format($guruNonASN) }}</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-center text-slate-500 mt-3 italic">*Data Non-ASN lainnya: {{ number_format($guruNonASN) }}</p>
                </div>
            </div>

            {{-- KARTU 3: STATUS INSENTIF --}}
            <div class="bg-white rounded-2xl border border-gray-300 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] hover:shadow-lg transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-center mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">RASIO PENYALURAN INSENTIF</h3>
                        </div>
                    </div>
                    <div class="relative h-48 w-full mb-6">
                        <canvas id="chartInsentif"></canvas>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-300">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-xs text-slate-600 font-medium">JATAH KUOTA INSENTIF</span>
                        <span class="text-xs font-bold bg-gray-200 text-slate-700 px-2 py-0.5 rounded-full">{{ number_format($targetInsentif) }} Orang</span>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-end">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                <div>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase">Guru yang diajukan</p>
                                    <p class="text-sm font-bold text-slate-800">{{ number_format($sudahTerimaInsentif) }} <span class="text-[10px] font-normal text-slate-500">Guru</span></p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-emerald-700">{{ $persenSudah }}%</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                                <div>
                                    <p class="text-[10px] text-slate-600 font-bold uppercase">Sisa Kuota Belum Diajukan</p>
                                    <p class="text-sm font-bold text-slate-800">{{ number_format($belumTerimaInsentif) }} <span class="text-[10px] font-normal text-slate-500">Slot</span></p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-red-700">{{ $persenBelum }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- ==================================================== --}}
        {{-- BAGIAN TENGAH: PROGRESS PEMBERKASAN (BARU) --}}
        {{-- ==================================================== --}}
        <div class="bg-white rounded-2xl border border-gray-300 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] mb-0 transition duration-300 hover:shadow-lg">
            <div class="flex items-center gap-3 mb-5 border-b border-gray-300 pb-4">
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg leading-tight">Status Pemberkasan Dokumen</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Pemantauan Kelengkapan File</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 px-2">
                {{-- Progress Lembaga --}}
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <p class="text-sm font-bold text-slate-700">Dokumen Legalitas Lembaga</p>
                            <p class="text-[10px] text-slate-600 mt-0.5"><span class="font-bold text-slate-700">{{ number_format($lembagaLengkap) }}</span> dari {{ number_format($totalLembagaBerkas) }} Lembaga</p>
                        </div>
                        <span class="text-xl font-black {{ $persenLembaga == 100 ? 'text-emerald-700' : 'text-amber-700' }}">{{ $persenLembaga }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 mb-1.5 overflow-hidden border border-gray-200">
                        <div class="{{ $persenLembaga == 100 ? 'bg-emerald-600' : 'bg-amber-600' }} h-3 rounded-full transition-all duration-1000 ease-out relative" style="width: {{ $persenLembaga }}%">
                            {{-- Efek Kilau --}}
                            <div class="absolute top-0 left-0 bottom-0 right-0 bg-white opacity-20" style="background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;"></div>
                        </div>
                    </div>
                    @if($persenLembaga < 100)
                        <p class="text-[10px] text-red-700 font-bold italic">* {{ number_format($totalLembagaBerkas - $lembagaLengkap) }} Lembaga Dokumennya Belum Lengkap</p>
                    @else
                        <p class="text-[10px] text-emerald-700 font-bold italic">✅ Sempurna! Seluruh berkas lembaga disetujui.</p>
                    @endif
                </div>

                {{-- Progress Guru --}}
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <p class="text-sm font-bold text-slate-700">Dokumen Identitas Guru</p>
                            <p class="text-[10px] text-slate-500 mt-0.5"><span class="font-bold text-slate-700">{{ number_format($guruLengkap) }}</span> dari {{ number_format($totalGuruBerkas) }} Guru</p>
                        </div>
                        <span class="text-xl font-black {{ $persenGuru == 100 ? 'text-emerald-500' : 'text-orange-500' }}">{{ $persenGuru }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 mb-1.5 overflow-hidden border border-gray-200">
                        <div class="{{ $persenGuru == 100 ? 'bg-emerald-500' : 'bg-orange-500' }} h-3 rounded-full transition-all duration-1000 ease-out relative" style="width: {{ $persenGuru }}%">
                             {{-- Efek Kilau --}}
                             <div class="absolute top-0 left-0 bottom-0 right-0 bg-white opacity-20" style="background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;"></div>
                        </div>
                    </div>
                    @if($persenGuru < 100)
                        <p class="text-[10px] text-red-500 font-bold italic">* {{ number_format($totalGuruBerkas - $guruLengkap) }} Guru Dokumennya Belum Lengkap</p>
                    @else
                        <p class="text-[10px] text-emerald-600 font-bold italic">✅ Sempurna! Seluruh berkas guru disetujui.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ==================================================== --}}
        {{-- BAGIAN BAWAH 1: GRAFIK SEBARAN KECAMATAN --}}
        {{-- ==================================================== --}}
        <div class="bg-white rounded-2xl border border-gray-300 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)]">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-2 gap-4">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Peta Sebaran Lembaga</h3>
                    <p class="text-sm text-slate-500">Statistik Total Lembaga per Wilayah</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                    {{-- FILTER KECAMATAN LEMBAGA --}}
                    @if(Auth::user()->role != 'korcam')
                        <div class="w-full sm:w-48">
                            <select id="filterKecamatanLembaga" aria-label="Pilih Kecamatan Lembaga" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2 font-semibold text-slate-700 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 cursor-pointer hover:bg-slate-100 transition">
                                <option value="ALL" selected>SEMUA KECAMATAN</option>
                                @foreach($kecamatans as $kec)
                                    <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" id="filterKecamatanLembaga" value="{{ Auth::user()->kecamatan_id }}">
                        <span class="bg-blue-50 border border-blue-100 text-blue-700 font-bold px-4 py-2 rounded-lg text-sm shadow-sm">Kec. {{ $kecamatanLabels[0] ?? '' }}</span>
                    @endif

                    {{-- Legend Custom --}}
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded bg-green-500"></span>
                            <span class="text-xs text-slate-600">TPQ</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded bg-blue-500"></span>
                            <span class="text-xs text-slate-600">Madin</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded bg-purple-600"></span>
                            <span class="text-xs text-slate-600">Ponpes</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-1 rounded bg-orange-400"></span>
                            <span class="text-xs text-slate-600">Total</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative w-full h-[350px]"> 
                <canvas id="chartSebaranLembaga"></canvas>
            </div>
        </div>

        {{-- ==================================================== --}}
        {{-- BAGIAN BAWAH 2: GRAFIK SEBARAN GURU PER DESA (BARU) --}}
        {{-- ==================================================== --}}
        <div class="bg-white rounded-2xl border border-gray-300 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] ">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-1 gap-4">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Distribusi Guru per Desa/Kelurahan</h3>
                    <p class="text-sm text-slate-500">Statistik Rinci Tenaga Pendidik</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                    
                    {{-- FILTER KECAMATAN (Hanya Admin yang bisa ubah) --}}
                    @if(Auth::user()->role != 'korcam')
                        <div class="w-full sm:w-48">
                            <select id="filterKecamatanGuru" aria-label="Pilih Kecamatan" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2 font-semibold text-slate-700 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 cursor-pointer hover:bg-slate-100 transition">
                                <option value="ALL" selected>SEMUA KECAMATAN</option>
                                @foreach($kecamatans as $kec)
                                    <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        {{-- Jika Korcam, beri filter tersembunyi dan badge nama kecamatannya --}}
                        <input type="hidden" id="filterKecamatanGuru" value="{{ Auth::user()->kecamatan_id }}">
                        <span class="bg-blue-50 border border-blue-100 text-blue-700 font-bold px-4 py-2 rounded-lg text-sm shadow-sm">Kec. {{ $kecamatanLabels[0] ?? '' }}</span>
                    @endif

                    {{-- Legend Custom --}}
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded bg-green-500"></span>
                            <span class="text-xs text-slate-600">Guru TPQ</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded bg-blue-500"></span>
                            <span class="text-xs text-slate-600">Guru Madin</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded bg-purple-600"></span>
                            <span class="text-xs text-slate-600">Guru Ponpes</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-1 rounded bg-orange-400"></span>
                            <span class="text-xs text-slate-600">Total</span>
                        </div>
                    </div>


                </div>
            </div>

            <div class="relative w-full h-[400px]"> 
                <canvas id="chartGuruDesa"></canvas>
            </div>
        </div>

        {{-- ==================================================== --}}
        {{-- BAGIAN BAWAH 3: PETA SEBARAN LEMBAGA (LEAFLET GIS)  --}}
        {{-- ==================================================== --}}
        <div class="bg-white rounded-2xl border border-gray-300 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] mt-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-4">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Pemetaan Geografis Sebaran Lembaga</h3>
                    <p class="text-sm text-slate-500">Titik Koordinat Lembaga Se-Kabupaten Kediri</p>
                </div>
                
                {{-- [BARU] Filter Interaktif Jenis Lembaga (Klik untuk Menyortir Pin Peta) --}}
                <div class="flex flex-wrap items-center gap-1.5 text-xs font-bold" id="legendFilterContainer">
                    <span class="text-[10px] text-slate-400 uppercase tracking-wider mr-1 hidden sm:inline">Filter Lembaga:</span>
                    
                    {{-- Tombol Semua --}}
                    <button type="button" onclick="filterMapMarkers('ALL', this)" class="btn-filter-map active px-3 py-1.5 rounded-lg border border-slate-300 bg-slate-800 text-black transition shadow-sm hover:opacity-90 flex items-center gap-1 text-[11px]">
                        <span>Semua</span>
                    </button>

                    {{-- Tombol TPQ --}}
                    <button type="button" onclick="filterMapMarkers('TPQ', this)" class="btn-filter-map px-3 py-1.5 rounded-lg border border-emerald-300 bg-white text-emerald-700 transition shadow-sm hover:bg-emerald-50 flex items-center gap-1.5 text-[11px]">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 32" fill="#10b981">
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 8.5 12 20 12 20s12-11.5 12-20c0-6.627-5.373-12-12-12z"/>
                            <circle cx="12" cy="11" r="4" fill="#ffffff"/>
                        </svg>
                        <span>TPQ</span>
                    </button>

                    {{-- Tombol Madin --}}
                    <button type="button" onclick="filterMapMarkers('MADIN', this)" class="btn-filter-map px-3 py-1.5 rounded-lg border border-blue-300 bg-white text-blue-700 transition shadow-sm hover:bg-blue-50 flex items-center gap-1.5 text-[11px]">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 32" fill="#2563eb">
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 8.5 12 20 12 20s12-11.5 12-20c0-6.627-5.373-12-12-12z"/>
                            <circle cx="12" cy="11" r="4" fill="#ffffff"/>
                        </svg>
                        <span>Madin</span>
                    </button>

                    {{-- Tombol Ponpes --}}
                    <button type="button" onclick="filterMapMarkers('PONPES', this)" class="btn-filter-map px-3 py-1.5 rounded-lg border border-purple-300 bg-white text-purple-700 transition shadow-sm hover:bg-purple-50 flex items-center gap-1.5 text-[11px]">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 32" fill="#9333ea">
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 8.5 12 20 12 20s12-11.5 12-20c0-6.627-5.373-12-12-12z"/>
                            <circle cx="12" cy="11" r="4" fill="#ffffff"/>
                        </svg>
                        <span>Ponpes</span>
                    </button>
                </div>
            </div>

            {{-- Container Peta Leaflet --}}
            <div class="relative w-full rounded-xl overflow-hidden border border-gray-300 shadow-inner z-0" style="height: 500px;">
                <div id="mapLembaga" style="height: 500px; min-height: 500px; width: 100%;"></div>
            </div>
        </div>

    </div>

    {{-- SCRIPT CHART --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b';

        // 1. CHART LEMBAGA (Doughnut)
        new Chart(document.getElementById('chartLembaga'), {
            type: 'doughnut',
            data: {
                labels: ['TPQ', 'Madin', 'Ponpes'],
                datasets: [{
                    data: [{{ $lembagaTPQ }}, {{ $lembagaMadin }}, {{ $lembagaPonpes }}],
                    backgroundColor: ['#10b981', '#3b82f6', '#9333ea'], 
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { display: false } } }
        });

        // 2. CHART GURU (Pie)
        new Chart(document.getElementById('chartGuru'), {
            type: 'pie',
            data: {
                labels: ['Non-ASN', 'PNS', 'P3K Full', 'P3K Paruh', 'Inpassing'],
                datasets: [{
                    data: [{{ $guruNonASN }}, {{ $guruPNS }}, {{ $guruP3KFull }}, {{ $guruP3KParuh }}, {{ $guruInpassing }}], 
                    backgroundColor: ['#e2e8f0', '#3b82f6', '#10b981', '#f59e0b', '#9333ea'], 
                    borderWidth: 2, borderColor: '#ffffff'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        // 3. CHART INSENTIF (Doughnut)
        new Chart(document.getElementById('chartInsentif'), {
            type: 'doughnut',
            data: {
                labels: ['Sudah Diajukan', 'Sisa Kuota Tersedia'],
                datasets: [{
                    data: [{{ $sudahTerimaInsentif }}, {{ $belumTerimaInsentif }}],
                    backgroundColor: ['#059669', '#f87171'], borderWidth: 0,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false } } }
        });



        // ==========================================
        // 4. CHART LEMBAGA (DINAMIS: FILTER SEMUA & PER KECAMATAN)
        // ==========================================
        const dataLembagaDesa = {!! json_encode($sebaranLembagaPerKecamatan ?? []) !!};
        const defaultLembagaSemua = {
            labels: {!! json_encode($kecamatanLabels) !!},
            total: {!! json_encode($dataTotalSebaran) !!},
            tpq: {!! json_encode($dataTpqSebaran) !!},
            madin: {!! json_encode($dataMadinSebaran) !!},
            ponpes: {!! json_encode($dataPonpesSebaran ?? []) !!}
        };

        const ctxSebaranLembaga = document.getElementById('chartSebaranLembaga');
        let chartLembagaObj;

        function updateLembagaChart(kecamatanId) {
            let chartData;

            // 1. JIKA PILIH "SEMUA KECAMATAN" -> TAMPILKAN DATA SE-KABUPATEN
            if (kecamatanId === 'ALL') {
                chartData = {
                    labels: defaultLembagaSemua.labels,
                    datasets: [
                        {
                            type: 'line', label: 'Total Lembaga', data: defaultLembagaSemua.total,
                            borderColor: '#fb923c', backgroundColor: '#fb923c', borderWidth: 2,
                            pointBackgroundColor: '#fff', pointBorderColor: '#fb923c', pointRadius: 4, pointHoverRadius: 6, tension: 0.4, order: 0 
                        },
                        {
                            type: 'bar', label: 'TPQ', data: defaultLembagaSemua.tpq,
                            backgroundColor: '#10b981', borderRadius: 4, barPercentage: 0.4, maxBarThickness: 30, order: 1
                        },
                        {
                            type: 'bar', label: 'Madin', data: defaultLembagaSemua.madin,
                            backgroundColor: '#3b82f6', borderRadius: 4, barPercentage: 0.4, maxBarThickness: 30, order: 1
                        },
                        {
                            type: 'bar', label: 'Ponpes', data: defaultLembagaSemua.ponpes,
                            backgroundColor: '#9333ea', borderRadius: 4, barPercentage: 0.4, maxBarThickness: 30, order: 1
                        }
                    ]
                };
            } else {
                // 2. JIKA PILIH KECAMATAN TERTENTU -> TAMPILKAN RINCIAN PER DESA
                const dataKec = dataLembagaDesa[kecamatanId];
                
                if (!dataKec) {
                    if (chartLembagaObj) {
                        chartLembagaObj.data.labels = [];
                        chartLembagaObj.data.datasets.forEach(ds => ds.data = []);
                        chartLembagaObj.update();
                    }
                    return;
                }

                chartData = {
                    labels: dataKec.labels,
                    datasets: [
                        {
                            type: 'line', label: 'Total Lembaga', data: dataKec.total,
                            borderColor: '#fb923c', backgroundColor: '#fb923c', borderWidth: 2,
                            pointBackgroundColor: '#fff', pointBorderColor: '#fb923c', pointRadius: 4, pointHoverRadius: 6, tension: 0.4, order: 0 
                        },
                        {
                            type: 'bar', label: 'TPQ', data: dataKec.tpq,
                            backgroundColor: '#10b981', borderRadius: 4, barPercentage: 0.4, maxBarThickness: 30, order: 1
                        },
                        {
                            type: 'bar', label: 'Madin', data: dataKec.madin,
                            backgroundColor: '#3b82f6', borderRadius: 4, barPercentage: 0.4, maxBarThickness: 30, order: 1
                        },
                        {
                            type: 'bar', label: 'Ponpes', data: dataKec.ponpes || [],
                            backgroundColor: '#9333ea', borderRadius: 4, barPercentage: 0.4, maxBarThickness: 30, order: 1
                        }
                    ]
                };
            }

            if (chartLembagaObj) {
                chartLembagaObj.data = chartData;
                chartLembagaObj.update();
            } else {
                chartLembagaObj = new Chart(ctxSebaranLembaga, {
                    type: 'bar',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.98)',
                                titleColor: '#0f172a',
                                bodyColor: '#475569',
                                borderColor: '#cbd5e1',
                                borderWidth: 1,
                                padding: 12,
                                usePointStyle: true,
                                boxPadding: 6,
                                titleFont: { size: 13, family: "'Inter', sans-serif", weight: 'bold' },
                                bodyFont: { size: 12, family: "'Inter', sans-serif" },
                                caretSize: 6,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                border: { dash: [5, 5], display: false },
                                grid: { color: '#e2e8f0', tickLength: 0 },
                                ticks: { font: { size: 11 }, padding: 10, stepSize: 5 }
                            },
                            x: {
                                border: { display: false },
                                grid: { display: false },
                                ticks: { font: { size: 11 }, maxRotation: 45, minRotation: 45 }
                            }
                        }
                    }
                });
            }
        }

        // Listener Filter Lembaga
        const selectKecamatanLembaga = document.getElementById('filterKecamatanLembaga');
        if (selectKecamatanLembaga) {
            updateLembagaChart(selectKecamatanLembaga.value);
            if (selectKecamatanLembaga.tagName.toLowerCase() === 'select') {
                selectKecamatanLembaga.addEventListener('change', function() {
                    updateLembagaChart(this.value);
                });
            }
        }

        // ==========================================
        // 5. CHART GURU PER DESA (BAWAH)
        // ==========================================
        const dataGuruDesa = {!! json_encode($sebaranGuruPerKecamatan) !!};
        const ctxGuruDesa = document.getElementById('chartGuruDesa');
        let chartGuruDesaObj; // Variable penampung grafik



        function updateGuruChart(kecamatanId) {
            let chartData;

            // JIKA PILIH "SEMUA KECAMATAN" -> TAMPILKAN DATA SE-KABUPATEN
            if (kecamatanId === 'ALL') {
                const allKecLabels = [];
                const allTpq = [];
                const allMadin = [];
                const allPonpes = [];
                const allTotal = [];

                const selectEl = document.getElementById('filterKecamatanGuru');
                if (selectEl && selectEl.tagName.toLowerCase() === 'select') {
                    Array.from(selectEl.options).forEach(opt => {
                        if (opt.value !== 'ALL' && dataGuruDesa[opt.value]) {
                            const dKec = dataGuruDesa[opt.value];
                            allKecLabels.push(opt.text);
                            
                            // Akumulasi total guru per kecamatan
                            const sumTpq = (dKec.tpq || []).reduce((a, b) => a + Number(b), 0);
                            const sumMadin = (dKec.madin || []).reduce((a, b) => a + Number(b), 0);
                            const sumPonpes = (dKec.ponpes || []).reduce((a, b) => a + Number(b), 0);
                            const sumTotal = (dKec.total || []).reduce((a, b) => a + Number(b), 0);

                            allTpq.push(sumTpq);
                            allMadin.push(sumMadin);
                            allPonpes.push(sumPonpes);
                            allTotal.push(sumTotal);
                        }
                    });
                }

                chartData = {
                    labels: allKecLabels, // Sumbu X: Nama-nama Kecamatan
                    datasets: [
                        {
                            type: 'line', label: 'Total Guru', data: allTotal,
                            borderColor: '#f97316', backgroundColor: '#f97316', borderWidth: 2,
                            pointBackgroundColor: '#ffffff', pointBorderColor: '#f97316',
                            pointRadius: 4, pointHoverRadius: 6, tension: 0.4, order: 0
                        },
                        {
                            type: 'bar', label: 'Guru TPQ', data: allTpq,
                            backgroundColor: '#10b981', borderRadius: 4,
                            barPercentage: 0.4, maxBarThickness: 30, order: 1
                        },
                        {
                            type: 'bar', label: 'Guru Madin', data: allMadin,
                            backgroundColor: '#3b82f6', borderRadius: 4,
                            barPercentage: 0.4, maxBarThickness: 30, order: 1
                        },
                        {
                            type: 'bar', label: 'Guru Ponpes', data: allPonpes,
                            backgroundColor: '#9333ea', borderRadius: 4,
                            barPercentage: 0.4, maxBarThickness: 30, order: 1
                        }
                    ]
                };
            } else {
                // JIKA PILIH KECAMATAN TERTENTU -> TAMPILKAN PER DESA
                const dataKec = dataGuruDesa[kecamatanId];
                
                if(!dataKec) {
                    if(chartGuruDesaObj) {
                        chartGuruDesaObj.data.labels = [];
                        chartGuruDesaObj.data.datasets.forEach(ds => ds.data = []);
                        chartGuruDesaObj.update();
                    }
                    return;
                }

                chartData = {
                    labels: dataKec.labels, // Sumbu X: Nama-nama Desa
                    datasets: [
                        {
                            type: 'line', label: 'Total Guru', data: dataKec.total,
                            borderColor: '#f97316', backgroundColor: '#f97316', borderWidth: 2,
                            pointBackgroundColor: '#ffffff', pointBorderColor: '#f97316', 
                            pointRadius: 4, pointHoverRadius: 6, tension: 0.4, order: 0
                        },
                        {
                            type: 'bar', label: 'Guru TPQ', data: dataKec.tpq,
                            backgroundColor: '#10b981', borderRadius: 4, 
                            barPercentage: 0.4, maxBarThickness: 30, order: 1
                        },
                        {
                            type: 'bar', label: 'Guru Madin', data: dataKec.madin,
                            backgroundColor: '#3b82f6', borderRadius: 4, 
                            barPercentage: 0.4, maxBarThickness: 30, order: 1
                        },
                        {
                            type: 'bar', label: 'Guru Ponpes', data: dataKec.ponpes || [],
                            backgroundColor: '#9333ea', borderRadius: 4, 
                            barPercentage: 0.4, maxBarThickness: 30, order: 1
                        }
                    ]
                };
            }



           if(chartGuruDesaObj) {
                chartGuruDesaObj.data = chartData;
                chartGuruDesaObj.update();
            } else {
                chartGuruDesaObj = new Chart(ctxGuruDesa, {
                    type: 'bar',
                    data: chartData,
                    // BAGIAN OPTIONS SUDAH DIPERBARUI DENGAN GAYA RECHARTS
                    options: {
                        responsive: true, 
                        maintainAspectRatio: false, 
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            // --- Kustomisasi Tooltip Gaya Recharts ---
                            tooltip: { 
                                backgroundColor: 'rgba(255, 255, 255, 0.98)', 
                                titleColor: '#0f172a', 
                                bodyColor: '#475569', 
                                borderColor: '#cbd5e1', 
                                borderWidth: 1, 
                                padding: 12, 
                                usePointStyle: true,
                                boxPadding: 6,
                                titleFont: { size: 13, family: "'Inter', sans-serif", weight: 'bold' },
                                bodyFont: { size: 12, family: "'Inter', sans-serif" },
                                caretSize: 6,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                border: { dash: [5, 5], display: false }, 
                                grid: { color: '#e2e8f0', tickLength: 0 }, 
                                ticks: { font: { size: 11 }, padding: 10, stepSize: 1 } 
                            },
                            x: { 
                                border: { display: false }, 
                                grid: { display: false }, 
                                ticks: { font: { size: 11 }, maxRotation: 45, minRotation: 45 } 
                            }
                        }
                    }
                });
            }

            
        }

        // Trigger Event saat halaman dimuat
        const selectKecamatan = document.getElementById('filterKecamatanGuru');
        if(selectKecamatan) {
            // Render grafik pertama kali menggunakan value yang sedang aktif
            updateGuruChart(selectKecamatan.value);
            
            // Tambahkan listener HANYA JIKA elemen tersebut adalah Dropdown (Admin)
            if(selectKecamatan.tagName.toLowerCase() === 'select') {
                selectKecamatan.addEventListener('change', function() {
                    updateGuruChart(this.value);
                });
            }
        }
    </script>

    {{-- ======================================================== --}}
    {{-- LEAFLET CSS & JS (100% GRATIS TANPA API KEY)             --}}
    {{-- ======================================================== --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
    /* 1. Perbesar Touch Target Tombol Zoom Leaflet (+ dan -) */
    .leaflet-touch .leaflet-bar a,
    .leaflet-control-zoom a {
        width: 44px !important;
        height: 44px !important;
        line-height: 44px !important;
        font-size: 18px !important;
    }
    
    /* 2. Perbesar Touch Target Tombol Layer Satelit */
    .leaflet-touch .leaflet-control-layers-toggle,
    .leaflet-control-layers-toggle {
        width: 44px !important;
        height: 44px !important;
    }

    /* 3. Pastikan Touch Target Tombol Filter Legenda Lega */
    .btn-filter-map {
        min-height: 38px;
        touch-action: manipulation;
    }
    </style>

    @php
        $markerData = [];
        if (isset($petaLembagas)) {
            foreach ($petaLembagas as $pl) {
                $raw = $pl->link_gmaps;
                $lat = null;
                $lng = null;

                // Ekstrak angka koordinat (contoh: -7.81234, 112.01234)
                if (preg_match('/([-+]?[0-9]+\.[0-9]+)[\s,]+([-+]?[0-9]+\.[0-9]+)/', $raw, $match)) {
                    $lat = (float)$match[1];
                    $lng = (float)$match[2];
                }

                if ($lat && $lng) {
                    $markerData[] = [
                        'nama'      => $pl->nama_lembaga,
                        'jenis'     => $pl->jenis_lembaga,
                        'kecamatan' => $pl->kecamatan->nama_kecamatan ?? '-',
                        'desa'      => $pl->desa->nama_desa ?? '-',
                        'santri'    => $pl->jumlah_santri ?? 0,
                        'status'    => $pl->status ?? 'AKTIF',
                        'lat'       => $lat,
                        'lng'       => $lng
                    ];
                }
            }
        }
    @endphp

    <script>
        function inisialisasiPeta() {
            const mapContainer = document.getElementById('mapLembaga');
            if (!mapContainer || typeof L === 'undefined') return;

            // 1. Koordinat Pusat: Kabupaten Kediri (-7.8480, 112.0178)
            const map = L.map('mapLembaga', {
                center: [-7.8480, 112.0178],
                zoom: 11,
                scrollWheelZoom: true
            });

            // Refresh kalkulasi ukuran peta agar ubin satelit langsung muncul penuh
            setTimeout(() => {
                map.invalidateSize();
            }, 300);

            // 2. Definisi Layer Tampilan Peta
            // A. Peta Jalan Standar (OpenStreetMap)
            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            });

            // B. Citra Satelit Asli (Esri World Imagery)
            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            });

            // C. Satelit Hibrida Google (Satelit + Nama Jalan & Batas Wilayah)
            const googleHybridLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                attribution: '&copy; Google Maps'
            });

            // Pasang Layer Google Hybrid sebagai default
            googleHybridLayer.addTo(map);

            // 3. Tombol Pemilih Layer di Kanan Atas
            const baseMaps = {
                "🛰️ Satelit + Nama Jalan (Google)": googleHybridLayer,
                "🌍 Citra Satelit Murni (Esri)": satelliteLayer,
                "🗺️ Peta Jalan Bersih (OSM)": osmLayer
            };
            L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);

            // 4. Render Marker Lembaga dengan Penampung Layer Khusus (Mendukung Filter)
            const listLembaga = {!! json_encode($markerData) !!};
            window.allMapMarkers = [];
            window.markerLayerGroup = L.layerGroup().addTo(map);

            listLembaga.forEach(item => {
                let markerColor = '#10b981'; // Hijau (TPQ)
                if (item.jenis === 'MADIN') markerColor = '#2563eb'; // Biru (MADIN)
                if (item.jenis === 'PONPES') markerColor = '#9333ea'; // Ungu (PONPES)

                // Template SVG Pin Modern dengan Aksesibilitas Penuh (ARIA)
                const pinSvg = `
                    <div role="button" aria-label="Lokasi ${item.jenis} ${item.nama}" style="display: flex; justify-content: center; align-items: center; filter: drop-shadow(0 4px 5px rgba(0,0,0,0.45)); width: 32px; height: 40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 32" width="30" height="38" aria-hidden="true">
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 8.5 12 20 12 20s12-11.5 12-20c0-6.627-5.373-12-12-12z" fill="${markerColor}" stroke="#ffffff" stroke-width="1.2"/>
                            <circle cx="12" cy="11" r="5.5" fill="#ffffff"/>
                            <circle cx="12" cy="11" r="3.2" fill="${markerColor}"/>
                        </svg>
                    </div>
                `;

                // Buat Ikon Pin Leaflet (Ukuran Sentuhan Standar Lighthouse 44x48px)
                const customIcon = L.divIcon({
                    html: pinSvg,
                    className: '',
                    iconSize: [44, 48],
                    iconAnchor: [22, 48],
                    popupAnchor: [0, -44]
                });

                // Buat Marker Lengkap dengan Title & Alt (Screen Reader Friendly)
                const marker = L.marker([item.lat, item.lng], { 
                    icon: customIcon,
                    title: `${item.jenis} - ${item.nama}`,
                    alt: `Pin lokasi ${item.jenis} ${item.nama}`
                });

                // Popup Info Saat Pin Diklik (Lengkap dengan Tombol Google Maps Langsung)
                const gmapsUrl = `https://www.google.com/maps?q=${item.lat},${item.lng}`;

                const popupContent = `
                    <div style="font-family: 'Inter', sans-serif; font-size: 11px; min-width: 190px; padding: 2px 0;">
                        <span style="display:inline-block; padding: 2px 7px; border-radius: 4px; font-weight: bold; font-size: 9px; color: #fff; background-color: ${markerColor}; margin-bottom: 5px;">
                            ${item.jenis}
                        </span>
                        <strong style="display:block; font-size: 12px; color: #0f172a; text-transform: uppercase; margin-bottom: 2px; line-height: 1.2;">
                            ${item.nama}
                        </strong>
                        <span style="color: #64748b; font-weight: 600; display: block; margin-bottom: 6px;">
                            Desa ${item.desa}, Kec. ${item.kecamatan}
                        </span>
                        <div style="border-top: 1px solid #e2e8f0; padding-top: 5px; margin-bottom: 8px; display: flex; justify-content: space-between; color: #334155;">
                            <span>Santri: <b>${item.santri}</b></span>
                            <span style="color: ${item.status === 'AKTIF' ? '#16a34a' : '#dc2626'}; font-weight: bold;">${item.status}</span>
                        </div>

                        <!-- Tombol Direct Google Maps -->
                        <a href="${gmapsUrl}" target="_blank" rel="noopener noreferrer" 
                           style="display: flex; align-items: center; justify-content: center; gap: 6px; width: 100%; padding: 6px 0; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 11px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); transition: background-color 0.2s;"
                           onmouseover="this.style.backgroundColor='#1d4ed8'" 
                           onmouseout="this.style.backgroundColor='#2563eb'">
                            <svg style="width: 13px; height: 13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Buka di Google Maps
                        </a>
                    </div>
                `;
                marker.bindPopup(popupContent);

                // Tandai jenis lembaga pada marker dan simpan ke layer
                marker.jenisLembaga = item.jenis;
                window.allMapMarkers.push(marker);
                window.markerLayerGroup.addLayer(marker);
            });
        }

        // Jalankan saat DOM dan library siap
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', inisialisasiPeta);
        } else {
            inisialisasiPeta();
        }

        // ========================================================
        // 5. FUNGSI FILTER PIN PETA BERDASARKAN JENIS LEMBAGA
        // ========================================================
        window.filterMapMarkers = function(jenis, btnElement) {
            if (!window.markerLayerGroup || !window.allMapMarkers) return;

            // 1. Bersihkan pin saat ini di peta
            window.markerLayerGroup.clearLayers();

            // 2. Tambahkan kembali pin yang cocok dengan filter
            window.allMapMarkers.forEach(m => {
                if (jenis === 'ALL' || m.jenisLembaga === jenis) {
                    window.markerLayerGroup.addLayer(m);
                }
            });

            // 3. Efek Aktif / Highlight pada Tombol Filter yang Diklik
            document.querySelectorAll('.btn-filter-map').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-offset-2', 'ring-slate-900', 'scale-105');
                btn.classList.add('opacity-60');
            });

            if (btnElement) {
                btnElement.classList.remove('opacity-60');
                btnElement.classList.add('ring-2', 'ring-offset-2', 'ring-slate-900', 'scale-105');
            }
        };
    </script>
</x-app-layout>