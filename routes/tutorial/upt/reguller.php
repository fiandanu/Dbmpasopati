<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\tutorial\upt\RegullerController;

Route::get('/tutorial', [RegullerController::class, 'tutorial'])->name('tutorial');

Route::get('/TutorialUpt', [RegullerController::class, 'TutorialUpt'])->name('TutorialUpt');

Route::prefix('tutor_upt')->name('tutor_upt.')->group(function () {
    Route::get('/ListDataSpp', [RegullerController::class, 'ListDataSpp'])->name('ListDataSpp');
    Route::delete('/DataBasePageDestroy/{id}', [RegullerController::class, 'DatabasePageDestroy'])->name('DataBasePageDestroy');
    Route::post('/store', [RegullerController::class, 'store'])->name('store');

    // Move PDF routes inside the group for consistency
    Route::post('/upload-pdf/{id}/{folder}', [RegullerController::class, 'uploadFilePDF'])->name('uploadFilePDF');
    Route::get('/view-pdf/{id}/{folder}', [RegullerController::class, 'viewUploadedPDF'])->name('viewpdf');
    Route::delete('/delete-pdf/{id}/{folder}', [RegullerController::class, 'deleteFilePDF'])->name('deleteFilePDF');

    // Export routes
    Route::get('/export-upt-list-csv', [RegullerController::class, 'exportListCsv'])->name('export.upt.list.csv');
    Route::get('/export-upt-list-pdf', [RegullerController::class, 'exportListPdf'])->name('export.upt.list.pdf');
});
