<?php

// use App\Http\Controllers\user\kendalapic\KendalaController;

use App\Http\Controllers\user\kendalapic\KendalaPicController;
use Illuminate\Support\Facades\Route;

// Route untuk Kendala
Route::prefix('kendala-pic')
    ->middleware(['auth', 'role:super_admin,teknisi,marketing'])
    ->name('kendalapic.')
    ->group(function () {
        // Route utama
        Route::get('/', [KendalaPicController::class, 'index'])->name('index');
        
        // Routes untuk Kendala
        Route::post('/kendala/store', [KendalaPicController::class, 'kendalaStore'])->name('kendala.store');
        Route::put('/kendala/update/{id}', [KendalaPicController::class, 'kendalaUpdate'])->name('kendala.update');
        Route::delete('/kendala/destroy/{id}', [KendalaPicController::class, 'kendalaDestroy'])->name('kendala.destroy');
        Route::get('/kendala/export/csv', [KendalaPicController::class, 'exportKendalaCsv'])->name('kendala.export.csv');
        Route::get('/kendala/export/pdf', [KendalaPicController::class, 'exportKendalaPdf'])->name('kendala.export.pdf');
        
        // Routes untuk PIC
        Route::post('/pic/store', [KendalaPicController::class, 'picStore'])->name('pic.store');
        Route::put('/pic/update/{id}', [KendalaPicController::class, 'picUpdate'])->name('pic.update');
        Route::delete('/pic/destroy/{id}', [KendalaPicController::class, 'picDestroy'])->name('pic.destroy');
        Route::get('/pic/export/csv', [KendalaPicController::class, 'exportPicCsv'])->name('pic.export.csv');
        Route::get('/pic/export/pdf', [KendalaPicController::class, 'exportPicPdf'])->name('pic.export.pdf');
    });
