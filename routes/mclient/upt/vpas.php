<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\reguler\VpasController;

Route::prefix('mclient-vpas')->name('mcvpas.')->group(function () {
    
    Route::get('/', [VpasController::class, 'ListDataMclientVpas'])->name('ListDataMclientVpas');
    Route::post('/store', [VpasController::class, 'MclientVpasStore'])->name('MclientVpasStore');
    Route::put('/update/{id}', [VpasController::class, 'MclientVpasUpdate'])->name('MclientVpasUpdate');
    Route::delete('/destroy/{id}', [VpasController::class, 'MclientVpasDestroy'])->name('MclientVpasDestroy');

    // New global export routes
    Route::get('/export-list-csv', [VpasController::class, 'exportListCsv'])->name('export.list.csv');
    Route::get('/export-list-pdf', [VpasController::class, 'exportListPdf'])->name('export.list.pdf');
});