<?php   

use App\Http\Controllers\user\kendalapic\PicController;
use Illuminate\Support\Facades\Route;



// Route untuk PIC
Route::prefix('pic')->name('pic.')->group(function () {
    Route::get('/data-pic', [PicController::class, 'index'])->name('DataPic');
    Route::post('/store', [PicController::class, 'PicPageStore'])->name('PicPageStore');
    Route::put('/update/{id}', [PicController::class, 'PicPageUpdate'])->name('PicPageUpdate');
    Route::delete('/destroy/{id}', [PicController::class, 'PicPageDestroy'])->name('PicPageDestroy');
});