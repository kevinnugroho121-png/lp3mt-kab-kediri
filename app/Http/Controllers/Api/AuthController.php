<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // === FUNGSI LOGIN ===
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cek Kredensial (Email & Password)
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal! Email atau Password salah.',
            ], 401);
        }

        // 3. Ambil Data User
        $user = User::where('email', $request->email)->firstOrFail();

        // [PENTING] Cek Role & Ambil Data Profil Detail
        // Supaya di HP nanti langsung lengkap datanya
        if ($user->role == 'atlet') {
            // Muat data dari tabel 'atlets'
            $user->load('atlet'); 
        } elseif ($user->role == 'pelatih') {
            // Muat data dari tabel 'pelatihs'
            $user->load('pelatih');
        }

        // 4. Buat Token Rahasia (Tiket Masuk HP)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Kirim Jawaban JSON ke HP
        return response()->json([
            'success'      => true,
            'message'      => 'Login Berhasil! Selamat datang, ' . $user->name,
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user, // Data user + profil atlet/pelatih nempel disini
        ]);
    }

    // === FUNGSI LOGOUT ===
    public function logout(Request $request)
    {
        // Hapus token yang sedang dipakai (biar gak bisa dipake lagi)
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }
}