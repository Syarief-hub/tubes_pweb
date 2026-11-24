<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasir') - Cafe</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-almond text-espresso md:flex">
    <aside class="bg-espresso text-almond md:w-60 md:min-h-screen p-6">
        <h1 class="text-xl font-bold mb-8">☕ Cafe Kasir</h1>
        <nav class="space-y-1">
            <a href="{{ route('kasir.index') }}"
               class="block rounded-lg px-4 py-2 {{ request()->routeIs('kasir.*') ? 'bg-caramel text-white' : 'hover:bg-white/10' }}">
                Antrean
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-6 md:p-10">
        <h2 class="text-2xl font-bold mb-6">@yield('title')</h2>

        @if (session('success'))
            <div role="status" class="mb-6 rounded-lg border-l-4 border-caramel bg-white px-4 py-3 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
