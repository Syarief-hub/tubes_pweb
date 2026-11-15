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

    // Memastikan nilai is_available dibaca sebagai boolean (true/false)
    protected $casts = [
        'is_available' => 'boolean',
    ];

    // Relasi: Setiap menu dimiliki oleh satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
