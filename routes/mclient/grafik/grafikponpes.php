<?php

use App\Http\Controllers\mclient\grafik\GrafikPonpesController;
use Illuminate\Support\Facades\Route;

// Monitoring Client - Grafik Ponpes
Route::get('/GrafikPonpes', [GrafikPonpesController::class, 'index'])->name('GrafikPonpes');
Route::get('/GrafikPonpes/data', [GrafikPonpesController::class, 'getData'])->name('GrafikPonpes.data');

// Routes untuk Vtren dan Reguler Ponpes
Route::get('/GrafikPonpes/vtren-data', [GrafikPonpesController::class, 'getVtrenData'])->name('GrafikPonpes.vtrenData');
Route::get('/GrafikPonpes/reguller-data', [GrafikPonpesController::class, 'getRegullerData'])->name('GrafikPonpes.regullerData');


Route::get('/GrafikPonpes/kunjungan-data', [GrafikPonpesController::class, 'getKunjunganData'])->name('GrafikPonpes.kunjunganData');
Route::get('/GrafikPonpes/pengiriman-data', [GrafikPonpesController::class, 'getPengirimanData'])->name('GrafikPonpes.pengirimanData');

Route::post('/GrafikPonpes/export-pdf', [GrafikPonpesController::class, 'exportPdf'])->name('GrafikPonpes.exportPdf');
