<?php

namespace App\Http\Middleware;

use App\Models\User\Pendaftaran;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Session;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Session::has('session')){
            // Session::flash('alert', ['title' => 'Information', 'message' => 'Silahkan Login Kembali', 'status' => 'warning']);
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect(route('loginadmin'))->with('alert', ['title' => 'Information', 'message' => 'Silahkan Login Kembali', 'status' => 'warning']);
        }
        //Pembayaran Pendaftaran
        $pembayaranpendaftaran = cache()->remember('admin_notif_pembayaranpendaftaran', 30, function () {
            return Pendaftaran::where('current_step', 3)->where('isactive', 1)->count();
        });
        session(['notifapprovalpembayaranpendaftaran' => $pembayaranpendaftaran]);

        //Berkas Khusus
        $berkaskhusus = cache()->remember('admin_notif_berkaskhusus', 30, function () {
            return Pendaftaran::where('current_step', 5)->where('isactive', 1)->count();
        });
        session(['notifapprovalberkaskhusus' => $berkaskhusus]);

        //Test
        $test = cache()->remember('admin_notif_test', 30, function () {
            return Pendaftaran::where('current_step', 7)->where('validasi_test', '0')->where('isactive', 1)->count();
        });
        session(['notifapprovaltest' => $test]);

        //Pembayaran UKT
        $pembayaranukt = cache()->remember('admin_notif_pembayaranukt', 30, function () {
            return Pendaftaran::where('current_step', 9)->where('isactive', 1)->count();
        });
        session(['notifapprovalpembayaranukt' => $pembayaranukt]);
        return $next($request);
    }
}