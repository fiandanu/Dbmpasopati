<?php

use App\Http\Controllers\user\upt\reguler\RegullerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\PageUser;

// REGULLER

// User Page BAGIAN UPT
// AWAL DATA WAJIB NGISI NAMA UPT DAN KANWIL



Route::prefix('upt')->name('upt.')->group(function () {


    Route::put('/ListUpdateReguller/{id}', [RegullerController::class, 'ListUpdateReguller'])->name('ListUpdateReguller');
    Route::get('/ListDataReguller', action: [RegullerController::class, 'ListDataReguller'])->name('ListDataReguller');
    Route::get('/UserPage', [RegullerController::class, 'UserPage'])->name('UserPage');
    Route::post('/UserPageStore', [RegullerController::class, 'UserPageStore'])->name('UserPageStore');
    Route::delete('/UserPageDestroy/{id}', [RegullerController::class, 'UserPageDestroy'])->name('UserPageDestroy');
    Route::put('/UserPageUpdate/{id}', [RegullerController::class, 'UserPageUpdate'])->name('UserPageUpdate');

    // Export Data Personal
    Route::get('/export-upt-csv/{id}', [RegullerController::class, 'exportVerticalCsv'])->name('export.upt.csv');
    Route::get('/export-upt-pdf/{id}', [RegullerController::class, 'exportUptPdf'])->name('export.upt.pdf');

    // New list export Global Data
    Route::get('/export-list-csv', [RegullerController::class, 'exportListCsv'])->name('export.list.csv');
    Route::get('/export-list-pdf', [RegullerController::class, 'exportListPdf'])->name('export.list.pdf');
});
