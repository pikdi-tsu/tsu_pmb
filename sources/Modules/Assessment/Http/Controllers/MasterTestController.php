<?php

namespace Modules\Assessment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assessment\Assessment_EngineTest;
use App\Models\Assessment\Assessment_TipeTest;
use App\Models\MasterData\Master_Fakultas;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MasterTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $engine = Assessment_EngineTest::where('isactive',1)->select('id','tipe_engine','keterangan')->get();
        $data = array(
            'title' => 'Master Test',
            'menu'  => 'Master Test',
            'engine' => $engine
        );
        return view('assessment::masterdata.test.index',$data);
    }

    function tabel_test()
    {
        $query = Assessment_TipeTest::orderBy('urutan', 'asc')->orderBy('isactive','desc')->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama', function($q){
                return $q->nama_test;
            })
            ->editColumn('kode', function ($q) {
                return $q->kode_test;
            })
            ->editColumn('urutan', function ($q) {
                return $q->urutan;
            })
            ->editColumn('durasi', function ($q) {
                return $q->durasi_menit.' Menit';
            })
            ->editColumn('aktif', function ($q) {

                $aktif = $q->isactive==1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
                return $aktif;
            })
            ->addColumn('action', function ($q) {
                $id = encrypt($q->id);
                $btn = '';
                // Tombol Set Aktif (Hijau)
                if($q->isactive==0){
                    $btn .= '<button class="btn btn-success btn-sm btn_aktif" data-aktif="1" data-id="'.$id.'" title="Set Aktif"><i class="fas fa-check"></i></button>';
                }
                // Tombol Set Tidak Aktif (merah)
                if($q->isactive==1){
                    $btn .= '<button class="btn btn-danger btn-sm btn_aktif" data-aktif="0" data-id="'.$id.'" title="Set Non Aktif"><i class="fa fa-trash"></i></button>';
                }
                // Tombol Edit/Detail (Biru)
                $btn .= ' <button class="btn btn-warning btn-sm btn_edit" data-id="'.$id.'" title="Edit Data"><i class="fas fa-edit"></i></button>';
                // Tombol Hapus (Merah)
                // $btn .= ' <button type="button" class="btn btn-danger btn-sm btn_hapus" data-id="'.$id.'" title="Hapus"><i class="fas fa-trash"></i></button>';
                return '<div class="text-center">'.$btn.'</div>';
            })
            ->rawColumns(['aktif', 'action','tipe'])
            ->make(true);
    }

    public function store(Request $post)
    {
        DB::beginTransaction();
        $save = null;
        if($post->IdTest){
            $save = $this->update($post);
        }else{
            $save = $this->save($post);
        }
        return response()->json($save, Response::HTTP_OK);
    }

    public function save($post)
    {
        $cek = Assessment_TipeTest::where('kode_test',$post->kodetest)->orwhere('urutan',$post->urutan_test)->where('isactive',1)->exists();
        if($cek){
            $data = array(
                'title' => 'Perhatian',
                'message' => 'Kode Test atau Urutan Test Tidak Boleh Sama',
                'status' => 'warning'
            );
        }else{
            $array = array(
                'nama_test' => $post->nama_test,
                'kode_test' => $post->kodetest,
                'tipe_engine' => $post->engine_test,
                'durasi_menit' => $post->durasi_test,
                'urutan' => $post->urutan_test,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => session('session')->nip
            );
            $save = Assessment_TipeTest::insert($array);
            if($save){
                DB::commit();
                $data = array(
                    'title' => 'Berhasil',
                    'message' => 'Data Type Test Berhasil Ditambahkan',
                    'status' => 'success'
                );
            }else{
                DB::rollBack();
                $data = array(
                    'title' => 'Gagal',
                    'message' => 'Data Type Test Gagal Ditambahkan',
                    'status' => 'error'
                );
            }
        }

        return $data;
    }

    public function update($post)
    {
        $id = $post->IdTest;
        $cek2 = Assessment_TipeTest::where('id','!=',$id)->where('isactive',1)->where('urutan',$post->urutan_test)->exists();
        if($cek2){
            $data = array(
                'title' => 'Perhatian',
                'message' => 'Urutan Test Tidak Boleh Sama',
                'status' => 'warning'
            );
        }else{
            $array = array(
                'nama_test' => $post->nama_test,
                'kode_test' => $post->kodetest,
                'tipe_engine' => $post->engine_test,
                'durasi_menit' => $post->durasi_test,
                'urutan' => $post->urutan_test,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => session('session')->nip
            );
            $update = Assessment_TipeTest::where('id',$id)->update($array);
            if($update){
                DB::commit();
                $data = array(
                    'title' => 'Berhasil',
                    'message' => 'Data Type Test Berhasil Diperbarui',
                    'status' => 'success'
                );
            }else{
                DB::rollBack();
                $data = array(
                    'title' => 'Gagal',
                    'message' => 'Data Type Test Gagal Diperbarui',
                    'status' => 'error'
                );
            }
        }
        return $data;
    }

    public function show($params)
    {
        $id = decrypt($params);
        $cek = Assessment_TipeTest::where('id',$id)->where('isactive',1);
        if($cek->exists()){
            $data = array(
                'hasil' => 1,
                'type' => $cek->first()
            );
        }else{
            $data = array(
                'hasil' => 1,
                'type' => null
            );
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function destroy($params,$status)
    {
        $id = decrypt($params);
        DB::beginTransaction();
        $array = array(
            'isactive' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session('session')->nip
        );
        $update = Assessment_TipeTest::where('id',$id)->update($array);
        if($update){
            DB::commit();
            $data['title'] = 'Berhasil';
            $data['message'] = $status=='0' ? 'Data Type Test Berhasil Dinonaktifkan' : 'Data Type Test Berhasil Diaktifkan';
            $data['status'] = 'success';
        }else{
            DB::rollBack();
            $data['title'] = 'Gagal';
            $data['message'] = $status=='0' ? 'Data Type Test Gagal Dinonaktifkan' : 'Data Type Test Gagal Diaktifkan';
            $data['status'] = 'error';
        }

        return response()->json($data, Response::HTTP_OK);
    }
}
