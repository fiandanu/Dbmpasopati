<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\VpasController;

Route::prefix('mclient-vpas')->group(function () {
    // List data monitoring client VPAS
    Route::get('/', [VpasController::class, 'ListDataMclientVpas'])->name('ListDataMclientVpas');
    // Store data baru
    Route::post('/store', [VpasController::class, 'MclientVpasStore'])->name('MclientVpasStore');
    // Update data
    Route::put('/update/{id}', [VpasController::class, 'MclientVpasUpdate'])->name('MclientVpasUpdate');
    // Delete data
    Route::delete('/destroy/{id}', [VpasController::class, 'MclientVpasDestroy'])->name('MclientVpasDestroy');
    // Export CSV
    Route::get('/export/csv', [VpasController::class, 'exportCsv'])->name('MclientVpas.export.csv');
    // Get dashboard statistics (untuk API/AJAX)
    Route::get('/dashboard-stats', [VpasController::class, 'getDashboardStats'])->name('MclientVpas.dashboard.stats');
});
