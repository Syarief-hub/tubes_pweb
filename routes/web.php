<?php

use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Halaman antrean kasir
Route::get('/kasir', [KasirController::class, 'index']);

// Validasi bukti transfer
Route::patch('/kasir/{pesanan}/konfirmasi', [KasirController::class, 'konfirmasi']);
Route::patch('/kasir/{pesanan}/tolak', [KasirController::class, 'tolak']);

// Edit pesanan
Route::get('/kasir/{pesanan}/edit', [KasirController::class, 'edit']);
Route::delete('/kasir/{pesanan}/item/{detail}', [KasirController::class, 'hapusItem']);
Route::post('/kasir/{pesanan}/item', [KasirController::class, 'tambahItem']);

// AJAX perkiraan selisih
Route::get('/kasir/{pesanan}/hitung', [KasirController::class, 'hitung']);

// Bayar tunai
Route::patch('/kasir/{pesanan}/bayar-tunai', [KasirController::class, 'bayarTunai']);
