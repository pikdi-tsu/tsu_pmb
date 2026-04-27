<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User\Biodata;
use App\Models\User\Maba;
use App\Models\User\Pendaftaran;
use App\Models\MasterData\Master_Akun;
use App\Models\MasterData\Master_Batch;
use App\Models\MasterData\Master_JenisPendaftaran;
use App\Models\MasterData\Master_JurusanKuliah;
use App\Models\MasterData\Master_JurusanSekolah;
use App\Models\MasterData\Master_Provinsi;
use App\Models\MasterData\Master_Kabupaten;
use App\Models\MasterData\Master_Kecamatan;
use App\Models\MasterData\Master_Kelurahan;
use App\Models\MasterData\Master_WaktuKuliah;
use App\Models\MasterData\Master_Berkas;
use App\Models\MasterData\Master_Beasiswa;
use App\Models\MasterData\Master_KontrakBeasiswa;

use App\Models\Parameter;
use Symfony\Component\HttpFoundation\Response;
use Session, Crypt, DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'title' => 'Beranda'
        );
        return view('user::halamandepannew.index', $data);
    }


    public function programStudi()
    {
        // Variabel $title agar sesuai dengan @section('title', $title) di master Anda
        $title = 'Program Studi - Universitas Tiga Serangkai';

        // 1. Ambil data jurusan dari database beserta relasi jenjangnya (S1, D3, dll)
        $jurusans = Master_JurusanKuliah::with('jenjang')->where('isactive', 1)->get();

        // 2. Kelompokkan data (Grouping)
        // Catatan: Jika Anda memiliki relasi 'fakultas' di model Master_JurusanKuliah, gunakan itu.
        // Jika tidak ada tabel fakultas, kode fallback 'Semua Program Studi' akan digunakan agar web tidak error.
        $groupedJurusans = $jurusans->groupBy(function ($item) {
            return $item->fakultas->nama_fakultas ?? 'Semua Program Studi';
        });

        // 3. Lempar data $groupedJurusans ke view
        return view('user::halamandepannew.programstudi.index', compact('title', 'groupedJurusans', 'jurusans'));
    }

    public function pengumuman()
    {
        // Variabel $title agar sesuai dengan @section('title', $title) di master Anda
        $title = 'Pengumuman - Universitas Tiga Serangkai';

        // Memanggil view sesuai path yang Anda berikan
        return view('user::halamandepannew.pengumuman.index', compact('title'));
    }

    public function detailPengumuman()
    {
        // Variabel $title agar sesuai dengan @section('title', $title) di master Anda
        $title = 'Detail Pengumuman - Universitas Tiga Serangkai';

        // Memanggil view sesuai path yang Anda berikan
        return view('user::halamandepannew.informasipendaftaran.detail', compact('title'));
    }

    public function informasiPendaftaran()
    {
        // Variabel $title agar sesuai dengan @section('title', $title) di master Anda
        $title = 'Informasi Pendaftaran - Universitas Tiga Serangkai';

        // Memanggil view sesuai path yang Anda berikan
        return view('user::halamandepannew.informasipendaftaran.index', compact('title'));
    }

    public function gelombangukt()
    {
        // Variabel $title agar sesuai dengan @section('title', $title) di master Anda
        $title = 'Informasi Pendaftaran - Universitas Tiga Serangkai';

        // Memanggil view sesuai path yang Anda berikan
        return view('user::halamandepannew.gelombangukt.index', compact('title'));
    }

    public function alurbeasiswa()
    {
        \Carbon\Carbon::setLocale('id');
        // Variabel $title agar sesuai dengan @section('title', $title) di master Anda
        $title = 'Alur Pendaftaran Beasiswa - Universitas Tiga Serangkai';
        $jenispendaftaran = Master_JenisPendaftaran::where('is_beasiswa', '1')->get();
        // $batchpendaftaran = Master_Batch::where('isactive', '1')->get();
        // dd($batchpendaftaran);

        $today = Carbon::today();

        // 1. Cek apakah ada batch yang sedang aktif
        $activeBatch = Master_Batch::where('tglmulai', '<=', $today)
            ->where('tglselesai', '>=', $today)
            ->orderBy('tglmulai', 'asc')
            ->first();

        if ($activeBatch) {
            $batch = $activeBatch;
        } else {
            // 2. Kalau tidak ada yang aktif, cek batch terakhir yang sudah lewat
            $lastBatch = Master_Batch::where('tglmulai', '<=', $today)
                ->orderBy('tglmulai', 'desc')
                ->first();

            // 3. Cek batch berikutnya
            $nextBatch = Master_Batch::where('tglmulai', '>', $today)
                ->orderBy('tglmulai', 'asc')
                ->first();

            if ($nextBatch && $today < $nextBatch->tglmulai) {
                // sebelum batch berikutnya mulai → tetap tampilkan batch sebelumnya
                $batch = $lastBatch;
            } else {
                // semua sudah lewat
                $batch = null;
            }
        }

        $batch->tglmulai_format = \Carbon\Carbon::parse($batch->tglmulai)
            ->translatedFormat('j F Y');

        $batch->tglselesai_format = \Carbon\Carbon::parse($batch->tglselesai)
            ->translatedFormat('j F Y');

        $waktukuliah = Master_WaktuKuliah::where('isactive', '1')->first();

        // dd($waktukuliah[0]);

        // Memanggil view sesuai path yang Anda berikan
        return view('user::halamandepannew.alurpendaftaran.beasiswa.index', compact('title', 'jenispendaftaran', 'batch', 'waktukuliah'));
    }

    public function detailbeasiswa($params)
    {
        // dd(decrypt($params));
        $idjenis = decrypt($params);
        // Variabel $title agar sesuai dengan @section('title', $title) di master Anda
        $title = 'Beasiswa Prestasi Akademik - Universitas Tiga Serangkai';
        $jenispendaftaran = Master_JenisPendaftaran::where('id', $idjenis)->where('is_beasiswa', '1')->first();
        $masterberkas = Master_Berkas::where('IdJenis', $jenispendaftaran->berkas_khusus)->where('isactive', '1')->get();
        $masterbeasiswa =  Master_Beasiswa::where('IdJalur', $jenispendaftaran->id)
            ->where('isactive', '1')
            ->get();

        $masterbeasiswa2 =  Master_Beasiswa::where('IdJalur', $jenispendaftaran->id)
            ->whereIn('idtingkat', [2, 3, 4])
            ->where('isactive', '1')
            ->get();

        $masterbeasiswa3 = $masterbeasiswa2->groupBy('idtingkat')->map(function ($items, $key) {

            // khusus idtingkat 4 digabung
            if ($key == 4) {
                return (object) [
                    'idtingkat' => 4,
                    'juara_ke' => 'Juara 1,2,3 Kejuaraan Tingkat Kota/Kabupaten'
                ];
            }

            // selain itu ambil data pertama (karena memang 1 data)
            return $items->first();
        });

        $kontrakbeasiswa = Master_KontrakBeasiswa::where('idjenispendaftaran', $jenispendaftaran->id)->where('isactive', '1')->get();
        // dd($masterbeasiswa3);
        // Memanggil view sesuai path yang Anda berikan
        return view('user::halamandepannew.alurpendaftaran.beasiswa.detailbeasiswa', compact('title', 'jenispendaftaran', 'masterberkas', 'masterbeasiswa', 'masterbeasiswa3', 'kontrakbeasiswa'));
    }

    public function pendaftaranreguler()
    {
        // Variabel $title agar sesuai dengan @section('title', $title) di master Anda
        $title = 'Informasi Pendaftaran - Universitas Tiga Serangkai';

        $masterberkas = Master_Berkas::whereIn('IdJenis', [2, 4, 6])->where('isactive', '1')->get();
        // dd($masterberkas);
        // Memanggil view sesuai path yang Anda berikan
        return view('user::halamandepannew.alurpendaftaran.reguler.index', compact('title', 'masterberkas'));
    }

    public function kontakkami()
    {
        // Variabel $title agar sesuai dengan @section('title', $title) di master Anda
        $title = 'Informasi Pendaftaran - Universitas Tiga Serangkai';

        // Memanggil view sesuai path yang Anda berikan
        return view('user::halamandepannew.kontakkami.index', compact('title'));
    }

    public function downloadbrowsur()
    {
        // Variabel $title agar sesuai dengan @section('title', $title) di master Anda
        $title = 'Informasi Pendaftaran - Universitas Tiga Serangkai';

        // Memanggil view sesuai path yang Anda berikan
        return view('user::halamandepannew.download.index', compact('title'));
    }

    public function getbrowsur()
    {
        $filename = 'Brosur PMB TSU 2026_0001.pdf';
        $path = storage_path('app/Browsur/' . $filename);
        // dd('masuk', $path);
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);

        // $filePath = 'brosur/brosur.pdf';

        // if (Storage::exists($filePath)) {
        //     return Storage::download($filePath, 'Brosur-TSU.pdf');
        // }

        // abort(404, 'File tidak ditemukan');
    }

    public function jalurPendaftaran()
    {
        $title = 'Jalur Pendaftaran - Universitas Tiga Serangkai';

        $batches = Master_Batch::where('isactive', '1')
            ->orderBy('tglmulai', 'asc')
            ->get();

        // Ganti 'status' menjadi 'isactive'
        $jenis_pendaftaran = Master_JenisPendaftaran::where('isactive', '1')->get();

        // (Hapus baris dd($jenis_pendaftaran); yang tadi kita tambahkan)

        return view('user::halamandepannew.jalurpendaftaran.index', compact('title', 'batches', 'jenis_pendaftaran'));
    }

    public function Register()
    {
        $param = Parameter::where('id', 1)->first();
        $provinsi = Master_Provinsi::where('isactive', 1)->get();

        $data = array(
            'title' => 'Register',
            'parameter' => $param,
            'provinsi' => $provinsi
        );
        // dd($data);
        return view('user::login.register_form', $data);
    }

    public function jurusan($params)
    {
        $cek = null;
        // dd($params);
        if ($params == 1) {
            $cek = Master_JurusanKuliah::whereIn('idjurusansekolah', ['1'])->with('jenjang')->where('isactive', 1)->get();
        } else {
            $cek = Master_JurusanKuliah::whereNotIn('idjurusansekolah', ['1'])->with('jenjang')->where('isactive', 1)->get();
        }

        if ($cek) {
            $data['hasil'] = 1;
            $data['jurusan'] = $cek;
        } else {
            $data['hasil'] = 0;
            $data['jurusan'] = $cek;
        }

        return response()->json($data, Response::HTTP_OK);
    }

    public function getKabupaten($params)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        // dd($params);
        $cek = Master_Kabupaten::where('idprov', $params)->where('isactive', 1)->get();

        if (count($cek) > 0) {
            $data['hasil'] = 1;
            $data['kab'] = $cek;
        } else {
            $data['hasil'] = 0;
            $data['kab'] = $cek;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function StoreRegister(Request $post)
    {
        $check_email = Master_Akun::where('email', $post->email)->count();

        if ($check_email > 0) {
            return redirect()->back()->with('alert', ['title' => 'Gagal', 'message' => 'Email sudah dipakai', 'status' => 'error']);
        }

        $data_akun = array(
            'email'    => $post->email,
            'password' => Hash::make($post->password),
            'created_at'      => date('Y-m-d H:i:s'),
        );
        $akun = Master_Akun::insert($data_akun);
        $cekAkun = Master_Akun::orderby('akun_id', 'desc')->first();

        $data_biodata = array(
            'akun'            => $cekAkun->akun_id,
            'nik'             => $post->nik,
            'nama'            => $post->nama,
            'nohp'            => $post->nohp,
            'provinsi'        => $post->provinsi,
            'kabupaten'       => $post->kabupaten,
            'created_at'      => date('Y-m-d H:i:s'),
        );
        $biodata = Biodata::insert($data_biodata);
        $cekBio = Biodata::where('akun', $cekAkun->akun_id)->first();
        // Email
        $data = array(
            'biodata'   => $cekBio,
            'akun'      => $cekAkun,
        );

        if ($akun && $biodata) {
            Mail::send('user::login/register_email', $data, function ($message) use ($cekBio, $cekAkun) {
                $message->subject('Aktivasi Akun - ' . $cekBio->nama);
                $message->to($cekAkun->email);
            });
        }
        // Email

        return redirect()->route('register.sukses')->with('alert', ['title' => 'Berhasil', 'message' => 'Registrasi Berhasil', 'status' => 'success']);
    }

    public function SuksesRegist()
    {
        $data = array('title' => 'Registrasi');
        return view('user::login.suksesRegis', $data);
    }

    public function VerifikasiAkun($params)
    {
        $akun_id = decrypt($params);
        $akun = Master_Akun::find($akun_id);
        if ($akun) {
            Master_Akun::where('akun_id', $akun_id)->update([
                'verifikasi_email' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $status = TRUE;
        } else {
            $status = FALSE;
        }

        return view('user::login.suksesAktivasiEmail', ['status' => $status, 'title' => 'Aktivasi Akun']);
    }

    public function LoginForm()
    {
        $data = array('title' => 'Login PMB');
        return view('user::login.login', $data);
    }

    public function LoginAction(Request $post)
    {
        $email      = $post->email;
        $password   = $post->password;

        $akun = Master_Akun::with('biodata')->where('email', $email)->first();
        // dd($akun);
        if ($akun) {
            if (Hash::check($password, $akun->password)) {
                if ($akun->verifikasi_email == 1) {
                    $user                = new Maba();
                    $user->email         = $akun->email;
                    $user->nama          = $akun->biodata->nama;
                    // $user->jenis_kelamin = $akun->biodata->biodata_jenis_kelamin;
                    $user->foto          = $akun->biodata->photo;
                    $user->_biodata      = Crypt::encrypt($akun->biodata->biodata_id);

                    Session::put('user', $user);
                    Session::flash('alert', ['title' => 'Berhasil', 'message' => 'Selamat datang', 'status' => 'success']);
                    return redirect(route('Dashboard'));
                } else {
                    // Session::flash('alert', 'sweetAlert("warning", "Silahkan verifikasi E-mail sebelum Login")');
                    return redirect()->back()->with('alert', ['title' => 'Warning', 'message' => 'Silahkan verifikasi E-mail sebelum Login', 'status' => 'warning']);
                }
            } else {
                // Session::flash('alert', 'sweetAlert("error", "Email / Password salah")');
                return redirect()->back()->with('alert', ['title' => 'Error', 'message' => 'Email / Password salah', 'status' => 'error']);
            }
        } else {
            // Session::flash('alert', 'sweetAlert("error", "Akun tidak terdaftar")');
            return redirect()->back()->with('alert', ['title' => 'Error', 'message' => 'Akun tidak terdaftar', 'status' => 'error']);
        }
    }

    public function Dashboard()
    {
        $bioId = decrypt(session('user')->_biodata);
        $bio = Biodata::where('biodata_id', $bioId)->first();
        $akun = Master_Akun::where('akun_id', $bio->akun)->select('akun_id', 'verifikasi_email')->first();
        $now = date('Y-m-d');
        $batch = Master_Batch::where('isactive', 1)->whereRaw('? BETWEEN tglmulai and tglselesai', [$now])->first();
        // dd($batch);
        $pendaftaran = null;
        if ($batch) {
            $pendaftaran = Pendaftaran::where('batch_daftar', $batch->id)->where('biodata_id', $bioId)->first();
        }

        $data = array(
            'title' => 'Dashboard',
            'menu'  => 'Dashboard',
            'biodata' => $bio,
            'akun' => $akun,
            'batch' => $batch,
            'daftar' => $pendaftaran
        );
        // dd($data);
        return view('user::user.dashboard', $data);
    }

    public function edit()
    {
        $data = array(
            'title' => 'Ganti Password',
            'menu' => 'Ganti Password'
        );
        return view('user::user.changepassword', $data);
    }

    public function update(Request $post)
    {
        $oldpass = $post->oldpass;
        $newpass = $post->newpass;
        $newpass2 = $post->newpass2;
        // dd(preg_match('/\d/', $$oldpass));
        if ((preg_match('/[[:punct:]]/', $oldpass)) == 1 || (preg_match('/[A-Z]/', $oldpass)) == 0 || (preg_match('/\d/', $oldpass)) == 0 || strlen($oldpass) < 8) {
            Session::flash('alert', ['title' => 'Gagal', 'message' => 'Pergantian Password Gagal ! Silahkan Baca Note !', 'status' => 'error']);
            return redirect()->back();
        }

        if ((preg_match('/[[:punct:]]/', $newpass)) == 1 || (preg_match('/[A-Z]/', $newpass)) == 0 || (preg_match('/\d/', $newpass)) == 0 || strlen($newpass) < 8) {
            Session::flash('alert', ['title' => 'Gagal', 'message' => 'Pergantian Password Gagal ! Silahkan Baca Note !', 'status' => 'error']);
            return redirect()->back();
        }

        if ((preg_match('/[[:punct:]]/', $newpass2)) == 1 || (preg_match('/[A-Z]/', $newpass2)) == 0 || (preg_match('/\d/', $newpass2)) == 0 || strlen($newpass2) < 8) {
            Session::flash('alert', ['title' => 'Gagal', 'message' => 'Pergantian Password Gagal ! Silahkan Baca Note !', 'status' => 'error']);
            return redirect()->back();
        }

        if ($newpass != $newpass2) {
            Session::flash('alert', ['title' => 'Gagal', 'message' => 'Password Baru Tidak Sama ! Silahkan Ulangi !', 'status' => 'error']);
            return redirect()->back();
        }

        $BioId = decrypt(session('user')->_biodata);
        $cekBio = Biodata::where('biodata_id', $BioId)->where('isactive', 1)->first();
        $email = session('user')->email;

        $cek = Master_Akun::where('akun_id', $cekBio->akun)->where('email', $email)->where('isactive', 1)->first();
        // dd($cek);
        // dd(Hash::check($oldpass,$cek->password));
        if ($cek) {
            if (Hash::check($newpass, $cek->password)) {
                Session::flash('alert', ['title' => 'Gagal', 'message' => 'Password Baru Tidak Boleh Sama Seperti Password Lama !', 'status' => 'error']);
                return redirect()->back();
            }
            if (Hash::check($oldpass, $cek->password)) {
                $user = array(
                    'password' => Hash::make($newpass),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $up1 = Master_Akun::where('akun_id', $cekBio->akun)->where('email', $email)->where('isactive', 1)->update($user);

                if ($up1) {
                    Session::flash('alert', ['title' => 'Berhasil', 'message' => 'Password Berubah', 'status' => 'success']);
                    return redirect()->back();
                } else {
                    Session::flash('alert', ['title' => 'Gagal', 'message' => 'Password Tidak Berubah !', 'status' => 'error']);
                    return redirect()->back();
                }
            } else {
                Session::flash('alert', ['title' => 'Gagal', 'message' => 'Password Salah !', 'status' => 'error']);
                return redirect()->back();
            }
        } else {
            Session::flash('alert', ['title' => 'Gagal', 'message' => 'User Tidak Ada ! Silahkan Hubungi Team IT', 'status' => 'error']);
            return redirect()->back();
        }
    }

    public function ResetPassword()
    {
        $data = array(
            'title' => 'Reset Password'
        );
        return view('user::login.forgotpassword', $data);
    }

    public function ResetPasswordAction(Request $post)
    {
        // dd($post);
        // dd(Str::random(10));
        $akun = Master_Akun::where('email', $post->email)
            ->whereHas('biodata')
            ->first();
        // dd($akun);
        // $akun = Master_Akun::where('akun_email', $post->akun_email)
        // ->whereHas('biodata', function($q) use($post){
        //     $q->where('biodata_tanggal_lahir', $post->biodata_tanggal_lahir);
        // })
        // ->first();
        if ($akun) {
            $biodata    = Biodata::where('akun', $akun->akun_id)->first();
            $password   = Str::random(10);

            $resetpass = Hash::make($password);

            $data = array(
                'akun'                  => $akun,
                'biodata'               => $biodata,
                'password_plaintext'    => $password,
            );

            try {
                Mail::send('user::login/forgot_password_email', $data, function ($message) use ($biodata, $akun) {
                    $message->subject('Reset Password - ' . $biodata->nama);
                    $message->to($akun->email);
                });
                Master_Akun::where('email', $post->email)->whereHas('biodata')->update([
                    'password' => $resetpass,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                return redirect(route('LoginPMB'))->with('alert', ['title' => 'Berhasil', 'message' => 'Password baru dikirim melalui email', 'status' => 'success']);
            } catch (\Exception $e) {
                return redirect()->back()->with('alert', ['title' => 'Gagal', 'message' => 'Error : ' . $e, 'status' => 'error']);
            }
        } else {
            return redirect(route('ResetPassword'))->with('alert', ['title' => 'Gagal', 'message' => 'Data tidak ditemukan', 'status' => 'error']);
        }
    }

    public function logout()
    {
        Session::flush();
        // Session::flash('alert', ['title' => 'Logout', 'message' => 'Berhasil logout', 'status' => 'success']);
        return redirect(route('indexing'));
    }
}
