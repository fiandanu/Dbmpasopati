<?php

use App\Http\Controllers\user\user\KanwilController;
use Illuminate\Support\Facades\Route;

// Route untuk Kanwil
Route::prefix('kanwil')
    ->name('kanwil.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
        Route::get('/data-kanwil', [KanwilController::class, 'index'])->name('DataKanwil');
        Route::post('/store', [KanwilController::class, 'KanwilPageStore'])->name('KanwilPageStore');
        Route::put('/update/{id}', [KanwilController::class, 'KanwilPageUpdate'])->name('KanwilPageUpdate');
        Route::delete('/destroy/{id}', [KanwilController::class, 'KanwilPageDestroy'])->name('KanwilPageDestroy');

        // Export routes
        Route::get('/export-kanwil-list-csv', [KanwilController::class, 'exportListCsv'])->name('export.kanwil.list.csv');
        Route::get('/export-kanwil-list-pdf', [KanwilController::class, 'exportListPdf'])->name('export.kanwil.list.pdf');
    });
