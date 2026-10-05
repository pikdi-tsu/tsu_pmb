<?php

namespace Modules\Admin\Http\Exports;

use App\Models\User\Pendaftaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportNIMExcel implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Pendaftaran::with(['biodata', 'batch', 'jurusanditerima.Fakultas', 'jalur'])
            ->where('isactive', 1)
            ->where('current_step', '>=', 11)
            ->whereHas('biodata', fn($q) => $q->whereNotNull('nim')->where('nim', '!=', ''))
            ->orderBy('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No', 'NIM', 'Nama Lengkap', 'Kode Pendaftaran',
            'Batch', 'Fakultas', 'Program Studi', 'Jalur',
            'Tanggal Daftar',
        ];
    }

    protected static $no = 0;

    public function map($row): array
    {
        self::$no++;
        return [
            self::$no,
            $row->biodata->nim ?? '-',
            $row->biodata->nama ?? '-',
            $row->KodePendaftaran,
            $row->batch->nama_batch ?? '-',
            $row->jurusanditerima->Fakultas->nama_fakultas ?? '-',
            $row->jurusanditerima->nama_jurusan ?? '-',
            $row->jalur->jenis_pendaftaran ?? '-',
            $row->created_at ? $row->created_at->format('d/m/Y') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFC94B4B']]]];
    }
}
