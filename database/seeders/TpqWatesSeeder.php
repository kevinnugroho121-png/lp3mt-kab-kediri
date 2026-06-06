<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lembaga;
use App\Models\Kecamatan;
use App\Models\Desa;

class TpqWatesSeeder extends Seeder
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
                 $this->command->warn("Desa {$namaDesa} tidak ditemukan di Kecamatan Wates.");
                 return null;
            }
            return $desa->id;
        };

        // Data TPQ sesuai daftar yang diberikan
        $dataLembaga = [
            ['nama_lembaga' => "AL AMIN WONOREJO WATES", 'desa' => 'WONOREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL AZIZIYAH DAWUNG PAGU WATES", 'desa' => 'PAGU', 'ormas' => 'NU'],
            ['nama_lembaga' => "AD DLIYA' SILIR WATES", 'desa' => 'SILIR', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL FALAH PAGU WATES", 'desa' => 'PAGU', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL FALAH TAWANG WATES", 'desa' => 'TAWANG', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL FARUQ WANOREJO WATES", 'desa' => 'WONOREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL FATTAH TEMPUREJO WATES", 'desa' => 'TEMPUREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL GHOFUR WONOREJO WATES", 'desa' => 'WONOREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL GHOZALI JANTI WATES", 'desa' => 'JANTI', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL GHOZALI DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HAFIZH GADUNGAN WATES", 'desa' => 'GADUNGAN', 'ormas' => 'Lainnya'],
            ['nama_lembaga' => "AL HIDAYAH WATES WATES", 'desa' => 'WATES', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HIDAYAH TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HIDAYAH JANTI WATES", 'desa' => 'JANTI', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HIKMAH JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IHSAN TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IHSAN POJOK WATES", 'desa' => 'POJOK', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IKHLAS JANTI WATES", 'desa' => 'JANTI', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IKHLAS KARANGANYAR WATES", 'desa' => 'KARANGANYAR', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IKHLAS SUMBERAGUNG WATES", 'desa' => 'SUMBERAGUNG', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IRSYAD BEJI WONOREJO WATES", 'desa' => 'WONOREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL ISTIQOMAH GADUNGAN WATES", 'desa' => 'GADUNGAN', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL JIHAD TAWANG WATES", 'desa' => 'TAWANG', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL KHOIROT DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL MA'AARIF POJOK WATES", 'desa' => 'POJOK', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL MUBAROKAH GADUNGAN WATES", 'desa' => 'GADUNGAN', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL MUKARROMAH SIDOMULYO WATES", 'desa' => 'SIDOMULYO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL MU'MINUN WONOREJO WATES", 'desa' => 'WONOREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HUSNA WATES WATES", 'desa' => 'WATES', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HUDA DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IHSAN JAJAR WATES", 'desa' => 'JAJAR', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IKHLAS TIRTO JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL IKHLAS PANDEAN JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL WATHON SUMBERAGUNG WATES", 'desa' => 'SUMBERAGUNG', 'ormas' => 'NU'],
            ['nama_lembaga' => "AN NAJAH JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AN NUR PLAOSAN WATES", 'desa' => 'PLAOSAN', 'ormas' => 'NU'],
            ['nama_lembaga' => "AN NUUR TAWANG WATES", 'desa' => 'TAWANG', 'ormas' => 'NU'],
            ['nama_lembaga' => "AN NUR GADUNGAN WATES", 'desa' => 'GADUNGAN', 'ormas' => 'NU'],
            ['nama_lembaga' => "AR ROHMAN WONOREJO WATES", 'desa' => 'WONOREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "ARRI'AYATUL IJTIMA'IYAH POJOK WATES", 'desa' => 'POJOK', 'ormas' => 'NU'],
            ['nama_lembaga' => "AS SALAM TAWANG WATES", 'desa' => 'TAWANG', 'ormas' => 'NU'],
            ['nama_lembaga' => "ASY SYUHADA' PLAOSAN WATES", 'desa' => 'PLAOSAN', 'ormas' => 'NU'],
            ['nama_lembaga' => "AS SYAFI'I DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "AT TAQWA WATES WATES", 'desa' => 'WATES', 'ormas' => 'NU'],
            ['nama_lembaga' => "BAITUL ABIDIN TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "BAITUN NIKMAH KARANGANYAR WATES", 'desa' => 'KARANGANYAR', 'ormas' => 'NU'],
            ['nama_lembaga' => "BAITURROHIM TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "BAITURROKHIM WONOREJO WATES", 'desa' => 'WONOREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "BAITURROHIM DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "BAITUL MUTTAQIN NGIJO SUMBERAGUNG WATES", 'desa' => 'SUMBERAGUNG', 'ormas' => 'NU'],
            ['nama_lembaga' => "BAITUS SYAKUR GADUNGAN WATES", 'desa' => 'GADUNGAN', 'ormas' => 'NU'],
            ['nama_lembaga' => "BINA UMAT SIDOMULYO WATES", 'desa' => 'SIDOMULYO', 'ormas' => 'NU'],
            ['nama_lembaga' => "DAARUZ ZAHRO TAWANG WATES", 'desa' => 'TAWANG', 'ormas' => 'NU'],
            ['nama_lembaga' => "DARUL ATHFAL SUMBERAGUNG WATES", 'desa' => 'SUMBERAGUNG', 'ormas' => 'NU'],
            ['nama_lembaga' => "DARUL HIKMAH JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "DARUL KHOIR JAJAR WATES", 'desa' => 'JAJAR', 'ormas' => 'NU'],
            ['nama_lembaga' => "DARUS SALAM POJOK WATES", 'desa' => 'POJOK', 'ormas' => 'NU'],
            ['nama_lembaga' => "DARUS SALAM TAWANG WATES", 'desa' => 'TAWANG', 'ormas' => 'NU'],
            ['nama_lembaga' => "HIDAYATUL MUBTADIIN AL MUWAZANAH PLAOSAN WATES", 'desa' => 'PLAOSAN', 'ormas' => 'NU'],
            ['nama_lembaga' => "HIDAYATUN NASYI'IN DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "HUDAYA JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "IBADURROHMAN JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "JALALABA KARANGANYAR WATES", 'desa' => 'KARANGANYAR', 'ormas' => 'NU'],
            ['nama_lembaga' => "MA’ARIF WINONG SIDOMULYO WATES", 'desa' => 'SIDOMULYO', 'ormas' => 'NU'],
            ['nama_lembaga' => "MAKANUL ULUM TEMPUREJO WATES", 'desa' => 'TEMPUREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "MASJID AL IRSYAD POJOK WATES", 'desa' => 'POJOK', 'ormas' => 'NU'],
            ['nama_lembaga' => "MASJID MBAH KAM DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "MIFTAHUL HIDAYAH TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "MIFTAHUL ULUM TAWANG WATES", 'desa' => 'TAWANG', 'ormas' => 'NU'],
            ['nama_lembaga' => "MUNAJATUL QUBRO WONOREJO WATES", 'desa' => 'WONOREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL HUDA DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL IMAN TAWANG WATES", 'desa' => 'TAWANG', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL ISLAM TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL ISHLAH DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL JANNAH SIDOMULYO WATES", 'desa' => 'SIDOMULYO', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL MISBAH WONOREJO WATES", 'desa' => 'WONOREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL QUR'AN 1 PAGU WATES", 'desa' => 'PAGU', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL QUR'AN 2 PAGU WATES", 'desa' => 'PAGU', 'ormas' => 'NU'],
            ['nama_lembaga' => "NUSA SUMBERAGUNG WATES", 'desa' => 'SUMBERAGUNG', 'ormas' => 'NU'],
            ['nama_lembaga' => "RAUDLATUL HUDA POJOK WATES", 'desa' => 'POJOK', 'ormas' => 'NU'],
            ['nama_lembaga' => "ROUDHOTUL QUR'AN TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "ROUDLOTUL ANWAR JAJAR WATES", 'desa' => 'JAJAR', 'ormas' => 'NU'],
            ['nama_lembaga' => "ROUDLOTUS SALAAM PLAOSAN WATES", 'desa' => 'PLAOSAN', 'ormas' => 'NU'],
            ['nama_lembaga' => "ROUDLOTUTH THULLAB UMMU SALAMAH SIDOMULYO WATES", 'desa' => 'SIDOMULYO', 'ormas' => 'NU'],
            ['nama_lembaga' => "SABILILLAH JOHO WATES", 'desa' => 'JOHO', 'ormas' => 'NU'],
            ['nama_lembaga' => "SIROJUL ULUM POJOK WATES", 'desa' => 'POJOK', 'ormas' => 'NU'],
            ['nama_lembaga' => "TARBIYATUL ATHFAL GADUNGAN WATES", 'desa' => 'GADUNGAN', 'ormas' => 'NU'],
            ['nama_lembaga' => "TASWIRUL JUHALA PAGU WATES", 'desa' => 'PAGU', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL JAMI' TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "SABILLUL MUTTAQIN TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HIKMAH TEMPUREJO WATES", 'desa' => 'TEMPUREJO', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL FALAH JAJAR WATES", 'desa' => 'JAJAR', 'ormas' => 'NU'],
            ['nama_lembaga' => "REMAJA AL MUBAROKAH PAGU WATES", 'desa' => 'PAGU', 'ormas' => 'NU'],
            ['nama_lembaga' => "USTHULUL ULUM DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HIKMAH SILIR WATES", 'desa' => 'SILIR', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL MUBAROK SILIR WATES", 'desa' => 'SILIR', 'ormas' => 'NU'],
            ['nama_lembaga' => "BABUSSALAM NGIJO SUMBERAGUNG WATES", 'desa' => 'SUMBERAGUNG', 'ormas' => 'NU'],
            ['nama_lembaga' => "AR ROHMAN TUNGE WATES", 'desa' => 'TUNGE', 'ormas' => 'NU'],
            ['nama_lembaga' => "DARUSSALAM DUWET WATES", 'desa' => 'DUWET', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL JIHAD JAJAR WATES", 'desa' => 'JAJAR', 'ormas' => 'NU'],
            ['nama_lembaga' => "FAAZ JAJAR WATES", 'desa' => 'JAJAR', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL HUDA TAWANG WATES", 'desa' => 'TAWANG', 'ormas' => 'NU'],
            ['nama_lembaga' => "NURUL QUR'AN 3 PAGU WATES", 'desa' => 'PAGU', 'ormas' => 'NU'],
            ['nama_lembaga' => "AL HUDA KARANGANYAR WATES", 'desa' => 'KARANGANYAR', 'ormas' => 'NU'],
        ];

        $insertedCount = 0;

        foreach ($dataLembaga as $data) {
            $desaId = $getDesaId($data['desa']);

            if ($desaId) {
                // Gunakan updateOrCreate agar tidak terjadi duplikat jika seeder dijalankan 2x
                Lembaga::updateOrCreate(
                    [
                        // Parameter Pencarian: Jika nama dan kecamatannya sama, berarti itu lembaga yang sama
                        'nama_lembaga' => $data['nama_lembaga'],
                        'kecamatan_id' => $kecamatanWates->id,
                    ],
                    [
                        // Parameter Pembaruan/Pembuatan
                        'jenis_lembaga' => 'TPQ',
                        'desa_id' => $desaId,
                        'ormas' => $data['ormas'],
                        'jumlah_santri' => 0,
                        'jumlah_guru' => 0,
                        'status_ijop' => 'Pending',
                        'status_super' => 'Pending',
                    ]
                );
                $insertedCount++;
            }
        }

        $this->command->info("Berhasil memproses {$insertedCount} data Lembaga TPQ di Kecamatan Wates.");
    }
}