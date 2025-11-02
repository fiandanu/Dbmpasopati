<?php

use App\Http\Controllers\DataBaseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\login\LoginController;
use App\Http\Controllers\mclient\reguler\RegullerController;
use App\Http\Controllers\MonitoringServerController;
use Illuminate\Support\Facades\Route;

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
// Route::get('/KomplainUpt', [RegullerController::class, 'monitoringClientUptOverview'])->name('KomplainUpt');
Route::get('/KomplainPonpes', [HomeController::class, 'KomplainPonpes'])->name('KomplainPonpes');
Route::get('/PencatatanKartu', [HomeController::class, 'PencatatanKartu'])->name('PencatatanKartu');

// Monitoring Client UPT - Overview dan Export
Route::get('/monitoring-client-upt', [RegullerController::class, 'monitoringClientUptOverview'])->name('mclient.upt.overview');
// Route::get('/monitoring-client-upt/export-csv', [RegullerController::class, 'exportMonitoringClientCsv'])->name('mclient.upt.export.csv');
// Route::get('/monitoring-client-upt/export-pdf', [RegullerController::class, 'exportMonitoringClientPdf'])->name('mclient.upt.export.pdf');
