<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Master_Berkas;
use App\Models\MasterData\Master_JenisPendaftaran;
use App\Models\MasterData\Master_TarifUKT;
use App\Models\MasterData\Master_Rekomendator;
use App\Models\Parameter;
use App\Models\User\PindahJalur;

use App\Models\Transaksi;
use App\Models\TransaksiHistory;
use App\Models\User\BerkasPendaftaran;
use App\Models\User\Biodata;
use App\Models\User\Maba;
use App\Models\User\Pendaftaran;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

use Symfony\Component\HttpFoundation\Response;
use Session, Crypt, DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Psy\Command\HistoryCommand;

class ValidasiBerkasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'title' => 'Berkas Beasiswa',
            'menu' => 'Berkas Beasiswa',
        );
        return view('user::user.berkas.index', $data);
    }

    public function tabelBerkasBeasiswa()
    {
        $bioId = decrypt(session('user')->_biodata);

        $data = Pendaftaran::where('pmb_pendaftaran.biodata_id', $bioId)
            ->join('pmb_master_jenispendaftaran as a', 'pmb_pendaftaran.jalur_daftar', '=', 'a.id')
            ->selectRaw('pmb_pendaftaran.*, a.berkas_khusus as list_berkas_diminta')
            ->whereNotNull('a.berkas_khusus')
            ->addSelect([
                'jml_diminta' => Master_Berkas::selectRaw('count(*)')
                    ->whereColumn('pmb_master_berkas.IdJenis', 'a.berkas_khusus')
            ])
            ->addSelect([
                'jml_diupload' => BerkasPendaftaran::selectRaw('count(*)')
                    ->whereColumn('pmb_berkas_pendaftaran.kode_daftar', 'pmb_pendaftaran.KodePendaftaran')
            ])
            ->with(['biodata', 'batch', 'jalur', 'jenisbeasiswa']);

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
                return $d->jalur->jenis_pendaftaran;
            })
            ->addColumn('beasiswa', function ($d) {
                return $d->jenisbeasiswa ? $d->jenisbeasiswa->jenis_beasiswa : '-';
            })
            ->addColumn('status', function ($d) {
                if ($d->validasi_berkas_khusus == '1') {
                    return '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Lolos</span>';
                } elseif ($d->validasi_berkas_khusus == '-1') {
                    return '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tidak Lolos</span>';
                } else {
                    if ($d->jml_diupload >= $d->jml_diminta && $d->jml_diminta > 0) {
                        return '<span class="badge bg-warning"><i class="fas fa-list-check"></i> Menunggu..</span>';
                    } else {
                        return '<span class="badge bg-primary text-dark"><i class="fas fa-exclamation-triangle"></i> Belum Lengkap</span>';
                    }
                }
            })
            ->addColumn('keterangan', function ($d) {
                return $d->keterangan ? $d->keterangan : '-';
            })
            ->addColumn('action', function ($d) {
                $id_raw = $d->KodePendaftaran;
                $id_encrypt = encrypt($d->KodePendaftaran);

                // 1. TOMBOL BARU: Mata (View Berkas) untuk buka Modal
                $btn = '<button type="button" class="btn btn-sm btn-outline-info btn-view-berkas me-1" style="border-radius: 6px; padding: 4px 10px;" data-id="' . $id_raw . '" title="Lihat Berkas Khusus"><i class="fa fa-eye"></i></button>';

                // 2. TOMBOL DETAIL (Lama): Tetap dipertahankan
                $btn .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-info btn-detail me-1" style="border-radius: 6px; padding: 4px 10px;" data-id="' . $id_raw . '" title="Lihat Detail"><i class="fa fa-info-circle"></i></a>';

                if ($d->validasi_berkas_khusus == '-1' && $d->status_pindah_jalur == '0') {
                    $btn .= '<br><div class="mt-2">';
                    $btn .= '<a href="#" class="btn btn-sm btn-outline-success setuju-pindah me-1" style="border-radius: 6px; padding: 4px 10px;" data-id="' . $id_encrypt . '" title="Setuju Pindah"><i class="fa fa-check-square fa-lg"></i></a>';
                    $btn .= '<a href="#" class="btn btn-sm btn-outline-danger tidaksetuju-pindah" style="border-radius: 6px; padding: 4px 10px;" data-id="' . $id_encrypt . '" title="Tolak"><i class="fa fa-window-close fa-lg"></i></a>';
                    $btn .= '</div>';
                }
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function GetBerkasUser(Request $request)
    {
        try {
            $kode_daftar = $request->kode_daftar;

            // 1. Identifikasi Kode Pendaftaran
            if (empty($kode_daftar)) {
                $bioId = decrypt(session('user')->_biodata);
                // PERBAIKAN 1: Hapus where('pmb_pendaftaran')
                $cek_daftar = Pendaftaran::where('biodata_id', $bioId)->first();

                if ($cek_daftar) {
                    $kode_daftar = $cek_daftar->KodePendaftaran;
                } else {
                    return response()->json([
                        'hasil' => 0,
                        'pesan' => 'Data pendaftaran tidak ditemukan dalam sesi Anda.'
                    ]);
                }
            }

            // 2. Ambil data pendaftaran (Gunakan 'with' untuk memanggil data jalur)
            // PERBAIKAN 2: Hapus where('pmb_pendaftaran') dan tambahkan with('jalur')
            $pendaftaran = Pendaftaran::with('jalur')
                ->where('KodePendaftaran', $kode_daftar)
                ->first();

            if (!$pendaftaran) {
                return response()->json([
                    'hasil' => 0,
                    'pesan' => 'Data Pendaftaran tidak ditemukan untuk Kode: ' . $kode_daftar
                ]);
            }

            // 3. OPTIMASI: Langsung cek kolom berkas_khusus melalui relasi JALUR
            // PERBAIKAN 3: Panggil berkas_khusus dari tabel master jalur
            $id_berkas_khusus = $pendaftaran->jalur->berkas_khusus ?? null;

            if (empty($id_berkas_khusus)) {
                return response()->json([
                    'hasil' => 1,
                    'kode_daftar' => $kode_daftar,
                    'data' => []
                ]);
            }

            // 4. Ambil daftar master berkas berdasarkan ID
            // PERBAIKAN 4: Hapus where('pmb_master_berkas')
            $master_berkas = Master_Berkas::where('IdJenis', $id_berkas_khusus)->get();

            $list_berkas = [];

            // 5. Loop untuk mengecek status upload tiap berkas
            foreach ($master_berkas as $mb) {
                // PERBAIKAN 5: Hapus where('pmb_berkas_pendaftaran')
                $cek_upload = BerkasPendaftaran::where('kode_daftar', $kode_daftar)
                    ->where('id_berkas', $mb->id)
                    ->first();

                $status_html = '';
                $keterangan_file = '-';
                $status_angka = 99;

                if (!$cek_upload) {
                    $status_html = '<span class="badge bg-secondary">Belum Diupload</span>';
                } else {
                    $status_angka = $cek_upload->status_berkas;

                    if ($cek_upload->status_berkas == '1') {
                        $status_html = '<span class="badge bg-success"><i class="fas fa-check"></i> Berkas Disetujui</span>';
                    } elseif ($cek_upload->status_berkas == '-1') {
                        $status_html = '<span class="badge bg-danger"><i class="fas fa-times"></i> Berkas Ditolak</span>';
                        $keterangan_file = $cek_upload->keterangan_berkas ?? 'Silakan upload ulang file yang sesuai.';
                    } else {
                        $status_html = '<span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Menunggu Verifikasi</span>';
                    }
                }

                $list_berkas[] = [
                    'id_berkas'    => $mb->id,
                    'kode_berkas'  => $mb->KodeBerkas,
                    'nama_berkas'  => $mb->nama_berkas,
                    'is_uploaded'  => $cek_upload ? true : false,
                    'file_name'    => $cek_upload ? $cek_upload->nama_berkas : null,
                    'status_html'  => $status_html,
                    'status_angka' => $status_angka,
                    'keterangan'   => $keterangan_file,
                ];
            }

            // 6. Kembalikan hasil akhir ke Frontend
            return response()->json([
                'hasil'       => 1,
                'kode_daftar' => $kode_daftar,
                'data'        => $list_berkas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'hasil' => 0,
                'pesan' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    public function showBerkasBeasiswa($params)
    {
        $id = $params;
        $bioId = decrypt(session('user')->_biodata);

        $cek1 = Pendaftaran::where('KodePendaftaran', $id)->where('biodata_id', $bioId)->with([
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
        $prodi2 = null;
        $prodi3 = null;

        if ($cek1) {
            if ($cek1->prodi1) {
                $prodi1 = Master_TarifUKT::where('idbatch', $cek1->batch_daftar)
                    ->where('idjalur', $cek1->jalur_daftar)
                    ->where('idjurusan', $cek1->prodi1->id)
                    ->where('isactive', 1)
                    ->first();
            }

            // PERBAIKAN: Cek dulu apakah relasi prodi2 tidak null
            if ($cek1->prodi2) {
                $prodi2 = Master_TarifUKT::where('idbatch', $cek1->batch_daftar)
                    ->where('idjalur', $cek1->jalur_daftar)
                    ->where('idjurusan', $cek1->prodi2->id)
                    ->where('isactive', 1)
                    ->first();
            }

            // <-- TAMBAHAN PENGECEKAN UKT PRODI 3
            if ($cek1->prodi3) {
                $prodi3 = Master_TarifUKT::where('idbatch', $cek1->batch_daftar)
                    ->where('idjalur', $cek1->jalur_daftar)
                    ->where('idjurusan', $cek1->prodi3->id)
                    ->where('isactive', 1)
                    ->first();
            }

            $rekomendator_text = '-';
            if ($cek1->rekomendator) {
                $rek = Master_Rekomendator::where('kode_rekomendator', $cek1->rekomendator)->first();
                if ($rek) {
                    $rekomendator_text = $rek->nama_rekomendator . ' (' . $rek->kode_rekomendator . ')';
                } else {
                    $rekomendator_text = $cek1->rekomendator;
                }
            }

            $data['hasil'] = 1;
            $data['daftar'] = $cek1;
            $data['IdDaftar'] = $params;
            $data['ukt1'] = $prodi1;
            $data['ukt2'] = $prodi2;
            $data['ukt3'] = $prodi3;
            $data['rekomendator'] = $rekomendator_text;
        } else {
            $data['hasil'] = 0;
            $data['daftar'] = null;
            $data['IdDaftar'] = null;
            $data['ukt1'] = null;
            $data['ukt2'] = null;
            $data['ukt3'] = null;
            $data['rekomendator'] = '-';
        }

        return response()->json($data, 200);
    }

    public function saveBerkas(Request $request)
    {
        $kddftar = $request->iddaftar;
        $id_berkas = $request->id_berkas;
        $kode_berkas = $request->kode_berkas;

        $request->validate([
            'iddaftar' => 'required',
            'id_berkas' => 'required',
            'kode_berkas' => 'required',
            'berkaskhusus' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'berkaskhusus.required' => 'File belum dipilih!',
            'berkaskhusus.file' => 'Upload harus berupa file yang valid!',
            'berkaskhusus.mimes' => 'Format berkas hanya boleh PDF, JPG, JPEG, atau PNG!',
            'berkaskhusus.max' => 'Ukuran berkas maksimal 5MB!',
        ]);

        $fileUpload = $request->file('berkaskhusus');

        $ext = strtolower($fileUpload->getClientOriginalExtension());
        $originalName = pathinfo($fileUpload->getClientOriginalName(), PATHINFO_FILENAME);
        $cleanName = Str::slug($originalName);
        $filename = $kddftar . '_' . $kode_berkas . '_' . $cleanName . '.' . $ext;

        DB::beginTransaction();

        try {
            $parameter = Parameter::where('id', 1)->first();
            $folder_path = $parameter->file_khusus ?? 'berkas_khusus';

            $existing = BerkasPendaftaran::where('kode_daftar', $kddftar)
                ->where('id_berkas', $id_berkas)
                ->first();

            $user_login = session('user') ? session('user')->nama : $kddftar;

            if ($existing) {
                $old_path = $folder_path . '/' . $existing->nama_berkas;
                if (Storage::exists($old_path)) {
                    Storage::delete($old_path);
                }

                $existing->update([
                    'nama_berkas' => $filename,
                    'updated_by'  => $user_login,
                ]);
            } else {
                BerkasPendaftaran::create([
                    'kode_daftar' => $kddftar,
                    'id_berkas'   => $id_berkas,
                    'nama_berkas' => $filename,
                    'created_by'  => $user_login,
                ]);
            }

            // Simpan file ke Storage
            $fileUpload->storeAs($folder_path, $filename);

        // --- MULAI PERUBAHAN DI SINI ---
        // PERBAIKAN 5: Cek step dan update pendaftaran sekaligus
        $cekstep = Pendaftaran::where('KodePendaftaran', $kddftar)->select('current_step')->first();

        Pendaftaran::where('KodePendaftaran', $kddftar)
            ->update([
                'current_step'           => ($cekstep->current_step == 4) ? $cekstep->current_step + 1 : $cekstep->current_step,
                'validasi_berkas_khusus' => '0',
                'updated_at'             => date('Y-m-d H:i:s')
            ]);
        // --- SELESAI PERUBAHAN DI SINI ---

        DB::commit();

        return response()->json([
            'status'  => 'success',
            'title'   => 'Berhasil',
            'message' => 'Upload Berkas Sukses!'
        ], 200);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'status'  => 'error',
            'title'   => 'Gagal',
            'message' => 'Terjadi kesalahan pada server saat memproses file Anda. Silakan coba lagi.'
        ], 500);
    }
}

    public function PindahJalur($params1, $params2)
    {
        try {
            $id = decrypt($params1);
        } catch (\Exception $e) {
            return response()->json([
                'title' => 'Gagal',
                'message' => 'Kode Pendaftaran tidak valid atau rusak!',
                'status' => 'error'
            ], 200);
        }

        $pindahjalur = $params2;

        $cekdaftar = Pendaftaran::where('KodePendaftaran', $id)->where('isactive', 1)->first();

        if (!$cekdaftar) {
            return response()->json([
                'title' => 'Gagal',
                'message' => 'Data Pendaftaran tidak ditemukan!',
                'status' => 'error'
            ], 200);
        }

        DB::beginTransaction();

        try {
            if ($pindahjalur == '-1') {
                // PROSES 1: MENGUNDURKAN DIRI
                // OPTIMASI: Langsung update dari variabel $cekdaftar
                $cekdaftar->update([
                    'keterangan' => 'Anda dinyatakan mengundurkan diri ! Silahkan Daftar pada batch selanjutnya.',
                    'status_pindah_jalur' => $pindahjalur,
                    'stop_step' => $cekdaftar->current_step,
                    'isactive' => '0'
                    // updated_at sudah otomatis diisi oleh Eloquent
                ]);

                DB::commit();

                return response()->json([
                    'title' => 'Berhasil',
                    'message' => 'Anda dinyatakan mengundurkan diri ! Silahkan Daftar pada batch selanjutnya.',
                    'status' => 'success'
                ], 200);
            } else {
                // PROSES 2: PINDAH KE REGULER
                $cekjalur = Master_JenisPendaftaran::where('jenis_pendaftaran', 'like', '%reguler%')
                    ->where('isactive', 1)
                    ->first();

                // PENCEGAHAN ERROR: Pastikan jalur reguler ditemukan
                if (!$cekjalur) {
                    return response()->json([
                        'title' => 'Gagal',
                        'message' => 'Jalur Reguler tidak ditemukan atau sedang tidak aktif!',
                        'status' => 'error'
                    ], 200);
                }

                // Tracking pindah jalur
                PindahJalur::insert([
                    'biodata_id' => $cekdaftar->biodata_id,
                    'KodePendaftaran' => $id,
                    'batch' => $cekdaftar->batch_daftar,
                    'jalur' => $cekdaftar->jalur_daftar,
                    'beasiswa' => $cekdaftar->beasiswa,
                    'berkas_khusus' => $cekdaftar->berkas_khusus,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // Tentukan lompatan step agar kita cukup update 1 kali saja
                $next_step = ($cekdaftar->bayar_pendaftaran == '-1') ? 2 : 6;

                // OPTIMASI: Update SEMUA data pendaftaran sekaligus
                $cekdaftar->update([
                    'bayar_pendaftaran'          => $cekdaftar->bayar_pendaftaran == '-1' ? '0' : $cekdaftar->bayar_pendaftaran,
                    'batch_daftar'               => $cekdaftar->batch_daftar,
                    'jalur_daftar'               => $cekjalur->id,
                    'beasiswa'                   => null,
                    'bayar_ukt'                  => '0',
                    'berkas_khusus'              => null,
                    'validasi_berkas_khusus'     => null,
                    'nik_validasi_berkas_khusus' => null,
                    'tgl_validasi_berkas_khusus' => null,
                    'keterangan'                 => null,
                    'status_pindah_jalur'        => '1',
                    'current_step'               => $next_step
                ]);

                // Cek Biaya Pendaftaran & Update Transaksi
                if ($cekdaftar->bayar_pendaftaran == '-1') {
                    $transaksi = Transaksi::where('user_id', $cekdaftar->biodata_id)
                        ->where('id_referensi', $id)
                        ->first();

                    if ($transaksi) {
                        // OPTIMASI: Update langsung dari variabel $transaksi
                        $transaksi->update([
                            'jumlah' => $cekjalur->jml_biaya_pendaftaran,
                            'status' => 'pending',
                            'metode_bayar' => null,
                            'midtrans_order_id' => null,
                            'midtrans_snap_token' => null,
                            'expired_at' => null
                        ]);

                        TransaksiHistory::where('transaksi_id', $transaksi->id)->delete();
                        TransaksiHistory::insert([
                            'transaksi_id' => $transaksi->id,
                            'status' => 'pending',
                            'keterangan' => 'Menunggu pembayaran (Pindah Jalur)',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }

                DB::commit();
                return response()->json([
                    'title' => 'Berhasil',
                    'message' => 'Anda Sudah Berpindah ke jalur pendaftaran Reguler. Silahkan Lanjut Ke Proses Selanjutnya!',
                    'status' => 'success'
                ], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'title' => 'Gagal',
                'message' => 'Gagal Berpindah Jalur, Silahkan coba lagi.',
                'status' => 'error'
            ], 200);
        }
    }
}
