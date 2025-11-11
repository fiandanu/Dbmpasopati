<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\reguler\RegullerController;

Route::prefix('mclient-reguler')
    ->name('mcreguler.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {

        Route::get('/', [RegullerController::class, 'ListDataMclientReguller'])->name('ListDataMclientReguller');
        Route::post('/store', [RegullerController::class, 'MclientRegullerStore'])->name('MclientRegullerStore');
        Route::put('/update/{id}', [RegullerController::class, 'MclientRegullerUpdate'])->name('MclientRegullerUpdate');
        Route::delete('/destroy/{id}', [RegullerController::class, 'MclientRegullerDestroy'])->name('MclientRegullerDestroy');

        // New global export routes
        Route::get('/export-list-csv', [RegullerController::class, 'exportListCsv'])->name('export.list.csv');
        Route::get('/export-list-pdf', [RegullerController::class, 'exportListPdf'])->name('export.list.pdf');
    });
