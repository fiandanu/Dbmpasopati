<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\upt\UptController;
use App\Http\Controllers\user\PageUser;
use App\Http\Controllers\DataBaseController;


// User Page BAGIAN UPT
// AWAL DATA WAJIB NGISI NAMA UPT DAN KANWIL

// MENAMPILKAN DATA UPT DAN KANWIL DAN MENGISI DATA OPSIONALNYA
Route::delete('/DataBasePageDestroy/{id}', [UptController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
Route::get('/DbReguler', [UptController::class, 'DbReguler'])->name('DbReguler');
Route::get('/ListDataUpt', [UptController::class, 'ListDataUpt'])->name('ListDataUpt');
Route::put('/ListDataUpdate/{id}', [UptController::class, 'ListDataUpdate'])->name('ListDataUpdate');
Route::get('/export-upt-csv/{id}', [UptController::class, 'exportVerticalCsv'])->name('export.upt.csv');
Route::get('/export-upt-pdf/{id}', [UptController::class, 'exportUptPdf'])->name('export.upt.pdf');



Route::prefix('upt')->name('upt.')->group(function () {
    Route::get('/UserPage', [PageUser::class, 'UserPage'])->name('UserPage');
    Route::post('/UserPageStore', [UptController::class, 'UserPageStore'])->name('UserPageStore');
    Route::delete('/UserPageDestroy/{id}', [UptController::class, 'UserPageDestroy'])->name('UserPageDestroy');
    Route::get('/UserPageEdit/{id}', [UptController::class, 'UserPageEdit'])->name('UserPageEdit');
    Route::put('/UserPageUpdate/{id}', [UptController::class, 'UserPageUpdate'])->name('UserPageUpdate');
});
