<?php

namespace Modules\Admin\resources\views\masterdata\rekomendator;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\DB;

class RekomendatorTemplateExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new RekomendatorInputSheet(),
            new KategoriReferenceSheet()
        ];
    }
}

// ==========================================
// KELAS UNTUK SHEET 1 (INPUT DATA)
// ==========================================
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekomendatorInputSheet implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    public function headings(): array
    {
        return [
            'kode_kategori', // DIUBAH MENJADI kode_kategori
            'nama_rekomendator',
            'alamat',
            'pekerjaan',
            'no_hp',
            'email',
            'nama_bank',
            'no_rekening',
            'atasnama_rekening'
        ];
    }

    public function array(): array
    {
        return [
            [
                'DOSEN', // Contoh kode (Bukan angka lagi)
                'Budi Santoso',
                'Jl. Pendidikan No. 123, Surakarta',
                'Guru SMA',
                '081234567890',
                'budi.santoso@email.com',
                'BCA',
                '1234567890',
                'Budi Santoso'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [ 1 => ['font' => ['bold' => true]] ];
    }

    public function title(): string { return 'Input Data Rekomendator'; }
}

// ==========================================
// KELAS UNTUK SHEET 2 (REFERENSI KATEGORI)
// ==========================================
class KategoriReferenceSheet implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    public function headings(): array
    {
        return [
            ['PETUNJUK PENGISIAN:'],
            ['Isikan teks pada kolom "kode_kategori" di Sheet pertama sesuai dengan daftar di bawah ini.'],
            [''],
            ['Kode Kategori', 'Nama Kategori'] // Disesuaikan dengan kode
        ];
    }

    public function array(): array
    {
        // Ambil data langsung dari database
        $kategoriList = DB::table('pmb_master_kategori_rekomendator')
                            ->where('isactive', '1')
                            ->select('kode_kategori', 'kategori_rekomendator') // Ambil kode_kategori
                            ->get();

        $data = [];
        foreach ($kategoriList as $kat) {
            $data[] = [
                $kat->kode_kategori,
                $kat->kategori_rekomendator
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FF0000']]], 
            2 => ['font' => ['italic' => true]],
            4 => ['font' => ['bold' => true]], 
        ];
    }

    public function title(): string { return 'Referensi Kategori (BACA)'; }
}