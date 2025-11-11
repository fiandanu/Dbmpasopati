<?php

namespace Routes\Mclient\Ponpes;

use App\Http\Controllers\mclient\DashboardPonpesController;
use Illuminate\Support\Facades\Route;


Route::prefix('mclient-ponpes-dashboard')
    ->name('MclientPonpesDashboard.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {

        Route::get('/KomplainPonpes', [DashboardPonpesController::class, 'monitoringClientPonpesOverview'])->name('KomplainPonpes');

        // EXPORT GLOBAL DATA TOP (data bagian atas)
        Route::get('/monitoring-client-ponpes-summary/export-csv', [DashboardPonpesController::class, 'exportMonitoringClientSummaryCsv'])->name('mclient.ponpes.summary.export.csv');
        Route::get('/monitoring-client-ponpes-summary/export-pdf', [DashboardPonpesController::class, 'exportMonitoringClientSummaryPdf'])->name('mclient.ponpes.summary.export.pdf');

        // EXPORT GLOBAL DATA (data bagian bawah)
        Route::get('/monitoring-client-ponpes/export-csv', [DashboardPonpesController::class, 'exportMonitoringClientCsv'])->name('mclient.ponpes.export.csv');
        Route::get('/monitoring-client-ponpes/export-pdf', [DashboardPonpesController::class, 'exportMonitoringClientPdf'])->name('mclient.ponpes.export.pdf');
    });
