<?php

namespace Modules\Admin\Http\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportPembayaranUKTExcel implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Transaksi::with(['biodata', 'pendaftaran.batch', 'pendaftaran.jalur', 'pendaftaran.jurusanditerima'])
            ->where('kategori', 'ukt')
            ->orderBy('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Kode Pendaftaran', 'Nama', 'Batch', 'Jalur', 'Prodi Diterima',
            'Kode Transaksi', 'Nominal', 'Skema UKT', 'Status', 'Validator', 'Keterangan', 'Tanggal Upload',
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
            $row->pendaftaran->jurusanditerima->nama_jurusan ?? '-',
            $row->kode_transaksi,
            $row->jumlah,
            strtoupper($row->pendaftaran->skema_ukt ?? '-'),
            strtoupper($row->status),
            $row->validator_pembayaran ?? '-',
            $row->keterangan ?? '-',
            $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF56AB2F']]]];
    }
}
