<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi form bawaan Laravel agar input tidak boleh kosong
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        // 2. Simpan data ke tabel categories
        Category::create($validated);

        // 3. Arahkan kembali ke halaman tabel dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
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
        // Mencari data kategori berdasarkan ID (jika tidak ada, tampilkan error 404)
        $category = Category::findOrFail($id);

        // Menampilkan halaman form edit sambil mengirim data lama
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       // 1. Validasi input agar tidak kosong
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        // 2. Cari data kategori lalu perbarui isinya di database
        $category = Category::findOrFail($id);
        $category->update($validated);

        // 3. Kembali ke halaman tabel dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari data kategori lalu hapus dari database
        $category = Category::findOrFail($id);
        $category->delete();

        // Kembali ke halaman tabel dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil dihapus!');
    }
}
