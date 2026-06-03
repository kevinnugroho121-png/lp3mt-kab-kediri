<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lembaga;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Support\Facades\DB;

class LembagaTPQSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil ID Kecamatan Wates
        $kecamatan = Kecamatan::firstOrCreate(['nama_kecamatan' => 'Wates']);

        // 2. Lokasi File CSV
        // PASTIKAN nama file di folder database/seeders/csv/ adalah "tpq_wates.csv"
        // Jika nama file Mas masih "tpq kec watess.csv", ubah teks di bawah ini!
        $csvFile = database_path('seeders/csv/tpq_wates.csv'); 

        // Cek file ada atau tidak
        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan di: $csvFile");
            $this->command->warn("Cek nama file di folder database/seeders/csv/, pastikan sama persis!");
            return;
        }

        // 3. Baca File CSV
        $handle = fopen($csvFile, "r");
        $row_index = 0;

        // FUNGSI PEMBERSIH TEKS (PENTING!)
        // Mengubah karakter aneh dari Excel (Windows-1252) ke Database (UTF-8)
        $clean = function($text) {
            // Tambahan pengaman: jika kosong, kembalikan null
            if (!$text) return null;
            return mb_convert_encoding($text, 'UTF-8', 'Windows-1252');
        };

        // Mulai looping baris
        while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) { 
            $row_index++;

            // --- LEWATI HEADER (BARIS KUNING & JUDUL) ---
            // Kita skip baris 1, 2, dan 3. Data mulai baris 4.
            if ($row_index <= 3) { 
                continue; 
            }

            // Cek jika baris kosong (nama lembaga tidak ada)
            if (empty($data[1])) { 
                continue; 
            }

            // --- MAPPING KOLOM CSV ---
            // [1] Nama Lembaga, [2] NSBQ, [3] Alamat, [4] Desa, [5] Kepala, [6] HP, [7] Santri, [8] Guru
            
            $nama_lembaga = $clean($data[1]); 
            $nsbq         = $clean($data[2]);
            $alamat       = $clean($data[3]);
            $nama_desa    = trim(strtoupper($clean($data[4]))); 
            $kepala       = $clean($data[5]);
            $no_telp      = $clean($data[6]);
            $jml_santri   = (int) $data[7];
            $jml_guru     = (int) $data[8];

            // --- FIX TYPO NAMA DESA ---
            if ($nama_desa == 'PLACSAN') $nama_desa = 'PLAOSAN';
            
            // Cari ID Desa
            $desa = Desa::firstOrCreate([
                'nama_desa' => ucwords(strtolower($nama_desa)), // Ubah jadi Title Case
                'kecamatan_id' => $kecamatan->id
            ]);

            // --- SIMPAN KE DATABASE ---
            Lembaga::create([
                'kecamatan_id' => $kecamatan->id,
                'desa_id'      => $desa->id,
                'nama_lembaga' => $nama_lembaga,
                'jenis_lembaga'=> 'TPQ',
                'nsbq'         => $nsbq,
                'ormas'        => 'NU', 
                'status'       => 'AKTIF',
                'alamat'       => $alamat,
                'kepala_lembaga' => $kepala,
                'no_telp'      => $no_telp,
                'jumlah_santri'=> $jml_santri,
                'jumlah_guru'  => $jml_guru,
                'penerima_insentif' => 0,
                'belum_menerima_insentif' => 0,
                'ijop' => 'ADA',
            ]);
        }

        fclose($handle);
        $this->command->info("Sukses import data TPQ Kecamatan Wates!");
    }
}