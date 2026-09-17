<?php

namespace App\Exports;

use App\Models\Lembaga;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LembagaExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $user = Auth::user();
        // [SYNC] Eager load relasi gurus agar perhitungan guru akurat
        $query = Lembaga::with(['kecamatan', 'desa', 'gurus']);

        if ($user->role == 'korcam') {
            $query->where('kecamatan_id', $user->kecamatan_id);
        }

        if ($user->role != 'korcam' && $this->request->filled('filter_kecamatan')) {
            $query->where('kecamatan_id', $this->request->filter_kecamatan);
        }
        if ($this->request->filled('filter_desa')) {
            $query->where('desa_id', $this->request->filter_desa);
        }
        // Fleksibel menangkap parameter 'filter_jenis' atau 'filter_jenis_lembaga' dari web
        $jenisTarget = $this->request->filter_jenis ?? $this->request->filter_jenis_lembaga ?? null;
        if (!empty($jenisTarget)) {
            $query->where('jenis_lembaga', $jenisTarget);
        }
        // [SYNC] Filter Ormas (Termasuk penanganan kategori LAINNYA)
        if ($this->request->filled('filter_ormas')) {
            if (strtoupper($this->request->filter_ormas) === 'LAINNYA') {
                $query->whereNotIn('ormas', ['NU', 'MUHAMMADIYAH', 'LDII']);
            } else {
                $query->where('ormas', strtoupper($this->request->filter_ormas));
            }
        }

        // [SYNC] Filter Pencarian Nama / Kepala
        if ($this->request->filled('search')) {
            $query->where(function($q) {
                $q->where('nama_lembaga', 'like', '%' . $this->request->search . '%')
                  ->orWhere('kepala_lembaga', 'like', '%' . $this->request->search . '%');
            });
        }

        // [SYNC] Filter Status Berkas
        if ($this->request->filled('filter_berkas')) {
            $fb = $this->request->filter_berkas;
            if ($fb == 'kosong') {
                $query->where(function($q) {
                    $q->whereNull('file_ijop')->orWhereNull('file_super')->orWhereNull('file_skam');
                });
            } elseif ($fb == 'pending') {
                $query->where(function($q) {
                    $q->where('status_ijop', 'Pending')->orWhere('status_super', 'Pending')->orWhere('status_skam', 'Pending');
                });
            } elseif ($fb == 'ditolak') {
                $query->where(function($q) {
                    $q->where('status_ijop', 'Ditolak')->orWhere('status_super', 'Ditolak')->orWhere('status_skam', 'Ditolak');
                });
            } elseif ($fb == 'disetujui') {
                $query->whereNotNull('file_ijop')->whereNotNull('file_super')->whereNotNull('file_skam')
                      ->where('status_ijop', 'Disetujui')->where('status_super', 'Disetujui')->where('status_skam', 'Disetujui');
            }
        }

        return $query->orderBy('nama_lembaga');
    }

    // NAMA KOLOM SAMA PERSIS DENGAN TEMPLATE IMPORT LEMBAGA
    public function headings(): array
    {
        return [
            'NO.', 'NAMA LEMBAGA', 'JENIS LEMBAGA', 'ORMAS', 'KEC', 'DESA', 'LINK GOOGLE MAPS',
            'SANTRI (L)', 'SANTRI (P)', 'JUMLAH SANTRI', 'JUMLAH GURU', 'PENERIMA INSENTIF', 'BELUM MENERIMA',
            'IJOP', 'MASA BERLAKU IJOP', 'STATUS', 'KEPALA LEMBAGA', 'NO HP',
            'PNS', 'PPPK', 'SERTIFIKASI', 'KETERANGAN'
        ];
    }

    public function map($lembaga): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        // 1. Sanitasi No HP (Mencegah kutip ganda '' dan normalisasi ke format '08...)
        $rawHp = (string)($lembaga->no_telp ?? '');
        $cleanHp = str_ireplace('o', '0', $rawHp);
        $cleanHp = preg_replace('/[^0-9]/', '', $cleanHp);
        if (str_starts_with($cleanHp, '62')) {
            $cleanHp = '0' . substr($cleanHp, 2);
        } elseif (str_starts_with($cleanHp, '8')) {
            $cleanHp = '0' . $cleanHp;
        }
        $formattedHp = !empty($cleanHp) ? "'" . $cleanHp : '-';

        // 2. Perhitungan Real-Time Data Guru dari Relasi Database (Aman dari null PHP 8.1+)
        $gurus = $lembaga->gurus ?? collect();
        $totalGuru = $gurus->count();
        $pns = $gurus->where('status_kepegawaian', 'PNS')->count();
        $pppk = $gurus->filter(function($g) {
            $status = strtoupper((string)($g->status_kepegawaian ?? ''));
            return $status === 'PPPK' || $status === 'PPPK PARUH WAKTU';
        })->count();
        $sertifikasi = $gurus->filter(function($g) {
            $sertif = strtoupper((string)($g->status_sertifikasi ?? ''));
            return $sertif === 'SERTIFIKASI' || $sertif === 'INPASSING';
        })->count();

        $sesuaiKriteria = $gurus->filter(function($g) {
            $statusPegawai = strtoupper((string)($g->status_kepegawaian ?? ''));
            $statusSertifikasi = strtoupper((string)($g->status_sertifikasi ?? ''));
            return !in_array($statusPegawai, ['PNS', 'PPPK', 'PPPK PARUH WAKTU']) && $statusSertifikasi !== 'INPASSING';
        });

        $diajukan = $sesuaiKriteria->where('penerima_insentif', 1)->count();
        $tidakDiajukan = $sesuaiKriteria->count() - $diajukan;

        $santriL = (int)($lembaga->jumlah_santri_l ?? 0);
        $santriP = (int)($lembaga->jumlah_santri_p ?? 0);
        $totalSantri = ($lembaga->jumlah_santri > 0) ? $lembaga->jumlah_santri : ($santriL + $santriP);

        // Fallback: Jika data lama L & P masih 0 tapi total ada isinya, bagi 50:50 agar tidak tampil 0 di Excel
        if (($santriL + $santriP) === 0 && $totalSantri > 0) {
            $santriL = (int) ceil($totalSantri / 2);
            $santriP = (int) floor($totalSantri / 2);
        }

        // 🛡️ ANTI-CRASH: Tanggal Masa Berlaku IJOP (Aman dari karakter '-' atau format aneh)
        $tglIjopFormatted = '-';
        $rawTglIjop = trim((string)($lembaga->masa_berlaku_ijop ?? ''));
        if (!empty($rawTglIjop) && $rawTglIjop !== '-') {
            try {
                $tglIjopFormatted = \Carbon\Carbon::parse($rawTglIjop)->format('d-m-Y');
            } catch (\Exception $e) {
                $tglIjopFormatted = $rawTglIjop;
            }
        }

        return [
            $rowNumber,
            $lembaga->nama_lembaga,
            $lembaga->jenis_lembaga,
            $lembaga->ormas ?? '-',
            $lembaga->kecamatan->nama_kecamatan ?? '-',
            $lembaga->desa->nama_desa ?? '-',
            $lembaga->link_gmaps ?? '-',
            $santriL,
            $santriP,
            $totalSantri,
            $totalGuru,
            $diajukan,
            $tidakDiajukan,
            $lembaga->ijop ?? 'ADA',
            $tglIjopFormatted,
            $lembaga->status ?? 'AKTIF',
            $lembaga->kepala_lembaga ?? '-',
            $formattedHp,
            $pns,
            $pppk,
            $sertifikasi,
            $lembaga->keterangan ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '059669']]
            ],
        ];
    }
}