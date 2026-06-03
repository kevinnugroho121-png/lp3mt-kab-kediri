<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LP3MT Kab. Kediri') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- [LIBRARY] SweetAlert2 (Wajib untuk Pop-up Keren) --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        
        <div class="flex h-screen overflow-hidden bg-gray-50">
            
            {{-- 1. SIDEBAR (Menu Kiri) --}}
            {{-- Pastikan file resources/views/layouts/sidebar.blade.php ADA. Jika belum, buat file kosong dulu --}}
            @include('layouts.sidebar')

            {{-- 2. KONTEN UTAMA (Kanan) --}}
            <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
                
                {{-- Navbar Atas (Profil & Logout) --}}
                @include('layouts.navigation')

                {{-- Area Konten yang Bisa Di-scroll --}}
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                    
                    {{-- Judul Halaman (Jika ada slot header) --}}
                    @if (isset($header))
                        <header class="bg-white shadow rounded-lg mb-6 p-4">
                            <div class="max-w-7xl mx-auto">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    {{-- ISI KONTEN UTAMA --}}
                    @yield('content')
                    
                    {{-- Footer Kecil --}}
                    <div class="mt-8 text-center text-xs text-gray-400 pb-4">
                        &copy; {{ date('Y') }} LP3MT Kabupaten Kediri. All rights reserved.
                    </div>
                </main>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- [POP-UP SAKTI] MENANGKAP PESAN DARI CONTROLLER --}}
        {{-- ========================================================== --}}
        
        <script>
            // 1. Cek Apakah ada pesan SUKSES dari Controller?
            // (Contoh: ->with('success', 'Data berhasil disimpan'))
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000, // Otomatis nutup dalam 3 detik
                    timerProgressBar: true
                });
            @endif

            // 2. Cek Apakah ada pesan ERROR dari Controller?
            // (Contoh: ->with('error', 'Gagal menghapus data'))
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33',
                });
            @endif

            // 3. Cek Apakah ada pesan INFO/WARNING?
            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: "{{ session('warning') }}",
                    confirmButtonColor: '#f59e0b',
                });
            @endif
        </script>

    </body>
</html>