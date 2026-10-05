<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Master_Batch;
use App\Models\MasterData\Master_Beasiswa;
use App\Models\MasterData\Master_JenisBerkas;
use App\Models\MasterData\Master_JenisPendaftaran;
use App\Models\MasterData\Master_JurusanKuliah;
use App\Models\MasterData\Master_JurusanSekolah;
use App\Models\MasterData\Master_TarifUKT;
use App\Models\MasterData\Master_WaktuKuliah;
use App\Models\MasterData\Master_Rekomendator;
use App\Models\Transaksi;
use App\Models\TransaksiHistory;
use App\Models\User\Biodata;
use App\Models\User\Maba;
use App\Models\User\Pendaftaran;
use Symfony\Component\HttpFoundation\Response;
use Session, Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = date('Y-m-d');
        $batch = Master_Batch::where('isactive', 1)->whereRaw('? BETWEEN tglmulai and tglselesai', [$now])->get();
        $sekolah = Master_JurusanSekolah::where('isactive', 1)->selectRaw('id,sekolah,jurusan_sekolah')->get();
        $waktu = Master_WaktuKuliah::where('isactive', 1)->selectRaw('id,waktu')->get();
        $bks = Master_JenisBerkas::with('berkas')->get();
        $data = array(
            'title' => 'Pendaftaran',
            'menu' => 'Pendaftaran',
            'batch' => $batch,
            'sekolah' => $sekolah,
            'waktu' => $waktu
        );
        return view('user::user.pendaftaran.index', $data);
    }

    public function tabelPendaftaran()
    {
        $bioId = decrypt(session('user')->_biodata);

        $data = Pendaftaran::where('biodata_id', $bioId)->with([
            'biodata',
            'batch',
            'jalur',
            'jenisbeasiswa',
            'jurusansekolah',
            'prodi1' => function ($q) {
                $q->with('jenjang');
            },
            'prodi2' => function ($q) {
                $q->with('jenjang');
            },
            'prodi3' => function ($q) {
                $q->with('jenjang');
            },
            'waktukuliah'
        ])->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama', function ($d) {
                return $d->biodata->nama;
            })
            ->addColumn('noreg', function ($d) {
                return $d->KodePendaftaran;
            })
            ->addColumn('batch', function ($d) {
                return $d->batch->nama_batch;
            })
            ->addColumn('jalur', function ($d) {
                $nama = $d->jalur->jenis_pendaftaran;
                return $nama;
            })
            ->addColumn('prodi1', function ($d) {
                $nama = $d->prodi1->jenjang->jenjang . '-' . $d->prodi1->jurusan;
                return $nama;
            })
            ->addColumn('prodi2', function ($d) {
                return $d->prodi2 ? $d->prodi2->jenjang->jenjang . '-' . $d->prodi2->jurusan : '-';
            })
            ->addColumn('prodi3', function ($d) {
                return $d->prodi3 ? $d->prodi3->jenjang->jenjang . '-' . $d->prodi3->jurusan : '-';
            })
            ->addColumn('rekomendator', function ($d) {
                return $d->rekomendator != null ? $d->rekomendator : '-';
            })
            ->addColumn('status', function ($d) {
                $role = '-';
                $warna = '';
                if ($d->isactive == 1) {
                    if ($d->konfirm_pendaftaran == 1) {
                        $role = 'Sudah Konfirmasi';
                        $warna = 'success';
                    } else {
                        $role = 'Belum Konfirmasi';
                        $warna = 'warning';
                    }
                } else {
                    $role = 'Mengundurkan Diri';
                    $warna = 'danger';
                }
                $show = '<span class="badge bg-' . $warna . '">' . $role . '</span>';
                return $show;
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->KodePendaftaran);

                $aktif = '';
                $detail = '';
                $konfirm = '';
                $edit = '';
                if ($d->isactive == 1) {
                    // if($d->konfirm_pendaftaran==0){
                    if ($d->current_step == 1) {
                        $aktif = '<a href="#" class="btn_delete" data-id="' . $id . '"><i title="Hapus Pendaftaran" class="fa fa-trash text-red"></i></a>';
                        $konfirm = '<a href="#" data-id="' . $id . '" class="btn_konfirm"><i title="Konfirmasi Pendaftaran" class="fas fa-check-circle text-green"></i></a>';
                        $edit   = '<a href="#" data-id="' . $id . '" class="btn_edit"><i title="Edit" class="fa fa-edit text-orange"></i></a>';
                    }
                }
                // else{
                //     $aktif  = '<a href="#" class="btn_delete" data-id="'.$id.'" data-status="'.encrypt('1').'"><i title="Aktifkan" class="fas fa-check-circle text-green"></i></a>';
                // }
                $detail = '<a href="#" data-id="' . $id . '" class="btn_detail"><i title="Detail" class="fa fa-info-circle"></i></a>';

                return $detail . ' ' . $edit . ' ' . $aktif . ' ' . $konfirm;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function showJalur($params)
    {
        if (!headers_sent()) {
            @header("Access-Control-Allow-Origin: *");
            @header("Access-Control-Allow-Headers: *");
        }

        $id = $params;
        $now = date('Y-m-d');
        $cek1 = Master_Batch::where('id', $id)->where('isactive', 1)->whereRaw('? BETWEEN tglmulai and tglselesai', [$now])->first();
        // dd($cek1);
        if ($cek1) {
            $jalur = [];
            $cek3 = Master_JenisPendaftaran::where('isactive', 1)->get();

            foreach ($cek3 as $q) {
                $cek2 = Master_TarifUKT::where('idbatch', $id)->where('idjalur', $q->id)->where('isactive', 1)->exists();
                if ($cek2) {
                    $jalur[] = $q;
                }
            }
            if (count($jalur) > 0) {
                $data['hasil'] = 1;
                $data['jalur'] = $jalur;
            } else {
                $data['hasil'] = -1;
                $data['jalur'] = null;
            }
        } else {
            $data['hasil'] = 0;
            $data['jalur'] = null;
        }

        return response()->json($data, Response::HTTP_OK);
    }

    public function showBeasiswa($params)
    {
        if (!headers_sent()) {
            @header("Access-Control-Allow-Origin: *");
            @header("Access-Control-Allow-Headers: *");
        }

        $cek1 = Master_Beasiswa::where('idjalur', $params)->selectRaw('id,jenis_beasiswa')->get();
        $data['bea'] = $cek1;
        return response()->json($data, Response::HTTP_OK);
    }

    public function detailbeasiswa($params)
    {
        if (!headers_sent()) {
            @header("Access-Control-Allow-Origin: *");
            @header("Access-Control-Allow-Headers: *");
        }

        $cek1 = Master_Beasiswa::where('id', $params)->with('tingkat')->selectRaw('id,idtingkat,juara_ke')->first();
        $data['bea'] = $cek1;
        return response()->json($data, Response::HTTP_OK);
    }
    public function showProdi($batch, $jalur, $jurusansekolah)
    {
        if (!headers_sent()) {
            @header("Access-Control-Allow-Origin: *");
            @header("Access-Control-Allow-Headers: *");
        }

        $idbatch = $batch;
        $idjalur = $jalur;
        $idjurusansekolah = $jurusansekolah;

        // memfilter data menggunakan parameter $idjurusansekolah
        // FIND_IN_SET digunakan agar tetap aman jika 1 Prodi menerima banyak Jurusan Sekolah (misal datanya "1,2,3")
        // orWhereNull digunakan jika ada Prodi Umum yang menerima SEMUA jurusan (idjurusansekolahnya kosong)
        $cek1 = Master_JurusanKuliah::selectRaw('id,KodeJurusan,idfakultas,idjenjang,idjurusansekolah,jurusan')
            ->with('jenjang')
            ->where('isactive', 1)
            ->get();
        // ----------------------------------------

        $prodi = [];
        foreach ($cek1 as $q) {
            // Filter prodi ini ada harganya (UKT-nya sudah disetting) di batch & jalur yang dipilih
            $cek2 = Master_TarifUKT::where('idbatch', $idbatch)->where('idjalur', $idjalur)->where('idjurusan', $q->id)->exists();
            if ($cek2) {
                $prodi[] = $q;
            }
        }

        // Ambil data Fakultas pakai Query Builder agar aman
        $fakultas = DB::table('pmb_master_fakultas')->select('KodeFakultas', 'namafakultas')->where('isactive', '1')->get();

        if (count($prodi) > 0) {
            $data['hasil'] = 1;
            $data['jurusan'] = $prodi;
            $data['fakultas'] = $fakultas;
        } else {
            $data['hasil'] = 0;
            $data['jurusan'] = $prodi;
            $data['fakultas'] = null;
        }

        return response()->json($data, Response::HTTP_OK);
    }

    public function cariRekomendator(Request $request)
    {
        $search = $request->q;

        $query = Master_Rekomendator::where('isactive', 1)
            ->select('kode_rekomendator', 'nama_rekomendator');

        if ($search) {
            $query->where('nama_rekomendator', 'like', '%' . $search . '%');
        }

        $data = $query->orderBy('nama_rekomendator', 'asc')->limit(15)->get();
        return response()->json($data);
    }

    public function StoreDaftar(Request $post)
    {
        if (!headers_sent()) {
            @header("Access-Control-Allow-Origin: *");
            @header("Access-Control-Allow-Headers: *");
        }
        // dd($post);
        if ($post->IdPendaftaran == null) {
            $data = $this->save($post);
        } else {
            $data = $this->update($post);
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function showDaftar($params)
    {
        $id = decrypt($params);
        $bioId = decrypt(session('user')->_biodata);

        $cek1 = Pendaftaran::where('KodePendaftaran', $id)->where('biodata_id', $bioId)->where('isactive', 1)->with([
            'biodata',
            'batch',
            'jalur' => function ($q) {
                $q->with([
                    'berkasumum' => function ($q) {
                        $q->with('berkas');
                    },

                    'berkaskhusus' => function ($q) {
                        $q->with('berkas');
                    }
                ]);
            },
            'jenisbeasiswa' => function ($q) {
                $q->with('tingkat');
            },
            'jurusansekolah',
            'prodi1' => function ($q) {
                $q->with('jenjang');
            },
            'prodi2' => function ($q) {
                $q->with('jenjang');
            },
            'prodi3' => function ($q) {
                $q->with('jenjang');
            },
            'waktukuliah'
        ])->first();
        $prodi1 = Master_TarifUKT::where('idbatch', $cek1->batch_daftar)->where('idjalur', $cek1->jalur_daftar)->where('idjurusan', $cek1->prodi1->id)->where('isactive', 1)->first();
        $prodi2 = Master_TarifUKT::where('idbatch', $cek1->batch_daftar)->where('idjalur', $cek1->jalur_daftar)->where('idjurusan', $cek1->prodi2->id)->where('isactive', 1)->first();

        $prodi3 = null;
        if ($cek1->prodi3) {
            $prodi3 = Master_TarifUKT::where('idbatch', $cek1->batch_daftar)->where('idjalur', $cek1->jalur_daftar)->where('idjurusan', $cek1->prodi3->id)->where('isactive', 1)->first();
        }

        // --- TAMBAHAN FORMAT REKOMENDATOR UNTUK DETAIL ---
        $rekomendator_text = '-';
        if ($cek1 && $cek1->rekomendator) {
            $rek = Master_Rekomendator::where('kode_rekomendator', $cek1->rekomendator)->first();
            if ($rek) {
                // Tampilan: Nama Lengkap (Kode)
                $rekomendator_text = $rek->nama_rekomendator . ' (' . $rek->kode_rekomendator . ')';
            } else {
                $rekomendator_text = $cek1->rekomendator;
            }
        }

        if ($cek1) {
            $data['hasil'] = 1;
            $data['daftar'] = $cek1;
            $data['IdDaftar'] = $params;
            $data['ukt1'] = $prodi1;
            $data['ukt2'] = $prodi2;
            $data['ukt3'] = $prodi3;
            $data['rekomendator'] = $rekomendator_text;
        } else {
            $data['hasil'] = 0;
            $data['daftar'] = $cek1;
            $data['IdDaftar'] = null;
            $data['ukt1'] = $prodi1;
            $data['ukt2'] = $prodi2;
            $data['ukt3'] = $prodi3;
            $data['rekomendator'] = '-';
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function save($post)
    {
        try {
            $batch = $post->batch;
            $jalur = $post->jalur;
            $beasiswa = $post->beasiswa;
            $tahunLulusUser = $post->tahunlulus;
            $jurusansekolah = $post->jurusansekolah;
            $prodi1 = $post->prodi1;
            $prodi2 = $post->prodi2;
            $prodi3 = $post->prodi3;
            // $waktukuliah = $post->waktukuliah;
            $rekomendator = $post->rekomendator;
            $bioId = decrypt(session('user')->_biodata);

            // --- VALIDASI FAKULTAS BERBEDA ---
            $fakultas1 = Master_JurusanKuliah::where('KodeJurusan', $prodi1)->value('idfakultas');
            $fakultas2 = Master_JurusanKuliah::where('KodeJurusan', $prodi2)->value('idfakultas');
            $fakultas3 = Master_JurusanKuliah::where('KodeJurusan', $prodi3)->value('idfakultas');

            if ($fakultas1 != 'F003') {
                if (($fakultas1 == $fakultas2 && $fakultas1 != null) || ($fakultas1 == $fakultas3 && $fakultas1 != null) || ($fakultas2 == $fakultas3 && $fakultas2 != null)) {
                    return ['title' => 'Peringatan', 'message' => 'Setiap Pilihan Program Studi harus berasal dari Fakultas yang berbeda!', 'status' => 'warning'];
                }
            } else {
                if (($fakultas1 != $fakultas2 && $fakultas1 != null) || ($fakultas1 != $fakultas3 && $fakultas1 != null) || ($fakultas2 != $fakultas3 && $fakultas2 != null)) {
                    return ['title' => 'Peringatan', 'message' => 'Jika Pilihan 1 Fakultas Vokasi, Maka Pilihan Selanjutnya Harus Vokasi Juga!', 'status' => 'warning'];
                }
            }


            $cek = Pendaftaran::where('biodata_id', $bioId)->where('batch_daftar', $batch)->exists();
            if ($cek) {
                return ['title' => 'Information', 'message' => 'Anda Sudah Daftar Pada Batch ini ! Silahkan Daftar pada Batch Berikutnya', 'status' => 'warning'];
            }

            DB::beginTransaction();
            $cekbiayadaftar = Master_JenisPendaftaran::where('id', $jalur)->where('isactive', 1)->first();
            $tahunSekarang = date('Y');

            // --- PERBAIKAN GENERATOR KODE PENDAFTARAN ---
            // Memastikan panjang batch dan jalur konsisten 2 digit (misal: 06, 11)
            $kd1 = str_pad($batch, 2, '0', STR_PAD_LEFT);
            $kd2 = str_pad($jalur, 2, '0', STR_PAD_LEFT);

            // Mencari kode terakhir berdasarkan format yang baku
            $lastRecord = Pendaftaran::whereYear('tgl_daftar', $tahunSekarang)
                ->where('KodePendaftaran', 'like', $tahunSekarang . $kd1 . $kd2 . '%')
                ->orderBy('KodePendaftaran', 'desc')
                ->first();

            if ($lastRecord) {
                // Ambil 4 digit terakhir dengan aman karena panjang string awal sudah pasti
                $lastSequence = (int) substr($lastRecord->KodePendaftaran, -4);
                $urut = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
            } else {
                // Jika belum ada pendaftar sama sekali di tahun/batch/jalur ini
                $urut = '0001';
            }

            $kode = $tahunSekarang . $kd1 . $kd2 . $urut;

            if ($post->waktukuliah == 'SORE') {
                $kelaspagi = '0';
                $kelassore = '1';
            } elseif ($post->waktukuliah == 'PAGI') {
                $kelaspagi = '1';
                $kelassore = '0';
            }
            // dd($post->waktukuliah, $kelaspagi, $kelassore);
            // ---------------------------------------------

            $data_daftar = array(
                'KodePendaftaran'   => $kode,
                'biodata_id'        => $bioId,
                'bayar_pendaftaran' => $cekbiayadaftar ? ($cekbiayadaftar->biaya_pendaftaran == 1 ? '0' : '-1') : '0',
                'tgl_daftar'        => date('Y-m-d H:i:s'),
                'batch_daftar'      => $batch,
                'jalur_daftar'      => $jalur,
                'beasiswa'          => $beasiswa,
                'tahun_lulus'       => $tahunLulusUser,
                'jurusan_sekolah'   => $jurusansekolah,
                'pilihan1'          => $prodi1,
                'pilihan2'          => $prodi2,
                'pilihan3'          => $prodi3,
                // 'waktu_kuliah'      => $waktukuliah,
                'rekomendator'      => $rekomendator,
                'bayar_ukt'         => $cekbiayadaftar ? ($cekbiayadaftar->status_ukt == 1 ? '0' : '-1') : '0',
                'kelaspagi'         => $kelaspagi,
                'kelassore'         => $kelassore,
                'created_at'        => date('Y-m-d H:i:s'),
                'created_by'        => session('user')->email,
            );

            $pendaftaran = Pendaftaran::insert($data_daftar);

            if ($pendaftaran) {
                DB::commit();
                return ['title' => 'Berhasil', 'message' => 'Data Pendaftaran Berhasil disimpan ! Silahkan konfirmasi pendaftaran anda', 'status' => 'success'];
            } else {
                DB::rollback();
                return ['title' => 'Gagal', 'message' => 'Data Pendaftaran Gagal disimpan !', 'status' => 'error'];
            }
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'title' => 'Server Error',
                'message' => 'Terjadi kendala pada server saat memproses data. Silakan coba beberapa saat lagi.' . ' ' . $e->getMessage(),
                'status' => 'error'
            ];
        }
    }

    public function update($post)
    {
        try {
            $kdDaftar = decrypt($post->IdPendaftaran);
            $batch = $post->batch;
            $jalur = $post->jalur;
            $beasiswa = $post->beasiswa;
            $tahun = $post->tahunlulus;
            $jurusansekolah = $post->jurusansekolah;
            $prodi1 = $post->prodi1;
            $prodi2 = $post->prodi2;
            $prodi3 = $post->prodi3;
            // $waktukuliah = $post->waktukuliah;
            $rekomendator = $post->rekomendator;
            $bioId = decrypt(session('user')->_biodata);

            // --- VALIDASI FAKULTAS BERBEDA ---
            $fakultas1 = Master_JurusanKuliah::where('KodeJurusan', $prodi1)->value('idfakultas');
            $fakultas2 = Master_JurusanKuliah::where('KodeJurusan', $prodi2)->value('idfakultas');
            $fakultas3 = Master_JurusanKuliah::where('KodeJurusan', $prodi3)->value('idfakultas');

            if ($fakultas1 != 'F003') {
                if (($fakultas1 == $fakultas2 && $fakultas1 != null) || ($fakultas1 == $fakultas3 && $fakultas1 != null) || ($fakultas2 == $fakultas3 && $fakultas2 != null)) {
                    return ['title' => 'Peringatan', 'message' => 'Setiap Pilihan Program Studi harus berasal dari Fakultas yang berbeda!', 'status' => 'warning'];
                }
            } else {
                if (($fakultas1 != $fakultas2 && $fakultas1 != null) || ($fakultas1 != $fakultas3 && $fakultas1 != null) || ($fakultas2 != $fakultas3 && $fakultas2 != null)) {
                    return ['title' => 'Peringatan', 'message' => 'Jika Pilihan 1 Fakultas Vokasi, Maka Pilihan Selanjutnya Harus Vokasi Juga!', 'status' => 'warning'];
                }
            }

            if ($post->waktukuliah == 'SORE') {
                $kelaspagi = '0';
                $kelassore = '1';
            } elseif ($post->waktukuliah == 'PAGI') {
                $kelaspagi = '1';
                $kelassore = '0';
            }

            DB::beginTransaction();
            $cekbiayadaftar = Master_JenisPendaftaran::where('id', $jalur)->where('isactive', 1)->first();

            $data_daftar = array(
                'bayar_pendaftaran' => $cekbiayadaftar ? ($cekbiayadaftar->biaya_pendaftaran == 1 ? '0' : '-1') : '0',
                'batch_daftar'      => $batch,
                'jalur_daftar'      => $jalur,
                'beasiswa'          => $beasiswa,
                'tahun_lulus'       => $tahun,
                'jurusan_sekolah'   => $jurusansekolah,
                'pilihan1'          => $prodi1,
                'pilihan2'          => $prodi2,
                'pilihan3'          => $prodi3,
                // 'waktu_kuliah'      => $waktukuliah,
                'kelaspagi'         => $kelaspagi,
                'kelassore'         => $kelassore,
                'rekomendator'      => $rekomendator,
                'bayar_ukt'         => $cekbiayadaftar ? ($cekbiayadaftar->status_ukt == 1 ? '0' : '-1') : '0',
                'updated_at'        => date('Y-m-d H:i:s'),
                'updated_by'        => session('user')->email,
            );
            $updt = Pendaftaran::where('KodePendaftaran', $kdDaftar)->where('biodata_id', $bioId)->where('isactive', 1)->update($data_daftar);

            if ($updt) {
                DB::commit();
                return ['title' => 'Berhasil', 'message' => 'Data Pendaftaran Berhasil diperbarui !', 'status' => 'success'];
            } else {
                DB::rollback();
                return ['title' => 'Gagal', 'message' => 'Data Pendaftaran Gagal diperbarui !', 'status' => 'error'];
            }
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'title' => 'Server Error',
                'message' => 'Terjadi kendala pada server saat memproses data. Silakan coba beberapa saat lagi.',
                'status' => 'error'
            ];
        }
    }

    public function ConfirmDaftar($params)
    {
        $kode = decrypt($params);

        DB::beginTransaction();

        // 1. Ambil data pendaftaran peserta saat ini
        $cek1 = Pendaftaran::where('KodePendaftaran', $kode)->where('isactive', 1)->first();

        // 2. Ambil data Master Jenis Pendaftaran (Jalur) berdasarkan pilihan peserta
        $jalur = Master_JenisPendaftaran::where('id', $cek1->jalur_daftar)->where('isactive', 1)->first();
        $biaya = $jalur->jml_biaya_pendaftaran;

        // 3.       Ambil ID berkas khusus dari tabel master jenis pendaftaran.
        // Jika  jalur tersebut tidak butuh berkas khusus (null di database), maka variabel ini akan berisi null.
        $idBerkasKhusus = $jalur->berkas_khusus;

        // 4. Update data pendaftaran (Tambahkan kolom berkas_khusus)
        if ($biaya == 0) {
            // Jika biaya pendaftaran gratis dan tidak ada syarat berkas khusus, langsung ke step 6 (Test Assessment)
            $nextStep = ($idBerkasKhusus == null) ? 6 : 4;
        } else {
            $nextStep = 2; // Menuju step Bayar Pendaftaran
        }

        $updt = Pendaftaran::where('KodePendaftaran', $kode)->where('isactive', 1)->update([
            'konfirm_pendaftaran' => '1',
            'tgl_konfirm'         => date('Y-m-d H:i:s'),
            'current_step'        => $nextStep,
            'berkas_khusus'       => $idBerkasKhusus, // <-- INI fungsi yang mengirim kode jenis berkas
            'updated_at'          => date('Y-m-d H:i:s')
        ]);

        $kode_transaksi = 'PMB-' . $cek1->KodePendaftaran . '-' . date('YmdHis');
        $bioId = decrypt(session('user')->_biodata);

        // Simpan transaksi
        $transaksi = Transaksi::insert([
            'user_id'        => $bioId,
            'kategori'       => 'pendaftaran',
            'id_referensi'   => $cek1->KodePendaftaran,
            'kode_transaksi' => $kode_transaksi,
            'jumlah'         => $biaya,
            'status'         => $biaya == 0 ? 'paid' : 'pending',
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        if ($updt && $transaksi) {
            DB::commit();
            $data['title'] = 'Berhasil';
            $data['message'] = 'Konfirmasi Pendaftaran Berhasil';
            $data['status'] = 'success';
        } else {
            DB::rollback();
            $data['title'] = 'Gagal';
            $data['message'] = 'Konfirmasi Pendaftaran Gagal';
            $data['status'] = 'error';
        }

        return response()->json($data, Response::HTTP_OK);
    }

    public function delete($params)
    {
        $id = decrypt($params);
        $cek1 = Pendaftaran::where('KodePendaftaran', $id)->where('isactive', 1)->exists();

        if ($cek1) {
            DB::beginTransaction();
            $update = Pendaftaran::where('KodePendaftaran', $id)->where('isactive', 1)->update([
                'isactive' => '0',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            if ($update) {
                DB::commit();
                $data['title'] = 'Berhasil';
                $data['message'] = 'Anda Dinyatakan Mengundurkan Diri Pada Batch ini. Silahkan Mendaftar lagi pada batch selanjutnya !';
                $data['status'] = 'success';
            } else {
                DB::rollback();
                $data['title'] = 'Gagal';
                $data['message'] = 'Gagal Menghapus Data Pendaftaran !';
                $data['status'] = 'error';
            }
        } else {
            $data['title'] = 'Gagal';
            $data['message'] = 'Data Pendaftaran Tidak Ada !';
            $data['status'] = 'error';
        }

        return response()->json($data, Response::HTTP_OK);
    }

    public function getwaktukuliah($params = null)
    {
        if (!$params) {
            return response()->json(['hasil' => 0], Response::HTTP_OK);
        }

        $getwaktukuliah = Master_JenisPendaftaran::where('id', $params)->where('isactive', '1')->first();
        if (!$getwaktukuliah) {
            return response()->json(['hasil' => 0], Response::HTTP_OK);
        }

        return response()->json($getwaktukuliah, Response::HTTP_OK);
    }
}
