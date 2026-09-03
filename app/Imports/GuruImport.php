<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\Lembaga;
use App\Models\Kecamatan;
use App\Models\Desa;
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
        $processedNamaIbu = []; // [BARU] Cegah ganda Nama + Ibu Kandung di internal file Excel
        $processedHp = [];      // [BARU] Cegah No HP kembar di internal file Excel

        // ========================================================
        // LOOP 1: RADAR VALIDASI KETAT & CEK GANDA (REJECT-ALL)
        // ========================================================
        foreach ($rows as $index => $row) {
            $lineNumber = $index + 2;

            if (empty(array_filter($row->toArray()))) continue;

            // Bersihkan format input (Mendukung Template Excel Lama & Baru)
            $rawNik          = preg_replace('/[^0-9]/', '', (string)($row['nik'] ?? ''));
            $rawNamaGuru     = trim((string)($row['nama_lengkap_tanpa_gelar'] ?? $row['nama_lengkap'] ?? ''));
            $rawLembaga      = trim((string)($row['nama_lembaga_tempat_mengajar'] ?? $row['nama_lembaga'] ?? ''));
            $rawJenisLembaga = trim((string)($row['jenis_lembaga'] ?? ''));
            $rawRekening     = trim(str_replace(["'", '"', ' '], '', (string)($row['nomer_rekening'] ?? $row['nomor_rekening'] ?? '')));
            $rawKecGuru      = trim((string)($row['kec_guru'] ?? $row['kecamatan_guru'] ?? $row['kecamatan'] ?? $row['kec'] ?? $row['kec_1'] ?? '')); 
            $rawDesaGuru     = trim((string)($row['desa_guru'] ?? $row['desa'] ?? '')); 
            $rawIbuKandung   = trim((string)($row['nama_ibu_kandung'] ?? ''));
            $rawJk           = strtoupper(trim((string)($row['jenis_kelamin'] ?? $row['lp'] ?? $row['l_p'] ?? '')));
            $rawTtl          = trim((string)($row['tempat_tanggal_lahir'] ?? ''));
            
            // Otomatis menambal angka 0 jika di Excel diawali angka 8 atau ubah 62 jadi 0
            $rawHp           = preg_replace('/[^0-9]/', '', (string)($row['no_hp'] ?? ''));
            if (str_starts_with($rawHp, '62')) {
                $rawHp = '0' . substr($rawHp, 2);
            } elseif (str_starts_with($rawHp, '8')) {
                $rawHp = '0' . $rawHp;
            }

            // A. Validasi Kolom Wajib Dasar
            if (empty($rawNik)) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'NIK' wajib diisi.";
            } elseif (strlen($rawNik) !== 16) {
                $this->errors[] = "Baris Ke-{$lineNumber}: NIK '{$rawNik}' tidak valid (Harus 16 digit angka murni).";
            }
            if (empty($rawNamaGuru))   $this->errors[] = "Baris Ke-{$lineNumber}: Nama Guru kosong.";
            if (empty($rawLembaga))    $this->errors[] = "Baris Ke-{$lineNumber}: Nama Lembaga kosong.";
            if (empty($rawRekening))   $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'NOMER REKENING' wajib diisi.";
            if (empty($rawKecGuru))    $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'KEC GURU' (Kecamatan Rumah Guru) wajib diisi.";
            if (empty($rawDesaGuru))   $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'DESA GURU' (Desa Rumah Guru) wajib diisi.";
            if (empty($rawIbuKandung)) $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'NAMA IBU KANDUNG' wajib diisi.";
            if (empty($rawJk) || !in_array($rawJk, ['L', 'P'])) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Jenis Kelamin harus 'L' atau 'P'.";
            }

            // ========================================================
            // [BARU] SATPAM WILAYAH: CEK RELASI DESA & KECAMATAN GURU
            // ========================================================
            if (!empty($rawKecGuru) && !empty($rawDesaGuru)) {
                // Bersihkan imbuhan seperti 'Kec.' atau 'Desa' jika penginput menulisnya
                $cleanKec = trim(preg_replace('/^(KECAMATAN|KEC\.?)\s+/i', '', $rawKecGuru));
                $cleanDesa = trim(preg_replace('/^(DESA|DS\.?|KELURAHAN|KEL\.?)\s+/i', '', $rawDesaGuru));

                $kecamatanDb = Kecamatan::where('nama_kecamatan', 'LIKE', $cleanKec)->first();

                if (!$kecamatanDb) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: Kecamatan Guru '{$rawKecGuru}' tidak ditemukan di database Kabupaten Kediri.";
                } else {
                    $desaValid = Desa::where('kecamatan_id', $kecamatanDb->id)
                                     ->where('nama_desa', 'LIKE', $cleanDesa)
                                     ->exists();

                    if (!$desaValid) {
                        $this->errors[] = "Baris Ke-{$lineNumber}: Desa '{$rawDesaGuru}' BUKAN bagian dari Kecamatan {$kecamatanDb->nama_kecamatan}. Silakan periksa kembali.";
                    }
                }
            }

            // B. SATPAM VALIDASI DOMISILI KTP KAB. KEDIRI & NIK DUKCAPIL
            $rawKabGuru = strtoupper(trim((string)($row['kab_guru'] ?? $row['kabupaten_guru'] ?? $row['kabupaten'] ?? $row['kab'] ?? 'KEDIRI')));

            // 1. Satpam Domisili KTP: Wajib Warga Kabupaten Kediri
            if (!empty($rawKabGuru) && !str_contains($rawKabGuru, 'KEDIRI') && $rawKabGuru !== '-') {
                $this->errors[] = "Baris Ke-{$lineNumber}: Domisili KTP Guru '{$rawNamaGuru}' terdata di luar Kabupaten Kediri ({$rawKabGuru}). Guru wajib berdomisili di Kabupaten Kediri.";
            }

            if (strlen($rawNik) === 16) {
                // 2. Validasi Tanggal Lahir & Jenis Kelamin (Terbuka untuk NIK se-Indonesia)
                if (!empty($rawTtl) && in_array($rawJk, ['L', 'P'])) {
                    $tglDeteksi = null;

                    // Deteksi jika ada format tanggal di dalam input (dengan koma, spasi, atau tanggal murni)
                    if (str_contains($rawTtl, ',')) {
                        $parts = explode(',', $rawTtl, 2);
                        $tglDeteksi = trim($parts[1]);
                    } elseif (preg_match('/([\d]{1,2}[\-\/\.][\d]{1,2}[\-\/\.][\d]{2,4})/', $rawTtl, $matches)) {
                        $tglDeteksi = trim($matches[1]);
                    }

                    // Jika petugas menyertakan tanggal lahir, cek sinkronisasinya dengan NIK
                    if (!empty($tglDeteksi)) {
                        try {
                            $parsedDate = Carbon::parse($tglDeteksi);
                            $tgl = (int)$parsedDate->format('d');
                            $bln = $parsedDate->format('m');
                            $thn = $parsedDate->format('y');

                            $expectedTgl = ($rawJk === 'P') ? ($tgl + 40) : $tgl;
                            $expectedTglStr = str_pad($expectedTgl, 2, '0', STR_PAD_LEFT);
                            $expectedNikPattern = $expectedTglStr . $bln . $thn;

                            $nikDatePart = substr($rawNik, 6, 6);

                            if ($nikDatePart !== $expectedNikPattern) {
                                $this->errors[] = "Baris Ke-{$lineNumber}: ANOMALI NIK! NIK '{$rawNik}' tidak cocok dengan Tanggal Lahir di Excel (" . $parsedDate->format('d-m-Y') . ") & Jenis Kelamin ({$rawJk}). 6 digit tengah NIK seharusnya '{$expectedNikPattern}'.";
                            }
                        } catch (\Exception $e) {
                            $this->errors[] = "Baris Ke-{$lineNumber}: Format Tanggal Lahir pada '{$rawTtl}' tidak valid.";
                        }
                    }
                    // Catatan: Jika petugas HANYA menulis kota (cth: "KEDIRI" atau "BLITAR"), JANGAN ditolak karena tanggal akan di-generate otomatis dari NIK di Loop 2.
                }
            }

            // C. SATPAM ANTI-KECOLLONGAN MENU
            if (!empty($rawJenisLembaga)) {
                $jenisUpper = strtoupper($rawJenisLembaga);
                if ($jenisUpper !== $this->menuAktif) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: Salah Kamar! Lembaga di baris ini berjenis '{$jenisUpper}', TIDAK BOLEH di-import melalui Halaman Guru {$this->menuAktif}.";
                    continue;
                }
            } else {
                $this->errors[] = "Baris Ke-{$lineNumber}: Kolom 'JENIS LEMBAGA' di Excel wajib diisi.";
                continue;
            }

            if (empty($rawLembaga)) continue;

            // D. PENCARIAN LEMBAGA SECARA GLOBAL
            $lembaga = Lembaga::where('nama_lembaga', 'LIKE', '%' . $rawLembaga . '%')
                              ->where('jenis_lembaga', $this->menuAktif)
                              ->first();
                              
            if (!$lembaga) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Gagal! Lembaga '{$rawLembaga}' dengan jenis {$this->menuAktif} tidak ditemukan di database.";
                continue;
            }

            // FILTER WILAYAH KERJA KORCAM
            if ($this->user->role == 'korcam' && $lembaga->kecamatan_id != $this->user->kecamatan_id) {
                $this->errors[] = "Baris Ke-{$lineNumber}: Hak Akses Ditolak! Lembaga '{$rawLembaga}' berada di luar wilayah kerja Kecamatan Anda.";
            }

            // E. DETEKSI DUPLIKASI NIK
            if (!empty($rawNik)) {
                if (isset($processedNiks[$rawNik])) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: DUPLIKASI EXCEL! NIK {$rawNik} kembar dengan data di Baris Ke-" . $processedNiks[$rawNik];
                } else {
                    $processedNiks[$rawNik] = $lineNumber;
                }
                
                if (Guru::where('nik', $rawNik)->exists()) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: NIK {$rawNik} sudah terdaftar di database sistem.";
                }
            }

            // F. [BARU] DETEKSI DATA GANDA SILUMAN: KOMBINASI (NAMA GURU + IBU KANDUNG)
            if (!empty($rawNamaGuru) && !empty($rawIbuKandung)) {
                $keyIdentitas = strtoupper($rawNamaGuru) . '|' . strtoupper($rawIbuKandung);
                
                if (isset($processedNamaIbu[$keyIdentitas])) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: INDIKASI DATA GANDA! Guru '{$rawNamaGuru}' dengan Ibu Kandung '{$rawIbuKandung}' kembar dengan data di Baris Ke-" . $processedNamaIbu[$keyIdentitas];
                } else {
                    $processedNamaIbu[$keyIdentitas] = $lineNumber;
                }

                $duplikatDb = Guru::where('nama_lengkap', strtoupper($rawNamaGuru))
                                  ->where('nama_ibu_kandung', strtoupper($rawIbuKandung))
                                  ->first();
                if ($duplikatDb) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: GAGAL! Guru '{$rawNamaGuru}' dengan Ibu Kandung '{$rawIbuKandung}' sudah ada di database dengan NIK: {$duplikatDb->nik}.";
                }
            }

            // G. DETEKSI DUPLIKASI NOMOR REKENING
            if (!empty($rawRekening)) {
                if (isset($processedRekenings[$rawRekening])) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: REKENING GANDA DI EXCEL! Nomor Rekening '{$rawRekening}' sama dengan data di Baris Ke-" . $processedRekenings[$rawRekening];
                } else {
                    $processedRekenings[$rawRekening] = $lineNumber;
                }
                
                if (Guru::where('nomor_rekening', $rawRekening)->exists()) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: GAGAL! Nomor Rekening '{$rawRekening}' sudah terdaftar atas nama guru lain di database.";
                }
            }

            // H. [BARU] DETEKSI DUPLIKASI NO HP
            if (!empty($rawHp)) {
                if (isset($processedHp[$rawHp])) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: NO HP GANDA DI EXCEL! Nomor '{$rawHp}' sama dengan data di Baris Ke-" . $processedHp[$rawHp];
                } else {
                    $processedHp[$rawHp] = $lineNumber;
                }

                if (Guru::where('no_hp', $rawHp)->exists()) {
                    $this->errors[] = "Baris Ke-{$lineNumber}: GAGAL! Nomor HP '{$rawHp}' sudah digunakan oleh guru lain di database.";
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

                $namaLembagaTarget = trim($row['nama_lembaga_tempat_mengajar'] ?? $row['nama_lembaga'] ?? '');
                $lembaga = Lembaga::where('nama_lembaga', 'LIKE', '%' . $namaLembagaTarget . '%')
                                  ->where('jenis_lembaga', $this->menuAktif)
                                  ->first();

                // Update langsung ke tabel lembagas via DB Query
                $rawAlamatLembaga = trim($row['alamat_lembaga'] ?? '');
                if ($lembaga && !empty($rawAlamatLembaga) && $rawAlamatLembaga !== '-') {
                    DB::table('lembagas')
                        ->where('id', $lembaga->id)
                        ->update(['alamat' => strtoupper($rawAlamatLembaga)]);
                }

                // ========================================================
                // 🧠 PARSER CERDAS TEMPAT & TANGGAL LAHIR (SEMUA SKENARIO)
                // ========================================================
                $inputTtl = trim((string)($row['tempat_tanggal_lahir'] ?? ''));
                $cleanNik = preg_replace('/[^0-9]/', '', (string)($row['nik'] ?? ''));

                // 1. Ekstrak Tanggal Lahir Resmi Langsung dari NIK (Sumber Kebenaran Utama)
                $nikTanggalLahir = null;
                if (strlen($cleanNik) === 16) {
                    $tglRaw = (int)substr($cleanNik, 6, 2);
                    $bln = (int)substr($cleanNik, 8, 2);
                    $thn2D = (int)substr($cleanNik, 10, 2);

                    // Rumus Dukcapil: Kurangi 40 jika perempuan
                    $tglAsli = ($tglRaw > 40) ? ($tglRaw - 40) : $tglRaw;
                    $thnFull = ($thn2D <= (int)date('y')) ? (2000 + $thn2D) : (1900 + $thn2D);

                    if (checkdate($bln, $tglAsli, $thnFull)) {
                        $nikTanggalLahir = sprintf('%04d-%02d-%02d', $thnFull, $bln, $tglAsli);
                    }
                }

                // 2. Tentukan Tempat & Tanggal Lahir sesuai Skenario Input
                $tempatLahir = 'KEDIRI';
                $tanggalLahir = $nikTanggalLahir; // Default selalu menggunakan tanggal akurat dari NIK

                if (!empty($inputTtl) && $inputTtl !== '-') {
                    // Skenario A: Format Standar "KOTA, TANGGAL" (cth: BLITAR, 17-06-1985)
                    if (str_contains($inputTtl, ',')) {
                        $parts = explode(',', $inputTtl, 2);
                        $tempatLahir = trim($parts[0]) ?: 'KEDIRI';
                        $tglStr = trim($parts[1]);
                        if (!empty($tglStr)) {
                            try {
                                $tanggalLahir = Carbon::parse($tglStr)->format('Y-m-d');
                            } catch (\Exception $e) {}
                        }
                    }
                    // Skenario B: Format Tanpa Koma "KOTA TANGGAL" (cth: KEDIRI 18-12-1970 atau BLITAR 17-06-1985)
                    elseif (preg_match('/^([a-zA-Z\s\.\'\-]+)\s+([\d\-\/\.]+)$/', $inputTtl, $matches)) {
                        $tempatLahir = trim($matches[1]) ?: 'KEDIRI';
                        try {
                            $tanggalLahir = Carbon::parse(trim($matches[2]))->format('Y-m-d');
                        } catch (\Exception $e) {}
                    }
                    // Skenario C: Hanya Angka Tanggal Saja (cth: 18-12-1970)
                    elseif (preg_match('/^[\d\-\/\.]+$/', $inputTtl)) {
                        $tempatLahir = 'KEDIRI';
                        try {
                            $tanggalLahir = Carbon::parse($inputTtl)->format('Y-m-d');
                        } catch (\Exception $e) {}
                    }
                    // Skenario D: Hanya Nama Kota Saja (cth: KEDIRI / BLITAR / NGANJUK)
                    else {
                        $tempatLahir = $inputTtl;
                    }
                }

                // Pengaman Terakhir: Pastikan Tanggal Lahir tidak pernah kosong
                if (empty($tanggalLahir)) {
                    $tanggalLahir = $nikTanggalLahir;
                }

                $desaGuru = $row['desa_guru'] ?? $row['desa'] ?? '';
                $kecGuru  = $row['kec_guru'] ?? $row['kecamatan_guru'] ?? $row['kecamatan'] ?? $row['kec'] ?? $row['kec_1'] ?? '';
                $kabGuru  = $row['kab_guru'] ?? $row['kabupaten_guru'] ?? $row['kabupaten'] ?? $row['kab'] ?? 'KEDIRI';
                $rekGuru  = $row['nomer_rekening'] ?? $row['nomor_rekening'] ?? '';
                $jkGuru   = strtoupper(trim((string)($row['jenis_kelamin'] ?? $row['lp'] ?? $row['l_p'] ?? '')));

                // Baca Status Kepegawaian / Pekerjaan Utama jika ada
                $rawPekerjaan = strtoupper(trim((string)($row['pekerjaan_utama'] ?? $row['status_kepegawaian'] ?? $row['pekerjaan'] ?? '')));
                $statusPegawai = in_array($rawPekerjaan, ['PNS', 'PPPK']) ? $rawPekerjaan : 'NON-ASN';

                // Normalisasi No HP sebelum masuk tabel database
                $cleanHp = preg_replace('/[^0-9]/', '', (string)($row['no_hp'] ?? ''));
                if (str_starts_with($cleanHp, '62')) {
                    $cleanHp = '0' . substr($cleanHp, 2);
                } elseif (str_starts_with($cleanHp, '8')) {
                    $cleanHp = '0' . $cleanHp;
                }

                Guru::create([
                    'lembaga_id'         => $lembaga->id,
                    'jenis_guru'         => $this->menuAktif, 
                    'nama_lengkap'       => strtoupper($row['nama_lengkap_tanpa_gelar'] ?? $row['nama_lengkap'] ?? ''),
                    'nik'                => preg_replace('/[^0-9]/', '', (string)($row['nik'] ?? '')),
                    'tempat_lahir'       => strtoupper($tempatLahir),
                    'tanggal_lahir'      => $tanggalLahir, 
                    'jenis_kelamin'      => $jkGuru,
                    'nama_ibu_kandung'   => strtoupper($row['nama_ibu_kandung'] ?? ''),
                    'agama'              => strtoupper($row['agama'] ?? 'ISLAM'),
                    'pekerjaan_utama'    => $rawPekerjaan ?: 'GURU', // <--- Diselipkan di sini agar tersimpan ke DB
                    'status_kepegawaian' => $statusPegawai, 
                    'status_sertifikasi' => 'BELUM SERTIFIKASI',
                    'penerima_insentif'  => 0,
                    'alamat_ktp'         => strtoupper($row['alamat_guru_sesuai_ktp'] ?? $row['alamat_sesuai_ktp'] ?? ''),
                    'desa'               => strtoupper($desaGuru),
                    'kecamatan'          => strtoupper($kecGuru),
                    'kabupaten'          => strtoupper($kabGuru),
                    'no_hp'              => $cleanHp,
                    'nomor_rekening'     => trim(str_replace(["'", '"', ' '], '', (string)$rekGuru)),
                    'status_ktp'         => 'Pending',
                    'status_kk'          => 'Pending',
                    'status_bukurekening'=> 'Pending',
                ]);
            }
        });
    }
}