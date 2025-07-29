<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\pks\PksController;


Route::prefix('pks')->name('pks.')->group(function () {
    Route::get('/ListDataPks', [PksController::class, 'ListDataPks'])->name('ListDataPks');
    Route::delete('/DataBasePageDestroy/{id}', [PksController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
});
Route::post('/upload-pdf/{id}', [PksController::class, 'uploadFilePDF'])->name('uploadFilePDF');
Route::get('/view-pdf/{id}', [PksController::class, 'viewUploadedPDF'])->name('viewpdf');
