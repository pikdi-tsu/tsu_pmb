<?php

use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Response;
    use Modules\Admin\Http\Controllers\BerkasPMBController;
    use Modules\Admin\Http\Controllers\DashboardController;
    use Modules\Admin\Http\Controllers\DataBeasiswaContoller;
    use Modules\Admin\Http\Controllers\DataNonBeasiswaController;
    use Modules\Admin\Http\Controllers\LoginController;
    use Modules\Admin\Http\Controllers\SettingController;
    use Modules\Admin\Http\Controllers\PembayaranPMBController;
    use Modules\Admin\Http\Controllers\PembayaranUKTController;
    use Modules\Admin\Http\Controllers\TestAssesmentController;
    use Modules\Assessment\Http\Controllers\MonitoringAssesmentController;
    use Modules\Admin\Http\Controllers\NimController;
    use Modules\Admin\Http\Controllers\EmailPMBController;
    use Modules\Admin\Http\Controllers\FinalPMBController;
    use Modules\User\Http\Controllers\KartuPesertaController;
    use Modules\Admin\Http\Controllers\masterdata\BatchPendaftaranController;
    use Modules\Admin\Http\Controllers\masterdata\BeasiswaController;
    use Modules\Admin\Http\Controllers\masterdata\BerkasController;
    use Modules\Admin\Http\Controllers\masterdata\ContentController;
    use Modules\Admin\Http\Controllers\masterdata\FakultasController;
    use Modules\Admin\Http\Controllers\masterdata\JenisBerkasController;
    use Modules\Admin\Http\Controllers\masterdata\JenisPendaftaranController;
    use Modules\Admin\Http\Controllers\masterdata\JenjangController;
    use Modules\Admin\Http\Controllers\masterdata\JurusanController;
    use Modules\Admin\Http\Controllers\masterdata\JurusanSekolahController;
    use Modules\Admin\Http\Controllers\masterdata\KabupatenController;
    use Modules\Admin\Http\Controllers\masterdata\KecamatanController;
    use Modules\Admin\Http\Controllers\masterdata\KelurahanController;
    use Modules\Admin\Http\Controllers\masterdata\ProvinsiController;
    use Modules\Admin\Http\Controllers\masterdata\SoalTestController;
    use Modules\Admin\Http\Controllers\masterdata\TarifUKTController;
    use Modules\Admin\Http\Controllers\masterdata\TingkatKejuaraanController;
    use Modules\Admin\Http\Controllers\masterdata\RekomendatorController;
    use Modules\Admin\Http\Controllers\TestPMBController;
    use Modules\Admin\Http\Controllers\LogAktivitasController;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    Route::middleware(['web'])->group(function () {
        Route::prefix('admin')->group(function () {

            Route::get('/file/{folder}/{filename}', function ($folder, $filename) {
                $cleanFolder   = basename(trim($folder));
                $cleanFilename = basename(trim($filename));
                $lowerFolder   = strtolower($cleanFolder);

                $folderMap = [
                    'file_photoprofile'        => 'FILE_PHOTOPROFILE',
                    'bukti_pendaftaran'        => 'BUKTI_PENDAFTARAN',
                    'bukti_bayar_pendaftaran'  => 'BUKTI_PENDAFTARAN',
                    'bukti_ukt'                => 'BUKTI_UKT',
                    'bukti_bayar_ukt'          => 'BUKTI_UKT',
                    'file_khusus'              => 'FILE_KHUSUS',
                    'berkas_khusus'            => 'FILE_KHUSUS',
                    'file_umum'                => 'FILE_UMUM',
                    'berkas_umum'              => 'FILE_UMUM',
                    'file_template'            => 'FILE_TEMPLATE',
                    'browsur'                  => 'Browsur',
                ];

                abort_unless(array_key_exists($lowerFolder, $folderMap), 403);
                $realFolder = $folderMap[$lowerFolder];

                // Pastikan yang mengakses dokumen pendaftaran/berkas adalah user atau admin yang login
                if ($realFolder !== 'FILE_PHOTOPROFILE' && $realFolder !== 'Browsur' && $realFolder !== 'FILE_TEMPLATE') {
                    abort_unless(session()->has('session') || session()->has('user'), 401);
                }

                $path = storage_path('app/' . $realFolder . '/' . $cleanFilename);

                if (!file_exists($path)) {
                    // Fallback foto profil jika file tidak ditemukan
                    if ($realFolder === 'FILE_PHOTOPROFILE') {
                        $fallback = storage_path('app/FILE_PHOTOPROFILE/user.png');
                        if (file_exists($fallback)) {
                            $path = $fallback;
                        } else {
                            $fallbackPublic = public_path('assets/img/user.png');
                            if (file_exists($fallbackPublic)) {
                                $path = $fallbackPublic;
                            } else {
                                abort(404);
                            }
                        }
                    } else {
                        abort(404);
                    }
                }

                // Bersihin semua output buffer
                if (ob_get_level()) {
                    ob_end_clean();
                }

                return response()->stream(function () use ($path) {
                    readfile($path);
                }, 200, [
                    'Content-Type'        => mime_content_type($path),
                    'Content-Length'      => filesize($path),
                    'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
                    'Cache-Control'       => 'no-cache, no-store, must-revalidate'
                ]);
            });

            Route::get('/', [LoginController::class, 'index'])->name('loginadmin');
            Route::post('/loginaction', [LoginController::class, 'loginaction'])->name('admin.loginaction');
            Route::get('/loginChance', [LoginController::class, 'loginChance'])->name('admin.loginchance');
            Route::get('/NewPassword', [LoginController::class, 'newPassword'])->name('admin.NewPassword');
            Route::post('/NewPasswordAction', [LoginController::class, 'newPasswordAction'])->name('admin.NewPasswordAction');
            Route::get('/checkbirthday', [LoginController::class, 'checkbirthday']);
            Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

            //forgot password
            Route::get('/ForgotPassword', [LoginController::class, 'forgotPassword'])->name('admin.ForgotPassword.show');
            Route::post('/ForgotPasswordAction', [LoginController::class, 'ActionSendLink'])->name('admin.ForgotPassword.SendLink');
            Route::get('/form_ForgotPassword/{params}', [LoginController::class, 'FormForgotPassword'])->name('admin.ForgotPassword.formreset');
            Route::post('/Action_ForgotPassword/{params}', [LoginController::class, 'ForgotPasswordAction'])->name('admin.ForgotPassword.ActionReset');

            Route::middleware(['checkadmin'])->group(function () {
                Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
                Route::prefix('DataPendaftaran')->group(function () {
                    Route::prefix('Beasiswa')->group(function () {
                        Route::get('/', [DataBeasiswaContoller::class, 'index'])->name('admin.databeasiswa.show');
                        Route::get('/TabelBeasiswa', [DataBeasiswaContoller::class, 'tabelBeasiswa'])->name('admin.databeasiswa.Tabel');
                        Route::get('/DetailBeasiswa/{params}', [DataBeasiswaContoller::class, 'showBeasiswa'])->name('admin.databeasiswa.detail');
                        Route::get('/CariRekomendator', [DataBeasiswaContoller::class, 'cariRekomendator'])->name('admin.databeasiswa.carirekomendator');
                        Route::post('/UpdateRekomendator', [DataBeasiswaContoller::class, 'updateRekomendator'])->name('admin.databeasiswa.updaterekomendator');
                        Route::post('/HapusData', [DataBeasiswaContoller::class, 'hapusdata'])->name('admin.databeasiswa.hapusdata');
                        Route::get('/EditJurusan/{params}', [DataBeasiswaContoller::class, 'editJurusan'])->name('admin.databeasiswa.editjurusan');
                        Route::post('/UpdateJurusan', [DataBeasiswaContoller::class, 'updateJurusan'])->name('admin.databeasiswa.updatejurusan');
                        Route::get('/ExportExcel', [DataBeasiswaContoller::class, 'exportExcel'])->name('admin.databeasiswa.exportexcel');
                    });
                    Route::prefix('NonBeasiswa')->group(function () {
                        Route::get('/', [DataNonBeasiswaController::class, 'index'])->name('admin.datanonbeasiswa.show');
                        Route::get('/TabelNonBeasiswa', [DataNonBeasiswaController::class, 'tabelNonBeasiswa'])->name('admin.datanonbeasiswa.Tabel');
                        Route::get('/DetailNonBeasiswa/{params}', [DataNonBeasiswaController::class, 'showNonBeasiswa'])->name('admin.datanonbeasiswa.detail');
                        Route::post('/UpdateRekomendator', [DataNonBeasiswaController::class, 'updateRekomendator'])->name('admin.datanonbeasiswa.updaterekomendator');
                        Route::get('/EditJurusan/{params}', [DataNonBeasiswaController::class, 'editJurusan'])->name('admin.datanonbeasiswa.editjurusan');
                        Route::post('/UpdateJurusan', [DataNonBeasiswaController::class, 'updateJurusan'])->name('admin.datanonbeasiswa.updatejurusan');
                        Route::get('/ExportExcel', [DataNonBeasiswaController::class, 'exportExcel'])->name('admin.datanonbeasiswa.exportexcel');
                    });
                    Route::get('/KartuPeserta/{kode}', [KartuPesertaController::class, 'download'])->name('admin.kartupeserta.download');
                });

                //Pembayaran Pendaftaran
                Route::prefix('PembayaranPMB')->group(function () {
                    Route::get('/', [PembayaranPMBController::class, 'index'])->name('admin.pembayaranpmb.show');
                    Route::get('/TabelPembayaranPMB', [PembayaranPMBController::class, 'tabelPembayaranPMB']);
                    Route::get('/ShowPembayaranPMB/{params}', [PembayaranPMBController::class, 'showPayment']);
                    Route::get('/Approve/{params}', [PembayaranPMBController::class, 'approve']);
                    Route::post('/Revisi', [PembayaranPMBController::class, 'revisi'])->name('admin.pembayaranpmb.revisi');
                    Route::get('/ExportExcel', [PembayaranPMBController::class, 'exportExcel'])->name('admin.pembayaranpmb.exportexcel');
                });

                //Pembayaran UKT
                Route::prefix('PembayaranUKT')->group(function () {
                    Route::get('/', [PembayaranUKTController::class, 'index'])->name('admin.pembayaranukt.show');
                    Route::get('/tabelPembayaranUKT', [PembayaranUKTController::class, 'tabelPembayaranUKT']);
                    Route::get('/ShowPembayaranUKT/{params}', [PembayaranUKTController::class, 'showPayment']);
                    Route::get('/Approve/{params}', [PembayaranUKTController::class, 'approve']);
                    Route::post('/Revisi', [PembayaranUKTController::class, 'revisi'])->name('admin.pembayaranukt.revisi');
                    Route::get('/ExportExcel', [PembayaranUKTController::class, 'exportExcel'])->name('admin.pembayaranukt.exportexcel');
                });

                Route::prefix('BerkasPMB')->group(function () {
                    Route::get('/', [BerkasPMBController::class, 'index'])->name('admin.berkaspmb.show');
                    Route::get('/TabelBerkasPMB', [BerkasPMBController::class, 'tabelBerkasPMB'])->name('admin.berkaspmb.tabel');
                    Route::post('/saveApprovalBerkas', [BerkasPMBController::class, 'saveApprovalBerkas'])->name('admin.berkaspmb.save');
                    Route::get('/GetBerkasUser', [BerkasPMBController::class, 'GetBerkasUser'])->name('admin.getberkasuser');
                    Route::post('/saveApprovalBerkasItem', [BerkasPMBController::class, 'saveApprovalBerkasItem'])->name('admin.berkaspmb.saveitem');
                });

                Route::prefix('TestOnlinePMB')->group(function () {
                    Route::get('/', [TestPMBController::class, 'index'])->name('admin.testpmb.show');
                    Route::get('/TabelTestOnlinePMB', [TestPMBController::class, 'tabelTestPMB'])->name('admin.testpmb.tabel');
                    Route::get('/DetailTestOnlinePMB/{params}', [TestPMBController::class, 'showDetailTest'])->name('admin.testpmb.showdetail');
                    Route::get('/ShowJurusanDiterima/{params}', [TestPMBController::class, 'show_jurusan']);
                    Route::post('/SavehasilTestOnline', [TestPMBController::class, 'hasil_test'])->name('admin.testpmb.save');
                });

                Route::prefix('TestAssesment')->group(function () {
                    Route::get('/', [TestAssesmentController::class, 'index'])->name('admin.testassesment.show');
                    Route::get('/TabelTestAssesment', [TestAssesmentController::class, 'tabelTestPMB'])->name('admin.testassesment.tabel');
                    Route::get('/DetailTestOnlinePMB/{params}', [TestAssesmentController::class, 'showDetailTest'])->name('admin.testassesment.showdetail');
                    Route::get('/ShowJurusanDiterima/{params}', [TestAssesmentController::class, 'show_jurusan']);
                    Route::post('/SavehasilTestOnline', [TestAssesmentController::class, 'hasil_test'])->name('admin.testassesment.save');
                    Route::get('/PrintDISC/{attempt_id}', [MonitoringAssesmentController::class, 'printDisc'])->name('admin.monitoringassesment.printdisc');
                    Route::post('/Reset', [TestAssesmentController::class, 'reset_test'])->name('admin.testassesment.reset');
                });

                Route::prefix('MonitoringAssesment')->group(function () {
                    Route::get('/', [MonitoringAssesmentController::class, 'index'])->name('admin.monitoringassesment.show');
                    Route::get('/TabelMonitoringAssesment', [MonitoringAssesmentController::class, 'tabelMonitoring'])->name('admin.monitoringassesment.tabel');
                    Route::get('/DetailTestOnlinePMB/{params}', [MonitoringAssesmentController::class, 'showDetailTest'])->name('admin.monitoringassesment.showdetail');
                    Route::get('/PrintDISC/{attempt_id}', [MonitoringAssesmentController::class, 'printDisc'])->name('admin.monitoringassesment.printdisc');
                });

                // Generate NIM
                Route::prefix('GenerateNIM')->group(function () {
                    Route::get('/', [NimController::class, 'index'])->name('admin.nim.index');
                    Route::post('/proses', [NimController::class, 'generate'])->name('admin.nim.proses');
                    Route::get('/ExportExcel', [NimController::class, 'exportExcel'])->name('admin.nim.exportexcel');
                });

                Route::prefix('EmailPMB')->group(function () {
                    Route::get('/', [EmailPMBController::class, 'index'])->name('admin.emailpmb.show');
                });
                Route::prefix('FinalPMB')->group(function () {
                    Route::get('/', [FinalPMBController::class, 'index'])->name('admin.finalpmb.show');
                    Route::get('/TabelFinal', [FinalPMBController::class, 'tabel_final'])->name('admin.finalpmb.tabel');
                    Route::get('/DetailFinal/{params}/{form}', [FinalPMBController::class, 'Detail'])->name('admin.finalpmb.detail');
                    Route::post('/UpdateFinal', [FinalPMBController::class, 'update'])->name('admin.finalpmb.update');
                    Route::get('/ChangeKabupaten/{prov}', [FinalPMBController::class, 'ChangeKabupaten']);
                    Route::get('/ChangeKecamatan/{prov}/{kab}', [FinalPMBController::class, 'ChangeKecamatan']);
                    Route::get('/ChangeKelurahan/{prov}/{kab}/{kec}', [FinalPMBController::class, 'ChangeKelurahan']);
                    Route::get('/EditJurusan/{params}', [FinalPMBController::class, 'editJurusan'])->name('admin.finalpmb.editjurusan');
                    Route::post('/UpdateJurusan', [FinalPMBController::class, 'updateJurusan'])->name('admin.finalpmb.updatejurusan');
                });
                Route::prefix('MasterData')->group(function () {
                    Route::prefix('BatchPendaftaran')->group(function () {
                        Route::get('/', [BatchPendaftaranController::class, 'index'])->name('admin.BatchPendaftaran.show');
                        Route::get('/TabelBatch', [BatchPendaftaranController::class, 'TabelBatch'])->name('admin.BatchPendaftaran.Tabel');
                        Route::post('/Store', [BatchPendaftaranController::class, 'StoreBatch'])->name('admin.BatchPendaftaran.Store');
                        Route::get('/EditBatch/{params}', [BatchPendaftaranController::class, 'ShowBatch'])->name('admin.BatchPendaftaran.Edit');
                        Route::get('/Status/{params1}/{params2}', [BatchPendaftaranController::class, 'delete'])->name('admin.BatchPendaftaran.delete');
                    });
                    Route::prefix('JenisPendaftaran')->group(function () {
                        Route::get('/', [JenisPendaftaranController::class, 'index'])->name('admin.JenisPendaftaran.show');
                        Route::get('/TabelJenis', [JenisPendaftaranController::class, 'table_Pendaftaran'])->name('admin.JenisPendaftaran.Tabel');
                        Route::post('/Store', [JenisPendaftaranController::class, 'StoreJalur'])->name('admin.JenisPendaftaran.Store');
                        Route::get('/EditJenis/{params}', [JenisPendaftaranController::class, 'ShowJalur'])->name('admin.JenisPendaftaran.Edit');
                        Route::get('/Status/{params1}/{params2}', [JenisPendaftaranController::class, 'delete'])->name('admin.JenisPendaftaran.delete');
                        Route::get('/StatusAktif/{params1}/{params2}', [JenisPendaftaranController::class, 'Mengaktifkan'])->name('admin.JenisPendaftaran.mengaktifkan');
                    });
                    Route::prefix('Beasiswa')->group(function () {
                        Route::get('/', [BeasiswaController::class, 'index'])->name('admin.Beasiswa.show');
                        Route::get('/TabelBeasiswa', [BeasiswaController::class, 'TabelBeasiswa'])->name('admin.Beasiswa.Tabel');
                        Route::post('/Store', [BeasiswaController::class, 'StoreBeasiswa'])->name('admin.Beasiswa.Store');
                        Route::get('/EditBeasiswa/{params}', [BeasiswaController::class, 'ShowBeasiswa'])->name('admin.Beasiswa.Edit');
                        Route::get('/Status/{params1}/{params2}', [BeasiswaController::class, 'delete'])->name('admin.Beasiswa.delete');
                    });
                    Route::prefix('TingkatKejuaraan')->group(function () {
                        Route::get('/', [TingkatKejuaraanController::class, 'index'])->name('admin.TingkatKejuaraan.show');
                        Route::get('/TabelTingkatKejuaraan', [TingkatKejuaraanController::class, 'TabelTingkatKejuaraan'])->name('admin.TingkatKejuaraan.Tabel');
                        Route::post('/StoreTingkat', [TingkatKejuaraanController::class, 'storeTingkat'])->name('admin.TingkatKejuaraan.Store');
                        Route::get('/EditTingkat/{params}', [TingkatKejuaraanController::class, 'ShowTingkat'])->name('admin.TingkatKejuaraan.Edit');
                        Route::get('/Delete/{params}', [TingkatKejuaraanController::class, 'delete'])->name('admin.TingkatKejuaraan.delete');
                    });
                    Route::prefix('Provinsi')->group(function () {
                        Route::get('/', [ProvinsiController::class, 'index'])->name('admin.Provinsi.show');
                        Route::get('/TabelProvinsi', [ProvinsiController::class, 'TabelProvinsi'])->name('admin.Provinsi.Tabel');
                    });
                    Route::prefix('Rekomendator')->group(function () {
                        Route::get('/', [RekomendatorController::class, 'index'])->name('admin.Rekomendator.show');
                        Route::get('/TabelRekomendator', [RekomendatorController::class, 'TabelRekomendator'])->name('admin.Rekomendator.Tabel');
                        Route::post('/Store', [RekomendatorController::class, 'StoreRekomendator'])->name('admin.Rekomendator.Store');
                        Route::get('/Edit/{params}', [RekomendatorController::class, 'ShowRekomendator'])->name('admin.Rekomendator.Edit');
                        Route::get('/Status/{params1}/{params2}', [RekomendatorController::class, 'delete'])->name('admin.Rekomendator.delete');
                        Route::get('/Destroy/{params}', [RekomendatorController::class, 'destroy'])->name('admin.Rekomendator.Destroy');
                        Route::post('/UploadExcel', [RekomendatorController::class, 'importExcel'])->name('admin.Rekomendator.UploadExcel');
                        Route::get('/TemplateExcel', [RekomendatorController::class, 'downloadTemplate'])->name('admin.Rekomendator.TemplateExcel');
                        Route::get('/MigrationTemplateExcel', [RekomendatorController::class, 'downloadMigrationTemplate'])->name('admin.Rekomendator.MigrationTemplateExcel');
                        Route::get('/KirimEmail/{params}', [RekomendatorController::class, 'kirimemail'])->name('admin.Rekomendator.KirimEmail');
                    });
                    Route::prefix('Kabupaten')->group(function () {
                        Route::get('/', [KabupatenController::class, 'index'])->name('admin.Kabupaten.show');
                        Route::get('/TabelKabupaten', [KabupatenController::class, 'TabelKabupaten'])->name('admin.Kabupaten.Tabel');
                    });
                    Route::prefix('Kecamatan')->group(function () {
                        Route::get('/', [KecamatanController::class, 'index'])->name('admin.Kecamatan.show');
                        Route::get('/TabelKecamatan', [KecamatanController::class, 'TabelKecamatan'])->name('admin.Kecamatan.Tabel');
                    });
                    Route::prefix('Kelurahan')->group(function () {
                        Route::get('/', [KelurahanController::class, 'index'])->name('admin.Kelurahan.show');
                        Route::get('/TabelKelurahan', [KelurahanController::class, 'TabelKelurahan'])->name('admin.Kelurahan.Tabel');
                    });
                    Route::prefix('TarifUKT')->group(function () {
                        Route::get('/', [TarifUKTController::class, 'index'])->name('admin.TarifUKT.show');
                        Route::get('/TabelUKT', [TarifUKTController::class, 'TabelUKT'])->name('admin.TarifUKT.Tabel');
                        Route::post('/Store', [TarifUKTController::class, 'StoreUKT'])->name('admin.TarifUKT.Store');
                        Route::get('/EditUKT/{params}', [TarifUKTController::class, 'ShowUKT'])->name('admin.TarifUKT.Edit');
                        Route::get('/Status/{params1}/{params2}', [TarifUKTController::class, 'delete'])->name('admin.TarifUKT.delete');
                        Route::get('/StatusAktif/{params1}/{params2}', [TarifUKTController::class, 'Mengaktifkan'])->name('admin.TarifUKT.mengaktifkan');
                    });
                    Route::prefix('Fakultas')->group(function () {
                        Route::get('/', [FakultasController::class, 'index'])->name('admin.fakultas.show');
                        Route::get('/TabelFakultas', [FakultasController::class, 'table_fakultas'])->name('admin.fakultas.Tabel');
                        Route::post('/Store', [FakultasController::class, 'StoreFakultas'])->name('admin.fakultas.Store');
                        Route::get('/EditFakultas/{params}', [FakultasController::class, 'ShowFakultas'])->name('admin.fakultas.Edit');
                        Route::get('/Status/{params1}/{params2}', [FakultasController::class, 'delete'])->name('admin.fakultas.delete');
                    });
                    Route::prefix('Jurusan')->group(function () {
                        Route::get('/', [JurusanController::class, 'index'])->name('admin.Jurusan.show');
                        Route::get('/TabelJurusan', [JurusanController::class, 'table_jurusan'])->name('admin.Jurusan.Tabel');
                        Route::post('/Store', [JurusanController::class, 'StoreJurusan'])->name('admin.Jurusan.Store');
                        Route::get('/EditJurusan/{params}', [JurusanController::class, 'ShowJurusan'])->name('admin.Jurusan.Edit');
                        Route::get('/Status/{params1}/{params2}', [JurusanController::class, 'delete'])->name('admin.Jurusan.delete');
                    });
                    Route::prefix('JenisBerkas')->group(function () {
                        Route::get('/', [JenisBerkasController::class, 'index'])->name('admin.JenisBerkas.show');
                        Route::get('/TabelJenisBerkas', [JenisBerkasController::class, 'TabelJenisBerkas'])->name('admin.JenisBerkas.Tabel');
                        Route::post('/Store', [JenisBerkasController::class, 'StoreJenisBerkas'])->name('admin.JenisBerkas.Store');
                        Route::get('/EditJenisBerkas/{params}', [JenisBerkasController::class, 'ShowJenisBerkas'])->name('admin.JenisBerkas.Edit');
                        Route::get('/Status/{params1}/{params2}', [JenisBerkasController::class, 'delete'])->name('admin.JenisBerkas.delete');
                    });
                    Route::prefix('Berkas')->group(function () {
                        Route::get('/', [BerkasController::class, 'index'])->name('admin.Berkas.show');
                        Route::get('/TabelBerkas', [BerkasController::class, 'TabelBerkas'])->name('admin.Berkas.Tabel');
                        Route::post('/Store', [BerkasController::class, 'StoreBerkas'])->name('admin.Berkas.Store');
                        Route::get('/EditBerkas/{params}', [BerkasController::class, 'ShowBerkas'])->name('admin.Berkas.Edit');
                        Route::get('/Status/{params1}/{params2}', [BerkasController::class, 'delete'])->name('admin.Berkas.delete');
                    });
                    Route::prefix('JenjangPendidikan')->group(function () {
                        Route::get('/', [JenjangController::class, 'index'])->name('admin.Jenjang.show');
                        Route::get('/TabelJenjang', [JenjangController::class, 'table_Pendaftaran'])->name('admin.Jenjang.Tabel');
                        Route::post('/Store', [JenjangController::class, 'StoreJurusan'])->name('admin.Jenjang.Store');
                        Route::get('/EditJenjang/{params}', [JenjangController::class, 'ShowJurusan'])->name('admin.Jenjang.Edit');
                        Route::get('/Status/{params1}/{params2}', [JenjangController::class, 'delete'])->name('admin.Jenjang.delete');
                    });
                    Route::prefix('JurusanSekolah')->group(function () {
                        Route::get('/', [JurusanSekolahController::class, 'index'])->name('admin.JurusanSekolah.show');
                        Route::get('/TabelSekolah', [JurusanSekolahController::class, 'table_Pendaftaran'])->name('admin.JurusanSekolah.Tabel');
                        Route::post('/Store', [JurusanSekolahController::class, 'StoreJurusan'])->name('admin.JurusanSekolah.Store');
                        Route::get('/EditSekolah/{params}', [JurusanSekolahController::class, 'ShowJurusan'])->name('admin.JurusanSekolah.Edit');
                        Route::get('/Status/{params1}/{params2}', [JurusanSekolahController::class, 'delete'])->name('admin.JurusanSekolah.delete');
                    });
                    Route::prefix('SoalTest')->group(function () {
                        Route::get('/', [SoalTestController::class, 'index'])->name('admin.Test.show');
                        Route::get('/TabelSekolah', [SoalTestController::class, 'table_Soal'])->name('admin.Test.Tabel');
                        Route::post('/Store', [SoalTestController::class, 'StoreTest'])->name('admin.Test.Store');
                        Route::get('/EditSoal/{params}/{detail}', [SoalTestController::class, 'ShowSoal'])->name('admin.Test.Edit');
                        Route::get('/Status/{params1}/{params2}', [SoalTestController::class, 'delete'])->name('admin.Test.delete');
                        Route::post('/UploadExcel', [SoalTestController::class, 'upload_excel'])->name('admin.Test.UploadExcel');
                    });
                    Route::prefix('Content')->group(function () {
                        Route::get('/', [ContentController::class, 'index'])->name('admin.content.show');
                    });
                });
                // Log Aktivitas Admin
                Route::prefix('LogAktivitas')->group(function () {
                    Route::get('/', [LogAktivitasController::class, 'index'])->name('admin.logaktivitas.index');
                    Route::get('/Tabel', [LogAktivitasController::class, 'tabel'])->name('admin.logaktivitas.tabel');
                    Route::get('/ModulList', [LogAktivitasController::class, 'modulList'])->name('admin.logaktivitas.modullist');
                });

                Route::prefix('Tools')->group(function () {
                    //Change Password
                    Route::get('/changepassword', [SettingController::class, 'showChangePassword'])->name('admin.show.changepassword');
                    Route::post('/changepasswordsave', [SettingController::class, 'saveChangePassword'])->name('admin.save.changepassword');

                    //Edit Profile
                    Route::get('/changeprofile', [SettingController::class, 'showEditProfile'])->name('admin.show.changeprofile');
                    Route::post('/changeprofilesave', [SettingController::class, 'saveEditProfile'])->name('admin.save.changeprofile');

                    //List Menu
                    Route::get('/ShowMenu', [SettingController::class, 'ShowMenu'])->name('admin.menu.show');
                    Route::get('/LisMenu', [SettingController::class, 'table_menu'])->name('admin.menu.TabelMenu');
                    Route::post('/SaveUpdateMenu', [SettingController::class, 'SaveUpdateMenu'])->name('admin.menu.SaveMenu');
                    Route::get('/GetMenu/{params}', [SettingController::class, 'GetMenu'])->name('admin.menu.GetMenu');
                    Route::get('/DeleteAktif/{params1}/{params2}', [SettingController::class, 'DeleteMenu'])->name('admin.menu.DeleteAktif');

                    //Group User
                    Route::get('/ShowGroupUser', [SettingController::class, 'ShowGroupUser'])->name('admin.gruopuser.show');
                    Route::get('/LisGroupUser', [SettingController::class, 'table_groupuser'])->name('admin.gruopuser.TabelGroupUser');
                    Route::post('/SaveUpdateGroupUser', [SettingController::class, 'SaveUpdateGroupUser'])->name('admin.gruopuser.Save');
                    Route::get('/GetGroupUser/{params}', [SettingController::class, 'GetGroupUser'])->name('admin.gruopuser.GetGroupUser');
                    Route::get('/ShowPrivilege/{params}', [SettingController::class, 'ShowPrivilege'])->name('admin.gruopuser.ShowPrivilege');
                    Route::post('/SavePrivilege/{params}', [SettingController::class, 'StorePrivilege'])->name('admin.gruopuser.SavePrivilege');
                    Route::get('/DeleteGroupUser/{params}', [SettingController::class, 'DeleteGroupUser'])->name('admin.gruopuser.DeleteGroupUser');

                    //User Management
                    Route::get('/usermanagement', [SettingController::class, 'userManagement'])->name('admin.show.userManagement');
                    Route::get('/tabelPegawai', [SettingController::class, 'table_pegawai'])->name('admin.show.tabelPegawai');
                    Route::get('/finduser', [SettingController::class, 'searchNama'])->name('admin.show.finduser');
                    Route::post('/StoreUser', [SettingController::class, 'StoreUser'])->name('admin.show.saveUser');
                    Route::get('/detailuser/{params}', [SettingController::class, 'DetailUser'])->name('admin.show.detailuser');
                    Route::get('/deleteuser/{params}', [SettingController::class, 'DeleteUser'])->name('admin.show.deleteuser');

                    //User Reset
                    Route::get('/userreset', [SettingController::class, 'UserReset'])->name('admin.UserReset.show');
                    Route::get('/userreset_tabelPegawai', [SettingController::class, 'UserReset_TablePegawai'])->name('admin.UserReset.tabelPegawai');
                    Route::get('/ResetPassword/{params}', [SettingController::class, 'ResetPassword'])->name('admin.UserReset.ResetPassword');
                    Route::get('/ResetQA/{params}', [SettingController::class, 'ResetQA'])->name('admin.UserReset.ResetQA');
                });
            });
        });
    });
