<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FormatSantriExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return ['No', 'NAMA LENGKAP SANTRI', 'L/P', 'USIA', 'KELAS', 'KETERANGAN'];
    }

    public function array(): array
    {
        return []; // Dikosongkan agar murni menghasilkan blangko template
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'color'    => ['rgb' => 'FFFF00'] // Latar kuning terang seperti file contoh
                ]
            ],
        ];
    }
}