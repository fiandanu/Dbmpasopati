<?php 

use App\Http\Controllers\user\kendalapic\KendalaController;
use Illuminate\Support\Facades\Route;


// Route untuk Kendala
Route::prefix('kendala')->name('kendala.')->group(function () {
    Route::get('/data-kendala', [KendalaController::class, 'index'])->name('DataKendala');
    Route::post('/store', [KendalaController::class, 'KendalaPageStore'])->name('KendalaPageStore');
    Route::put('/update/{id}', [KendalaController::class, 'KendalaPageUpdate'])->name('KendalaPageUpdate');
    Route::delete('/destroy/{id}', [KendalaController::class, 'KendalaPageDestroy'])->name('KendalaPageDestroy');
});