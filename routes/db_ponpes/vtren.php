<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\ponpes\vtren\VtrenController;

Route::get('/ListDataVtrend', [VtrenController::class, 'ListDataVtrend'])->name('ListDataVtrend');
Route::put('/ListDataPonpesUpdate/{id}', [VtrenController::class, 'ListDataPonpesUpdate'])->name('ListDataPonpesUpdate');
Route::delete('/PonpesPageDestroy/{id}', [VtrenController::class, 'PonpesPageDestroy'])->name('PonpesPageDestroy');
Route::get('/exportPonpesPdf/{id}', [VtrenController::class, 'exportPonpesPdf'])->name('exportPonpesPdf');
Route::get('/exportPonpesCsv/{id}', [VtrenController::class, 'exportPonpesCsv'])->name('exportPonpesCsv');

