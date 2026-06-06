<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LP3MT Kabupaten Kediri</title>
    
    {{-- FONT: PLUS JAKARTA SANS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- TAILWIND CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            /* BACKGROUND FOTO SLG */
            background-image: url('{{ asset('images/bg-slg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        /* LAPISAN OVERLAY NAVY GELAP */
        .overlay-navy {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #0f172a; /* Slate-900 (Navy Gelap) */
            opacity: 0.85; /* Tingkat kegelapan 85% */
            z-index: -1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    {{-- Selimut Gelap untuk menenggelamkan foto SLG --}}
    <div class="overlay-navy"></div>

    {{-- KARTU FORM LOGIN (Kotak Putih di Tengah Layar) --}}
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-slate-100 relative z-10 animate-fade-in-up">
        
        {{-- HEADER KARTU --}}
        <div class="flex flex-col items-center mb-8 text-center">
            <img src="{{ asset('images/logo_lp3mt.png') }}" alt="Logo LP3MT" class="h-20 w-auto object-contain mb-4 drop-shadow-sm" onerror="this.onerror=null; this.src='https://via.placeholder.com/100?text=LP3MT';">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">LP3MT</h1>
            <p class="text-[10px] font-extrabold text-red-600 uppercase tracking-[0.2em] mt-1">Kabupaten Kediri</p>
            <p class="text-sm text-slate-500 mt-2 font-medium">Portal Pendataan Tenaga Pendidik</p>
        </div>

        {{-- STATUS SESSION (Jika ada error/pesan) --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        {{-- FORM INPUT --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- INPUT EMAIL --}}
            <div class="relative">
                <input id="email" class="peer block w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 bg-slate-50 placeholder-transparent focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-0 transition-colors font-semibold text-slate-800" 
                       type="email" name="email" :value="old('email')" required autofocus 
                       placeholder="Email" />
                <label for="email" class="absolute left-4 -top-2.5 bg-white px-1.5 text-xs font-bold text-slate-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-slate-400 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-600 rounded">
                    Email / Username
                </label>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            {{-- INPUT PASSWORD DENGAN IKON MATA --}}
            <div class="relative">
                <input id="password" class="peer block w-full px-4 py-3.5 pr-12 rounded-xl border-2 border-slate-200 bg-slate-50 placeholder-transparent focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-0 transition-colors font-semibold text-slate-800" 
                       type="password" name="password" required autocomplete="current-password"
                       placeholder="Password" />
                <label for="password" class="absolute left-4 -top-2.5 bg-white px-1.5 text-xs font-bold text-slate-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-slate-400 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-600 rounded">
                    Password
                </label>
                
                {{-- Tombol Mata --}}
                <button type="button" onclick="toggleLoginPassword()" class="absolute inset-y-0 right-0 px-4 flex items-center text-slate-400 hover:text-blue-600 transition focus:outline-none">
                    <svg id="eye_icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>

                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500 font-bold" />
            </div>

            {{-- INGAT SAYA & LUPA PASSWORD --}}
            <div class="flex items-center justify-between mt-2 px-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer transition">
                    <span class="ms-2 text-sm font-medium text-slate-500 group-hover:text-slate-800 transition">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:text-blue-800 font-bold transition" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>

            {{-- TOMBOL SUBMIT --}}
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-lg shadow-red-600/30 transition duration-200 transform hover:-translate-y-0.5 mt-6 tracking-wide uppercase text-sm">
                Masuk Aplikasi
            </button>

            {{-- TOMBOL KEMBALI --}}
            <div class="pt-4 text-center border-t border-slate-100 mt-6">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center text-sm font-bold text-slate-400 hover:text-blue-600 transition duration-150 ease-in-out group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
            
        </form>
    </div>

    {{-- Animasi CSS --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    </style>

    {{-- Animasi CSS --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    </style>

    {{-- SCRIPT IKON MATA --}}
    <script>
        function toggleLoginPassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye_icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Ikon Mata Dicoret
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0l3.29 3.29m0 0l-3.29-3.29m0 0L3 3m18 18l-3.29-3.29m0 0l-3.29-3.29m0 0l3.29 3.29m0 0L21 21"></path>';
            } else {
                passwordInput.type = 'password';
                // Ikon Mata Terbuka
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
</body>
</html>



</body>
</html>