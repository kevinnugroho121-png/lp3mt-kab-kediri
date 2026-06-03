<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar 26 Kecamatan di Kabupaten Kediri (Urut Abjad)
        $kecamatans = [
            'Badas',
            'Banyakan',
            'Gampengrejo',
            'Grogol',
            'Gurah',
            'Kandangan',
            'Kandat',
            'Kayen Kidul',
            'Kepung',
            'Kras',
            'Kunjang',
            'Mojo',
            'Ngadiluwih',
            'Ngancar',
            'Ngasem',
            'Pagu',
            'Papar',
            'Pare',
            'Plemahan',
            'Plosoklaten',
            'Puncu',
            'Purwoasri',
            'Ringinrejo',
            'Semen',
            'Tarokan',
            'Wates', // Ini tidak akan duplikat karena pakai firstOrCreate
        ];

        foreach ($kecamatans as $nama) {
            Kecamatan::firstOrCreate([
                'nama_kecamatan' => $nama
            ]);
        }
    }
}