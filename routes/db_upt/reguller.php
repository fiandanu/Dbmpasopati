<?php

use App\Http\Controllers\user\upt\reguler\RegullerController;
use Illuminate\Support\Facades\Route;

// REGULLER

// User Page BAGIAN UPT
// AWAL DATA WAJIB NGISI NAMA UPT DAN KANWIL

Route::prefix('upt')
    ->name('upt.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {

        Route::put('/ListUpdateReguller/{id}', [RegullerController::class, 'ListUpdateReguller'])->name('ListUpdateReguller');
        Route::get('/ListDataReguller', action: [RegullerController::class, 'ListDataReguller'])->name('ListDataReguller');

        // Export Data Personal
        Route::get('/export-upt-csv/{id}', [RegullerController::class, 'exportVerticalCsv'])->name('export.upt.csv');
        Route::get('/export-upt-pdf/{id}', [RegullerController::class, 'exportUptPdf'])->name('export.upt.pdf');

        // New list export Global Data
        Route::get('/export-list-csv', [RegullerController::class, 'exportListCsv'])->name('export.list.csv');
        Route::get('/export-list-pdf', [RegullerController::class, 'exportListPdf'])->name('export.list.pdf');
    });
