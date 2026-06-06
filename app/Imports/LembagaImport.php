<?php

namespace App\Imports;

use App\Models\Lembaga;
use App\Models\Kecamatan;
use App\Models\Desa;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class LembagaImport implements ToCollection, WithHeadingRow
{
    protected $user;
    public $errors = [];

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function collection(Collection $rows)
    {
        $processedRows = [];

        // ========================================================
        // LOOP 1: VALIDASI KETAT & CEK GANDA (SISTEM REJECT-ALL)
        // ========================================================
        foreach ($rows as $index => $row) {
            $lineNumber = $index + 2; // Baris Excel (Header dihitung baris 1)

            // Skip jika baris benar-benar kosong melompong
            if (empty(array_filter($row->toArray()))) {
                continue;
            }

            // Antisipasi flexibilitas nama kolom (Mendukung KEC / KECAMATAN)
            $rawKec   = $row['kec'] ?? $row['kecamatan'] ?? null;
            $rawDesa  = $row['desa'] ?? null;
            $rawNama  = $row['nama_lembaga'] ?? null;
            $rawJenis = $row['jenis_lembaga'] ?? null;

            // A. Validasi Kolom Wajib Kosong
            if (empty(trim($rawNama))) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'NAMA LEMBAGA' wajib diisi.";
            }
            if (empty(trim($rawJenis))) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'JENIS LEMBAGA' wajib diisi.";
            } elseif (!in_array(strtoupper(trim($rawJenis)), ['MADIN', 'TPQ', 'PONPES'])) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Jenis Lembaga '{$rawJenis}' tidak valid. Harus berisi MADIN, TPQ, atau PONPES.";
            }
            if (empty(trim($rawKec))) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'KEC' (Kecamatan) wajib diisi.";
            }
            if (empty(trim($rawDesa))) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'DESA' wajib diisi.";
            }

            // Jika ada kolom dasar yang kosong, lewati baris ini agar query wilayah tidak crash
            if (empty(trim($rawNama)) || empty(trim($rawJenis)) || empty(trim($rawKec)) || empty(trim($rawDesa))) {
                continue;
            }

            // B. Validasi Keberadaan Wilayah di Sistem Database
            $kecamatan = Kecamatan::where('nama_kecamatan', 'LIKE', '%' . trim($rawKec) . '%')->first();
            if (!$kecamatan) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Kecamatan '{$rawKec}' tidak terdaftar dalam database sistem.";
                continue;
            }

            // Hak Akses Korcam: Tidak boleh import data kecamatan lain
            if ($this->user->role == 'korcam' && $kecamatan->id != $this->user->kecamatan_id) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Anda tidak memiliki wewenang mengimpor data di luar wilayah Kecamatan Anda.";
                continue;
            }

            $desa = Desa::where('kecamatan_id', $kecamatan->id)
                        ->where('nama_desa', 'LIKE', '%' . trim($rawDesa) . '%')->first();
            if (!$desa) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Desa '{$rawDesa}' tidak ditemukan di wilayah Kecamatan '{$rawKec}'.";
                continue;
            }

            // C. Validasi Duplikasi Data Internal File Excel (Antar baris Excel)
            $namaLembagaUpper = strtoupper(trim($rawNama));
            $keyKombinasiUnik = $namaLembagaUpper . '|' . $kecamatan->id . '|' . $desa->id;

            if (isset($processedRows[$keyKombinasiUnik])) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Duplikasi terdeteksi di dalam file Excel! Lembaga '{$namaLembagaUpper}' kembar dengan data di Baris Ke-" . $processedRows[$keyKombinasiUnik];
            } else {
                $processedRows[$keyKombinasiUnik] = $lineNumber;
            }

            // D. Validasi Duplikasi dengan Database Utama
            $isDuplicateInDb = Lembaga::where('nama_lembaga', $namaLembagaUpper)
                                       ->where('kecamatan_id', $kecamatan->id)
                                       ->where('desa_id', $desa->id)
                                       ->exists();
            if ($isDuplicateInDb) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Lembaga '{$namaLembagaUpper}' di Desa '{$rawDesa}' SUDAH ADA di dalam database aplikasi.";
            }
        }

        // JIKA DIKETAHUI ADA DOSA DATA, LEMPAR STATUS FAIL-SAFE (BATAL TOTAL)
        if (!empty($this->errors)) {
            throw new \Exception("excel_validation_failed");
        }

        // ========================================================
        // LOOP 2: EKSEKUSI DATABASE (Hanya jalan jika 100% Lolos)
        // ========================================================
        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                if (empty(array_filter($row->toArray()))) {
                    continue;
                }

                $rawKec  = $row['kec'] ?? $row['kecamatan'] ?? null;
                $rawDesa = $row['desa'] ?? null;

                $kecamatan = Kecamatan::where('nama_kecamatan', 'LIKE', '%' . trim($rawKec) . '%')->first();
                $desa      = Desa::where('kecamatan_id', $kecamatan->id)
                                 ->where('nama_desa', 'LIKE', '%' . trim($rawDesa) . '%')->first();

                // Parsing format tanggal Masa Berlaku IJOP
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

                Lembaga::create([
                    'kecamatan_id'            => $kecamatan->id,
                    'desa_id'                 => $desa->id,
                    'nama_lembaga'            => strtoupper(trim($row['nama_lembaga'])),
                    'jenis_lembaga'           => strtoupper(trim($row['jenis_lembaga'])),
                    'nsbq'                    => $row['nsbq'] ?? null,
                    'ormas'                   => strtoupper($row['ormas'] ?? 'NU'),
                    'status'                  => strtoupper($row['status'] ?? 'AKTIF'),
                    'alamat'                  => strtoupper($row['alamat'] ?? ''),
                    'kepala_lembaga'          => strtoupper($row['kepala_lembaga'] ?? ''),
                    'no_telp'                 => $row['no_hp'] ?? $row['no_telp'] ?? null,
                    
                    // Pemetaan Kolom Excel Template Baru Mas Kevin
                    'jumlah_santri'           => (int) ($row['jumlah_santri'] ?? 0),
                    'jumlah_guru'             => (int) ($row['jumlah_guru'] ?? 0),
                    'penerima_insentif'       => (int) ($row['penerima_insentif'] ?? 0),
                    'belum_menerima_insentif' => (int) ($row['belum_menerima'] ?? $row['belum_menerima_insentif'] ?? 0),
                    'jumlah_pns'              => (int) ($row['pns'] ?? 0),
                    'jumlah_pppk'             => (int) ($row['pppk'] ?? 0),
                    'jumlah_sertifikasi'      => (int) ($row['sertifikasi'] ?? 0),
                    
                    // Standarisasi Berkas Awal
                    'ijop'                    => strtoupper($row['ijop'] ?? 'TIDAK ADA'),
                    'masa_berlaku_ijop'       => $masaBerlaku,
                    'status_ijop'             => 'Pending',
                    'status_super'            => 'Pending',
                    'status_skam'             => 'Pending',
                    'keterangan'              => strtoupper($row['keterangan'] ?? ''),
                ]);
            }
        });
    }
}