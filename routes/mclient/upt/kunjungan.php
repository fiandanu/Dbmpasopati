<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\reguler\KunjunganController;

Route::prefix('mclient-kunjungan')->group(function () {
    
    Route::get('/', [KunjunganController::class, 'ListDataMclientKunjungan'])->name('ListDataMclientKunjungan');
    Route::post('/store', [KunjunganController::class, 'MclientKunjunganStore'])->name('MclientKunjunganStore');
    Route::put('/update/{id}', [KunjunganController::class, 'MclientKunjunganUpdate'])->name('MclientKunjunganUpdate');
    Route::delete('/destroy/{id}', [KunjunganController::class, 'MclientKunjunganDestroy'])->name('MclientKunjunganDestroy');
    Route::get('/export/csv', [KunjunganController::class, 'exportCsv'])->name('MclientKunjungan.export.csv');
    Route::get('/dashboard-stats', [KunjunganController::class, 'getDashboardStats'])->name('MclientKunjungan.dashboard.stats');
    Route::get('/get-upt-data', [KunjunganController::class, 'getUptData'])->name('MclientKunjungan.getUptData');
});