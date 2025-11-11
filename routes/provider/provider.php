<?php

use App\Http\Controllers\user\provider\ProviderController;
use Illuminate\Support\Facades\Route;

// Route untuk Provider
Route::prefix('provider')
    ->name('provider.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
        Route::get('/data-provider', [ProviderController::class, 'index'])->name('DataProvider');
        Route::post('/store', [ProviderController::class, 'ProviderPageStore'])->name('ProviderPageStore');
        Route::put('/update/{id}', [ProviderController::class, 'ProviderPageUpdate'])->name('ProviderPageUpdate');
        Route::delete('/destroy/{id}', [ProviderController::class, 'ProviderPageDestroy'])->name('ProviderPageDestroy');

        // Export routes
        Route::get('/export-provider-list-csv', [ProviderController::class, 'exportListCsv'])->name('export.provider.list.csv');
        Route::get('/export-provider-list-pdf', [ProviderController::class, 'exportListPdf'])->name('export.provider.list.pdf');
    });
