<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\reguler\KunjunganController;

Route::prefix('mclient-kunjungan-upt')
    ->name('mclientkunjunganupt.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {

        Route::get('/', [KunjunganController::class, 'ListDataMclientKunjungan'])->name('ListDataMclientKunjungan');
        Route::post('/store', [KunjunganController::class, 'MclientKunjunganStore'])->name('MclientKunjunganStore');
        Route::put('/update/{id}', [KunjunganController::class, 'MclientKunjunganUpdate'])->name('MclientKunjunganUpdate');
        Route::delete('/destroy/{id}', [KunjunganController::class, 'MclientKunjunganDestroy'])->name('MclientKunjunganDestroy');

        // global export
        Route::get('/kunjungan/export/pdf', [KunjunganController::class, 'exportListPdf'])->name('mckunjungan.export.list.pdf');
        Route::get('/kunjungan/export/csv', [KunjunganController::class, 'exportListCsv'])->name('mckunjungan.export.list.csv');
    });
