<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\MasterData\Master_Akun;
use App\Models\MasterData\Master_Batch;
use App\Models\MasterData\Master_JurusanKuliah;
use App\Models\User\Pendaftaran;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        if (Session::has('tmp')) {
            Session::forget('tmp');
        }

        // ==========================================
        // 1. SUMMARY CARDS — PIPELINE FUNNEL
        // ==========================================
        $total            = Pendaftaran::where('isactive', 1)->count();
        $sudahBayarPMB    = Pendaftaran::where('isactive', 1)->where('bayar_pendaftaran', '1')->count();
        $berkasApproved   = Pendaftaran::where('isactive', 1)->where('validasi_berkas_khusus', '1')->count();
        $lulusTest        = Pendaftaran::where('isactive', 1)->whereNotNull('jurusan_diterima')->count();
        $sudahBayarUKT    = Pendaftaran::where('isactive', 1)->where('bayar_ukt', '1')->count();
        $sudahNIM         = Pendaftaran::where('isactive', 1)->whereHas('biodata', fn($q) => $q->whereNotNull('nim')->where('nim', '!=', ''))->count();
        $blmvalidasi      = Pendaftaran::where('isactive', 1)->where('konfirm_pendaftaran', '0')->count();

        // ==========================================
        // 2. GRAFIK DONUT — Per Jalur (Reguler vs Beasiswa)
        // ==========================================
        $perJalur = Pendaftaran::where('isactive', 1)
            ->with('jalur')
            ->select('jalur_daftar', DB::raw('COUNT(*) as total'))
            ->groupBy('jalur_daftar')
            ->get()
            ->map(fn($item) => [
                'label' => $item->jalur ? $item->jalur->jenis_pendaftaran : 'Tidak Diketahui',
                'total' => $item->total,
            ]);

        // ==========================================
        // 3. GRAFIK BAR — Top 5 Prodi Terfavorit (berdasarkan pilihan 1)
        // ==========================================
        $topProdi = Pendaftaran::where('isactive', 1)
            ->whereNotNull('pilihan1')
            ->where('pilihan1', '!=', '')
            ->select('pilihan1', DB::raw('COUNT(*) as total'))
            ->groupBy('pilihan1')
            ->orderByDesc('total')
            ->limit(5)
            ->with('prodi1.jenjang')
            ->get()
            ->map(function ($item) {
                $label = $item->pilihan1;
                if ($item->prodi1) {
                    $jenjang = $item->prodi1->jenjang ? $item->prodi1->jenjang->jenjang . ' ' : '';
                    $label = $jenjang . $item->prodi1->jurusan;
                }
                return [
                    'label' => $label,
                    'total' => (int) $item->total,
                ];
            });

        // ==========================================
        // 4. GRAFIK LINE — Tren Pendaftar 30 Hari Terakhir
        // ==========================================
        $trenHarian = Pendaftaran::where('isactive', 1)
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->select(DB::raw('DATE(created_at) as tgl'), DB::raw('COUNT(*) as total'))
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get()
            ->keyBy('tgl');

        // Buat array lengkap 30 hari (isi 0 jika tidak ada pendaftar)
        $trenLabels = [];
        $trenData   = [];
        for ($i = 29; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $trenLabels[] = now()->subDays($i)->format('d/m');
            $trenData[]   = $trenHarian->has($tgl) ? $trenHarian[$tgl]->total : 0;
        }

        // ==========================================
        // 5. REKAP BATCH AKTIF
        // ==========================================
        $batchAktif = Master_Batch::where('isactive', 1)->get()->map(function ($b) {
            $terisi = Pendaftaran::where('isactive', 1)->where('batch_daftar', $b->id)->count();
            $today  = now()->toDateString();
            if ($today < $b->tglmulai) {
                $statusBatch = 'akan_datang';
            } elseif ($today > $b->tglselesai) {
                $statusBatch = 'berakhir';
            } else {
                $statusBatch = 'aktif';
            }
            $sisaHari = now()->diffInDays($b->tglselesai, false);
            return [
                'nama'       => $b->nama_batch,
                'tglmulai'   => $b->tglmulai,
                'tglselesai' => $b->tglselesai,
                'kuota'      => $b->kuota,
                'terisi'     => $terisi,
                'status'     => $statusBatch,
                'sisa_hari'  => $sisaHari,
            ];
        });

        // Warning jika ada batch aktif yang akan berakhir ≤ 3 hari
        $batchHampirBerakhir = $batchAktif->filter(fn($b) => $b['status'] === 'aktif' && $b['sisa_hari'] >= 0 && $b['sisa_hari'] <= 3);

        $data = [
            'title'                => 'Dashboard',
            'menu'                 => 'dashboard',
            // Funnel
            'total'                => $total,
            'sudahBayarPMB'        => $sudahBayarPMB,
            'berkasApproved'       => $berkasApproved,
            'lulusTest'            => $lulusTest,
            'sudahBayarUKT'        => $sudahBayarUKT,
            'sudahNIM'             => $sudahNIM,
            'blmvalidasi'          => $blmvalidasi,
            // Charts
            'perJalurJson'         => $perJalur->toJson(),
            'topProdiJson'         => $topProdi->toJson(),
            'trenLabelsJson'       => json_encode($trenLabels),
            'trenDataJson'         => json_encode($trenData),
            // Batch
            'batchAktif'           => $batchAktif,
            'batchHampirBerakhir'  => $batchHampirBerakhir,
        ];

        return view('admin::dashboard/dashboard', $data);
    }
}
