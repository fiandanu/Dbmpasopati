<?php

// Dashboard UPT dengan Grafik
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\user\upt\DashboardUptController;
use App\Http\Controllers\user\PageUser;
use App\Http\Controllers\user\upt\DashboardUptController;

    Route::prefix('database')->name('database.')->group(function () {
    Route::get('/DbUpt', [DashboardUptController::class, 'index'])->name('DbUpt');
    Route::get('/DataBasePonpes', [PageUser::class, 'DataBasePonpes'])->name('DataBasePonpes');
});