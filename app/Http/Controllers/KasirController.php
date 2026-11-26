<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pesanan; 
use Illuminate\Http\Request;

class KasirController extends Controller
{
      // Halaman antrean: GET /kasir
    public function index(){

        // Antrean 1: transfer yang menunggu dicek buktinya
        $pesanans = Pesanan::where('status', 'menunggu_validasi')->get();

        // Antrean 2: tunai yang belum bayar
        $tunais = Pesanan::where('metode_bayar', 'tunai')
            ->where('status', 'menunggu_pembayaran')
            ->get();

        return view('kasir.index', [
            'pesanans' => $pesanans,
            'tunais' => $tunais,
        ]);
    }

    // Bukti transfer benar: PATCH /kasir/{pesanan}/konfirmasi
    public function konfirmasi(Pesanan $pesanan){
        // Pengaman: hanya pesanan yang menunggu validasi
        if ($pesanan->status != 'menunggu_validasi') {
            return redirect('/kasir')->with('pesan', 'Pesanan tidak bisa dikonfirmasi.');
        }

        $pesanan->update(['status' => 'diproses']);

        return redirect('/kasir')->with('pesan', 'Pesanan ' . $pesanan->kode_unik . ' dikonfirmasi.');
    }

     // Bukti transfer salah: PATCH /kasir/{pesanan}/tolak
    public function tolak(Pesanan $pesanan){
        if ($pesanan->status != 'menunggu_validasi') {
            return redirect('/kasir')->with('pesan', 'Pesanan tidak bisa ditolak.');
        }

        // Pelanggan harus upload bukti ulang
        $pesanan->update(['status' => 'menunggu_pembayaran']);

        return redirect('/kasir')->with('pesan', 'Pesanan ' . $pesanan->kode_unik . ' ditolak.');
    }

    // Halaman edit: GET /kasir/{pesanan}/edit
    public function edit(Pesanan $pesanan){
        // Menu yang boleh ditambahkan: hanya yang tersedia
        $menus = Menu::where('is_available', true)->get();

        // Hitung ringkasan
        $totalItem = $pesanan->details->sum('subtotal');
        $kodeTransfer = $this->kodeTransfer($pesanan);
        $totalBaru = $totalItem + $kodeTransfer;
        $dibayar = $pesanan->total_dibayar; // null = belum bayar (tunai)

        $selisih = null;
        if ($dibayar !== null) {
            $selisih = $dibayar - $totalBaru;
        }

        return view('kasir.edit', [
            'pesanan' => $pesanan,
            'menus' => $menus,
            'totalItem' => $totalItem,
            'kodeTransfer' => $kodeTransfer,
            'totalBaru' => $totalBaru,
            'dibayar' => $dibayar,
            'selisih' => $selisih,
        ]);
    }

     // Hapus item: DELETE /kasir/{pesanan}/item/{detail}
    public function hapusItem(Pesanan $pesanan, DetailPesanan $detail){
        // Pengaman: item harus milik pesanan ini
        if ($detail->pesanan_id != $pesanan->id) {
            abort(404);
        }

        $detail->delete();
        $this->hitungUlang($pesanan);

        return back()->with('pesan', 'Item dihapus.');
    }

    // Tambah item: POST /kasir/{pesanan}/item
    public function tambahItem(Request $request, Pesanan $pesanan){
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'qty' => 'required|integer|min:1',
        ]);

        $menu = Menu::find($request->menu_id);

        if (! $menu->is_available) {
            return back()->with('pesan', 'Menu sedang tidak tersedia.');
        }

        // Harga diambil dari tabel menus, bukan dari form
        $pesanan->details()->create([
            'menu_id' => $menu->id,
            'qty' => $request->qty,
            'harga_satuan' => $menu->harga,
            'subtotal' => $menu->harga * $request->qty,
        ]);

        $this->hitungUlang($pesanan);

        return back()->with('pesan', $menu->nama_menu . ' ditambahkan.');
    }

    // Perkiraan selisih untuk AJAX: GET /kasir/{pesanan}/hitung?menu_id=..&qty=..
    public function hitung(Request $request, Pesanan $pesanan){
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'qty' => 'required|integer|min:1',
        ]);

        $menu = Menu::find($request->menu_id);

        // Total sekarang + item yang akan ditambahkan (belum disimpan)
        $totalItem = $pesanan->details->sum('subtotal') + ($menu->harga * $request->qty);
        $totalBaru = $totalItem + $this->kodeTransfer($pesanan);

        $selisih = null;
        if ($pesanan->total_dibayar !== null) {
            $selisih = $pesanan->total_dibayar - $totalBaru;
        }

        return response()->json([
            'total_tagihan' => $totalBaru,
            'selisih' => $selisih,
        ]);
    }

     // Terima uang tunai: PATCH /kasir/{pesanan}/bayar-tunai
    public function bayarTunai(Request $request, Pesanan $pesanan){
        // Pengaman: hanya pesanan tunai yang belum bayar
        if ($pesanan->metode_bayar != 'tunai' || $pesanan->status != 'menunggu_pembayaran') {
            return redirect('/kasir')->with('pesan', 'Pesanan ini tidak bisa dibayar tunai.');
        }

        // Uang minimal sebesar total tagihan
        $request->validate([
            'uang_diterima' => 'required|integer|min:' . $pesanan->total_tagihan,
        ], [
            'uang_diterima.min' => 'Uang diterima kurang dari total tagihan.',
        ]);

        $kembalian = $request->uang_diterima - $pesanan->total_tagihan;

        // Lunas: dibayar = tagihan, lanjut ke dapur
        $pesanan->update([
            'total_dibayar' => $pesanan->total_tagihan,
            'status' => 'diproses',
        ]);

        return redirect('/kasir')->with('pesan',
            'Pesanan ' . $pesanan->kode_unik . ' lunas. Kembalian: Rp ' . number_format($kembalian, 0, ',', '.'));
    }

     // 3 digit kode transfer: hanya non-tunai. Tunai = 0.
     private function kodeTransfer(Pesanan $pesanan){
        if ($pesanan->metode_bayar == 'non-tunai') {
            return $pesanan->total_tagihan % 1000;
        }

        return 0;
    }

    // Total tagihan = jumlah subtotal semua item + kode transfer
    private function hitungUlang(Pesanan $pesanan){
        $kodeTransfer = $this->kodeTransfer($pesanan);
        $totalItem = $pesanan->details()->sum('subtotal');

        $pesanan->update(['total_tagihan' => $totalItem + $kodeTransfer]);
    }
}
