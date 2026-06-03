<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;
use App\Models\Desa;

class DesaWatesSeeder extends Seeder
{
    public function run()
    {
        // 1. Pastikan Kecamatan Wates ada
        $kec = Kecamatan::firstOrCreate(['nama_kecamatan' => 'Wates']);

        // 2. Daftar 18 Desa Resmi di Kec. Wates (Urut Abjad)
        $daftar_desa = [
            'Duwet',
            'Gadungan',
            'Jajar',
            'Janti',
            'Joho',
            'Karanganyar', // Tidak ada di Excel Madin
            'Pagu',
            'Plaosan',     // Di Excel tertulis 'PLACSAN' (Typo)
            'Pojok',
            'Segaran',     // Tidak ada di Excel Madin
            'Sidomulyo',
            'Silir',
            'Sumberagung',
            'Tawang',      // Ada TPQ, tapi tidak ada Madin di Excel
            'Tempurejo',   // Tidak ada di Excel Madin
            'Tunge',
            'Wates',
            'Wonorejo',
        ];

        // 3. Masukkan ke Database
        foreach ($daftar_desa as $nama) {
            Desa::firstOrCreate([
                'kecamatan_id' => $kec->id,
                'nama_desa' => $nama
            ]);
        }
    }
}