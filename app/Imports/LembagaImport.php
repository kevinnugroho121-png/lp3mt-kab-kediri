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

            // 1. Ambil No. Urut fisik kolom 'no'/'nomor' atau hitung urutan otomatis
            $rawNoUrut = trim((string)($row['no'] ?? $row['nomor'] ?? ''));
            $noUrut    = (!empty($rawNoUrut) && is_numeric($rawNoUrut)) ? $rawNoUrut : ($index + 1);

            // Antisipasi flexibilitas nama kolom (Mendukung KEC / KECAMATAN)
            $rawKec   = $row['kec'] ?? $row['kecamatan'] ?? null;
            $rawDesa  = $row['desa'] ?? null;
            $rawNama  = $row['nama_lembaga'] ?? null;
            $rawJenis = $row['jenis_lembaga'] ?? null;

            // 2. Buat Label Identitas Khusus (No Urut + Baris Excel + Nama Lembaga)
            $namaLembagaClean = strtoupper(preg_replace('/\s+/', ' ', trim((string)$rawNama)));
            $labelNama        = !empty($namaLembagaClean) ? " - '{$namaLembagaClean}'" : "";
            $tag              = "No. Urut {$noUrut} (Baris Excel {$lineNumber}){$labelNama}: ";
            $infoBarisIni     = "No. Urut {$noUrut} (Baris Excel {$lineNumber})";

            // A. Validasi Kolom Wajib Kosong
            if (empty(trim((string)$rawNama))) {
                $this->errors[] = "No. Urut {$noUrut} (Baris Excel {$lineNumber}): Kolom 'NAMA LEMBAGA' wajib diisi.";
            }
            if (empty(trim((string)$rawJenis))) {
                $this->errors[] = "{$tag}Kolom 'JENIS LEMBAGA' wajib diisi.";
            } elseif (!in_array(strtoupper(trim((string)$rawJenis)), ['MADIN', 'TPQ', 'PONPES'])) {
                $this->errors[] = "{$tag}Jenis Lembaga '{$rawJenis}' tidak valid. Harus berisi MADIN, TPQ, atau PONPES.";
            }
            if (empty(trim((string)$rawKec))) {
                $this->errors[] = "{$tag}Kolom 'KEC' (Kecamatan) wajib diisi.";
            }
            if (empty(trim((string)$rawDesa))) {
                $this->errors[] = "{$tag}Kolom 'DESA' wajib diisi.";
            }

            // Jika ada kolom dasar yang kosong, lewati baris ini agar query wilayah tidak crash
            if (empty(trim((string)$rawNama)) || empty(trim((string)$rawJenis)) || empty(trim((string)$rawKec)) || empty(trim((string)$rawDesa))) {
                continue;
            }

            // B. Validasi Keberadaan Wilayah di Sistem Database (Pembersihan Otomatis Kata 'Kec.' / 'Kecamatan')
            $cleanKecStr = strtoupper(trim(preg_replace('/^(KEC\.|KECAMATAN)\s+/i', '', (string)$rawKec)));
            $kecamatan = Kecamatan::where('nama_kecamatan', $cleanKecStr)
                                  ->orWhere('nama_kecamatan', 'LIKE', '%' . $cleanKecStr . '%')
                                  ->first();

            if (!$kecamatan) {
                $this->errors[] = "{$tag}Kecamatan '{$rawKec}' tidak terdaftar dalam database sistem.";
                continue;
            }

            // 🛡️ SATPAM WILAYAH KORCAM (Sesuai Arahan Pak Arif di Video)
            if ($this->user->role == 'korcam' && $kecamatan->id != $this->user->kecamatan_id) {
                $namaKecamatanAkun = $this->user->kecamatan->nama_kecamatan ?? 'wilayah Anda';
                $this->errors[] = "{$tag}AKSI DITOLAK! File Excel ini memuat data Kecamatan {$kecamatan->nama_kecamatan}. Anda login sebagai KORCAM {$namaKecamatanAkun} dan HANYA berhak mengimpor data untuk Kecamatan {$namaKecamatanAkun}.";
                continue;
            }

            $desa = Desa::where('kecamatan_id', $kecamatan->id)
                        ->where('nama_desa', 'LIKE', '%' . trim((string)$rawDesa) . '%')->first();
            if (!$desa) {
                $this->errors[] = "{$tag}Desa '{$rawDesa}' tidak ditemukan di wilayah Kecamatan '{$rawKec}'.";
                continue;
            }

            // C. Validasi Duplikasi Data Internal File Excel
            $jenisLembagaUpper = strtoupper(trim((string)$rawJenis));
            
            // Kunci unik: Nama bersih + Jenis + Kecamatan + Desa
            $keyKombinasiUnik = $namaLembagaClean . '|' . $jenisLembagaUpper . '|' . $kecamatan->id . '|' . $desa->id;

            if (isset($processedRows[$keyKombinasiUnik])) {
                $this->errors[] = "{$tag}Duplikasi internal Excel! Lembaga '{$namaLembagaClean}' ({$jenisLembagaUpper}) kembar dengan " . $processedRows[$keyKombinasiUnik];
            } else {
                $processedRows[$keyKombinasiUnik] = $infoBarisIni;
            }

            // D. Validasi Duplikasi dengan Database Utama
            $isDuplicateInDb = Lembaga::where('nama_lembaga', $namaLembagaClean)
                                       ->where('jenis_lembaga', $jenisLembagaUpper)
                                       ->where('kecamatan_id', $kecamatan->id)
                                       ->where('desa_id', $desa->id)
                                       ->exists();
            if ($isDuplicateInDb) {
                $this->errors[] = "{$tag}Lembaga '{$namaLembagaClean}' ({$jenisLembagaUpper}) di Desa '{$rawDesa}' SUDAH ADA di database.";
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

                $cleanKecStr = strtoupper(trim(preg_replace('/^(KEC\.|KECAMATAN)\s+/i', '', (string)$rawKec)));
                $kecamatan = Kecamatan::where('nama_kecamatan', $cleanKecStr)
                                      ->orWhere('nama_kecamatan', 'LIKE', '%' . $cleanKecStr . '%')
                                      ->first();

                // Kunci gembok mutlak untuk Korcam
                $targetKecamatanId = ($this->user->role == 'korcam') ? $this->user->kecamatan_id : ($kecamatan->id ?? null);

                $desa      = Desa::where('kecamatan_id', $targetKecamatanId)
                                 ->where('nama_desa', 'LIKE', '%' . trim($rawDesa) . '%')->first();

                // Parsing format tanggal Masa Berlaku IJOP (Anti-Crash berbagai format)
                $masaBerlaku = null;
                $rawIjopTgl = trim((string)($row['masa_berlaku_ijop'] ?? ''));
                if (!empty($rawIjopTgl) && $rawIjopTgl !== '-') {
                    try {
                        if (is_numeric($rawIjopTgl)) {
                            $masaBerlaku = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rawIjopTgl)->format('Y-m-d');
                        } else {
                            // Samakan pemisah slash jadi dash agar terbaca d-m-Y secara akurat
                            $cleanDateStr = str_replace('/', '-', $rawIjopTgl);
                            $masaBerlaku = Carbon::parse($cleanDateStr)->format('Y-m-d');
                        }
                    } catch (\Exception $e) {
                        $masaBerlaku = null;
                    }
                }

                // 1. Satpam Pembersih No. HP Kepala Lembaga (Kutip ganda, huruf O, & awalan 08)
                $rawHp = (string)($row['no_hp'] ?? $row['no_telp'] ?? '');
                $cleanHp = str_ireplace('o', '0', $rawHp); // Mengubah huruf O jadi angka 0
                $cleanHp = preg_replace('/[^0-9]/', '', $cleanHp); // Hapus kutip, strip, titik, & spasi
                if (str_starts_with($cleanHp, '62')) {
                    $cleanHp = '0' . substr($cleanHp, 2);
                } elseif (str_starts_with($cleanHp, '8')) {
                    $cleanHp = '0' . $cleanHp;
                }

                // 2. Satpam Auto-Koreksi Typo Ormas
                $rawOrmas = strtoupper(trim((string)($row['ormas'] ?? 'NU')));
                if (in_array($rawOrmas, ['MUHAMADIYAH', 'MUHAMMADIYA', 'MUHAMMADIYAH'])) {
                    $cleanOrmas = 'MUHAMMADIYAH';
                } elseif (in_array($rawOrmas, ['NU', 'NJ', 'NAHDLATUL ULAMA'])) {
                    $cleanOrmas = 'NU';
                } elseif ($rawOrmas === 'LDII') {
                    $cleanOrmas = 'LDII';
                } else {
                    $cleanOrmas = $rawOrmas ?: 'NU';
                }

                // 3. Satpam Standardisasi Status Kelembagaan
                $rawStatus = strtoupper(trim((string)($row['status'] ?? 'AKTIF')));
                if (in_array($rawStatus, ['AKTIF', 'AKTIF '])) {
                    $cleanStatus = 'AKTIF';
                } elseif (in_array($rawStatus, ['TIDAK AKTIF', 'NON AKTIF', 'NONAKTIF', 'MATI', 'TIDAK ADA', 'BELUM ADA', '0', 'NON-AKTIF'])) {
                    $cleanStatus = 'TIDAK AKTIF';
                } else {
                    $cleanStatus = $rawStatus ?: 'AKTIF';
                }

                // 4. Logika Cerdas Pembacaan Santri (Support Template Baru L/P & Template Lama 50:50)
                $santriL = (int) ($row['santri_l'] ?? $row['santri_laki_laki'] ?? $row['santri_putra'] ?? 0);
                $santriP = (int) ($row['santri_p'] ?? $row['santri_perempuan'] ?? $row['santri_putri'] ?? 0);
                $totalSantri = (int) ($row['jumlah_santri'] ?? $row['total_santri'] ?? 0);

                if ($santriL > 0 || $santriP > 0) {
                    $totalSantri = $santriL + $santriP;
                } elseif ($totalSantri > 0) {
                    // Jika impor dari template lama yang hanya berisi total, bagi rata otomatis (50:50)
                    $santriL = (int) ceil($totalSantri / 2);
                    $santriP = (int) floor($totalSantri / 2);
                }

                Lembaga::create([
                    'kecamatan_id'            => $kecamatan->id,
                    'desa_id'                 => $desa->id,
                    'nama_lembaga'            => strtoupper(preg_replace('/\s+/', ' ', trim($row['nama_lembaga']))),
                    'jenis_lembaga'           => strtoupper(trim($row['jenis_lembaga'])),
                    'nsbq'                    => $row['nsbq'] ?? null,
                    'ormas'                   => $cleanOrmas,
                    'status'                  => $cleanStatus,
                    'alamat'                  => strtoupper($row['alamat'] ?? ''),
                    'link_gmaps'              => !empty($row['link_google_maps'] ?? $row['link_gmaps'] ?? $row['google_maps'] ?? null) ? trim($row['link_google_maps'] ?? $row['link_gmaps'] ?? $row['google_maps']) : null,
                    'kepala_lembaga'          => strtoupper(trim((string)($row['kepala_lembaga'] ?? ''))),
                    'no_telp'                 => !empty($cleanHp) ? $cleanHp : null,
                    
                    // Kolom Santri Lengkap (Total + L & P)
                    'jumlah_santri'           => $totalSantri,
                    'jumlah_santri_l'         => $santriL,
                    'jumlah_santri_p'         => $santriP,
                    
                    // [DIUBAH] Semua hitungan guru dipaksa jadi 0, mengabaikan ketikan Korcam di Excel
                    'jumlah_guru'             => 0, 
                    'penerima_insentif'       => 0, 
                    'belum_menerima_insentif' => 0, 
                    'jumlah_pns'              => 0, 
                    'jumlah_pppk'             => 0, 
                    'jumlah_sertifikasi'      => 0, 
                    
                    // Standarisasi Berkas Awal
                    'ijop'                    => strtoupper($row['ijop'] ?? 'TIDAK ADA'),
                    'masa_berlaku_ijop'       => $masaBerlaku,
                    'status_ijop'             => 'Pending',
                    'status_skd'              => 'Pending', // [BARU] Status awal SKD
                    'status_super'            => 'Pending',
                    'status_skam'             => 'Pending',
                    'keterangan'              => strtoupper($row['keterangan'] ?? ''),


                ]);
            }
        });
    }
}