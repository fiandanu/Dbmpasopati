<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\ponpes\SettingPonpesController;

Route::prefix('mclient-ponpes-setting')->name('mclientponpessetting.')->group(function () {
    Route::get('/', [SettingPonpesController::class, 'ListDataMclientPonpesSetting'])->name('ListDataMclientPonpesSetting');
    Route::post('/store', [SettingPonpesController::class, 'MclientPonpesSettingStore'])->name('MclientPonpesSettingStore');
    Route::put('/update/{id}', [SettingPonpesController::class, 'MclientPonpesSettingUpdate'])->name('MclientPonpesSettingUpdate');
    Route::delete('/destroy/{id}', [SettingPonpesController::class, 'MclientPonpesSettingDestroy'])->name('MclientPonpesSettingDestroy');

    // Global export with filters
    Route::get('/export/pdf', [SettingPonpesController::class, 'exportListPdf'])->name('mclientsettingponpes.export.list.pdf');
    Route::get('/export/csv', [SettingPonpesController::class, 'exportListCsv'])->name('mclientsettingponpes.export.list.csv');
});
