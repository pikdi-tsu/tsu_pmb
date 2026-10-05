<?php

namespace Modules\Admin\Http\Controllers\masterdata;

use App\Models\MasterData\Master_Beasiswa;
use App\Models\MasterData\Master_JenisBerkas;
use App\Models\MasterData\Master_JenisPendaftaran;
use App\Models\MasterData\Master_Tingkat;
use App\Models\User\Pendaftaran;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Session, Crypt, DB;
use Yajra\DataTables\DataTables;
use Symfony\Component\HttpFoundation\Response;

class BeasiswaController extends Controller
{
    public function index()
    {
        $jalur = Master_JenisPendaftaran::where('isactive',1)->where('is_beasiswa',1)->get();
        $tingkat = Master_Tingkat::where('isactive',1)->get();
        $data = array(
            'title'    => 'Master Data Beasiswa',
            'menu'     => 'Beasiswa',
            'jalur'    => $jalur,
            'tingkat'  => $tingkat
        );
        return view('admin::masterdata.beasiswa.index', $data);
    }

    public function TabelBeasiswa()
    {
        $data = Master_Beasiswa::where('isactive', '1')->with('tingkat','jalur')->get();
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('jalur', function ($d) {
            return $d->jalur->KodeJenis.' - '.$d->jalur->jenis_pendaftaran;
        })
        ->addColumn('beasiswa', function ($d) {
            $nama = $d->jenis_beasiswa;
            return $nama;
        })
        ->addColumn('tingkat', function ($d) {
            return $d->tingkat ? $d->tingkat->tingkat_kejuaraan : '-';
        })
        ->addColumn('juara', function ($d) {
            return $d->juara_ke ? $d->juara_ke : '-';
        })
        ->addColumn('durasi_s1', function ($d) {
            return $d->durasi_s1.' Semester';
        })
        ->addColumn('durasi_d3', function ($d) {
            $nama = $d->durasi_d3.' Semester';
            return $nama;
        })
        ->addColumn('potongan', function ($d) {
            $show = '<span class="badge bg-success">'.$d->persen_potongan.'%</span>';
            return $show;
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
            $id = encrypt($d->id);

            $url = '#';
            $edit   = '<a href="#" data-id="'.$id.'" class="btn_edit"><i title="Edit" class="fa fa-edit text-orange"></i></a>';
            $aktif = '';
            if($d->isactive==1){
                $aktif = '<a href="#" data-id="'.$id.'" data-status="'.encrypt('0').'" class="btn_delete"><i title="Hapus" class="fa fa-trash text-red"></i></a>';
            }else{
                $aktif  = '<a href="#" data-id="'.$id.'" data-status="'.encrypt('1').'" class="btn_delete"><i title="Aktifkan" class="fas fa-check-circle text-green"></i></a>';
            }
            return $edit.' '.$aktif;
        })
        ->rawColumns(['action','aktif','potongan'])
        ->make(true);
    }

    public function StoreBeasiswa(Request $post)
    {
        // dd($post);
        if($post->IdBeasiswa == null){
            $alert = $this->Save($post);
        }else{
            $alert = $this->Update($post);
        }

        return response()->json($alert, Response::HTTP_OK);
        // return redirect()->route('admin.JenisPendaftaran.show')->with('alert',$alert);

    }

    public function Save($post)
    {
        $up = array(
            'IdJalur' => $post->jalur,
            'jenis_beasiswa' => $post->jenis,
            'idtingkat' => $post->tingkat,
            'juara_ke' => $post->juara,
            'durasi_d3' => $post->biaya_d3,
            'durasi_s1' => $post->biaya_s1,
            'persen_potongan' => $post->potongan,
            'created_at'   => date('Y-m-d H:i:s'),
            'created_by'   => session('session')->nip,
        );
        DB::beginTransaction();
        $save = Master_Beasiswa::insert($up);
        if($save){
            DB::commit();
            $alert = array(
                'title' => 'Berhasil!',
                'message' => 'Data Beasiswa Tersimpan !',
                'status' => 'success'
            );
        }else{
            DB::rollback();
            $alert = array(
                'title' => 'Gagal!',
                'message' => 'Data Beasiswa Gagal Disimpan !',
                'status' => 'error'
            );
        }
        return $alert;
    }

    public function ShowBeasiswa($params)
    {
        $id = decrypt($params);
        // dd($id);
        $check = Master_Beasiswa::where('id',$id)->where('isactive',1)->first();

        if($check){
            $data['hasil'] = 1;
            $data['bea'] = $check;
            $data['IdBeasiswa'] = $params;
        }else{
            $data['hasil'] = 0;
            $data['bea'] = $check;
            $data['IdBeasiswa'] = $params;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function Update($post)
    {
        $id = decrypt($post->IdBeasiswa);
        // dd($id);

        $up = array(
            'IdJalur' => $post->jalur,
            'jenis_beasiswa' => $post->jenis,
            'idtingkat' => $post->tingkat,
            'juara_ke' => $post->juara,
            'durasi_d3' => $post->biaya_d3,
            'durasi_s1' => $post->biaya_s1,
            'persen_potongan' => $post->potongan,
            'updated_at'   => date('Y-m-d H:i:s'),
            'updated_by'   => session('session')->nip,
        );
        DB::beginTransaction();
        $update = Master_Beasiswa::where('id',$id)->update($up);
        if($update){
            DB::commit();
            $alert = array(
                'title' => 'Berhasil!',
                'message' => 'Data Beasiswa Diperbarui !',
                'status' => 'success'
            );
        }else{
            DB::rollback();
            $alert = array(
                'title' => 'Gagal!',
                'message' => 'Data Beasiswa Gagal Diperbarui !',
                'status' => 'error'
            );
        }
        return $alert;
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

        $update = Master_Beasiswa::where('id',$id)->update($up);

        $kata = $aktif=='1' ? 'Berhasil Mengaktifkan Data': 'Berhasil Menghapus Data';
        $del = $aktif=='1' ? 'Gagal Mengaktifkan Data': 'Gagal Menghapus Data';

        if($update){
            DB::commit();
            $master['message'] = $kata;
            $master['status'] = 'success';
        }else{
            DB::rollback();
            $master['message'] = $del;
            $master['status'] = 'error';
        }
        return response()->json($master, Response::HTTP_OK);
    }
}
