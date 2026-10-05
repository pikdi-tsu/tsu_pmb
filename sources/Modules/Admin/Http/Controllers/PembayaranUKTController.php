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
use Modules\Admin\Http\Exports\ExportPembayaranUKTExcel;

class PembayaranUKTController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Pembayaran UKT',
            'menu'  => 'Data Pembayaran UKT',
        );
        return view('admin::pembayaranUKT.index', $data);
    }

    public function tabelPembayaranUKT()
    {
        $data = Transaksi::with('biodata','pendaftaran')->where('kategori','ukt')->get();
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('nama', function ($d) {
            return $d->biodata->nama;
        })
        ->addColumn('noreg', function ($d) {
            return $d->id_referensi;
        })
        ->addColumn('kodetx', function ($d) {
            $nama = $d->kode_transaksi;
            return $nama;
        })
        ->addColumn('jenis', function ($d) {
            $nama = $d->kategori;
            return $nama;
        })
        ->addColumn('nominal', function ($d) {
            $nama = rupiah($d->jumlah);
            return $nama;
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
            if($d->status=='paid' && $d->pendaftaran->skema_ukt) {
                $show .= '<br><small class="text-muted text-uppercase">'. $d->pendaftaran->skema_ukt .'</small>';
            }
            return $show;
        })
        ->addColumn('keterangan', function ($d) {
            $ket = '-';
            if($d->keterangan){
                $ket = $d->keterangan;
            }
            return $ket;
        })
        ->addColumn('approve', function ($d) {
            $id = encrypt($d->id_referensi);
            $approval = '';
            if($d->pendaftaran->bayar_ukt==0){
                $approval = '<a href="#" class="revisi-bayar" data-id="'.$id.'"><i title="Revisi Bukti Pembayaran" class="fa fa-window-close fa-lg text-red"></i></a>
                                    <a href="#" class="approve-bayar" data-id="'.$id.'"><i title="Approve" class="fa fa-check-square fa-lg text-green"></i></a>';
            }else{
		$nama = $d->validator_pembayaran==null ? '-' : namaku($d->validator_pembayaran);
                $approval = '<span class="badge bg-success">'.$nama.'</span>' ;
            }
            return $approval;
        })
        ->addColumn('action', function ($d) {
            $id = encrypt($d->id_referensi);

            $detail = '<a href="#" data-id="'.$id.'" class="btn_detail"><i title="Detail" class="fa fa-info-circle"></i></a>';
            $show = '';
            if($d->bukti_pembayaran){
                $params1 = Parameter::where('id',1)->first();
                //$linkkhusus = asset('sources/storage/app/'.$params1->bukti_bayar_ukt.'/'.$d->bukti_pembayaran);
		  $linkkhusus = url('admin/file/'.strtoupper($params1->bukti_bayar_ukt).'/'.$d->bukti_pembayaran);
                $show = '<a href="'.$linkkhusus.'" target="_blank"><i title="Lihat Bukti Pendaftaran" class="fa fa-eye"></i></a>';
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
        ->with(['biodata','batch','jalur',
        'jenisbeasiswa'=>function($q){
            $q->with('tingkat');
        },
        'jurusansekolah',
        'prodi1'=>function($q){
            $q->with('jenjang');
        },
        'prodi2'=>function($q){
            $q->with('jenjang');
        },
        'prodi3'=>function($q){ 
            $q->with('jenjang');
        },
        'waktukuliah','bayar'
        ])->first();

        // PENGAMAN PENCARIAN NOMINAL UKT (Mencegah Fatal Error jika prodi kosong)
        $prodi1 = null;
        if($cek1 && $cek1->prodi1){
            $prodi1 = Master_TarifUKT::where('idbatch',$cek1->batch_daftar)->where('idjalur',$cek1->jalur_daftar)->where('idjurusan',$cek1->prodi1->id)->where('isactive',1)->first();
        }
        
        $prodi2 = null;
        if($cek1 && $cek1->prodi2){
            $prodi2 = Master_TarifUKT::where('idbatch',$cek1->batch_daftar)->where('idjalur',$cek1->jalur_daftar)->where('idjurusan',$cek1->prodi2->id)->where('isactive',1)->first();
        }

        $prodi3 = null;
        if($cek1 && $cek1->prodi3){
            $prodi3 = Master_TarifUKT::where('idbatch',$cek1->batch_daftar)->where('idjalur',$cek1->jalur_daftar)->where('idjurusan',$cek1->prodi3->id)->where('isactive',1)->first();
        }

        $rekomendator_text = '-';
        if ($cek1 && $cek1->rekomendator) {
            $rek = Master_Rekomendator::where('kode_rekomendator', $cek1->rekomendator)->first();
            if ($rek) {
                // Tampilan: Nama Lengkap (Kode)
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

    // Tambahkan "Request $request" di parameternya
    public function approve(Request $request, $params)
    {
        $id = decrypt($params);
        
        // Tangkap data skema dari AJAX dan normalize ke lowercase sesuai enum ('lunas','bulanan')
        $skema = strtolower($request->skema_ukt ?? 'lunas'); 

        $cek = Pendaftaran::where('KodePendaftaran',$id)->select('current_step','jalur_daftar')->first();
        $step = $cek->current_step + 1;

        DB::beginTransaction();

        $update1 = Pendaftaran::where('KodePendaftaran',$id)->update([
            'bayar_ukt'    => '1',
            'skema_ukt'    => $skema, // <-- Simpan ke tabel pmb_pendaftaran
            'current_step' => $step,
            'keterangan'   => null,
            'updated_at'   => date('Y-m-d H:i:s')
        ]);

        $update2 = Transaksi::where('id_referensi',$id)->update([
            'status'               => 'paid',
            'validator_pembayaran' => session('session')->nip,
            'keterangan'           => null,
            'updated_at'           => date('Y-m-d H:i:s')
        ]);

        if($update1 && $update2){
            DB::commit();
            LogAktivitas::catat('Pembayaran UKT', 'Approve', $id, 'Skema: ' . strtoupper($skema));
            $data['status']  = true;
            $data['message'] = 'Bukti Pembayaran Berhasil di Validasi (Skema: '. strtoupper($skema) .')';
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
            LogAktivitas::catat('Pembayaran UKT', 'Revisi', $id, 'Keterangan: ' . $ket);
            $alert = ['title' => 'Information', 'message' => 'Berhasil Menambahkan Keterangan', 'status' => 'success'];
        }else{
            DB::rollback();
            $alert = ['title' => 'Information', 'message' => 'Gagal Menambahkan Keterangan', 'status' => 'error'];
        }
        return redirect()->back()->with('alert',$alert);

    }

    public function exportExcel()
    {
        $filename = 'Rekap_Pembayaran_UKT_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new ExportPembayaranUKTExcel(), $filename);
    }
}
