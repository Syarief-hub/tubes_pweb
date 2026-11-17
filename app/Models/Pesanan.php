<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    protected $fillable = [
        'nama_pelanggan', 'no_meja', 'kode_unik', 'metode_bayar', 'bukti_transfer',
        'total_tagihan', 'total_dibayar', 'status',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
