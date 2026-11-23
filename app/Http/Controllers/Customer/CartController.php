<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Mengambil data keranjang dari Session
        $cart = session()->get('cart', []);

        // Mengambil ID menu yang ada di keranjang
        $menuIds = array_keys($cart);

        // Mengambil data menu dari database
        $cartItems = Menu::with('category')
            ->whereIn('id', $menuIds)
            ->get();

        // Menghitung subtotal setiap menu
        $cartItems = $cartItems->map(function ($menu) use ($cart) {
            $menu->qty = (int) ($cart[$menu->id]['qty'] ?? 0);
            $menu->subtotal = $menu->harga * $menu->qty;
            return $menu;
        });

        // Menghitung total seluruh item
        $totalItems = $cartItems->sum('qty');

        // Menghitung total harga
        $totalPrice = $cartItems->sum('subtotal');

        //Pajak Restauran (10% dari total harga)
        $pajak = $totalPrice * 0.1;

        // Menghitung total bayar (total harga + pajak)
        $totalBayar = $totalPrice + $pajak;

        // Mengambil catatan dapur dari session
        $cartNote = session()->get('cart_note', '');

        return view('customer.keranjang', compact(
            'cartItems',
            'totalItems',
            'totalPrice',
            'pajak',
            'totalBayar',
            'cartNote'
        ));
    }


    public function add(Request $request)
    {
        //Validasi ID menu
        $request->validate([
            'menu_id' => 'required|integer|exists:menus,id',
        ]);

        //Ambil id menu dari database
        $menu = Menu::findOrFail($request->menu_id);

        //Periksa ketersediaan menu
        if (!$menu->is_available) {
            return back()->with('eror', 'Maaf menu sedang tidak tersedia');
        }

        //Ambil data keranjang dari session
        $cart = session()->get('cart', []);

        //ambil ID menu
        $menuId = $menu->id;

        //jika menu sudah ada, tambah kuantitas
        if (isset($cart[$menuId])) {
            $cart[$menuId]['qty']++;
        } else {
            //jika menu belum ada, tambahkan sebagai item baru
            $cart[$menuId] = [
                'qty' => 1,
            ];
        }

        //Simpan kembali ke session
        session()->put('cart', $cart);

        return back()->with('success', 'Menu berhasil ditambahkan ke keranjang');
    }

    public function saveNote(Request $request)
    {
        //Validasi catatan dapur
        $request->validate([
            'catatan_dapur' => 'nullable|string|max:500',
        ]);

        //Simpan catatan dapur ke session
        session()->put(
        'cart_note',
        trim((string) $request->input('catatan_dapur', ''))
    );

        return back()->with('success', 'Catatan dapur berhasil disimpan');
    }
    

    public function update(Request $request, $menuId)
    {
        //Validasi jumlah menu
        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        //Ambil data keranjang dari session
        $cart = session()->get('cart', []);

        //Periksa apakah menu ada di keranjang
        if (!isset($cart[$menuId])) {
            return back()->with('error', 'Menu tidak ditemukan di keranjang');
        }

        //Ambil menu dari Database
        $menu = Menu::findOrFail($menuId);

        //Ambil kuantitas baru dan kuantitas saat ini
        $newQty = (int) $request->qty;
        $currentQty = $cart[$menuId]['qty'];

        //Periksa ketersediaan menu
        if (!$menu->is_available && $newQty > $currentQty) {
            return back()->with('error', 'Menu sedang tidak tersedia.');
        }

        // Memperbarui jumlah di session 
        $cart[$menuId]['qty'] = $newQty;

        // Simpan Session
        session()->put('cart', $cart);

        return back()->with('success', 'Jumlah menu berhasil diperbarui');
    }


    public function remove($menuId)
    {
        //Ambil data keranjang dari session
        $cart = session()->get('cart', []);

        //Periksa apakah menu ada di keranjang
        if (!isset($cart[$menuId])) {
            return back()->with('error', 'Menu tidak ditemukan di keranjang');
        }

        //Hapus menu dari keranjang
        unset($cart[$menuId]);

        //Simpan Session
        session()->put('cart', $cart);

        return back()->with('success', 'Menu berhasil dihapus dari keranjang');
    }

    public function clear()
    {
        //Hapus semua item dari keranjang
        session()->forget('cart');

        return back()->with('success', 'Keranjang berhasil dikosongkan');
    }
}
