<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\ponpes\pks\PksController;


// PKS Ponpes
Route::prefix('ponpes/pks')->name('ponpes.pks.')->group(function () {
    Route::get('/ListDataPks', [PksController::class, 'ListDataPks'])->name('ListDataPks');
    Route::delete('/DataBasePageDestroy/{id}', [PksController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
});


Route::post('/upload-pdf-ponpes/{id}', [PksController::class, 'uploadFilePDFPonpesPks'])->name('uploadFilePDFPonpesPks');
Route::get('/view-pdf-ponpes/{id}', [PksController::class, 'viewUploadedPDF'])->name('viewpdf.ponpes');
Route::delete('/delete-pdf-ponpes/{id}', [PksController::class, 'deleteFilePDF'])->name('deleteFilePDF.ponpes');
