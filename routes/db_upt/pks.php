<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\pks\PksController;


// PKS
Route::prefix('dbpks')->name('dbpks.')->group(function () {
    Route::get('/ListDataPks', [PksController::class, 'ListDataPks'])->name('ListDataPks');
    Route::delete('/DataBasePageDestroy/{id}', [PksController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');

    // PDF routes
    Route::post('/upload-pdf/{id}', [PksController::class, 'uploadFilePDFPks'])->name('uploadFilePDFPks');
    Route::get('/view-pdf/{id}', [PksController::class, 'viewUploadedPDF'])->name('viewpdf');
    Route::delete('/delete-pdf/{id}', [PksController::class, 'deleteFilePDF'])->name('deleteFilePDF');

    // Export Global Data List
    Route::get('/export-pks-list-csv', [PksController::class, 'exportListCsv'])->name('export.pks.list.csv');
    Route::get('/export-pks-list-pdf', [PksController::class, 'exportListPdf'])->name('export.pks.list.pdf');
});