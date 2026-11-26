<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
     protected $fillable = [
        'pesanan_id',
        'menu_id',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    // Satu item milik satu menu
    public function menu(){
        return $this->belongsTo(Menu::class);
    }
}
