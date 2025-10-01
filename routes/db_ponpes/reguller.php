<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\ponpes\reguller\RegullerController;

// ini di file ponpes.php

Route::prefix('ponpes')->name('ponpes.')->group(function () {
    
    // Ponpes Export Data All
    Route::get('/ponpes/export/list/csv', [RegullerController::class, 'exportListCsv'])->name('export.list.csv');
    Route::get('/ponpes/export/list/pdf', [RegullerController::class, 'exportListPdf'])->name('export.list.pdf');

    // Db Ponpes
    Route::get('/ListDataPonpes', [RegullerController::class, 'ListDataPonpes'])->name('ListDataPonpes');
    Route::put('/ListDataPonpesUpdate/{id}', [RegullerController::class, 'ListDataPonpesUpdate'])->name('ListDataPonpesUpdate');
    Route::get('/exportPonpesPdf/{id}', [RegullerController::class, 'exportPonpesPdf'])->name('exportPonpesPdf');
    Route::get('/exportPonpesCsv/{id}', [RegullerController::class, 'exportPonpesCsv'])->name('exportPonpesCsv');

});
