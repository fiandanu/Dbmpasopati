<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\ponpes\PonpesController;

// ini di file ponpes.php

// User Routes
Route::prefix('reguler-ponpes')->name('reguler.ponpes.')->group(function () {
    Route::get('/list', [PonpesController::class, 'ListDataPonpes'])->name('ListDataPonpes');
    Route::put('/update/{id}', [PonpesController::class, 'ListDataPonpesUpdate'])->name('ListDataPonpesUpdate');
    Route::delete('/destroy/{id}', [PonpesController::class, 'PonpesPageDestroy'])->name('DataBasePageDestroyPonpes');
    Route::get('/export/pdf/{id}', [PonpesController::class, 'exportPonpesPdf'])->name('export.ponpes.pdf');
    Route::get('/export/csv/{id}', [PonpesController::class, 'exportPonpesCsv'])->name('export.ponpes.csv');
});

Route::prefix('ponpes')->name('ponpes.')->group(function () {
    Route::get('/UserPage', [PonpesController::class, 'UserPage'])->name('UserPage');
    Route::post('/UserPageStore', [PonpesController::class, 'UserPageStore'])->name('UserPageStore');
    Route::get('/UserPageEdit/{id}', [PonpesController::class, 'UserPageEdit'])->name('UserPageEdit');
    Route::put('/UserPageUpdate/{id}', [PonpesController::class, 'UserPageUpdate'])->name('UserPageUpdate');
    Route::delete('/UserPageDestroy/{id}', [PonpesController::class, 'UserPageDestroy'])->name('UserPageDestroy');
});
