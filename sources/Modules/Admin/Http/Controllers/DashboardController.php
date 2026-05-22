<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\MasterData\Master_Akun;
use App\Models\User\Pendaftaran;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
// use Session, Crypt, DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        if (Session::has('tmp')) {
            Session::forget('tmp');
        }

        // $total = Master_Akun::where('isactive',1)->count();
        // $validasi = Master_Akun::where('isactive',1)->where('verifikasi_email',1)->count();
        // $aktif = Master_Akun::where('isactive',1)->where('verifikasi_email',1)->count();
        // $blmvalidasi = Master_Akun::where('isactive',1)->where('verifikasi_email',0)->count();

        $jmlpendaftar = Pendaftaran::where('isactive', 1)->count();
        $validasipendaftar = Pendaftaran::where('isactive', 1)->where('konfirm_pendaftaran', '1')->count();
        $blmvalidasipendaftar = Pendaftaran::where('isactive', 1)->where('konfirm_pendaftaran', '0')->count();
        // dd($jmlpendaftar, $validasipendaftar, $blmvalidasipendaftar);
        $data = array(
            'title' => 'Dashboard',
            'menu'  => 'dashboard',
            'total' => $jmlpendaftar,
            'validasi' => $validasipendaftar,
            'aktif' => $jmlpendaftar,
            'blmvalidasi' => $blmvalidasipendaftar
        );
        return view('admin::dashboard/dashboard', $data);
    }
}
