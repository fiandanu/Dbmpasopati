<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\PageUser;
use App\Http\Controllers\user\upt\reguler\vpr;

// VPAS/REGULER

// MENAMPILKAN DATA UPT DAN KANWIL DAN MENGISI DATA OPSIONALNYA
Route::delete('/DataBasePageDestroy/{id}', [vpr::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
Route::put('/ListDataUpdate/{id}', [vpr::class, 'ListDataUpdate'])->name('ListDataUpdate');
Route::get('/export-vpas-csv/{id}', [vpr::class, 'exportVerticalCsv'])->name('export.vpas.csv');
Route::get('/export-vpas-pdf/{id}', [vpr::class, 'exportUptPdf'])->name('export.vpas.pdf');

Route::prefix('upt')->name('upt.')->group(function () {
    Route::get('/UserPage', [PageUser::class, 'UserPage'])->name('UserPage');
    Route::post('/UserPageStore', [vpr::class, 'UserPageStore'])->name('UserPageStore');
    Route::delete('/UserPageDestroy/{id}', [vpr::class, 'UserPageDestroy'])->name('UserPageDestroy');
    Route::put('/UserPageUpdate/{id}', [vpr::class, 'UserPageUpdate'])->name('UserPageUpdate');
});
