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

    // Menerima request filter dari Controller
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $user = Auth::user();
        $query = Guru::with(['lembaga.kecamatan', 'lembaga.desa']);
        
        $filterType = $this->request->type ?? 'ALL';

        // Filter Wilayah Korcam
        if ($user->role == 'korcam') {
            $query->whereHas('lembaga', function($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            });
        }

        // Filter Jenis / Menu
        if ($filterType == 'INSENTIF') {
            $query->where('status_kepegawaian', 'NON-ASN');
        } elseif (in_array($filterType, ['MADIN', 'TPQ', 'PONPES'])) {
            $query->where('jenis_guru', $filterType);
        }

        // Filter Pencarian dari Form
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
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        return $query->latest();
    }

    // Menentukan Judul Kolom di Excel
    public function headings(): array
    {
        return [
            'NO', 'NAMA LENGKAP', 'NIK', 'JENIS KELAMIN', 'TEMPAT LAHIR', 'TANGGAL LAHIR',
            'NAMA LEMBAGA', 'JENIS LEMBAGA', 'KECAMATAN', 'DESA',
            'STATUS KEPEGAWAIAN', 'STATUS SERTIFIKASI', 'PENERIMA INSENTIF',
            'NO HP', 'NOMOR REKENING'
        ];
    }

    // Memetakan Data ke dalam Kolom Excel
    public function map($guru): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $guru->nama_lengkap,
            "'" . $guru->nik, // Tanda petik agar NIK tidak jadi rumus eksponen di Excel
            $guru->jenis_kelamin,
            $guru->tempat_lahir,
            \Carbon\Carbon::parse($guru->tanggal_lahir)->format('d-m-Y'),
            $guru->lembaga->nama_lembaga ?? '-',
            $guru->jenis_guru,
            $guru->lembaga->kecamatan->nama_kecamatan ?? '-',
            $guru->lembaga->desa->nama_desa ?? '-',
            $guru->status_kepegawaian,
            $guru->status_sertifikasi,
            $guru->penerima_insentif ? 'YA' : 'TIDAK',
            "'" . $guru->no_hp,
            "'" . $guru->nomor_rekening,
        ];
    }

    // Mempercantik Tampilan Header Excel (Warna Hijau LP3MT)
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