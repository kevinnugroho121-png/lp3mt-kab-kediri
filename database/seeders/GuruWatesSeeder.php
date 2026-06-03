<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;
use App\Models\Lembaga;
use Carbon\Carbon;

class GuruWatesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Lokasi File CSV
        $csvFile = database_path('seeders/csv/guru_wates.csv'); 

        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan: $csvFile");
            return;
        }

        // ============================================================
        // ⚠️ MAPPING KOLOM SESUAI REQUEST MAS (URUTAN 0, 1, 2...)
        // ============================================================
        // 0: No
        // 1: NAMA LENGKAP (tanpa gelar)
        // 2: TEMPAT TANGGAL LAHIR
        // 3: JENIS KELAMIN
        // 4: NIK
        // 5: NAMA LEMBAGA
        // 6: JENIS LEMBAGA
        // 7: ALAMAT SESUAI KTP
        // 8: DESA
        // 9: KEC
        // 10: KAB
        // 11: AGAMA
        // 12: NO HP
        // 13: NAMA IBU KANDUNG
        // 14: NOMER REKENING
        // 15: KETERANGAN

        $col_nama_guru    = 1; 
        $col_ttl          = 2; // Isinya gabungan (Contoh: KEDIRI, 20-01-1990)
        $col_jk           = 3;
        $col_nik          = 4;
        $col_nama_lembaga = 5;
        $col_alamat       = 7;
        $col_desa         = 8;
        $col_agama        = 11;
        $col_hp           = 12;
        $col_ibu          = 13;
        $col_rekening     = 14;
        $col_ket          = 15;

        // ============================================================

        $handle = fopen($csvFile, "r");
        $row_index = 0;

        // Fungsi Bersih-bersih Teks
        $clean = function($text) {
            if (!$text) return null;
            return trim(mb_convert_encoding($text, 'UTF-8', 'Windows-1252'));
        };

        $this->command->info("Mulai import data Guru...");

        while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) { 
            // CATATAN: Ganti koma (",") jadi titik koma (";") jika CSV Mas pakai titik koma
            
            $row_index++;
            // Lewati Header (Baris 1 saja, atau sesuaikan)
            if ($row_index <= 1) continue; 
            
            // Skip jika Nama Guru kosong
            if (empty($data[$col_nama_guru])) continue; 

            // 1. AMBIL DATA
            $raw_lembaga  = $clean($data[$col_nama_lembaga]);
            $nama_guru    = $clean($data[$col_nama_guru]);
            $raw_ttl      = $clean($data[$col_ttl]);
            $nik          = $clean($data[$col_nik]);
            $jk           = $clean($data[$col_jk]);
            $alamat       = $clean($data[$col_alamat]);
            $desa_domisili= $clean($data[$col_desa]);
            $agama        = $clean($data[$col_agama]);
            $no_telp      = $clean($data[$col_hp]);
            $ibu_kandung  = $clean($data[$col_ibu]);
            $no_rekening  = $clean($data[$col_rekening]);
            $keterangan   = $clean($data[$col_ket]);

            // 2. PISAHKAN TEMPAT & TANGGAL LAHIR
            // Logika: Cari koma terakhir sebagai pemisah
            $tempat_lahir = null;
            $tanggal_lahir = null;

            if ($raw_ttl) {
                // Coba pisahkan berdasarkan koma (Contoh: "Kediri, 12-05-1990")
                $parts = explode(',', $raw_ttl);
                
                if (count($parts) >= 2) {
                    $tanggal_str = trim(end($parts)); // Ambil bagian paling belakang sebagai tanggal
                    $tempat_lahir = trim(str_replace($tanggal_str, '', $raw_ttl)); // Sisanya adalah tempat
                    $tempat_lahir = rtrim($tempat_lahir, ', '); // Hapus koma sisa
                    
                    try {
                        // Coba format umum Indonesia
                        $tanggal_lahir = Carbon::parse($tanggal_str)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $tanggal_lahir = null; // Gagal parsing tanggal
                    }
                } else {
                    // Kalau tidak ada koma, anggap semuanya tempat lahir
                    $tempat_lahir = $raw_ttl;
                }
            }

            // 3. CARI ID LEMBAGA
            // Cari yg namanya mirip (antisipasi typo spasi/tanda baca)
            $lembaga = Lembaga::where('nama_lembaga', 'LIKE', $raw_lembaga)
                              ->orWhere('nama_lembaga', 'LIKE', "%$raw_lembaga%")
                              ->first();

            if (!$lembaga) {
                // $this->command->warn("SKIP: Lembaga '$raw_lembaga' tidak ditemukan.");
                continue; 
            }

            // 4. SIMPAN DATA GURU
            Guru::create([
                'lembaga_id'          => $lembaga->id,
                'nama_guru'           => $nama_guru,
                'nik'                 => $nik,
                'jenis_kelamin'       => $jk,
                'tempat_lahir'        => $tempat_lahir,
                'tanggal_lahir'       => $tanggal_lahir,
                'alamat'              => $alamat,
                'desa'                => $desa_domisili,
                'no_telp'             => $no_telp,
                'agama'               => $agama,
                'nama_ibu_kandung'    => $ibu_kandung,
                'no_rekening'         => $no_rekening,
                'keterangan'          => $keterangan,
                'jabatan'             => 'GURU', // Default
            ]);
        }

        fclose($handle);
        $this->command->info("Selesai import data Guru!");
    }
}