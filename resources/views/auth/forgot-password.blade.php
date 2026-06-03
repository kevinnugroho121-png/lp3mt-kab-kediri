<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - LP3MT Kabupaten Kediri</title>
    
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
            background-color: #0f172a; /* Slate-900 */
            opacity: 0.85; 
            z-index: -1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    {{-- Selimut Gelap untuk foto SLG --}}
    <div class="overlay-navy"></div>

    {{-- KARTU FORM (Kotak Putih di Tengah Layar) --}}
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-slate-100 relative z-10 animate-fade-in-up">
        
        {{-- HEADER KARTU --}}
        <div class="flex flex-col items-center mb-6 text-center">
            {{-- Logo lebih kecil sedikit dari login agar proporsional --}}
            <img src="{{ asset('images/logo_lp3mt.png') }}" alt="Logo LP3MT" class="h-16 w-auto object-contain mb-4 drop-shadow-sm" onerror="this.onerror=null; this.src='https://via.placeholder.com/100?text=LP3MT';">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Lupa Password?</h1>
            
        </div>

        {{-- TEKS PETUNJUK (Disesuaikan ke Bahasa Indonesia formal) --}}
        <div class="mb-6 text-sm text-slate-500 font-medium leading-relaxed text-center px-2">
            {{ __('Masukkan alamat email Anda yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang sandi Anda.') }}
        </div>

        {{-- STATUS SESSION (Pesan Sukses Jika Email Terkirim) --}}
        <x-auth-session-status class="mb-6 p-3 rounded-lg bg-green-50 text-green-700 text-sm font-bold text-center border border-green-200" :status="session('status')" />

        {{-- FORM INPUT --}}
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            {{-- INPUT EMAIL (Gaya Floating Label sama seperti Login) --}}
            <div class="relative">
                <input id="email" class="peer block w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 bg-slate-50 placeholder-transparent focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-0 transition-colors font-semibold text-slate-800" 
                       type="email" name="email" :value="old('email')" required autofocus 
                       placeholder="Email" />
                <label for="email" class="absolute left-4 -top-2.5 bg-white px-1.5 text-xs font-bold text-slate-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-slate-400 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-600 rounded">
                    Alamat Email
                </label>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            {{-- TOMBOL SUBMIT --}}
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-lg shadow-red-600/30 transition duration-200 transform hover:-translate-y-0.5 mt-6 tracking-wide uppercase text-xs sm:text-sm">
                {{ __('Kirim Link Reset Password') }}
            </button>

            {{-- TOMBOL KEMBALI KE LOGIN --}}
            <div class="pt-4 text-center border-t border-slate-100 mt-6">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center text-sm font-bold text-slate-400 hover:text-blue-600 transition duration-150 ease-in-out group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Halaman Login
                </a>
            </div>
            
        </form>
    </div>

    {{-- Animasi CSS --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    </style>
</body>
</html>