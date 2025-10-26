<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\ponpes\DashboardPonpesController;

Route::prefix('database')->name('database.')->group(function () {
    Route::get('/DataBasePonpes', [DashboardPonpesController::class, 'index'])->name('DataBasePonpes');

    // Export routes
    Route::get('/DbPonpes/export/csv', [DashboardPonpesController::class, 'exportCsv'])->name('DbPonpes.export.csv');
    Route::get('/DbPonpes/export/pdf', [DashboardPonpesController::class, 'exportPdf'])->name('DbPonpes.export.pdf');
});
