<?php

use App\Http\Controllers\user\upt\vpr\VprController;
use Illuminate\Support\Facades\Route;

// VPAS/REGULER

// MENAMPILKAN DATA UPT DAN KANWIL DAN MENGISI DATA OPSIONALNYA
Route::delete('/DataBasePageDestroy/{id}', [VprController::class, 'DataBasePageDestroy'])->name('DataBasePageDestroy');
Route::get('/ListDataVpr', [VprController::class, 'ListDataVpr'])->name('ListDataVpr');
Route::put('/ListUpdateReguller/{id}', [VprController::class, 'ListUpdateReguller'])->name('ListUpdateReguller');
Route::get('/export-upt-csv/{id}', [VprController::class, 'exportVerticalCsv'])->name('export.upt.csv');
Route::get('/export-upt-pdf/{id}', [VprController::class, 'exportUptPdf'])->name('export.upt.pdf');

// C:\laragon\www\Backup Project\Dbmpasopati\app\Http\Controllers\user\upt\vpasreguler\VprController.php