<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\spp\SppUptController;

Route::prefix('spp')
    ->name('spp.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
        Route::get('/ListDataSpp', [SppUptController::class, 'ListDataSpp'])->name('ListDataSpp');
        Route::post('/store', [SppUptController::class, 'store'])->name('store');
        Route::delete('/DataBasePageDestroy/{id}', [SppUptController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');

        // PDF routes
        Route::post('/upload-pdf/{id}/{folder}', [SppUptController::class, 'uploadFilePDF'])->name('uploadFilePDF');
        Route::get('/view-pdf/{id}/{folder}', [SppUptController::class, 'viewUploadedPDF'])->name('viewpdf');
        Route::delete('/delete-pdf/{id}/{folder}', [SppUptController::class, 'deleteFilePDF'])->name('deleteFilePDF');

        // Export Global Data List
        Route::get('/export-spp-list-csv', [SppUptController::class, 'exportListCsv'])->name('export.spp.list.csv');
        Route::get('/export-spp-list-pdf', [SppUptController::class, 'exportListPdf'])->name('export.spp.list.pdf');
    });
