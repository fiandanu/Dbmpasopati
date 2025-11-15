<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\DashboardUptController;

Route::prefix('database')
    ->name('database.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
        Route::get('/DbUpt', [DashboardUptController::class, 'index'])->name('DbUpt');

        // Export routes
        Route::get('/DbUpt/export/csv', [DashboardUptController::class, 'exportCsv'])->name('DbUpt.export.csv');
        Route::get('/DbUpt/export/pdf', [DashboardUptController::class, 'exportPdf'])->name('DbUpt.export.pdf');

        // EXPORT CARD KATEGORI PDF
        Route::get('/DbUpt/export/cards-pdf', [DashboardUptController::class, 'exportCardsPdf'])->name('DbUpt.export.cards.pdf');
    });
