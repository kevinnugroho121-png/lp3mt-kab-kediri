<x-guest-layout>
    
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex flex-col items-center mb-6 text-center">
        {{-- LOGO MASJID (SVG Path Baru) --}}
        <div class="mb-4">
            <svg class="w-20 h-20 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C10.5 2 9 3 9 4V8H5V20H9V14H15V20H19V8H15V4C15 3 13.5 2 12 2M7 10H9V12H7V10M15 10H17V12H15V10M11 5H13V8H11V5Z" />
                <path d="M2 10L5 10L5 22L19 22L19 10L22 10L12 0L2 10Z" opacity="0.2"/>
            </svg>
        </div>
        
        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">LP3MT</h1>
        <p class="text-xs font-bold text-green-600 uppercase tracking-[0.2em] mt-1">Kabupaten Kediri</p>
        <p class="text-sm text-gray-400 mt-2">Sistem Pendataan Guru Ngaji</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div class="relative">
            <input id="email" class="peer block w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-gray-50 placeholder-transparent focus:border-green-500 focus:bg-white focus:outline-none focus:ring-0 transition-colors" 
                   type="email" name="email" :value="old('email')" required autofocus 
                   placeholder="Email" />
            <label for="email" class="absolute left-4 -top-2.5 bg-white px-1 text-xs font-semibold text-green-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-400 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600">
                Email / Username
            </label>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="relative">
            <input id="password" class="peer block w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-gray-50 placeholder-transparent focus:border-green-500 focus:bg-white focus:outline-none focus:ring-0 transition-colors" 
                   type="password" name="password" required autocomplete="current-password"
                   placeholder="Password" />
            <label for="password" class="absolute left-4 -top-2.5 bg-white px-1 text-xs font-semibold text-green-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-400 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600">
                Password
            </label>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="flex items-center justify-between mt-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-gray-500 group-hover:text-gray-700 transition">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-green-600 hover:text-green-800 font-medium transition" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-green-600/30 transition duration-200 transform hover:-translate-y-0.5 mt-4">
            Masuk Aplikasi
        </button>

{{-- TAMBAHAN: TOMBOL KEMBALI KE LANDING PAGE --}}
    <div class="mb-6">
        <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Beranda
        </a>
    </div>
    {{-- BATAS TAMBAHAN --}}

        
    </form>
</x-guest-layout>