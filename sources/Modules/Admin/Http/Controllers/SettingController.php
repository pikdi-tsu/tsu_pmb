<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\Admin\GroupUserModel;
use App\Models\Admin\MahasiswaModel;
use App\Models\Admin\MasterGroupModel;
use App\Models\Admin\ModulModel;
use App\Models\Admin\PegawaiModel;
use App\Models\Admin\User;
use App\Models\Admin\UserResetPasswordModel;
use App\Models\Admin\LogAktivitas;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

use Session, Crypt, DB;

class SettingController extends Controller
{
    //change password normal
    public function showChangePassword()
    {
        $data = array(
            'title' => 'Change Password',
            'menu'  => 'Change Password',
        );
        return view('admin::setting.changepassword', $data);
    }

    public function saveChangePassword(Request $post)
    {
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Headers: *");
        // dd($post->_token);
        //cek password
        $oldpass = $post->oldpass;
        $newpass = $post->newpass;
        $newpass2 = $post->newpass2;
        // dd(preg_match('/\d/', $$oldpass));
        if((preg_match('/[[:punct:]]/', $oldpass))==1||(preg_match('/[A-Z]/', $oldpass))==0||(preg_match('/\d/', $oldpass))==0||strlen($oldpass)<8){
            Session::flash('alert', ['title' => 'Gagal','message' => 'Pergantian Password Gagal ! Silahkan Baca Note !','status' => 'error']);
            return redirect()->back();
        }

        if((preg_match('/[[:punct:]]/', $newpass))==1||(preg_match('/[A-Z]/', $newpass))==0||(preg_match('/\d/', $newpass))==0||strlen($newpass)<8){
            Session::flash('alert', ['title' => 'Gagal','message' => 'Pergantian Password Gagal ! Silahkan Baca Note !','status' => 'error']);
            return redirect()->back();
        }

        if((preg_match('/[[:punct:]]/', $newpass2))==1||(preg_match('/[A-Z]/', $newpass2))==0||(preg_match('/\d/', $newpass2))==0||strlen($newpass2)<8){
            Session::flash('alert', ['title' => 'Gagal','message' => 'Pergantian Password Gagal ! Silahkan Baca Note !','status' => 'error']);
            return redirect()->back();
        }

        if($newpass!=$newpass2){
            Session::flash('alert', ['title' => 'Gagal','message' => 'Password Baru Tidak Sama ! Silahkan Ulangi !','status' => 'error']);
            return redirect()->back();
        }

        $nik = session('session')->nip;
        $email = session('session')->email;

        $cek = User::where('nik',$nik)->where('email',$email)->where('isactive',1)->first();
        // dd(Hash::check($oldpass,$cek->password));
        if($cek){
            if(Hash::check($newpass,$cek->password)){
                Session::flash('alert', ['title' => 'Gagal','message' => 'Password Baru Tidak Boleh Sama Seperti Password Lama !','status' => 'error']);
                return redirect()->back();
            }
            if(Hash::check($oldpass,$cek->password)){
                $user = array(
                    'password' => Hash::make($newpass),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $nik
                );
                $up1 = User::where('nik',$nik)->where('email',$email)->where('isactive',1)->update($user);
                $logreset = array(
                    'email' => $email,
                    'token' => $post->_token,
                    'activity' => 'Change Password PMB',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $nik
                );
                $up2 = UserResetPasswordModel::insert($logreset);
                if($up1 && $up2){
                    Session::flash('alert', ['title' => 'Berhasil','message' => 'Password Berubah','status' => 'success']);
                    return redirect()->back();
                }else{
                    Session::flash('alert', ['title' => 'Gagal','message' => 'Password Tidak Berubah !','status' => 'error']);
                    return redirect()->back();
                }
            }else{
                Session::flash('alert', ['title' => 'Gagal','message' => 'Password Salah !','status' => 'error']);
                return redirect()->back();
            }
        }else{
            Session::flash('alert', ['title' => 'Gagal','message' => 'User Tidak Ada ! Silahkan Hubungi Team IT','status' => 'error']);
            return redirect()->back();
        }
    }
    //END change password normal

