<?php

namespace Modules\Admin\resources\views\masterdata\rekomendator;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekomendatorMigrationExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'kdafiliator',
            'kdkategori',
            'nmafiliator',
            'alamatafiliator',
            'pekerjaan',
            'nohp',
            'norek',
            'bank',
            'email',
            'atasnama_rekening' // Ditambahkan tapi dikosongi
        ];
    }

    public function array(): array
    {
        return [
            [
                'REKOM001',          // kdafiliator
                '004REKOM',          // kdkategori
                'Budi Santoso',      // nmafiliator
                'Jl. Solo No. 123',  // alamatafiliator
                'Guru SMA',          // pekerjaan
                '081234567890',      // nohp
                '1234567890',        // norek
                'BCA',               // bank
                'budi@email.com',    // email
                ''                   // atasnama_rekening (dikosongkan)
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}