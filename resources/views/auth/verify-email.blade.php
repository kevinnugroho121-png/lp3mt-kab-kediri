<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <<title>Verifikasi Email - LP3MT Kabupaten Kediri</title>
    
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
            background-color: #0f172a; 
            opacity: 0.85; 
            z-index: -1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="overlay-navy"></div>

    {{-- KARTU FORM --}}
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-slate-100 relative z-10 animate-fade-in-up">
        
        {{-- HEADER KARTU --}}
        <div class="flex flex-col items-center mb-6 text-center">
            <img src="{{ asset('images/logo_lp3mt.png') }}" alt="Logo LP3MT" class="h-20 w-auto object-contain mb-4 drop-shadow-sm" onerror="this.onerror=null; this.src='https://via.placeholder.com/100?text=LP3MT';">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">LP3MT</h1>
            <p class="text-[10px] font-extrabold text-red-600 uppercase tracking-[0.2em] mt-1">Kabupaten Kediri</p>
            <p class="text-sm text-slate-500 mt-2 font-medium">Verifikasi Akun Anda</p>
        </div>

        {{-- TEKS PENJELASAN --}}
        <div class="mb-6 text-sm text-slate-600 text-center leading-relaxed">
            Akun Anda telah berhasil didaftarkan. Sebelum dapat mengakses sistem, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda.
            <br><br>
            Jika Anda tidak menerima email tersebut, silakan klik tombol di bawah ini.
        </div>

        {{-- NOTIFIKASI JIKA EMAIL SUKSES DIKIRIM ULANG --}}
        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 font-semibold text-sm text-green-700 text-center bg-green-50 p-4 rounded-xl border border-green-200">
                Tautan verifikasi baru telah berhasil dikirim ke alamat email Anda.
            </div>
        @endif

        {{-- TOMBOL AKSI --}}
        <div class="flex flex-col space-y-3 mt-2">
            
            {{-- Tombol Resend --}}
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-lg shadow-red-600/30 transition duration-200 transform hover:-translate-y-0.5 tracking-wide text-sm">
                    Kirim Ulang Email
                </button>
            </form>

            {{-- Tombol Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3.5 px-4 rounded-xl transition duration-200 text-sm">
                    Keluar (Log Out)
                </button>
            </form>

        </div>
    </div>

    {{-- Animasi CSS --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    </style>
</body>
</html>