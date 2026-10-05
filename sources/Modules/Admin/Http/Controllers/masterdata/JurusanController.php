<?php

namespace Modules\Admin\Http\Controllers\Masterdata;

use App\Models\MasterData\Master_Fakultas;
use App\Models\MasterData\Master_Jenjang;
use App\Models\MasterData\Master_JurusanKuliah;
use App\Models\MasterData\Master_JurusanSekolah;
use App\Models\User\Pendaftaran;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Session, Crypt, DB;
use Yajra\DataTables\DataTables;
use Symfony\Component\HttpFoundation\Response;

class JurusanController extends Controller
{
    public function index()
    {
        $last = Master_JurusanKuliah::where('isactive',1)->orderBy('id','desc')->select('KodeJurusan')->first();
        if ($last) {
            $lastNumber = (int) substr($last->KodeJurusan, 1); // ambil angka setelah J
            $newCode = 'J' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newCode = 'J001';
        }
        $jenjang = Master_Jenjang::where('isactive',1)->get();
        $sekolah = Master_JurusanSekolah::where('isactive',1)->get();
        $fakultas = Master_Fakultas::where('isactive',1)->get();
        $data = array(
            'title' => 'Master Data Jurusan',
            'menu'  => 'Jurusan',
            'kdjurusan' => $newCode,
            'jenjang' => $jenjang,
            'sekolah' => $sekolah,
            'fakultas' => $fakultas
        );
        return view('admin::masterdata.jurusan.index', $data);
    }

    public function table_jurusan()
    {
        $data = Master_JurusanKuliah::where('isactive',1)->with('jenjang','JurusanSekolah','Fakultas')->orderBy('idfakultas','asc')->get();
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('kode', function ($d) {
            return $d->KodeJurusan;
        })
        ->addColumn('nama', function ($d) {
            $nama = $d->jurusan;
            return $nama;
        })
        ->addColumn('fakultas', function ($d) {
            $nama = $d->Fakultas->namafakultas;
            return $nama;
        })
        ->addColumn('jenjang', function ($d) {
            return $d->jenjang->jenjang;
        })
        ->addColumn('jurusansekolah', function ($d) {
            $nama = $d->JurusanSekolah->sekolah.' - '.$d->JurusanSekolah->jurusan_sekolah;
            return $nama;
        })
        // TAMBAHAN: Kolom Format NIM
        ->addColumn('format_nim', function ($d) {
            return $d->format_nim ?? '-';
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
            $id = encrypt($d->KodeJurusan);

            $url = '#';
            $edit   = '<a href="#" data-id="'.$id.'" class="btn_edit"><i title="Edit" class="fa fa-edit text-orange"></i></a>';
            $aktif = '';
            if($d->isactive==1){
                $url = route('admin.Jurusan.delete',[$id,encrypt('0')]);
                $aktif = '<a href="'.$url.'" class="btn_delete"><i title="Hapus" class="fa fa-trash text-red"></i></a>';
            }else{
                $url = route('admin.Jurusan.delete',[$id,encrypt('1')]);
                $aktif  = '<a href="'.$url.'" class="btn_delete"><i title="Aktifkan" class="fas fa-check-circle text-green"></i></a>';
            }
            return $edit.' '.$aktif;
        })
        ->rawColumns(['action','aktif'])
        ->make(true);
    }

    public function StoreJurusan(Request $post)
    {
        // Perbaikan: Hanya cek duplikat jika ini adalah simpan baru (IdJurusan == null)
        $cek = Master_JurusanKuliah::where('isactive',1)
        ->where('jurusan',$post->namajurusan)
        ->where('idjenjang',$post->jenjang)
        ->where('idjurusansekolah',$post->jurusansekolah)
        ->where('idfakultas',$post->fakultas)
        ->first();
        
        $alert = null;
        if($cek && $post->IdJurusan == null){
            $alert = array(
                'title' => 'Gagal!',
                'message' => 'Jurusan Sudah Ada !',
                'status' => 'warning'
            );
        }else{
            if($post->IdJurusan == null){
                $alert = $this->Save($post);
            }else{
                $alert = $this->Update($post);
            }
        }

        return redirect()->route('admin.Jurusan.show')->with('alert',$alert);
    }

