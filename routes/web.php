<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DataBaseController;
use App\Http\Controllers\MonitoringServerController;
use App\Http\Controllers\user\provider\ProviderController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\user\PageUser;


Route::get('/', [LoginController::class, 'login'])->name('login');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

// Database Umum
Route::get('/DbPks', [DataBaseController::class, 'DbPks'])->name('DbPks');
Route::get('/DbCreatePks', [DataBaseController::class, 'DbCreatePks'])->name('DbCreatePks');
Route::post('/PksStore', [DataBaseController::class, 'PksStore'])->name('PksStore');
Route::get('/DbVpas', [DataBaseController::class, 'DbVpas'])->name('DbVpas');

Route::prefix('database')->name('database.')->group(function () {
    Route::get('/DbUpt', [PageUser::class, 'DbUpt'])->name('DbUpt');
    Route::get('/DataBasePonpes', [PageUser::class, 'DataBasePonpes'])->name('DataBasePonpes');
});

// Provider Routes
Route::prefix('provider')->name('provider.')->group(function () {
    Route::get('/DataProvider', [PageUser::class, 'DataProvider'])->name('DataProvider');
    Route::post('/ProviderPageStore', [ProviderController::class, 'ProviderPageStore'])->name('ProviderPageStore');
    Route::delete('/ProviderPageDestroy/{id}', [ProviderController::class, 'ProviderPageDestroy'])->name('ProviderPageDestroy');
    Route::put('/ProviderPageUpdate/{id}', [ProviderController::class, 'ProviderPageUpdate'])->name('ProviderPageUpdate');
});

// Monitoring Server
Route::get('/GrafikServer', [MonitoringServerController::class, 'GrafikServer'])->name('GrafikServer');
Route::get('/MonitoringUpt', [MonitoringServerController::class, 'MonitoringUpt'])->name('MonitoringUpt');
Route::get('/MonitoringPonpes', [MonitoringServerController::class, 'MonitoringPonpes'])->name('MonitoringPonpes');

// Monitoring Client
Route::get('/GrafikClient', [HomeController::class, 'GrafikClient'])->name('GrafikClient');
Route::get('/KomplainUpt', [HomeController::class, 'KomplainUpt'])->name('KomplainUpt');
Route::get('/KomplainPonpes', [HomeController::class, 'KomplainPonpes'])->name('KomplainPonpes');
Route::get('/PencatatanKartu', [HomeController::class, 'PencatatanKartu'])->name('PencatatanKartu');

// Monitoring Tutorial
Route::get('/TutorialUpt', [TutorialController::class, 'TutorialUpt'])->name('TutorialUpt');
Route::get('/TutorialPonpes', [TutorialController::class, 'TutorialPonpes'])->name('TutorialPonpes');
Route::get('/TutorialServer', [TutorialController::class, 'TutorialServer'])->name('TutorialServer');
Route::get('/TutorialMicrotik', [TutorialController::class, 'TutorialMicrotik'])->name('TutorialMicrotik');



