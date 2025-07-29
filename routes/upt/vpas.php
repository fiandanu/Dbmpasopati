<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\PageUser;
use App\Http\Controllers\user\upt\vpas\VpasController;


// VPAS

// MENAMPILKAN DATA UPT DAN KANWIL DAN MENGISI DATA OPSIONALNYA
Route::delete('/DataBasePageDestroy/{id}', [VpasController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
Route::get('/ListDataVpas', [VpasController::class, 'ListDataVpas'])->name('ListDataVpas');
// Route::get('/DbReguler', [VpasController::class, 'DbReguler'])->name('DbReguler');
Route::put('/ListDataUpdate/{id}', [VpasController::class, 'ListDataUpdate'])->name('ListDataUpdate');
Route::get('/export-vpas-csv/{id}', [VpasController::class, 'exportVerticalCsv'])->name('export.vpas.csv');
Route::get('/export-vpas-pdf/{id}', [VpasController::class, 'exportUptPdf'])->name('export.vpas.pdf');

Route::prefix('upt')->name('upt.')->group(function () {
    Route::get('/UserPage', [PageUser::class, 'UserPage'])->name('UserPage');
    Route::post('/UserPageStore', [VpasController::class, 'UserPageStore'])->name('UserPageStore');
    Route::delete('/UserPageDestroy/{id}', [VpasController::class, 'UserPageDestroy'])->name('UserPageDestroy');
    Route::put('/UserPageUpdate/{id}', [VpasController::class, 'UserPageUpdate'])->name('UserPageUpdate');
});