    //Edit Profile
    public function showEditProfile()
    {
        $data = array(
            'title' => 'Change Profile',
            'menu'  => 'Change Profile',
        );
        return view('admin::setting.editprofile', $data);
    }

    public function saveEditProfile(Request $post)
    {
        // dd($post);
        if ($post->hasFile('photoprofile')) {
            // $file->getClientOriginalName() -> mengambil nama file
            $file = $post->file('photoprofile');
            $ext = $file->getClientOriginalExtension();
            // dd($ext);
            $filename = time() . '_' . session('session')->nip.'.'.$ext;
            $file->storeAs('FILE_PHOTOPROFILE', $filename);
            User::where('nik',session('session')->nip)->where('email',session('session')->email)->where('isactive',1)->update([
                'photo_profile_pmb' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => session('session')->nip
            ]);
            Session::flash('alert', ['title' => 'Berhasil','message' => 'Profil Berhasil Diperbarui','status' => 'success']);
            return redirect()->back();
        }else{
            Session::flash('alert', ['title' => 'Gagal','message' => 'Foto Tidak ditemukan','status' => 'error']);
            return redirect()->back();
        }
    }
    //END Edit Profile

    //User Management
    //Pegawai
    public function userManagement()
    {
        $master = MasterGroupModel::where('isactive',1)->selectRaw('KodeGroupUser,NamaGroup')->get();
        $data = array(
            'title' => 'User Management',
            'menu'  => 'User Management',
            'mastergroup' => $master
        );
        return view('admin::setting.usermanagement', $data);
    }

