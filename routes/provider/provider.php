<?php   

use App\Http\Controllers\user\provider\ProviderController;
use Illuminate\Support\Facades\Route;



// Route untuk Provider
Route::prefix('provider')->name('provider.')->group(function () {
    Route::get('/data-provider', [ProviderController::class, 'index'])->name('DataProvider');
    Route::post('/store', [ProviderController::class, 'ProviderPageStore'])->name('ProviderPageStore');
    Route::put('/update/{id}', [ProviderController::class, 'ProviderPageUpdate'])->name('ProviderPageUpdate');
    Route::delete('/destroy/{id}', [ProviderController::class, 'ProviderPageDestroy'])->name('ProviderPageDestroy');
});
