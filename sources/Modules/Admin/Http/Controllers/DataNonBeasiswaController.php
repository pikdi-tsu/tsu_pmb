<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Session, Crypt, DB;
use Yajra\DataTables\DataTables;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MasterData\Master_TarifUKT;
use App\Models\User\Pendaftaran;
use App\Models\MasterData\Master_Rekomendator;
use App\Models\MasterData\Master_Fakultas;
use App\Models\MasterData\Master_JurusanKuliah;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Http\Exports\ExportPendaftarExcel;


class DataNonBeasiswaController extends Controller
{
    public function index()
    {
        $getfakultas = Master_Fakultas::with(['jurusan' => function ($q) {
            $q->with('jenjang');
        }])->where('isactive', '1')->get();

        $data = array(
            'title' => 'Data Pendaftar Non Beasiswa',
            'menu'  => 'Data Pendaftar Non Beasiswa',
            'getfakultas' => $getfakultas
        );
        return view('admin::pendaftaran.nonbeasiswa.index', $data);
    }

    public function tabelNonBeasiswa()
    {
        $data = Pendaftaran::where('beasiswa', null)->where('deleted_at', NULL)->with([
            'biodata',
            'batch',
            'jalur',
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
        ])->orderBy('created_at', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama', function ($d) {
                return $d->biodata->nama;
            })
            ->addColumn('noreg', function ($d) {
                return $d->KodePendaftaran;
            })
            ->addColumn('batch', function ($d) {
                return $d->batch->nama_batch . ' ' . $d->batch->tahun_akademik;
            })
            ->addColumn('jalur', function ($d) {
                $nama = $d->jalur->jenis_pendaftaran;
                return $nama;
            })
            ->addColumn('prodi1', function ($d) {
                if ($d->prodi1) {
                    $jenjang = $d->prodi1->jenjang ? $d->prodi1->jenjang->jenjang : '';
                    return ($jenjang ? $jenjang . '-' : '') . $d->prodi1->jurusan;
                }
                return '-';
            })
            ->addColumn('prodi2', function ($d) {
                if ($d->prodi2) {
                    $jenjang = $d->prodi2->jenjang ? $d->prodi2->jenjang->jenjang : '';
                    return ($jenjang ? $jenjang . '-' : '') . $d->prodi2->jurusan;
                }
                return '-';
            })
            ->addColumn('prodi3', function ($d) {
                if ($d->prodi3) {
                    $jenjang = $d->prodi3->jenjang ? $d->prodi3->jenjang->jenjang : '';
                    return ($jenjang ? $jenjang . '-' : '') . $d->prodi3->jurusan;
                }
                return '-';
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
            ->addColumn('jadwalkelas', function ($d) {
                if ($d->kelaspagi == '1') {
                    $jadwal = 'Kelas Pagi';
                } elseif ($d->kelassore == '1') {
                    $jadwal = 'Kelas Sore';
                } else {
                    $jadwal = 'Belum Diatur';
                }

                return $jadwal;
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->KodePendaftaran);
                $rek_text = '-';
                if ($d->rekomendator) {
                    $rek = \App\Models\MasterData\Master_Rekomendator::where('kode_rekomendator', $d->rekomendator)->first();
                    if ($rek) {
                        $rek_text = $rek->nama_rekomendator . ' (' . $rek->kode_rekomendator . ')';
                    } else {
                        $rek_text = $d->rekomendator;
                    }
                }
                // $aktif = '';
                // $detail = '';
                // $konfirm = '';
                // $edit = '';
                // if($d->isactive==1){
                //     if($d->konfirm_pendaftaran==0){
                //         $aktif = '<a href="#" class="btn_delete" data-id="'.$id.'"><i title="Hapus Pendaftaran" class="fa fa-trash text-red"></i></a>';
                //         $konfirm = '<a href="#" data-id="'.$id.'" class="btn_konfirm"><i title="Konfirmasi Pendaftaran" class="fas fa-check-circle text-green"></i></a>';
                //         $edit   = '<a href="#" data-id="'.$id.'" class="btn_edit"><i title="Edit" class="fa fa-edit text-orange"></i></a>';
                //     }
                // }
                // else{
                //     $aktif  = '<a href="#" class="btn_delete" data-id="'.$id.'" data-status="'.encrypt('1').'"><i title="Aktifkan" class="fas fa-check-circle text-green"></i></a>';
                // }
                // $detail = '<a href="#" data-id="'.$id.'" class="btn_detail"><i title="Detail" class="fa fa-info-circle"></i></a>';

                // return $detail.' '.$edit.' '.$aktif.' '.$konfirm;
                $detail = '<a href="#" data-id="' . $id . '" class="btn_detail"><i title="Detail" class="fa fa-info-circle text-blue"></i></a>';
                $editjur = '';
                if ($d->konfirm_pendaftaran == 1) {
                    $editjur = ' <a href="#" data-id="' . $id . '" class="btn_edit_jurusan"><i title="Edit Jurusan" class="fa fa-edit text-info"></i></a>';
                }
                $edit_rek = ' <a href="#" data-id="' . $id . '" data-rek="' . $rek_text . '" class="btn_edit_rekomendator"><i title="Info / Edit Rekomendator" class="fa fa-user-edit text-orange"></i></a>';
                $hapus = '<a href="#" data-id="' . $id . '" class="btn_hapus"><i title="Hapus Data" class="fa fa-trash text-danger"></i></a>';

                return $detail . ' ' . $edit_rek . ' ' . $editjur . ' ' . $hapus;
            })
            ->rawColumns(['action', 'status', 'jadwalkelas'])
            ->make(true);
    }

