<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\MasterData\Master_JenisPendaftaran;
use App\Models\MasterData\Master_TarifUKT;
use App\Models\MasterData\Master_Rekomendator;
use App\Models\Parameter;
use App\Models\Transaksi;
use App\Models\TransaksiHistory;
use App\Models\User\Pendaftaran;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin\LogAktivitas;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Http\Exports\ExportPembayaranPMBExcel;

class PembayaranPMBController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Pembayaran PMB',
            'menu'  => 'Data Pembayaran PMB',
        );
        return view('admin::pembayaranPMB.index', $data);
    }

    public function tabelPembayaranPMB()
    {
        $data = Transaksi::with('biodata','pendaftaran')->where('kategori','pendaftaran')->get();
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('nama', function ($d) {
            // PENGAMAN: Cek apakah biodata ada
            return $d->biodata ? $d->biodata->nama : '-';
        })
        ->addColumn('noreg', function ($d) {
            return $d->id_referensi;
        })
        ->addColumn('kodetx', function ($d) {
            return $d->kode_transaksi;
        })
        ->addColumn('jenis', function ($d) {
            return $d->kategori;
        })
        ->addColumn('nominal', function ($d) {
            return rupiah($d->jumlah);
        })
        ->addColumn('status', function ($d) {
            $warna = 'warning';
            if($d->status=='paid'){
                $warna = 'success';
            }elseif($d->status=='pending'||$d->status=='waiting'){
                $warna = 'warning';
            }else{
                $warna = 'danger';
            }
            $show = '<span class="badge bg-'.$warna.'">'.$d->status.'</span>';
            return $show;
        })
        ->addColumn('keterangan', function ($d) {
            return $d->keterangan ? $d->keterangan : '-';
        })
        ->addColumn('approve', function ($d) {
            $id = encrypt($d->id_referensi);
            $approval = '';
            
            // PENGAMAN: Cek apakah pendaftaran ada sebelum dibaca
            if($d->pendaftaran && $d->pendaftaran->bayar_pendaftaran==0){
                $approval = '<a href="#" class="revisi-bayar" data-id="'.$id.'"><i title="Revisi Bukti Pembayaran" class="fa fa-window-close fa-lg text-red"></i></a>
                                    <a href="#" class="approve-bayar" data-id="'.$id.'"><i title="Approve" class="fa fa-check-square fa-lg text-green"></i></a>';
            }else{
                $validator = $d->validator_pembayaran ? namaku($d->validator_pembayaran) : 'Sistem';
                $approval = '<span class="badge bg-success">'.$validator.'</span>' ;
            }
            return $approval;
        })
        ->addColumn('action', function ($d) {
            $id = encrypt($d->id_referensi);

            $detail = '<a href="#" data-id="'.$id.'" class="btn_detail"><i title="Detail" class="fa fa-info-circle"></i></a>';
            $show = '';
            if($d->bukti_pembayaran){
                // PENGAMAN: Cek apakah parameter ada
                $params1 = Parameter::where('id',1)->first();
                if($params1){
                    //$linkkhusus = asset('sources/storage/app/'.$params1->bukti_bayar_pendaftaran.'/'.$d->bukti_pembayaran);
		      $linkkhusus = url('admin/file/'.strtoupper($params1->bukti_bayar_pendaftaran).'/'.$d->bukti_pembayaran);
                    $show = '<a href="'.$linkkhusus.'" target="_blank"><i title="Lihat Bukti Pendaftaran" class="fa fa-eye"></i></a>';
                }
            }

            return $detail.' '.$show;
        })

        ->rawColumns(['action','status','approve'])
        ->make(true);
    }

    public function showPayment($params)
    {
        $id = decrypt($params);
        $cek = Pendaftaran::where('KodePendaftaran',$id)
        ->select('biodata_id','KodePendaftaran')->first();
        
        $cek1 = Pendaftaran::where('KodePendaftaran',$id)->where('biodata_id',$cek->biodata_id)
        ->with(['biodata','batch','jalur'=>function($q){
            $q->with(['berkasumum'=>function($q){ $q->with('berkas'); },
                      'berkaskhusus'=>function($q){ $q->with('berkas'); }
            ]);
        },
        'jenisbeasiswa'=>function($q){ $q->with('tingkat'); },
        'jurusansekolah',
        'prodi1'=>function($q){ $q->with('jenjang'); },
        'prodi2'=>function($q){ $q->with('jenjang'); },
        'prodi3'=>function($q){ $q->with('jenjang'); }, 
        'waktukuliah','bayar'
        ])->first();

        $prodi1 = null;
        if($cek1->prodi1) {
            $prodi1 = Master_TarifUKT::where('idbatch',$cek1->batch_daftar)->where('idjalur',$cek1->jalur_daftar)->where('idjurusan',$cek1->prodi1->id)->where('isactive',1)->first();
        }

        $prodi2 = null;
        if($cek1->prodi2) {
            $prodi2 = Master_TarifUKT::where('idbatch',$cek1->batch_daftar)->where('idjalur',$cek1->jalur_daftar)->where('idjurusan',$cek1->prodi2->id)->where('isactive',1)->first();
        }

        $prodi3 = null;
        if($cek1->prodi3) {
            $prodi3 = Master_TarifUKT::where('idbatch',$cek1->batch_daftar)->where('idjalur',$cek1->jalur_daftar)->where('idjurusan',$cek1->prodi3->id)->where('isactive',1)->first();
        }

        $rekomendator_text = '-';
        if ($cek1 && $cek1->rekomendator) {
            $rek = Master_Rekomendator::where('kode_rekomendator', $cek1->rekomendator)->first();
            if ($rek) {
                // Format: Nama Rekomendator (Kode)
                $rekomendator_text = $rek->nama_rekomendator . ' (' . $rek->kode_rekomendator . ')';
            } else {
                $rekomendator_text = $cek1->rekomendator;
            }
        }

        if($cek1){
            $data['hasil'] = 1;
            $data['daftar'] = $cek1;
            $data['IdDaftar'] = $params;
            $data['ukt1'] = $prodi1;
            $data['ukt2'] = $prodi2;
            $data['ukt3'] = $prodi3; 
            $data['rekomendator'] = $rekomendator_text;
        }else{
            $data['hasil'] = 0;
            $data['daftar'] = $cek1;
            $data['IdDaftar'] = null;
            $data['ukt1'] = null;
            $data['ukt2'] = null;
            $data['ukt3'] = null;
            $data['rekomendator'] = '-';
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function approve($params)
    {
        $id = decrypt($params);
        $cek = Pendaftaran::where('KodePendaftaran',$id)->select('current_step','jalur_daftar')->first();
        $jalur = Master_JenisPendaftaran::where('id',$cek->jalur_daftar)->first();

        $step = $jalur->berkas_khusus==null ? $cek->current_step+3 : $cek->current_step+1;
        // dd($step);
        DB::beginTransaction();

        $update1 = Pendaftaran::where('KodePendaftaran',$id)->update([
            'bayar_pendaftaran' => '1',
            'current_step' => $step,
            'keterangan' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $update2 = Transaksi::where('id_referensi',$id)->update([
            'status' => 'paid',
            'validator_pembayaran' => session('session')->nip,
            'keterangan' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if($update1&&$update2){
            DB::commit();
            LogAktivitas::catat('Pembayaran PMB', 'Approve', $id, 'Bukti pembayaran PMB divalidasi');
            $data['status']  = true;
            $data['message'] = 'Bukti Pembayaran Berhasil di Validasi';
        }else{
            DB::rollback();
            $data['status']  = false;
            $data['message'] = 'Bukti Pembayaran Gagal di Validasi';
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function revisi(Request $post)
    {
        $id = decrypt($post->iddaftar);
        $ket = $post->keterangan;

        DB::beginTransaction();

        $update1 = Pendaftaran::where('KodePendaftaran',$id)->update([
            'keterangan' => $ket,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $update2 = Transaksi::where('id_referensi',$id)->update([
            'keterangan' => $ket,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if($update1&&$update2){
            DB::commit();
            LogAktivitas::catat('Pembayaran PMB', 'Revisi', $id, 'Keterangan: ' . $ket);
            $alert = ['title' => 'Information', 'message' => 'Berhasil Menambahkan Keterangan', 'status' => 'success'];
        }else{
            DB::rollback();
            $alert = ['title' => 'Information', 'message' => 'Gagal Menambahkan Keterangan', 'status' => 'error'];
        }
        return redirect()->back()->with('alert',$alert);

    }

    public function exportExcel()
    {
        $filename = 'Rekap_Pembayaran_PMB_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new ExportPembayaranPMBExcel(), $filename);
    }
}
