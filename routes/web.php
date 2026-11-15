<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\OwnerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthenticatedSessionController::class, 'index'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('dashboard', [OwnerController::class, 'index'])->name('dashboard');
});
