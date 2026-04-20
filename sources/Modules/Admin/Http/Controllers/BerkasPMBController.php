<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\MasterData\Master_Berkas;
use App\Models\MasterData\Master_TarifUKT;
use App\Models\MasterData\Master_JenisPendaftaran;
use App\Models\Parameter;
use App\Models\User\Pendaftaran;
use App\Models\User\BerkasPendaftaran;
use Yajra\DataTables\DataTables;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Session, Crypt, DB;
use Symfony\Component\HttpFoundation\Response;

class BerkasPMBController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Berkas PMB',
            'menu'  => 'Data Berkas PMB',
        );
        return view('admin::berkasPMB.index', $data);
    }

    public function tabelBerkasPMB()
    {
        $data = Pendaftaran::join('pmb_master_jenispendaftaran as a', 'pmb_pendaftaran.jalur_daftar', '=', 'a.id')
            ->select('pmb_pendaftaran.*')
            ->whereNotNull('a.berkas_khusus')
            ->with(['biodata', 'batch', 'jalur', 'jenisbeasiswa'])
            ->orderBy('pmb_pendaftaran.created_at', 'asc');
            return DataTables::of($data)
            ->addIndexColumn()
            // ... (kode Anda di bawahnya tidak perlu diubah)
            ->addIndexColumn()
            // 2. TAMBAHKAN FALLBACK (?) AGAR TIDAK ERROR JIKA DATA RELASI KOSONG
            ->addColumn('nama', function ($d) {
                return $d->biodata ? $d->biodata->nama : '-';
            })
            ->addColumn('noreg', function ($d) {
                return $d->KodePendaftaran;
            })
            ->addColumn('batch', function ($d) {
                return $d->batch ? $d->batch->nama_batch : '-';
            })
            ->addColumn('jalur', function ($d) {
                return $d->jalur ? $d->jalur->jenis_pendaftaran : '-';
            })
            ->addColumn('beasiswa', function ($d) {
                return $d->jenisbeasiswa ? $d->jenisbeasiswa->jenis_beasiswa : '-';
            })
            ->addColumn('keterangan', function ($d) {
                return $d->keterangan ? $d->keterangan : '-';
            })
            ->addColumn('validator', function ($d) {
                if ($d->validasi_berkas_khusus == '1' || $d->validasi_berkas_khusus == '-1') {
                    $nik = '<span class="badge bg-success">' . $d->nik_validasi_berkas_khusus . '</span>';

                    // Catatan: Jika fungsi namaku() melakukan query database, 
                    // ini bisa membuat sedikit lambat. Jika hanya mencocokkan array/session, maka aman.
                    $nama = '<span class="badge bg-warning">' . namaku($d->nik_validasi_berkas_khusus) . '</span>';

                    return $nik . ' ' . $nama;
                }
                return '-';
            })
            ->addColumn('pindahjalur', function ($d) {
                if ($d->status_pindah_jalur == '0') {
                    return '<span class="text-warning"><i class="fas fa-clock"></i> Menunggu Persetujuan</span>';
                } elseif ($d->status_pindah_jalur == '1') {
                    return '<span class="text-success"><i class="fas fa-check-double"></i> Pindah ke Reguler</span>';
                } elseif ($d->status_pindah_jalur == '-1') {
                    return '<span class="text-danger"><i class="fas fa-times"></i> Ditolak / Undur Diri</span>';
                }
                return '-';
            })
            ->addColumn('status', function ($d) {
                if ($d->validasi_berkas_khusus == '0') {
                    $status = 'Tahap Validasi';
                    $warna = 'warning';
                } elseif ($d->validasi_berkas_khusus == '1') {
                    $status = 'Berkas OK';
                    $warna = 'success';
                } elseif ($d->validasi_berkas_khusus == '-1') {
                    $status = 'Berkas Ditolak';
                    $warna = 'danger';
                } else {
                    $warna = 'warning';
                    $status = 'Belum Upload Berkas';
                }
                return '<span class="badge bg-' . $warna . '">' . $status . '</span>';
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->KodePendaftaran);

                // Tombol untuk melihat detail/daftar berkas user
                $btn_berkas = '<a href="javascript:void(0)" data-id="' . $id . '" class="btn_lihat_berkas" style="margin-right: 10px;"><i title="Lihat Daftar Berkas" class="fa fa-eye text-info"></i></a>';

                // Tombol Approval
                $edit = '';
                // Admin tetap bisa klik tombol approval jika status 0 atau -1
                if ($d->validasi_berkas_khusus == '0' || $d->validasi_berkas_khusus == '-1') {
                    $edit = '<a href="#" data-id="' . $id . '" class="btn_approval"><i title="Approval Berkas" class="fa fa-edit text-orange"></i></a>';
                }

                return $btn_berkas . $edit;
            })
            ->rawColumns(['action', 'status', 'validator', 'pindahjalur']) // Tambahkan pindahjalur karena kita pakai tag HTML span
            ->make(true);
    }

    public function GetBerkasUser(Request $request)
    {
        try {
            $id = $request->id;
            if (empty($id)) {
                return DataTables::of([])->make(true);
            }

            // 1. Dekripsi ID untuk mendapatkan Kode Pendaftaran
            $kode_daftar = decrypt($id);
            $pendaftaran = Pendaftaran::where('KodePendaftaran', $kode_daftar)->first();

            if (!$pendaftaran) {
                return DataTables::of([])->make(true);
            }

            // 2. Ambil persyaratan berkas
            $id_jenis_berkas = $pendaftaran->berkas_khusus;
            $master_berkas = Master_Berkas::where('IdJenis', $id_jenis_berkas)->get();

            // 3. Tentukan folder penyimpanan berkas
            $params1 = Parameter::where('id', 1)->first();
            $folder = $params1 ? $params1->file_khusus : 'berkas_khusus';

            // OPTIMASI: Ambil SEMUA berkas yang sudah diupload pendaftar ini dalam 1x Query
            // keyBy('id_berkas') akan mengubah index array menjadi ID Berkas, sehingga mudah dicari
            $berkas_diupload = BerkasPendaftaran::where('kode_daftar', $kode_daftar)
                ->get()
                ->keyBy('id_berkas');

            $list_berkas = [];

            foreach ($master_berkas as $mb) {
                // OPTIMASI: Cukup cari di dalam Collection yang sudah ditarik, tidak perlu ke database lagi!
                // Ini setara dengan mengecek array, prosesnya instan.
                $cek_upload = $berkas_diupload->get($mb->id);

                $status_html = '<span class="badge bg-secondary">Belum Diupload</span>';
                $keterangan_html = '-';
                $action_html = '-';
                $validator_html = '-';

                if ($cek_upload) {
                    // LOGIKA VALIDATOR
                    if (!empty($cek_upload->nik_validasi_berkas)) {
                        $nip_val = $cek_upload->nik_validasi_berkas;
                        $validator_html = '<span class="badge bg-success">' . $nip_val . '</span><br>' .
                            '<small class="text-muted" style="font-weight:bold;">' . namaku($nip_val) . '</small>';
                    }

                    // LOGIKA STATUS
                    if ($cek_upload->status_berkas == '1') {
                        $status_html = '<span class="badge bg-success"><i class="fa fa-check"></i> Sesuai</span>';
                    } elseif ($cek_upload->status_berkas == '-1') {
                        $status_html = '<span class="badge bg-danger"><i class="fa fa-times"></i> Ditolak</span>';
                        $keterangan_html = $cek_upload->keterangan_berkas ?? '-';
                    } else {
                        $status_html = '<span class="badge bg-warning text-dark">Menunggu Validasi</span>';
                    }

                    // LOGIKA ACTION
                    //$link = asset('sources/storage/app/' . $folder . '/' . $cek_upload->nama_berkas);
		      $link = url('admin/file/' . strtoupper($folder) . '/' . $cek_upload->nama_berkas);

                    $action_html = '<a href="' . $link . '" target="_blank" class="btn btn-sm btn-outline-info mb-1 mr-1" style="border-radius: 6px; padding: 4px 10px;" title="Lihat Berkas"><i class="fa fa-eye"></i></a>';

                    if ($cek_upload->status_berkas != '1') {
                        $action_html .= '<button class="btn btn-sm btn-outline-warning btn-validasi-item mb-1 style="border-radius: 6px; padding: 4px 10px;"" 
                            data-kodedaftar="' . $kode_daftar . '" 
                            data-idberkas="' . $mb->id . '" 
                            data-namaberkas="' . $mb->nama_berkas . '" 
                            title="Verifikasi"><i class="fa fa-edit"></i></button>';
                    }
                }

                $list_berkas[] = [
                    'nama_berkas' => $mb->nama_berkas,
                    'validator'   => $validator_html,
                    'status'      => $status_html,
                    'keterangan'  => $keterangan_html,
                    'action'      => $action_html,
                ];
            }

            return DataTables::of($list_berkas)
                ->addIndexColumn()
                ->rawColumns(['status', 'keterangan', 'action', 'validator'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi Kesalahan Sistem']);
        }
    }

    public function saveApprovalBerkasItem(Request $request)
    {
        try {
            DB::beginTransaction();

            // Mengambil NIP dari session sesuai standar kode utama Anda
            $nip_admin = session('session')->nip ?? 'Admin';

            // PERBAIKAN: Langsung tembak ke kolom yang dicari
            $update = BerkasPendaftaran::where('kode_daftar', $request->kode_daftar)
                ->where('id_berkas', $request->id_berkas)
                ->update([
                    'status_berkas'       => $request->status,
                    'keterangan_berkas'   => $request->keterangan,
                    'nik_validasi_berkas' => $nip_admin,
                    // Opsional: Gunakan now() bawaan Laravel agar lebih elegan
                    'updated_at'          => now()
                ]);

            DB::commit();
            return response()->json([
                'title'   => 'Berhasil!',
                'message' => 'Berkas divalidasi oleh ' . namaku($nip_admin),
                'status'  => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'title'   => 'Error!',
                'message' => 'Terjadi Kesalahan Sistem',
                'status'  => 'error'
            ]);
        }
    }

    public function saveApprovalBerkas(Request $post)
    {
        try {
            // Pastikan kodedaftar memang dienkripsi dari View. Jika dari View tidak dienkripsi, 
            // hapus fungsi decrypt() dan gunakan langsung $post->kodedaftar;
            $id = decrypt($post->kodedaftar);

            // KITA HILANGKAN ->where('current_step', 5) AGAR LEBIH FLEKSIBEL
            $cek = Pendaftaran::where('KodePendaftaran', $id)->first();

            if (!$cek) {
                return response()->json([
                    'title' => 'Gagal!',
                    'message' => 'Data Pendaftaran tidak ditemukan!',
                    'status' => 'error'
                ], Response::HTTP_OK);
            }

            // --- 1. PENGECEKAN STATUS BERKAS PER ITEM ---
            $berkas_items = DB::table('pmb_berkas_pendaftaran')->where('kode_daftar', $id)->get();

            $ada_yang_menunggu = false;
            $ada_yang_ditolak = false;

            foreach ($berkas_items as $item) {
                if (empty($item->status_berkas) || $item->status_berkas == '0') {
                    $ada_yang_menunggu = true;
                }
                if ($item->status_berkas == '-1') {
                    $ada_yang_ditolak = true;
                }
            }

            // --- 2. LOGIKA PENENTUAN STATUS GLOBAL ---
            $status_global = $post->status == 'null' ? '0' : $post->status;

            // Jika Admin memilih "OK" tapi ada berkas yang menunggu atau ditolak
            if ($status_global == '1') {
                if ($ada_yang_menunggu) {
                    return response()->json([
                        'title' => 'Peringatan!',
                        'message' => 'Masih ada berkas yang belum divalidasi secara individual.',
                        'status' => 'warning'
                    ], Response::HTTP_OK);
                }
                if ($ada_yang_ditolak) {
                    return response()->json([
                        'title' => 'Peringatan!',
                        'message' => 'Tidak bisa meluluskan pendaftar ini karena ada berkas yang berstatus Ditolak/Revisi.',
                        'status' => 'warning'
                    ], Response::HTTP_OK);
                }
            }

            // --- 3. PROSES SIMPAN KE DATABASE ---
            DB::beginTransaction();

            $stepku = $cek->current_step;
            if ($status_global == '1') {
                // Pastikan jika stepnya sudah 6 atau lebih, tidak perlu ditambah lagi
                $stepku = ($cek->current_step < 6) ? 6 : $cek->current_step;
            }

            $pindahjalur = $post->pindahjalur == '0' ? null : '0';
            $keterangan = $post->keterangan;

            $updt = Pendaftaran::where('KodePendaftaran', $id)
                ->update([
                    'validasi_berkas_khusus'     => $status_global,
                    'update_berkaskhusus'        => null,
                    'nik_validasi_berkas_khusus' => session('session')->nip ?? 'Admin',
                    'tgl_validasi_berkas_khusus' => date('Y-m-d H:i:s'),
                    'status_pindah_jalur'        => $pindahjalur,
                    'keterangan'                 => $keterangan,
                    'current_step'               => $stepku,
                    'stop_step'                  => null,
                    'updated_at'                 => date('Y-m-d H:i:s'),
                    'isactive'                   => '1'
                ]);

            if ($updt) {
                DB::commit();
                return response()->json([
                    'title'   => 'Berhasil!',
                    'message' => $status_global == '-1' ? 'Keterangan Revisi Berkas Sudah Ditambahkan!' : 'Berhasil Finalisasi Validasi Berkas',
                    'status'  => 'success'
                ], Response::HTTP_OK);
            } else {
                DB::rollback();
                return response()->json([
                    'title'   => 'Gagal!',
                    'message' => 'Gagal Update Database',
                    'status'  => 'error'
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'title'   => 'Error Server!',
                'message' => 'Terjadi Kesalahan Sistem',
                'status'  => 'error'
            ], Response::HTTP_OK);
        }
    }
}
