<?php

namespace Modules\Admin\Http\Controllers\masterdata;

use App\Models\MasterData\Master_Batch;
use App\Models\MasterData\Master_Beasiswa;
use App\Models\MasterData\Master_JenisPendaftaran;
use App\Models\MasterData\Master_JurusanKuliah;
use App\Models\MasterData\Master_TarifUKT;


use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Session, Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Symfony\Component\HttpFoundation\Response;

class TarifUKTController extends Controller
{
    public function index()
    {
        $batch = Master_Batch::where('isactive', 1)->get();
        $jalur = Master_JenisPendaftaran::where('isactive', 1)->get();
        $prodi = Master_JurusanKuliah::with('jenjang', 'Fakultas')->where('isactive', 1)->get();
        $data = array(
            'title'    => 'Master Data Tarif UKT',
            'menu'     => 'Tarif UKT',
            'batch' => $batch,
            'jalur' => $jalur,
            'prodi' => $prodi
        );
        return view('admin::masterdata.tarifukt.index', $data);
    }

    public function TabelUKT()
    {
        $data = Master_TarifUKT::with([
            'batch',
            'jalur',
            'jurusan' => function ($q) {
                $q->with('jenjang', 'Fakultas');
            }
        ])->orderBy('id', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('batch', function ($d) {
                $nama = $d->batch->kode_batch . ' - ' . $d->batch->nama_batch . ' - ' . $d->batch->tahun_akademik;
                return $nama;
            })
            ->addColumn('jalur', function ($d) {
                $nama = $d->jalur->KodeJenis . ' - ' . $d->jalur->jenis_pendaftaran;
                return $nama;
            })
            ->addColumn('prodi', function ($d) {
                $nama = $d->jurusan->fakultas->singkatan . ' - ' . $d->jurusan->jenjang->jenjang . ' - ' . $d->jurusan->jurusan;
                return $nama;
            })
            ->addColumn('tarif', function ($d) {
                $nama = '<span class="badge bg-warning">' . rupiah($d->biaya_ukt) . '</span>';
                return $nama;
            })
            ->addColumn('keterangan', function ($d) {
                return $d->keterangan;
            })
            ->addColumn('aktif', function ($d) {
                $role = '-';
                $warna = '';
                if ($d->isactive == 1) {
                    $role = 'Aktif';
                    $warna = 'success';
                } else {
                    $role = 'Tidak Aktif';
                    $warna = 'danger';
                }
                $show = '<span class="badge bg-' . $warna . '">' . $role . '</span>';
                return $show;
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->id);

                $url = '#';
                $edit   = '<a href="#" data-id="' . $id . '" class="btn_edit"><i title="Edit" class="fa fa-edit text-orange"></i></a>';
                $aktif = '';
                if ($d->isactive == 1) {
                    $aktif = '<a href="#" data-id="' . $id . '" data-status="' . encrypt('0') . '" class="btn_delete"><i title="Hapus" class="fa fa-trash text-red"></i></a>';
                } else {
                    $aktif  = '<a href="#" data-id="' . $id . '" data-status="' . encrypt('1') . '" class="btn_aktifkan"><i title="Aktifkan" class="fas fa-check-circle text-green"></i></a>';
                }
                return $edit . ' ' . $aktif;
            })
            ->rawColumns(['action', 'aktif', 'tarif'])
            ->make(true);
    }

    public function StoreUKT(Request $post)
    {
        // dd(count($post->prodi));
        if ($post->IdUKT == null) {
            $alert = $this->Save($post);
        } else {
            $alert = $this->Update($post);
        }

        return response()->json($alert, Response::HTTP_OK);
        // return redirect()->route('admin.JenisPendaftaran.show')->with('alert',$alert);

    }

    public function Save($post)
    {
        $cek = Master_TarifUKT::where('idbatch', $post->batch)->where('idjalur', $post->jalur)->whereIn('idjurusan', $post->prodi)->where('isactive', 1)->exists();
        if ($cek) {
            $alert = array(
                'title' => 'Gagal!',
                'message' => 'Tarif UKT Sudah Ada !',
                'status' => 'error'
            );
        } else {
            DB::beginTransaction();
            $count = 0;
            foreach ($post->prodi as $p) {
                $up = array(
                    'idbatch'      => $post->batch,
                    'idjalur'      => $post->jalur,
                    'idjurusan'    => $p,
                    'biaya_ukt'    => preg_replace('/[^0-9]/', '', $post->biaya_ukt),
                    'keterangan'   => $post->keterangan,
                    'created_at'   => date('Y-m-d H:i:s'),
                    'created_by'   => session('session')->nip,
                );
                $save = Master_TarifUKT::insert($up);
                if ($save) {
                    $count++;
                }
            }
            if ($count == count($post->prodi)) {
                DB::commit();
                $alert = array(
                    'title' => 'Berhasil!',
                    'message' => 'Data Tarif UKT Tersimpan !',
                    'status' => 'success'
                );
            } else {
                DB::rollback();
                $alert = array(
                    'title' => 'Gagal!',
                    'message' => 'Data Tarif UKT Gagal Disimpan !',
                    'status' => 'error'
                );
            }
        }
        return $alert;
    }

