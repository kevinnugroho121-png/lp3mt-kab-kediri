<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Support\Facades\DB;

class WilayahKediriSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Matikan pengecekan Foreign Key agar bisa truncate (hapus bersih)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 2. Bersihkan tabel sebelum isi ulang
        Desa::truncate();
        Kecamatan::truncate();

        // 3. DAFTAR KECAMATAN & DESA
        // Sesuai request: Semua kecamatan masuk, tapi desa hanya Wates dulu.
        $dataWilayah = [
            // --- KECAMATAN WATES (LENGKAP 18 DESA) ---
            'Wates' => [
                'Duwet', 'Gadungan', 'Jajar', 'Janti', 'Joho', 
                'Karanganyar', 'Pagu', 'Plaosan', 'Pojok', 'Segaran', 
                'Sidomulyo', 'Silir', 'Sumberagung', 'Tawang', 
                'Tempurejo', 'Tunge', 'Wates', 'Wonorejo'
            ],

            // --- KECAMATAN LAIN (DESA MENYUSUL) ---
            'Badas'         => [],
            'Banyakan'      => [],
            'Gampengrejo'   => [],
            'Grogol'        => [],
            'Gurah'         => [],
            'Kandangan'     => [],
            'Kandat'        => [],
            'Kayen Kidul'   => [],
            'Kepung'        => [],
            'Kras'          => [],
            'Kunjang'       => [],
            'Mojo'          => [],
            'Ngadiluwih'    => [],
            'Ngancar'       => [],
            'Ngasem'        => [],
            'Pagu'          => [], // Awas: Ada Desa Pagu (Wates) & Kec. Pagu (Beda)
            'Papar'         => [],
            'Pare'          => [],
            'Plemahan'      => [],
            'Plosoklaten'   => [],
            'Puncu'         => [],
            'Purwoasri'     => [],
            'Ringinrejo'    => [],
            'Semen'         => [],
            'Tarokan'       => [],
        ];

        // 4. EKSEKUSI LOOPING
        foreach ($dataWilayah as $kecamatanName => $listDesa) {
            // Simpan Kecamatan
            $kecamatan = Kecamatan::create([
                'nama_kecamatan' => $kecamatanName
            ]);

            // Simpan Desa (Jika ada datanya)
            foreach ($listDesa as $desaName) {
                Desa::create([
                    'nama_desa' => $desaName,
                    'kecamatan_id' => $kecamatan->id
                ]);
            }
        }

        // 5. Kembalikan pengaturan database normal
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}