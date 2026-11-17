<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Kategori</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f2ede7] text-[#552919] antialiased">

    <div class="max-w-4xl mx-auto py-10 px-4">
        <!-- Header & Tombol Tambah -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#552919]">Kelola Kategori Menu</h1>
                <p class="text-sm text-[#552919]/75">Daftar kategori makanan dan minuman</p>
            </div>
            <a href="{{ route('categories.create') }}" 
               class="bg-[#64120c] hover:bg-[#552919] text-[#f2ede7] font-medium px-4 py-2 rounded-lg shadow-sm transition">
                + Tambah Kategori
            </a>
        </div>

        <!-- Notifikasi Sukses -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-white border-l-4 border-[#ab7843] text-[#552919] rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabel Kategori -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-[#ab7843]/30">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#552919] text-[#f2ede7] text-xs uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-6 w-16">No</th>
                        <th class="py-3.5 px-6">Nama Kategori</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#ab7843]/20 text-sm">
                    @forelse ($categories as $index => $category)
                        <tr class="hover:bg-[#f2ede7]/60 transition">
                            <td class="py-4 px-6 font-medium text-[#552919]/70">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-semibold text-[#552919]">{{ $category->nama_kategori }}</td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('categories.edit', $category->id) }}" 
                                   class="inline-block bg-[#ab7843] hover:bg-[#552919] text-white px-3 py-1.5 rounded-md text-xs font-medium transition">
                                    Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                            <td colspan="3" class="py-8 px-6 text-center text-[#552919]/70">
                                Belum ada data kategori. Klik <strong>+ Tambah Kategori</strong> untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>