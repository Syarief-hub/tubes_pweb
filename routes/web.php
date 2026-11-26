<?php

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\OwnerController;
use Illuminate\Support\Facades\Route;

// Customer start
Route::get('/', function () {
    return redirect()->route('customer.menu', ['no_meja' => session('no_meja', 1)]);
});

Route::get('/menu/{no_meja}', [MenuController::class, 'index'])->name('customer.menu');
Route::get('/keranjang', [CartController::class, 'index'])->name('customer.cart');
Route::post('/keranjang', [CartController::class, 'add'])->name('customer.cart.add'); // Tambah menu
Route::post('/keranjang/catatan', [CartController::class, 'saveNote'])->name('customer.cart.note'); // Simpan catatan dapur
Route::delete('/keranjang/kosongkan', [CartController::class, 'clear'])->name('customer.cart.clear'); //hapus semua menu    
Route::patch('/keranjang/{menuId}', [CartController::class, 'update'])->name('customer.cart.update'); //ubah jumlah menu
Route::delete('/keranjang/{menuId}', [CartController::class, 'remove'])->name('customer.cart.remove'); //hapus 1 menu
Route::get('/menu', [MenuController::class, 'index'])->name('customer.menu');
// Customer End

// Authtentication Start 
Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthenticatedSessionController::class, 'index'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
// Authtentication End

// Owner Start 
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('dashboard', [OwnerController::class, 'index'])->name('dashboard');
});
// Owner End

// Admin Start
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('menus', AdminMenuController::class);
    Route::patch('menus/{menu}/toggle-stock', [AdminMenuController::class, 'toggleStock'])->name('menus.toggleStock');
});
// Admin End

// Kasir Start
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->controller(KasirController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::patch('/{pesanan}/konfirmasi', 'konfirmasi')->name('konfirmasi');
    Route::patch('/{pesanan}/tolak', 'tolak')->name('tolak');
    Route::get('/{pesanan}/edit', 'edit')->name('edit');
    Route::delete('/{pesanan}/item/{detail}', 'hapusItem')->name('hapus-item');
    Route::post('/{pesanan}/item', 'tambahItem')->name('tambah-item');
    Route::get('/{pesanan}/hitung', 'hitung')->name('hitung');
});
// Kasir End