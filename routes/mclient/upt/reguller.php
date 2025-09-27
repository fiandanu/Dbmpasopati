<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\reguler\RegullerController;

Route::prefix('mclient-reguler')->name('mcreguler.')->group(function () {
    
    Route::get('/', [RegullerController::class, 'ListDataMclientReguller'])->name('ListDataMclientReguller');
    Route::post('/store', [RegullerController::class, 'MclientRegullerStore'])->name('MclientRegullerStore');
    Route::put('/update/{id}', [RegullerController::class, 'MclientRegullerUpdate'])->name('MclientRegullerUpdate');
    Route::delete('/destroy/{id}', [RegullerController::class, 'MclientRegullerDestroy'])->name('MclientRegullerDestroy');
    Route::get('/export/csv', [RegullerController::class, 'exportCsv'])->name('MclientReguller.export.csv');
    Route::get('/dashboard-stats', [RegullerController::class, 'getDashboardStats'])->name('MclientReguller.dashboard.stats');
    Route::get('/get-upt-data', [RegullerController::class, 'getUptData'])->name('MclientReguller.getUptData');
    
    // New global export routes
    Route::get('/export-list-csv', [RegullerController::class, 'exportListCsv'])->name('export.list.csv');
    Route::get('/export-list-pdf', [RegullerController::class, 'exportListPdf'])->name('export.list.pdf');
});