    public function ShowUKT($params)
    {
        $id = decrypt($params);
        // dd($id);
        $check = Master_TarifUKT::where('id', $id)->where('isactive', 1)->first();

        if ($check) {
            $data['hasil'] = 1;
            $data['ukt'] = $check;
            $data['IdUKT'] = $params;
        } else {
            $data['hasil'] = 0;
            $data['ukt'] = $check;
            $data['IdUKT'] = $params;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function Update($post)
    {
        $id = decrypt($post->IdUKT);
        // dd($id);

        $cek = Master_TarifUKT::where('id', '!=', $id)->where('idbatch', $post->batch)->where('idjalur', $post->jalur)->whereIn('idjurusan', $post->prodi)->where('isactive', 1)->exists();
        if ($cek) {
            $alert = array(
                'title' => 'Gagal!',
                'message' => 'Tarif UKT Ada !',
                'status' => 'error'
            );
        } else {
            DB::beginTransaction();
            $count = 0;
            foreach ($post->prodi as $p) {
                $up = array(
                    'idbatch'      => $post->batch,
                    'idjalur'      => $post->jalur,
                    'idjurusan'    => $p,
                    'biaya_ukt'    => preg_replace('/[^0-9]/', '', $post->biaya_ukt),
                    'keterangan'   => $post->keterangan,
                    'updated_at'   => date('Y-m-d H:i:s'),
                    'updated_by'   => session('session')->nip,
                );
                $save = Master_TarifUKT::where('id', $id)->update($up);
                if ($save) {
                    $count++;
                }
            }

            if ($count == count($post->prodi)) {
                DB::commit();
                $alert = array(
                    'title' => 'Berhasil!',
                    'message' => 'Data Tarif UKT Diperbarui !',
                    'status' => 'success'
                );
            } else {
                DB::rollback();
                $alert = array(
                    'title' => 'Gagal!',
                    'message' => 'Data Tarif UKT Gagal Diperbarui !',
                    'status' => 'error'
                );
            }
        }
        return $alert;
    }

    public function delete($params1, $params2)
    {
        $id = decrypt($params1);
        $aktif = decrypt($params2);
        DB::beginTransaction();
        $up = array(
            'isactive' => $aktif,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session('session')->nip
        );

        $cek = Master_TarifUKT::where('id', $id)->select('id', 'idbatch')->first();
        $cekbatch = Master_Batch::where('id', $cek->idbatch)->select('id', 'isactive')->first();
        if ($cekbatch->isactive == '0') {
            $master['message'] = 'Harus Mengaktifkan Batch Pendaftaran Terlebih Dahulu';
            $master['status'] = 'warning';
        } else {
            $update = Master_TarifUKT::where('id', $id)->update($up);

            $kata = $aktif == '1' ? 'Berhasil Mengaktifkan Data' : 'Berhasil Menghapus Data';
            $del = $aktif == '1' ? 'Gagal Mengaktifkan Data' : 'Gagal Menghapus Data';

            if ($update) {
                DB::commit();
                $master['message'] = $kata;
                $master['status'] = 'success';
            } else {
                DB::rollback();
                $master['message'] = $del;
                $master['status'] = 'error';
            }
        }

        return response()->json($master, Response::HTTP_OK);
    }

    public function Mengaktifkan($params1, $params2)
    {
        $id = decrypt($params1);
        $aktif = decrypt($params2);
        // $cek = Pendaftaran::where('jalur_daftar', $id)->first(); // Perbaikan: Ganti jenis_daftar jadi jalur_daftar (sesuai struktur tabel Pendaftaran)
        // if ($cek) {
        //     $alert = ['title' => 'Gagal', 'message' => 'Jenis Pendaftaran Sudah ada yang mendaftar !', 'status' => 'error'];
        // } else {
        DB::beginTransaction();
        $up = array(
            'isactive' => $aktif,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => session('session')->nip
        );

        $update = Master_TarifUKT::where('id', $id)->update($up);

        if ($update) {
            DB::commit();
            $alert = ['title' => 'Berhasil', 'message' => 'Status Tarif UKT Berhasil Diaktifkan', 'status' => 'success'];
        } else {
            DB::rollback();
            $alert = ['title' => 'Gagal', 'message' => 'Gagal Mengaktifkan Status Tarif UKT', 'status' => 'error'];
        }
        // }
        return response()->json($alert, Response::HTTP_OK);
    }
}
