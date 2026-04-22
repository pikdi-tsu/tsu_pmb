<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\GroupUserModel;
use App\Models\Admin\MasterGroupModel;
use App\Models\Admin\ModulModel;
use App\Models\Admin\PegawaiModel;
use App\Models\Admin\User;
use App\Models\Admin\Admin;
use App\Models\Admin\UserResetPasswordModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session, Crypt, DB;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $question_1 = array(
        'What is the first film you watched in theaters',
        'What is your nickname?',
        'What is your grandmothers maiden name?',
        'What is the name of your favorite elementary school teacher?',
        'Where did you meet your partner?',
        'Where is your mothers city born?'
    );

    public $question_2 = array(
        'What is your favorite food?',
        'What is the name of your favorite sports team?',
        'Whats your best hero name?',
        'What is the name of your favorite singer?',
        'Where did your parents city meet?',
        'Where did you first work?'
    );
    public function index()
    {
        if (Session::has('session')) {
            return redirect()->route('admin.dashboard')->with('alert',[
                'title' => 'success!',
                'message' => 'Already login',
                'status' => 'success'
            ]);
        } else {
            $this->checkTimeChance();
            $data = array(
                'title' => 'Login Admin',
                'menu' => 'Login Admin'
            );
            return view('admin::login.loginform',$data);
        }
    }

    public function loginaction(Request $post)
    {
        $credentials = $post->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        // dd($this->loginChance());
        if (Auth::attempt(['email' => $post->email, 'password' => $post->password])) {
            $cek = User::where('email', $post->email)->where('isactive',1)->first();
            if ($cek == null) {
                Session::flash('alert', ['title' => 'Error', 'message' => 'Email belum terdaftar, Kesempatan : ' . $this->loginChance() . ' kali', 'status' => 'error']);
                return redirect()->back();
            }

            $pass = null;

            if (Hash::check($post->password, $cek->password)) {
                $pass = TRUE;
            }

            if (($post->email == $cek->email) && $pass == TRUE) {
                $nama = null;
                $groupuser = GroupUserModel::where('KodeGroupUser', $cek->privilege_pmb)->get();
                $mastergroup = MasterGroupModel::where('KodeGroupUser', $cek->privilege_pmb)->first();

                    $cek2 = PegawaiModel::where('nik', $cek->nik)->first();
                    $nama = $cek2->nama;
                if(Hash::check(defaultpassword(),$cek->password)){
                    $session = array(
                        'tmp_nik'   => $cek->nik,
                        'tmp_nama'  => $nama,
                        'tmp_email' => $post->email,
                        'tmp_role' => $cek->privilege_pmb,
                    );

                    Session::put('tmp', $session);
                    return redirect('admin/NewPassword')->with('alert', ['title' => 'Information', 'message' => 'Silahkan Input Password Baru !', 'status' => 'info']);
                }else{
                    $post->session()->regenerate();
                    $admin                = new Admin();
                    $admin->nip           = $cek->nik;
                    $admin->nama          = $nama;
                    $admin->email         = $cek->email;

                    Session::put('session', $admin);
                    Session::put('namagroup', $mastergroup==null ? null : $mastergroup->NamaGroup);
                    Session::put('groupuser',$groupuser);
                    Session::put('appname','PMB');
                    Session::flash('alert', ['title' => 'Success', 'message' => 'Berhasil Login!', 'status' => 'success']);
                    return redirect()->intended(route('admin.dashboard'));
                }
            } else {
                Session::flash('alert', ['title' => 'Error', 'message' => 'Password Salah, Kesempatan : ' . $this->loginChance() . ' kali', 'status' => 'error']);
                return redirect()->back();
            }
        } else {
            Session::flash('alert', ['title' => 'Gagal', 'message' => 'Silahkan Isi Email dan Password dengan benar, Kesempatan : ' . $this->loginChance() . ' kali', 'status' => 'error']);
            return redirect()->back();
        }
    }

    public function loginChance()
    {
        if(Session::has('login_chance')){
            $login_chance = Session::get('login_chance');

            if ($login_chance['chance'] > 0) {
                $chance = $login_chance['chance'] - 1;
                $data = array(
                    'chance'        => $chance,
                    'time_start'    => time(),
                );
                Session::put('login_chance', $data);

                return $chance;
            } elseif ($login_chance['time_start'] > (15)) {
                Session::forget('login_chance');
            } elseif ($login_chance['chance'] == 0) {
                $data = array(
                    'chance'        => 0,
                    'time_start'    => time(),
                );
                Session::put('login_chance', $data);
            }
        }else{
            $data = array(
                'chance'        => 5,
                'time_start'    => time(),
            );
            Session::put('login_chance', $data);

            return 5;
        }
    }

    public function newPassword()
    {
        $session    = Session::get('tmp');
        $data = array(
            'title'     => 'New Password',

            'action'    => '#',
            'nik'       => isset($session['tmp_nik']) ? $session['tmp_nik'] : '',
            'nama'      => isset($session['tmp_nama']) ? $session['tmp_nama'] : '',
            'role'      => isset($session['tmp_role']) ? $session['tmp_role'] : '',
            'question_1'=> $this->question_1,
            'question_2'=> $this->question_2,
        );
        // dd($data);
        return view('admin::login.newpassword',$data);
    }

    public function newPasswordAction(Request $post)
    {
        $data = array(
            'nik'       => $post->nik,
            'password'  => Hash::make($post->password),
            'q1'        => $post->q_1,
            'a1'        => $post->a_1,
            'q2'        => $post->q_2,
            'a2'        => $post->a_2,
        );

        $cek = User::where('nik',$post->nik)->first();
        // dd($cek);
        if (isset($post->nik) && isset($post->password) && isset($post->q_1) && isset($post->a_1) && isset($post->q_2) && isset($post->a_2)) {
            $update = User::where('nik',$post->nik)->where('isactive',1)->update($data);
            if($update){
                Session::forget('tmp');
                return redirect()->route('loginadmin')->with('alert',['title' => 'Berhasil', 'message' => 'Password Berhasil Diganti, Silahkan Login ulang !', 'status' => 'success']);
            }else{
                return redirect()->route('admin.NewPassword')->with('alert',['title' => 'Gagal', 'message' => 'Password gagal diganti ! Silahkan coba kembali !', 'status' => 'error']);
            }
        }else{
            return redirect()->route('admin.NewPassword')->with('alert',['title' => 'Gagal', 'message' => 'Silahkan Lengkapi Data !', 'status' => 'error']);
        }
    }

    function checkTimeChance(){
        if(Session::has('login_chance')){
            $login_chance = Session::get('login_chance');
            if ($login_chance['chance'] == 0) {
                $chance = date('H:i:s', strtotime('+30 second', $login_chance['time_start']));
                if (time() >= strtotime($chance)) {
                    Session::forget('login_chance');
                }else{
                    Session::put('time_chance', strtotime('+30 second', $login_chance['time_start']) - time());
                }
            }
        }
    }

    public function checkbirthday(Request $get){
        $birthday = $get->birthday;
        $nik      = $get->nik;
        $role     = $get->role;
        $cekrole = MasterGroupModel::where('KodeGroupUser', $role)->first();

        $cek1 = PegawaiModel::where('nik', $nik)->first();
        $tgl = $cek1->tanggallahir;
        if (strtotime($tgl) == strtotime($birthday)) {
            return '1';
        } else {
            return '0';
        }
    }

    public function logout(Request $req)
    {
        // dd($req);
        Auth::logout();

        $req->session()->invalidate();
        $req->session()->regenerateToken();

        Session::flash('alert', ['title' => 'Success', 'message' => 'Anda sudah logout', 'status' => 'success']);
        return redirect(route('loginadmin'));
    }

    public function forgotPassword()
    {
        $data = array(
            'title' => 'Forgot Password',
        );
        return view('admin::login.forgotpassword', $data);
    }

    public function ActionSendLink(Request $post)
    {
        $post->validate(['email' => 'required|email']);

        $cek = User::where('email',$post->email)->first();
        // dd($post,$cek);
        if($cek){
            $email = $cek->email;
            $enc = encrypt($cek->nik);
            $cek1 = PegawaiModel::where('nik',$cek->nik)->where('email',$email)->first();
            UserResetPasswordModel::insert([
                'email'   => $cek->email,
                'token'   => $post->_token,
                'activity'=> 'Forgot Password'
            ]);
            $resetLink = route('admin.ForgotPassword.formreset', $enc);
            $login = route('loginadmin');

            // Kirim email
            // $email, $nama, $data, $jenis, $subject
            $nama = $cek1->nama;
            $data = $nama.'##'.$cek1->nik.'##'.$resetLink.'##'.$login;
            $jenis = 'Reset Password';
            $subject = 'Reset Password Admin PMB';

            $send = SendEmail($email, $nama, $data, $jenis, $subject);
            User::where('email',$post->email)->where('nik',$cek->nik)->update([
                'forgotpassword_sendemail' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $cek->nik
            ]);
            return redirect()->route('loginadmin')->with('alert',['title' => 'success', 'message' => 'Silahkan cek email anda untuk reset password', 'status' => 'success']);
        }else{
            return redirect()->back()->with('alert',['title' => 'Gagal', 'message' => 'Gagal Mengirim Link ! User Tidak Terdaftar', 'status' => 'error']);
        }
    }

    public function FormForgotPassword($params)
    {
        // dd($params);
        $nik = decrypt($params);
        $cek = User::where('nik',$nik)->select('email','nik','forgotpassword_sendemail')->first();

        $data = array(
            'title' => 'Form Forgot Password',
            'data' => $cek,
            'params' => $params
        );
        // dd($data);
        return view('admin::login.form_forgotpassword', $data);
    }

    public function ForgotPasswordAction(Request $post,$params)
    {
        $nik = decrypt($params);
        // dd($post,$params);
        // $cek = User::where('nik',$nik)->where('email',$post->email)->first();
        $arr = array(
            'password' => Hash::make($post->password),
            'forgotpassword_sendemail' => '0',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $nik
        );

        $updt = User::where('nik',$nik)->where('email',$post->email)->update($arr);
        if($updt){
            return redirect()->route('loginadmin')->with('alert',['title' => 'success', 'message' => 'Password Sudah diubah ! Silahkan Login', 'status' => 'success']);
        }else{
            return redirect()->back()->with('alert',['title' => 'Error', 'message' => 'Password gagal diganti !', 'status' => 'error']);
        }
    }
}
