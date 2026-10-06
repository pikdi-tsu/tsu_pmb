<?php

namespace Modules\Admin\resources\views\masterdata\rekomendator;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Export data Master Rekomendator.
 * Semua sel disimpan sebagai teks (StringValueBinder) agar No. HP & No. Rekening
 * tidak kehilangan angka 0 di depan atau berubah jadi format ilmiah (1,49E+14).
 */
class RekomendatorDataExport extends StringValueBinder implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithCustomValueBinder
{
    private int $rowNumber = 0;

    public function __construct(private Collection $rows)
    {
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Rekomendator',
            'Nama Rekomendator',
            'Kategori',
            'Pekerjaan',
            'Alamat',
            'No. HP',
            'Email',
            'Nama Bank',
            'No. Rekening',
            'Atas Nama Rekening',
            'Tanggal Daftar',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            ++$this->rowNumber,
            $row->kode_rekomendator,
            $row->nama_rekomendator,
            $row->nama_kategori ?? $row->kategori,
            $row->pekerjaan,
            $row->alamat,
            $row->no_hp,
            $row->email,
            $row->nama_bank,
            $row->no_rekening,
            $row->atasnama_rekening,
            $row->created_at ? date('Y-m-d H:i', strtotime($row->created_at)) : '-',
            $row->isactive == 1 ? 'Aktif' : 'Tidak Aktif',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = $sheet->getHighestColumn();
                // Header tetap terlihat saat scroll & bisa difilter langsung di Excel
                $sheet->freezePane('A2');
                $sheet->setAutoFilter('A1:' . $lastColumn . $sheet->getHighestRow());
                $sheet->getStyle('A1:' . $lastColumn . '1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFD9EAD3');
            },
        ];
    }
}
