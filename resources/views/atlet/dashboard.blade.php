<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- WRAPPER: Padding standar --}}
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">
        
        {{-- LAYOUT UTAMA: Grid 12 Kolom --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- === BAGIAN KIRI (8 Kolom) === --}}
            <div class="lg:col-span-8 flex flex-col gap-6">
                
                {{-- 1. HEADER CARD (Profil Singkat) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Halo, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-sm text-gray-500 mt-1">Selamat datang di dashboard atlet.</p>
                        <div class="mt-3 flex gap-2">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-100">
                                {{ $atlet->kategori_hitung ?? '-' }}
                            </span>
                            <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs font-bold rounded-full border border-purple-100">
                                {{ $atlet->posisi ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="hidden sm:block text-4xl opacity-80">🏀</div>
                </div>

                {{-- 2. STATISTIK ABSENSI (Grid 4 Kotak) --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $hadir }}</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Hadir</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $izin }}</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Izin</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $sakit }}</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Sakit</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $alpha }}</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Alpha</div>
                    </div>
                </div>

                {{-- 3. GRAFIK PERKEMBANGAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-gray-800">📈 Grafik Rapor</h4>
                        <div class="flex gap-3 text-xs">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Teknik</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> Fisik</span>
                        </div>
                    </div>
                    {{-- Container Grafik dengan Tinggi Tetap agar tidak melar --}}
                    <div class="relative h-64 w-full">
                        @if(count($chart_labels) > 0)
                            <canvas id="raporChart"></canvas>
                        @else
                            <div class="h-full flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-100 rounded-lg">
                                <span>Belum ada data nilai.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- === BAGIAN KANAN (4 Kolom) === --}}
            <div class="lg:col-span-4 flex flex-col gap-6">
                
                {{-- 1. STATUS TAGIHAN SPP --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Status Keuangan</h4>
                    
                    @if($tagihan_pending > 0)
                        <div class="w-16 h-16 mx-auto bg-red-100 text-red-600 rounded-full flex items-center justify-center text-2xl mb-3">
                            ⚠️
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-1">Tunggakan</h2>
                        <p class="text-sm text-red-600 font-medium mb-4">
                            {{ $tagihan_pending }} bulan belum lunas
                        </p>
                        <button class="w-full bg-red-600 text-white text-sm font-bold px-4 py-2.5 rounded-lg hover:bg-red-700 transition">
                            Bayar Sekarang
                        </button>
                    @else
                        <div class="w-16 h-16 mx-auto bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mb-3">
                            ✅
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-1">Lunas</h2>
                        <p class="text-sm text-green-600 font-medium mb-4">
                            Administrasi aman!
                        </p>
                        <button class="w-full bg-gray-100 text-gray-400 text-sm font-bold px-4 py-2.5 rounded-lg cursor-not-allowed">
                            Tidak Ada Tagihan
                        </button>
                    @endif
                </div>

                {{-- 2. PENGUMUMAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col h-full">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-xl">
                        <h5 class="font-bold text-sm text-gray-700">📢 Pengumuman</h5>
                        <a href="{{ route('notifikasi.index.user') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
                    </div>
                    <div class="p-4 space-y-4 flex-1">
                        @forelse($notifikasis as $info)
                            <div class="pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                                <p class="text-sm font-bold text-gray-800 line-clamp-1">{{ $info->judul }}</p>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $info->isi }}</p>
                                <p class="text-[10px] text-gray-400 mt-1 text-right">{{ $info->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-400 text-sm">
                                Tidak ada info baru.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT CHART JS --}}
    @if(count($chart_labels) > 0)
        <script>
            const ctx = document.getElementById('raporChart');
            if(ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chart_labels),
                        datasets: [
                            {
                                label: 'Teknik',
                                data: @json($data_teknik),
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                tension: 0.3,
                                pointRadius: 3
                            },
                            {
                                label: 'Fisik',
                                data: @json($data_fisik),
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 2,
                                tension: 0.3,
                                pointRadius: 3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 10 } }
                        },
                        scales: {
                            y: { beginAtZero: true, max: 100, grid: { color: '#f3f4f6' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        </script>
    @endif
</x-app-layout>