    public function showNonBeasiswa($params)
    {
        $id = decrypt($params);
        // ->where('isactive',1)
        $cek1 = Pendaftaran::where('KodePendaftaran', $id)->with([
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
        $prodi1 = null;
        if ($cek1 && $cek1->prodi1) {
            $prodi1 = Master_TarifUKT::where('idbatch', $cek1->batch_daftar)->where('idjalur', $cek1->jalur_daftar)->where('idjurusan', $cek1->prodi1->id)->where('isactive', 1)->first();
        }

        $prodi2 = null;
        if ($cek1 && $cek1->prodi2) {
            $prodi2 = Master_TarifUKT::where('idbatch', $cek1->batch_daftar)->where('idjalur', $cek1->jalur_daftar)->where('idjurusan', $cek1->prodi2->id)->where('isactive', 1)->first();
        }

        $prodi3 = null;
        if ($cek1 && $cek1->prodi3) {
            $prodi3 = Master_TarifUKT::where('idbatch', $cek1->batch_daftar)->where('idjalur', $cek1->jalur_daftar)->where('idjurusan', $cek1->prodi3->id)->where('isactive', 1)->first();
        }
        $rekomendator_text = '-';
        if ($cek1 && $cek1->rekomendator) {
            $rek = Master_Rekomendator::where('kode_rekomendator', $cek1->rekomendator)->first();
            if ($rek) {
                $rekomendator_text = $rek->nama_rekomendator . ' (' . $rek->kode_rekomendator . ')';
            } else {
                $rekomendator_text = $cek1->rekomendator;
            }
        }
        if ($cek1) {
            $data['hasil'] = 1;
            $data['daftar'] = $cek1;
            $data['ukt1'] = $prodi1;
            $data['ukt2'] = $prodi2;
            $data['ukt3'] = $prodi3;
            $data['rekomendator'] = $rekomendator_text;
        } else {
            $data['hasil'] = 0;
            $data['daftar'] = $cek1;
            $data['ukt1'] = $prodi1;
            $data['ukt2'] = $prodi2;
            $data['ukt3'] = $prodi3;
            $data['rekomendator'] = '-';
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function updateRekomendator(Request $request)
    {
        $id = decrypt($request->id_daftar);
        $kode_rek = $request->kode_rekomendator;

        $update = Pendaftaran::where('KodePendaftaran', $id)->update([
            'rekomendator' => $kode_rek,
            'updated_at'   => date('Y-m-d H:i:s')
        ]);

        if ($update) {
            return response()->json(['status' => 'success', 'message' => 'Data Rekomendator berhasil diupdate']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengubah Rekomendator']);
        }
    }

    public function editJurusan($params)
    {
        $kodependaftaran = decrypt($params);
        // $getpendaftaran = Pendaftaran::with([
        //     'biodata',
        //     'batch',
        //     'jalur',
        //     'jurusansekolah',
        //     'prodi1' => function ($q) {
        //         $q->with('jenjang');
        //     },
        //     'prodi2' => function ($q) {
        //         $q->with('jenjang');
        //     },
        //     'prodi3' => function ($q) {
        //         $q->with('jenjang');
        //     },
        //     'waktukuliah'
        // ])->where('KodePendaftaran', $kodependaftaran)->where('isactive', '1')->where('deleted_at', NULL)->first();

        // $getjurusan = Master_JurusanKuliah::selectRaw('id,KodeJurusan,idfakultas,idjenjang,idjurusansekolah,jurusan')
        //     ->with('jenjang')
        //     ->where('isactive', 1)
        //     ->get();

        // $getfakultas = Master_Fakultas::with('jurusan')->where('isactive', '1')->get();

        $getpendaftaran = Pendaftaran::with(['jalur'])->where('KodePendaftaran', $kodependaftaran)->where('isactive', '1')->where('deleted_at', NULL)->first();

        // dd($getpendaftaran);

        return response()->json($getpendaftaran, Response::HTTP_OK);
    }

    public function updateJurusan(Request $req)
    {
        try {
            $kodependaftaran = decrypt($req->kodependaftaran);
            // dd($req);

            $kelaspagi = '0';
            $kelassore = '0';
            if ($req->jadwalkelas == 'SORE') {
                $kelaspagi = '0';
                $kelassore = '1';
            } elseif ($req->jadwalkelas == 'PAGI') {
                $kelaspagi = '1';
                $kelassore = '0';
            }

            $updatejurusan = Pendaftaran::where('KodePendaftaran', $kodependaftaran)->where('isactive', '1')->where('deleted_at', NULL)->update([
                'pilihan1'    => $req->prodi1,
                'pilihan2'    => $req->prodi2,
                'pilihan3'    => $req->prodi3,
                'kelaspagi'   => $kelaspagi,
                'kelassore'   => $kelassore,
                'updated_at'  => date('Y-m-d H:i:s'),
                'updated_by'  => session('session')->nip,
            ]);

            if ($updatejurusan) {
                return response()->json(['status' => 'success', 'message' => 'Data Jurusan Berhasil Diubah']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Gagal Mengubah Data Jurusan']);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updateJurusan DataNonBeasiswa: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        $filters = [
            'batch_id' => $request->batch_id,
            'jalur_id' => $request->jalur_id,
        ];
        $filename = 'Rekap_Pendaftar_Non_Beasiswa_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new ExportPendaftarExcel($filters), $filename);
    }
}
