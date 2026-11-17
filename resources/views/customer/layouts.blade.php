<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Resto QR')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#eee9e5] text-[#29211e] antialiased">

    {{-- Container utama aplikasi --}}
    <div class="relative mx-auto min-h-screen w-full max-w-[430px] bg-[#fff8f5] shadow-xl">

        {{-- HEADER --}}
        <header class="sticky top-0 z-40 border-b border-[#f3e9e4] bg-[#fff8f5]/95 backdrop-blur">

            <div class="flex h-[72px] items-center justify-between px-5">

                {{-- Logo dan nama restoran --}}
                <div class="flex-1 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <h1 class="text-xl font-semibold tracking-tight">
                            Resto QR
                        </h1>

                        <span class="rounded-full bg-[#ffdfcf] px-2 py-1 text-[10px] font-semibold text-[#913500]">
                            {{ session('no_meja', 'Meja 5') }}
                        </span>
                    </div>

                    <p class="text-[11px] text-[#76594e]">
                        @yield('subtitle', 'Katalog Menu')
                    </p>
                </div>

                {{-- Tombol notifikasi dan profil --}}
                <div class="absolute right-4 flex items-center gap-3">

                    <button type="button" class="flex h-9 w-9 items-center justify-center text-[#624b40]"
                        aria-label="Notifikasi">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor" class="h-6 w-6">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.25 18.75a2.25 2.25 0 0 1-4.5 0m9-2.25H5.25l1.5-1.5V10.5a5.25 5.25 0 0 1 10.5 0V15l1.5 1.5Z" />
                        </svg>
                    </button>

                    <button type="button"
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-[#a93800] text-white"
                        aria-label="Profil">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="h-5 w-5">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.1a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75c-2.7 0-5.26-.6-7.5-1.65Z" />
                        </svg>
                    </button>

                </div>
            </div>
        </header>


        {{-- KONTEN HALAMAN --}}
        <main class="min-h-screen pb-32">

            @yield('content')

        </main>


        {{-- BOTTOM NAVIGATION --}}
        <nav class="fixed inset-x-0 bottom-0 z-50 mx-auto w-full max-w-[430px] border-t border-[#f1e6e0] bg-[#fffaf7]">

            <div class="grid h-[72px] grid-cols-3">

                {{-- Menu --}}
                <a href="{{ route('customer.menu') }}"
                    class="flex flex-col items-center justify-center gap-1
                    {{ request()->routeIs('customer.menu') ? 'text-[#b33d00]' : 'text-[#76594e]' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="h-6 w-6">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m5 5 14 14M19 5 5 19M7.5 3.75l1.5 3M3.75 7.5l3 1.5m9.75 11.25 1.5-3m3-9.75-3-1.5" />
                    </svg>

                    <span class="text-[11px] font-medium">Menu</span>
                </a>


                {{-- Keranjang --}}
                <a href="{{ url('/keranjang') }}"
                    class="flex flex-col items-center justify-center gap-1 text-[#76594e]">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="h-6 w-6">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 7.5h12l1 13H5l1-13Zm3 0V6a3 3 0 0 1 6 0v1.5" />
                    </svg>

                    <span class="text-[11px] font-medium">Keranjang</span>
                </a>


                {{-- Pesanan --}}
                <a href="{{ url('/pesanan') }}" class="flex flex-col items-center justify-center gap-1 text-[#76594e]">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="h-6 w-6">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7 3.75h10v16.5H7V3.75Zm3 4.5h4m-4 4h4m-4 4h4M4 6v14.25h13" />
                    </svg>

                    <span class="text-[11px] font-medium">Pesanan</span>
                </a>

            </div>
        </nav>

    </div>

</body>

</html>
