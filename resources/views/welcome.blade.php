<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal Resmi Pendataan dan Penyaluran Insentif Guru Pesantren, Madin, dan TPQ LP3MT Kabupaten Kediri">
    <title>LP3MT Kabupaten Kediri</title>
    
    {{-- FONT BARU: PLUS JAKARTA SANS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- VITE CSS & JS (Menggantikan Tailwind CDN) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- KUSTOM ANIMASI --}}


    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        @keyframes fadeInUp {
            from { opacity: 0.3; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in-up { animation: fadeInUp 0.35s ease-out forwards; }
        .delay-100 { animation-delay: 0.05s; }
        .delay-200 { animation-delay: 0.1s; }
        .delay-300 { animation-delay: 0.15s; }
        .delay-400 { animation-delay: 0.2s; }
        .delay-500 { animation-delay: 0.25s; }
    </style>

</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    {{-- NAVBAR --}}
    <nav class="bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <img src="{{ asset('images/logo_lp3mt.png') }}" alt="Logo LP3MT Kabupaten Kediri" width="40" height="40" class="w-10 h-10 object-contain">
                        <span class="font-bold text-xl tracking-tight text-slate-900">LP3MT Kabupaten Kediri</span>
                    </div>
                </div>
                <div class="flex items-center">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-bold rounded-full text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition shadow-md shadow-red-200 hover:shadow-red-300 transform hover:-translate-y-0.5 tracking-wide">
                                LOGIN
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow flex flex-col justify-start items-center px-4 py-16 relative overflow-hidden">
        
        <div class="relative z-10 w-full max-w-5xl mx-auto text-center">
            
            {{-- 1. JUDUL UTAMA --}}
            <div class="mb-12">
                <span class="inline-block py-1.5 px-4 rounded-full bg-slate-200 text-slate-800 text-xs font-extrabold tracking-widest mb-6 border border-slate-300 uppercase shadow-sm">
                    Portal Resmi Pendataan Insentif
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight mb-6 leading-tight">
                    Lembaga Pendampingan Program <br class="hidden md:block"> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-red-600">Pesantren, Madin dan TPQ TPA</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-600 font-medium max-w-3xl mx-auto leading-relaxed">
                    Terintegrasi langsung dengan program LP3MT dan Pemerintah Kabupaten Kediri untuk penyaluran insentif yang transparan, akurat, dan tepat sasaran.
                </p>
            </div>

            {{-- 2. BAGIAN LOGO INSTANSI --}}
            <div class="mb-20 flex flex-col items-center animate-fade-in-up delay-100">
                <p class="text-xs font-bold text-slate-600 uppercase tracking-widest mb-4">Didukung Oleh:</p>
                <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 bg-white px-8 md:px-12 py-6 rounded-3xl shadow-sm border border-slate-200">
                    <div class="group relative transition transform hover:-translate-y-1 duration-300">
                        <img src="{{ asset('images/logo_kabupaten.png') }}" alt="Logo Pemerintah Kabupaten Kediri" width="80" height="80" class="h-16 md:h-20 w-auto object-contain transition duration-300">
                    </div>
                    <div class="group relative transform transition hover:-translate-y-1 duration-300">
                        <img src="{{ asset('images/logo_lp3mt.png') }}" alt="Logo LP3MT Kabupaten Kediri" width="80" height="80" class="h-16 md:h-20 w-auto object-contain relative">
                    </div>
                    <div class="group relative transition transform hover:-translate-y-1 duration-300">
                        <img src="{{ asset('images/logo_masbup.png') }}" alt="Logo Mas Dhito Kabupaten Kediri" width="80" height="80" class="h-16 md:h-20 w-auto object-contain transition duration-300">
                    </div>
                </div>
            </div>

            {{-- 3. SUMMARY CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-5xl mx-auto mb-10 animate-fade-in-up delay-200">
                
                {{-- Card 1: Lembaga --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col items-center justify-center relative overflow-hidden group hover:shadow-md transition">
                    <div class="absolute top-0 w-full h-1.5 bg-blue-600"></div>
                    <span class="text-slate-500 font-extrabold text-xs uppercase tracking-widest mb-2">Total Lembaga</span>
                    <span class="text-5xl font-black text-slate-900">{{ $lembagaTPQ + $lembagaMadin + $lembagaPonpes }}</span>
                </div>

                {{-- Card 2: Guru --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col items-center justify-center relative overflow-hidden group hover:shadow-md transition">
                    <div class="absolute top-0 w-full h-1.5 bg-red-600"></div>
                    <span class="text-slate-500 font-extrabold text-xs uppercase tracking-widest mb-2">Guru Terdaftar</span>
                    <span class="text-5xl font-black text-slate-900">{{ $guruTPQ + $guruMadin + $guruPonpes }}</span>
                </div>

                {{-- Card 3: Status --}}
                <div class="bg-slate-900 p-6 rounded-2xl shadow-md border border-slate-800 flex flex-col items-center justify-center relative overflow-hidden">
                    <span class="text-slate-400 font-extrabold text-xs uppercase tracking-widest mb-2">Status Portal</span>
                    <span class="text-2xl font-bold text-white flex items-center gap-3 mt-2">
                        <span class="relative flex h-4 w-4">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
                        </span>
                        Sistem Aktif
                    </span>
                </div>
            </div>

            {{-- 4. GRAFIK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-5xl mx-auto animate-fade-in-up delay-300">
                
                {{-- Chart Lembaga --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                    <h2 class="text-lg font-extrabold text-slate-800 mb-6">Sebaran Lembaga</h2>
                    <div class="relative w-full h-64 transform transition hover:scale-105 duration-300">
                        <canvas id="chartLembaga"></canvas>
                    </div>
                </div>

                {{-- Chart Guru --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                    <h2 class="text-lg font-extrabold text-slate-800 mb-6">Sebaran Tenaga Pendidik</h2>
                    <div class="relative w-full h-64 transform transition hover:scale-105 duration-300">
                        <canvas id="chartGuru"></canvas>
                    </div>
                </div>

            </div>

            {{-- ========================================== --}}
            {{-- 5. [BARU] GALERI DOKUMENTASI KEGIATAN      --}}
            {{-- ========================================== --}}
            <div class="mt-20 w-full max-w-5xl mx-auto animate-fade-in-up delay-400 mb-10">
                
                {{-- Header Galeri --}}
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight mb-3">Dokumentasi Kegiatan</h2>
                    <div class="w-16 h-1 bg-gradient-to-r from-blue-600 to-red-600 mx-auto rounded-full mb-4"></div>
                    <p class="text-sm md:text-base text-slate-600">Potret pelaksanaan dan pendampingan program LP3MT di wilayah Kabupaten Kediri.</p>
                </div>
                
                {{-- Grid Galeri Dinamis (Menyesuaikan Jumlah Foto) --}}
                @if(isset($dokumentasis) && $dokumentasis->count() > 0)
                    {{-- [GRID AUTOMAGIC] Kotak akan menyesuaikan ukuran dengan pintar --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-{{ $dokumentasis->count() < 4 ? $dokumentasis->count() : '4' }} gap-4 auto-rows-max">
                        
                        @foreach($dokumentasis as $dok)
                            <div class="group relative overflow-hidden rounded-2xl shadow-sm border border-slate-200 cursor-pointer bg-slate-200 aspect-[4/3] w-full">
                                {{-- Mengambil gambar dari folder Storage --}}
                                <img src="{{ asset('storage/' . $dok->foto_path) }}" alt="{{ $dok->judul }}" loading="lazy" width="400" height="300" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition duration-500 ease-in-out">
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-4">
                                    {{-- Mengambil Judul Foto dari Database --}}
                                    <span class="text-white text-xs font-bold tracking-wide">{{ $dok->judul }}</span>
                                </div>
                            </div>
                        @endforeach

                    </div>
                @else
                    {{-- Tampilan jika Admin belum upload foto sama sekali --}}
                    <div class="flex flex-col items-center justify-center p-12 bg-slate-100 border border-slate-200 rounded-3xl border-dashed">
                        <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-slate-600 font-medium text-sm">Dokumentasi kegiatan belum diunggah.</p>
                    </div>
                @endif
            </div>
            {{-- End Galeri --}}

        </div>
    </main>

    {{-- FOOTER RESMI (Gov-Tech Style) --}}
    <footer class="bg-slate-900 pt-16 pb-8 border-t-4 border-blue-600 mt-16 animate-fade-in-up delay-500">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                
                {{-- Info Kiri --}}
                <div class="text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-3 mb-4">
                        <img src="{{ asset('images/logo_lp3mt.png') }}" alt="Logo LP3MT Kabupaten Kediri" width="32" height="32" class="h-8 w-auto opacity-90">
                        <p class="text-white font-bold text-xl tracking-tight">LP3MT Kab. Kediri</p>
                    </div>
                    <p class="text-slate-300 text-sm max-w-sm leading-relaxed">
                        Sistem Informasi terpadu untuk pendataan dan penyaluran insentif tenaga pendidik TPQ dan Madrasah Diniyah di wilayah Kabupaten Kediri.
                    </p>
                </div>
                
                {{-- Copyright Kanan --}}
                <div class="text-center md:text-right mt-4 md:mt-0">
                    <p class="text-slate-300 text-sm font-medium">
                        &copy; {{ date('Y') }} Pemerintah Kabupaten Kediri. <br class="hidden md:block">
                        Hak Cipta Dilindungi Undang-Undang.
                    </p>
                </div>
                
            </div>
        </div>
    </footer>

    {{-- SCRIPT CHART.JS DIMUAT DI AKHIR BODY DENGAN EVENT LOAD --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script>
        window.addEventListener('load', function () {
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#64748b'; 
            
            // 1. CHART LEMBAGA
            const ctxLembaga = document.getElementById('chartLembaga');
            if (ctxLembaga) {
                new Chart(ctxLembaga, {
                    type: 'doughnut',
                    data: {
                        labels: ['TPQ ({{ $lembagaTPQ }})', 'Madin ({{ $lembagaMadin }})', 'Ponpes ({{ $lembagaPonpes }})'],
                        datasets: [{
                            data: [{{ $lembagaTPQ }}, {{ $lembagaMadin }}, {{ $lembagaPonpes }}], 
                            backgroundColor: ['#2563eb', '#f97316', '#1e293b'], 
                            hoverBackgroundColor: ['#1d4ed8', '#ea580c', '#0f172a'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true, padding: 20, font: { weight: 'bold' } } }
                        }
                    }
                });
            }

            // 2. CHART GURU
            const ctxGuru = document.getElementById('chartGuru');
            if (ctxGuru) {
                new Chart(ctxGuru, {
                    type: 'pie',
                    data: {
                        labels: ['Guru TPQ ({{ $guruTPQ }})', 'Guru Madin ({{ $guruMadin }})', 'Guru Ponpes ({{ $guruPonpes }})'],
                        datasets: [{
                            data: [{{ $guruTPQ }}, {{ $guruMadin }}, {{ $guruPonpes }}],
                            backgroundColor: ['#dc2626', '#38bdf8', '#64748b'], 
                            borderWidth: 3, 
                            borderColor: '#ffffff',
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true, padding: 20, font: { weight: 'bold' } } }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>