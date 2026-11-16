<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'nama_kategori',
    ];

    // Relasi: Kategori memiliki banyak menu
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
