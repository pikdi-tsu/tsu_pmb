<?php

namespace Modules\Admin\Http\Controllers\masterdata;

use App\Models\MasterData\Master_Fakultas;
use App\Models\MasterData\Master_JurusanKuliah;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Session, Crypt, DB;
use Yajra\DataTables\DataTables;
use Symfony\Component\HttpFoundation\Response;

class FakultasController extends Controller
{
    public function index()
    {
        $last = Master_Fakultas::where('isactive',1)->orderBy('IdFakultas','desc')->select('KodeFakultas')->first();
        if ($last) {
            $lastNumber = (int) substr($last->KodeFakultas, 1); // ambil angka setelah F
            $newCode = 'F' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newCode = 'F001';
        }
        $data = array(
            'title' => 'Master Data Fakultas',
            'menu'  => 'Fakultas',
            'kdfakultas' => $newCode
        );
        return view('admin::masterdata.fakultas.index', $data);
    }

    public function table_fakultas()
    {
        $data = Master_Fakultas::where('isactive',1)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('kode', function ($d) {
                return $d->KodeFakultas;
            })
            ->addColumn('nama', function ($d) {
                $nama = $d->namafakultas;
                return $nama;
            })
            ->addColumn('singkatan', function ($d) {
                return $d->singkatan;
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
                $id = encrypt($d->KodeFakultas);

                $url = '#';
                $edit   = '<a href="#" data-id="'.$id.'" class="btn_edit"><i title="Edit" class="fa fa-edit text-orange"></i></a>';
                $aktif = '';
                if($d->isactive==1){
                    $url = route('admin.fakultas.delete',[$id,encrypt('0')]);
                    $aktif = '<a href="'.$url.'" class="btn_delete"><i title="Hapus" class="fa fa-trash text-red"></i></a>';
                }else{
                    $url = route('admin.fakultas.delete',[$id,encrypt('1')]);
                    $aktif  = '<a href="'.$url.'" class="btn_delete"><i title="Aktifkan" class="fas fa-check-circle text-green"></i></a>';
                }
                return $edit.' '.$aktif;
            })
            ->rawColumns(['action','aktif'])
            ->make(true);
    }

    public function StoreFakultas(Request $post)
    {
        // PERBAIKAN: Bungkus pencarian 'orWhere' agar tidak bertabrakan dengan 'isactive = 1'
        $cek = Master_Fakultas::where('isactive', 1)
            ->where(function($query) use ($post) {
                $query->where('namafakultas', $post->namafakultas)
                      ->orWhere('singkatan', $post->singkatanfakultas);
            })
            ->first();
            
        $alert = null;
        
        // Hanya cek duplikat jika ini adalah simpan baru (IdFakultas == null)
        if($cek && $post->IdFakultas == null){
            $alert = array(
                'title' => 'Gagal!',
                'message' => 'Nama Fakultas atau Singkatan Sudah Ada !',
                'status' => 'warning'
            );
        }else{
            if($post->IdFakultas == null){
                $alert = $this->Save($post);
            }else{
                $alert = $this->Update($post);
            }
        }

        return redirect()->back()->with('alert',$alert);
    }

    public function Save($post)
    {
        $up = array(
            'KodeFakultas' => $post->kdfakultas,
            'namafakultas' => $post->namafakultas,
            'singkatan'    => $post->singkatanfakultas,
            'format_nim'   => $post->format_nim, // TAMBAHAN: Simpan Format NIM
            'created_at'   => date('Y-m-d H:i:s'),
            'created_by'   => optional(session('session'))->nip ?? 'System', // Menggunakan optional untuk keamanan
        );
        DB::beginTransaction();
        try {
            $save = Master_Fakultas::insert($up);
            DB::commit();
            $alert = array(
                'title' => 'Berhasil!',
                'message' => 'Data Fakultas Tersimpan !',
                'status' => 'success'
            );
        } catch (\Throwable $e) {
            DB::rollback();
            $alert = array(
                'title' => 'Gagal!',
                'message' => 'Data Fakultas Gagal Disimpan ! ',
                'status' => 'error'
            );
        }
        return $alert;
    }

    public function ShowFakultas($params)
    {
        $id = decrypt($params); // Ini berisi KodeFakultas (contoh: 'F004')
        $check = Master_Fakultas::where('KodeFakultas', $id)->first();

        if($check){
            $data['hasil'] = 1;
            $data['fakultas'] = $check;
            // PERBAIKAN: Ambil IdFakultas yang asli (angka) lalu enkripsi
            $data['IdFakultas'] = encrypt($check->IdFakultas); 
        }else{
            $data['hasil'] = 0;
            $data['fakultas'] = $check;
            $data['IdFakultas'] = null;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function Update($post)
    {
        $id = decrypt($post->IdFakultas);

        $up = array(
            'namafakultas' => $post->namafakultas,
            'singkatan'    => $post->singkatanfakultas,
            'format_nim'   => $post->format_nim, // TAMBAHAN: Update Format NIM
            'updated_at'   => date('Y-m-d H:i:s'),
            'updated_by'   => optional(session('session'))->nip ?? 'System',
        );
        DB::beginTransaction();
        try {
            $update = Master_Fakultas::where('isactive',1)->where('IdFakultas',$id)->where('KodeFakultas',$post->kdfakultas)->update($up);
            DB::commit();
            $alert = array(
                'title' => 'Berhasil!',
                'message' => 'Data Fakultas Diperbarui !',
                'status' => 'success'
            );
        } catch (\Throwable $e) {
            DB::rollback();
            $alert = array(
                'title' => 'Gagal!',
                'message' => 'Data Fakultas Gagal Diperbarui ! ',
                'status' => 'error'
            );
        }
        return $alert;
    }

    public function delete($params1,$params2)
    {
        $id = decrypt($params1);
        $aktif = decrypt($params2);
        $cek = Master_JurusanKuliah::where('fakultas',$id)->first();
        // dd($cek);
        if($cek){
            $alert = ['title' => 'Gagal','message' => 'Menu Sudah Digunakan !','status' => 'error'];
            return redirect()->back()->with('alert',$alert);
        }
        DB::beginTransaction();
        $up = array(
            'isactive' => $aktif,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => optional(session('session'))->nip ?? 'System'
        );

        $update = Master_Fakultas::where('KodeFakultas',$id)->update($up);

        if($update){
            DB::commit();
            $alert = ['title' => 'Berhasil','message' => 'Fakultas Berhasil Diperbarui','status' => 'success'];
        }else{
            DB::rollback();
            $alert = ['title' => 'Gagal','message' => 'Fakultas Gagal Diperbarui','status' => 'error'];
        }
        return redirect()->back()->with('alert',$alert);
    }
}