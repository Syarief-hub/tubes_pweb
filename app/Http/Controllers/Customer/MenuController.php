<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        // Validasi parameter pencarian dan kategori
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        // Mengambil semua kategori beserta jumlah menunya
        $categories = Category::withCount('menus')
            ->orderBy('nama_kategori')
            ->get();

        // Mengambil data menu beserta relasi kategorinya
        $menus = Menu::with('category')
            // Filter berdasarkan kata kunci pencarian
            ->when($request->filled('search'), function ($query) use ($request) {
                $keyword = trim($request->search);

                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('nama_menu', 'like', "%{$keyword}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($keyword) {
                            $categoryQuery->where(
                                'nama_kategori',
                                'like',
                                "%{$keyword}%"
                            );
                        });
                });
            })

            // Filter berdasarkan kategori
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })

            ->orderBy('nama_menu')
            ->get();

        // Menghitung seluruh menu
        $totalMenu = Menu::count();

        return view('customer.menu', compact(
            'categories',
            'menus',
            'totalMenu'
        ));
    }
}
