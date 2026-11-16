<?php

use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Modul Kasir (Anggota 2). Middleware role ditambah Anggota 4.
Route::prefix('kasir')->name('kasir.')->controller(KasirController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::patch('/{pesanan}/konfirmasi', 'konfirmasi')->name('konfirmasi');
    Route::patch('/{pesanan}/tolak', 'tolak')->name('tolak');
    Route::get('/{pesanan}/edit', 'edit')->name('edit');
    Route::delete('/{pesanan}/item/{detail}', 'hapusItem')->name('hapus-item');
    Route::post('/{pesanan}/item', 'tambahItem')->name('tambah-item');
    Route::get('/{pesanan}/hitung', 'hitung')->name('hitung');
});
