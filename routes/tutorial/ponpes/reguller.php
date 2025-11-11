<?php

use App\Http\Controllers\tutorial\ponpes\RegullerPonpesController;
use Illuminate\Support\Facades\Route;

Route::get('/tutorial_ponpes_reguller', [RegullerPonpesController::class, 'tutorial_ponpes_reguller'])->name('tutorial_ponpes_reguller');

Route::prefix('tutor_ponpes_reguller')
    ->name('tutor_ponpes_reguller.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
        // // Kategori Upt Tutorial
        Route::get('/TutorialPonpes', [RegullerPonpesController::class, 'Tutorial_Upt_Reguler'])->name('TutorialPonpes');

        Route::get('/ListDataSpp', [RegullerPonpesController::class, 'ListDataSpp'])->name('ListDataSpp');
        Route::delete('/DataBasePageDestroy/{id}', [RegullerPonpesController::class, 'DatabasePageDestroy'])->name('DataBasePageDestroy');
        Route::post('/store', [RegullerPonpesController::class, 'store'])->name('store');

        // PDF routes
        Route::post('/upload-pdf/{id}/{folder}', [RegullerPonpesController::class, 'uploadFilePDF'])->name('uploadFilePDF');
        Route::get('/view-pdf/{id}/{folder}', [RegullerPonpesController::class, 'viewUploadedPDF'])->name('viewpdf');
        Route::delete('/delete-pdf/{id}/{folder}', [RegullerPonpesController::class, 'deleteFilePDF'])->name('deleteFilePDF');

        // Export routes
        Route::get('/export-reguller-list-csv', [RegullerPonpesController::class, 'exportListCsv'])->name('export.reguller.list.csv');
        Route::get('/export-reguller-list-pdf', [RegullerPonpesController::class, 'exportListPdf'])->name('export.reguller.list.pdf');
    });
