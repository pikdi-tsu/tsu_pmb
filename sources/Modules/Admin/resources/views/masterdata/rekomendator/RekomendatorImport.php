<?php

namespace Modules\Admin\resources\views\masterdata\rekomendator;

use App\Models\MasterData\Master_Rekomendator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\Log;

// PERHATIKAN: Kita HAPUS "WithHeadingRow" dan GANTI dengan "WithStartRow"
class RekomendatorImport implements ToModel, WithStartRow, SkipsEmptyRows
{
    /**
     * @return int
     * Kita mulai baca dari baris ke-2 (baris 1 adalah header, kita lewati)
     */
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // Debugging: Lihat bentuk row yang diterima (sekarang berupa array [0, 1, 2, 3...])
        Log::info('Mencoba import baris index:', $row);

        // Pastikan baris ini setidaknya punya 3 kolom awal (A, B, C) untuk diproses
        if (!isset($row[0]) && !isset($row[1]) && !isset($row[2])) {
            return null;
        }

        // ==========================================
        // PENENTUAN: APAKAH INI FILE MIGRASI ATAU FILE INPUT BARU?
        // Kita cek dari struktur kolom pertama (Kolom A / index 0)
        // Jika Kolom A berisi teks yang cukup panjang (seperti 'REKOM001'), kemungkinan itu Migrasi.
        // ==========================================

        $kolomA = trim((string)$row[0]); // kdafiliator (Migrasi) ATAU kode_kategori (Input Baru)
        $kolomB = trim((string)$row[1]); // kdkategori (Migrasi) ATAU nama_rekomendator (Input Baru)
        $kolomC = trim((string)$row[2]); // nmafiliator (Migrasi) ATAU alamat (Input Baru)

        // Jika Kolom A kosong, abaikan
        if (empty($kolomA)) {
            Log::warning('Baris diabaikan karena Kolom A kosong.', $row);
            return null;
        }

        // 1. LOGIKA MIGRASI
        // Kita asumsikan ini file migrasi jika Kolom B (index 1) terlihat seperti kode kategori ('004REKOM')
        // dan Kolom C (index 2) ada isinya (karena di file Input Baru, Kolom B adalah Nama, dan Kolom A adalah kode_kategori)
        // Cara paling aman: Cek apakah jumlah kolom di file ini >= 9 (format migrasi punya 10 kolom)
        
        if (count($row) >= 9) {
            
            $kode = $kolomA;             // A: kdafiliator
            $kategori = $kolomB;         // B: kdkategori
            $nama = $kolomC;             // C: nmafiliator
            
            if(empty($nama)){
                $nama = 'Tanpa Nama';
            }

            // Cek Duplikat
            $cek = Master_Rekomendator::where('kode_rekomendator', $kode)->first();
            if ($cek) {
                Log::info("Skip Migrasi: $kode sudah ada.");
                return null;
            }

            return new Master_Rekomendator([
                'kode_rekomendator' => $kode,
                'kategori'          => $kategori,
                'nama_rekomendator' => $nama,
                'alamat'            => $row[3] ?? null, // D: alamatafiliator
                'pekerjaan'         => $row[4] ?? null, // E: pekerjaan
                'no_hp'             => $row[5] ?? null, // F: nohp
                'no_rekening'       => $row[6] ?? null, // G: norek
                'nama_bank'         => $row[7] ?? null, // H: bank
                'email'             => $row[8] ?? null, // I: email
                'atasnama_rekening' => $row[9] ?? null, // J: atasnama_rekening
                'created_at'        => date('Y-m-d H:i:s'),
                'created_by'        => session('session')->nip ?? 'System Import',
                'isactive'          => '1'
            ]);
        }
        
        // 2. LOGIKA INPUT BARU (File dengan kolom lebih sedikit, yaitu 9 kolom)
        else {
            
            $kategori = $kolomA; // A: kode_kategori
            $nama     = $kolomB; // B: nama_rekomendator

            if (empty($nama)) {
                 Log::warning('Baris input baru dilewati karena nama kosong.', $row);
                 return null;
            }

            $kodeRekomendator = $this->generateKodeRekomendator($kategori);

            return new Master_Rekomendator([
                'kode_rekomendator' => $kodeRekomendator,
                'kategori'          => $kategori,
                'nama_rekomendator' => $nama,
                'alamat'            => $row[2] ?? null, // C: alamat
                'pekerjaan'         => $row[3] ?? null, // D: pekerjaan
                'no_hp'             => $row[4] ?? null, // E: no_hp
                'email'             => $row[5] ?? null, // F: email
                'nama_bank'         => $row[6] ?? null, // G: nama_bank
                'no_rekening'       => $row[7] ?? null, // H: no_rekening
                'atasnama_rekening' => $row[8] ?? null, // I: atasnama_rekening
                'created_at'        => date('Y-m-d H:i:s'),
                'created_by'        => session('session')->nip ?? 'System Import',
                'isactive'          => '1'
            ]);
        }
    }

    private function generateKodeRekomendator($kodeKategori)
    {
        $kategori = DB::table('pmb_master_kategori_rekomendator')->where('kode_kategori', $kodeKategori)->first();
        if (!$kategori) return 'UNKNOWN0001';

        $prefix = $kategori->kode_kategori; 
        
        $lastData = Master_Rekomendator::where('kategori', $kodeKategori)
                        ->where('kode_rekomendator', 'like', $prefix . '%')
                        ->orderBy('kode_rekomendator', 'desc')
                        ->first();

        if (!$lastData || empty($lastData->kode_rekomendator)) {
            $newNumber = 1;
        } else {
            $lastKode = $lastData->kode_rekomendator;
            $lastNumber = (int) substr($lastKode, strlen($prefix));
            $newNumber = $lastNumber + 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}