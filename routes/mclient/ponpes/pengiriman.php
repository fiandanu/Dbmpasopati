<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\ponpes\PengirimanController;

Route::prefix('mclient-ponpes-pengiriman')
    ->name('mclientpengirimanponpes.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
        Route::get('/', [PengirimanController::class, 'ListDataMclientPonpesPengiriman'])->name('ListDataMclientPonpesPengiriman');
        Route::post('/store', [PengirimanController::class, 'MclientPonpesPengirimanStore'])->name('MclientPonpesPengirimanStore');
        Route::put('/update/{id}', [PengirimanController::class, 'MclientPonpesPengirimanUpdatePonpes'])->name('MclientPonpesPengirimanUpdatePonpes');
        Route::delete('/destroy/{id}', [PengirimanController::class, 'MclientPonpesPengirimanDestroyPonpes'])->name('MclientPonpesPengirimanDestroyPonpes');

        // Global export with filters
        Route::get('/export/pdf', [PengirimanController::class, 'exportListPdf'])->name('mclientpengirimanponpes.export.list.pdf');
        Route::get('/export/csv', [PengirimanController::class, 'exportListCsv'])->name('mclientpengirimanponpes.export.list.csv');
    });
