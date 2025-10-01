<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\catatankartu\VtrenController;

Route::prefix('mclient-catatan-vtren')->name('mccatatanvtren.')->group(function () {
    Route::get('/', [VtrenController::class, 'ListDataMclientCatatanVtren'])->name('ListDataMclientCatatanVtren');
    Route::post('/store', [VtrenController::class, 'MclientCatatanStoreVtren'])->name('MclientCatatanStoreVtren');
    Route::put('/update/{id}', [VtrenController::class, 'MclientCatatanUpdateVtren'])->name('MclientCatatanUpdateVtren');
    Route::delete('/destroy/{id}', [VtrenController::class, 'MclientCatatanDestroyVtren'])->name('MclientCatatanDestroyVtren');
    Route::get('/export/csv', [VtrenController::class, 'exportCsv'])->name('MclientCatatanVtren.export.csv');
    Route::get('/dashboard-stats', [VtrenController::class, 'getDashboardStats'])->name('MclientCatatanVtren.dashboard.stats');
    Route::get('/get-upt-data', [VtrenController::class, 'getUptData'])->name('MclientCatatanVtren.getUptData');
});
