<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Wajib dipanggil karena berada di luar root namespace
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User\Pendaftaran;
use App\Models\User\Biodata;
use App\Models\MasterData\Master_Batch;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\LogAktivitas;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Http\Exports\ExportNIMExcel;

class NimController extends Controller
{
    /**
     * Menampilkan halaman Generate NIM dan Data Table
     */
    public function index(Request $request)
    {
        // Variabel untuk Layout/Header
        $title = 'Generate NIM Mahasiswa';
        $menu = 'Generate NIM';

        // 1. Ambil data tahun akademik unik untuk dropdown filter Tahun
        $tahun_list = Master_Batch::select('tahun_akademik')
            ->distinct()
            ->orderBy('tahun_akademik', 'desc')
            ->pluck('tahun_akademik');
        
        // 2. Tangkap parameter filter dari URL
        $tahun_selected = $request->tahun;
        $batch_selected = $request->batch_id;

        $batch_list = [];
        $mahasiswa = [];

        // 3. Jika tahun dipilih, munculkan list batch di tahun tersebut
        if ($tahun_selected) {
            $batch_list = Master_Batch::where('tahun_akademik', $tahun_selected)->get();
        }

        // 4. Jika batch dipilih, munculkan data mahasiswa yang lolos (current_step = 11)
        if ($batch_selected) {
            $mahasiswa = Pendaftaran::with([
                'biodata', 
                'batch', 
                'jurusan_acc.Fakultas', 
                'jalur'
            ])
            ->where('current_step', 11)
            ->where('batch_daftar', $batch_selected)
            ->get();
        }

        // Jangan lupa tambahkan 'title' dan 'menu' ke dalam compact
        return view('admin::generateNim.index', compact(
            'tahun_list', 'tahun_selected', 'batch_list', 'batch_selected', 'mahasiswa', 'title', 'menu'
        ));
    }

    /**
     * Proses Generate NIM ke Database
     */
    public function generate(Request $request)
    {
        // Validasi Input
        if (!$request->batch_id) {
            return response()->json([
                'status' => false, 
                'message' => 'Batch ID tidak ditemukan!'
            ], 400);
        }

        $batch_id = $request->batch_id;

        // Ambil data mahasiswa (Tahap 11 & Belum punya NIM)
        $pendaftarans = Pendaftaran::with(['biodata', 'batch', 'jurusan_acc.Fakultas', 'jalur'])
            ->where('current_step', 11)
            ->where('batch_daftar', $batch_id)
            ->whereHas('biodata', function ($query) {
                $query->whereNull('nim')->orWhere('nim', '');
            })
            ->get();

        if ($pendaftarans->isEmpty()) {
            return response()->json([
                'status' => false, 
                'message' => 'Semua mahasiswa di batch ini sudah memiliki NIM / Data kosong.'
            ]);
        }

        DB::beginTransaction();
        try {
            $nik_admin = Auth::user()->nik ?? session('nik');
            $sequence_tracker = [];

            foreach ($pendaftarans as $p) {
                // Ambil nama mahasiswa untuk pesan error (jika kosong pakai no pendaftaran)
                $nama_mhs = $p->biodata->nama ?? ('No. Reg: ' . $p->no_pendaftaran);

                // ==========================================
                // 1. PENGECEKAN DATA KOSONG PER BARIS
                // ==========================================
                if (!$p->batch) {
                    throw new \Exception("VALIDASI_DATA|Data Batch Akademik kosong untuk mahasiswa atas nama: **$nama_mhs**");
                }
                if (!$p->jurusan_acc) {
                    throw new \Exception("VALIDASI_DATA|Prodi Diterima belum diset untuk mahasiswa atas nama: **$nama_mhs**");
                }
                if (!$p->jurusan_acc->Fakultas) {
                    throw new \Exception("VALIDASI_DATA|Relasi Fakultas pada Prodi (" . $p->jurusan_acc->nama_jurusan . ") terputus untuk mahasiswa atas nama: **$nama_mhs**");
                }
                if (!$p->jalur) {
                    throw new \Exception("VALIDASI_DATA|Jalur Pendaftaran kosong untuk mahasiswa atas nama: **$nama_mhs**");
                }

                // ==========================================
                // 2. LOGIC GENERATE NIM
                // ==========================================
                $tahun_akademik = $p->batch->tahun_akademik ?? '0000';
                $tahun_awal = explode('/', $tahun_akademik)[0]; 
                $part1_tahun = substr($tahun_awal, -3); 
                
                $part2_fakultas = $p->jurusan_acc->Fakultas->format_nim ?? 'X';
                $part3_prodi = str_pad($p->jurusan_acc->format_nim ?? '0', 3, '0', STR_PAD_LEFT);
                $part4_jalur = $p->jalur->format_nim ?? '0';

                $prefix_nim = $part1_tahun . $part2_fakultas . $part3_prodi . $part4_jalur;

                if (!isset($sequence_tracker[$part1_tahun])) {
                    $last_biodata = Biodata::where('nim', 'like', $part1_tahun . '%')
                        ->whereRaw('LENGTH(nim) = 12')
                        ->orderByRaw('CAST(RIGHT(nim, 4) AS UNSIGNED) DESC')
                        ->lockForUpdate()
                        ->first();

                    if ($last_biodata && !empty($last_biodata->nim)) {
                        $sequence_tracker[$part1_tahun] = (int) substr($last_biodata->nim, -4);
                    } else {
                        $sequence_tracker[$part1_tahun] = 0;
                    }
                }

                $sequence_tracker[$part1_tahun]++;
                $new_sequence = $sequence_tracker[$part1_tahun];

                $part5_urut = str_pad($new_sequence, 4, '0', STR_PAD_LEFT);
                $final_nim = $prefix_nim . $part5_urut;

                // 3. SIMPAN
                $p->biodata->nim = $final_nim;
                $p->biodata->validator_nik = $nik_admin; 
                $p->biodata->save();
            }

            DB::commit();
            LogAktivitas::catat('Generate NIM', 'Generate', 'Batch-' . $batch_id, 'NIM di-generate untuk ' . $pendaftarans->count() . ' mahasiswa');
            return response()->json([
                'status' => true, 
                'message' => 'Berhasil men-generate NIM untuk ' . $pendaftarans->count() . ' mahasiswa.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();

            // Cek apakah ini error dari validasi yang kita buat (ada kata 'VALIDASI_DATA|')
            if (strpos($errorMessage, 'VALIDASI_DATA|') !== false) {
                // Pecah pesan untuk mengambil teks aslinya
                $pesanAman = explode('|', $errorMessage)[1];
                
                return response()->json([
                    'status' => false, 
                    'message' => 'Proses Dibatalkan! ' . $pesanAman . '. Silakan lengkapi data tersebut terlebih dahulu.'
                ], 422); // 422 Unprocessable Entity
            }

            // Jika error lain (SQL Error asli), catat di log dan munculkan pesan standar
            Log::error('Gagal Generate NIM: ' . $errorMessage); 
            return response()->json([
                'status' => false, 
                'message' => 'Terjadi kesalahan sistem saat proses generate. Silakan hubungi Tim IT.'
            ], 500); 
        }
    }

    public function exportExcel()
    {
        $filename = 'Rekap_NIM_Mahasiswa_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new ExportNIMExcel(), $filename);
    }
}