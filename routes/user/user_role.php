<?php

namespace Routes\User;

use App\Http\Controllers\user\user\UserRoleController;
use Illuminate\Support\Facades\Route;


Route::prefix('UserRole')->name('UserRole.')
    ->middleware(['auth', 'role:super_admin'])->group(function () {
        Route::resource('user-role', UserRoleController::class)
            ->only('index', 'store', 'update', 'destroy');
    });
