<?php

use App\Http\Controllers\Customer\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('customer.menu');
});

Route::get('/menu', [MenuController::class, 'index'])->name('customer.menu');