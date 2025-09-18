<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\catatankartu\VtrenController;

Route::prefix('mclient-catatan-vtren')->group(function () {
    Route::get('/', [VtrenController::class, 'ListDataMclientCatatanVtren'])->name('ListDataMclientCatatanVtren');
    Route::post('/store', [VtrenController::class, 'MclientCatatanStore'])->name('MclientCatatanStore');
    Route::put('/update/{id}', [VtrenController::class, 'MclientCatatanUpdate'])->name('MclientCatatanUpdate');
    Route::delete('/destroy/{id}', [VtrenController::class, 'MclientCatatanDestroy'])->name('MclientCatatanDestroy');
    Route::get('/export/csv', [VtrenController::class, 'exportCsv'])->name('MclientCatatan.export.csv');
    Route::get('/dashboard-stats', [VtrenController::class, 'getDashboardStats'])->name('MclientCatatan.dashboard.stats');
    Route::get('/get-upt-data', [VtrenController::class, 'getUptData'])->name('MclientCatatan.getUptData');
});