    public function table_pegawai()
    {
        $data = User::where('isactive',1)->selectRaw('id,nik,email,privilege_pmb')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nik', function ($d) {
                return $d->nik;
            })
            ->addColumn('nama', function ($d) {
                $cek = PegawaiModel::where('nik',$d->nik)->where('email',$d->email)->select('nama')->first();
                $nama = $cek==null ? '-' : $cek->nama;
                return $nama;
            })
            ->addColumn('email', function ($d) {
                return $d->email;
            })
            ->addColumn('role', function ($d) {
                $cek = MasterGroupModel::where('KodeGroupUser',$d->privilege_pmb)->selectRaw('NamaGroup')->first();
                return $cek==null ? '-' : $cek->NamaGroup;
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->id);
                $detail = '';
                $edit = '<a href="#" data-id="'.$id.'" class="btn_edit"><i title="Edit Data" class="fa fa-edit text-orange"></i></a>';
                $delete = '<a href="#" data-id="'.$id.'" class="btn_delete"><i title="Delete Data" class="fa fa-trash text-red"></i></a>';
                // $detail = '<a href="#" data-id="'.$id.'" class="btn_detail"><i title="Detail Content" class="fas fa-info-circle"></i></a>';

                return $detail . '  ' . $edit . '  ' . $delete;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function searchNama(Request $post)
    {
        $query = $post->get('q');
        // dd($query,$role);

        $data = PegawaiModel::where('nama', 'LIKE', "%{$query}%")
        ->orWhere('nik','LIKE',"%{$query}%")
        ->whereNotNull('email')
        ->select('nik', 'nama')
        ->limit(10)
        ->get();

        return response()->json($data);
    }

    public function StoreUser(Request $post)
    {
        // dd($post);
        if($post->userid==null){
            $alert = $this->SaveUser($post);
        }else{
            $alert = $this->EditSaveUser($post);
        }
        return redirect()->back()->with('alert',$alert);
    }

    public function SaveUser($post)
    {
        $role = $post->roleaccess;
        $nik = $post->nik;
        // dd($nik);
        if($role==null||$nik==null){
            return ['title' => 'Information','message' => 'User dan Role Akses tidak boleh kosong !','status' => 'warning'];
        }

        $cek = User::where('nik',$nik)->where('isactive',1)->first();
        if($cek){
            return ['title' => 'Information','message' => 'User Sudah Terdaftar','status' => 'warning'];
        }

        $cek1 = PegawaiModel::where('nik',$nik)->select('nik','email')->first();
        $email = $cek1->email;

        // dd($email);
        if($cek1==null){
            return ['title' => 'Information','message' => 'User Sudah Tidak Terdaftar sebagai Dosen atau Tendik !','status' => 'warning'];
        }

        $in = array(
            'nik' => $nik,
            'email' => $email,
            'privilege_pmb' => $role,
            'password' => Hash::make(defaultpassword()),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => session('session')->nip
        );

        $insert = User::insert($in);

        if($insert){
            $alert = ['title' => 'Information','message' => 'User Berhasil Ditambahkan !','status' => 'success'];
        }else{
            $alert = ['title' => 'Information','message' => 'User Gagal Ditambahkan !','status' => 'error'];
        }
            // return redirect()->back()->with('alert',$alert);
            return $alert;
    }

    public function DetailUser($params)
    {

        $id = decrypt($params);
        $cek = User::where('id',$id)->where('isactive',1)->selectRaw('id,nik,privilege_pmb')->first();
        // dd($cek);

        $cek1 = PegawaiModel::where('nik',$cek->nik)->selectRaw('nik, nama')->first();
        $nama = $cek1->nama;

        $data['nik'] = $cek->nik;
        $data['nama'] = $nama;
        $data['role'] = $cek->privilege_pmb;
        $data['userid'] = $params;
        return response()->json($data, Response::HTTP_OK);
    }

    public function EditSaveUser($post)
    {
        $id = decrypt($post->userid);

        $up = array(
            'privilege_pmb' => $post->roleaccess,
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by'  => session('session')->nip
        );

        $updt = User::where('id',$id)->update($up);
        if($updt){
            $alert = ['title' => 'Information','message' => 'Role Akses Berhasil diganti !','status' => 'success'];
        }else{
            $alert = ['title' => 'Information','message' => 'Role Akses Gagal diganti !','status' => 'error'];
        }

        return $alert;
    }

    public function DeleteUser($params)
    {
        $id = decrypt($params);
        // dd($id);
        $cek = User::where('id',$id)->first();
        if(session('session')->nip==$cek->nik){
            $alert = ['title' => 'Gagal','message' => 'User Masih Aktif !','status' => 'error'];
            return response()->json($alert, Response::HTTP_OK);
        }
         $update = User::where('id',$id)->update([
            'isactive' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session('session')->nip
         ]);

        if($update){
            $alert = ['title' => 'Berhasil','message' => 'User Berhasil dihapus','status' => 'success'];
        }else{
            $alert = ['title' => 'Gagal','message' => 'User Gagal dihapus','status' => 'error'];
        }
        return response()->json($alert, Response::HTTP_OK);
    }
    //END User Management

    //User Reset
    public function UserReset()
    {
        // dd(session('_token'));
        $data = array(
            'title' => 'User Reset',
            'menu'  => 'User Reset',
        );

        return view('admin::setting.userreset', $data);
    }

    public function UserReset_TablePegawai()
    {
        $data = User::where('isactive',1)->selectRaw('id,nik,email,privilege_pmb')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nip', function ($d) {
                return $d->nik;
            })
            ->addColumn('nama', function ($d) {
                $cek = PegawaiModel::where('nik',$d->nik)->where('email',$d->email)->select('nama')->first();
                $nama = $cek==null ? '-' : $cek->nama;
                return $nama;
            })
            ->addColumn('email', function ($d) {
                return $d->email;
            })
            ->addColumn('role', function ($d) {
                $cek = MasterGroupModel::where('KodeGroupUser',$d->privilege_pmb)->selectRaw('NamaGroup')->first();
                return $cek==null ? '-' : $cek->NamaGroup;
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->id);
                $cek = PegawaiModel::where('nik',$d->nik)->where('email',$d->email)->select('nama')->first();
                $nama = $cek==null ? '-' : $cek->nama;
                $edit = '<a href="'.route('admin.UserReset.ResetPassword',[$id]).'" class="btn_edit"><i title="Reset Password : '.$nama.'" class="fa fa-key text-orange actiona"></i></a>';
                $delete = '<a href="'.route('admin.UserReset.ResetQA',[$id]).'" class="btn_delete"><i title="Reset Security Question : '.$nama.'" class="fa fa-question-circle text-blue actiona"></i></a>';

                return $edit . '  ' . $delete;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function ResetPassword($params)
    {
        // dd($params);
        $id = decrypt($params);
        $cek = User::where('id',$id)->first();
        if($cek==null){
            $alert = ['title' => 'Gagal','message' => 'User Tidak Terdaftar','status' => 'error'];
            return redirect()->back()->with('alert',$alert);
        }

        $update = User::where('id',$id)->update([
            'password' => Hash::make(defaultpassword()),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session('session')->nip
        ]);

        if($update){
            $logreset = array(
                'email' => $cek->email,
                'token' => session('_token'),
                'activity' => 'Reset Password dari PMB',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => session('session')->nip
            );
            UserResetPasswordModel::insert($logreset);
            $alert = ['title' => 'Berhasil','message' => 'Password Berhasil direset','status' => 'success'];
        }else{
            $alert = ['title' => 'Gagal','message' => 'Password Gagal direset','status' => 'error'];
        }
        return redirect()->back()->with('alert',$alert);
    }

    public function ResetQA($params)
    {
        $id = decrypt($params);
        $cek = User::where('id',$id)->first();
        if($cek==null){
            $alert = ['title' => 'Gagal','message' => 'User Tidak Terdaftar','status' => 'error'];
            return redirect()->back()->with('alert',$alert);
        }

        $update = User::where('id',$id)->update([
            'q1' => null,
            'a1' => null,
            'q2' => null,
            'a2' => null,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session('session')->nip
        ]);

        if($update){
            $logreset = array(
                'email' => $cek->email,
                'token' => session('_token'),
                'activity' => 'Reset QA',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => session('session')->nip
            );
            UserResetPasswordModel::insert($logreset);
            $alert = ['title' => 'Berhasil','message' => 'Pertanyaan Keamanan Berhasil direset','status' => 'success'];
        }else{
            $alert = ['title' => 'Gagal','message' => 'Pertanyaan Keamanan Gagal direset','status' => 'error'];
        }
        return redirect()->back()->with('alert',$alert);
    }
    //END User Reset

    //List Menu Akses
    public function ShowMenu()
    {
        $data = array(
            'title' => 'List Menu',
            'menu'  => 'List Menu',
        );

        return view('admin::setting/listmenu', $data);
    }

    public function table_menu()
    {
        $data = ModulModel::where('MenuAktif','Y')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('modul', function ($d) {
                return $d->modul;
            })
            ->addColumn('menu', function ($d) {
                $nama = $d->menu;
                return $nama;
            })
            ->addColumn('alias', function ($d) {
                return $d->alias;
            })
            ->addColumn('aktif', function ($d) {
                $role = '-';
                $warna = '';
                if($d->MenuAktif=='Y'){
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
                $id = encrypt($d->IdMenu);
                $url = '#';
                $edit   = '<a href="#" data-id="'.$id.'" class="btn_edit"><i title="Edit Menu" class="fa fa-edit text-orange"></i></a>';
                $aktif = '';
                if($d->MenuAktif=='Y'){
                    $url = route('admin.menu.DeleteAktif',[$id,encrypt('N')]);
                    $aktif = '<a href="'.$url.'" class="btn_delete"><i title="Hapus Menu" class="fa fa-trash text-red"></i></a>';
                }else{
                    $url = route('admin.menu.DeleteAktif',[$id,encrypt('Y')]);
                    $aktif  = '<a href="'.$url.'" class="btn_delete"><i title="Aktifkan Menu" class="fas fa-check-circle text-green"></i></a>';
                }
                return $edit.' '.$aktif;
            })
            ->rawColumns(['action','aktif'])
            ->make(true);
    }

    public function GetMenu($params)
    {
        $id = decrypt($params);
        // dd($id);
        $check = ModulModel::where('IdMenu',$id)->where('MenuAktif','Y')->first();

        if($check){
            $data['hasil'] = 1;
            $data['menu'] = $check;
            $data['menuid'] = $params;
        }else{
            $data['hasil'] = 0;
            $data['menu'] = $check;
            $data['menuid'] = $params;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function SaveUpdateMenu(Request $post)
    {
        // dd($post);
        $id = isset($post->menuid) ? decrypt($post->menuid) : null;
        $modul = $post->modul;
        $menu = $post->menu;
        $alias = $post->alias;
        $aktif = $post->aktif;

        $check = ModulModel::where('menu','LIKE','%'.$menu.'%')->first();
        if($check){
            $alert = ['title' => 'Gagal','message' => 'Menu Sudah Ada','status' => 'warning'];
            return redirect()->back()->with('alert',$alert);
        }else{
            $in = false;
            if($id){
                $up = array(
                    'modul' => $modul,
                    'menu' => $menu,
                    'alias' => $alias,
                    'MenuAktif' => $aktif,
                    'updated_at' => date('Y_m-d H:i:s'),
                    'updated_by' => session('session')->nip
                );
                $in = ModulModel::where('IdMenu',$id)->update($up);
                $mesage = 'Menu Berhasil Diperbarui';
            }else{
                $up = array(
                    'modul' => $modul,
                    'menu' => $menu,
                    'alias' => $alias,
                    'MenuAktif' => $aktif,
                    'created_at' => date('Y_m-d H:i:s'),
                    'created_by' => session('session')->nip
                );
                $in = ModulModel::insert($up);
                $mesage = 'Menu Berhasil Disimpan';
            }
            if($in){
                $alert = ['title' => 'Berhasil','message' => $mesage,'status' => 'success'];
            }else{
                $alert = ['title' => 'Gagal','message' => 'Menu Gagal Ditambahkan','status' => 'error'];
            }
            return redirect()->back()->with('alert',$alert);
        }
    }

    public function DeleteMenu($params1,$params2)
    {
        $id = decrypt($params1);
        $aktif = decrypt($params2);
        $cekmenu = ModulModel::where('IdMenu',$id)->selectRaw('modul,menu,alias')->first();
        $cek = GroupUserModel::where('Modul',$cekmenu->modul)->where('Menu',$cekmenu->menu)->first();
        // dd($cek);
        if($cek){
            $alert = ['title' => 'Gagal','message' => 'Menu Sudah Digunakan !','status' => 'error'];
            return redirect()->back()->with('alert',$alert);
        }
        $up = array(
            'MenuAktif' => $aktif,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session('session')->nip
        );

        $update = ModulModel::where('IdMenu',$id)->update($up);

        if($update){
            $alert = ['title' => 'Berhasil','message' => 'Menu Berhasil Diperbarui','status' => 'success'];
        }else{
            $alert = ['title' => 'Gagal','message' => 'Menu Gagal Diperbarui','status' => 'error'];
        }
        return redirect()->back()->with('alert',$alert);

    }
    //END Menu Akses

    //Group User
    public function ShowGroupUser()
    {
        $data = array(
            'title' => 'Master Group User',
            'menu'  => 'Master Data Group User',
        );

        return view('admin::setting.groupuser', $data);
    }

    public function table_groupuser()
    {
        $data = MasterGroupModel::where('isactive',1)->with('groupuser')->orderby('NamaGroup')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('group', function ($d) {
                return $d->NamaGroup;
            })
            ->addColumn('privilege', function ($d) {
                $nama = $d->groupuser->count().' Menu';
                return $nama;
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->KodeGroupUser);
                $url = '#';
                $editpriv  = '<a href="'.route('admin.gruopuser.ShowPrivilege',[$id]).'" class="btn_priv mr-2"><i title="Edit Privilege of '.$d->NamaGroup.'" class="fa fa-eye text-green"></i></a>';
                $editnama   = '<a href="#" data-id="'.$id.'" class="btn_edit mr-2"><i title="Edit '.$d->NamaGroup.' Data" class="fa fa-edit text-orange"></i></a>';
                $del       = '<a href="'.route('admin.gruopuser.DeleteGroupUser',[$id]).'" onclick="return confirm(\'Apakah Anda yakin ingin menghapus Group User '.$d->NamaGroup.'?\')" class="btn_del text-danger"><i title="Hapus '.$d->NamaGroup.'" class="fa fa-trash"></i></a>';

                return $editpriv.' '.$editnama.' '.$del;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function SaveUpdateGroupUser(Request $post)
    {
        $id = isset($post->groupuserid) ? decrypt($post->groupuserid) : null;
        $groupuser = $post->groupuser;

        $check = MasterGroupModel::where('NamaGroup','LIKE','%'.$groupuser.'%')->first();
        if($check){
            $alert = ['title' => 'Gagal','message' => 'Nama Sudah Ada !','status' => 'warning'];
            return redirect()->back()->with('alert',$alert);
        }else{
            $in = false;
            if($id){
                $up = array(
                    'NamaGroup' => $groupuser,
                    'updated_at' => date('Y_m-d H:i:s'),
                    'updated_by' => session('session')->nip
                );
                $in = MasterGroupModel::where('KodeGroupUser',$id)->update($up);
                $mesage = 'Group User Berhasil Diperbarui';
            }else{
                $lastdata = MasterGroupModel::orderBy('KodeGroupUser', 'desc')->first();
                $format = '000';
                $lastid = 1;
                if($lastdata){
                    $lastid = substr($lastdata->KodeGroupUser, 1) + 1;
                }
                $kdgroupuser = 'G' . substr($format, strlen($lastid)) . $lastid;
                // dd($kdgroupuser);
                $up = array(
                    'KodeGroupUser' => $kdgroupuser,
                    'NamaGroup' => $groupuser,
                    'created_at' => date('Y_m-d H:i:s'),
                    'created_by' => session('session')->nip
                );
                $in = MasterGroupModel::insert($up);
                $mesage = 'Group User Berhasil Disimpan';
            }
            if($in){
                $alert = ['title' => 'Berhasil','message' => $mesage,'status' => 'success'];
            }else{
                $alert = ['title' => 'Gagal','message' => 'Group User Gagal Ditambahkan','status' => 'error'];
            }
            return redirect()->back()->with('alert',$alert);
        }
    }

    public function GetGroupUser($params)
    {
        $id = decrypt($params);
        $check = MasterGroupModel::where('KodeGroupUser',$id)->first();

        if($check){
            $data['hasil'] = 1;
            $data['group'] = $check;
            $data['groupid'] = $params;
        }else{
            $data['hasil'] = 0;
            $data['menu'] = $check;
            $data['groupid'] = $params;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function DeleteGroupUser($params)
    {
        $id = decrypt($params);

        // Cek apakah group masih dipakai oleh user aktif
        $cekUser = User::where('privilege_pmb', $id)->where('isactive', 1)->count();
        if ($cekUser > 0) {
            $alert = ['title' => 'Gagal', 'message' => 'Group masih digunakan oleh ' . $cekUser . ' user aktif! Tidak dapat dihapus.', 'status' => 'error'];
            return redirect()->back()->with('alert', $alert);
        }

        // Soft-delete group user
        $update = MasterGroupModel::where('KodeGroupUser', $id)->update([
            'isactive'   => 0,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session('session')->nip
        ]);

        if ($update) {
            LogAktivitas::catat('Group User', 'Hapus Group User', $id, 'Menghapus group user: ' . $id);
            $alert = ['title' => 'Berhasil', 'message' => 'Group User Berhasil Dihapus', 'status' => 'success'];
        } else {
            $alert = ['title' => 'Gagal', 'message' => 'Group User Gagal Dihapus', 'status' => 'error'];
        }

        return redirect()->back()->with('alert', $alert);
    }

    public function ShowPrivilege($params)
    {
        $id = decrypt($params);
        $data = MasterGroupModel::where('KodeGroupUser',$id)->first();
        $modul = ModulModel::where('MenuAktif','Y')->select('modul')->distinct()->orderBy('modul')->get();
        // dd($modul);
        $moduldata = null;

        foreach ($modul as $m)
        {
            $moduldata[$m->modul] = ModulModel::where('modul', $m->modul)->where('MenuAktif','Y')->orderBy(DB::raw('modul, menu','alias'))->get();
        }

        if($data)
        {
            $mod_groupuser = GroupUserModel::where('KodeGroupUser', $id)->select('Modul','Menu','FullAkses')->get();
            $dataku[] = '';
            foreach ($mod_groupuser as $key => $value) {
                $dataku[] = $value->Modul.$value->Menu;
            }

            $view = array(
                'title' => 'Privilege Menu',
                'menu' => 'Privilege Menu',
                'data' => $data,
                'id' => $params,
                'modul'=>$modul,
                'moduldata' => $moduldata,
                'm_modgroup' => $dataku,
                'akses' => $mod_groupuser
            );
            // dd($view);
            return view('admin::setting.privilege', $view);
        }

        return redirect()->back()->with('alert', [
            'title' => 'Error!',
            'message' => 'Data Not Found!',
            'status' => 'error'
        ]);
    }

    function StorePrivilege(Request $request, $params)
    {
        $decrypted = decrypt($params);
        $master = MasterGroupModel::where('KodeGroupUser',$decrypted)->first();
        DB::beginTransaction();

        $del = GroupUserModel::where('KodeGroupUser',$decrypted)->delete();
        $moduldata = ModulModel::where('MenuAktif','Y')->orderBy(DB::raw('modul, menu','alias'))->select('modul','menu')->get();
        // dd($moduldata);
        foreach ($moduldata as $key => $value) {
            $cari = $value->modul.$value->menu;
            // dd($cari);
            if(isset($request->menumod[$cari]) AND $request->actionmod[$cari]!=null){
                $mod = explode("#", $request->menumod[$cari]);

                $inn = array(
                    'KodeGroupUser'=>$decrypted,
                    'Modul'=>$mod[0],
                    'Menu'=>$mod[1],
                    'FullAkses'=>$request->actionmod[$cari],
                    'created_at'=>date('Y-m-d H:i:s'),
                    'created_by'=>session('session')->nip
                );
                // dd($inn);
                $in = GroupUserModel::insert($inn);
                if(!$in){
                    $gagal[] = 'ok';
                }
            }
        }
        if(empty($gagal)){
            DB::commit();
            return redirect(route('admin.gruopuser.show'))->with('alert', [
                'title' => 'Success!',
                'message' => 'Data Has Been Saved Successfully!',
                'status' => 'success'
            ]);

        }else{
            DB::rollBack();
            return redirect(route('gruopuser.show'))->with('alert', [
                'title' => 'Error!',
                'message' => 'Data Failed Saved!',
                'status' => 'error'
            ]);
        }

    }
    //END Group User

}
