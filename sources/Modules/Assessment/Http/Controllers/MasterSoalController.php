<?php

namespace Modules\Assessment\Http\Controllers;

use Modules\Assessment\Services\QuestionService;
use App\Http\Controllers\Controller;
use App\Models\Assessment\Assessment_EngineTest;
use App\Models\Assessment\Assessment_QuestionOptions;
use App\Models\Assessment\Assessment_Questions;
use App\Models\Assessment\Assessment_TipeTest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MasterSoalController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index()
    {
        $test = Assessment_TipeTest::where('isactive',1)->get();
        $data = array(
            'title' => 'Master Soal',
            'menu'  => 'Master Soal',
            'testTypes' => $test
        );
        return view('assessment::masterdata.soal.index',$data);
    }

    function tabel_soal()
    {
        $query = Assessment_Questions::orderBy('tipe_test_id', 'asc')->orderBy('urutan','asc')->with('tipe','option')->get();

        return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('type', function($q){
            $tipe = '';

            if($q->tipe->tipe_engine=='multiple_choice'){
                $tipe = '<span class="badge badge-primary">'.$q->tipe->nama_test.'</span>';
            }elseif($q->tipe->tipe_engine=='single_choice'){
                $tipe = '<span class="badge badge-warning">'.$q->tipe->nama_test.'</span>';
            }elseif($q->tipe->tipe_engine=='disc'){
                $tipe = '<span class="badge badge-success">'.$q->tipe->nama_test.'</span>';
            }
            return $tipe;
        })
        ->editColumn('soal', function ($q) {
            return $q->pertanyaan;
        })
        ->editColumn('pilihan', function ($q) {
            $option = '';
            foreach($q->option as $p){
                $kode = '';
                $benar = '';
                $disc = '';
                $nilai = '';
                if($p->kode){
                    $kode = $p->kode.'. ';
                }
                if($p->is_benar==1){
                    $benar = '<i class="fas fa-check-circle text-success"></i>';
                }
                if($p->disc_tipe){
                    $disc = '<span class="badge bg-success">'.$p->disc_tipe.'</span>';
                }
                if($p->nilai!=null){
                    $nilai = '<span class="badge bg-success">'.$p->nilai.'</span>';
                }
                $option .= $disc.'<span class="badge bg-warning">'.$kode.$p->label.'</span>'.$benar.$nilai.'<br>';
            }
            return $option;
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
        ->rawColumns(['aktif', 'action','tipe','pilihan','type'])
        ->make(true);
    }

    public function store(Request $post)
    {
        $data = null;
        if($post->IdSoal){
            $data = $this->questionService->update($post);
        }else{
            $data = $this->questionService->store($post);
        }

        return response()->json($data,Response::HTTP_OK);
    }

    public function show($params)
    {
        $id = decrypt($params);
        $question = Assessment_Questions::with(['option', 'tipe'])->findOrFail($id);
        return response()->json($question);
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
        $update = Assessment_Questions::where('id',$id)->update($array);
        if($update){
            DB::commit();
            $data['title'] = 'Berhasil';
            $data['message'] = $status=='0' ? 'Soal Test Berhasil Dinonaktifkan' : 'Soal Test Berhasil Diaktifkan';
            $data['status'] = 'success';
        }else{
            DB::rollBack();
            $data['title'] = 'Gagal';
            $data['message'] = $status=='0' ? 'Soal Test Gagal Dinonaktifkan' : 'Soal Test Gagal Diaktifkan';
            $data['status'] = 'error';
        }

        return response()->json($data, Response::HTTP_OK);
    }
}
