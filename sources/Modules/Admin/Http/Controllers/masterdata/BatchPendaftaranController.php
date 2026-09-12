<?php

namespace Modules\Admin\Http\Controllers\masterdata;

use App\Models\MasterData\Master_Batch;
use App\Models\MasterData\Master_TarifUKT;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Session, Crypt, DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class BatchPendaftaranController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Master Batch Pendaftaran',
            'menu'  => 'Batch Pendaftaran',
        );
        return view('admin::masterdata.batchpendaftaran.index', $data);
    }

    public function TabelBatch()
    {
        $data = Master_Batch::orderBy('id','desc')->get();
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('kode', function ($d) {
            return $d->kode_batch;
        })
        ->addColumn('nama', function ($d) {
            return $d->nama_batch;
        })
        ->addColumn('tahun', function ($d) {
            $nama = $d->tahun_akademik;
            return $nama;
        })
        ->addColumn('mulai', function ($d) {
            $nama = tglIndo($d->tglmulai);
            return $nama;
        })
        ->addColumn('selesai', function ($d) {
            $nama = tglIndo($d->tglselesai);
            return $nama;
        })
        ->addColumn('kuota', function ($d) {
            $nama = $d->kuota.' Orang';
            return $nama;
        })
        ->addColumn('aktif', function ($d) {
            $today = now()->toDateString();
            if ($d->isactive != 1) {
                return '<span class="badge badge-secondary">Tidak Aktif</span>';
            }
            if ($today < $d->tglmulai) {
                $sisaHari = now()->diffInDays($d->tglmulai, false);
                return '<span class="badge badge-warning text-dark">Akan Datang</span><br><small class="text-muted">Mulai ' . tglIndo($d->tglmulai) . '</small>';
            } elseif ($today > $d->tglselesai) {
                return '<span class="badge badge-secondary">Berakhir</span>';
            } else {
                $sisaHari = now()->diffInDays($d->tglselesai, false);
                $warna    = $sisaHari <= 3 ? 'danger' : 'success';
                return '<span class="badge badge-' . $warna . '">Aktif</span><br><small class="text-' . $warna . '">Sisa ' . $sisaHari . ' hari</small>';
            }
        })
        ->addColumn('action', function ($d) {
            $id = encrypt($d->id);

            $url = '#';
            $edit   = '<a href="#" data-id="'.$id.'" class="btn_edit"><i title="Edit" class="fa fa-edit text-orange"></i></a>';
            $aktif = '';
            $detail = '';
            if($d->isactive==1){
                $aktif = '<a href="#" class="btn_delete" data-id="'.$id.'" data-status="'.encrypt('0').'"><i title="Hapus" class="fa fa-trash text-red"></i></a>';
            }else{
                $aktif  = '<a href="#" class="btn_delete" data-id="'.$id.'" data-status="'.encrypt('1').'"><i title="Aktifkan" class="fas fa-check-circle text-green"></i></a>';
            }
            // $detail = '<a href="#" data-id="'.$id.'" class="btn_detail"><i title="Detail" class="fa fa-info-circle"></i></a>';

            return $detail.' '.$edit.' '.$aktif;
        })
        ->rawColumns(['action','aktif'])
        ->make(true);
    }


    public function StoreBatch(Request $post)
    {
        // dd($post);
        $mulai = $post->tglmulai;  // format YYYY-mm-dd
        $selesai = $post->tglselesai;
        if($post->IdBatch==null){
            $cek = Master_Batch::where('isactive', 1)
            ->where(function($q) use ($post) {
                $q->where('kode_batch', $post->kodebatch)
                ->orWhere('nama_batch', $post->batch);
                // ->orWhere('tahun_akademik', $post->tahun);
            })
            ->exists();
            $cektgl = Master_Batch::where('isactive', 1)
            ->where('tglmulai', '<=', $selesai)
            ->where('tglselesai', '>=', $mulai)
            ->exists();
            if($cek){
                $data['title']  = 'Information';
                $data['status'] = 'warning';
                $data['message'] = 'Kode Batch atau Nama Batch Sudah Ada !';
            }elseif($cektgl){
                $data['title']  = 'Information';
                $data['status'] = 'warning';
                $data['message'] = 'Tanggal Mulai/Selesai Ada didalam Batch Lain !';
            }else{
                $data = $this->save($post);
                // $data['title']  = 'Information';
                // $data['status'] = 'success';
                // $data['message'] = 'Data Tersimpan ';
            }
        }else{
            $id = decrypt($post->IdBatch);
            $cek = Master_Batch::where('id','!=',$id)->where('isactive', 1)
            ->where('kode_batch', $post->kodebatch)
            ->where('nama_batch', $post->batch)
            ->exists();

            $cektgl = Master_Batch::where('id','!=',$id)->where('isactive', 1)
            ->where('tglmulai', '<=', $selesai)
            ->where('tglselesai', '>=', $mulai)
            ->exists();
            // dd($cek,$cektgl);
            if($cek){
                $data['title']  = 'Information';
                $data['status'] = 'warning';
                $data['message'] = 'Batch Sudah Ada !';
            }elseif($cektgl){
                $data['title']  = 'Information';
                $data['status'] = 'warning';
                $data['message'] = 'Tanggal Mulai/Selesai Ada didalam Batch Lain !';
            }else{
                $data = $this->update($post);
                // $data['title']  = 'Information';
                // $data['status'] = 'success';
                // $data['message'] = 'Updated Data Berhasil';
            }
        }
        return $data;
    }

    public function save($post)
    {
        DB::beginTransaction();

        $arrayIn = array(
            'kode_batch' => $post->kodebatch,
            'nama_batch' => $post->batch,
            'tahun_akademik' => $post->tahun,
            'tglmulai' => $post->tglmulai,
            'tglselesai' => $post->tglselesai,
            'kuota' => $post->kuota,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => session('session')->nip,
            'isactive'   => $post->status

        );

        $batch = Master_Batch::insert($arrayIn);

        if(!$batch){
            DB::rollback();
            $data['title']  = 'Information';
            $data['status'] = 'error';
            $data['message'] = 'Data Batch Gagal Tersimpan';
        }else{
            DB::commit();
            $data['title']  = 'Information';
            $data['status'] = 'success';
            $data['message'] = 'Data Batch Tersimpan ';
        }
        return $data;
    }

    public function ShowBatch($params)
    {
        $id = decrypt($params);
        $cek = Master_Batch::where('isactive',1)->where('id',$id)->first();

        if($cek){
            $master['hasil']  = 1;
            $master['batch'] = $cek;
            $master['IdBatch'] = $params;
        }else{
            $master['hasil'] = 0;
            $master['batch'] = null;
            $master['IdBatch'] = $params;
        }
        return response()->json($master, Response::HTTP_OK);
    }

    public function update($post)
    {
        $id = decrypt($post->IdBatch);
        DB::beginTransaction();

        $arrayIn = array(
            'kode_batch' => $post->kodebatch,
            'nama_batch' => $post->batch,
            'tahun_akademik' => $post->tahun,
            'tglmulai' => $post->tglmulai,
            'tglselesai' => $post->tglselesai,
            'kuota' => $post->kuota,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session('session')->nip,
            'isactive'   => $post->status

        );

        $batch = Master_Batch::where('id',$id)->update($arrayIn);

        if(!$batch){
            DB::rollback();
            $data['title']  = 'Information';
            $data['status'] = 'error';
            $data['message'] = 'Update Data Batch Gagal';
        }else{
            DB::commit();
            $data['title']  = 'Information';
            $data['status'] = 'success';
            $data['message'] = 'Update Data Batch Berhasil ';
        }
        return $data;
    }

    public function delete($params1,$params2)
    {
        $id = decrypt($params1);
        $aktif = decrypt($params2);
        DB::beginTransaction();
        $up = array(
            'isactive' => $aktif,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session('session')->nip
        );

        $update = Master_Batch::where('id',$id)->update($up);

        $upukt = Master_TarifUKT::where('idbatch',$id)->update([
            'isactive' => $aktif,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session('session')->nip
        ]);

        $kata = $aktif=='1' ? 'Berhasil Mengaktifkan Data': 'Berhasil Menghapus Data';
        $del = $aktif=='1' ? 'Gagal Mengaktifkan Data': 'Gagal Menghapus Data';

        if($update&&$upukt){
            DB::commit();
            $master['message'] = $kata;
            $master['type'] = 'success';
        }else{
            DB::rollback();
            $master['message'] = $del;
            $master['type'] = 'error';
        }
        return response()->json($master, Response::HTTP_OK);
        // return redirect()->route('admin.Test.show')->with('alert',$alert);
    }
}
