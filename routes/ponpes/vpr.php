<?php

use App\Http\Controllers\user\ponpes\vpr\VprPonpesController;
use Illuminate\Support\Facades\Route;

// ini di file ponpes.php

Route::get('/ListDataVprPonpes', [VprPonpesController::class, 'ListDataVprPonpes'])->name('ListDataVprPonpes');
Route::put('/ListDataPonpesUpdate/{id}', [VprPonpesController::class, 'ListDataPonpesUpdate'])->name('ListDataPonpesUpdate');
Route::delete('/PonpesPageDestroy/{id}', [VprPonpesController::class, 'PonpesPageDestroy'])->name('PonpesPageDestroy');
Route::get('/exportPonpesPdf/{id}', [VprPonpesController::class, 'exportPonpesPdf'])->name('exportPonpesPdf');
Route::get('/exportPonpesCsv/{id}', [VprPonpesController::class, 'exportPonpesCsv'])->name('exportPonpesCsv');

