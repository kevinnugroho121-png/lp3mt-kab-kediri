<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kecamatan;
// use App\Models\Desa; // Tidak butuh model Desa lagi di sini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // 1. PANGGIL DATA REAL DARI SEEDER LAIN
        // ==========================================
        // Urutan ini PENTING agar tidak error foreign key
        $this->call([
            KecamatanSeeder::class,     // 1. Masukkan 26 Kecamatan
            DesaWatesSeeder::class,     // 2. Masukkan 18 Desa di Wates
            LembagaWatesSeeder::class,  // 3. Masukkan 32 Madin & Guru (Excel)
            LembagaTPQSeeder::class,    // 4. Masukkan TPQ dari CSV
        ]);

        // ==========================================
        // 2. BUAT AKUN LOGIN (USER)
        // ==========================================
        
        // Ambil ID Kecamatan Wates (Pastikan seeder kecamatan sudah jalan)
        $kec_wates = Kecamatan::where('nama_kecamatan', 'Wates')->first();

        // A. SUPER ADMIN (Pusat)
        User::create([
            'name'           => 'Admin Utama',
            'email'          => 'admin@lp3mt.com',
            'password'       => Hash::make('password'),
            'role'           => 'admin', // Sesuaikan value dengan Controller (admin, bukan super_admin)
            'kecamatan_id'   => null,
            'jabatan_korcam' => null,
        ]);

        // B. VERIFIKATOR KABUPATEN
        User::create([
            'name'           => 'Verifikator Kab',
            'email'          => 'verif@lp3mt.com',
            'password'       => Hash::make('password'),
            'role'           => 'verifikator',
            'kecamatan_id'   => null,
            'jabatan_korcam' => null,
        ]);

        // C. KORCAM WATES (Akses 1 Kecamatan)
        if ($kec_wates) {
            User::create([
                'name'           => 'Ketua Korcam Wates',
                'email'          => 'wates@lp3mt.com',
                'password'       => Hash::make('password'),
                'role'           => 'korcam',
                'kecamatan_id'   => $kec_wates->id, 
                'jabatan_korcam' => 'Ketua', // WAJIB DIISI agar tidak error tampilan
            ]);
        }

        // CATATAN:
        // Bagian Operator Desa SUDAH DIHAPUS TOTAL
        // karena fitur tersebut sudah ditiadakan sesuai request Mas.
    }
}