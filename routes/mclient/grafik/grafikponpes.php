<?php

use App\Http\Controllers\mclient\grafik\GrafikPonpesController;
use Illuminate\Support\Facades\Route;

// Monitoring Client - Grafik Ponpes
Route::get('/GrafikPonpes', [GrafikPonpesController::class, 'index'])->name('GrafikPonpes');
Route::get('/GrafikPonpes/data', [GrafikPonpesController::class, 'getData'])->name('GrafikPonpes.data');

// Routes untuk Vtren dan Reguler Ponpes
Route::get('/GrafikPonpes/vtren-data', [GrafikPonpesController::class, 'getVtrenData'])->name('GrafikPonpes.vtrenData');
Route::get('/GrafikPonpes/reguller-data', [GrafikPonpesController::class, 'getRegullerData'])->name('GrafikPonpes.regullerData');

Route::post('/GrafikPonpes/export-pdf', [GrafikPonpesController::class, 'exportPdf'])->name('GrafikPonpes.exportPdf');
