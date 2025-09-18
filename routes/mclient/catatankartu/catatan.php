<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\catatankartu\CatatanController;

Route::prefix('mclient-catatan')->group(function () {
    Route::get('/', [CatatanController::class, 'ListDataMclientCatatan'])->name('ListDataMclientCatatan');
    Route::post('/store', [CatatanController::class, 'MclientCatatanStore'])->name('MclientCatatanStore');
    Route::put('/update/{id}', [CatatanController::class, 'MclientCatatanUpdate'])->name('MclientCatatanUpdate');
    Route::delete('/destroy/{id}', [CatatanController::class, 'MclientCatatanDestroy'])->name('MclientCatatanDestroy');
    Route::get('/export/csv', [CatatanController::class, 'exportCsv'])->name('MclientCatatan.export.csv');
    Route::get('/dashboard-stats', [CatatanController::class, 'getDashboardStats'])->name('MclientCatatan.dashboard.stats');
    Route::get('/get-upt-data', [CatatanController::class, 'getUptData'])->name('MclientCatatan.getUptData');
});