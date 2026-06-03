<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lembaga;
use App\Models\Kecamatan;
use App\Models\Desa;

class LembagaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan Kecamatan Wates ada, ambil ID-nya
        $kecamatanWates = Kecamatan::where('nama_kecamatan', 'WATES')->first();

        if (!$kecamatanWates) {
            $this->command->error('Kecamatan WATES tidak ditemukan di database. Pastikan data wilayah sudah disedding.');
            return;
        }

        // Helper function untuk mencari ID Desa berdasarkan nama di Kecamatan Wates
        $getDesaId = function ($namaDesa) use ($kecamatanWates) {
            $desa = Desa::where('nama_desa', $namaDesa)
                        ->where('kecamatan_id', $kecamatanWates->id)
                        ->first();
            
            if (!$desa) {
                 // Fallback atau error handling jika desa tidak ketemu.
                 // Untuk seeder, kita bisa lewati atau bikin dummy, tapi sebaiknya kasih warning
                 $this->command->warn("Desa {$namaDesa} tidak ditemukan di Kecamatan Wates.");
                 return null;
            }
            return $desa->id;
        };

        $dataLembaga = [
            ['nama_lembaga' => "AL ASY'ARI POJOK WATES", 'desa' => 'POJOK', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HUDA JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL WATHON SUMBERAGUNG WATES", 'desa' => 'SUMBERAGUNG', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL BAROKAH POJOK WATES", 'desa' => 'POJOK', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HIDAYAH ULA JANTI WATES", 'desa' => 'JANTI', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HIDAYAH WUSTHO JANTI WATES", 'desa' => 'JANTI', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HIKMAH JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IKHLAS JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IRSYAD WONOREJO WATES", 'desa' => 'WONOREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL MINHAJJ WATES WATES", 'desa' => 'WATES', 'ormas' => 'Lainnya'], // "-" diset jadi Lainnya
            ['nama_lembaga' => "AL HUSNA WATES WATES", 'desa' => 'WATES', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IHSAN JAJAR WATES", 'desa' => 'JAJAR', 'ormas' => 'NU'],
            ['nama_lembaga' => "AR ROHMAN TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "BAITUL MUTTAQIIN NGIJO SUMBERAGUNG WATES", 'desa' => 'SUMBERAGUNG', 'ormas' => 'NU'],
            ['nama_lembaga' => "BAITUL UMMAH SUMBERAGUNG WATES", 'desa' => 'SUMBERAGUNG', 'ormas' => 'NU'],
            ['nama_lembaga' => "DARUL KHOIR JAJAR WATES", 'desa' => 'JAJAR', 'ormas' => 'NU'],
            ['nama_lembaga' => "DARUSSALAMAH SIDOMULYO WATES", 'desa' => 'SIDOMULYO', 'ormas' => 'NU'],
            ['nama_lembaga' => "HIDAYATUL MUBTADIIN AL MUWAZANAH PLAOSAN WATES", 'desa' => 'PLAOSAN', 'ormas' => 'NU'],
            ['nama_lembaga' => "MADINATUL ULUM TEKENUWUNG SUMBERAGUNG WATES", 'desa' => 'SUMBERAGUNG', 'ormas' => 'NU'],
            ['nama_lembaga' => "MIFTAKHUL HUDA SILIR WATES", 'desa' => 'SILIR', 'ormas' => 'NU'],
            ['nama_lembaga' => "MIFTAKHUL MUBTADIIN SIDOMULYO WATES", 'desa' => 'SIDOMULYO', 'ormas' => 'NU'],
            ['nama_lembaga' => "MMQ ROUDLOTUL BADI'AH PAGU WATES", 'desa' => 'PAGU', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL QUR'AN PAGU WATES", 'desa' => 'PAGU', 'ormas' => 'NU'],
            ['nama_lembaga' => "ROUDLOTUL QUR'AN TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "SABILILLAH JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL ISHLAH DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "BAITURROHIM DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "BABUSSALAM NGIJO SUMBERAGUNG WATES", 'desa' => 'SUMBERAGUNG', 'ormas' => 'NU'],
            ['nama_lembaga' => "ROUDLOTUS SALAAM PLAOSAN WATES", 'desa' => 'PLAOSAN', 'ormas' => 'NU'],
            ['nama_lembaga' => "JABAL NUUR DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL JAMI' TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL QUR'AN 2 PAGU WATES", 'desa' => 'PAGU', 'ormas' => 'NU'],
        ];

        $insertedCount = 0;

        foreach ($dataLembaga as $data) {
            $desaId = $getDesaId($data['desa']);

            // Hanya insert jika desa ditemukan
            if ($desaId) {
                Lembaga::create([
                    'nama_lembaga' => $data['nama_lembaga'],
                    'jenis_lembaga' => 'MADIN',
                    'kecamatan_id' => $kecamatanWates->id,
                    'desa_id' => $desaId,
                    'ormas' => $data['ormas'],
                    'jumlah_santri' => 0,
                    'jumlah_guru' => 0,
                    'status_ijop' => 'Pending',
                    'status_super' => 'Pending',
                    // Field file otomatis null jika tidak diset
                ]);
                $insertedCount++;
            }
        }

        $this->command->info("Berhasil menambahkan {$insertedCount} data Lembaga MADIN di Kecamatan Wates.");
    }
}