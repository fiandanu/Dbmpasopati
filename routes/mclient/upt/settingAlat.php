<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\reguler\SettingAlatController;

Route::prefix('mclient-setting-alat-upt')->group(function () {
    Route::get('/', [SettingAlatController::class, 'ListDataMclientSettingAlat'])->name('ListDataMclientSettingAlat');
    Route::post('/store', [SettingAlatController::class, 'MclientSettingAlatStore'])->name('MclientSettingAlatStore');
    Route::put('/update/{id}', [SettingAlatController::class, 'MclientSettingAlatUpdate'])->name('MclientSettingAlatUpdate');
    Route::delete('/destroy/{id}', [SettingAlatController::class, 'MclientSettingAlatDestroy'])->name('MclientSettingAlatDestroy');
    Route::get('/export/csv', [SettingAlatController::class, 'exportCsv'])->name('MclientKunjungan.export.csv');
    Route::get('/dashboard-stats', [SettingAlatController::class, 'getDashboardStats'])->name('MclientKunjungan.dashboard.stats');
    Route::get('/get-upt-data', [SettingAlatController::class, 'getUptData'])->name('MclientKunjungan.getUptData');
});
