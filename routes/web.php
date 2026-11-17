<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\OwnerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('customer.menu');
});

Route::get('/menu', [MenuController::class, 'index'])->name('customer.menu');

Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthenticatedSessionController::class, 'index'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('dashboard', [OwnerController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('menus', AdminMenuController::class);
    Route::patch('menus/{menu}/toggle-stock', [AdminMenuController::class, 'toggleStock'])
        ->name('menus.toggleStock');
});
