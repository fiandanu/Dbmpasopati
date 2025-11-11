<?php

use App\Http\Controllers\user\user\ListDataPonpesController;
use Illuminate\Support\Facades\Route;

Route::prefix('UserPonpes')
    ->name('UserPonpes.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
    Route::resource('ponpes', ListDataPonpesController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('/ponpes/export/list/csv', [ListDataPonpesController::class, 'exportListCsv'])->name('export.list.csv');
    Route::get('/ponpes/export/list/pdf', [ListDataPonpesController::class, 'exportListPdf'])->name('export.list.pdf');
});
