<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Master_Batch;
use App\Models\MasterData\Master_Berkas;
use App\Models\MasterData\Master_JenisBerkas;
use App\Models\MasterData\Master_Kabupaten;
use App\Models\MasterData\Master_Kecamatan;
use App\Models\MasterData\Master_Kelurahan;
use App\Models\MasterData\Master_Provinsi;
use App\Models\MasterData\Master_TarifUKT;
use App\Models\MasterData\Master_Rekomendator;

use App\Models\Assessment\Assessment_Attempts;
use App\Models\Assessment\Assessment_TipeTest;
use App\Models\Assessment\Assessment_Questions;
use App\Models\Assessment\Assessment_Answers;

use App\Models\Parameter;
use App\Models\User\Biodata;
use App\Models\User\Pendaftaran;
use App\Models\User\Saudara;
use App\Models\User\BerkasPendaftaran; 
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Session, Crypt, DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\masterdata\JenisBerkasController;

class HasilPMBController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bioId = decrypt(session('user')->_biodata);
        // $batch = Master_Batch::where('isactive',1)->whereRaw('? BETWEEN tglmulai and tglselesai',[$now])->first();
        // $datadaftar = Pendaftaran::where('biodata_id',$bioId)->orderby('created_at','desc')->first();
        $datadaftar = Pendaftaran::where('biodata_id',$bioId)->orderby('created_at','desc')->where('isactive',1)->with([
        'biodata'=>function($q){
                $q->with('saudara');
            },
            'batch',
            'jalur',
            'jenisbeasiswa' => function($q) {
                $q->with('tingkat');
            },
            'jurusansekolah',
            'prodi1' => function($q) {
                $q->with('jenjang');
            },
            'prodi2' => function($q) {
                $q->with('jenjang');
            },
            'prodi3' => function($q) {
                $q->with('jenjang');
            },
            'waktukuliah', 'bayar', 'jawaban_peserta',
            'jurusan_acc' => function($q) {
                $q->with('jenjang', 'fakultas');
            }
        ])->first();

        // Jika user belum mendaftar sama sekali, antisipasi error dengan membatasi eksekusi lanjutan
        if (!$datadaftar) {
             return redirect()->back()->with('error', 'Data Pendaftaran tidak ditemukan.'); // Sesuaikan dengan logika aplikasimu
        }

        $prodi1 = Master_TarifUKT::where('idbatch', $datadaftar->batch_daftar)->where('idjalur', $datadaftar->jalur_daftar)->where('idjurusan', $datadaftar->prodi1->id ?? null)->where('isactive', 1)->first();
        $prodi2 = Master_TarifUKT::where('idbatch', $datadaftar->batch_daftar)->where('idjalur', $datadaftar->jalur_daftar)->where('idjurusan', $datadaftar->prodi2->id ?? null)->where('isactive', 1)->first();
        $prodi3 = Master_TarifUKT::where('idbatch', $datadaftar->batch_daftar)->where('idjalur', $datadaftar->jalur_daftar)->where('idjurusan', $datadaftar->prodi3->id ?? null)->where('isactive', 1)->first();
        
        $params1 = Parameter::where('id', 1)->first();
        $linkkhusus = null;
        $linkumum = null;

        if ($datadaftar->berkas_khusus != null) {
            $linkkhusus = asset('sources/storage/app/' . $params1->file_khusus . '/' . $datadaftar->berkas_khusus);
        }

        if ($datadaftar->biodata->berkas_umum != null) {
            $linkumum = asset('sources/storage/app/' . $params1->file_umum . '/' . $datadaftar->biodata->berkas_umum);
        }

        $berkasumum = Master_Berkas::where('IdJenis', $datadaftar->jalur->berkas_umum)->get();
        
        // <-- [TAMBAHKAN INI] Ambil data file pendaftar
        $berkasPendaftar = BerkasPendaftaran::where('kode_daftar', $datadaftar->KodePendaftaran)->get(); 

        $provinsi = Master_Provinsi::where('idprov', $datadaftar->biodata->provinsi)->first();
        $kabupaten = Master_Kabupaten::where('idprov', $datadaftar->biodata->provinsi)->where('idkab', $datadaftar->biodata->kabupaten)->first();
        $kecamatan = Master_Kecamatan::where('idprov', $datadaftar->biodata->provinsi)->where('idkab', $datadaftar->biodata->kabupaten)->where('idkec', $datadaftar->biodata->kecamatan)->first();
        $kelurahan = Master_Kelurahan::where('idprov', $datadaftar->biodata->provinsi)->where('idkab', $datadaftar->biodata->kabupaten)->where('idkec', $datadaftar->biodata->kecamatan)->where('idkel', $datadaftar->biodata->kelurahan)->first();

        $provinsi_sekolah = Master_Provinsi::where('idprov', $datadaftar->biodata->provinsi_sekolah)->first();
        $kabupaten_sekolah = Master_Kabupaten::where('idprov', $datadaftar->biodata->provinsi_sekolah)->where('idkab', $datadaftar->biodata->kabupaten_sekolah)->first();
        
        $rekomendator_text = '-';
        if ($datadaftar->rekomendator) {
            $rek = Master_Rekomendator::where('kode_rekomendator', $datadaftar->rekomendator)->first();
            if ($rek) {
                // Tampilan: Nama Lengkap (Kode)
                $rekomendator_text = $rek->nama_rekomendator . ' (' . $rek->kode_rekomendator . ')';
            } else {
                $rekomendator_text = $datadaftar->rekomendator;
            }
        }

        $completedTestIds = Assessment_Attempts::where('kodependaftaran', $datadaftar->KodePendaftaran)
            ->where('status', 'finished') // <-- CATATAN: Pastikan ini sesuai dengan status selesaimu (misal: 'selesai' atau 'finished')
            ->pluck('tipe_test_id');

        // 2. Ambil data nama tes berdasarkan ID tersebut
        $subtesSelesai = Assessment_TipeTest::whereIn('id', $completedTestIds)->get();

        $data = array(
            'title'             => 'Hasil PMB',
            'menu'              => 'Hasil Akhir Pendaftaran Calon Mahasiswa Baru',
            'datadaftar'        => $datadaftar,
            'ukt1'              => $prodi1,
            'ukt2'              => $prodi2,
            'ukt3'              => $prodi3,
            'berkas_khusus'     => $linkkhusus,
            'berkas_umum'       => $linkumum,
            'detailberkas_umum' => $berkasumum,
            'berkasPendaftar'   => $berkasPendaftar,
            'provinsi'          => $provinsi,
            'kabupaten'         => $kabupaten,
            'kecamatan'         => $kecamatan,
            'kelurahan'         => $kelurahan,
            'provinsi_sekolah'  => $provinsi_sekolah,
            'kabupaten_sekolah' => $kabupaten_sekolah,
            'rekomendator'      => $rekomendator_text,
            'subtes_selesai'    => $subtesSelesai
        );
        
        return view('user::user.hasil.index', $data);
    }
}