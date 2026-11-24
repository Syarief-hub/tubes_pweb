<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    protected $casts = ['is_available' => 'boolean'];
    
    protected $table = 'menus';

    protected $fillable = [
        'category_id',
        'nama_menu',
        'harga',
        'gambar',
        'is_available',
    ];
  
    // Relasi: 1 Menu dimiliki oleh satu kategori
    // Relasi: Menu dimiliki oleh satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
