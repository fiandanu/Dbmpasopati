<?php

use App\Http\Controllers\user\user\NamaWilayahController;
use Illuminate\Support\Facades\Route;

// Route untuk Nama Wilayah
Route::prefix('nama-wilayah')->name('namawilayah.')->group(function () {
    Route::get('/data-nama-wilayah', [NamaWilayahController::class, 'index'])->name('DataNamaWilayah');
    Route::post('/store', [NamaWilayahController::class, 'NamaWilayahPageStore'])->name('NamaWilayahPageStore');
    Route::put('/update/{id}', [NamaWilayahController::class, 'NamaWilayahPageUpdate'])->name('NamaWilayahPageUpdate');
    Route::delete('/destroy/{id}', [NamaWilayahController::class, 'NamaWilayahPageDestroy'])->name('NamaWilayahPageDestroy');

    // Export routes
    Route::get('/export-namawilayah-list-csv', [NamaWilayahController::class, 'exportListCsv'])->name('export.namawilayah.list.csv');
    Route::get('/export-namawilayah-list-pdf', [NamaWilayahController::class, 'exportListPdf'])->name('export.namawilayah.list.pdf');
});
