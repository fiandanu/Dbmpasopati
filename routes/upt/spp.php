<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\spp\SppController;

Route::prefix('spp')->name('spp.')->group(function () {
    Route::get('/ListDataSpp', [SppController::class, 'ListDataSpp'])->name('ListDataSpp');
    Route::delete('/DataBasePageDestroy/{id}', [SppController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
});

Route::post('/upload-pdf/{id}/{folder}', [SppController::class, 'uploadFilePDF'])->name('uploadFilePDF');
Route::get('/view-pdf/{id}/{folder}', [SppController::class, 'viewUploadedPDF'])->name('viewpdf');
Route::delete('/delete-pdf/{id}/{folder}', [SppController::class, 'deleteFilePDF'])->name('deleteFilePDF');
