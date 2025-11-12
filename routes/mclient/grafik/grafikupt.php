<?php

use App\Http\Controllers\mclient\grafik\GrafikUptController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('monitoring/client')->group(function () {
    Route::get('/grafik', [GrafikUptController::class, 'index'])->name('GrafikClient');
    Route::get('/GrafikClient/data', [GrafikUptController::class, 'getData'])->name('GrafikClient.data');

    // TAMBAHAN: Routes untuk VPAS dan Reguler
    Route::get('/GrafikClient/vpas-data', [GrafikUptController::class, 'getVpasData'])->name('GrafikClient.vpasData');
    Route::get('/GrafikClient/reguller-data', [GrafikUptController::class, 'getRegullerData'])->name('GrafikClient.regullerData');

    Route::post('/GrafikClient/export-pdf', [GrafikUptController::class, 'exportPdf'])->name('GrafikClient.exportPdf');
});
