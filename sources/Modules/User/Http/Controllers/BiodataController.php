<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Master_Batch;
use App\Models\MasterData\Master_JenisBerkas;
use App\Models\MasterData\Master_Kabupaten;
use App\Models\MasterData\Master_Kecamatan;
use App\Models\MasterData\Master_Kelurahan;
use App\Models\MasterData\Master_Provinsi;
use App\Models\MasterData\Master_Berkas;
use App\Models\Parameter;
use App\Models\User\Biodata;
use App\Models\User\BerkasPendaftaran;
use App\Models\User\Pendaftaran;
use App\Models\User\Saudara;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Session, Crypt, DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\masterdata\JenisBerkasController;

class BiodataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bioId = decrypt(session('user')->_biodata);
        $bio = Biodata::where('biodata_id',$bioId)->first();
        $provinsi = Master_Provinsi::where('isactive',1)->get();
        $kabupaten = Master_Kabupaten::where('idprov',$bio->provinsi)->where('isactive',1)->get();
        $kecamatan = Master_Kecamatan::where('idprov',$bio->provinsi)->where('idkab',$bio->kabupaten)->where('isactive',1)->get();
        $now = date('Y-m-d');
        // $batch = Master_Batch::where('isactive',1)->whereRaw('? BETWEEN tglmulai and tglselesai',[$now])->first();
        // $datadaftar = Pendaftaran::where('biodata_id',$bioId)->where('batch_daftar',$batch->id)->first();
        $datadaftar = Pendaftaran::where('biodata_id',$bioId)->orderby('created_at','desc')->first();
        $berkas = Master_JenisBerkas::where('jenis_berkas','Umum')->where('kategori','0')->with('berkas')->first();
        $data = array(
            'title' => 'Biodata',
            'menu' => 'Biodata',
            'bio'  => $bio,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten,
            'kecamatan' => $kecamatan,
            'datadaftar' => $datadaftar,
            'berkas' => $berkas
        );
        return view('user::user.biodata.index',$data);
    }

    public function ChangeKabupaten($prov)
    {
        $kabupaten = Master_Kabupaten::where('idprov',$prov)->where('isactive',1)->get();

        if(count($kabupaten)>0){
            $data['hasil'] = 1;
            $data['kabupaten'] = $kabupaten;
        }else{
            $data['hasil'] = 0;
            $data['kabupaten'] = $kabupaten;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function ChangeKecamatan($prov,$kab)
    {
        $kecamatan = Master_Kecamatan::where('idprov',$prov)->where('idkab',$kab)->where('isactive',1)->get();

        if(count($kecamatan)>0){
            $data['hasil'] = 1;
            $data['kecamatan'] = $kecamatan;
        }else{
            $data['hasil'] = 0;
            $data['kecamatan'] = $kecamatan;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function ChangeKelurahan($prov,$kab,$kec)
    {
        $kelurahan = Master_Kelurahan::where('idprov',$prov)->where('idkab',$kab)->where('idkec',$kec)->where('isactive',1)->get();

        if(count($kelurahan)>0){
            $data['hasil'] = 1;
            $data['kelurahan'] = $kelurahan;
        }else{
            $data['hasil'] = 0;
            $data['kelurahan'] = $kelurahan;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function save_biodata(Request $post)
{
    $jmlsaudara = (int)($post->jumlah_saudara ?? 0);
    $namaSaudara = $post->nama_saudara ?? [];
    if($jmlsaudara != count($namaSaudara)){
        return redirect()->back()->with('alert',['title' => 'Information', 'message' => 'Jumlah saudara harus sama dengan data saudara !', 'status' => 'warning']);
    }

    $cek = Pendaftaran::where('KodePendaftaran', $post->kodedaftar)->first();
    $bioId = decrypt(session('user')->_biodata);
    $cek1 = Biodata::where('biodata_id', $bioId)->where('isactive', 1)->first();
    $parameter = Parameter::where('id', 1)->first();

    // Hapus file lama jika sebelumnya pernah menggunakan sistem 1 file pdf
    if($cek1->berkas_umum != null){
        $path = $parameter->file_umum.'/'.$cek1->berkas_umum;
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }

    DB::beginTransaction();

    try {
        $databio = array(
            'nik' => $post->nik,
            'nokk' => $post->nokk,
            'nama' => $post->nama,
            'nohp' => $post->nohp,
            'jenkel' => $post->jenkel,
            'tempat_lahir' => $post->tempat_lahir,
            'tgl_lahir' => $post->tgl_lahir,
            // 'tinggi_badan' => $post->tinggi_badan,
            // 'berat_badan' => $post->berat_badan,
            'agama' => $post->agama,
            'ukuran_jas' => $post->ukuran_jas,
            'provinsi' => $post->provinsi,
            'kabupaten' => $post->kabupatenkota,
            'kecamatan' => $post->kecamatan,
            'kelurahan' => $post->kelurahan,
            'alamat_lengkap' => $post->alamat_lengkap,
            'rt' => $post->rt,
            'rw' => $post->rw,
            'kodepos' => $post->kodepos,
            'nama_ayah' => $post->nama_ayah,
            'tempat_lahir_ayah' => $post->tempat_lahir_ayah,
            'tgl_lahir_ayah' => $post->tgl_lahir_ayah,
            'status_ayah' => $post->status_ayah,
            'statushidup_ayah' => $post->statushidup_ayah,
            'nohp_ayah' => $post->nohp_ayah,
            'pekerjaan_ayah' => $post->pekerjaan_ayah,
            'penghasilan_ayah' => !empty($post->penghasilan_ayah) ? (preg_replace('/[^0-9]/', '', (string)$post->penghasilan_ayah) ?: 0) : 0,
            'alamat_ayah' => $post->alamat_ayah,
            'nama_ibu' => $post->nama_ibu,
            'tempat_lahir_ibu' => $post->tempat_lahir_ibu,
            'tgl_lahir_ibu' => $post->tgl_lahir_ibu,
            'status_ibu' => $post->status_ibu,
            'statushidup_ibu' => $post->statushidup_ibu,
            'nohp_ibu' => $post->nohp_ibu,
            'pekerjaan_ibu' => $post->pekerjaan_ibu,
            'penghasilan_ibu' => !empty($post->penghasilan_ibu ?? $post->penghasilan_Ibu) ? (preg_replace('/[^0-9]/', '', (string)($post->penghasilan_ibu ?? $post->penghasilan_Ibu)) ?: 0) : 0,
            'alamat_ibu' => $post->alamat_ibu,
            'jumlah_saudara' => $jmlsaudara,
            'nama_sekolah' => $post->nama_sekolah,
            'jenis_sekolah' => $post->jenis_sekolah,
            'provinsi_sekolah' => $post->provinsi_sekolah,
            'kabupaten_sekolah' => $post->kabupatenkota_sekolah,
            'npsn' => $post->npsn,
            'nisn' => $post->nisn,
            'nilai_akhir' => $post->nilai_akhir,
            'berkas_umum' => null, // <- Set null karena sudah dipindah ke tabel detail
            'updated_at' => now()
        );

        // Update Biodata
        Biodata::where('biodata_id', $bioId)->where('isactive', 1)->update($databio);

        // Update Pendaftaran
        Pendaftaran::where('KodePendaftaran', $post->kodedaftar)->update([
            'current_step' => $cek->current_step + 1,
            'tahun_lulus' => $post->tahun_lulus,
            'updated_at' => now()
        ]);

        // Simpan Data Saudara
        $countSaudara = 0;
        if($jmlsaudara > 0){
            Saudara::where('bio_id', $bioId)->delete(); // Langsung delete jika ada (karena diganti baru)
            foreach ($post->nama_saudara as $key => $p) {
                $ups = Saudara::create([ // Gunakan Create jika Eloquent
                    'bio_id' => $bioId,
                    'nama' => $p,
                    'pekerjaan' => $post->pekerjaan_saudara[$key],
                    'status_hidup' => $post->statushidup_saudara[$key],
                    'status_kekerabatan' => $post->statuskekerabatan_saudara[$key],
                ]);
                if($ups) $countSaudara++;
            }
        }

        if ($post->hasFile('berkas_pendaftaran')) {
            // Tangkap data format dari hidden input
            $formatWajibArray = $post->format_wajib;

            foreach ($post->file('berkas_pendaftaran') as $kodeBerkas => $file) {
                
                // JURUS SAKTI: Bersihkan "brks_"
                $cleanIdBerkas = str_replace('brks_', '', $kodeBerkas);

                // Ambil ekstensi file yang diupload user
                $ext = strtolower($file->getClientOriginalExtension());
                
                // --- START VALIDASI CERDAS ---
                // Ambil format wajibnya, hilangkan titiknya (contoh: ".jpg" jadi "jpg")
                $formatWajib = 'pdf'; // Default aman
                if (isset($formatWajibArray[$kodeBerkas])) {
                    $formatWajib = str_replace('.', '', strtolower($formatWajibArray[$kodeBerkas]));
                }

                // Proses pengecekan
                if ($formatWajib == 'jpg' && !in_array($ext, ['jpg', 'jpeg'])) {
                    DB::rollback();
                    return redirect()->back()->with('alert',['title' => 'Gagal!', 'message' => 'Format file Pas Foto wajib berupa JPG/JPEG!', 'status' => 'error']);
                } elseif ($formatWajib == 'pdf' && $ext != 'pdf') {
                    DB::rollback();
                    return redirect()->back()->with('alert',['title' => 'Gagal!', 'message' => 'Format berkas dokumen wajib berupa PDF!', 'status' => 'error']);
                }
                // --- END VALIDASI CERDAS ---

                // Buat Format Nama File
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $cleanName = Str::slug($originalName); 
                $filename = $post->kodedaftar . '_brks_' . $cleanIdBerkas . '_' . $cleanName . '.' . $ext;

                // Cek file lama untuk ditimpa
                $existingBerkas = BerkasPendaftaran::where('kode_daftar', $post->kodedaftar)
                                                    ->where('id_berkas', $cleanIdBerkas)
                                                    ->first();
                if ($existingBerkas) {
                    Storage::delete($parameter->file_umum . '/' . $existingBerkas->nama_berkas);
                    $existingBerkas->delete();
                }

                BerkasPendaftaran::create([
                    'kode_daftar'         => $post->kodedaftar,
                    'id_berkas'           => $cleanIdBerkas,
                    'nama_berkas'         => $filename,
                    'status_berkas'       => null,
                    'keterangan_berkas'   => null,
                    'nik_validasi_berkas' => null,
                    'created_by'          => $post->nama,
                ]);

                // Pindahkan file fisik
                $file->storeAs($parameter->file_umum, $filename);
            }
        }

        if($countSaudara == $jmlsaudara){
            DB::commit();
            return redirect()->route('Dashboard')->with('alert',['title' => 'Berhasil', 'message' => 'Update Biodata Berhasil !', 'status' => 'success']);
        } else {
            DB::rollback();
            return redirect()->back()->with('alert',['title' => 'Error', 'message' => 'Update Biodata Gagal pada data Saudara ! Silahkan Input Kembali', 'status' => 'error']);
        }

    } catch (\Exception $e) {
        DB::rollback();
        // Return error message asli dari try-catch untuk memudahkan debugging
        return redirect()->back()->with('alert',['title' => 'Error', 'message' => 'Sistem Error: ' . $e->getMessage(), 'status' => 'error']);
    }
}
}
