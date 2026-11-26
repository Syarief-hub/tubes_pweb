<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', 'Admin') - Nafil Cafe</title>
</head>
<body class="min-h-screen bg-[#f2ede7] text-[#552919] antialiased">
    <div class="min-h-screen md:flex">
        <x-app-sidebar />

        <main class="min-w-0 flex-1 md:ml-64">
            <header class="flex items-center justify-between border-b border-[#ab7843]/20 bg-white px-5 py-4 sm:px-8">
                <div>
                    <p class="text-xs text-[#ab7843]">Dashboard / Admin</p>
                    <p class="mt-1 font-semibold">{{ auth()->user()->name }}</p>
                </div>
                <span class="rounded-full bg-[#f2ede7] px-3 py-1.5 text-xs font-semibold text-[#64120c]">
                    ADMIN
                </span>
            </header>

            @yield('content')
        </main>
    </div>
</body>
</html>