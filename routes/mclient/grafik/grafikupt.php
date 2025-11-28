<?php

use App\Http\Controllers\mclient\grafik\GrafikUptController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('monitoring/client')->group(function () {
    Route::get('/grafik', [GrafikUptController::class, 'index'])->name('GrafikClient');
    Route::get('/GrafikClient/data', [GrafikUptController::class, 'getData'])->name('GrafikClient.data');
    Route::get('/GrafikClient/vpas-data', [GrafikUptController::class, 'getVpasData'])->name('GrafikClient.vpasData');
    Route::get('/GrafikClient/reguller-data', [GrafikUptController::class, 'getRegullerData'])->name('GrafikClient.regullerData');

    // TAMBAHKAN ROUTE INI
    Route::get('/GrafikClient/kunjungan-data', [GrafikUptController::class, 'getKunjunganData'])->name('GrafikClient.kunjunganData');
    Route::get('/GrafikClient/pengiriman-data', [GrafikUptController::class, 'getPengirimanData'])->name('GrafikClient.pengirimanData');
    
    Route::post('/GrafikClient/export-pdf', [GrafikUptController::class, 'exportPdf'])->name('GrafikClient.exportPdf');
});
