<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data menu beserta nama kategorinya
        $menus = Menu::with('category')->latest()->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function toggleStock(Menu $menu)
    {
        // Membalik nilai boolean: true menjadi false, false menjadi true
        $menu->update([
            'is_available' => !$menu->is_available,
        ]);

        $status = $menu->is_available ? 'Tersedia' : 'Habis';

        return redirect()->route('menus.index')
                         ->with('success', "Status stok {$menu->nama_menu} diubah menjadi {$status}.");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengambil semua data kategori untuk ditampilkan di pilihan dropdown form
        $categories = Category::all();

        return view('admin.menus.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'nama_menu'    => 'required|string|max:255',
            'harga'        => 'required|integer|min:0',
            'gambar'       => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'required|boolean',
        ], [
            'category_id.required' => 'Kategori wajib dipilih.',
            'nama_menu.required'   => 'Nama menu wajib diisi.',
            'harga.required'       => 'Harga menu wajib diisi.',
            'harga.integer'        => 'Harga harus berupa angka.',
            'gambar.required'      => 'Foto menu wajib diunggah.',
            'gambar.image'         => 'File harus berupa gambar (JPG, PNG, atau WEBP).',
            'gambar.max'           => 'Ukuran gambar maksimal 2MB.',
        ]);

        // 2. Simpan file gambar ke folder storage/app/public/menus
        $gambarPath = $request->file('gambar')->store('menus', 'public');

        // 3. Simpan seluruh data menu ke database
        Menu::create([
            'category_id'  => $validated['category_id'],
            'nama_menu'    => $validated['nama_menu'],
            'harga'        => $validated['harga'],
            'gambar'       => $gambarPath,
            'is_available' => $validated['is_available'],
        ]);

        // 4. Kembali ke halaman tabel menu dengan pesan sukses
        return redirect()->route('menus.index')
                         ->with('success', 'Menu baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menu = Menu::findOrFail($id);
        $categories = Category::all();

        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);

        // 1. Validasi input (gambar dibuat nullable agar tidak wajib upload ulang jika hanya ubah nama/harga)
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'nama_menu'    => 'required|string|max:255',
            'harga'        => 'required|integer|min:0',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'required|boolean',
        ], [
            'category_id.required' => 'Kategori wajib dipilih.',
            'nama_menu.required'   => 'Nama menu wajib diisi.',
            'harga.required'       => 'Harga menu wajib diisi.',
            'harga.integer'        => 'Harga harus berupa angka.',
            'gambar.image'         => 'File harus berupa gambar (JPG, PNG, atau WEBP).',
            'gambar.max'           => 'Ukuran gambar maksimal 2MB.',
        ]);

        // 2. Cek apakah admin mengunggah foto baru
        if ($request->hasFile('gambar')) {
            // Hapus foto lama dari folder storage agar tidak menumpuk
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            // Simpan foto baru
            $validated['gambar'] = $request->file('gambar')->store('menus', 'public');
        }

        // 3. Perbarui data menu di database
        $menu->update($validated);

        return redirect()->route('menus.index')
                         ->with('success', 'Data menu berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);

        // Hapus file foto dari storage sebelum data menu dihapus dari database
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();

        return redirect()->route('menus.index')
                         ->with('success', 'Menu berhasil dihapus!');
    }
}
