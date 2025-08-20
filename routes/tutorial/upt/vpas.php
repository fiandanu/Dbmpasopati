<?php

use App\Http\Controllers\tutorial\upt\VpasController;
use Illuminate\Support\Facades\Route;

Route::get('/tutorial_vpas', [VpasController::class, 'tutorial_vpas'])->name('tutorial_vpas');

Route::prefix('tutor_vpas')->name('tutor_vpas.')->group(function () {
    Route::get('/ListDataSpp', [VpasController::class, 'ListDataSpp'])->name('ListDataSpp');
    Route::delete('/DataBasePageDestroy/{id}', [VpasController::class, 'DatabasePageDestroy'])->name('DataBasePageDestroy');
    Route::post('/store', [VpasController::class, 'store'])->name('store');

    Route::post('/upload-pdf/{id}/{folder}', [VpasController::class, 'uploadFilePDF'])->name('uploadFilePDF');
    Route::get('/view-pdf/{id}/{folder}', [VpasController::class, 'viewUploadedPDF'])->name('viewpdf');
    Route::delete('/delete-pdf/{id}/{folder}', [VpasController::class, 'deleteFilePDF'])->name('deleteFilePDF');
});
