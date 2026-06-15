<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

// [BARU] Wajib dipanggil untuk akses tabel session dan lempar error
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Cek kecocokan email dan password
        $request->authenticate();

        $user = Auth::user();

        // 2. [BARU] LIMIT DEVICE HANYA BERLAKU UNTUK ROLE 'KORCAM'
        if ($user->role == 'korcam') {
            
            $lifetime = config('session.lifetime') * 60; // Ubah menit ke detik
            $activeLimit = time() - $lifetime;

            // Hitung ada berapa perangkat yang sedang login pakai akun korcam ini
            $activeSessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('last_activity', '>=', $activeLimit)
                ->count();

            // Jika yang nyantol sudah 2 atau lebih, tendang!
            if ($activeSessions >= 2) {
                
                // Logout paksa agar tidak tercatat
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Lemparkan pesan error merah ke halaman login
                throw ValidationException::withMessages([
                    'email' => ['Akses Ditolak! Akun Korcam ini sedang aktif di batas maksimal (2 Perangkat).'],
                ]);
            }
        }

        // 3. Jika aman (Korcam slot masih ada, atau dia adalah Superadmin/Verifikator), izinkan masuk
        $request->session()->regenerate();

        // [BARU] Catat jam tepat saat berhasil login secara permanen
        $user->last_seen_at = now();
        $user->save();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // [BARU] Catat jam terakhir persis sebelum dia diputus dari sistem
        if (Auth::check()) {
            $user = Auth::user();
            $user->last_seen_at = now();
            $user->save();
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
