<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\ponpes\spp\SppController;

Route::prefix('sppPonpes')->name('sppPonpes.')->group(function () {
    
    Route::get('/ListDataSpp', [SppController::class, 'ListDataSpp'])->name('ListDataSpp');
    Route::post('/store', [SppController::class, 'store'])->name('store');
    Route::delete('/destroy/{id}', [SppController::class, 'DatabasePageDestroy'])->name('DatabasePageDestroy');

    // Export Data All
    Route::get('/ponpes/spp/export-csv', [SppController::class, 'exportListCsv'])->name('export.csv');
    Route::get('/ponpes/spp/export-pdf', [SppController::class, 'exportListPdf'])->name('export.pdf');

    // Upload PDF
    Route::post('/upload-pdf/{id}/{folder}', [SppController::class, 'uploadFilePDF'])->name('uploadFilePDF');
    Route::get('/view-pdf/{id}/{folder}', [SppController::class, 'viewUploadedPDF'])->name('viewpdf');
    Route::delete('/delete-pdf/{id}/{folder}', [SppController::class, 'deleteFilePDF'])->name('deleteFilePDF');
});
