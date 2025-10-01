<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\catatankartu\CatatanController;

Route::prefix('mclient-catatan-vpas')->name('mccatatanvpas.')->group(function () {
    Route::get('/', [CatatanController::class, 'ListDataMclientCatatanVpas'])->name('ListDataMclientCatatanVpas');
    Route::post('/store', [CatatanController::class, 'MclientCatatanStoreVpas'])->name('MclientCatatanStoreVpas');
    Route::put('/update/{id}', [CatatanController::class, 'MclientCatatanUpdateVpas'])->name('MclientCatatanUpdateVpas');
    Route::delete('/destroy/{id}', [CatatanController::class, 'MclientCatatanDestroyVpas'])->name('MclientCatatanDestroyVpas');
    Route::get('/export/csv', [CatatanController::class, 'exportCsv'])->name('MclientCatatan.export.csv');
    Route::get('/dashboard-stats', [CatatanController::class, 'getDashboardStats'])->name('MclientCatatan.dashboard.stats');
    Route::get('/get-upt-data', [CatatanController::class, 'getUptData'])->name('MclientCatatan.getUptData');
});
