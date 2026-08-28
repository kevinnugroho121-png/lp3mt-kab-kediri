<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Sandi - LP3MT Kabupaten Kediri</title>
    
    {{-- Favicon Tab Browser --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo_lp3mt.png') }}">
    
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

    {{-- KARTU FORM (Kotak Putih di Tengah Layar) --}}
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-slate-100 relative z-10 animate-fade-in-up">
        
        {{-- HEADER KARTU --}}
        <div class="flex flex-col items-center mb-8 text-center">
            <img src="{{ asset('images/logo_lp3mt.png') }}" alt="Logo LP3MT" class="h-20 w-auto object-contain mb-4 drop-shadow-sm" onerror="this.onerror=null; this.src='https://via.placeholder.com/100?text=LP3MT';">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">LP3MT</h1>
            <p class="text-[10px] font-extrabold text-red-600 uppercase tracking-[0.2em] mt-1">Kabupaten Kediri</p>
            <p class="text-sm text-slate-500 mt-2 font-medium">Atur Ulang Sandi Anda</p>
        </div>

        {{-- FORM INPUT --}}
        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <!-- Password Reset Token yang disembunyikan (Wajib ada) -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- INPUT EMAIL --}}
            <div class="relative">
                <input id="email" class="peer block w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 bg-slate-50 placeholder-transparent focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-0 transition-colors font-semibold text-slate-800" 
                       type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                       placeholder="Email" />
                <label for="email" class="absolute left-4 -top-2.5 bg-white px-1.5 text-xs font-bold text-slate-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-slate-400 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-600 rounded">
                    Email yang terdaftar
                </label>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            {{-- INPUT PASSWORD BARU --}}
            <div class="relative">
                <input id="password" class="peer block w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 bg-slate-50 placeholder-transparent focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-0 transition-colors font-semibold text-slate-800" 
                       type="password" name="password" required autocomplete="new-password"
                       placeholder="Password Baru" />
                <label for="password" class="absolute left-4 -top-2.5 bg-white px-1.5 text-xs font-bold text-slate-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-slate-400 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-600 rounded">
                    Buat Password Baru
                </label>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            {{-- INPUT KONFIRMASI PASSWORD --}}
            <div class="relative">
                <input id="password_confirmation" class="peer block w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 bg-slate-50 placeholder-transparent focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-0 transition-colors font-semibold text-slate-800" 
                       type="password" name="password_confirmation" required autocomplete="new-password"
                       placeholder="Konfirmasi Password" />
                <label for="password_confirmation" class="absolute left-4 -top-2.5 bg-white px-1.5 text-xs font-bold text-slate-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-slate-400 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-600 rounded">
                    Ketik Ulang Password
                </label>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            {{-- TOMBOL SUBMIT --}}
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-lg shadow-red-600/30 transition duration-200 transform hover:-translate-y-0.5 mt-6 tracking-wide uppercase text-sm">
                Simpan Password Baru
            </button>

        </form>
    </div>

    {{-- Animasi CSS --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    </style>
</body>
</html>