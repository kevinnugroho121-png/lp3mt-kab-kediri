<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LP3MT Kabupaten Kediri</title>
    
    {{-- FONT INTER --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- TAILWIND & CHART.JS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    {{-- NAVBAR (Header Hijau Minimalis) --}}
    <nav class="bg-white/80 backdrop-blur-md border-b border-emerald-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        {{-- Logo Gambar --}}
                        <img src="{{ asset('images/logo_lp3mt.png') }}" alt="Logo App" class="w-10 h-10 object-contain">
                        <span class="font-bold text-xl tracking-tight text-slate-800">LP3MT Kabupaten Kediri <span class="text-emerald-600 font-normal"></span></span>
                    </div>
                </div>
                <div class="flex items-center">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-medium rounded-full text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition shadow-lg shadow-emerald-200 hover:shadow-emerald-300 transform hover:-translate-y-0.5">
                                LOGIN
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow flex flex-col justify-center items-center px-4 py-12 relative overflow-hidden">
        
        {{-- Background Decoration (Lebih Halus) --}}
        <div class="absolute top-0 left-1/2 w-96 h-96 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-1/2 w-96 h-96 bg-lime-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="relative z-10 w-full max-w-6xl mx-auto text-center">
            
            {{-- 1. BAGIAN LOGO INSTANSI --}}
            <div class="flex justify-center items-end gap-6 md:gap-12 mb-14">
                
                {{-- LOGO PEMKAB --}}
                <div class="group relative transition transform hover:scale-110 duration-300">
                    <img src="{{ asset('images/logo_kabupaten.png') }}" 
                         alt="Pemkab Kediri" 
                         class="h-24 md:h-28 w-auto object-contain drop-shadow-xl filter opacity-90 group-hover:opacity-100 transition duration-300"
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/100?text=LOGO';">
                </div>

                {{-- LOGO LP3MT (TENGAH - UKURAN DISAMAKAN & POSISI DIRATAKAN) --}}
                <div class="group relative transform transition hover:scale-110 duration-300">
                    <img src="{{ asset('images/logo_lp3mt.png') }}" 
                         alt="Logo LP3MT" 
                         class="h-24 md:h-28 w-auto object-contain drop-shadow-2xl z-10 relative"
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/100?text=LP3MT';">
                </div>

                {{-- LOGO MASBUP --}}
                <div class="group relative transition transform hover:scale-110 duration-300">
                    <img src="{{ asset('images/logo_masbup.png') }}" 
                         alt="Logo Masbup" 
                         class="h-24 md:h-28 w-auto object-contain drop-shadow-xl filter opacity-90 group-hover:opacity-100 transition duration-300"
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/100?text=MASBUP';">
                </div>

            </div>

            {{-- 2. JUDUL UTAMA --}}
            <div class="mb-20">
                <h1 class="text-4xl md:text-6xl font-extrabold text-slate-800 tracking-tight mb-4 drop-shadow-sm">
                    LP3MT Kabupaten Kediri
                </h1>
                <p class="text-xl text-slate-500 font-medium max-w-2xl mx-auto">
                    Sistem Informasi <span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-1 rounded">TPQ & Madrasah Diniyah</span>
                </p>
            </div>

            {{-- 3. GRID CHARTS (SEKARANG HANYA 2 KOLOM) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-24 text-center max-w-4xl mx-auto">
                
                {{-- CHART 1: LEMBAGA --}}
                <div class="flex flex-col items-center">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-slate-700">Komposisi Lembaga</h3>
                        <span class="block text-sm text-slate-400 mt-1">Total se-Kabupaten: <b>{{ $lembagaTPQ + $lembagaMadin + $lembagaPonpes }}</b></span>
                        <div class="text-xs text-slate-500 mt-2 space-x-2">
                            <span class="inline-block px-2 py-1 bg-emerald-50 text-emerald-700 rounded-md">TPQ: {{ $lembagaTPQ }}</span>
                            <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 rounded-md">Madin: {{ $lembagaMadin }}</span>
                            <span class="inline-block px-2 py-1 bg-purple-50 text-purple-700 rounded-md">Ponpes: {{ $lembagaPonpes }}</span>
                        </div>
                    </div>
                    <div class="relative w-full h-56 transform transition hover:scale-105 duration-300">
                        <canvas id="chartLembaga"></canvas>
                    </div>
                </div>

                {{-- CHART 2: GURU --}}
                <div class="flex flex-col items-center relative">
                    <div class="absolute inset-0 bg-emerald-50/50 rounded-full filter blur-3xl -z-10"></div>
                    
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-slate-800">Total Tenaga Pendidik</h3>
                        <span class="block text-sm text-emerald-600 font-medium mt-1">Data Guru Terdaftar: <b>{{ $guruTPQ + $guruMadin + $guruPonpes }}</b></span>
                        <div class="text-xs text-slate-500 mt-2 space-x-2">
                            <span class="inline-block px-2 py-1 bg-emerald-50 text-emerald-700 rounded-md">TPQ: {{ $guruTPQ }}</span>
                            <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 rounded-md">Madin: {{ $guruMadin }}</span>
                            <span class="inline-block px-2 py-1 bg-purple-50 text-purple-700 rounded-md">Ponpes: {{ $guruPonpes }}</span>
                        </div>
                    </div>
                    <div class="relative w-full h-56 transform transition hover:scale-105 duration-300">
                        <canvas id="chartGuru"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- FOOTER SIMPLE --}}
    <footer class="py-8 text-center text-slate-400 text-sm">
        &copy; {{ date('Y') }} LP3MT Kabupaten Kediri.
    </footer>

    {{-- SCRIPT DATA REAL DARI DATABASE --}}
    <script>
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b'; 
        
        // 1. CHART LEMBAGA
        const ctxLembaga = document.getElementById('chartLembaga');
        new Chart(ctxLembaga, {
            type: 'doughnut',
            data: {
                labels: ['TPQ', 'Madin', 'Ponpes'],
                datasets: [{
                    // Injeksi data real dari Laravel Controller
                    data: [{{ $lembagaTPQ }}, {{ $lembagaMadin }}, {{ $lembagaPonpes }}], 
                    backgroundColor: ['#059669', '#3b82f6', '#8b5cf6'], 
                    hoverBackgroundColor: ['#047857', '#2563eb', '#7c3aed'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true, padding: 20 } }
                }
            }
        });

        // 2. CHART GURU
        const ctxGuru = document.getElementById('chartGuru');
        new Chart(ctxGuru, {
            type: 'pie',
            data: {
                labels: ['Guru TPQ', 'Guru Madin', 'Guru Ponpes'],
                datasets: [{
                    // Injeksi data real dari Laravel Controller
                    data: [{{ $guruTPQ }}, {{ $guruMadin }}, {{ $guruPonpes }}],
                    backgroundColor: ['#10b981', '#60a5fa', '#a78bfa'], 
                    borderWidth: 4, 
                    borderColor: '#f8fafc' 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true, padding: 20 } }
                }
            }
        });
    </script>
</body>
</html>