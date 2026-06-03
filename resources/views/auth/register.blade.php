<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Jethree Academy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen overflow-hidden bg-white font-sans text-gray-900">

    <div class="flex h-full w-full">
        
        {{-- BAGIAN KIRI: BRANDING (35% Lebar) --}}
        <div class="hidden lg:flex w-5/12 bg-green-800 text-white flex-col justify-center items-center p-12 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/basketball.png')]"></div>
            
            <div class="relative z-10 text-center">
                <div class="w-24 h-24 bg-white text-green-800 rounded-full flex items-center justify-center text-5xl shadow-2xl mb-6 mx-auto">
                    🏀
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight mb-2">Jethree Academy</h1>
                <p class="text-green-200 text-lg">Bergabunglah dengan tim juara.</p>
            </div>
            
            <div class="absolute bottom-6 text-xs text-green-400">
                &copy; {{ date('Y') }} Sistem Informasi Akademi Basket
            </div>
        </div>

        {{-- BAGIAN KANAN: FORMULIR (65% Lebar - Compact) --}}
        <div class="w-full lg:w-7/12 flex items-center justify-center p-8 bg-gray-50 h-full overflow-y-auto lg:overflow-hidden">
            <div class="w-full max-w-2xl">
                
                {{-- Header Mobile Only --}}
                <div class="lg:hidden text-center mb-6">
                    <h2 class="text-2xl font-bold text-green-800">Daftar Akun Baru</h2>
                </div>

                {{-- Judul Desktop --}}
                <div class="hidden lg:block mb-6 border-b border-gray-200 pb-2">
                    <h2 class="text-2xl font-bold text-gray-800">📝 Registrasi Siswa Baru</h2>
                    <p class="text-xs text-gray-500">Lengkapi data di bawah ini untuk membuat akun.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- GRID COMPACT: 2 Kolom Rapat --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">

                        {{-- === DATA AKUN === --}}
                        <div class="sm:col-span-2">
                            <h3 class="text-xs font-bold text-green-700 uppercase tracking-wide mb-2">1. Data Akun</h3>
                        </div>

                        {{-- Nama --}}
                        <div class="sm:col-span-2">
                            <input type="text" name="name" required placeholder="Nama Lengkap (Sesuai Akta)" 
                                   class="w-full rounded border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white shadow-sm">
                        </div>

                        {{-- Email --}}
                        <div>
                            <input type="email" name="email" required placeholder="Email Aktif" 
                                   class="w-full rounded border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white shadow-sm">
                        </div>

                        {{-- No HP --}}
                        <div>
                            <input type="number" name="no_hp" required placeholder="No. WhatsApp" 
                                   class="w-full rounded border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white shadow-sm">
                        </div>

                        {{-- Password --}}
                        <div>
                            <input type="password" name="password" required placeholder="Password (Min. 8)" 
                                   class="w-full rounded border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white shadow-sm">
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <input type="password" name="password_confirmation" required placeholder="Ulangi Password" 
                                   class="w-full rounded border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white shadow-sm">
                        </div>

                        {{-- === BIODATA === --}}
                        <div class="sm:col-span-2 mt-2">
                            <h3 class="text-xs font-bold text-green-700 uppercase tracking-wide mb-2">2. Biodata Atlet</h3>
                        </div>

                        {{-- Tgl Lahir --}}
                        <div>
                            <label class="text-[10px] text-gray-500 block ml-1">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" required 
                                   class="w-full rounded border-gray-300 px-3 py-1.5 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white shadow-sm">
                        </div>

                        {{-- Gender --}}
                        <div>
                            <label class="text-[10px] text-gray-500 block ml-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" required class="w-full rounded border-gray-300 px-3 py-1.5 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white shadow-sm">
                                <option value="">- Pilih -</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        {{-- Sekolah --}}
                        <div>
                            <input type="text" name="nama_sekolah" placeholder="Asal Sekolah" 
                                   class="w-full rounded border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white shadow-sm">
                        </div>

                        {{-- Orang Tua --}}
                        <div>
                            <input type="text" name="nama_orang_tua" placeholder="Nama Orang Tua" 
                                   class="w-full rounded border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white shadow-sm">
                        </div>

                        {{-- Alamat (Full Width) --}}
                        <div class="sm:col-span-2">
                            <input type="text" name="alamat" placeholder="Alamat Lengkap (Singkat saja)" 
                                   class="w-full rounded border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white shadow-sm">
                        </div>

                    </div>

                    {{-- ERROR MESSAGE (Compact) --}}
                    @if ($errors->any())
                        <div class="mt-2 text-red-500 text-xs text-center">
                            * {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- TOMBOL AKSI --}}
                    <div class="mt-6 flex items-center justify-between">
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-green-700 font-medium">
                            &larr; Login Saja
                        </a>

                        <button type="submit" class="bg-green-700 hover:bg-green-800 text-white font-bold py-2.5 px-8 rounded shadow-lg transform hover:-translate-y-0.5 transition text-sm">
                            DAFTAR 🚀
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>
</html>