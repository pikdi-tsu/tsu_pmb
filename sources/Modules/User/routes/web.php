<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\AssessmentController;
use Modules\User\Http\Controllers\BiodataController;
use Modules\User\Http\Controllers\DaftarRekomendatorController;
use Modules\User\Http\Controllers\HasilPMBController;
use Modules\User\Http\Controllers\LoginController;
use Modules\User\Http\Controllers\OnlineTestController;
use Modules\User\Http\Controllers\PembayaranController;
use Modules\User\Http\Controllers\PembayaranUKTController;
use Modules\User\Http\Controllers\PendaftaranController;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\ValidasiBerkasController;
use Modules\User\Http\Controllers\KartuPesertaController;


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

Route::prefix('')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('indexing');
    Route::middleware(['web'])->group(function () {
        Route::get('/jalur-pendaftaran', [UserController::class, 'jalurPendaftaran'])->name('jalur_pendaftaran');
        Route::get('/pengumuman', [UserController::class, 'pengumuman'])->name('pengumuman');
        Route::get('/informasi-pendaftaran', [UserController::class, 'informasiPendaftaran'])->name('informasi_pendaftaran');
        Route::get('/detail-pengumuman', [UserController::class, 'detailPengumuman'])->name('detail_pengumuman');
        Route::get('/register', [UserController::class, 'Register'])->name('register');
        Route::post('/StoreRegist', [UserController::class, 'StoreRegister'])->name('register.save');
        Route::get('/GetKabupaten/{params}', [UserController::class, 'getKabupaten']);
        Route::get('/VerifikasiAkun/{params}', [UserController::class, 'VerifikasiAkun'])->name('verifikasi_akun');
        Route::get('/showJurusan/{params}', [UserController::class, 'jurusan'])->name('jurusan.show');
        Route::get('/RegistDone', [UserController::class, 'SuksesRegist'])->name('register.sukses');
        Route::get('/LoginPMB', [UserController::class, 'LoginForm'])->name('LoginPMB');
        Route::post('/LoginAction', [UserController::class, 'LoginAction'])->name('login.action');
        Route::get('/ResetPassword', [UserController::class, 'ResetPassword'])->name('ResetPassword');
        Route::post('/ResetPasswordAction', [UserController::class, 'ResetPasswordAction'])->name('ResetPasswordAction');

        Route::get('/program-studi', [UserController::class, 'programStudi'])->name('program_studi');
        Route::get('/gelobangukt', [UserController::class, 'gelombangukt'])->name('gelombangukt');
        Route::get('/alurpendaftaranbeasiswa', [UserController::class, 'alurbeasiswa'])->name('alurpendaftaranbeasiswa');
        Route::get('/detailbeasiswa/{params}', [UserController::class, 'detailbeasiswa'])->name('detailbeasiswa');
        Route::get('/pendaftaranreguler', [UserController::class, 'pendaftaranreguler'])->name('pendaftaranreguler');
        Route::get('/kontakkami', [UserController::class, 'kontakkami'])->name('kontakkami');
        Route::get('/downloadbrowsur', [UserController::class, 'downloadbrowsur'])->name('downloadbrowsur');
        Route::get('/getbrowsur', [UserController::class, 'getbrowsur'])->name('getbrowsur');

        //Daftar Rekomendator
        Route::get('/Daftar-Rekomendator', [DaftarRekomendatorController::class, 'index'])->name('daftarrekomendator.index');
        Route::post('/Save-Rekomendator', [DaftarRekomendatorController::class, 'save'])->name('daftarrekomendator.save');

        // Route yang butuh user login
        Route::middleware(['checkuser'])->group(function () {
            Route::get('/Dashboard', [UserController::class, 'Dashboard'])->name('Dashboard');
            Route::get('/kartu-peserta', [KartuPesertaController::class, 'download'])->name('kartupeserta.download');
            Route::get('/ChangePassword', [UserController::class, 'edit'])->name('user.ChangePassword');
            Route::post('/ChangePasswordSave', [UserController::class, 'update'])->name('user.ChangePasswordSave');
            Route::post('/logout', [UserController::class, 'logout'])->name('logout');

            Route::prefix('Pendaftaran')->group(function () {
                Route::get('/', [PendaftaranController::class, 'index'])->name('pendaftaran');
                Route::get('/ShowJalur/{params}', [PendaftaranController::class, 'showJalur'])->name('Daftar.ShowJalur');
                Route::get('/ShowBeasiswa/{params}', [PendaftaranController::class, 'showBeasiswa'])->name('Daftar.ShowBeasiswa');
                Route::get('/ShowDetailBeasiswa/{params}', [PendaftaranController::class, 'detailbeasiswa'])->name('Daftar.ShowDetailBeasiswa');
                Route::get('/CariRekomendator', [PendaftaranController::class, 'cariRekomendator'])->name('Daftar.CariRekomendator');
                Route::get('/ShowProdi/{batch}/{jalur}/{jurusansekolah}', [PendaftaranController::class, 'showProdi'])->name('Daftar.ShowProdi');
                Route::post('/StoreDaftar', [PendaftaranController::class, 'StoreDaftar'])->name('Daftar.StoreDaftar');
                Route::get('/TabelDaftar', [PendaftaranController::class, 'tabelPendaftaran'])->name('Daftar.TabelDaftar');
                Route::get('/KonfirmasiDaftar/{params}', [PendaftaranController::class, 'ConfirmDaftar'])->name('Daftar.KonfirmasiDaftar');
                Route::get('/ShowDaftar/{params}', [PendaftaranController::class, 'showDaftar'])->name('Daftar.ShowDaftar');
                Route::get('/DeleteDaftar/{params}', [PendaftaranController::class, 'delete'])->name('Daftar.DeleteDaftar');
                Route::get('/GetWaktuKuliah/{params?}', [PendaftaranController::class, 'getwaktukuliah'])->name('Daftar.GetWaktuKuliah');
            });

            Route::prefix('BerkasBeasiswa')->group(function () {
                Route::get('/', [ValidasiBerkasController::class, 'index'])->name('BksBeasiswa');
                Route::get('/TabelBerkasBeasiswa', [ValidasiBerkasController::class, 'tabelBerkasBeasiswa'])->name('BksBeasiswa.Tabel');
                Route::get('/ShowBerkasBeasiswa/{params}', [ValidasiBerkasController::class, 'showBerkasBeasiswa']);
                Route::post('/SaveBerkasBeasiswa', [ValidasiBerkasController::class, 'saveBerkas'])->name('BksBeasiswa.save');
                Route::get('/ApprovalPindahJalur/{params1}/{params2}', [ValidasiBerkasController::class, 'PindahJalur']);
                Route::get('/GetBerkasUser', [ValidasiBerkasController::class, 'GetBerkasUser']);
            });

            Route::prefix('OnlineTest')->group(function () {
                Route::get('/', [OnlineTestController::class, 'index'])->name('test');
                Route::get('/cek_test/{params}', [OnlineTestController::class, 'cek_test']);
                Route::post('/SaveTest', [OnlineTestController::class, 'saveTest'])->name('test.save');
            });

            Route::prefix('Pembayaran')->group(function () {
                Route::get('/', [PembayaranController::class, 'index'])->name('pembayaran');
                Route::get('/TabelPembayaran', [PembayaranController::class, 'tabelPembayaran'])->name('Pembayaran.TabelBayar');
                Route::get('/ShowPayment/{params}', [PembayaranController::class, 'ShowPayment']);
                Route::post('/uploadbayar', [PembayaranController::class, 'upload_bayar'])->name('Pembayaran.uploadbayar');
                Route::get('/PaymentPMB/{params}', [PembayaranController::class, 'PaymentPMB']); //tdk dipake
                Route::post('/payment', [PembayaranController::class, 'test_bayar'])->name('payment.create'); //tdk dipake
            });

            Route::prefix('PembayaranUKT')->group(function () {
                Route::get('/', [PembayaranUKTController::class, 'index'])->name('pembayaranUKT');
                Route::get('/TabelPembayaranUKT', [PembayaranUKTController::class, 'tabelPembayaran'])->name('PembayaranUKT.TabelBayar');
                Route::get('/ShowPaymentUKT/{params}', [PembayaranUKTController::class, 'ShowPayment']);
                Route::post('/uploadbayar', [PembayaranUKTController::class, 'upload_bayar'])->name('PembayaranUKT.uploadbayar');
                Route::get('/PaymentUKT/{params}', [PembayaranUKTController::class, 'PaymentUKT']); //tdk dipake
                Route::post('/paymentUKT', [PembayaranUKTController::class, 'test_bayar'])->name('paymentUKT.create'); //tdk dipake
            });

            Route::prefix('Biodata')->group(function () {
                Route::get('/', [BiodataController::class, 'index'])->name('biodata');
                Route::get('/ChangeKabupaten/{prov}', [BiodataController::class, 'ChangeKabupaten']);
                Route::get('/ChangeKecamatan/{prov}/{kab}', [BiodataController::class, 'ChangeKecamatan']);
                Route::get('/ChangeKelurahan/{prov}/{kab}/{kec}', [BiodataController::class, 'ChangeKelurahan']);
                Route::post('/SaveBiodata', [BiodataController::class, 'save_biodata'])->name('biodata.Save');
            });

            Route::prefix('HasilPMB')->group(function () {
                Route::get('/', [HasilPMBController::class, 'index'])->name('HasilPMB');
            });
            Route::prefix('Assessment')->group(function () {
                Route::get('/', [AssessmentController::class, 'index'])->name('assessment.index');
                Route::get('/prepare/{id}', [AssessmentController::class, 'prepareTest'])->name('assessment.prepare');
                Route::post('/start-exam', [AssessmentController::class, 'startExam'])->name('assessment.start_exam');
                Route::post('/save-answer', [AssessmentController::class, 'saveAnswer'])->name('assessment.save_answer');
                Route::post('/finish', [AssessmentController::class, 'finishTest'])->name('assessment.finish');
            });
        });
    });
});
