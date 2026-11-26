<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kasir Cafe</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-almond text-espresso flex min-h-screen">

    <!-- Sidebar kiri -->
    <aside class="w-60 bg-espresso text-almond p-6">
        <h1 class="text-xl font-bold mb-8">Cafe Kasir</h1>
        <a href="/kasir" class="block bg-caramel text-white rounded-lg px-4 py-2">
            Antrean
        </a>
    </aside>

    <!-- Konten kanan -->
    <main class="flex-1 p-8">

        <!-- Kotak pesan, muncul setelah kasir menekan tombol -->
        @if (session('pesan'))
            <div class="bg-white border-l-4 border-caramel rounded-lg px-4 py-3 mb-6">
                {{ session('pesan') }}
            </div>
        @endif

        @yield('konten')
    </main>

</body>
</html>