<?php

namespace Modules\Assessment\Http\Controllers;

use App\Models\User\Pendaftaran;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class MonitoringAssesmentController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Monitoring Assessment PMB',
            'menu'  => 'Monitoring Assessment PMB',
        );
        return view('assessment::monitoringassesment.index', $data);
    }

    public function tabelMonitoring()
    {
        // Tambahkan relasi batch dan jalur
        $data = Pendaftaran::where('isactive', 1)
            ->with(['biodata', 'batch', 'jalur']) 
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama', function ($d) {
                return $d->biodata ? $d->biodata->nama : '-';
            })
            ->addColumn('noreg', function ($d) {
                return $d->KodePendaftaran;
            })
            // Tambahkan 2 kolom baru ini
            ->addColumn('batch', function ($d) {
                return $d->batch ? $d->batch->nama_batch . ' ' . $d->batch->tahun_akademik : '-';
            })
            ->addColumn('jalur', function ($d) {
                return $d->jalur ? $d->jalur->jenis_pendaftaran : '-';
            })
            ->addColumn('tes_tpa', function ($d) {
                return $this->getTestStatus($d->KodePendaftaran, 'multiple_choice'); 
            })
            ->addColumn('tes_hip', function ($d) {
                return $this->getTestStatus($d->KodePendaftaran, 'single_choice'); 
            })
            ->addColumn('tes_disc', function ($d) {
                return $this->getTestStatus($d->KodePendaftaran, 'disc'); 
            })
            ->addColumn('status', function ($d) {
                $finishedTests = DB::table('pmb_assessment_attempts')
                    ->where('kodependaftaran', $d->KodePendaftaran)
                    ->where('status', 'finished')
                    ->count();
                
                $totalAttempts = DB::table('pmb_assessment_attempts')
                    ->where('kodependaftaran', $d->KodePendaftaran)
                    ->count();

                if ($totalAttempts == 0) {
                    return '<span class="badge bg-danger">Belum Test</span>';
                } elseif ($finishedTests < 3) {
                    return '<span class="badge bg-warning text-dark">On Progress</span>';
                } else {
                    return '<span class="badge bg-success">Selesai Test</span>';
                }
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->KodePendaftaran);
                return '<a href="#" data-id="'.$id.'" class="btn_detail"><i title="Detail Test" class="fa fa-info-circle fa-lg text-info"></i></a>';
            })
            ->rawColumns(['action','status','tes_tpa','tes_hip','tes_disc'])
            ->make(true);
    }

    // Fungsi helper untuk mendapatkan status per tipe test
    private function getTestStatus($kodedaftar, $tipeEngine)
    {
        $attempt = DB::table('pmb_assessment_attempts as a')
            ->join('pmb_assessment_tipe_test as t', 'a.tipe_test_id', '=', 't.id')
            ->where('a.kodependaftaran', $kodedaftar)
            ->where('t.tipe_engine', $tipeEngine)
            ->select('a.status')
            ->first();

        if (!$attempt) {
            return '<span class="badge bg-danger">Belum Test</span>';
        } elseif ($attempt->status == 'finished') {
            return '<span class="badge bg-success">Selesai Test</span>';
        } else {
            return '<span class="badge bg-warning text-dark">On Progress</span>';
        }
    }

   public function showDetailTest($params)
    {
        $id = decrypt($params);
        $cek = Pendaftaran::where('KodePendaftaran', $id)->exists();

        if(!$cek){
            return response()->json(['hasil' => 0], Response::HTTP_OK);
        }

        $daftar = Pendaftaran::where('KodePendaftaran', $id)
            ->with(['biodata', 'batch', 'jalur', 'prodi1.jenjang', 'prodi2.jenjang', 'prodi3.jenjang'])
            ->first();

        $attempts = DB::table('pmb_assessment_attempts as a')
            ->join('pmb_assessment_tipe_test as t', 'a.tipe_test_id', '=', 't.id')
            ->join('pmb_assessment_engine_test as e', 't.tipe_engine', '=', 'e.tipe_engine') 
            ->where('a.kodependaftaran', $id)
            ->select('a.*', 't.nama_test as tipe_test_nama', 'e.tipe_engine')
            ->orderBy('a.id', 'asc')
            ->get();

        $attemptData = [];
        
        foreach ($attempts as $attempt) {
            $answers = DB::table('pmb_assessment_answers as ans')
                ->join('pmb_assessment_questions as q', 'ans.question_id', '=', 'q.id')
                ->leftJoin('pmb_assessment_question_options as opt', 'ans.option_id', '=', 'opt.id')
                ->leftJoin('pmb_assessment_question_options as opt_most', 'ans.most_option_id', '=', 'opt_most.id')
                ->leftJoin('pmb_assessment_question_options as opt_least', 'ans.least_option_id', '=', 'opt_least.id')
                ->where('ans.attempt_id', $attempt->id)
                ->select(
                    'q.urutan',
                    'q.pertanyaan', 
                    'ans.jawaban_1',
                    'ans.jawaban_2',
                    'opt.label as option_label',
                    'opt.is_benar',
                    'opt_most.label as most_label',
                    'opt_most.disc_tipe as most_disc',
                    'opt_most.urutan as most_urutan', // <-- MENGAMBIL ANGKA 1-4 UNTUK KOLOM P
                    'opt_least.label as least_label',
                    'opt_least.disc_tipe as least_disc',
                    'opt_least.urutan as least_urutan' // <-- MENGAMBIL ANGKA 1-4 UNTUK KOLOM K
                )
                ->orderBy('q.urutan', 'asc')
                ->get();

            $attemptData[] = [
                'id' => $attempt->id,
                'tipe_test_nama' => $attempt->tipe_test_nama,
                'tipe_engine' => $attempt->tipe_engine,
                'mulai_at' => $attempt->mulai_at,
                'selesai_at' => $attempt->selesai_at,
                'status' => $attempt->status,
                'answers' => $answers,
            ];
        }

        $data['hasil'] = 1;
        $data['daftar'] = $daftar;
        $data['attempts'] = $attemptData; 

        return response()->json($data, Response::HTTP_OK);
    }
    public function printDisc($attempt_id)
    {
        $attempt = DB::table('pmb_assessment_attempts')->where('id', $attempt_id)->first();
        if (!$attempt) return abort(404, 'Data Assessment tidak ditemukan.');

        $daftar = Pendaftaran::where('KodePendaftaran', $attempt->kodependaftaran)
            ->with(['biodata', 'batch', 'jalur'])->first();

        // Ambil Jawaban untuk hitung Rekap Line 1, 2, 3
        $answers = DB::table('pmb_assessment_answers as ans')
            ->leftJoin('pmb_assessment_question_options as opt_most', 'ans.most_option_id', '=', 'opt_most.id')
            ->leftJoin('pmb_assessment_question_options as opt_least', 'ans.least_option_id', '=', 'opt_least.id')
            ->where('ans.attempt_id', $attempt_id)
            ->select('opt_most.disc_tipe as most_disc', 'opt_least.disc_tipe as least_disc')
            ->get();

        $l1 = ['D'=>0, 'I'=>0, 'S'=>0, 'C'=>0, 'star'=>0];
        $l2 = ['D'=>0, 'I'=>0, 'S'=>0, 'C'=>0, 'star'=>0];

        foreach($answers as $a) {
            $m = strtoupper($a->most_disc);
            $k = strtoupper($a->least_disc);
            if(isset($l1[$m])) $l1[$m]++; elseif($m == '*') $l1['star']++;
            if(isset($l2[$k])) $l2[$k]++; elseif($k == '*') $l2['star']++;
        }

        $l3 = [
            'D' => $l1['D'] - $l2['D'],
            'I' => $l1['I'] - $l2['I'],
            'S' => $l1['S'] - $l2['S'],
            'C' => $l1['C'] - $l2['C'],
        ];

        // Ambil Hasil JSON Deskripsi
        $result = DB::table('pmb_assessment_test_results')->where('attempt_id', $attempt_id)->first();
        $hasil = $result && $result->hasil_json ? json_decode($result->hasil_json, true) : null;

        $data = [
            'nama'  => $daftar->biodata ? $daftar->biodata->nama : '-',
            'noreg' => $daftar->KodePendaftaran,
            'batch' => $daftar->batch ? $daftar->batch->nama_batch . ' ' . $daftar->batch->tahun_akademik : '-',
            'jalur' => $daftar->jalur ? $daftar->jalur->jenis_pendaftaran : '-',
            'l1'    => $l1,
            'l2'    => $l2,
            'l3'    => $l3,
            'hasil' => $hasil 
        ];

        // Sesuaikan path view-nya dengan folder modul kamu
        return view('admin::hasilassesment.pdf_disc', $data); 
    }
}