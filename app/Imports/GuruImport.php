<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\Lembaga; // Wajib import model Lembaga
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class GuruImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{
    /**
     * Proses memasukkan data dari baris Excel ke Database
     */
    public function model(array $row)
    {
        // 1. CARI OTOMATIS ID LEMBAGA BERDASARKAN NAMA DARI EXCEL
        $namaLembagaExcel = trim($row['nama_lembaga']);
        $lembaga = Lembaga::where('nama_lembaga', 'LIKE', '%' . $namaLembagaExcel . '%')->first();
        
        // Jika lembaga salah ketik / tidak ada di database, lemparkan error biar ketahuan
        if (!$lembaga) {
            throw new \Exception("Gagal! Nama Lembaga '{$namaLembagaExcel}' tidak ditemukan di database. Pastikan pengetikannya sama persis.");
        }

        // 2. PECAH TEMPAT DAN TANGGAL LAHIR
        // Asumsi format di excel: "Kediri, 17-08-1990" atau "Kediri, 17 Agustus 1990"
        $ttl = explode(',', $row['tempat_tanggal_lahir']);
        $tempatLahir = trim($ttl[0]);
        $tanggalLahir = null;
        
        if (isset($ttl[1])) {
            try {
                // Mencoba memparsing tanggal dari format teks
                $tanggalLahir = Carbon::parse(trim($ttl[1]))->format('Y-m-d');
            } catch (\Exception $e) {
                // Jika format tanggal aneh, kita biarkan null atau set default
                $tanggalLahir = null; 
            }
        }

        // 3. MASUKKAN KE DATABASE
        return new Guru([
            'lembaga_id'         => $lembaga->id, // Hasil pencarian otomatis
            'jenis_guru'         => strtoupper($row['jenis_lembaga']), 
            'nama_lengkap'       => strtoupper($row['nama_lengkap_tanpa_gelar']),
            'nik'                => $row['nik'],
            
            'tempat_lahir'       => strtoupper($tempatLahir),
            'tanggal_lahir'      => $tanggalLahir, 
            
            'jenis_kelamin'      => strtoupper($row['jenis_kelamin']),
            'nama_ibu_kandung'   => strtoupper($row['nama_ibu_kandung']),
            'agama'              => strtoupper($row['agama']),
            
            // Default value karena tidak ada di Excel LP3MT
            'status_kepegawaian' => 'NON-ASN', 
            'status_sertifikasi' => 'BELUM SERTIFIKASI',
            'penerima_insentif'  => 1, // Default dianggap berhak (Bisa disesuaikan)
            
            'alamat_ktp'         => strtoupper($row['alamat_sesuai_ktp']),
            'desa'               => strtoupper($row['desa']),
            'kecamatan'          => strtoupper($row['kec']),
            'kabupaten'          => strtoupper($row['kab']),
            'no_hp'              => $row['no_hp'],
            'nomor_rekening'     => $row['nomer_rekening'],
            
            // Set default dokumen
            'status_ktp'         => 'Pending',
            'status_kk'          => 'Pending',
            'status_bukurekening'=> 'Pending',
        ]);
    }

    /**
     * Aturan Validasi (Nama Kolom menyesuaikan header Excel LP3MT)
     * Catatan: Library akan mengubah spasi/kurung menjadi underscore (_)
     */
    public function rules(): array
    {
        return [
            '*.nama_lengkap_tanpa_gelar' => 'required|string',
            '*.nik'                      => 'required|numeric|digits:16|unique:gurus,nik',
            '*.nama_lembaga'             => 'required|string',
            '*.jenis_lembaga'            => 'required|string',
            '*.tempat_tanggal_lahir'     => 'required|string',
            '*.jenis_kelamin'            => 'required|string',
            '*.alamat_sesuai_ktp'        => 'required|string',
            '*.nomer_rekening'           => 'required|numeric',
        ];
    }

    /**
     * Pesan Error Custom
     */
    public function customValidationMessages()
    {
        return [
            '*.nama_lengkap_tanpa_gelar.required' => 'Nama Lengkap wajib diisi.',
            '*.nik.required'                      => 'NIK wajib diisi.',
            '*.nik.digits'                        => 'NIK harus pas 16 digit angka.',
            '*.nik.unique'                        => 'Gagal! NIK ini sudah pernah terdaftar di sistem.',
            '*.nama_lembaga.required'             => 'Nama Lembaga tidak boleh kosong.',
            '*.tempat_tanggal_lahir.required'     => 'Tempat & Tanggal Lahir wajib diisi.',
            '*.nomer_rekening.required'           => 'Nomor Rekening wajib diisi.',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }
}