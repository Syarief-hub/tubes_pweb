<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirTest extends TestCase
{
    use RefreshDatabase;

    private function buatPesanan(): Pesanan
    {
        $kategori = Category::create(['nama_kategori' => 'Minuman']);
        $menu = $kategori->menus()->create(['nama_menu' => 'Kopi Susu', 'harga' => 18000]);

        $pesanan = Pesanan::create([
            'nama_pelanggan' => 'Budi',
            'no_meja' => '3',
            'kode_unik' => 'T-001',
            'metode_bayar' => 'non-tunai',
            'bukti_transfer' => 'bukti_transfer/contoh.jpg',
            'total_tagihan' => 18456, // 18000 + 456 kode transfer
            'total_dibayar' => 18456,
            'status' => 'menunggu_validasi',
        ]);

        $pesanan->details()->create([
            'menu_id' => $menu->id,
            'qty' => 1,
            'harga_satuan' => 18000,
            'subtotal' => 18000,
        ]);

        return $pesanan;
    }

    public function test_index_menampilkan_pesanan_menunggu_validasi(): void
    {
        $pesanan = $this->buatPesanan();

        $this->get('/kasir')
            ->assertOk()
            ->assertSee('T-001')
            ->assertSee('Budi');
    }

    public function test_index_tidak_menampilkan_pesanan_diproses(): void
    {
        $pesanan = $this->buatPesanan();
        $pesanan->update(['status' => 'diproses']);

        $this->get('/kasir')
            ->assertOk()
            ->assertDontSee('T-001');
    }

    public function test_konfirmasi_ubah_status_ke_diproses(): void
    {
        $pesanan = $this->buatPesanan();

        $this->patch("/kasir/{$pesanan->id}/konfirmasi")
            ->assertRedirect();

        $this->assertDatabaseHas('pesanans', ['id' => $pesanan->id, 'status' => 'diproses']);
    }

    public function test_tolak_ubah_status_ke_menunggu_pembayaran(): void
    {
        $pesanan = $this->buatPesanan();

        $this->patch("/kasir/{$pesanan->id}/tolak")
            ->assertRedirect();

        $this->assertDatabaseHas('pesanans', ['id' => $pesanan->id, 'status' => 'menunggu_pembayaran']);
    }

    public function test_konfirmasi_gagal_jika_status_bukan_menunggu_validasi(): void
    {
        $pesanan = $this->buatPesanan();
        $pesanan->update(['status' => 'diproses']);

        $this->patch("/kasir/{$pesanan->id}/konfirmasi")
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('pesanans', ['id' => $pesanan->id, 'status' => 'diproses']);
    }

    public function test_hapus_item_kurangi_total(): void
    {
        $pesanan = $this->buatPesanan();
        $detail = $pesanan->details->first();
        $kodeTransfer = $pesanan->total_tagihan % 1000; // 456

        $this->delete("/kasir/{$pesanan->id}/item/{$detail->id}")
            ->assertRedirect();

        // Total = 0 + kode transfer
        $this->assertDatabaseHas('pesanans', ['id' => $pesanan->id, 'total_tagihan' => $kodeTransfer]);
        $this->assertDatabaseMissing('detail_pesanans', ['id' => $detail->id]);
    }

    public function test_tambah_item_naikkan_total(): void
    {
        $pesanan = $this->buatPesanan();
        $menu2 = Category::first()->menus()->create(['nama_menu' => 'Americano', 'harga' => 15000]);

        $this->post("/kasir/{$pesanan->id}/item", ['menu_id' => $menu2->id, 'qty' => 2])
            ->assertRedirect();

        $kodeTransfer = 456;
        // 18000 (existing) + 30000 (15000*2) + 456
        $this->assertDatabaseHas('pesanans', ['id' => $pesanan->id, 'total_tagihan' => 48456]);
        $this->assertDatabaseHas('detail_pesanans', [
            'pesanan_id' => $pesanan->id,
            'menu_id' => $menu2->id,
            'qty' => 2,
            'subtotal' => 30000,
        ]);
    }

    public function test_hitung_endpoint_json(): void
    {
        $pesanan = $this->buatPesanan();

        $this->getJson("/kasir/{$pesanan->id}/hitung")
            ->assertOk()
            ->assertJsonStructure(['total_item', 'kode_transfer', 'total_tagihan', 'dibayar', 'selisih'])
            ->assertJson([
                'kode_transfer' => 456,
                'dibayar' => 18456,
                'selisih' => 0,
            ]);
    }
}
