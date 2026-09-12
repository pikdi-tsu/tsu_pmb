<?php

namespace Modules\Admin\Http\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportPembayaranPMBExcel implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Transaksi::with(['biodata', 'pendaftaran.batch', 'pendaftaran.jalur'])
            ->where('kategori', 'pendaftaran')
            ->orderBy('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Kode Pendaftaran', 'Nama', 'Batch', 'Jalur',
            'Kode Transaksi', 'Nominal', 'Status', 'Validator', 'Keterangan', 'Tanggal Upload',
        ];
    }

    protected static $no = 0;

    public function map($row): array
    {
        self::$no++;
        return [
            self::$no,
            $row->id_referensi,
            $row->biodata->nama ?? '-',
            $row->pendaftaran->batch->nama_batch ?? '-',
            $row->pendaftaran->jalur->jenis_pendaftaran ?? '-',
            $row->kode_transaksi,
            $row->jumlah,
            strtoupper($row->status),
            $row->validator_pembayaran ?? '-',
            $row->keterangan ?? '-',
            $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF11998E']]]];
    }
}
