<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
     protected $fillable = [
        'category_id',
        'nama_menu',
        'harga',
        'gambar',
        'is_available',
    ];
}
