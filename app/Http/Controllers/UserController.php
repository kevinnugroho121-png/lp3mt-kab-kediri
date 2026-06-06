<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered; // <--- [TAMBAHKAN BARIS INI]

class UserController extends Controller
{
    // 1. TAMPILKAN DAFTAR USER
    public function index()
    {
        $users = User::with('kecamatan')
                     ->latest()
                     ->paginate(10);

        return view('admin.user.index', compact('users'));
    }

    // 2. FORM TAMBAH USER
    public function create()
    {
        // Mengambil semua kecamatan beserta relasi user yang ber-role 'korcam'
        $kecamatans = Kecamatan::with(['users' => function($query) {
            $query->where('role', 'korcam');
        }])->orderBy('nama_kecamatan', 'ASC')->get();

        return view('admin.user.create', compact('kecamatans'));
    }

    // 3. SIMPAN USER BARU
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required',
            
            // Validasi: Kecamatan & Jabatan WAJIB diisi JIKA role = korcam
            'kecamatan_id'   => 'required_if:role,korcam',
            'jabatan_korcam' => 'required_if:role,korcam', 
        ]);

        $kecamatan_id = null;
        $jabatan_korcam = null;

        // Jika Role yang dipilih adalah KORCAM, ambil datanya
        if ($request->role == 'korcam') {
            $kecamatan_id   = $request->kecamatan_id;
            $jabatan_korcam = $request->jabatan_korcam; // Isinya 'Ketua', 'Anggota 1', atau 'Anggota 2'
            
            // [OPSIONAL] Double Check di Backend untuk keamanan (mencegah data ganda)
            $exists = User::where('role', 'korcam')
                          ->where('kecamatan_id', $kecamatan_id)
                          ->where('jabatan_korcam', $jabatan_korcam)
                          ->exists();
            
            if ($exists) {
                return back()->withInput()->with('error', "Posisi $jabatan_korcam di kecamatan tersebut sudah terisi!");
            }
        }

        // Simpan ke Database dan tampung di variabel $user
        $user = User::create([
            'name'           => strtoupper($request->name),    // [FIX] Paksa Huruf Besar
            'email'          => strtolower($request->email),   // [FIX] Paksa Huruf Kecil
            'password'       => Hash::make($request->password),
            'role'           => $request->role,
            'kecamatan_id'   => $kecamatan_id,
            'jabatan_korcam' => $jabatan_korcam, 
        ]);

        // [TAMBAHAN BARU] Pancing sistem untuk ngirim email verifikasi!
        event(new Registered($user));

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan dan email verifikasi telah dikirim!');
    }

    // 4. HAPUS USER
    public function destroy(User $user)
    {
        // Cegah hapus akun sendiri
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus');
    }

    // ==========================================================
    // 5. TAMBAHAN PENTING: CEK KETERSEDIAAN POSISI (AJAX)
    // ==========================================================
    // Fungsi ini dipanggil oleh JavaScript di create.blade.php
    public function checkKorcamAvailability(Request $request)
    {
        $kecamatanId = $request->kecamatan_id;
        $jabatan = $request->jabatan; // 'Ketua', 'Anggota 1', 'Anggota 2'

        // Cek di database apakah ada user dengan role korcam, di kecamatan X, jabatan Y
        $exists = User::where('role', 'korcam')
                      ->where('kecamatan_id', $kecamatanId)
                      ->where('jabatan_korcam', $jabatan)
                      ->exists();

        // Kembalikan jawaban ke JavaScript (JSON)
        return response()->json(['exists' => $exists]);
    }
}