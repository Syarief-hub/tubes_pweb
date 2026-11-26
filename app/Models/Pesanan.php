<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    // Kolom yang boleh diisi
    protected $fillable = [
        'nama_pelanggan',
        'no_meja',
        'kode_unik',
        'metode_bayar',
        'bukti_transfer',
        'total_tagihan',
        'total_dibayar',
        'status',
    ];

     // Satu pesanan punya banyak item
    public function details(){
        return $this->hasMany(DetailPesanan::class);
    }
}
