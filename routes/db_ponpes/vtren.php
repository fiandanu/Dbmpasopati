<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\ponpes\vtren\VtrenController;

Route::prefix('DbPonpes')->name('DbPonpes.')->group(function () {
    
    Route::get('/ListDataVtrend', [VtrenController::class, 'ListDataVtrend'])->name('ListDataVtrend');
    Route::put('/ListDataPonpesUpdate/{id}', [VtrenController::class, 'ListDataPonpesUpdate'])->name('ListDataPonpesUpdate');
    Route::delete('/PonpesPageDestroy/{id}', [VtrenController::class, 'PonpesPageDestroy'])->name('PonpesPageDestroy');

    // Untuk Export Data Personal
    Route::get('/exportPonpesPdf/{id}', [VtrenController::class, 'exportPonpesPdf'])->name('exportPonpesPdf');
    Route::get('/exportPonpesCsv/{id}', [VtrenController::class, 'exportPonpesCsv'])->name('exportPonpesCsv');

    // Untuk Export Data All
    Route::get('/vtren/export/list/csv', [VtrenController::class, 'exportListCsv'])->name('vtren.export.list.csv');
    Route::get('/vtren/export/list/pdf', [VtrenController::class, 'exportListPdf'])->name('vtren.export.list.pdf');
});
