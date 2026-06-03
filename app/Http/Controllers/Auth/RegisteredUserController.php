<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Atlet; // [PENTING] Import Model Atlet
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB; // [PENTING] Import DB untuk transaksi

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Data
        $request->validate([
            // Validasi Akun
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // Validasi Data Atlet
            'tgl_lahir' => [
                'required', 
                'date', 
                'before:-5 years' // Aturan: Harus lahir sebelum 5 tahun lalu (Minimal umur 5 th)
            ],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'no_hp' => ['required', 'numeric'],
            'alamat' => ['nullable', 'string'],
            'nama_sekolah' => ['nullable', 'string', 'max:255'],
            'nama_orang_tua' => ['nullable', 'string', 'max:255'],
        ], [
            // Pesan Error Bahasa Indonesia (Opsional, biar lebih ramah)
            'tgl_lahir.before' => 'Maaf, usia minimal untuk mendaftar adalah 5 tahun.',
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // 2. Proses Simpan ke Database (Pakai Transaction)
        DB::transaction(function () use ($request) {
            
            // A. Buat Akun User (Login)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'atlet', // Otomatis jadi Role Atlet
            ]);

            // B. Hitung Umur & Tentukan Kategori (KU)
            $usia = \Carbon\Carbon::parse($request->tgl_lahir)->age;
            
            $kategori = 'KU-18'; // Default
            if($usia <= 10) $kategori = 'KU-10';
            elseif($usia <= 12) $kategori = 'KU-12';
            elseif($usia <= 14) $kategori = 'KU-14';
            elseif($usia <= 16) $kategori = 'KU-16';
            
            // C. Buat Data Atlet
            Atlet::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->name,
                
                // [PERBAIKAN KUNCI DI SINI]
                // Kolom Database  =>  Data dari Input Form
                'tanggal_lahir'    => $request->tgl_lahir,  // Ubah 'tgl_lahir' jadi 'tanggal_lahir'
                'jenis_kelamin'    => $request->jenis_kelamin,
                'no_hp_atlet'      => $request->no_hp,      // Ubah 'no_hp' jadi 'no_hp_atlet'
                
                'alamat'           => $request->alamat,
                'nama_sekolah'     => $request->nama_sekolah,
                'nama_orang_tua'   => $request->nama_orang_tua,
                'kategori'         => $kategori,
                'status'           => 'Aktif',
                'posisi'           => 'Belum Ditentukan',
            ]);

            // D. Login & Event Registered
            event(new Registered($user));
            Auth::login($user);
        });

        // 3. Redirect ke Dashboard setelah sukses
        return redirect(route('dashboard', absolute: false));
    }
}