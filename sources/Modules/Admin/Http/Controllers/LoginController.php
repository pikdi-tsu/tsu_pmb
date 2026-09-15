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
        if (Session::has('session') || Auth::check()) {
            return redirect()->route('admin.dashboard')->with('alert', [
                'title'   => 'Info',
                'message' => 'Anda sudah login.',
                'status'  => 'info'
            ]);
        }

        // SSO Block (IP Based)
        $ssoThrottleKey = 'sso-attempt:' . request()->ip();
        $ssoSeconds = 0;
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($ssoThrottleKey, 5)) {
            $ssoSeconds = \Illuminate\Support\Facades\RateLimiter::availableIn($ssoThrottleKey);
            session()->now('error', "SECURITY LOCKDOWN: Tunggu <b id='sso-alert-timer'>$ssoSeconds</b> detik lagi.");
        }

        // Manual PIKDI Block (Session Based)
        $manualSeconds = 0;
        if (session()->has('manual_block_until')) {
            $timeLeft = session('manual_block_until') - now()->timestamp;
            if ($timeLeft > 0) {
                $manualSeconds = $timeLeft;
                session()->now('error', "SECURITY LOCKDOWN: Tunggu <b id='sso-alert-timer'>$manualSeconds</b> detik lagi.");
            } else {
                session()->forget('manual_block_until');
            }
        }

        $data = [
            'title'                   => 'Login Admin PMB',
            'menu'                    => 'Login Admin PMB',
            'app_name'                => config('app.name', 'TSU PMB'),
            'existing_sso_seconds'    => $ssoSeconds,
            'existing_manual_seconds' => $manualSeconds,
        ];

        return view('admin::login.loginform', $data);
    }

    public function loginaction(Request $post)
    {
        $post->validate([
            'identity' => ['required'],
            'password' => ['required'],
        ]);

        $throttleKey = 'manual-login:' . $post->ip();
        $maxAttempts = 5;

        // Anti Brute Force Lockdown
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            session()->put('manual_block_until', now()->addSeconds($seconds)->timestamp);

            return back()
                ->with('error', "SECURITY LOCKDOWN: Terlalu banyak percobaan salah. Tunggu <b id='sso-alert-timer'>$seconds</b> detik lagi.")
                ->with('retry_seconds_manual', $seconds)
                ->withInput($post->only('identity'));
        }

        $identity = $post->identity;
        $isEmail = filter_var($identity, FILTER_VALIDATE_EMAIL);

        $user = null;
        if ($isEmail) {
            $user = User::where('email', $identity)->where('isactive', 1)->first();
        } else {
            $user = User::where('username', $identity)
                ->orWhere('nik', $identity)
                ->where('isactive', 1)
                ->first();
        }

        if ($user && $user->password && Hash::check($post->password, $user->password)) {
            $post->session()->regenerate();
            Auth::login($user);

            // Bersihkan limiter
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            session()->forget('manual_block_until');

            // Setup Sesi Admin PMB via Helper
            \App\Helpers\AdminSessionHelper::setupSession($user);

            return redirect()->intended(route('admin.dashboard'))
                ->with('alert', [
                    'title'   => 'Success',
                    'message' => 'Berhasil Login!',
                    'status'  => 'success'
                ]);
        }

        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);
        $attemptsLeft = \Illuminate\Support\Facades\RateLimiter::retriesLeft($throttleKey, $maxAttempts);

        return back()
            ->with('error', "Username/Email atau Password salah! Sisa percobaan: <b>$attemptsLeft kali</b> lagi.")
            ->withInput($post->only('identity'));
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
