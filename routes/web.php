<?php

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('customer.menu', ['no_meja' => session('no_meja', 1)]);
});

Route::get('/menu/{no_meja}', [MenuController::class, 'index'])
    ->name('customer.menu');

Route::get('/keranjang', [CartController::class, 'index'])
    ->name('customer.cart');

Route::post('/keranjang', [CartController::class, 'add'])
    ->name('customer.cart.add'); // Tambah menu

Route::post('/keranjang/catatan', [CartController::class, 'saveNote'])
    ->name('customer.cart.note'); // Simpan catatan dapur

Route::delete('/keranjang/kosongkan', [CartController::class, 'clear'])
    ->name('customer.cart.clear'); //hapus semua menu
    
Route::patch('/keranjang/{menuId}', [CartController::class, 'update'])
    ->name('customer.cart.update'); //ubah jumlah menu

Route::delete('/keranjang/{menuId}', [CartController::class, 'remove'])
    ->name('customer.cart.remove'); //hapus 1 menu
