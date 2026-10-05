<?php

namespace Modules\Admin\Http\Controllers\masterdata;

use App\Models\MasterData\Master_Jawaban;
use App\Models\MasterData\Master_Soal;
use App\Models\UsersImport;


use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Session, Crypt, DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class SoalTestController extends Controller
{
    public function index()
    {

        //$linkfile = url('sources/storage/app/FILE_TEMPLATE/Template Soal Test.xlsx');
	  $linkfile = url('admin/file/FILE_TEMPLATE/Template Soal Test.xlsx');

        $data = array(
            'title' => 'Master Soal Test',
            'menu'  => 'Soal Test',
            'linkfile' => $linkfile
        );
        return view('admin::masterdata.soaltest.index', $data);
    }

    public function table_Soal()
    {
        $data = Master_Soal::where('isactive',1)->with('jawaban')->get();
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('kode', function ($d) {
            return $d->kode_soal;
        })
        ->addColumn('soal', function ($d) {
            return $d->soal_test;
        })
        ->addColumn('jumlah', function ($d) {
            $nama = count($d->jawaban).' Pilihan Ganda';
            return $nama;
        })
        ->addColumn('aktif', function ($d) {
            $role = '-';
            $warna = '';
            if($d->isactive==1){
                $role = 'Aktif';
                $warna = 'success';
            }else{
                $role = 'Tidak Aktif';
                $warna = 'danger';
            }
            $show = '<span class="badge bg-'.$warna.'">'.$role.'</span>';
            return $show;
        })
        ->addColumn('action', function ($d) {
            $id = encrypt($d->id_soal);

            $url = '#';
            $edit   = '<a href="#" data-id="'.$id.'" class="btn_edit"><i title="Edit" class="fa fa-edit text-orange"></i></a>';
            $aktif = '';
            if($d->isactive==1){
                $aktif = '<a href="#" class="btn_delete" data-id="'.$id.'" data-status="'.encrypt('0').'"><i title="Hapus" class="fa fa-trash text-red"></i></a>';
            }else{
                $aktif  = '<a href="#" class="btn_delete" data-id="'.$id.'" data-status="'.encrypt('1').'"><i title="Aktifkan" class="fas fa-check-circle text-green"></i></a>';
            }
            $detail = '<a href="#" data-id="'.$id.'" class="btn_detail"><i title="Detail" class="fa fa-info-circle"></i></a>';

            return $detail.' '.$edit.' '.$aktif;
        })
        ->rawColumns(['action','aktif'])
        ->make(true);
    }

    public function StoreTest(Request $post)
    {
        if($post->idku==null){
            $cek = Master_Soal::where('isactive',1)->where('kode_soal',$post->kodesoal)->orwhere('soal_test',$post->pertanyaan)->first();
            if($cek!=null){
                $data['title']  = 'Information';
                $data['status'] = 'warning';
                $data['message'] = 'Kode Soal atau Soal Test Tidak Boleh Sama !';
            }else{
                $this->save($post);
                $data['title']  = 'Information';
                $data['status'] = 'success';
                $data['message'] = 'Data Tersimpan ';

            }
        }else{
            $this->update($post);
            $data['title']  = 'Information';
            $data['status'] = 'success';
            $data['message'] = 'Updated Data Berhasil';

        }
        return $data;
    }

    public function save($post)
    {
        $pertanyaan = $post->pertanyaan;
        DB::beginTransaction();

        $soal = Master_Soal::insert([
            'kode_soal' => $post->kodesoal,
            'soal_test' => $pertanyaan,
            'created_by' => session('session')->nip,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $idsoal = Master_Soal::latest('id_soal')->first();

        $pilihan = $post->pilihan;
        $kunci = $post->kuncijawaban;
        $score = $post->score;
        $abjad = $post->abjad;

        $count = 0;
        foreach ($pilihan as $key => $value) {
            if ($abjad[$key] != null && $value != null && $score[$key] != null) {
                $jawab = Master_Jawaban::insert([
                    'kode_soal' => $idsoal->kode_soal,
                    'pilihan' => strtoupper($abjad[$key]),
                    'jawaban' => $value,
                    'jawaban_benar' => strtoupper($kunci),
                    'score' => $score[$key],
                    'created_by' => session('session')->nip,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                if($jawab){
                    $count++;
                }
            }
        }
        if($count != count($abjad) || !$soal){
            DB::rollback();
        }else{
            DB::commit();
        }
    }

    public function ShowSoal($params,$detail)
    {
        $id = decrypt($params);
        $cek = Master_Soal::where('isactive',1)->where('id_soal',$id)->with(['jawaban'])->first();
        // dd($cek,$id);
        $ceksoal = false; //cek soal sedang digunakan atau tidak, sementara belum ada action dari soal, sementara false

        if($ceksoal and $detail=='edit'){
            $master['hasil'] = 2;
            $master['master'] = null;
        }elseif($cek){
            $master['hasil']  = 1;
            $master['master'] = $cek;
        }else{
            $master['hasil'] = 0;
            $master['master'] = null;
        }
        return response()->json($master, Response::HTTP_OK);
    }

    public function update($post)
    {
        $master['kode_soal']  = $post->kodesoal;
        $master['soal_test']  = $post->pertanyaan;
        $master['updated_at'] = date('Y-m-d H:i:s');
        $master['updated_by'] = session('session')->nip;
        Master_Soal::where('isactive',1)->where('id_soal',$post->idku)->update($master);

        $pilihan = $post->pilihan;
        $kunci = $post->kuncijawaban;
        $score = $post->score;
        $abjad = $post->abjad;

        $jawab = Master_Jawaban::where('kode_soal', $post->kodesoal)->where('isactive', 1)->get();
        if(count($post->pilihan) != count($jawab)){
            Master_Jawaban::where('kode_soal', $post->kodesoal)->where('isactive', 1)->delete();
            foreach ($pilihan as $key => $value) {
                if ($abjad[$key] != null && $value != null && $score[$key] != null) {
                    $savejawab = Master_Jawaban::create([
                        'kode_soal' => $post->kodesoal,
                        'pilihan' => strtoupper($abjad[$key]),
                        'jawaban' => $value,
                        'jawaban_benar' => strtoupper($kunci),
                        'score' => $score[$key],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('session')->nip,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => session('session')->nip
                    ]);
                }
            }
        }else {
            foreach ($jawab as $key => $value) {
                if ($abjad[$key] != null && $pilihan[$key] != null && $score[$key] != null) {
                    $savejawab = Master_Jawaban::where('id_jawaban', $value->id_jawaban)
                    ->where('kode_soal',$post->kodesoal)->update([
                        'kode_soal' => $post->kodesoal,
                        'pilihan' => strtoupper($abjad[$key]),
                        'jawaban' => $pilihan[$key],
                        'jawaban_benar' => strtoupper($kunci),
                        'score' => $score[$key],
                        'updated_by' => session('session')->nip,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
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

        $update = Master_Soal::where('id_soal',$id)->update($up);

        $kata = $aktif=='1' ? 'Berhasil Mengaktifkan Data': 'Berhasil Menghapus Data';
        $del = $aktif=='1' ? 'Gagal Mengaktifkan Data': 'Gagal Menghapus Data';

        if($update){
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

    public function upload_excel(Request $post)
    {
        $file = $post->file('fileku');
        if($file==null){
            $data['message'] = 'Silahkan pilih file !';
            $data['status'] = 'error';
        }else{

            $array = Excel::toArray(new UsersImport, $file);
            $rsA = [];
            $rsA = $array[0];
            $G = 0;
            dd($rsA[0][1]);
            DB::beginTransaction();

            if(count($rsA) > 0){
                if(count($rsA[0]) != 4 OR !($rsA[0][0] == 'Kode Soal') OR !($rsA[0][1] == 'Pertanyaan')){
                    $data['message'] = 'Maaf struktur file excel anda tidak sesuai dengan format di program!';
                    $data['status'] = 'error';
                }else{
                    $count = 0;
                    for($w = 1; $w < count($rsA); $w++){
                        $kodesoal = $rsA[$w][0];
                        $pertanyaan = $rsA[$w][1];
                        $pilskor = explode('##',$rsA[$w][2]);
                        $jwbbenar = $rsA[$w][3];

                        $cek = Master_Soal::where('kode_soal',$kodesoal)->orwhere('soal_test',$pertanyaan)->where('isactive',1)->first();

                        if($cek){
                            $count++;
                        }else{
                            Master_Soal::insert([
                                'kode_soal' => $kodesoal,
                                'soal_test' => $pertanyaan,
                                'created_by' => session('session')->nip,
                                'created_at' => date('Y-m-d H:i:s'),
                            ]);

                            $idsoal = Master_Soal::latest('id_soal')->first();
                            foreach($pilskor as $row => $p){
                                $ex1 = explode('=',$p);
                                $ex2 = explode('.',$ex1[0]);
                                $abjad = $ex2[0];
                                $pilihan = $ex2[1];
                                $score = $ex1[1];
                                // if ($abjad[$row] != null && $p != null && $score[$row] != null) {
                                Master_Jawaban::insert([
                                    'kode_soal' => $idsoal->kode_soal,
                                    'pilihan' => strtoupper($abjad),
                                    'jawaban' => $pilihan,
                                    'jawaban_benar' => strtoupper($jwbbenar),
                                    'score' => $score,
                                    'created_by' => session('session')->nip,
                                    'created_at' => date('Y-m-d H:i:s'),
                                ]);
                                // }
                            }
                        }
                    }

                    if($count==0){
                        DB::commit();
                        $data['message'] = 'Soal Test Berhasil Disimpan';
                        $data['status'] = 'success';
                    }else{
                        DB::rollback();
                        $data['message'] = 'Kode Soal atau Soal Test Tidak Boleh Sama !';
                        $data['status'] = 'error';
                    }

                }
            }else{
                DB::rollback();
                $data['message'] = 'Data askes kosong!, Silahkan cek ulang!';
                $data['status'] = 'error';
            }
        }
        // return $data;
        return response()->json($data, Response::HTTP_OK);

    }
}
