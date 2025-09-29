<?php

use App\Http\Controllers\user\user\ListDataUptController;
use Illuminate\Support\Facades\Route;

Route::prefix('User')->name('User.')->group(function () {
    Route::get('/UserPage', [ListDataUptController::class, 'UserPage'])->name('UserPage');
    Route::post('/UserPageStore', [ListDataUptController::class, 'UserPageStore'])->name('UserPageStore');
    Route::delete('/UserPageDestroy/{id}', [ListDataUptController::class, 'UserPageDestroy'])->name('UserPageDestroy');
    Route::put('/UserPageUpdate/{id}', [ListDataUptController::class, 'UserPageUpdate'])->name('UserPageUpdate');

    // New list export Global Data
    Route::get('/export-list-csv', [ListDataUptController::class, 'exportListCsv'])->name('export.list.csv');
    Route::get('/export-list-pdf', [ListDataUptController::class, 'exportListPdf'])->name('export.list.pdf');
});
