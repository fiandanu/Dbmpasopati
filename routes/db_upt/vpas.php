<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\vpas\VpasController;

// VPAS Routes with proper prefix and naming
Route::prefix('vpas')
    ->name('vpas.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
        Route::put('/ListUpdateVpas/{id}', [VpasController::class, 'ListUpdateVpas'])->name('ListUpdateVpas');
        Route::get('/ListDataVpas', [VpasController::class, 'ListDataVpas'])->name('ListDataVpas');
        Route::delete('/DataBasePageDestroy/{id}', [VpasController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');

        // Export Data Personal
        Route::get('/export-vpas-csv/{id}', [VpasController::class, 'exportVerticalCsv'])->name('export.vpas.csv');
        Route::get('/export-vpas-pdf/{id}', [VpasController::class, 'exportUptPdf'])->name('export.vpas.pdf');

        // New list export Global Data (like Reguler)
        Route::get('/export-vpas-list-csv', [VpasController::class, 'exportListCsv'])->name('export.vpas.list.csv');
        Route::get('/export-vpas-list-pdf', [VpasController::class, 'exportListPdf'])->name('export.vpas.list.pdf');
    });
