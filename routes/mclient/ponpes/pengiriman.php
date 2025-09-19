<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\ponpes\PengirimanController;

Route::prefix('mclient-ponpes-pengiriman')->group(function () {
    Route::get('/', [PengirimanController::class, 'ListDataMclientPonpesPengiriman'])->name('ListDataMclientPonpesPengiriman');
    Route::post('/store', [PengirimanController::class, 'MclientPonpesPengirimanStore'])->name('MclientPonpesPengirimanStore');
    Route::put('/update/{id}', [PengirimanController::class, 'MclientPonpesPengirimanUpdatePonpes'])->name('MclientPonpesPengirimanUpdatePonpes');
    Route::delete('/destroy/{id}', [PengirimanController::class, 'MclientPonpesPengirimanDestroyPonpes'])->name('MclientPonpesPengirimanDestroyPonpes');
    Route::get('/export/csv', [PengirimanController::class, 'exportCsv'])->name('MclientPonpesPengiriman.export.csv');
    Route::get('/dashboard-stats', [PengirimanController::class, 'getDashboardStats'])->name('MclientPonpesPengiriman.dashboard.stats');
    Route::get('/get-ponpes-data', [PengirimanController::class, 'getPonpesData'])->name('MclientPonpesPengiriman.getPonpesData');
});