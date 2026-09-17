<?php

namespace App\Exports;

use App\Models\Guru;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuruExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $query = Guru::with(['lembaga.kecamatan', 'lembaga.desa']);
        
        $filterType = $this->request->type ?? 'ALL';

        if ($user->role == 'korcam') {
            $query->whereHas('lembaga', function($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            });
        }

        if ($filterType == 'INSENTIF') {
            $query->where('status_kepegawaian', 'NON-ASN');
        } elseif (in_array($filterType, ['MADIN', 'TPQ', 'PONPES'])) {
            $query->where('jenis_guru', $filterType);
        }

        if ($user->role != 'korcam' && $this->request->filled('filter_kecamatan')) {
            $query->whereHas('lembaga', function($q) {
                $q->where('kecamatan_id', $this->request->filter_kecamatan);
            });
        }
        if ($this->request->filled('filter_desa')) {
            $query->whereHas('lembaga', function($q) {
                $q->where('desa_id', $this->request->filter_desa);
            });
        }

        if ($this->request->filled('filter_lembaga')) {
            $query->where('lembaga_id', $this->request->filter_lembaga);
        }
        // [BARU - Poin 3] Filter status insentif pada Excel
        if ($this->request->filled('filter_insentif')) {
            $query->where('penerima_insentif', $this->request->filter_insentif);
        }

        // 🔍 SINKRONISASI FILTER BERKAS DENGAN WEB
        if ($this->request->filled('filter_berkas')) {
            $filterBerkas = $this->request->filter_berkas;
            if ($filterBerkas == 'kosong') {
                $query->where(function($q) {
                    $q->whereNull('file_ktp')->orWhereNull('file_kk')->orWhereNull('file_bukurekening');
                });
            } elseif ($filterBerkas == 'pending') {
                $query->where(function($q) {
                    $q->where('status_ktp', 'Pending')->orWhere('status_kk', 'Pending')->orWhere('status_bukurekening', 'Pending');
                });
            } elseif ($filterBerkas == 'ditolak') {
                $query->where(function($q) {
                    $q->where('status_ktp', 'Ditolak')->orWhere('status_kk', 'Ditolak')->orWhere('status_bukurekening', 'Ditolak');
                });
            } elseif ($filterBerkas == 'disetujui') {
                $query->whereNotNull('file_ktp')->whereNotNull('file_kk')->whereNotNull('file_bukurekening')
                      ->where('status_ktp', 'Disetujui')->where('status_kk', 'Disetujui')->where('status_bukurekening', 'Disetujui');
            }
        }

        // 🔍 SINKRONISASI PENCARIAN SPESIFIK PER KOLOM
        if ($this->request->filled('col_nama')) $query->where('nama_lengkap', 'like', '%' . $this->request->col_nama . '%');
        if ($this->request->filled('col_nik')) $query->where('nik', 'like', '%' . $this->request->col_nik . '%');
        if ($this->request->filled('col_status_pegawai')) $query->where('status_kepegawaian', 'like', '%' . $this->request->col_status_pegawai . '%');
        if ($this->request->filled('col_alamat')) $query->where('alamat_ktp', 'like', '%' . $this->request->col_alamat . '%');

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // 🔍 SINKRONISASI URUTAN (A-Z / Z-A)
        if ($this->request->filled('sort_col') && $this->request->filled('sort_dir')) {
            $allowedSorts = ['nama_lengkap', 'nik', 'status_kepegawaian', 'jenis_guru'];
            if (in_array($this->request->sort_col, $allowedSorts)) {
                return $query->orderBy($this->request->sort_col, $this->request->sort_dir);
            }
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA LENGKAP (tanpa gelar)',
            'TEMPAT TANGGAL LAHIR',
            'JENIS KELAMIN',
            'NIK',
            'NAMA LEMBAGA TEMPAT MENGAJAR',
            'JENIS LEMBAGA',
            'DESA LEMBAGA',
            'KECAMATAN LEMBAGA',
            'ALAMAT GURU SESUAI KTP',
            'DESA GURU',
            'KECAMATAN GURU',
            'KABUPATEN GURU',
            'AGAMA',
            'PEKERJAAN UTAMA',
            'NO HP',
            'NAMA IBU KANDUNG',
            'NOMER REKENING',
            'KETERANGAN'
        ];
    }

    public function map($guru): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        // Format Tempat & Tanggal Lahir
        $tgl_lahir = $guru->tanggal_lahir ? \Carbon\Carbon::parse($guru->tanggal_lahir)->format('d-m-Y') : '';
        $ttl_gabungan = trim(($guru->tempat_lahir ?? '') . ($tgl_lahir ? ', ' . $tgl_lahir : ''));

        // Normalisasi format No HP agar rapi awalan 08...
        $cleanHp = !empty($guru->no_hp) ? preg_replace('/[^0-9]/', '', (string)$guru->no_hp) : '';
        if (!empty($cleanHp)) {
            if (str_starts_with($cleanHp, '62')) {
                $cleanHp = '0' . substr($cleanHp, 2);
            } elseif (str_starts_with($cleanHp, '8')) {
                $cleanHp = '0' . $cleanHp;
            }
        }

        // Logika 3 Kategori Keterangan sesuai kriteria
        $keterangan = 'BELUM DIAJUKAN';
        if (in_array(strtoupper($guru->status_kepegawaian ?? ''), ['PNS', 'PPPK']) || strtoupper($guru->status_sertifikasi ?? '') == 'INPASSING') {
            $keterangan = 'TIDAK BISA DIAJUKAN';
        } elseif ($guru->penerima_insentif == 1) {
            $keterangan = 'DIAJUKAN';
        }

        return [
            $rowNumber,
            $guru->nama_lengkap,
            $ttl_gabungan,
            $guru->jenis_kelamin,
            !empty($guru->nik) ? "'" . $guru->nik : '-',
            $guru->lembaga->nama_lembaga ?? '-',
            $guru->jenis_guru ?? '-',
            $guru->lembaga->desa->nama_desa ?? '-',
            $guru->lembaga->kecamatan->nama_kecamatan ?? '-',
            $guru->alamat_ktp ?? '-',
            $guru->desa ?? '-',
            $guru->kecamatan ?? '-',
            $guru->kabupaten ?? 'KEDIRI',
            $guru->agama ?? 'ISLAM',
            $guru->pekerjaan_utama ?: ($guru->status_kepegawaian ?? 'GURU'),
            !empty($cleanHp) ? "'" . $cleanHp : '-',
            $guru->nama_ibu_kandung ?? '-',
            !empty($guru->nomor_rekening) ? "'" . $guru->nomor_rekening : '-',
            $keterangan
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