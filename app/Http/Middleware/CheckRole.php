<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan user sudah login
        if (!auth()->check()) {
            return redirect('login');
        }

        // 2. Cek apakah role user saat ini ADA di dalam daftar role yang dikirim (misal: ['admin', 'verifikator'])
        if (in_array(auth()->user()->role, $roles)) {
            return $next($request); // Silakan lewat
        }

        // 3. Jika nyelonong tapi rolenya tidak sesuai, lempar balik ke dashboard
        return redirect('/dashboard')->with('error', 'Akses ditolak! Anda tidak punya izin ke menu tersebut.');
    }
}