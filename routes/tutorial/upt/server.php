<?php

use App\Http\Controllers\tutorial\upt\ServerController;
use Illuminate\Support\Facades\Route;

Route::get('/tutorial_server', [ServerController::class, 'tutorial_server'])->name('tutorial_server');

Route::prefix('server_page')
    ->name('server_page.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
        Route::get('/ListDataSpp', [ServerController::class, 'ListDataSpp'])->name('ListDataSpp');
        Route::delete('/DataBasePageDestroy/{id}', [ServerController::class, 'DatabasePageDestroy'])->name('DataBasePageDestroy');
        Route::post('/store', [ServerController::class, 'store'])->name('store');

        // PDF routes
        Route::post('/upload-pdf/{id}/{folder}', [ServerController::class, 'uploadFilePDF'])->name('uploadFilePDF');
        Route::get('/view-pdf/{id}/{folder}', [ServerController::class, 'viewUploadedPDF'])->name('viewpdf');
        Route::delete('/delete-pdf/{id}/{folder}', [ServerController::class, 'deleteFilePDF'])->name('deleteFilePDF');

        // Export routes
        Route::get('/export-server-list-csv', [ServerController::class, 'exportListCsv'])->name('export.server.list.csv');
        Route::get('/export-server-list-pdf', [ServerController::class, 'exportListPdf'])->name('export.server.list.pdf');
    });
