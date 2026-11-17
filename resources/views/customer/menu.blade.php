@extends('customer.layouts')

@section('title', 'Katalog Menu - NafilCafe & Resto')
@section('subtitle', 'Katalog Menu')

@section('content')

    {{-- Notifikasi berhasil --}}
    @if (session('success'))
        <div class="mx-4 mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700" role="status">
            {{ session('success') }}

        </div>
    @endif

    {{-- Notifikasi gagal --}}
    @if (session('error'))
        <div class="mx-4 mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            {{ session('error') }}

        </div>
    @endif


    {{-- BANNER SELAMAT DATANG --}}
    <section class="px-4 pt-4">
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#d94700] to-[#ae3700] p-4 text-white shadow-md">

            <div class="relative z-10">
                <div class="mb-2 flex items-center gap-2 text-xs font-medium tracking-wider">
                    <span>♨</span>
                    <span>MEJA {{ session('no_meja', '05') }} • SANTAP DI TEMPAT</span>
                </div>

                <h2 class="text-xl font-bold leading-tight">
                    Selamat Datang di NafilCafe & Resto!
                </h2>

                <p class="mt-2 max-w-[240px] text-sm leading-relaxed text-orange-50">
                    Pilih hidangan favoritmu, kami sajikan hangat penuh rasa.
                </p>
            </div>

            <div class="absolute -right-8 -top-10 h-36 w-36 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-12 right-12 h-28 w-28 rounded-full bg-white/10"></div>

        </div>
    </section>

    {{-- PENCARIAN MENU --}}
    <section class="px-4 pt-4">
        <form action="{{ route('customer.menu') }}" method="GET">

            @if (request('category_id'))
                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
            @endif

            <div class="flex items-center gap-3 rounded-full border border-[#f2e8e3] bg-white px-4 py-3 shadow-sm">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                    stroke="currentColor" class="h-5 w-5 shrink-0 text-[#76594e]">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 1 1-13.5 0 6.75 6.75 0 0 1 13.5 0Z" />
                </svg>

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu favorit kamu..."
                    class="w-full border-none bg-transparent text-sm text-[#382a25] outline-none placeholder:text-[#a18b80] focus:ring-0">

                <button type="submit" class="shrink-0 text-sm font-semibold text-[#ad3b00]">
                    Cari
                </button>

            </div>
        </form>
    </section>

    {{-- FILTER KATEGORI --}}
    <section class="pt-4">
        <div class="flex gap-2 overflow-x-auto px-4 pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">

            {{-- Semua Kategori --}}
            <a href="{{ route('customer.menu', ['search' => request('search')]) }}"
                class="flex shrink-0 items-center gap-1 rounded-full border px-4 py-2 text-xs font-semibold transition
                {{ !request('category_id')
                    ? 'border-[#a93800] bg-[#a93800] text-white shadow-md'
                    : 'border-[#f1e4de] bg-white text-[#654c41]' }}">

                Semua ({{ $totalMenu }})
            </a>

            {{-- Daftar Kategori --}}
            @foreach ($categories as $category)
                <a href="{{ route('customer.menu', [
                    'category_id' => $category->id,
                    'search' => request('search'),
                ]) }}"
                    class="flex shrink-0 items-center gap-1 rounded-full border px-4 py-2 text-xs font-semibold transition
                    {{ request('category_id') == $category->id
                        ? 'border-[#a93800] bg-[#a93800] text-white shadow-md'
                        : 'border-[#f1e4de] bg-white text-[#654c41]' }}">

                    {{ $category->nama_kategori }}
                    ({{ $category->menus_count }})
                </a>
            @endforeach

        </div>
    </section>

    {{-- DAFTAR MENU --}}
    <section class="px-4 pt-3 pb-6">

        <div class="mb-4 flex items-center justify-between">

            <div>
                <h2 class="text-lg font-bold text-[#29211e]">
                    Pilihan Menu
                </h2>

                <p class="text-xs text-[#8d7469]">
                    Temukan hidangan favoritmu
                </p>
            </div>

            <span class="text-xs font-medium text-[#76594e]">
                {{ $menus->count() }} Menu
            </span>

        </div>

        {{-- Jika Menu Tersedia --}}
        @forelse($menus as $menu)
            <div
                class="mb-3 flex min-h-[140px] gap-3 overflow-hidden rounded-2xl border border-[#f5ece7] bg-white p-3 shadow-sm">

                {{-- INFORMASI MENU --}}
                <div class="flex min-w-0 flex-1 flex-col">
                    {{-- Status Ketersediaan --}}
                    @if (!$menu->is_available)
                        <span class="mb-1.5 w-fit rounded-full bg-red-50 px-2 py-1 text-[10px] font-semibold text-red-600">
                            Stok Habis
                        </span>
                    @endif

                    {{-- Nama Menu --}}
                    <h3 class="line-clamp-2 text-base font-bold leading-snug text-[#29211e]">
                        {{ $menu->nama_menu }}
                    </h3>

                    {{-- Kategori --}}
                    <p class="mt-1 text-xs text-[#80675b]">
                        {{ $menu->category?->nama_kategori ?? 'Tanpa Kategori' }}
                    </p>

                    {{-- Harga dan Tombol --}}
                    <div class="mt-auto flex items-center justify-between gap-2 pt-3">
                        <span class="whitespace-nowrap text-base font-bold text-[#bd4000]">
                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        </span>

                        @if ($menu->is_available)
                            <form action="{{ route('customer.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                <button type="submit"
                                    class="flex shrink-0 items-center gap-1 rounded-full bg-[#ae3b00] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#913100]">
                                    <span class="text-base leading-none">+</span>
                                    Tambah
                                </button>
                            </form>
                        @else
                            <button type="button" disabled
                                class="shrink-0 rounded-full bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-400">
                                Habis
                            </button>
                        @endif

                    </div>
                </div>

                {{-- GAMBAR MENU --}}
                <div class="h-24 w-24 shrink-0 overflow-hidden rounded-xl bg-[#f5e9e2]">

                    @if ($menu->gambar)
                        <img src="{{ Storage::disk('public')->url($menu->gambar) }}" alt="{{ $menu->nama_menu }}"
                            loading="lazy" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full flex-col items-center justify-center text-[#b99b8c]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.4"
                                stroke="currentColor" class="h-9 w-9">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5 8.25 11l3.75 3.75 3-3L21 17.25M4.5 4.5h15v15h-15z" />
                            </svg>

                            <span class="text-[9px]">No Image</span>
                        </div>
                    @endif

                </div>
            </div>

        @empty

            {{-- Jika Tidak Ada Menu --}}
            <div class="rounded-2xl border border-dashed border-[#e7d6cc] bg-white px-5 py-12 text-center">
                <div class="mb-3 text-4xl">🍽️</div>

                <h3 class="font-semibold text-[#382a25]">
                    Menu Tidak Ditemukan
                </h3>

                <p class="mt-1 text-sm text-[#8d7469]">
                    Coba gunakan kata kunci atau kategori lainnya.
                </p>

                <a href="{{ route('customer.menu') }}"
                    class="mt-4 inline-block rounded-full bg-[#ae3b00] px-5 py-2 text-sm font-semibold text-white">
                    Lihat Semua Menu
                </a>
            </div>
        @endforelse
    </section>


    {{-- RINGKASAN KERANJANG SEMENTARA --}}
    <div class="fixed inset-x-0 bottom-[72px] z-30 mx-auto w-full max-w-[430px] px-4 pb-2">
        <a href="{{ url('/keranjang') }}"
            class="flex items-center justify-between rounded-full bg-[#302a26] px-3 py-2 text-white shadow-xl">

            <div class="flex items-center gap-3">
                <div class="relative flex h-10 w-10 items-center justify-center rounded-full bg-[#e84b00]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 7.5h12l1 13H5l1-13Zm3 0V6a3 3 0 0 1 6 0v1.5" />
                    </svg>
                </div>

                <div>
                    <p class="text-[10px] text-gray-300">
                        0 Item dalam Pesanan
                    </p>

                    <p class="text-base font-bold">
                        Rp 0
                    </p>
                </div>

            </div>

            <span class="flex items-center gap-1 rounded-full bg-[#ad3b00] px-4 py-3 text-xs font-semibold">
                Lihat Keranjang
                <span class="text-base">→</span>
            </span>
        </a>
    </div>

@endsection
