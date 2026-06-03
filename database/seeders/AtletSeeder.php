<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Atlet;

class AtletSeeder extends Seeder
{
    public function run()
    {
        // Pakai Faker versi Indonesia biar namanya lokal
        $faker = Faker::create('id_ID');

        // Kita buat 50 Data Atlet
        for ($i = 1; $i <= 50; $i++) {

            // 1. Tentukan Kategori & Jenjang secara Random tapi Logis
            $pilihan = $faker->randomElement(['SD', 'SMP', 'SMA']);
            
            if ($pilihan == 'SD') {
                $jenjang = 'SD';
                $kategori = $faker->randomElement(['KU-10', 'KU-12']);
                $tahunLahir = $faker->numberBetween(2013, 2015);
            } elseif ($pilihan == 'SMP') {
                $jenjang = 'SMP';
                $kategori = 'KU-14';
                $tahunLahir = $faker->numberBetween(2010, 2012);
            } else {
                $jenjang = 'SMA';
                $kategori = $faker->randomElement(['KU-16', 'KU-18']);
                $tahunLahir = $faker->numberBetween(2007, 2009);
            }

            // Generate Tanggal Lahir sesuai tahun di atas
            $tglLahir = $faker->dateTimeBetween("$tahunLahir-01-01", "$tahunLahir-12-31")->format('Y-m-d');
            
            // Gender
            $gender = $faker->randomElement(['Laki-laki', 'Perempuan']);
            $namaLengkap = $gender == 'Laki-laki' ? $faker->name('male') : $faker->name('female');

            // 2. Buat AKUN LOGIN (User)
            // Emailnya pola: atlet1@gmail.com, atlet2@gmail.com, dst.
            // Password: password123
            $user = User::create([
                'name'     => $namaLengkap,
                'email'    => 'atlet' . $i . '@gmail.com',
                'password' => Hash::make('password123'),
                'role'     => 'atlet',
            ]);

            // 3. Buat DATA ATLET (Sesuai kolom database Mas)
            Atlet::create([
                'user_id'           => $user->id, // Sambungkan ke User diatas
                'nama_lengkap'      => $namaLengkap,
                'nama_panggilan'    => strtok($namaLengkap, " "), // Ambil kata pertama
                'tempat_lahir'      => $faker->city,
                'tanggal_lahir'     => $tglLahir,
                'jenis_kelamin'     => $gender,
                'foto_profil'       => null, // Kosong dulu sesuai request
                'alamat'            => $faker->address,
                'no_hp_atlet'       => $faker->phoneNumber,
                'jenjang_sekolah'   => $jenjang,
                'nama_sekolah'      => $jenjang . ' ' . $faker->company, // Contoh: SD Maju Jaya
                'kategori'          => $kategori,
                'posisi'            => $faker->randomElement(['Point Guard', 'Shooting Guard', 'Small Forward', 'Power Forward', 'Center']),
                'status'            => 'Aktif',
                'nama_orang_tua'    => $faker->name,
                'no_hp_orang_tua'   => $faker->phoneNumber,
            ]);
        }
    }
}