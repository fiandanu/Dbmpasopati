<?php

use App\Http\Controllers\user\user\KanwilController;
use Illuminate\Support\Facades\Route;


// Route untuk Kanwil
Route::prefix('kanwil')->name('kanwil.')->group(function () {
    Route::get('/data-kanwil', [KanwilController::class, 'index'])->name('DataKanwil');
    Route::post('/store', [KanwilController::class, 'KanwilPageStore'])->name('KanwilPageStore');
    Route::put('/update/{id}', [KanwilController::class, 'KanwilPageUpdate'])->name('KanwilPageUpdate');
    Route::delete('/destroy/{id}', [KanwilController::class, 'KanwilPageDestroy'])->name('KanwilPageDestroy');
});
