<?php

namespace App\Imports;

use App\Models\Lembaga;
use App\Models\Kecamatan; // Pastikan model ini ada
use App\Models\Desa;      // Pastikan model ini ada
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class LembagaImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{
    /**
     * Proses memasukkan data dari baris Excel ke Database
     */
    public function model(array $row)
    {
        // 1. CARI ID KECAMATAN OTOMATIS
        $namaKec = trim($row['kecamatan']);
        // Menyesuaikan jika nama kolom di tabel kecamatans adalah 'nama' atau 'nama_kecamatan'
        $kecamatan = Kecamatan::where('nama_kecamatan', 'LIKE', '%' . $namaKec . '%')->first();

        if (!$kecamatan) {
            throw new \Exception("Gagal! Kecamatan '{$namaKec}' tidak ditemukan di database.");
        }

        // 2. CARI ID DESA OTOMATIS (Di dalam kecamatan tersebut)
        $namaDesa = trim($row['desa']);
        $desa = Desa::where('kecamatan_id', $kecamatan->id)
                    ->where('nama_desa', 'LIKE', '%' . $namaDesa . '%')->first();

        if (!$desa) {
            throw new \Exception("Gagal! Desa '{$namaDesa}' di Kecamatan '{$namaKec}' tidak ditemukan.");
        }

        // 3. AMANKAN FORMAT TANGGAL IJOP
        $masaBerlaku = null;
        if (!empty($row['masa_berlaku_ijop'])) {
            try {
                if (is_numeric($row['masa_berlaku_ijop'])) {
                    $masaBerlaku = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['masa_berlaku_ijop'])->format('Y-m-d');
                } else {
                    $masaBerlaku = Carbon::parse($row['masa_berlaku_ijop'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $masaBerlaku = null;
            }
        }

        // 4. SIMPAN KE DATABASE LEMBAGA
        return new Lembaga([
            'kecamatan_id'            => $kecamatan->id,
            'desa_id'                 => $desa->id,
            'nama_lembaga'            => strtoupper(trim($row['nama_lembaga'])),
            'jenis_lembaga'           => strtoupper(trim($row['jenis_lembaga'])),
            'nsbq'                    => $row['nsbq'] ?? null,
            'ormas'                   => strtoupper($row['ormas'] ?? 'NU'),
            'status'                  => strtoupper($row['status'] ?? 'AKTIF'),
            'alamat'                  => strtoupper($row['alamat'] ?? ''),
            'kepala_lembaga'          => strtoupper($row['kepala_lembaga'] ?? ''),
            'no_telp'                 => $row['no_hp'] ?? null,
            
            // Data dinamis diisi 0, biar otomatis dihitung sistem dari tabel guru
            'jumlah_santri'           => $row['jumlah_santri'] ?? 0,
            'jumlah_guru'             => 0,
            'penerima_insentif'       => 0,
            'belum_menerima_insentif' => 0,
            'jumlah_pns'              => 0,
            'jumlah_pppk'             => 0,
            'jumlah_sertifikasi'      => 0,
            
            // Status Berkas & Dokumen diset ke default/pending
            'ijop'                    => strtoupper($row['ijop'] ?? 'TIDAK ADA'),
            'masa_berlaku_ijop'       => $masaBerlaku,
            'status_ijop'             => 'Pending',
            'status_super'            => 'Pending',
            'keterangan'              => $row['keterangan'] ?? null,
        ]);
    }

    /**
     * Aturan Validasi Inti (Cegah Baris Kosong)
     */
    public function rules(): array
    {
        return [
            '*.nama_lembaga'  => 'required|string',
            '*.jenis_lembaga' => 'required|in:MADIN,TPQ,PONPES,madin,tpq,ponpes',
            '*.kecamatan'     => 'required|string',
            '*.desa'          => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nama_lembaga.required'  => 'Nama Lembaga wajib diisi di Excel.',
            '*.jenis_lembaga.required' => 'Jenis Lembaga wajib diisi.',
            '*.kecamatan.required'     => 'Kecamatan wajib diisi untuk mendeteksi lokasi.',
            '*.desa.required'          => 'Desa wajib diisi.',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }
}