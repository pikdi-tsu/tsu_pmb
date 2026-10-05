<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\MasterData\Master_JurusanKuliah;
use App\Models\MasterData\Master_Soal;
use App\Models\MasterData\Master_TarifUKT;
use App\Models\Parameter;
use App\Models\Transaksi;
use App\Models\TransaksiHistory;
use App\Models\User\Pendaftaran;
use Yajra\DataTables\DataTables;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TestAssesmentController extends Controller
{
    public function index()
    {
        // Menyesuaikan menu untuk Assesment
        $data = array(
            'title' => 'Hasil Assesment PMB',
            'menu'  => 'Hasil Assesment PMB',
        );
        return view('admin::hasilassesment.index', $data);
    }

    public function tabelTestPMB()
    {
        $data = Pendaftaran::where('isactive', 1)
            ->with(['biodata', 'batch', 'jalur', 'jenisbeasiswa', 'jurusan_acc' => function ($q) {
                $q->with('jenjang');
            }])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama', function ($d) {
                return $d->biodata ? $d->biodata->nama : '-';
            })
            ->addColumn('noreg', function ($d) {
                return $d->KodePendaftaran;
            })
            ->addColumn('batch', function ($d) {
                return $d->batch ? $d->batch->nama_batch : '-';
            })
            ->addColumn('jalur', function ($d) {
                return $d->jalur ? $d->jalur->jenis_pendaftaran : '-';
            })
            ->addColumn('beasiswa', function ($d) {
                return $d->jenisbeasiswa ? $d->jenisbeasiswa->jenis_beasiswa : '-';
            })
            ->addColumn('diterima', function ($d) {
                return $d->jurusan_diterima && $d->jurusan_acc ? $d->jurusan_acc->jenjang->jenjang . ' - ' . $d->jurusan_acc->jurusan : '-';
            })
            ->addColumn('validator', function ($d) {
                // Tangani jika nilai null
                $validasi = $d->validasi_test === null ? '0' : (string)$d->validasi_test;

                if ($validasi == '0') {
                    return '<span class="badge bg-warning">Waiting</span>';
                } else {
                    return '<span class="badge bg-success">' . namaku($d->nik_validasi_test) . '</span>';
                }
            })
            ->addColumn('status', function ($d) {
                // Hitung jumlah attempt yang sudah selesai (finished)
                $finishedTests = DB::table('pmb_assessment_attempts')
                    ->where('kodependaftaran', $d->KodePendaftaran)
                    ->where('status', 'finished')
                    ->count();

                // Hitung apakah sudah pernah mulai test sama sekali
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
            ->addColumn('hasil', function ($d) {
                $id = encrypt($d->KodePendaftaran);

                // Cek status pengerjaan test
                $finishedTests = DB::table('pmb_assessment_attempts')
                    ->where('kodependaftaran', $d->KodePendaftaran)
                    ->where('status', 'finished')
                    ->count();

                // Tangani jika nilai null, jadikan '0' (belum divalidasi)
                $validasi = $d->validasi_test === null ? '0' : (string)$d->validasi_test;

                $show = '<span class="badge bg-warning">Waiting</span>';

                if ($validasi == '0') {
                    if ($finishedTests >= 3) {
                        $show = '<a href="#" class="tidaklolos" data-id="' . $id . '"><i title="Tidak Lolos" class="fa fa-window-close fa-lg text-red"></i></a>
                                 <a href="#" class="lolos" data-id="' . $id . '"><i title="Lolos" class="fa fa-check-square fa-lg text-green"></i></a>';
                    } else {
                        $show = '<span class="badge bg-secondary">Menunggu Selesai</span>';
                    }
                } elseif ($validasi == '1') {
                    $show = '<span class="badge bg-success">Lolos Test</span>';
                } elseif ($validasi == '-1') {
                    $show = '<span class="badge bg-danger">Tidak Lolos Test</span> <a href="#" class="lolos" data-id="' . $id . '"><i title="Loloskan Peserta" class="fa fa-check-square fa-lg text-green"></i></a>';
                }
                return $show;
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->KodePendaftaran);
                $finishedTests = DB::table('pmb_assessment_attempts')
                    ->where('kodependaftaran', $d->KodePendaftaran)
                    ->where('status', 'finished')
                    ->count();
                $validasi = $d->validasi_test === null ? '0' : (string)$d->validasi_test;
                    $reset = '';
                // Tombol detail selalu muncul (atau bisa dibatasi if $totalAttempts > 0)
                $detail = '<a href="#" data-id="' . $id . '" class="btn_detail"><i title="Detail Test" class="fa fa-info-circle fa-lg"></i></a>';
                if ($finishedTests<3||$validasi == '0') {
                    $reset  = '<a href="#" data-id="' . $id . '" class="btn_reset ml-2"><i title="Reset Ujian" class="fa fa-sync-alt fa-lg text-warning"></i></a>';
                }
                return $detail . $reset;          
	    })
            ->rawColumns(['action', 'status', 'validator', 'hasil'])
            ->make(true);
    }

    public function showDetailTest($params)
    {
        $id = decrypt($params);
        $cek = Pendaftaran::where('KodePendaftaran', $id)->exists();

        if (!$cek) {
            return response()->json(['hasil' => 0], Response::HTTP_OK);
        }

        $daftar = Pendaftaran::where('KodePendaftaran', $id)
            ->with(['biodata', 'batch', 'jalur', 'prodi1.jenjang', 'prodi2.jenjang', 'prodi3.jenjang', 'jurusan_acc.jenjang'])
            ->first();

        // Ambil Data Attempts Assessment
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
                    'opt_most.urutan as most_urutan', // Ambil urutan angka untuk kolom P
                    'opt_least.label as least_label',
                    'opt_least.disc_tipe as least_disc',
                    'opt_least.urutan as least_urutan' // Ambil urutan angka untuk kolom K
                )
                ->orderBy('q.urutan', 'asc')
                ->get();

            // Ambil hasil JSON dari tabel result jika sudah dikalkulasi
            $result = DB::table('pmb_assessment_test_results')
                ->where('attempt_id', $attempt->id)
                ->first();

            $hasil_disc = null;
            if ($result && $result->hasil_json) {
                $hasil_disc = json_decode($result->hasil_json, true);
            }

            $attemptData[] = [
                'id' => $attempt->id,
                'tipe_test_nama' => $attempt->tipe_test_nama,
                'tipe_engine' => $attempt->tipe_engine,
                'mulai_at' => $attempt->mulai_at,
                'selesai_at' => $attempt->selesai_at,
                'status' => $attempt->status,
                'answers' => $answers,
                'hasil_disc' => $hasil_disc
            ];
        }

        $data['hasil'] = 1;
        $data['daftar'] = $daftar;
        $data['attempts'] = $attemptData;

        return response()->json($data, Response::HTTP_OK);
    }

   public function show_jurusan($params)
    {
        try {
            $id = decrypt($params);
            
            // Ambil pendaftar sekalian dengan relasi prodinya
            $mhs = Pendaftaran::where('KodePendaftaran', $id)
                ->with(['prodi1.jenjang', 'prodi2.jenjang', 'prodi3.jenjang'])
                ->first();

            if(!$mhs){
                return response()->json(['hasil' => 0], Response::HTTP_OK);
            }

            $jurusan = [];

            // PILIHAN 1
            if (is_object($mhs->pilihan1)) {
                // Jika sudah berupa objek, langsung masukkan
                $jurusan[] = $mhs->pilihan1;
            } elseif (!empty($mhs->pilihan1)) {
                // Jika berupa teks string ID, query ke database
                $j1 = Master_JurusanKuliah::where('KodeJurusan', $mhs->pilihan1)->with('jenjang')->first();
                if ($j1) $jurusan[] = $j1;
            }

            // PILIHAN 2
            if (is_object($mhs->pilihan2)) {
                $jurusan[] = $mhs->pilihan2;
            } elseif (!empty($mhs->pilihan2)) {
                $j2 = Master_JurusanKuliah::where('KodeJurusan', $mhs->pilihan2)->with('jenjang')->first();
                if ($j2) $jurusan[] = $j2;
            }

            // PILIHAN 3
            if (is_object($mhs->pilihan3)) {
                $jurusan[] = $mhs->pilihan3;
            } elseif (!empty($mhs->pilihan3)) {
                $j3 = Master_JurusanKuliah::where('KodeJurusan', $mhs->pilihan3)->with('jenjang')->first();
                if ($j3) $jurusan[] = $j3;
            }

            // BACKUP AMAN: Jika array masih kosong, ambil dari relasi prodi
            if (empty($jurusan)) {
                if ($mhs->prodi1) $jurusan[] = $mhs->prodi1;
                if ($mhs->prodi2) $jurusan[] = $mhs->prodi2;
                if ($mhs->prodi3) $jurusan[] = $mhs->prodi3;
            }

            return response()->json([
                'hasil' => 1,
                'jurusan' => $jurusan
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            // Menangkap error 500 agar website tidak hang
            return response()->json([
                'hasil' => 0,
                'message' => 'Terjadi kesalahan Sistem' 
            ], 500);
        }
    }

    public function reset_test(Request $request)
    {
        try {
            $kodedaftar = decrypt($request->kodedaftar);
            $jenis = $request->jenis;

            DB::beginTransaction();

            // 1. Cari attempt berdasarkan jenis test
            $query = DB::table('pmb_assessment_attempts as a')
                ->join('pmb_assessment_tipe_test as t', 'a.tipe_test_id', '=', 't.id')
                ->where('a.kodependaftaran', $kodedaftar)
                ->select('a.id');

            if ($jenis == 'tpa') {
                $query->where('t.tipe_engine', 'multiple_choice');
            } elseif ($jenis == 'hip') {
                $query->whereIn('t.tipe_engine', ['single_choice', 'likert', 'dual_scale']);
            } elseif ($jenis == 'disc') {
                $query->where('t.tipe_engine', 'disc');
            }

            $attempts = $query->pluck('id')->toArray();

            if (!empty($attempts)) {
                // 2. Hapus Jawaban
                DB::table('pmb_assessment_answers')->whereIn('attempt_id', $attempts)->delete();
                
                // 3. Hapus Hasil (JSON DISC jika ada)
                DB::table('pmb_assessment_test_results')->whereIn('attempt_id', $attempts)->delete();
                
                // 4. Hapus Riwayat Attempt agar bisa mulai dari awal
                DB::table('pmb_assessment_attempts')->whereIn('id', $attempts)->delete();
            }

            // 5. REVISI: Reset Validasi dan kembalikan Current Step ke 6
            Pendaftaran::where('KodePendaftaran', $kodedaftar)->update([
                'validasi_test' => '0',
                'jurusan_diterima' => null,
                'keterangan' => null,
                'current_step' => 6 // Peserta balik ke tahap sebelum validasi (pengerjaan ulang)
            ]);

            DB::commit();

            return response()->json([
                'title' => 'Berhasil',
                'message' => 'Data ujian berhasil direset.',
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'title' => 'Gagal',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    public function hasil_test(Request $post)
    {
        $id = decrypt($post->kodedaftar);
        DB::beginTransaction();
        $cek = Pendaftaran::where('KodePendaftaran', $id)->first();
        $ket = '';
        if($post->status_diterima=='1'){
            $jur = Master_JurusanKuliah::where('KodeJurusan',$post->jurusan_diterima)->with('jenjang')->first();
            $ket = 'Selamat Anda diterima di Jurusan : '.$jur->jenjang->jenjang.'-'.$jur->jurusan.'. Silahkan Selesaikan Step Selanjutnya.';
        }else{
            $ket = 'anda belum lolos seleksi,Silahkan Daftar kembali pada batch selanjutnya';
        }
        $data = array(
            'validasi_test' => $post->status_diterima,
            'nik_validasi_test' => session('session')->nip,
            'tgl_validasi_test' => now(),
            'jurusan_diterima' => $post->jurusan_diterima,
            'keterangan' => $ket,
            'current_step' =>  $post->status_diterima == '1' ? $cek->current_step + 1 : $cek->current_step,
            'stop_step' =>  $post->status_diterima == '1' ? null : $cek->current_step,
            'updated_at' => now()
        );

        $updt = Pendaftaran::where('KodePendaftaran', $id)->update($data);

        $t1 = 0;
        // $t2=0;
        if ($post->status_diterima == '1') {
            $kode = 'UKT-' . $id . '-' . date('YmdHis');
            $jurusan = Master_JurusanKuliah::where('KodeJurusan', $post->jurusan_diterima)->first();
            $biaya = Master_TarifUKT::where('idbatch', $cek->batch_daftar)->where('idjalur', $cek->jalur_daftar)->where('idjurusan', $jurusan->id)->first();
            $transaksi = Transaksi::insert([
                'user_id' => $cek->biodata_id,
                'kategori' => 'ukt',
                'id_referensi' => $id,
                'kode_transaksi' => $kode,
                'jumlah' => $biaya->biaya_ukt,
                'status' => $biaya->biaya_ukt == 0 ? 'paid' : 'pending',
                // 'keterangan' => $biaya->keterangan,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if (!$transaksi) {
                $t1++;
            }

            $cek2 = Transaksi::orderby('id', 'desc')->latest()->first();
            // Simpan history
            // $historyTransaksi = TransaksiHistory::insert([
            //     'transaksi_id' => $cek2->id,
            //     'status' => $cek2->status,
            //     'keterangan' => $biaya->biaya_ukt == 0 ? 'Gratis / Beasiswa' : 'Menunggu pembayaran',
            //     'created_at' => date('Y-m-d H:i:s')
            // ]);
            // if(!$historyTransaksi){
            //     $t2++;
            // }
            if ($biaya->biaya_ukt == 0) {
                $ceklagi = Pendaftaran::where('KodePendaftaran', $id)->first();
                Pendaftaran::where('KodePendaftaran', $id)->update([
                    'current_step' => $ceklagi->current_step + 2,
                    'updated_at' => now()
                ]);
            }
        }
         if($updt&&$t1==0){ //&&$t2==0
            DB::commit();
            $data['title'] = 'Berhasil';
            $data['message'] = 'Data Test Online Sudah divalidasi !';
            $data['status'] = 'success';
        }else{
            DB::rollback();
            $data['title'] = 'Gagal';
            $data['message'] = 'Data Test Online Gagal divalidasi !';
            $data['status'] = 'error';
        }
        return response()->json($data, Response::HTTP_OK);
        // Fungsi show_jurusan dan hasil_test() tetap sama persis seperti aslinya
        // (Silakan copy-paste dari method aslimu agar tidak mengubah logic transaksi UKT)
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

        $l1 = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0, 'star' => 0];
        $l2 = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0, 'star' => 0];

        foreach ($answers as $a) {
            $m = strtoupper($a->most_disc);
            $k = strtoupper($a->least_disc);
            if (isset($l1[$m])) $l1[$m]++;
            elseif ($m == '*') $l1['star']++;
            if (isset($l2[$k])) $l2[$k]++;
            elseif ($k == '*') $l2['star']++;
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
