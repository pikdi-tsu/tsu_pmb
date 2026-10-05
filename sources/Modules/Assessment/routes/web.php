<?php

use Illuminate\Support\Facades\Route;
use Modules\Assessment\Http\Controllers\AssessmentController;
use Modules\Assessment\Http\Controllers\MasterSoalController;
use Modules\Assessment\Http\Controllers\MasterTestController;


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
    Route::middleware(['checkadmin'])->group(function () {

        Route::prefix('admin')->group(function() {

            Route::prefix('Assessment')->group(function() {

                Route::prefix('MasterAssessment')->group(function() {

                    Route::prefix('Test')->group(function() {
                        Route::get('/', [MasterTestController::class, 'index'])->name('admin.mastertest.show');
                        Route::get('/tabel-test', [MasterTestController::class, 'tabel_test'])->name('admin.mastertest.tabel');
                        Route::post('/save-test', [MasterTestController::class, 'store'])->name('admin.mastertest.save');
                        Route::get('/edit-test/{params}', [MasterTestController::class, 'show'])->name('admin.mastertest.edit');
                        Route::get('/status-test/{params}/{status}', [MasterTestController::class, 'destroy'])->name('admin.mastertest.status');
                    });

                    Route::prefix('Soal')->group(function() {
                        Route::get('/', [MasterSoalController::class, 'index'])->name('admin.mastersoal.show');
                        Route::get('/tabel-soal', [MasterSoalController::class, 'tabel_soal'])->name('admin.mastersoal.tabel');
                        Route::post('/save-soal', [MasterSoalController::class, 'store'])->name('admin.mastersoal.save');
                        Route::get('/edit-soal/{params}', [MasterSoalController::class, 'show'])->name('admin.mastersoal.edit');
                        Route::get('/status-soal/{params}/{status}', [MasterSoalController::class, 'destroy'])->name('admin.mastersoal.status');
                    });

                });

            });

        });

    });
});
