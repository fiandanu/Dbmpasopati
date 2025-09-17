<?php

// use App\Http\Controllers\mclient\reguler\KunjunganController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\reguler\KunjunganController;

Route::prefix('mclient-reguller-kunjungan')->group(function () {
    Route::get('/', [KunjunganController::class, 'ListDataMclientReguller'])->name('ListDataMclientReguller');
    Route::post('/store', [KunjunganController::class, 'MclientRegullerStore'])->name('MclientRegullerStore');
    Route::put('/update/{id}', [KunjunganController::class, 'MclientRegullerUpdate'])->name('MclientRegullerUpdate');
    Route::delete('/destroy/{id}', [KunjunganController::class, 'MclientRegullerDestroy'])->name('MclientRegullerDestroy');
});
