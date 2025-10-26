<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\login\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DataBaseController;
use App\Http\Controllers\MonitoringServerController;

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

// Database Umum
Route::get('/DbPks', [DataBaseController::class, 'DbPks'])->name('DbPks');
Route::get('/DbCreatePks', [DataBaseController::class, 'DbCreatePks'])->name('DbCreatePks');
Route::post('/PksStore', [DataBaseController::class, 'PksStore'])->name('PksStore');
Route::get('/DbVpas', [DataBaseController::class, 'DbVpas'])->name('DbVpas');

// Monitoring Server
Route::get('/GrafikServer', [MonitoringServerController::class, 'GrafikServer'])->name('GrafikServer');
Route::get('/MonitoringUpt', [MonitoringServerController::class, 'MonitoringUpt'])->name('MonitoringUpt');
Route::get('/MonitoringPonpes', [MonitoringServerController::class, 'MonitoringPonpes'])->name('MonitoringPonpes');

// Monitoring Client
Route::get('/GrafikClient', [HomeController::class, 'GrafikClient'])->name('GrafikClient');
Route::get('/KomplainUpt', [HomeController::class, 'KomplainUpt'])->name('KomplainUpt');
Route::get('/KomplainPonpes', [HomeController::class, 'KomplainPonpes'])->name('KomplainPonpes');
Route::get('/PencatatanKartu', [HomeController::class, 'PencatatanKartu'])->name('PencatatanKartu');




