<?php

use App\Http\Controllers\tutorial\upt\mikrotikController;
use Illuminate\Support\Facades\Route;

Route::get('/tutorial_mikrotik', [mikrotikController::class, 'tutorial_mikrotik'])->name('tutorial_mikrotik');

Route::prefix('mikrotik_page')->name('mikrotik_page.')->group(function () {
    Route::get('/ListDataSpp', [mikrotikController::class, 'ListDataSpp'])->name('ListDataSpp');
    Route::delete('/DataBasePageDestroy/{id}', [mikrotikController::class, 'DatabasePageDestroy'])->name('DataBasePageDestroy');
    Route::post('/store', [mikrotikController::class, 'store'])->name('store');

    // PDF routes
    Route::post('/upload-pdf/{id}/{folder}', [mikrotikController::class, 'uploadFilePDF'])->name('uploadFilePDF');
    Route::get('/view-pdf/{id}/{folder}', [mikrotikController::class, 'viewUploadedPDF'])->name('viewpdf');
    Route::delete('/delete-pdf/{id}/{folder}', [mikrotikController::class, 'deleteFilePDF'])->name('deleteFilePDF');

    // Export routes
    Route::get('/export-mikrotik-list-csv', [mikrotikController::class, 'exportListCsv'])->name('export.mikrotik.list.csv');
    Route::get('/export-mikrotik-list-pdf', [mikrotikController::class, 'exportListPdf'])->name('export.mikrotik.list.pdf');
});
