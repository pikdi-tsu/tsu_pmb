<?php

namespace Modules\Admin\Http\Controllers\masterdata;

use App\Models\MasterData\Master_JenisBerkas;
use App\Models\MasterData\Master_JenisPendaftaran;
use App\Models\User\Pendaftaran;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Session, Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Symfony\Component\HttpFoundation\Response;

class JenisPendaftaranController extends Controller
{
    public function index()
    {
        $jenisberkas = Master_JenisBerkas::where('isactive', 1)->get();
        $data = array(
            'title'  => 'Master Data Jenis Pendaftaran',
            'menu'   => 'Jenis Pendaftaran',
            'master' => $jenisberkas
        );
        return view('admin::masterdata.jenispendaftaran.index', $data);
    }

    public function table_Pendaftaran()
    {
        $data = Master_JenisPendaftaran::with('berkasumum', 'berkaskhusus')->orderBy('created_at', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('kode', function ($d) {
                return $d->KodeJenis;
            })
            ->addColumn('nama', function ($d) {
                $nama = $d->jenis_pendaftaran;
                return $nama;
            })
            ->addColumn('status', function ($d) {
                if ($d->is_beasiswa == 1) {
                    $role = 'Beasiswa';
                    $warna = 'success';
                } else {
                    $role = 'Non Beasiswa';
                    $warna = 'danger';
                }
                $show = '<span class="badge bg-' . $warna . '">' . $role . '</span>';
                return $show;
            })
            // TAMBAHAN: Kolom format_nim
            ->addColumn('format_nim', function ($d) {
                return $d->format_nim ?? '-';
            })
            ->addColumn('biaya_daftar', function ($d) {
                if ($d->biaya_pendaftaran == 1) {
                    $role = 'Bayar';
                    $warna = 'danger';
                    $nominal = '<span class="badge bg-warning">' . rupiah($d->jml_biaya_pendaftaran) . '</span>';
                } else {
                    $role = 'Gratis';
                    $warna = 'success';
                    $nominal = '';
                }
                $show = '<span class="badge bg-' . $warna . '">' . $role . '</span>';
                return $show . ' ' . $nominal;
            })
            ->addColumn('ukt', function ($d) {
                if ($d->status_ukt == 1) {
                    $role = 'Bayar';
                    $warna = 'danger';
                } else {
                    $role = 'Gratis';
                    $warna = 'success';
                }
                $show = '<span class="badge bg-' . $warna . '">' . $role . '</span>';
                return $show;
            })
            ->addColumn('berkasumum', function ($d) {
                $nama = $d->berkasumum->jenis_berkas;
                return $nama;
            })
            ->addColumn('berkaskhusus', function ($d) {
                $nama = $d->berkaskhusus ? $d->berkaskhusus->jenis_berkas : '-';
                return $nama;
            })
            ->addColumn('deskripsi', function ($d) {
                $nama = $d->deskripsi;
                return $nama;
            })
            ->addColumn('jadwalkelas', function ($d) {
                if ($d->kelaspagi == '1' && $d->kelassore  == '1') {
                    $jadwal = 'Kelas Pagi & Kelas Sore';
                } elseif ($d->kelaspagi == '1') {
                    $jadwal = 'Kelas Pagi';
                } elseif ($d->kelassore == '1') {
                    $jadwal = 'Kelas Sore';
                } else {
                    $jadwal = 'Belum di Setting';
                }

                return $jadwal;
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
            ->rawColumns(['action', 'aktif', 'ukt', 'biaya_daftar', 'status'])
            ->make(true);
    }

    public function StoreJalur(Request $post)
    {
        $cek = Master_JenisPendaftaran::where('isactive', 1)
            ->where('KodeJenis', $post->kode)
            ->where('jenis_pendaftaran', $post->namajalur)
            ->first();
        $alert = null;
        if ($cek && $post->IdJenis == null) { // Hanya cek duplikat jika sedang Create Baru
            $alert = array(
                'title' => 'Gagal!',
                'message' => 'Kode Jalur atau Nama Jalur Pendaftaran Tidak Boleh Sama !',
                'status' => 'warning'
            );
        } else {
            if ($post->IdJenis == null) {
                $alert = $this->Save($post);
            } else {
                $alert = $this->Update($post);
            }
        }
        return response()->json($alert, Response::HTTP_OK);
    }

    public function Save($post)
    {
        $up = array(
            'KodeJenis'             => $post->kode,
            'jenis_pendaftaran'     => $post->namajalur,
            'is_beasiswa'           => $post->jenisjalur,
            'biaya_pendaftaran'     => isset($post->check_daftar) ? $post->check_daftar : '0',
            'jml_biaya_pendaftaran' => preg_replace('/[^0-9]/', '', $post->biaya_daftar),
            'status_ukt'            => $post->statusukt,
            // 'berkas' => $post->berkas, <--- BARIS INI SUDAH DIHAPUS
            'berkas_umum'           => $post->berkasumum,
            'berkas_khusus'         => $post->berkaskhusus,
            'format_nim'            => $post->format_nim,
            'deskripsi'             => $post->deskripsi,
            'kelaspagi'             => ($post->kelaspagi == NULL) ? '0' : $post->kelaspagi,
            'kelassore'             => ($post->kelassore == NULL) ? '0' : $post->kelassore,
            'created_at'            => date('Y-m-d H:i:s'),
            'created_by'            => optional(session('session'))->nip ?? 'System',
        );

        DB::beginTransaction();
        try {
            $save = Master_JenisPendaftaran::insert($up);
            DB::commit();
            $alert = array(
                'title'   => 'Berhasil!',
                'message' => 'Data Jenis Pendaftaran Tersimpan !',
                'status'  => 'success'
            );
        } catch (\Throwable $e) {
            DB::rollback();
            $alert = array(
                'title'   => 'Gagal!',
                'message' => 'Data Jenis Pendaftaran Gagal Disimpan ! ',
                'status'  => 'error'
            );
        }
        return $alert;
    }

    public function ShowJalur($params)
    {
        $id = decrypt($params);
        $check = Master_JenisPendaftaran::where('id', $id)->first();

        if ($check) {
            $data['hasil'] = 1;
            $data['jenis'] = $check;
            $data['IdJenis'] = $params;
        } else {
            $data['hasil'] = 0;
            $data['jenis'] = $check;
            $data['IdJenis'] = $params;
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function Update($post)
    {
        $id = decrypt($post->IdJenis);

        $up = array(
            'KodeJenis'             => $post->kode,
            'jenis_pendaftaran'     => $post->namajalur,
            'is_beasiswa'           => $post->jenisjalur,
            'biaya_pendaftaran'     => isset($post->check_daftar) ? $post->check_daftar : '0',
            'jml_biaya_pendaftaran' => preg_replace('/[^0-9]/', '', $post->biaya_daftar),
            'status_ukt'            => $post->statusukt,
            'berkas_umum'           => $post->berkasumum,
            'berkas_khusus'         => $post->berkaskhusus,
            'format_nim'            => $post->format_nim,
            'deskripsi'             => $post->deskripsi,
            'kelaspagi'             => ($post->kelaspagi == NULL) ? '0' : $post->kelaspagi,
            'kelassore'             => ($post->kelassore == NULL) ? '0' : $post->kelassore,
            'updated_at'            => date('Y-m-d H:i:s'),
            'updated_by'            => optional(session('session'))->nip ?? 'System',
        );
        // dd($up);
        DB::beginTransaction();
        try {
            $update = Master_JenisPendaftaran::where('id', $id)->update($up);
            DB::commit();
            $alert = array(
                'title'   => 'Berhasil!',
                'message' => 'Data Jenis Pendaftaran Diperbarui !',
                'status'  => 'success'
            );
        } catch (\Throwable $e) {
            DB::rollback();
            $alert = array(
                'title'   => 'Gagal!',
                'message' => 'Data Jenis Pendaftaran Gagal Diperbarui ! ',
                'status'  => 'error'
            );
        }
        return $alert;
    }

    public function delete($params1, $params2)
    {
        $id = decrypt($params1);
        $aktif = decrypt($params2);
        $cek = Pendaftaran::where('jalur_daftar', $id)->first(); // Perbaikan: Ganti jenis_daftar jadi jalur_daftar (sesuai struktur tabel Pendaftaran)
        if ($cek) {
            $alert = ['title' => 'Gagal', 'message' => 'Jenis Pendaftaran Sudah ada yang mendaftar !', 'status' => 'error'];
        } else {
            DB::beginTransaction();
            $up = array(
                'isactive' => $aktif,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => session('session')->nip
            );

            $update = Master_JenisPendaftaran::where('id', $id)->update($up);

            if ($update) {
                DB::commit();
                $alert = ['title' => 'Berhasil', 'message' => 'Status Jalur Pendaftaran Berhasil Dinonaktifkan', 'status' => 'success'];
            } else {
                DB::rollback();
                $alert = ['title' => 'Gagal', 'message' => 'Gagal Menonaktifkan Status Jalur Pendaftaran', 'status' => 'error'];
            }
        }
        return response()->json($alert, Response::HTTP_OK);
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

        $update = Master_JenisPendaftaran::where('id', $id)->update($up);

        if ($update) {
            DB::commit();
            $alert = ['title' => 'Berhasil', 'message' => 'Status Jalur Pendaftaran Berhasil Diaktifkan', 'status' => 'success'];
        } else {
            DB::rollback();
            $alert = ['title' => 'Gagal', 'message' => 'Gagal Mengaktifkan Status Jalur Pendaftaran', 'status' => 'error'];
        }
        // }
        return response()->json($alert, Response::HTTP_OK);
    }
}
