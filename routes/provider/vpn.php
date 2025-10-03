<?php

use App\Http\Controllers\user\provider\VpnController;
use Illuminate\Support\Facades\Route;

// Route untuk VPN
Route::prefix('vpn')->name('vpn.')->group(function () {
    Route::get('/data-vpn', [VpnController::class, 'index'])->name('DataVpn');
    Route::post('/store', [VpnController::class, 'VpnPageStore'])->name('VpnPageStore');
    Route::put('/update/{id}', [VpnController::class, 'VpnPageUpdate'])->name('VpnPageUpdate');
    Route::delete('/destroy/{id}', [VpnController::class, 'VpnPageDestroy'])->name('VpnPageDestroy');

    // Export routes
    Route::get('/export-vpn-list-csv', [VpnController::class, 'exportListCsv'])->name('export.vpn.list.csv');
    Route::get('/export-vpn-list-pdf', [VpnController::class, 'exportListPdf'])->name('export.vpn.list.pdf');
});
