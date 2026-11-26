<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <title>@yield('title', 'Kasir') - Cafe</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-almond text-espresso md:flex">
    <x-app-sidebar />

    <main class="min-w-0 flex-1 p-6 md:ml-64 md:p-10">
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
