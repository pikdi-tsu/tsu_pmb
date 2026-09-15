<?php

use App\Http\Controllers\EmergencyLoginController;
use App\Http\Controllers\SsoController;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\LoginController;

Route::middleware(['web'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::get('/login/sso', [SsoController::class, 'redirect'])->name('sso.login');
    Route::get('/login/sso/callback', [SsoController::class, 'callback'])->name('sso.callback');
    Route::get('/emergency-login', [EmergencyLoginController::class, 'login'])->name('emergency-login');
    Route::get('/rescue-login', [EmergencyLoginController::class, 'showRescueForm'])->name('rescue');
    Route::post('/rescue-login', [EmergencyLoginController::class, 'processRescueLogin'])->name('rescue.post');
});
