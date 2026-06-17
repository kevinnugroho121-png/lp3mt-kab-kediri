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
        $query = Lembaga::with(['kecamatan', 'desa']);

        if ($user->role == 'korcam') {
            $query->where('kecamatan_id', $user->kecamatan_id);
        }

        if ($user->role != 'korcam' && $this->request->filled('filter_kecamatan')) {
            $query->where('kecamatan_id', $this->request->filter_kecamatan);
        }
        if ($this->request->filled('filter_desa')) {
            $query->where('desa_id', $this->request->filter_desa);
        }
        if ($this->request->filled('filter_jenis')) {
            $query->where('jenis_lembaga', $this->request->filter_jenis);
        }
        if ($this->request->filled('filter_ormas')) {
            $query->where('ormas', $this->request->filter_ormas);
        }

        return $query->orderBy('nama_lembaga');
    }

    // NAMA KOLOM SAMA PERSIS DENGAN TEMPLATE IMPORT LEMBAGA
    public function headings(): array
    {
        return [
            'NO.', 'NAMA LEMBAGA', 'JENIS LEMBAGA', 'ORMAS', 'KEC', 'DESA',
            'JUMLAH SANTRI', 'JUMLAH GURU', 'PENERIMA INSENTIF', 'BELUM MENERIMA',
            'IJOP', 'MASA BERLAKU IJOP', 'STATUS', 'KEPALA LEMBAGA', 'NO HP',
            'PNS', 'PPPK', 'SERTIFIKASI', 'KETERANGAN'
        ];
    }

    public function map($lembaga): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $lembaga->nama_lembaga,
            $lembaga->jenis_lembaga,
            $lembaga->ormas,
            $lembaga->kecamatan->nama_kecamatan ?? '-',
            $lembaga->desa->nama_desa ?? '-',
            $lembaga->jumlah_santri,
            $lembaga->jumlah_guru,
            $lembaga->penerima_insentif,
            $lembaga->belum_menerima_insentif,
            $lembaga->ijop,
            $lembaga->masa_berlaku_ijop ? \Carbon\Carbon::parse($lembaga->masa_berlaku_ijop)->format('d-m-Y') : '-',
            $lembaga->status,
            $lembaga->kepala_lembaga,
            "'" . $lembaga->no_telp,
            $lembaga->jumlah_pns,
            $lembaga->jumlah_pppk,
            $lembaga->jumlah_sertifikasi,
            $lembaga->keterangan,
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