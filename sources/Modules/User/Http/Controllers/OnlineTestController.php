<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Master_Jawaban;
use App\Models\MasterData\Master_JenisBerkas;
use App\Models\MasterData\Master_JenisPendaftaran;
use App\Models\MasterData\Master_Soal;
use App\Models\User\Biodata;
use App\Models\User\JawabanTest;
use App\Models\User\Maba;
use App\Models\User\Pendaftaran;
use App\Models\User\Master_Akun;
use App\Models\User\Master_JurusanKuliah;
use App\Models\User\Master_JurusanSekolah;
use App\Models\User\Master_WaktuKuliah;
use Symfony\Component\HttpFoundation\Response;
use Session, Crypt, DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class OnlineTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bioId = decrypt(session('user')->_biodata);
        $soal = Master_Soal::with(['jawaban'])->where('isactive',1)->get();
        // dd($soal);
        $status = Pendaftaran::join('pmb_master_batchpendaftaran as a','pmb_pendaftaran.batch_daftar','=','a.id')
        ->where('pmb_pendaftaran.biodata_id',$bioId)
        ->whereRaw('? BETWEEN a.tglmulai and a.tglselesai',[date('Y-m-d')])
        // ->whereRaw('(bayar_pendaftaran="1" OR bayar_pendaftaran="-1")')
        ->with('batch')
        ->selectRaw('pmb_pendaftaran.*')
        ->first();

        $data = array(
            'title' => 'Online Test',
            'menu' => 'Online Test',
            'soal' => $soal,
            'data' => $status
        );
        return view('user::user.onlinetest.index',$data);
    }

    public function cek_test($params)
    {
        // dd($params);
        $bioId = decrypt(session('user')->_biodata);
        $id = decrypt($params);
        $cek1 = JawabanTest::where('biodata_id',$bioId)->where('kodependaftaran',$id)->exists();
        $cek2 = Pendaftaran::where('KodePendaftaran',$id)->where('biodata_id',$bioId)->where('isactive','0')->exists();
        // dd($bioId,$id);

        if($cek1||$cek2){
            $data['hasil'] = 1;
        }else{
            $data['hasil'] = 0;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function saveTest(Request $post)
    {
        // dd($post);
        $id = decrypt($post->IdPendaftaran);
        $bioId = decrypt(session('user')->_biodata);
        $jawaban = $post->jawaban;
        $ceksoal = Master_Soal::where('isactive',1)->get();
        $cekcek = Pendaftaran::where('KodePendaftaran',$id)->where('biodata_id',$bioId)->select('current_step')->first();
        // if(count($post->jawaban)!=count($cek)){
        //     $status = ['title' => 'Gagal!', 'status' => 'warning', 'message' => 'Jawaban Test Tidak Boleh Kosong ! Silahkan Ulangi Test !'];
        //     return redirect()->back()->with('alert', $status);
        // }

        DB::beginTransaction();

        $count = 0;

        foreach($ceksoal as $key => $q){
            $jawabanpeserta = $jawaban[$key]==null ? null : $jawaban[$key];
            $cek = Master_Jawaban::where('id_jawaban',$jawabanpeserta)->where('isactive',1)->first();
            $idjwb  = isset($cek) ? $cek->id_jawaban : null;
            $jwb    = isset($cek) ? $cek->pilihan : $jawabanpeserta;
            $skor = isset($cek) ? $cek->score : 0;
            $update = array(
              'biodata_id'      => $bioId,
              'kodependaftaran' => $id,
              'kodesoal'        => $q->kode_soal,
              'id_jawaban'      => $idjwb,
              'jawaban_peserta' => $jwb,
              'skor'            => $skor,
              'created_at'      => date('Y-m-d H:i:s'),
            );
            $sum_skor[] = $skor;
            $save = JawabanTest::insert($update);
            $save = true;
            if(!$save){
                $count++;
            }
        }

        $finalskor = array_sum($sum_skor);

        $updt = Pendaftaran::where('KodePendaftaran',$id)->where('biodata_id',$bioId)->update([
            'tgl_test' => now(),
            'validasi_test' => '0',
            'status_test' => '1',
            'nilai_test' => $finalskor,
            'current_step' => $cekcek->current_step+1,
            'updated_at' => now()
        ]);

        if($count==0&&$updt){
            DB::commit();
            $status = ['title' => 'Berhasil!', 'status' => 'success', 'message' => 'Selamat, Anda sudah menyelesaikan test!'];
            return redirect()->route('Dashboard')->with('alert', $status);
        }else{
            DB::rollback();;
            $status = ['title' => 'Gagal !', 'status' => 'error', 'message' => 'Server Error. Silahkan Ulangi Test Anda Dari Awal !'];
            return redirect()->back()->with('alert', $status);
        }

    }
}
