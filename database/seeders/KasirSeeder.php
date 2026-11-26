<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Database\Seeder;

class KasirSeeder extends Seeder{
    public function run(): void{
        
        // 1. Hapus data lama (anak dulu, baru induk)
        DetailPesanan::query()->delete();
        Pesanan::query()->delete();
        Menu::query()->delete();
        Category::query()->delete();

        // 2. Kategori
        $minuman = Category::create(['nama_kategori' => 'Minuman']);
        $makanan = Category::create(['nama_kategori' => 'Makanan']);

        // 3. Menu
        $kopiSusu = Menu::create(['category_id' => $minuman->id, 'nama_menu' => 'Kopi Susu', 'harga' => 18000]);
        $americano = Menu::create(['category_id' => $minuman->id, 'nama_menu' => 'Americano', 'harga' => 15000]);
        Menu::create(['category_id' => $minuman->id, 'nama_menu' => 'Caramel Latte', 'harga' => 25000, 'is_available' => false]);
        $nasiGoreng = Menu::create(['category_id' => $makanan->id, 'nama_menu' => 'Nasi Goreng', 'harga' => 30000]);
        $croissant = Menu::create(['category_id' => $makanan->id, 'nama_menu' => 'Croissant', 'harga' => 22000]);

        // 4. Pesanan A-123: non-tunai, 18.000 + kode 519
        $budi = Pesanan::create([
            'nama_pelanggan' => 'Budi',
            'no_meja' => '5',
            'kode_unik' => 'A-123',
            'metode_bayar' => 'non-tunai',
            'bukti_transfer' => 'bukti_transfer/bukti-pembayaran-uji-a123.jpg',
            'total_tagihan' => 18519,
            'total_dibayar' => 18519,
            'status' => 'menunggu_validasi',
        ]);
        $budi->details()->create(['menu_id' => $kopiSusu->id, 'qty' => 1, 'harga_satuan' => 18000, 'subtotal' => 18000]);

        // Pesanan B-456: non-tunai, 45.000 + kode 467
        $sari = Pesanan::create([
            'nama_pelanggan' => 'Sari',
            'no_meja' => '7',
            'kode_unik' => 'B-456',
            'metode_bayar' => 'non-tunai',
            'bukti_transfer' => 'bukti_transfer/contoh.jpg',
            'total_tagihan' => 45467,
            'total_dibayar' => 45467,
            'status' => 'menunggu_validasi',
        ]);
        $sari->details()->create(['menu_id' => $americano->id, 'qty' => 1, 'harga_satuan' => 15000, 'subtotal' => 15000]);
        $sari->details()->create(['menu_id' => $nasiGoreng->id, 'qty' => 1, 'harga_satuan' => 30000, 'subtotal' => 30000]);

        // Pesanan C-789: non-tunai, 55.000 + kode 578
        $dimas = Pesanan::create([
            'nama_pelanggan' => 'Dimas',
            'no_meja' => '6',
            'kode_unik' => 'C-789',
            'metode_bayar' => 'non-tunai',
            'bukti_transfer' => 'bukti_transfer/contoh.jpg',
            'total_tagihan' => 55578,
            'total_dibayar' => 55578,
            'status' => 'menunggu_validasi',
        ]);
        $dimas->details()->create(['menu_id' => $kopiSusu->id, 'qty' => 1, 'harga_satuan' => 18000, 'subtotal' => 18000]);
        $dimas->details()->create(['menu_id' => $americano->id, 'qty' => 1, 'harga_satuan' => 15000, 'subtotal' => 15000]);
        $dimas->details()->create(['menu_id' => $croissant->id, 'qty' => 1, 'harga_satuan' => 22000, 'subtotal' => 22000]);

        // Pesanan D-321: tunai, belum bayar, tanpa kode & tanpa bukti
        $rina = Pesanan::create([
            'nama_pelanggan' => 'Rina',
            'no_meja' => '2',
            'kode_unik' => 'D-321',
            'metode_bayar' => 'tunai',
            'total_tagihan' => 30000,
            'status' => 'menunggu_pembayaran',
        ]);
        $rina->details()->create(['menu_id' => $nasiGoreng->id, 'qty' => 1, 'harga_satuan' => 30000, 'subtotal' => 30000]);
    }
}