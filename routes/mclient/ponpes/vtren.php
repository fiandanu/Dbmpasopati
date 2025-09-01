<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\ponpes\VtrenController;

Route::prefix('mclient-ponpes-vtren')->group(function () {
    
    Route::get('/', [VtrenController::class, 'ListDataMclientPonpesVtren'])->name('ListDataMclientPonpesVtren');
    Route::post('/store', [VtrenController::class, 'MclientPonpesVtrenStore'])->name('MclientPonpesVtrenStore');
    Route::put('/update/{id}', [VtrenController::class, 'MclientPonpesVtrenUpdate'])->name('MclientPonpesVtrenUpdate');
    Route::delete('/destroy/{id}', [VtrenController::class, 'MclientPonpesVtrenDestroy'])->name('MclientPonpesVtrenDestroy');
    Route::get('/export/csv', [VtrenController::class, 'exportCsv'])->name('MclientPonpesVtren.export.csv');
    Route::get('/dashboard-stats', [VtrenController::class, 'getDashboardStats'])->name('MclientPonpesVtren.dashboard.stats');
    Route::get('/get-ponpes-data', [VtrenController::class, 'getPonpesData'])->name('MclientPonpesVtren.getPonpesData');
});