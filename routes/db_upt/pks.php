<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\pks\PksController;
// PKS
Route::prefix('dbpks')
    ->name('dbpks.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
        Route::get('/ListDataPks', [PksController::class, 'ListDataPks'])->name('ListDataPks');

        // CRUD Operations
        Route::post('/store', [PksController::class, 'store'])->name('store');
        Route::put('/update/{id}', [PksController::class, 'update'])->name('update');
        Route::delete('/DataBasePageDestroy/{id}', [PksController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');

        // PDF routes - Parameter harus {id}/{folder}
        Route::post('/upload-pdf/{id}/{folder}', [PksController::class, 'uploadFilePDFPks'])->name('uploadFilePDFPks');
        Route::get('/view-pdf/{id}/{folder}', [PksController::class, 'viewUploadedPDF'])->name('viewpdf');
        Route::delete('/delete-pdf/{id}/{folder}', [PksController::class, 'deleteFilePDF'])->name('deleteFilePDF');


        // Export Global Data List
        Route::get('/export-pks-list-csv', [PksController::class, 'exportListCsv'])->name('export.pks.list.csv');
        Route::get('/export-pks-list-pdf', [PksController::class, 'exportListPdf'])->name('export.pks.list.pdf');
    });
