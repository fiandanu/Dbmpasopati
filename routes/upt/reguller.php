<?php
use App\Http\Controllers\user\upt\reguler\RegullerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\PageUser;


// REGULLER

// User Page BAGIAN UPT
// AWAL DATA WAJIB NGISI NAMA UPT DAN KANWIL

// MENAMPILKAN DATA UPT DAN KANWIL DAN MENGISI DATA OPSIONALNYA
Route::delete('/DataBasePageDestroy/{id}', [RegullerController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
Route::get('/ListDataReguller', [RegullerController::class, 'ListDataReguller'])->name('ListDataReguller');
Route::put('/ListUpdateReguller/{id}', [RegullerController::class, 'ListUpdateReguller'])->name('ListUpdateReguller');
Route::get('/export-upt-csv/{id}', [RegullerController::class, 'exportVerticalCsv'])->name('export.upt.csv');
Route::get('/export-upt-pdf/{id}', [RegullerController::class, 'exportUptPdf'])->name('export.upt.pdf');


Route::prefix('upt')->name('upt.')->group(function () {
    Route::get('/UserPage', [PageUser::class, 'UserPage'])->name('UserPage');
    Route::post('/UserPageStore', [RegullerController::class, 'UserPageStore'])->name('UserPageStore');
    Route::delete('/UserPageDestroy/{id}', [RegullerController::class, 'UserPageDestroy'])->name('UserPageDestroy');
    Route::put('/UserPageUpdate/{id}', [RegullerController::class, 'UserPageUpdate'])->name('UserPageUpdate');
});
