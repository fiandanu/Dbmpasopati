<?php

use App\Http\Controllers\DataBaseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MonitoringServerController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'login'])->name('login');


Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');


// Database
Route::get('/DataBasePonpes', [DataBaseController::class, 'DataBasePonpes'])->name('DataBasePonpes');
Route::get('/DataBaseUpt', [DataBaseController::class, 'DataBaseUpt'])->name('DataBaseUpt');

// PKS
Route::get('/DbPks', [DataBaseController::class, 'DbPks'])->name('DbPks');
Route::get('/DbCreatePks', [DataBaseController::class, 'DbCreatePks'])->name('DbCreatePks');
Route::post('/PksStore', [DataBaseController::class, 'PksStore'])->name('PksStore');


// Database


// Monitoring Server
Route::get('/GrafikServer', [MonitoringServerController::class, 'GrafikServer'])->name('GrafikServer');
Route::get('/MonitoringUpt', [MonitoringServerController::class, 'MonitoringUpt'])->name('MonitoringUpt');
Route::get('/MonitoringPonpes', [MonitoringServerController::class, 'MonitoringPonpes'])->name('MonitoringPonpes');
// Monitoring Server


// Monitoring Client
Route::get('/GrafikClient', [HomeController::class, 'GrafikClient'])->name('GrafikClient');
Route::get('/KomplainUpt', [HomeController::class, 'KomplainUpt'])->name('KomplainUpt');
Route::get('/KomplainPonpes', [HomeController::class, 'KomplainPonpes'])->name('KomplainPonpes');
Route::get('/PencatatanKartu', [HomeController::class, 'PencatatanKartu'])->name('PencatatanKartu');
// Monitoring Client


// Monitoring Tutorial
Route::get('/TutorialUpt', [TutorialController::class, 'TutorialUpt'])->name('TutorialUpt');
Route::get('/TutorialPonpes', [TutorialController::class, 'TutorialPonpes'])->name('TutorialPonpes');
Route::get('/TutorialServer', [TutorialController::class, 'TutorialServer'])->name('TutorialServer');
Route::get('/TutorialMicrotik', [TutorialController::class, 'TutorialMicrotik'])->name('TutorialMicrotik');
// Monitoring Tutorial


// User Page
Route::get('/UserPage', [UserController::class, 'UserPage'])->name('UserPage');
Route::post('/UserPageStore', [UserController::class, 'UserPageStore'])->name('UserPageStore');
Route::delete('/UserPageDestroy/{id}', [UserController::class, 'UserPageDestroy'])->name('UserPageDestroy');
Route::get('/UserPageEdit/{id}', [UserController::class, 'UserPageEdit'])->name('UserPageEdit');
Route::put('/UserPageUpdate/{id}', [UserController::class, 'UserPageUpdate'])->name('UserPageUpdate');
// User Page