<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\DashboardUptController;

Route::prefix('database')->name('database.')->group(function () {
    Route::get('/DbUpt', [DashboardUptController::class, 'index'])->name('DbUpt');

    // Export routes
    Route::get('/DbUpt/export/csv', [DashboardUptController::class, 'exportCsv'])->name('DbUpt.export.csv');
    Route::get('/DbUpt/export/pdf', [DashboardUptController::class, 'exportPdf'])->name('DbUpt.export.pdf');
});
