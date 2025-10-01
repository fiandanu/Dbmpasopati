<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\ponpes\KunjunganController;

Route::prefix('mclient-ponpes-kunjungan')->name('mckunjunganponpes.')->group(function () {

    Route::get('/', [KunjunganController::class, 'ListDataMclientPonpesKunjungan'])->name('ListDataMclientPonpesKunjungan');
    Route::post('/store', [KunjunganController::class, 'MclientPonpesKunjunganStore'])->name('MclientPonpesKunjunganStore');
    Route::put('/update/{id}', [KunjunganController::class, 'MclientPonpesKunjunganUpdate'])->name('MclientPonpesKunjunganUpdate');
    Route::delete('/destroy/{id}', [KunjunganController::class, 'MclientPonpesKunjunganDestroy'])->name('MclientPonpesKunjunganDestroy');

    Route::get('/dashboard-stats', [KunjunganController::class, 'getDashboardStats'])->name('MclientPonpesKunjungan.dashboard.stats');
    Route::get('/get-ponpes-data', [KunjunganController::class, 'getPonpesData'])->name('MclientPonpesKunjungan.getPonpesData');

    // Global export
    Route::get('/export/pdf', [KunjunganController::class, 'exportListPdf'])->name('mclientkunjunganponpes.export.list.pdf');
    Route::get('/export/csv', [KunjunganController::class, 'exportListCsv'])->name('mclientkunjunganponpes.export.list.csv');
});
