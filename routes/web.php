<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ObatKeluarController;
use App\Http\Controllers\ObatKeluarDetailController;

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// Group untuk admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('obat-masuk', App\Http\Controllers\ObatMasukController::class);
    Route::resource('pasiens', App\Http\Controllers\PasienController::class);
    Route::get('/laporan/obat-keluar', [ObatKeluarDetailController::class, 'index'])->name('laporan.obat-keluar.index');
    Route::resource('obat', ObatController::class);
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::resource('obat-keluar', App\Http\Controllers\ObatKeluarController::class);
    Route::get('/obat-keluar/{id}/export-pdf', [ObatKeluarController::class, 'exportPdf'])->name('obat-keluar.export-pdf');
});

// Group untuk poli
Route::middleware(['auth', 'role:poli'])->group(function () {
    // Route::resource('obat-keluar', App\Http\Controllers\ObatKeluarController::class);
    // Route::get('/obat-keluar/{id}/export-pdf', [ObatKeluarController::class, 'exportPdf'])->name('obat-keluar.export-pdf');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
});
