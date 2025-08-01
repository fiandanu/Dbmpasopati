<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\pks\PksController;


// PKS
Route::prefix('pks')->name('pks.')->group(function () {
    Route::get('/ListDataPks', [PksController::class, 'ListDataPks'])->name('ListDataPks');
    Route::delete('/DataBasePageDestroy/{id}', [PksController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
});

Route::post('/upload-pdf/{id}', [PksController::class, 'uploadFilePDFPks'])->name('uploadFilePDFPks');
Route::get('/view-pdf/{id}', [PksController::class, 'viewUploadedPDF'])->name('viewpdf');
Route::delete('/delete-pdf/{id}', [PksController::class, 'deleteFilePDF'])->name('deleteFilePDF');
