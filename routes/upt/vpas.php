<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\vpas\VpasController;

// VPAS

// MENAMPILKAN DATA UPT DAN KANWIL DAN MENGISI DATA OPSIONALNYA
Route::delete('/DataBasePageDestroy/{id}', [VpasController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
Route::get('/ListDataVpas', [VpasController::class, 'ListDataVpas'])->name('ListDataVpas');
Route::put('/ListUpdateVpas/{id}', [VpasController::class, 'ListUpdateVpas'])->name('ListUpdateVpas');
Route::get('/export-vpas-csv/{id}', [VpasController::class, 'exportVerticalCsv'])->name('export.vpas.csv');
Route::get('/export-vpas-pdf/{id}', [VpasController::class, 'exportUptPdf'])->name('export.vpas.pdf');

