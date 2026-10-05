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
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Session, Crypt, DB;
use Symfony\Component\HttpFoundation\Response;

class TestPMBController extends Controller
{
    public function index()
    {
        $soal = Master_Soal::with('jawaban')->where('isactive',1)->get();
        $data = array(
            'title' => 'Test Online PMB',
            'menu'  => 'Test Online PMB',
            'soal' => $soal
        );
        return view('admin::testPMB.index', $data);
    }

    public function tabelTestPMB()
    {
        $data = Pendaftaran::where('isactive',1)
        ->with(['biodata','batch','jalur','jenisbeasiswa','jurusan_acc'=>function($q){
                    $q->with('jenjang');
                }
            ])
        ->get();
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('nama', function ($d) {
            return $d->biodata->nama;
        })
        ->addColumn('noreg', function ($d) {
            return $d->KodePendaftaran;
        })
        ->addColumn('batch', function ($d) {
            $nama = $d->batch->nama_batch;
            return $nama;
        })
        ->addColumn('jalur', function ($d) {
            $nama = $d->jalur->jenis_pendaftaran;
            return $nama;
        })
        ->addColumn('beasiswa', function ($d) {
            $nama = $d->jenisbeasiswa ? $d->jenisbeasiswa->jenis_beasiswa : '-';
            return $nama;
        })
        ->addColumn('tgl_test', function ($d) {
            $show = $d->tgl_test;
            if($show){
                $pisah = explode(' ',$show);
                $tgl = tglIndo($pisah[0]);
            }else{
                $tgl = '';
            }
            return $tgl;
        })
        ->addColumn('nilai', function ($d) {
            $show = $d->nilai_test;
            return $show;
        })
        ->addColumn('diterima', function ($d) {
            $show = $d->jurusan_diterima ? $d->jurusan_acc->jenjang->jenjang.' - '.$d->jurusan_acc->jurusan : '-';
            return $show;
        })
        ->addColumn('validator', function ($d) {
            $show = $d->validasi_test;
            $show2 = '<span class="badge bg-warning">Belum Test</span>';
            if($show==0){
                $show2 = '<span class="badge bg-warning">Waiting</span>';
            }else{
                $show2 = '<span class="badge bg-success">'.$d->nik_validasi_test.'-'.namaku($d->nik_validasi_test).'</span>';
            }
            return $show2;
        })
        ->addColumn('status', function ($d) {
            $show = '';
            if($d->status_test=='0'){
                $show = '<span class="badge bg-warning">Belum Test</span>';
            }else{
                $show = '<span class="badge bg-success">Sudah Test</span>';
            }
            return $show;
        })
        ->addColumn('hasil', function ($d) {
            $id = encrypt($d->KodePendaftaran);
            $show = '<span class="badge bg-warning">Waiting</span>';
            if($d->validasi_test=='0'){
                $show = '<a href="#" class="tidaklolos" data-id="'.$id.'"><i title="Tidak Lolos" class="fa fa-window-close fa-lg text-red"></i></a>
                                <a href="#" class="lolos" data-id="'.$id.'"><i title="Lolos" class="fa fa-check-square fa-lg text-green"></i></a>';
            }elseif($d->validasi_test=='1'){
                $show = '<span class="badge bg-success">Lolos Test</span>';
            }elseif($d->validasi_test=='-1'){
                $show = '<span class="badge bg-danger">Tidak Lolos Test</span>'.'<a href="#" class="lolos" data-id="'.$id.'"><i title="Loloskan Peserta" class="fa fa-check-square fa-lg text-green"></i></a>';
            }
            return $show;
        })
        ->addColumn('action', function ($d) {
            $id = encrypt($d->KodePendaftaran);

            $edit   = '';
            $aktif = '';
            $detail = '';
            if($d->status_test=='1'){
                // $edit   = '<a href="#" data-id="'.$id.'" class="btn-edit"><i title="Approval Berkas" class="fa fa-edit text-orange"></i></a>';
                $detail = '<a href="#" data-id="'.$id.'" class="btn_detail"><i title="Detail Test" class="fa fa-info-circle"></i></a>';
            }
            // if($d->isactive==1){
            //     $aktif = '<a href="#" class="btn_delete" data-id="'.$id.'" data-status="'.encrypt('0').'"><i title="Hapus" class="fa fa-trash text-red"></i></a>';
            // }else{
            //     $aktif  = '<a href="#" class="btn_delete" data-id="'.$id.'" data-status="'.encrypt('1').'"><i title="Aktifkan" class="fas fa-check-circle text-green"></i></a>';
            // }

            return $detail.' '.$edit;
        })
        ->rawColumns(['action','status','validator','hasil'])
        ->make(true);
    }

