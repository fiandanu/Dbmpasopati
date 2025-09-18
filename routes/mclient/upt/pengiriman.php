<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\reguler\PengirimanController;

Route::prefix('mclient-pengiriman')->group(function () {
    Route::get('/', [PengirimanController::class, 'ListDataMclientPengiriman'])->name('ListDataMclientPengiriman');
    Route::post('/store', [PengirimanController::class, 'MclientKunjunganStore'])->name('MclientKunjunganStore');
    Route::put('/update/{id}', [PengirimanController::class, 'MclientKunjunganUpdate'])->name('MclientKunjunganUpdate');
    Route::delete('/destroy/{id}', [PengirimanController::class, 'MclientKunjunganDestroy'])->name('MclientKunjunganDestroy');
    Route::get('/export/csv', [PengirimanController::class, 'exportCsv'])->name('MclientKunjungan.export.csv');
    Route::get('/dashboard-stats', [PengirimanController::class, 'getDashboardStats'])->name('MclientKunjungan.dashboard.stats');
    Route::get('/get-upt-data', [PengirimanController::class, 'getUptData'])->name('MclientKunjungan.getUptData');
});