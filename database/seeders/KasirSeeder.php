<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Pesanan;
use Illuminate\Database\Seeder;

// Data contoh untuk uji modul kasir: php artisan db:seed --class=KasirSeeder
class KasirSeeder extends Seeder
{
    public function run(): void
    {
        $minuman = Category::create(['nama_kategori' => 'Minuman']);
        $makanan = Category::create(['nama_kategori' => 'Makanan Utama']);

        $menus = collect([
            $minuman->menus()->create(['nama_menu' => 'Kopi Susu', 'harga' => 18000]),
            $minuman->menus()->create(['nama_menu' => 'Americano', 'harga' => 15000]),
            $minuman->menus()->create(['nama_menu' => 'Caramel Latte', 'harga' => 25000, 'is_available' => false]),
            $makanan->menus()->create(['nama_menu' => 'Nasi Goreng', 'harga' => 30000]),
            $makanan->menus()->create(['nama_menu' => 'Croissant', 'harga' => 22000]),
        ]);

        foreach (['Budi' => 'A-123', 'Sari' => 'B-456', 'Dimas' => 'C-789'] as $nama => $kode) {
            $items = $menus->random(2);
            $total = $items->sum('harga') + random_int(100, 999); // + 3 digit kode transfer

            $pesanan = Pesanan::create([
                'nama_pelanggan' => $nama,
                'no_meja' => (string) random_int(1, 10),
                'kode_unik' => $kode,
                'metode_bayar' => 'non-tunai',
                'bukti_transfer' => 'bukti_transfer/contoh.jpg',
                'total_tagihan' => $total,
                'total_dibayar' => $total,
                'status' => 'menunggu_validasi',
            ]);

            foreach ($items as $menu) {
                $pesanan->details()->create([
                    'menu_id' => $menu->id, 'qty' => 1,
                    'harga_satuan' => $menu->harga, 'subtotal' => $menu->harga,
                ]);
            }
        }
    }
}