    public function Save($post)
    {
        $up = array(
            'KodeJurusan' => $post->kdjurusan,
            'idfakultas' => $post->fakultas,
            'idjenjang'    => $post->jenjang,
            'idjurusansekolah'    => $post->jurusansekolah,
            'jurusan'    => $post->namajurusan,
            'format_nim' => $post->format_nim, // TAMBAHAN: Simpan Format NIM
            'created_at'   => date('Y-m-d H:i:s'),
            'created_by'   => optional(session('session'))->nip ?? 'System', // Menggunakan optional
        );
        
        DB::beginTransaction();
        try {
            $save = Master_JurusanKuliah::insert($up);
            DB::commit();
            $alert = array(
                'title' => 'Berhasil!',
                'message' => 'Data Jurusan Tersimpan !',
                'status' => 'success'
            );
        } catch (\Throwable $e) {
            DB::rollback();
            $alert = array(
                'title' => 'Gagal!',
                'message' => 'Data Jurusan Gagal Disimpan ! ',
                'status' => 'error'
            );
        }
        return $alert;
    }

    public function ShowJurusan($params)
    {
        $id = decrypt($params);
        // dd($id);
        $check = Master_JurusanKuliah::where('isactive',1)->where('KodeJurusan',$id)->first();

        if($check){
            $data['hasil'] = 1;
            $data['jurusan'] = $check;
            $data['IdJurusan'] = encrypt($check->KodeJurusan); // Perbaikan enkripsi ID
        }else{
            $data['hasil'] = 0;
            $data['jurusan'] = $check;
            $data['IdJurusan'] = null;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function Update($post)
    {
        $id = decrypt($post->IdJurusan);
        // dd($id);

        $up = array(
            'KodeJurusan' => $post->kdjurusan,
            'idfakultas' => $post->fakultas,
            'idjenjang'    => $post->jenjang,
            'idjurusansekolah'    => $post->jurusansekolah,
            'jurusan'    => $post->namajurusan,
            'format_nim' => $post->format_nim, // TAMBAHAN: Update Format NIM
            'updated_at'   => date('Y-m-d H:i:s'),
            'updated_by'   => optional(session('session'))->nip ?? 'System',
        );
        
        DB::beginTransaction();
        try {
            // Gunakan $id (KodeJurusan) sebagai identifier update
            $update = Master_JurusanKuliah::where('isactive',1)->where('KodeJurusan',$id)->update($up);
            DB::commit();
            $alert = array(
                'title' => 'Berhasil!',
                'message' => 'Data Jurusan Diperbarui !',
                'status' => 'success'
            );
        } catch (\Throwable $e) {
            DB::rollback();
            $alert = array(
                'title' => 'Gagal!',
                'message' => 'Data Jurusan Gagal Diperbarui ! ',
                'status' => 'error'
            );
        }
        return $alert;
    }

    public function delete($params1,$params2)
    {
        $id = decrypt($params1);
        $aktif = decrypt($params2);
        
        // Perbaiki pengecekan di tabel Pendaftaran
        $cek = Pendaftaran::where('pilihan1', $id)
            ->orWhere('pilihan2', $id)
            ->first();
            
        // dd($cek);
        if($cek){
            $alert = ['title' => 'Gagal','message' => 'Jurusan Sudah Ada yang mendaftar !','status' => 'error'];
            return redirect()->route('admin.Jurusan.show')->with('alert',$alert);
        }
        
        DB::beginTransaction();
        $up = array(
            'isactive' => $aktif,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => optional(session('session'))->nip ?? 'System'
        );

        $update = Master_JurusanKuliah::where('KodeJurusan',$id)->update($up);

        if($update){
            DB::commit();
            $alert = ['title' => 'Berhasil','message' => 'Jurusan Berhasil Diperbarui','status' => 'success'];
        }else{
            DB::rollback();
            $alert = ['title' => 'Gagal','message' => 'Jurusan Gagal Diperbarui','status' => 'error'];
        }
        return redirect()->route('admin.Jurusan.show')->with('alert',$alert);
    }
}