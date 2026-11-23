<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tambah Menu</title>
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

    <div class="max-w-2xl mx-auto py-10 px-4">
        <!-- Judul Halaman -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#552919]">Tambah Menu Baru</h1>
            <p class="text-sm text-[#552919]/75">Masukkan detail produk makanan atau minuman beserta fotonya</p>
        </div>

        <!-- Kartu Form -->
        <div class="bg-white rounded-xl shadow-sm border border-[#ab7843]/30 overflow-hidden">
            <div class="bg-[#552919] px-6 py-3.5">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-[#f2ede7]">Form Data Menu</h2>
            </div>

            <!-- Wajib menggunakan enctype="multipart/form-data" untuk upload file gambar -->
            <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                <!-- Pilihan Kategori -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-[#552919] mb-2">
                        Kategori Menu
                    </label>
                    <select name="category_id" 
                            id="category_id" 
                            class="w-full px-4 py-2.5 rounded-lg border border-[#ab7843]/50 bg-white text-[#552919] focus:outline-none focus:border-[#64120c] focus:ring-1 focus:ring-[#64120c]">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1.5 text-xs font-medium text-[#64120c]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Menu -->
                <div>
                    <label for="nama_menu" class="block text-sm font-semibold text-[#552919] mb-2">
                        Nama Menu
                    </label>
                    <input type="text" 
                           name="nama_menu" 
                           id="nama_menu" 
                           value="{{ old('nama_menu') }}"
                           placeholder="Contoh: Nasi Goreng Spesial, Es Kopi Susu"
                           class="w-full px-4 py-2.5 rounded-lg border border-[#ab7843]/50 text-[#552919] placeholder-[#552919]/40 focus:outline-none focus:border-[#64120c] focus:ring-1 focus:ring-[#64120c]">
                    @error('nama_menu')
                        <p class="mt-1.5 text-xs font-medium text-[#64120c]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Satuan -->
                <div>
                    <label for="harga" class="block text-sm font-semibold text-[#552919] mb-2">
                        Harga (Rp)
                    </label>
                    <input type="number" 
                           name="harga" 
                           id="harga" 
                           value="{{ old('harga') }}"
                           placeholder="Contoh: 25000 (tulis angka saja tanpa titik)"
                           class="w-full px-4 py-2.5 rounded-lg border border-[#ab7843]/50 text-[#552919] placeholder-[#552919]/40 focus:outline-none focus:border-[#64120c] focus:ring-1 focus:ring-[#64120c]">
                    @error('harga')
                        <p class="mt-1.5 text-xs font-medium text-[#64120c]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Gambar Menu -->
                <div>
                    <label for="gambar" class="block text-sm font-semibold text-[#552919] mb-2">
                        Foto Produk Menu
                    </label>
                    <input type="file" 
                           name="gambar" 
                           id="gambar" 
                           accept="image/jpeg,image/png,image/jpg,image/webp"
                           class="w-full px-3 py-2 rounded-lg border border-[#ab7843]/50 text-sm text-[#552919] file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#ab7843] file:text-white hover:file:bg-[#552919] transition">
                    <p class="mt-1 text-xs text-[#552919]/60">Format yang didukung: JPG, PNG, WEBP (Maksimal 2MB).</p>
                    @error('gambar')
                        <p class="mt-1.5 text-xs font-medium text-[#64120c]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Ketersediaan Stok -->
                <div>
                    <label for="is_available" class="block text-sm font-semibold text-[#552919] mb-2">
                        Status Stok Awal
                    </label>
                    <select name="is_available" 
                            id="is_available" 
                            class="w-full px-4 py-2.5 rounded-lg border border-[#ab7843]/50 bg-white text-[#552919] focus:outline-none focus:border-[#64120c] focus:ring-1 focus:ring-[#64120c]">
                        <option value="1" {{ old('is_available', '1') == '1' ? 'selected' : '' }}>Tersedia</option>
                        <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>Habis</option>
                    </select>
                    @error('is_available')
                        <p class="mt-1.5 text-xs font-medium text-[#64120c]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-[#ab7843]/20">
                    <a href="{{ route('menus.index') }}" 
                       class="px-4 py-2 rounded-lg border border-[#ab7843] text-[#552919] hover:bg-[#f2ede7] text-sm font-medium transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-[#64120c] hover:bg-[#552919] text-[#f2ede7] px-5 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                        Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>