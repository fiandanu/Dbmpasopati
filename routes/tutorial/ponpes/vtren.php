<?php

use App\Http\Controllers\tutorial\ponpes\vtrenController;
use Illuminate\Support\Facades\Route;

Route::get('/tutorial_ponpes_vtren', [vtrenController::class, 'tutorial_ponpes_vtren'])->name('tutorial_ponpes_vtren');

Route::prefix('tutor_ponpes_vtren')
    ->name('tutor_ponpes_vtren.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
        Route::get('/ListDataSpp', [vtrenController::class, 'ListDataSpp'])->name('ListDataSpp');
        Route::delete('/DataBasePageDestroy/{id}', [vtrenController::class, 'DatabasePageDestroy'])->name('DataBasePageDestroy');
        Route::post('/store', [vtrenController::class, 'store'])->name('store');

        // PDF routes
        Route::post('/upload-pdf/{id}/{folder}', [vtrenController::class, 'uploadFilePDF'])->name('uploadFilePDF');
        Route::get('/view-pdf/{id}/{folder}', [vtrenController::class, 'viewUploadedPDF'])->name('viewpdf');
        Route::delete('/delete-pdf/{id}/{folder}', [vtrenController::class, 'deleteFilePDF'])->name('deleteFilePDF');

        // Export routes
        Route::get('/export-vtren-list-csv', [vtrenController::class, 'exportListCsv'])->name('export.vtren.list.csv');
        Route::get('/export-vtren-list-pdf', [vtrenController::class, 'exportListPdf'])->name('export.vtren.list.pdf');
    });
