<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Eksekutif') }}
        </h2>
    </x-slot>

    {{-- LOAD LIBRARY CHART.JS (Via CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- 1. WELCOME BANNER --}}
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-8 text-white shadow-xl flex justify-between items-center relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
                    <p class="text-gray-300">Berikut adalah ringkasan performa finansial akademi Anda.</p>
                </div>
                {{-- Hiasan Background --}}
                <div class="absolute right-0 top-0 h-full w-1/3 bg-white opacity-5 transform skew-x-12 translate-x-10"></div>
            </div>

            {{-- 2. CARDS STATISTIK (Ringkasan) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Card 1: Atlet --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl">
                        🏃‍♂️
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase">Total Atlet Aktif</p>
                        <h4 class="text-3xl font-bold text-gray-800">{{ $atlet_aktif }}</h4>
                    </div>
                </div>

                {{-- Card 2: Pemasukan --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-14 h-14 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-2xl">
                        💰
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase">Total Pemasukan</p>
                        <h4 class="text-3xl font-bold text-gray-800">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</h4>
                    </div>
                </div>

                {{-- Card 3: Tunggakan --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-14 h-14 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-2xl">
                        ⚠️
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase">Piutang (Belum Lunas)</p>
                        <h4 class="text-3xl font-bold text-gray-800">Rp {{ number_format($tagihan_belum_lunas, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>

            {{-- 3. AREA GRAFIK (CHARTS) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Grafik Kiri (Line Chart Pemasukan) --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        📈 Tren Pemasukan Tahun {{ date('Y') }}
                    </h4>
                    <div class="h-64">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>

                {{-- Grafik Kanan (Donut Chart Status SPP) --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        📊 Status SPP Bulan Ini
                    </h4>
                    <div class="h-48 flex justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center text-sm text-gray-500">
                        Perbandingan Lunas vs Belum Lunas
                    </div>
                </div>
            </div>

            {{-- 4. RIWAYAT TRANSAKSI TERAKHIR --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-white">
                    <h4 class="font-bold text-gray-800 text-lg">📜 Transaksi Pembayaran Terbaru</h4>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 uppercase text-xs font-bold text-gray-500">
                            <tr>
                                <th class="px-6 py-4">Nama Atlet</th>
                                <th class="px-6 py-4">Keterangan</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($riwayat_terbaru as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-gray-800">
                                        {{-- Cek jika relasi user ada --}}
                                        {{ $item->atlet->user->name ?? 'Atlet (Terhapus)' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{-- Tampilkan Bulan Tagihan --}}
                                        SPP Bulan {{ $item->tagihan->bulan ?? '-' }} {{ $item->tagihan->tahun ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        {{ $item->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-green-600">
                                        + Rp {{ number_format($item->jumlah_dibayar, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                        Belum ada data transaksi masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPT RENDERING CHART --}}
    <script>
        // 1. CHART PEMASUKAN (LINE CHART)
        const ctxIncome = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctxIncome, {
            type: 'line',
            data: {
                labels: @json($bulan_label), // Label Bulan (Jan, Feb...) dari Controller
                datasets: [{
                    label: 'Pemasukan (Rp)',
                    data: @json($income_data), // Data Uang dari Controller
                    borderColor: '#10B981', // Warna Hijau Emerald
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    tension: 0.4, // Garis melengkung halus
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10B981',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [5, 5] },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. CHART STATUS SPP (DOUGHNUT CHART)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Lunas', 'Belum Lunas'],
                datasets: [{
                    data: [{{ $spp_lunas }}, {{ $spp_belum }}],
                    backgroundColor: ['#10B981', '#EF4444'], // Hijau & Merah
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        });
    </script>
</x-app-layout>