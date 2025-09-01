<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\VpasController;

Route::prefix('mclient-vpas')->group(function () {
    
    Route::get('/', [VpasController::class, 'ListDataMclientVpas'])->name('ListDataMclientVpas');
    Route::post('/store', [VpasController::class, 'MclientVpasStore'])->name('MclientVpasStore');
    Route::put('/update/{id}', [VpasController::class, 'MclientVpasUpdate'])->name('MclientVpasUpdate');
    Route::delete('/destroy/{id}', [VpasController::class, 'MclientVpasDestroy'])->name('MclientVpasDestroy');
    Route::get('/export/csv', [VpasController::class, 'exportCsv'])->name('MclientVpas.export.csv');
    Route::get('/dashboard-stats', [VpasController::class, 'getDashboardStats'])->name('MclientVpas.dashboard.stats');
    Route::get('/get-upt-data', [VpasController::class, 'getUptData'])->name('MclientVpas.getUptData');
});