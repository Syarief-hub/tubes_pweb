<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Menu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f2ede7] text-[#552919] antialiased">

    <!-- Navigasi Atas Admin -->
    <nav class="bg-[#552919] text-[#f2ede7] shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-3.5 flex justify-between items-center">
            <span class="font-bold tracking-wide text-lg">Admin Restoran</span>
            <div class="space-x-3 text-sm font-medium">
                <a href="{{ route('categories.index') }}" 
                   class="px-3 py-1.5 rounded-md hover:bg-[#ab7843]/30 transition">
                    Kelola Kategori
                </a>
                <a href="{{ route('menus.index') }}" 
                   class="px-3 py-1.5 rounded-md bg-[#64120c] text-[#f2ede7]">
                    Kelola Menu
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto py-10 px-4">
        <!-- Header & Tombol Tambah -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#552919]">Kelola Daftar Menu</h1>
                <p class="text-sm text-[#552919]/75">Atur katalog makanan, minuman, foto produk, dan ketersediaan stok</p>
            </div>
            <a href="{{ route('menus.create') }}" 
               class="bg-[#64120c] hover:bg-[#552919] text-[#f2ede7] font-medium px-4 py-2 rounded-lg shadow-sm transition">
                + Tambah Menu
            </a>
        </div>

        <!-- Notifikasi Sukses -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-white border-l-4 border-[#ab7843] text-[#552919] rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabel Menu -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-[#ab7843]/30">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#552919] text-[#f2ede7] text-xs uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 w-12">No</th>
                        <th class="py-3.5 px-4 w-24">Gambar</th>
                        <th class="py-3.5 px-4">Nama Menu</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Harga</th>
                        <th class="py-3.5 px-4 text-center">Status Stok</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#ab7843]/20 text-sm">
                    @forelse ($menus as $index => $menu)
                        <tr class="hover:bg-[#f2ede7]/60 transition">
                            <td class="py-3.5 px-4 font-medium text-[#552919]/70">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4">
                                @if ($menu->gambar)
                                    <img src="{{ asset('storage/' . $menu->gambar) }}" 
                                         alt="{{ $menu->nama_menu }}" 
                                         class="w-16 h-16 object-cover rounded-lg border border-[#ab7843]/30">
                                @else
                                    <div class="w-16 h-16 bg-[#f2ede7] rounded-lg flex items-center justify-center text-xs text-[#552919]/50">
                                        No Foto
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#552919]">{{ $menu->nama_menu }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-[#f2ede7] text-[#552919] border border-[#ab7843]/40">
                                    {{ $menu->category->nama_kategori ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-[#552919]">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <!-- Tombol Toggle Stok (Klik untuk ubah Tersedia / Habis) -->
                                <form action="{{ route('menus.toggleStock', $menu->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            title="Klik untuk mengubah status stok"
                                            class="px-3 py-1 rounded-full text-xs font-semibold transition cursor-pointer {{ $menu->is_available ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-[#64120c]/15 text-[#64120c] hover:bg-[#64120c]/25' }}">
                                        {{ $menu->is_available ? '● Tersedia' : '● Habis' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('menus.edit', $menu->id) }}" 
                                   class="inline-block bg-[#ab7843] hover:bg-[#552919] text-white px-3 py-1.5 rounded-md text-xs font-medium transition">
                                    Edit
                                </a>
                                <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="border border-[#64120c] text-[#64120c] hover:bg-[#64120c] hover:text-[#f2ede7] px-3 py-1.5 rounded-md text-xs font-medium transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 px-6 text-center text-[#552919]/70">
                                Belum ada data menu. Klik <strong>+ Tambah Menu</strong> untuk menambahkan produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>