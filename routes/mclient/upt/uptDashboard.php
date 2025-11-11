<?php

namespace Routes\Mclient\Upt;

use App\Http\Controllers\mclient\DashboardUptController;
use Illuminate\Support\Facades\Route;


Route::prefix('mclient-upt-dashboard')
    ->name('MclientUptDashboard.')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->group(function () {
        Route::get('/KomplainUpt', [DashboardUptController::class, 'monitoringClientUptOverview'])->name('KomplainUpt');

        // EXPORT GLOBAL DATA TOP (data bagian atas)
        Route::get('/monitoring-client-upt-summary/export-csv', [DashboardUptController::class, 'exportMonitoringClientSummaryCsv'])->name('mclient.upt.summary.export.csv');
        Route::get('/monitoring-client-upt-summary/export-pdf', [DashboardUptController::class, 'exportMonitoringClientSummaryPdf'])->name('mclient.upt.summary.export.pdf');

        // EXPORT GLOBAL DATA (data bagian bawah)
        Route::get('/monitoring-client-upt/export-csv', [DashboardUptController::class, 'exportMonitoringClientCsv'])->name('mclient.upt.export.csv');
        Route::get('/monitoring-client-upt/export-pdf', [DashboardUptController::class, 'exportMonitoringClientPdf'])->name('mclient.upt.export.pdf');
    });
