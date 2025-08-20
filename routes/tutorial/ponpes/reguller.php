<?php

use App\Http\Controllers\tutorial\ponpes\RegullerPonpesController;
use Illuminate\Support\Facades\Route;

Route::get('/tutorial_ponpes_reguller', [RegullerPonpesController::class, 'tutorial_ponpes_reguller'])->name('tutorial_ponpes_reguller');

Route::prefix('tutor_ponpes_reguller')->name('tutor_ponpes_reguller.')->group(function () {
    Route::get('/ListDataSpp', [RegullerPonpesController::class, 'ListDataSpp'])->name('ListDataSpp');
    Route::delete('/DataBasePageDestroy/{id}', [RegullerPonpesController::class, 'DatabasePageDestroy'])->name('DataBasePageDestroy');
    Route::post('/store', [RegullerPonpesController::class, 'store'])->name('store');

    // Move PDF routes inside the group for consistency
    Route::post('/upload-pdf/{id}/{folder}', [RegullerPonpesController::class, 'uploadFilePDF'])->name('uploadFilePDF');
    Route::get('/view-pdf/{id}/{folder}', [RegullerPonpesController::class, 'viewUploadedPDF'])->name('viewpdf');
    Route::delete('/delete-pdf/{id}/{folder}', [RegullerPonpesController::class, 'deleteFilePDF'])->name('deleteFilePDF');
});
