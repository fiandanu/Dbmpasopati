<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\ponpes\reguller\RegullerController;

// ini di file ponpes.php

Route::get('/ListDataPonpes', [RegullerController::class, 'ListDataPonpes'])->name('ListDataPonpes');
Route::put('/ListDataPonpesUpdate/{id}', [RegullerController::class, 'ListDataPonpesUpdate'])->name('ListDataPonpesUpdate');
Route::delete('/PonpesPageDestroy/{id}', [RegullerController::class, 'PonpesPageDestroy'])->name('PonpesPageDestroy');
Route::get('/exportPonpesPdf/{id}', [RegullerController::class, 'exportPonpesPdf'])->name('exportPonpesPdf');
Route::get('/exportPonpesCsv/{id}', [RegullerController::class, 'exportPonpesCsv'])->name('exportPonpesCsv');

Route::prefix('ponpes')->name('ponpes.')->group(function () {
    Route::get('/UserPage', [RegullerController::class, 'UserPage'])->name('UserPage');
    Route::post('/UserPageStore', [RegullerController::class, 'UserPageStore'])->name('UserPageStore');
    Route::get('/UserPageEdit/{id}', [RegullerController::class, 'UserPageEdit'])->name('UserPageEdit');
    Route::put('/UserPageUpdate/{id}', [RegullerController::class, 'UserPageUpdate'])->name('UserPageUpdate');
    Route::delete('/UserPageDestroy/{id}', [RegullerController::class, 'UserPageDestroy'])->name('UserPageDestroy');
});
