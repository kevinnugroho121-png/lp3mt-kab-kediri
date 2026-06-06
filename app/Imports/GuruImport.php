<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\Lembaga;
use App\Models\Kecamatan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class GuruImport implements ToCollection, WithHeadingRow
{
    protected $user;
    protected $menuAktif; // Tempat menyimpan status menu asal (MADIN/TPQ/PONPES)
    public $errors = [];

    // Mengambil suntikan data dari Controller
    public function __construct($user, $menuAktif)
    {
        $this->user = $user;
        $this->menuAktif = strtoupper($menuAktif);
    }

    public function collection(Collection $rows)
    {
        $processedNiks = [];
        $processedRekenings = [];

        // ========================================================
        // LOOP 1: RADAR VALIDASI KETAT & CEK GANDA (REJECT-ALL)
        // ========================================================
        foreach ($rows as $index => $row) {
            $lineNumber = $index + 2;

            if (empty(array_filter($row->toArray()))) continue;

            $rawNik          = $row['nik'] ?? null;
            $rawNamaGuru     = $row['nama_lengkap_tanpa_gelar'] ?? null;
            $rawLembaga      = $row['nama_lembaga'] ?? null;
            $rawJenisLembaga = $row['jenis_lembaga'] ?? null;
            $rawRekening     = $row['nomer_rekening'] ?? null;
            $rawKecGuru      = $row['kec'] ?? null; 
            $rawDesaGuru     = $row['desa'] ?? null; 

            // A. Validasi Kolom Wajib Dasar
            if (empty(trim($rawNik))) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'NIK' wajib diisi.";
            } elseif (strlen(trim($rawNik)) !== 16) {
                $this->errors[] = "Baris Ke-{$lineNumber}: NIK '{$rawNik}' tidak valid (Harus 16 digit).";
            }
            if (empty(trim($rawNamaGuru))) $this->errors[] = "Baris Ke-{$lineNumber}: Nama Guru kosong.";
            if (empty(trim($rawLembaga))) $this->errors[] = "Baris Ke-{$lineNumber}: Nama Lembaga kosong.";
            if (empty(trim($rawRekening))) $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'NOMER REKENING' wajib diisi.";
            if (empty(trim($rawKecGuru))) $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'KEC' (Kecamatan Rumah Guru) wajib diisi.";
            if (empty(trim($rawDesaGuru))) $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'DESA' (Desa Rumah Guru) wajib diisi.";

            // B. SATPAM ANTI-KECOLLONGAN MENU (Validasi Jenis Lembaga Excel vs Halaman Web Aktif)
            if (!empty(trim($rawJenisLembaga))) {
                $jenisUpper = strtoupper(trim($rawJenisLembaga));
                if ($jenisUpper !== $this->menuAktif) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: Salah Kamar! Lembaga di baris ini berjenis '{$jenisUpper}', TIDAK BOLEH di-import melalui Halaman Guru {$this->menuAktif}.";
                    continue; // Skip pengecekan lanjutan untuk baris yang salah kamar
                }
            } else {
                $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'JENIS LEMBAGA' di Excel wajib diisi.";
                continue;
            }

            if (empty(trim($rawLembaga))) continue;

            // C. PENCARIAN LEMBAGA SECARA GLOBAL BERDASARKAN JENISNYA
            $lembaga = Lembaga::where('nama_lembaga', 'LIKE', '%' . trim($rawLembaga) . '%')
                              ->where('jenis_lembaga', $this->menuAktif)
                              ->first();
                              
            if (!$lembaga) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Gagal! Lembaga '{$rawLembaga}' dengan jenis {$this->menuAktif} tidak ditemukan di database aplikasi. Pastikan nama lembaga terdaftar dan tipenya sesuai.";
                continue;
            }

            // FILTER WILAYAH KERJA KORCAM
            if ($this->user->role == 'korcam' && $lembaga->kecamatan_id != $this->user->kecamatan_id) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Hak Akses Ditolak! Lembaga tempat mengajar ('{$rawLembaga}') berada di luar wilayah kerja Kecamatan Anda.";
            }

            // D. Validasi Duplikasi NIK
            if (!empty(trim($rawNik))) {
                if (isset($processedNiks[$rawNik])) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: DUPLIKASI EXCEL! NIK {$rawNik} kembar dengan data di Baris Ke-" . $processedNiks[$rawNik];
                } else {
                    $processedNiks[$rawNik] = $lineNumber;
                }
                
                if (Guru::where('nik', $rawNik)->exists()) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: NIK {$rawNik} sudah terdaftar di database sistem.";
                }
            }

            // E. Validasi Duplikasi Nomor Rekening
            if (!empty(trim($rawRekening))) {
                $cleanRek = trim($rawRekening);
                if (isset($processedRekenings[$cleanRek])) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: REKENING GANDA DI EXCEL! Nomor Rekening '{$cleanRek}' sama dengan data di Baris Ke-" . $processedRekenings[$cleanRek];
                } else {
                    $processedRekenings[$cleanRek] = $lineNumber;
                }
                
                if (Guru::where('nomor_rekening', $cleanRek)->exists()) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: GAGAL! Nomor Rekening '{$cleanRek}' sudah terdaftar atas nama guru lain.";
                }
            }
        }

        // JIKA ADA ERROR -> REJECT ALL
        if (!empty($this->errors)) {
            throw new \Exception("excel_validation_failed");
        }

        // ========================================================
        // LOOP 2: EKSEKUSI DATABASE (Jalan Hanya Jika 100% Lolos)
        // ========================================================
        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                if (empty(array_filter($row->toArray()))) continue;

                $lembaga = Lembaga::where('nama_lembaga', 'LIKE', '%' . trim($row['nama_lembaga']) . '%')
                                  ->where('jenis_lembaga', $this->menuAktif)
                                  ->first();

                $ttl = explode(',', $row['tempat_tanggal_lahir']);
                $tempatLahir = trim($ttl[0]);
                $tanggalLahir = null;
                if (isset($ttl[1])) {
                    try {
                        $tanggalLahir = Carbon::parse(trim($ttl[1]))->format('Y-m-d');
                    } catch (\Exception $e) {}
                }

                Guru::create([
                    'lembaga_id'         => $lembaga->id,
                    'jenis_guru'         => $this->menuAktif, 
                    'nama_lengkap'       => strtoupper($row['nama_lengkap_tanpa_gelar']),
                    'nik'                => trim($row['nik']),
                    'tempat_lahir'       => strtoupper($tempatLahir),
                    'tanggal_lahir'      => $tanggalLahir, 
                    'jenis_kelamin'      => strtoupper($row['jenis_kelamin']),
                    'nama_ibu_kandung'   => strtoupper($row['nama_ibu_kandung']),
                    'agama'              => strtoupper($row['agama']),
                    'status_kepegawaian' => 'NON-ASN', 
                    'status_sertifikasi' => 'BELUM SERTIFIKASI',
                    'penerima_insentif'  => 0, // <--- [FIXED] Default menjadi 0 (Standby / Abu-abu)
                    'alamat_ktp'         => strtoupper($row['alamat_sesuai_ktp']),


                    'desa'               => strtoupper($row['desa']),
                    'kecamatan'          => strtoupper($row['kec_1'] ?? $row['kecamatan_guru'] ?? $row['kec'] ?? ''),
                    'kabupaten'          => strtoupper($row['kab'] ?? 'KEDIRI'),
                    'no_hp'              => $row['no_hp'] ?? null,
                    'nomor_rekening'     => trim($row['nomer_rekening']),
                    'status_ktp'         => 'Pending',
                    'status_kk'          => 'Pending',
                    'status_bukurekening'=> 'Pending',
                ]);
            }
        });
    }
}