    public function showDetailTest($params)
    {
        $id = decrypt($params);
        $cek = Pendaftaran::where('KodePendaftaran',$id)->exists();
        // dd($cek);
        if(!$cek){
            $data['hasil'] = 0;
        }else{
            $daftar = Pendaftaran::where('KodePendaftaran',$id)->with(['biodata','batch','jalur',
                'prodi1'=>function($q){
                    $q->with('jenjang');
                },
                'prodi2'=>function($q){
                    $q->with('jenjang');
                },
                'prodi3'=>function($q){
                    $q->with('jenjang');
                },
                'jurusan_acc'=>function($q){
                    $q->with('jenjang');
                },
                'jawaban_peserta'])
            ->first();

            $data['hasil'] = 1;
            $data['daftar'] = $daftar;
        }

        return response()->json($data, Response::HTTP_OK);
    }

    public function show_jurusan($params)
    {
        // dd($params);
        $id = decrypt($params);
        $cek = Pendaftaran::where('KodePendaftaran',$id)->exists();
        if(!$cek){
            $data['hasil'] = 0;
        }else{
            $mhs = Pendaftaran::where('KodePendaftaran',$id)->first();
            $jurusan = array();
            // $jurusan = Master_JurusanKuliah::where('KodeJurusan',$mhs->pilihan1)->orwhere('KodeJurusan',$mhs->pilihan2)->with('jenjang')->get();
           // Pilihan 1
            if ($mhs->pilihan1 != null) {
                $jurusan1 = Master_JurusanKuliah::where('KodeJurusan',$mhs->pilihan1)->with('jenjang')->first();
                if ($jurusan1) array_push($jurusan, $jurusan1);
            }
            
            // Pilihan 2
            if ($mhs->pilihan2 != null) {
                $jurusan2 = Master_JurusanKuliah::where('KodeJurusan',$mhs->pilihan2)->with('jenjang')->first();
                if ($jurusan2) array_push($jurusan, $jurusan2);
            }

            // Pilihan 3 
            if ($mhs->pilihan3 != null) {
                $jurusan3 = Master_JurusanKuliah::where('KodeJurusan',$mhs->pilihan3)->with('jenjang')->first();
                if ($jurusan3) array_push($jurusan, $jurusan3);
            }
            $data['hasil'] = 1;
            $data['jurusan'] = $jurusan;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function hasil_test(Request $post)
    {
        $id = decrypt($post->kodedaftar);
        DB::beginTransaction();
        $cek = Pendaftaran::where('KodePendaftaran',$id)->first();
        $ket = '';
        if($post->status_diterima=='1'){
            $jur = Master_JurusanKuliah::where('KodeJurusan',$post->jurusan_diterima)->with('jenjang')->first();
            $ket = 'Selamat Anda diterima di Jurusan : '.$jur->jenjang->jenjang.'-'.$jur->jurusan.'. Silahkan Selesaikan Step Selanjutnya.';
        }else{
            $ket = 'Mohon maaf anda belum lolos seleksi.';
        }
        $data = array(
            'validasi_test' => $post->status_diterima,
            'nik_validasi_test' => session('session')->nip,
            'tgl_validasi_test' => now(),
            'jurusan_diterima' => $post->jurusan_diterima,
            'keterangan' => $ket,
            'current_step' =>  $post->status_diterima=='1' ? $cek->current_step+1 : $cek->current_step,
            'stop_step' =>  $post->status_diterima=='1' ? null : $cek->current_step,
            'updated_at' => now()
        );

        $updt = Pendaftaran::where('KodePendaftaran',$id)->update($data);

        $t1=0;
        // $t2=0;
        if($post->status_diterima=='1'){
            $kode = 'UKT-'.$id.'-'.date('YmdHis');
            $jurusan = Master_JurusanKuliah::where('KodeJurusan',$post->jurusan_diterima)->first();
            $biaya = Master_TarifUKT::where('idbatch',$cek->batch_daftar)->where('idjalur',$cek->jalur_daftar)->where('idjurusan',$jurusan->id)->first();
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

            if(!$transaksi){
                $t1++;
            }

            $cek2 = Transaksi::orderby('id','desc')->latest()->first();
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
            if($biaya->biaya_ukt==0){
                $ceklagi = Pendaftaran::where('KodePendaftaran',$id)->first();
                Pendaftaran::where('KodePendaftaran',$id)->update([
                    'current_step' => $ceklagi->current_step+2,
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
    }
}
