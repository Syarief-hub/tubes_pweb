<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with('details.menu')
            ->where('status', 'menunggu_validasi')
            ->oldest()
            ->get();

        return view('kasir.index', compact('pesanans'));
    }

    public function konfirmasi(Pesanan $pesanan)
    {
        if ($pesanan->status !== 'menunggu_validasi') {
            return back()->with('error', 'Pesanan tidak bisa dikonfirmasi.');
        }

        $pesanan->update(['status' => 'diproses']);

        return back()->with('success', "Pesanan {$pesanan->kode_unik} dikonfirmasi.");
    }

    public function tolak(Pesanan $pesanan)
    {
        if ($pesanan->status !== 'menunggu_validasi') {
            return back()->with('error', 'Pesanan tidak bisa ditolak.');
        }

        $pesanan->update(['status' => 'menunggu_pembayaran']);

        return back()->with('success', "Bukti transfer {$pesanan->kode_unik} ditolak. Pelanggan diminta upload ulang.");
    }

    public function edit(Pesanan $pesanan)
    {
        $pesanan->load('details.menu');
        $menus = Menu::where('is_available', true)->orderBy('nama_menu')->get();

        return view('kasir.edit', compact('pesanan', 'menus'));
    }

    public function hapusItem(Pesanan $pesanan, DetailPesanan $detail)
    {
        // Pastikan item milik pesanan ini
        if ($detail->pesanan_id !== $pesanan->id) {
            abort(404);
        }

        $detail->delete();
        $this->hitungUlangTotal($pesanan);

        return back()->with('success', "Item dihapus dari pesanan {$pesanan->kode_unik}.");
    }

    public function tambahItem(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'qty' => 'required|integer|min:1',
        ]);

        $menu = Menu::findOrFail($request->menu_id);

        $pesanan->details()->create([
            'menu_id' => $menu->id,
            'qty' => $request->qty,
            'harga_satuan' => $menu->harga,
            'subtotal' => $menu->harga * $request->qty,
        ]);

        $this->hitungUlangTotal($pesanan);

        return back()->with('success', "{$menu->nama_menu} ditambahkan ke pesanan {$pesanan->kode_unik}.");
    }

    /**
     * Hitung ulang total_tagihan dari semua detail_pesanans.
     * 3 digit kode transfer tetap dipertahankan dari total_tagihan lama.
     */
    private function hitungUlangTotal(Pesanan $pesanan): void
    {
        $subtotalBaru = $pesanan->details()->sum('subtotal');

        // Pertahankan 3 digit kode unik transfer dari tagihan awal
        $kodeTransfer = $pesanan->total_tagihan % 1000;
        $pesanan->update(['total_tagihan' => $subtotalBaru + $kodeTransfer]);
    }

    /**
     * API endpoint: hitung selisih bayar secara real-time (AJAX).
     * Mengembalikan JSON berdasarkan data aktual di database.
     */
    public function hitung(Pesanan $pesanan)
    {
        $totalItem = $pesanan->details()->sum('subtotal');
        $kodeTransfer = $pesanan->total_tagihan % 1000;
        $totalTagihan = $totalItem + $kodeTransfer;
        $dibayar = $pesanan->total_dibayar ?? 0;
        $selisih = $dibayar - $totalTagihan;

        return response()->json([
            'total_item' => $totalItem,
            'kode_transfer' => $kodeTransfer,
            'total_tagihan' => $totalTagihan,
            'dibayar' => $dibayar,
            'selisih' => $selisih,
        ]);
    }
}
