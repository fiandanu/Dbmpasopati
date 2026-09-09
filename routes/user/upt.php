<?php

use App\Http\Controllers\user\user\ListDataUptController;
use Illuminate\Support\Facades\Route;

Route::prefix('management')
    ->name('management.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {

        Route::resource('upt', ListDataUptController::class)->only([
            'index',
            'store',
            'update',
            'destroy'
        ]);

        // New list export Global Data
        Route::get('/export-list-csv', [ListDataUptController::class, 'exportListCsv'])->name('export.list.csv');
        Route::get('/export-list-pdf', [ListDataUptController::class, 'exportListPdf'])->name('export.list.pdf');
    });
