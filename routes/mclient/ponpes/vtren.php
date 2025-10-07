<?php

use App\Http\Controllers\mclient\ponpes\VtrenController;
use Illuminate\Support\Facades\Route;

Route::prefix('mclient-vtren')->name('mcvtren.')->group(function () {

    Route::get('/', [VtrenController::class, 'ListDataMclientVtren'])->name('ListDataMclientVtren');
    Route::post('/store', [VtrenController::class, 'MclientVtrenStore'])->name('MclientVtrenStore');
    Route::put('/update/{id}', [VtrenController::class, 'MclientVtrenUpdate'])->name('MclientVtrenUpdate');
    Route::delete('/destroy/{id}', [VtrenController::class, 'MclientVtrenDestroy'])->name('MclientVtrenDestroy');

    // New global export routes
    Route::get('/export-list-csv', [VtrenController::class, 'exportListCsv'])->name('export.list.csv');
    Route::get('/export-list-pdf', [VtrenController::class, 'exportListPdf'])->name('export.list.pdf');
});