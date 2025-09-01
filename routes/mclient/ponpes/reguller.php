<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\ponpes\RegullerController;

Route::prefix('mclient-ponpes-reguller')->group(function () {
    
    Route::get('/', [RegullerController::class, 'ListDataMclientPonpesReguller'])->name('ListDataMclientPonpesReguller');
    Route::post('/store', [RegullerController::class, 'MclientPonpesRegullerStore'])->name('MclientPonpesRegullerStore');
    Route::put('/update/{id}', [RegullerController::class, 'MclientPonpesRegullerUpdate'])->name('MclientPonpesRegullerUpdate');
    Route::delete('/destroy/{id}', [RegullerController::class, 'MclientPonpesRegullerDestroy'])->name('MclientPonpesRegullerDestroy');
    Route::get('/export/csv', [RegullerController::class, 'exportCsv'])->name('MclientPonpesReguller.export.csv');
    Route::get('/dashboard-stats', [RegullerController::class, 'getDashboardStats'])->name('MclientPonpesReguller.dashboard.stats');
    Route::get('/get-ponpes-data', [RegullerController::class, 'getPonpesData'])->name('MclientPonpesReguller.getPonpesData');
});