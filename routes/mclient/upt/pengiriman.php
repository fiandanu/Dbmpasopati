<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\reguler\PengirimanController;

Route::prefix('mclient-pengiriman-upt')
    ->name('mclientpengirimanupt.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {

        Route::get('/', [PengirimanController::class, 'ListDataMclientPengirimanUpt'])->name('ListDataMclientPengirimanUpt');
        Route::post('/store', [PengirimanController::class, 'MclientPengirimanStoreUpt'])->name('MclientPengirimanStoreUpt');
        Route::put('/update/{id}', [PengirimanController::class, 'MclientPengirimanUpdateUpt'])->name('MclientPengirimanUpdateUpt');
        Route::delete('/destroy/{id}', [PengirimanController::class, 'MclientKunjunganDestroyUpt'])->name('MclientKunjunganDestroyUpt');

        // routes/web.php
        Route::get('/mcpengiriman/export/csv', [PengirimanController::class, 'exportListCsv'])->name('mcpengiriman.export.list.csv');
        Route::get('/mcpengiriman/export/pdf', [PengirimanController::class, 'exportListPdf'])->name('mcpengiriman.export.list.pdf');
    });
