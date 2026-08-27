<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Hash;

class KorcamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil 26 data kecamatan
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();

        // Pemetaan nomor akun ke jabatan korcam (Format standar)
        $positions = [
            1 => 'Ketua',
            2 => 'Anggota 1',
            3 => 'Anggota 2'
        ];

        foreach ($kecamatans as $kec) {
            // Bersihkan nama kecamatan (contoh: "KAYEN KIDUL" -> "kayenkidul")
            $slugKec = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $kec->nama_kecamatan));

            foreach ($positions as $nomor => $posisiLabel) {
                // Cek apakah posisi ini di kecamatan tersebut sudah ada akunnya
                $isFilled = User::where('kecamatan_id', $kec->id)
                                ->where('jabatan_korcam', $posisiLabel)
                                ->exists();

                // Jika posisi sudah terisi, lewati (skip) agar tidak membuat akun dobel
                if ($isFilled) {
                    continue;
                }

                $email = "lp3mt{$slugKec}{$nomor}@gmail.com";

                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'              => "KORCAM " . strtoupper($kec->nama_kecamatan) . " - " . strtoupper($posisiLabel),
                        'password'          => Hash::make('kediri2026'),
                        'role'              => 'korcam',
                        'kecamatan_id'      => $kec->id,
                        'jabatan_korcam'    => $posisiLabel,
                        'email_verified_at' => now(),
                    ]
                );
            }
        }
    }
}