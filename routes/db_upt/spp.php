<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\spp\SppUptController;

Route::prefix('spp')->name('spp.')->group(function () {
    Route::get('/ListDataSpp', [SppUptController::class, 'ListDataSpp'])->name('ListDataSpp');
    Route::delete('/DataBasePageDestroy/{id}', [SppUptController::class, 'DatabasePageDestroy'])->name('DataBasePageDestroy');

    // Move PDF routes inside the group for consistency
    Route::post('/upload-pdf/{id}/{folder}', [SppUptController::class, 'uploadFilePDF'])->name('uploadFilePDF');
    Route::get('/view-pdf/{id}/{folder}', [SppUptController::class, 'viewUploadedPDF'])->name('viewpdf');
    Route::delete('/delete-pdf/{id}/{folder}', [SppUptController::class, 'deleteFilePDF'])->name('deleteFilePDF');
});
