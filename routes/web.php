<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
   
});

Route::resource('admin/categories', CategoryController::class);
Route::resource('admin/menus', MenuController::class);
Route::patch('admin/menus/{menu}/toggle-stock', [MenuController::class, 'toggleStock'])->name('menus.toggleStock');