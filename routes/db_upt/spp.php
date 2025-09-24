<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\spp\SppUptController;

Route::prefix('spp')->name('spp.')->group(function () {
    Route::get('/ListDataSpp', [SppUptController::class, 'ListDataSpp'])->name('ListDataSpp');
    Route::delete('/DataBasePageDestroy/{id}', [SppUptController::class, 'DatabasePageDestroy'])->name('DataBasePageDestroy');

    // PDF routes
    Route::post('/upload-pdf/{id}/{folder}', [SppUptController::class, 'uploadFilePDF'])->name('uploadFilePDF');
    Route::get('/view-pdf/{id}/{folder}', [SppUptController::class, 'viewUploadedPDF'])->name('viewpdf');
    Route::delete('/delete-pdf/{id}/{folder}', [SppUptController::class, 'deleteFilePDF'])->name('deleteFilePDF');

    // Export Data Personal
    Route::get('/export-spp-csv/{id}', [SppUptController::class, 'exportVerticalCsv'])->name('export.spp.csv');
    Route::get('/export-spp-pdf/{id}', [SppUptController::class, 'exportUptPdf'])->name('export.spp.pdf');

    // Export Global Data List
    Route::get('/export-spp-list-csv', [SppUptController::class, 'exportListCsv'])->name('export.spp.list.csv');
    Route::get('/export-spp-list-pdf', [SppUptController::class, 'exportListPdf'])->name('export.spp.list.pdf');
});