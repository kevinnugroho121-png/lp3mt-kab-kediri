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
        if ($this->request->filled('search')) {


            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
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
            'NAMA LEMBAGA', 
            'ALAMAT LEMBAGA',           // <-- 1. Kolom baru diselipkan di sini
            'JENIS LEMBAGA', 
            'ALAMAT GURU SESUAI KTP',   // <-- 2. Nama kolom disesuaikan
            'DESA', 
            'KEC', 
            'KAB', 
            'AGAMA', 
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

        // Gabungkan lagi Tempat dan Tanggal Lahir (Contoh: "KEDIRI, 16-06-2026")
        $tgl_lahir = $guru->tanggal_lahir ? \Carbon\Carbon::parse($guru->tanggal_lahir)->format('d-m-Y') : '';
        $ttl_gabungan = $guru->tempat_lahir . ($tgl_lahir ? ', ' . $tgl_lahir : '');

        // Susun alamat lembaga otomatis dari Desa & Kecamatan Lembaga
        $alamatLembagaOtomatis = $guru->lembaga 
            ? (($guru->lembaga->desa->nama_desa ?? '-') . ', KEC. ' . ($guru->lembaga->kecamatan->nama_kecamatan ?? '-'))
            : '-';

        return [
            $rowNumber,
            $guru->nama_lengkap,
            $ttl_gabungan,
            $guru->jenis_kelamin,
            "'" . $guru->nik, 
            $guru->lembaga->nama_lembaga ?? '-',
            $alamatLembagaOtomatis,         // <-- [SUDAH OTOMATIS TERISI DESA & KEC LEMBAGA]
            $guru->jenis_guru,
            $guru->alamat_ktp,             
            $guru->lembaga->desa->nama_desa ?? '-',
            $guru->lembaga->kecamatan->nama_kecamatan ?? '-',
            $guru->kabupaten ?? 'KEDIRI',
            $guru->agama,
            "'" . $guru->no_hp,
            $guru->nama_ibu_kandung,
            "'" . $guru->nomor_rekening,
            ($guru->penerima_insentif == 1) ? 'YA DIAJUKAN' : 'TIDAK'
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