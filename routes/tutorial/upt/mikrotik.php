<?php

use App\Http\Controllers\tutorial\upt\MikrotikController;
use Illuminate\Support\Facades\Route;


Route::get('/tutorial_mikrotik', [MikrotikController::class, 'tutorial_mikrotik'])->name('tutorial_mikrotik');

Route::prefix('mikrotik_page')->name('mikrotik_page.')->group(function () {
    Route::get('/ListDataSpp', [MikrotikController::class, 'ListDataSpp'])->name('ListDataSpp');
    Route::delete('/DataBasePageDestroy/{id}', [MikrotikController::class, 'DatabasePageDestroy'])->name('DataBasePageDestroy');
    Route::post('/store', [MikrotikController::class, 'store'])->name('store');

    // Move PDF routes inside the group for consistency
    Route::post('/upload-pdf/{id}/{folder}', [MikrotikController::class, 'uploadFilePDF'])->name('uploadFilePDF');
    Route::get('/view-pdf/{id}/{folder}', [MikrotikController::class, 'viewUploadedPDF'])->name('viewpdf');
    Route::delete('/delete-pdf/{id}/{folder}', [MikrotikController::class, 'deleteFilePDF'])->name('deleteFilePDF');
});
