<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DataBaseController;
use App\Http\Controllers\MonitoringServerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (Belum Login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Landing page - redirect ke login
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

    // Proses login
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Database Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('database')->name('database.')->group(function () {
        // PKS Routes
        Route::get('/pks', [DataBaseController::class, 'DbPks'])->name('DbPks');
        Route::get('/pks/create', [DataBaseController::class, 'DbCreatePks'])->name('DbCreatePks');
        Route::post('/pks/store', [DataBaseController::class, 'PksStore'])->name('PksStore');

        // VPAS Routes
        Route::get('/vpas', [DataBaseController::class, 'DbVpas'])->name('DbVpas');
    });

    /*
    |--------------------------------------------------------------------------
    | Monitoring Server Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('monitoring/server')->group(function () {
        Route::get('/grafik', [MonitoringServerController::class, 'GrafikServer'])->name('GrafikServer');
        Route::get('/upt', [MonitoringServerController::class, 'MonitoringUpt'])->name('MonitoringUpt');
        Route::get('/ponpes', [MonitoringServerController::class, 'MonitoringPonpes'])->name('MonitoringPonpes');
    });
});
