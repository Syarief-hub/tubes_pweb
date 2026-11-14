<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Kategori</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f2ede7] text-[#552919] antialiased">

    <div class="max-w-xl mx-auto py-10 px-4">
        <!-- Judul Halaman -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#552919]">Edit Kategori</h1>
            <p class="text-sm text-[#552919]/75">Perbarui nama kategori makanan atau minuman</p>
        </div>

        <!-- Kartu Form -->
        <div class="bg-white rounded-xl shadow-sm border border-[#ab7843]/30 overflow-hidden">
            <div class="bg-[#552919] px-6 py-3.5">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-[#f2ede7]">Form Edit Data Kategori</h2>
            </div>

            <form action="{{ route('categories.update', $category->id) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="nama_kategori" class="block text-sm font-semibold text-[#552919] mb-2">
                        Nama Kategori
                    </label>
                    <input type="text" 
                           name="nama_kategori" 
                           id="nama_kategori" 
                           value="{{ old('nama_kategori', $category->nama_kategori) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-[#ab7843]/50 text-[#552919] focus:outline-none focus:border-[#64120c] focus:ring-1 focus:ring-[#64120c]">
                    
                    <!-- Pesan Error Validasi -->
                    @error('nama_kategori')
                        <p class="mt-1.5 text-xs font-medium text-[#64120c]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-end space-x-3 pt-2">
                    <a href="{{ route('categories.index') }}" 
                       class="px-4 py-2 rounded-lg border border-[#ab7843] text-[#552919] hover:bg-[#f2ede7] text-sm font-medium transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-[#64120c] hover:bg-[#552919] text-[#f2ede7] px-5 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>