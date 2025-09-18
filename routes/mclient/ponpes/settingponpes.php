<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\ponpes\SettingPonpesController;

Route::prefix('mclient-ponpes-setting')->group(function () {
    
    Route::get('/', [SettingPonpesController::class, 'ListDataMclientPonpesSetting'])->name('ListDataMclientPonpesSetting');
    Route::post('/store', [SettingPonpesController::class, 'MclientPonpesSettingStore'])->name('MclientPonpesSettingStore');
    Route::put('/update/{id}', [SettingPonpesController::class, 'MclientPonpesSettingUpdate'])->name('MclientPonpesSettingUpdate');
    Route::delete('/destroy/{id}', [SettingPonpesController::class, 'MclientPonpesSettingDestroy'])->name('MclientPonpesSettingDestroy');
    Route::get('/export/csv', [SettingPonpesController::class, 'exportCsv'])->name('MclientPonpesSetting.export.csv');
    Route::get('/dashboard-stats', [SettingPonpesController::class, 'getDashboardStats'])->name('MclientPonpesSetting.dashboard.stats');
    Route::get('/get-ponpes-data', [SettingPonpesController::class, 'getPonpesData'])->name('MclientPonpesSetting.getPonpesData');
});