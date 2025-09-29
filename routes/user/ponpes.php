<?php

use App\Http\Controllers\user\user\ListDataPonpesController;
use Illuminate\Support\Facades\Route;

Route::prefix('UserPonpes')->name('UserPonpes.')->group(function () {
    // Halaman User Ponpes
    Route::get('/UserPage', [ListDataPonpesController::class, 'UserPage'])->name('UserPage');
    Route::post('/UserPageStore', [ListDataPonpesController::class, 'UserPageStore'])->name('UserPageStore');
    Route::put('/UserPageUpdate/{id}', [ListDataPonpesController::class, 'UserPageUpdate'])->name('UserPageUpdate');
    Route::delete('/PonpesPageDestroy/{id}', [ListDataPonpesController::class, 'PonpesPageDestroy'])->name('PonpesPageDestroy');

    Route::get('/ponpes/export/list/csv', [ListDataPonpesController::class, 'exportListCsv'])->name('export.list.csv');
    Route::get('/ponpes/export/list/pdf', [ListDataPonpesController::class, 'exportListPdf'])->name('export.list.pdf');
});
