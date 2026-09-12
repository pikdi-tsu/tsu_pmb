<?php

namespace Modules\Admin\Http\Exports;

use App\Models\User\Pendaftaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportPendaftarExcel implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Pendaftaran::with(['biodata', 'batch', 'jalur', 'prodi1', 'prodi2', 'jenisbeasiswa'])
            ->where('isactive', 1);

        if (!empty($this->filters['batch_id'])) {
            $query->where('batch_daftar', $this->filters['batch_id']);
        }
        if (!empty($this->filters['jalur_id'])) {
            $query->where('jalur_daftar', $this->filters['jalur_id']);
        }

        return $query->orderBy('created_at')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Pendaftaran',
            'Nama Lengkap',
            'No. KTP/NIK',
            'Batch / Gelombang',
            'Jalur Pendaftaran',
            'Prodi Pilihan 1',
            'Prodi Pilihan 2',
            'Jenis Beasiswa',
            'Bayar PMB',
            'Validasi Berkas',
            'Jurusan Diterima',
            'Bayar UKT',
            'NIM',
            'Tanggal Daftar',
        ];
    }

    protected static $no = 0;

    public function map($row): array
    {
        self::$no++;
        return [
            self::$no,
            $row->KodePendaftaran,
            $row->biodata->nama ?? '-',
            $row->biodata->nik ?? '-',
            $row->batch->nama_batch ?? '-',
            $row->jalur->jenis_pendaftaran ?? '-',
            $row->prodi1->nama_jurusan ?? '-',
            $row->prodi2->nama_jurusan ?? '-',
            $row->jenisbeasiswa->jenis_beasiswa ?? '-',
            $row->bayar_pendaftaran == '1' ? 'Sudah' : 'Belum',
            $row->validasi_berkas_khusus == '1' ? 'Disetujui' : ($row->validasi_berkas_khusus == '-1' ? 'Ditolak' : 'Menunggu'),
            $row->jurusanditerima->nama_jurusan ?? '-',
            $row->bayar_ukt == '1' ? 'Sudah' : 'Belum',
            $row->biodata->nim ?? '-',
            $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF3A7BD5']]],
        ];
    }
}
