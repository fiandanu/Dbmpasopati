<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\PageUser;
use App\Http\Controllers\user\upt\reguler\Kontol;

// VPAS/REGULER

// MENAMPILKAN DATA UPT DAN KANWIL DAN MENGISI DATA OPSIONALNYA
Route::delete('/DataBasePageDestroy/{id}', [Kontol::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
Route::put('/ListDataUpdate/{id}', [Kontol::class, 'ListDataUpdate'])->name('ListDataUpdate');
Route::get('/export-vpas-csv/{id}', [Kontol::class, 'exportVerticalCsv'])->name('export.vpas.csv');
Route::get('/export-vpas-pdf/{id}', [Kontol::class, 'exportUptPdf'])->name('export.vpas.pdf');

Route::prefix('upt')->name('upt.')->group(function () {
    Route::get('/UserPage', [PageUser::class, 'UserPage'])->name('UserPage');
    Route::post('/UserPageStore', [Kontol::class, 'UserPageStore'])->name('UserPageStore');
    Route::delete('/UserPageDestroy/{id}', [Kontol::class, 'UserPageDestroy'])->name('UserPageDestroy');
    Route::put('/UserPageUpdate/{id}', [Kontol::class, 'UserPageUpdate'])->name('UserPageUpdate');
});
