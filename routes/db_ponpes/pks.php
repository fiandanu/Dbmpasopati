<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\ponpes\pks\PksController;

// PKS Ponpes
Route::prefix('DbPonpes.pks.')->name('DbPonpes.pks.')->group(function () {
    Route::get('/ListDataPks', [PksController::class, 'ListDataPks'])->name('ListDataPks');
    Route::delete('/DataBasePageDestroy/{id}', [PksController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');

    // Upload PDF
    Route::post('/upload-pdf-ponpes/{id}', [PksController::class, 'uploadFilePDFPonpesPks'])->name('uploadFilePDFPonpesPks');
    Route::get('/view-pdf-ponpes/{id}', [PksController::class, 'viewUploadedPDF'])->name('viewpdf.ponpes');
    Route::delete('/delete-pdf-ponpes/{id}', [PksController::class, 'deleteFilePDF'])->name('deleteFilePDF.ponpes');

    // Export Data Global
    Route::get('/ponpes/pks/export-csv', [PksController::class, 'exportListCsv'])->name('export.list.csv');
    Route::get('/ponpes/pks/export-pdf', [PksController::class, 'exportListPdf'])->name('export.list.pdf');
});
