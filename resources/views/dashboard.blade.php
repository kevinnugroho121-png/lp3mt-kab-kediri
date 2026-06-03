<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Eksekutif') }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">Wilayah Data: <b>{{ $wilayahKerja }}</b></p>
    </x-slot>

    <div class="py-8">
        {{-- ==================================================== --}}
        {{-- BAGIAN ATAS: 3 KARTU RINGKASAN --}}
        {{-- ==================================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            {{-- KARTU 1: DATA LEMBAGA --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] hover:shadow-lg transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Komposisi Lembaga</h3>
                            <p class="text-xs text-gray-400">Sebaran Jenis Lembaga</p>
                        </div>
                        <div class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center text-lg">🕌</div>
                    </div>
                    <div class="relative h-48 w-full mb-6">
                        <canvas id="chartLembaga"></canvas>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="grid grid-cols-3 gap-2 mb-4 text-center border-b border-gray-200 pb-4">
                        <div>
                            <span class="block text-[10px] text-gray-400 font-bold uppercase">TPQ</span>
                            <span class="text-lg font-bold text-green-600">{{ number_format($lembagaTPQ) }}</span>
                        </div>
                        <div class="border-x border-gray-200">
                            <span class="block text-[10px] text-gray-400 font-bold uppercase">Madin</span>
                            <span class="text-lg font-bold text-blue-600">{{ number_format($lembagaMadin) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-gray-400 font-bold uppercase">Ponpes</span>
                            <span class="text-lg font-bold text-purple-600">{{ number_format($lembagaPonpes) }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-orange-100 text-orange-600 rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 font-bold">TOT. GURU</p>
                                <p class="text-sm font-extrabold text-gray-800">{{ number_format($totalGuru) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-teal-100 text-teal-600 rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 font-bold">TOT. SANTRI</p>
                                <p class="text-sm font-extrabold text-gray-800">{{ number_format($totalSantri) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU 2: STATUS GURU --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] hover:shadow-lg transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Status Kepegawaian</h3>
                            <p class="text-xs text-gray-400">Klasifikasi Status Guru</p>
                        </div>
                        <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-lg">👨‍🏫</div>
                    </div>
                    <div class="relative h-48 w-full mb-6">
                        <canvas id="chartGuru"></canvas>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center justify-between bg-white p-2 rounded border border-gray-100">
                            <span class="text-[10px] text-gray-500 font-bold">PNS</span>
                            <span class="text-sm font-bold text-blue-600">{{ number_format($guruPNS) }}</span>
                        </div>
                        <div class="flex items-center justify-between bg-white p-2 rounded border border-gray-100">
                            <span class="text-[10px] text-gray-500 font-bold">P3K Full</span>
                            <span class="text-sm font-bold text-emerald-600">{{ number_format($guruP3KFull) }}</span>
                        </div>
                        <div class="flex items-center justify-between bg-white p-2 rounded border border-gray-100">
                            <span class="text-[10px] text-gray-500 font-bold">P3K Paruh</span>
                            <span class="text-sm font-bold text-amber-600">{{ number_format($guruP3KParuh) }}</span>
                        </div>
                        <div class="flex items-center justify-between bg-white p-2 rounded border border-gray-100">
                            <span class="text-[10px] text-gray-500 font-bold">Inpassing</span>
                            <span class="text-sm font-bold text-purple-600">{{ number_format($guruInpassing) }}</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-center text-gray-400 mt-3 italic">*Data Non-ASN lainnya: {{ number_format($guruNonASN) }}</p>
                </div>
            </div>

            {{-- KARTU 3: STATUS INSENTIF --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] hover:shadow-lg transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Penyaluran Insentif</h3>
                            <p class="text-xs text-gray-400">Progres Penerima Bantuan</p>
                        </div>
                        <div class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center text-lg">🤲</div>
                    </div>
                    <div class="relative h-48 w-full mb-6">
                        <canvas id="chartInsentif"></canvas>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-xs text-gray-500 font-medium">Target Tahap 1</span>
                        <span class="text-xs font-bold bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full">{{ number_format($targetInsentif) }} Orang</span>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-end">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Sudah Terima</p>
                                    <p class="text-sm font-bold text-gray-800">{{ number_format($sudahTerimaInsentif) }} <span class="text-[10px] font-normal text-gray-400">Guru</span></p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-emerald-600">{{ $persenSudah }}%</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Belum/Gagal</p>
                                    <p class="text-sm font-bold text-gray-800">{{ number_format($belumTerimaInsentif) }} <span class="text-[10px] font-normal text-gray-400">Guru</span></p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-red-500">{{ $persenBelum }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================================================== --}}
        {{-- BAGIAN BAWAH 1: GRAFIK SEBARAN KECAMATAN --}}
        {{-- ==================================================== --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)]">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Peta Sebaran Lembaga</h3>
                    <p class="text-sm text-gray-400">Statistik Total Lembaga per Kecamatan</p>
                </div>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded bg-green-500"></span>
                        <span class="text-xs text-gray-500">TPQ</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded bg-blue-500"></span>
                        <span class="text-xs text-gray-500">Madin</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-1 rounded bg-orange-400"></span>
                        <span class="text-xs text-gray-500">Total</span>
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
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] mt-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Distribusi Guru per Desa/Kelurahan</h3>
                    <p class="text-sm text-gray-400">Statistik Rinci Tenaga Pendidik</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                    
                    {{-- FILTER KECAMATAN (Hanya Admin yang bisa ubah) --}}
                    @if(Auth::user()->role != 'korcam')
                        <div class="w-full sm:w-48">
                            <select id="filterKecamatanGuru" class="w-full border-gray-300 rounded-lg shadow-sm text-sm py-2 font-semibold text-gray-700 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 cursor-pointer hover:bg-slate-100 transition">
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
                            <span class="text-xs text-gray-500">Guru TPQ</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded bg-blue-500"></span>
                            <span class="text-xs text-gray-500">Guru Madin</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-1 rounded bg-orange-400"></span>
                            <span class="text-xs text-gray-500">Total</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative w-full h-[400px]"> 
                <canvas id="chartGuruDesa"></canvas>
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
                labels: ['Sudah Terima', 'Belum Terima'],
                datasets: [{
                    data: [{{ $sudahTerimaInsentif }}, {{ $belumTerimaInsentif }}],
                    backgroundColor: ['#059669', '#f87171'], borderWidth: 0,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false } } }
        });

        // ==========================================
        // 4. CHART LEMBAGA PER KECAMATAN (ATAS)
        // ==========================================
        new Chart(document.getElementById('chartSebaranLembaga'), {
            type: 'bar', 
            data: {
                labels: {!! json_encode($kecamatanLabels) !!},
                datasets: [
                    {
                        type: 'line', label: 'Total Lembaga', data: {!! json_encode($dataTotalSebaran) !!},
                        borderColor: '#fb923c', backgroundColor: '#fb923c', borderWidth: 3,
                        pointBackgroundColor: '#fff', pointBorderColor: '#fb923c', pointRadius: 5, tension: 0.4, order: 0 
                    },
                    {
                        type: 'bar', label: 'TPQ', data: {!! json_encode($dataTpqSebaran) !!},
                        backgroundColor: '#10b981', borderRadius: 4, barPercentage: 0.6, order: 1
                    },
                    {
                        type: 'bar', label: 'Madin', data: {!! json_encode($dataMadinSebaran) !!},
                        backgroundColor: '#3b82f6', borderRadius: 4, barPercentage: 0.6, order: 1
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: 'rgba(255,255,255,0.95)', titleColor: '#1e293b', bodyColor: '#475569', borderColor: '#e2e8f0', borderWidth: 1, padding: 10, usePointStyle: true }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { font: { size: 11 }, stepSize: 1 } },
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                }
            }
        });

        // ==========================================
        // 5. CHART GURU PER DESA (BAWAH)
        // ==========================================
        const dataGuruDesa = {!! json_encode($sebaranGuruPerKecamatan) !!};
        const ctxGuruDesa = document.getElementById('chartGuruDesa');
        let chartGuruDesaObj; // Variable penampung grafik

        function updateGuruChart(kecamatanId) {
            const dataKec = dataGuruDesa[kecamatanId];
            
            // Handle jika data kecamatan tidak ditemukan/kosong
            if(!dataKec) {
                if(chartGuruDesaObj) {
                    chartGuruDesaObj.data.labels = [];
                    chartGuruDesaObj.data.datasets.forEach(ds => ds.data = []);
                    chartGuruDesaObj.update();
                }
                return;
            }

            const chartData = {
                labels: dataKec.labels, // Menampilkan nama-nama Desa
                datasets: [
                    {
                        type: 'line', label: 'Total Guru', data: dataKec.total,
                        borderColor: '#fb923c', backgroundColor: '#fb923c', borderWidth: 3,
                        pointBackgroundColor: '#fff', pointBorderColor: '#fb923c', pointRadius: 5, tension: 0.4, order: 0 
                    },
                    {
                        type: 'bar', label: 'Guru TPQ', data: dataKec.tpq,
                        backgroundColor: '#10b981', borderRadius: 4, barPercentage: 0.6, order: 1
                    },
                    {
                        type: 'bar', label: 'Guru Madin', data: dataKec.madin,
                        backgroundColor: '#3b82f6', borderRadius: 4, barPercentage: 0.6, order: 1
                    }
                ]
            };

            if(chartGuruDesaObj) {
                chartGuruDesaObj.data = chartData;
                chartGuruDesaObj.update();
            } else {
                chartGuruDesaObj = new Chart(ctxGuruDesa, {
                    type: 'bar',
                    data: chartData,
                    options: {
                        responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: 'rgba(255,255,255,0.95)', titleColor: '#1e293b', bodyColor: '#475569', borderColor: '#e2e8f0', borderWidth: 1, padding: 10, usePointStyle: true }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { font: { size: 11 }, stepSize: 1 } },
                            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
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
</x-app-layout>