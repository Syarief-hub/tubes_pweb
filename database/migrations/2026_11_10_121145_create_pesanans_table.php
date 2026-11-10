<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('no_meja');
            $table->string('kode_unik')->unique();
            $table->enum('metode_bayar', ['tunai', 'non-tunai']);
            $table->string('bukti_transfer')->nullable();
            $table->integer('total_tagihan');
            $table->integer('total_dibayar')->nullable();
            $table->enum('status', ['menunggu_pembayaran', 'menunggu_validasi', 'diproses', 'selesai', 'batal'])->default('menunggu_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
