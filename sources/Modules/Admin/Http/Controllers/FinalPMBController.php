<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\MasterData\Master_Berkas;
use App\Models\MasterData\Master_JenisBerkas;
use App\Models\MasterData\Master_Kabupaten;
use App\Models\MasterData\Master_Kecamatan;
use App\Models\MasterData\Master_Kelurahan;
use App\Models\MasterData\Master_Provinsi;
use App\Models\MasterData\Master_TarifUKT;
use App\Models\MasterData\Master_Rekomendator;
use App\Models\MasterData\Master_Fakultas;
use App\Models\Parameter;
use App\Models\User\Biodata;
use App\Models\User\BerkasPendaftaran;
use Illuminate\Support\Facades\Storage;

use App\Models\User\Pendaftaran;
use App\Models\User\Saudara;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Yajra\DataTables\DataTables;
use Session, Crypt;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class FinalPMBController extends Controller
{
    public function index()
    {
        $getfakultas = Master_Fakultas::with(['jurusan' => function ($q) {
            $q->with('jenjang');
        }])->where('isactive', '1')->get();

        $data = array(
            'title' => 'Final PMB',
            'menu'  => 'Final PMB',
            'getfakultas' => $getfakultas
        );
        return view('admin::finalPMB.index', $data);
    }

    public function tabel_final()
    {
        $data = Pendaftaran::with([
            'biodata',
            'batch',
            'jalur',
            'jurusanditerima' => function ($q) {
                $q->with('jenjang');
            }
        ])->where('isactive', 1)->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama', function ($d) {
                return $d->biodata->nama;
            })
            ->addColumn('batch', function ($d) {
                return $d->batch->nama_batch;
            })
            ->addColumn('jalur', function ($d) {
                $nama = $d->jalur->jenis_pendaftaran;
                return $nama;
            })
            ->addColumn('pindahjalur', function ($d) {
                $status = 'Tidak Pindah';
                $warna = 'success';
                if ($d->status_pindah_jalur == '1') {
                    $status = 'Pindah Jalur';
                    $warna = 'warning';
                } elseif ($d->status_pindah_jalur == '-1') {
                    $status = 'Tidak Setuju Pindah Jalur';
                    $warna = 'danger';
                } elseif ($d->status_pindah_jalur == '0') {
                    $status = 'Menunggu Persetujuan';
                    $warna = 'danger';
                }
                $show = '<span class="badge bg-' . $warna . '">' . $status . '</span>';
                return $show;
            })
            ->addColumn('status', function ($d) {
                $warna = 'warning';
                $status = '';
                if ($d->isactive == '1') {
                    if ($d->validasi_test == '1') {
                        $warna = 'success';
                        $status = 'Lolos Test';
                    } else {
                        $warna = 'danger';
                        $status = 'Tidak Lolos Test';
                    }
                } else {
                    $warna = 'danger';
                    $status = 'Mengundurkan Diri';
                }
                $show = '<span class="badge bg-' . $warna . '">' . $status . '</span>';
                return $show;
            })
            ->addColumn('programstudi', function ($d) {
                if ($d->isactive == '1') {
                    if ($d->validasi_test == '1') {
                        $jenjang = $d->jurusanditerima->jenjang ? $d->jurusanditerima->jenjang->jenjang : '';
                        return ($jenjang ? $jenjang . '-' : '') . $d->jurusanditerima->jurusan;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->biodata_id);
                $kodependaftaran = encrypt($d->KodePendaftaran);
                $edit   = '<a href="' . route('admin.finalpmb.detail', [$id, encrypt('edit')]) . '" class="btn_edit"><i title="Edit Biodata" class="fa fa-user-edit text-orange"></i></a>';
                $editjur = '';
                if ($d->validasi_test == '1') {
                    $editjur = ' <a href="#" data-id="' . $kodependaftaran . '" class="btn_edit_jurusan"><i title="Edit Jurusan" class="fa fa-edit text-info"></i></a>';
                }
                $detail = '<a href="' . route('admin.finalpmb.detail', [$id, encrypt('detail')]) . '" class="btn_detail"><i title="Detail" class="fa fa-info-circle"></i></a>';
                return $detail . ' ' . $edit . ' ' . $editjur;
            })
            ->rawColumns(['action', 'status', 'pindahjalur'])
            ->make(true);
    }

    public function ChangeKabupaten($prov)
    {
        $kabupaten = Master_Kabupaten::where('idprov', $prov)->where('isactive', 1)->get();

        if (count($kabupaten) > 0) {
            $data['hasil'] = 1;
            $data['kabupaten'] = $kabupaten;
        } else {
            $data['hasil'] = 0;
            $data['kabupaten'] = $kabupaten;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function ChangeKecamatan($prov, $kab)
    {
        $kecamatan = Master_Kecamatan::where('idprov', $prov)->where('idkab', $kab)->where('isactive', 1)->get();

        if (count($kecamatan) > 0) {
            $data['hasil'] = 1;
            $data['kecamatan'] = $kecamatan;
        } else {
            $data['hasil'] = 0;
            $data['kecamatan'] = $kecamatan;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function ChangeKelurahan($prov, $kab, $kec)
    {
        $kelurahan = Master_Kelurahan::where('idprov', $prov)->where('idkab', $kab)->where('idkec', $kec)->where('isactive', 1)->get();

        if (count($kelurahan) > 0) {
            $data['hasil'] = 1;
            $data['kelurahan'] = $kelurahan;
        } else {
            $data['hasil'] = 0;
            $data['kelurahan'] = $kelurahan;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function Detail($params, $form)
    {
        $id = decrypt($params);
        $page = decrypt($form);

        $datadaftar = Pendaftaran::where('biodata_id', $id)->orderby('created_at', 'desc')->where('isactive', 1)->with([
            'biodata' => function ($q) {
                $q->with('saudara', 'akunbio');
            },
            'batch',
            'jalur',
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
            'waktukuliah',
            'bayar',
            'jawaban_peserta',
            'jurusan_acc' => function ($q) {
                $q->with('jenjang', 'fakultas');
            }
        ])
            ->first();
        $prodi1 = null;
        if ($datadaftar && $datadaftar->prodi1) {
            $prodi1 = Master_TarifUKT::where('idbatch', $datadaftar->batch_daftar)->where('idjalur', $datadaftar->jalur_daftar)->where('idjurusan', $datadaftar->prodi1->id)->where('isactive', 1)->first();
        }
        $prodi2 = null;
        if ($datadaftar && $datadaftar->prodi2) {
            $prodi2 = Master_TarifUKT::where('idbatch', $datadaftar->batch_daftar)->where('idjalur', $datadaftar->jalur_daftar)->where('idjurusan', $datadaftar->prodi2->id)->where('isactive', 1)->first();
        }
        $prodi3 = null;
        if ($datadaftar && $datadaftar->prodi3) {
            $prodi3 = Master_TarifUKT::where('idbatch', $datadaftar->batch_daftar)->where('idjalur', $datadaftar->jalur_daftar)->where('idjurusan', $datadaftar->prodi3->id)->where('isactive', 1)->first();
        }
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

        $berkasPendaftar = BerkasPendaftaran::where('kode_daftar', $datadaftar->KodePendaftaran)->get();

        $halaman = '';
        $title = '';
        $menu = '';
        $provinsi = '';
        $kabupaten = '';
        $kecamatan = '';
        $kelurahan = '';
        $berkas = '';
        $provinsi_sekolah = '';
        $kabupaten_sekolah = '';
        if ($page == 'detail') {
            $title = 'Detail PMB';
            $menu = 'Detail Calon Mahasiswa';
            $halaman = 'detail';
            $provinsi = Master_Provinsi::where('idprov', $datadaftar->biodata->provinsi)->first();
            $kabupaten = Master_Kabupaten::where('idprov', $datadaftar->biodata->provinsi)->where('idkab', $datadaftar->biodata->kabupaten)->first();
            $kecamatan = Master_Kecamatan::where('idprov', $datadaftar->biodata->provinsi)->where('idkab', $datadaftar->biodata->kabupaten)->where('idkec', $datadaftar->biodata->kecamatan)->first();
            $kelurahan = Master_Kelurahan::where('idprov', $datadaftar->biodata->provinsi)->where('idkab', $datadaftar->biodata->kabupaten)->where('idkec', $datadaftar->biodata->kecamatan)->where('idkel', $datadaftar->biodata->kelurahan)->first();
            $berkas = '';
            $provinsi_sekolah = Master_Provinsi::where('idprov', $datadaftar->biodata->provinsi_sekolah)->first();
            $kabupaten_sekolah = Master_Kabupaten::where('idprov', $datadaftar->biodata->provinsi_sekolah)->where('idkab', $datadaftar->biodata->kabupaten_sekolah)->first();
        } else {
            $title = 'Edit Data PMB';
            $menu = 'Edit Data Calon Mahasiswa';
            $halaman = 'edit';
            $provinsi = Master_Provinsi::where('isactive', 1)->get();
            $kabupaten = Master_Kabupaten::where('isactive', 1)->get();
            // $kecamatan = Master_Kecamatan::where('isactive',1)->get();
            // $kelurahan = Master_Kelurahan::where('isactive',1)->get();
            // $provinsi = '';
            // $kabupaten = '';
            $kecamatan = '';
            $kelurahan = '';
            $berkas = Master_JenisBerkas::where('jenis_berkas', 'Umum')->where('kategori', '0')->with('berkas')->first();
            $provinsi_sekolah = Master_Provinsi::where('idprov', $datadaftar->biodata->provinsi_sekolah)->first();
            $kabupaten_sekolah = Master_Kabupaten::where('idprov', $datadaftar->biodata->provinsi_sekolah)->where('idkab', $datadaftar->biodata->kabupaten_sekolah)->first();
        }

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

        $data = array(
            'title'             => $title,
            'menu'              => $menu,
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
            'berkas'            => $berkas,
            'provinsi_sekolah'  => $provinsi_sekolah,
            'kabupaten_sekolah' => $kabupaten_sekolah,
            'rekomendator'      => $rekomendator_text
        );
        return view('admin::finalPMB.' . $halaman, $data);
    }

    public function update(Request $post)
    {
        $parameter = Parameter::where('id', 1)->first();
        $cek = Pendaftaran::where('KodePendaftaran', $post->kodedaftar)->first();
        $bioId = $cek->biodata_id;
        $jmlsaudara = $post->jumlah_saudara;

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
                'penghasilan_ayah' => $post->penghasilan_ayah,
                'alamat_ayah' => $post->alamat_ayah,
                'nama_ibu' => $post->nama_ibu,
                'tempat_lahir_ibu' => $post->tempat_lahir_ibu,
                'tgl_lahir_ibu' => $post->tgl_lahir_ibu,
                'status_ibu' => $post->status_ibu,
                'statushidup_ibu' => $post->statushidup_ibu,
                'nohp_ibu' => $post->nohp_ibu,
                'pekerjaan_ibu' => $post->pekerjaan_ibu,
                'penghasilan_ibu' => $post->penghasilan_Ibu,
                'alamat_ibu' => $post->alamat_ibu,
                'jumlah_saudara' => $jmlsaudara,
                'nama_sekolah' => $post->nama_sekolah,
                'jenis_sekolah' => $post->jenis_sekolah,
                'provinsi_sekolah' => $post->provinsi_sekolah,
                'kabupaten_sekolah' => $post->kabupatenkota_sekolah,
                'npsn' => $post->npsn,
                'nisn' => $post->nisn,
                'nilai_akhir' => $post->nilai_akhir,
                'updated_at' => now()
            );

            $upbio = Biodata::where('biodata_id', $bioId)->where('isactive', 1)->update($databio);

            $updaftar = Pendaftaran::where('KodePendaftaran', $post->kodedaftar)->update([
                'tahun_lulus' => $post->tahun_lulus,
                'updated_at' => now()
            ]);

            $count = 0;
            if ($jmlsaudara > 0) {
                $cek2 = Saudara::where('bio_id', $bioId);
                if ($cek2->exists()) {
                    $cek2->delete();
                }
                foreach ($post->nama_saudara as $key => $p) {
                    $saudara = array(
                        'bio_id' => $bioId,
                        'nama' => $p,
                        'pekerjaan' => $post->pekerjaan_saudara[$key],
                        'status_hidup' => $post->statushidup_saudara[$key],
                        'status_kekerabatan' => $post->statuskekerabatan_saudara[$key],
                        'created_at' => now()
                    );
                    if (Saudara::insert($saudara)) {
                        $count++;
                    }
                }
            }

            if ($post->hasFile('berkas_baru')) {
                foreach ($post->file('berkas_baru') as $id_berkas => $file) {
                    $ext = $file->getClientOriginalExtension();
                    // Format Nama File: Kodependaftaran_IDBerkas_Timestamp.pdf
                    $filename = $post->kodedaftar . '_' . $id_berkas . '_' . date('YmdHis') . '.' . $ext;

                    // Upload file ke folder
                    $file->storeAs($parameter->file_umum, $filename);

                    // Cek apakah data di tabel pmb_berkas_pendaftaran sudah ada
                    $berkasLama = BerkasPendaftaran::where('kode_daftar', $post->kodedaftar)
                        ->where('id_berkas', $id_berkas)->first();

                    if ($berkasLama) {
                        // Hapus fisik file lama (jika ingin hemat storage)
                        $path = $parameter->file_umum . '/' . $berkasLama->nama_berkas;
                        if (Storage::exists($path)) {
                            Storage::delete($path);
                        }
                        // Update nama file baru di database
                        $berkasLama->update(['nama_berkas' => $filename]);
                    } else {
                        // Insert data baru ke database
                        BerkasPendaftaran::create([
                            'kode_daftar' => $post->kodedaftar,
                            'id_berkas' => $id_berkas,
                            'nama_berkas' => $filename,
                            'isactive' => 1
                        ]);
                    }
                }
            }

            // 4. COMMIT & REDIRECT
            DB::commit();
            return redirect()->back()->with('alert', ['title' => 'Berhasil', 'message' => 'Update Biodata & Berkas Berhasil !', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            // Tampilkan pesan error aslinya agar gampang diperbaiki kalau ada masalah
            return redirect()->back()->with('alert', ['title' => 'Error', 'message' => 'Update Biodata & Berkas Gagal !', 'status' => 'error']);
        }
    }

    public function editJurusan($params)
    {
        $kodependaftaran = decrypt($params);

        $getpendaftaran = Pendaftaran::with(['biodata', 'jalur'])->where('KodePendaftaran', $kodependaftaran)->where('isactive', '1')->where('deleted_at', NULL)->first();

        return response()->json($getpendaftaran, Response::HTTP_OK);
    }

    public function updateJurusan(Request $req)
    {
        try {
            $kodependaftaran = decrypt($req->kodependaftaran);
            // dd($req);

            if ($req->jadwalkelas == 'SORE') {
                $kelaspagi = '0';
                $kelassore = '1';
            } elseif ($req->jadwalkelas == 'PAGI') {
                $kelaspagi = '1';
                $kelassore = '0';
            }

            $updatejurusan = Pendaftaran::where('KodePendaftaran', $kodependaftaran)->where('isactive', '1')->where('deleted_at', NULL)->update([
                'jurusan_diterima' => $req->prodiditerima,
                'kelaspagi'        => $kelaspagi,
                'kelassore'        => $kelassore,
                'updated_at'       => date('Y-m-d H:i:s'),
                'updated_by'       => session('session')->nip,
            ]);

            if ($updatejurusan) {
                return response()->json(['status' => 'success', 'message' => 'Data Jurusan Berhasil Diubah']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Gagal Mengubah Data Jurusan']);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
