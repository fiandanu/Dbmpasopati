<?php
use App\Http\Controllers\user\ponpes\reguller\PonpesRegController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\PageUser;


Route::delete('/DataBasePageDestroy/{id}', [PonpesRegController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
Route::get('/ListDataReguller', [PonpesRegController::class, 'ListDataReguller'])->name('ListDataReguller');
Route::put('/ListUpdateReguller/{id}', [PonpesRegController::class, 'ListUpdateReguller'])->name('ListUpdateReguller');
Route::get('/exportPonpesPdf{id}', [PonpesRegController::class, 'exportPonpesPdf'])->name('export.ponpes.pdf');
Route::get('/exportPonpesCsv{id}', [PonpesRegController::class, 'exportPonpesCsv'])->name('export.ponpes.csv');