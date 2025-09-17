<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mclient\reguler\RegullerController;

Route::prefix('mclient-reguller')->group(function () {
    Route::get('/', [RegullerController::class, 'ListDataMclientReguller'])->name('ListDataMclientReguller');
    Route::post('/store', [RegullerController::class, 'MclientRegullerStore'])->name('MclientRegullerStore');
    Route::put('/update/{id}', [RegullerController::class, 'MclientRegullerUpdate'])->name('MclientRegullerUpdate');
    Route::delete('/destroy/{id}', [RegullerController::class, 'MclientRegullerDestroy'])->name('MclientRegullerDestroy');
});
