@extends('customer.layouts')

@section('title', 'Keranjang Belanja - Resto QR')
@section('subtitle', 'Keranjang Belanja')

@section('content')

    <div class="px-4 pt-4 pb-44">

        {{-- NOTIFIKASI --}}
        @if (session('success'))
            <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif


        {{-- JUDUL KERANJANG --}}
        <section class="mb-4">

            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-orange-500"></span>

                        <h2 class="text-xl font-semibold text-[#29211e]">
                            Pesanan Meja {{ session('no_meja', '5') }}
                        </h2>
                    </div>

                    <p class="mt-1 flex items-center gap-1 text-xs text-[#76594e]">
                        <span>♧</span>
                        {{ $totalItems }} Menu Dipilih · Dine-in
                    </p>
                </div>

                <div class="flex items-center gap-2 rounded-full bg-[#eee7e3] px-3 py-2 text-xs font-semibold">
                    <span class="text-[#ae3b00]">▰</span>
                    Meja {{ session('no_meja', '5') }}
                </div>

            </div>

        </section>


        @if ($cartItems->isEmpty())
            {{-- KERANJANG KOSONG --}}
            <div class="rounded-2xl border border-[#f1e5df] bg-white px-5 py-12 text-center">
                <div class="mb-3 text-5xl">🛍️</div>
                <h3 class="text-lg font-bold text-[#302a26]">
                    Keranjang Masih Kosong
                </h3>

                <p class="mt-2 text-sm text-[#8b756b]">
                    Yuk, pilih makanan dan minuman favoritmu!
                </p>

                <a href="{{ route('customer.menu', ['no_meja' => session('no_meja')]) }}"
                    class="mt-5 inline-flex rounded-full bg-[#ad3b00] px-6 py-3 text-sm font-semibold text-white">
                    Lihat Katalog Menu
                </a>
            </div>
        @else
            {{-- DAFTAR ITEM KERANJANG --}}
            <section class="space-y-3">
                @foreach ($cartItems as $item)
                    <div class="rounded-2xl border border-[#f3e9e4] bg-white p-3 shadow-sm">

                        {{-- INFORMASI MENU --}}
                        <div class="flex items-start gap-3">

                            {{-- GAMBAR --}}
                            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-[#f6eee9]">
                                @if ($item->gambar)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->gambar) }}"
                                        alt="{{ $item->nama_menu }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center text-3xl">
                                        🍽️
                                    </div>
                                @endif
                            </div>


                            {{-- DETAIL MENU --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">

                                    <h3 class="text-base font-semibold leading-snug text-[#29211e]">
                                        {{ $item->nama_menu }}
                                    </h3>

                                    {{-- HAPUS ITEM --}}
                                    <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="shrink-0 rounded-lg p-1 text-[#796d66] hover:bg-red-50 hover:text-red-600"
                                            onclick="return confirm('Hapus {{ $item->nama_menu }} dari keranjang?')">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.7" stroke="currentColor" class="h-5 w-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 7h12m-10 0V5h8v2m-9 0 1 14h8l1-14m-7 4v6m4-6v6" />
                                            </svg>
                                        </button>
                                    </form>

                                </div>


                                <p class="mt-1 text-sm font-semibold text-[#b33d00]">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    <span class="text-[10px] font-normal text-[#76594e]">
                                        / porsi
                                    </span>
                                </p>

                                <p class="mt-1 inline-block rounded-md bg-[#f8f0ec] px-2 py-1 text-[11px] text-[#76594e]">
                                    {{ $item->category?->nama_kategori ?? 'Menu' }}
                                </p>

                                @if (!$item->is_available)
                                    <p class="mt-1 text-[11px] font-semibold text-red-600">
                                        Stok menu sedang habis
                                    </p>
                                @endif
                            </div>
                        </div>


                        {{-- SUBTOTAL DAN KUANTITAS --}}
                        <div class="mt-3 flex items-center justify-between gap-2 rounded-xl bg-[#fcf8f5] px-2 py-2">

                            <span class="text-[11px] text-[#76594e]">
                                Subtotal menu
                            </span>

                            {{-- KONTROL JUMLAH --}}
                            <div class="flex items-center gap-1 rounded-full bg-[#eee9e5] p-0.5">

                                {{-- KURANG --}}
                                <form action="{{ route('customer.cart.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="qty" value="{{ $item->qty - 1 }}">
                                    <button type="submit" {{ $item->qty <= 1 ? 'disabled' : '' }}
                                        class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-lg font-medium text-[#b33d00] shadow-sm disabled:cursor-not-allowed disabled:opacity-40">
                                        −
                                    </button>

                                </form>


                                <span class="min-w-6 text-center text-sm font-semibold">
                                    {{ $item->qty }}
                                </span>


                                {{-- TAMBAH --}}
                                <form action="{{ route('customer.cart.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="qty" value="{{ $item->qty + 1 }}">
                                    <button type="submit" {{ !$item->is_available ? 'disabled' : '' }}
                                        class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-lg font-medium text-[#b33d00] shadow-sm disabled:cursor-not-allowed disabled:opacity-40">
                                        +
                                    </button>
                                </form>

                            </div>


                            {{-- SUBTOTAL --}}
                            <span class="text-sm font-bold text-[#29211e]">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>

                        </div>

                    </div>
                @endforeach

            </section>


            <div class="flex items-center justify-between gap-3 mt-4">
                {{-- Tombol Tambah Pesanan Lainnya --}}
                <a href="{{ route('customer.menu', ['no_meja' => session('no_meja')]) }}"
                    class="flex-1 text-center rounded-xl border border-[#b33d00] px-3 py-3 text-sm font-semibold text-[#b33d00]">
                    + Tambah Pesanan Lainnya
                </a>

                {{-- Tombol Kosongkan Keranjang --}}
                <form action="{{ route('customer.cart.clear') }}" method="POST"
                    onsubmit="return confirm('Yakin ingin mengosongkan seluruh keranjang?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="rounded-xl border border-red-500 px-3 py-3 text-sm font-semibold text-red-600">
                        Kosongkan
                    </button>
                </form>

            </div>


            {{-- CATATAN DAPUR --}}
            <section class="mt-4 rounded-2xl border border-[#f3e9e4] bg-white p-3">

                <h3 class="flex items-center gap-2 text-base font-semibold">
                    <span class="text-[#b33d00]">♨</span>
                    Catatan untuk Dapur
                </h3>

                <p class="mt-1 text-xs text-[#76594e]">
                    Sampaikan preferensi penyajian atau alergi Anda
                </p>

                <form action="{{ route('customer.cart.note') }}" method="POST">
                    @csrf
                    <textarea name="catatan_dapur" rows="3" maxlength="500" placeholder="Contoh: Alergi udang, sendok garpu ekstra"
                        class="mt-3 w-full resize-none rounded-xl border border-[#f1e6e0] bg-[#faf3ef] p-3 text-sm text-[#382a25] outline-none placeholder:text-[#a18b80] focus:border-[#bd4b12] focus:ring-1 focus:ring-[#bd4b12]">{{ old('catatan_dapur', $cartNote ?? '') }}</textarea>
                    <div class="mt-2 flex justify-end">
                        <button type="submit" class="rounded-full bg-[#ad3b00] px-4 py-2 text-xs font-semibold text-white">
                            Simpan Catatan
                        </button>
                    </div>
                </form>

            </section>


            {{-- RINCIAN PEMBAYARAN --}}
            <section class="mt-4 rounded-2xl border border-[#f3e9e4] bg-white p-3">

                <div class="mb-4 flex items-center justify-between">

                    <h3 class="flex items-center gap-2 text-base font-semibold">
                        <span class="text-[#b33d00]">▤</span>
                        Rincian Pembayaran
                    </h3>

                    <span class="rounded-full bg-[#ffdfcf] px-2 py-1 text-[10px] font-semibold text-[#913500]">
                        Meja {{ session('no_meja', '5') }}
                    </span>

                </div>


                <div class="space-y-3 text-sm">

                    {{-- SUBTOTAL --}}
                    <div class="flex items-center justify-between">
                        <span class="text-[#76594e]">
                            Subtotal Pesanan
                        </span>

                        <span class="font-medium">
                            Rp {{ number_format($totalPrice, 0, ',', '.') }}
                        </span>
                    </div>


                    {{-- PAJAK --}}
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[#76594e]">
                            Pajak Restoran PB1 (10%)
                        </span>

                        <span class="font-medium">
                            Rp {{ number_format($pajak, 0, ',', '.') }}
                        </span>
                    </div>
                </div>


                {{-- TOTAL --}}
                <div class="mt-4 rounded-xl bg-[#faf1ec] p-3">

                    <div class="flex items-center justify-between gap-2">

                        <div>
                            <p class="text-xs font-semibold text-[#382a25]">
                                Total Pembayaran
                            </p>

                            <p class="mt-1 text-[10px] text-[#76594e]">
                                Sudah termasuk pajak
                            </p>
                        </div>

                        <span class="text-xl font-bold text-[#b33d00]">
                            Rp {{ number_format($totalBayar, 0, ',', '.') }}
                        </span>

                    </div>

                </div>

            </section>


            {{-- INFORMASI PESANAN --}}
            <div class="mt-4 flex items-start gap-2 rounded-xl bg-[#ffede4] p-3">

                <span
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#ffdac8] text-lg text-[#b33d00]">
                    ⚡
                </span>

                <p class="text-xs leading-relaxed text-[#913500]">
                    Pesanan akan diteruskan ke dapur setelah pembayaran dikonfirmasi.
                </p>

            </div>

        @endif

    </div>


    {{-- FOOTER TOTAL DAN CHECKOUT --}}
    @if ($totalItems > 0)
        <div
            class="fixed inset-x-0 bottom-[72px] z-30 mx-auto w-full max-w-[430px] border-t border-[#f0e5df] bg-[#fffaf7]/95 px-4 py-3 shadow-[0_-5px_20px_rgba(0,0,0,0.06)] backdrop-blur">

            <div class="flex items-center justify-between gap-3">

                <div class="shrink-0">
                    <p class="text-[11px] uppercase tracking-wide text-[#76594e]">
                        Total Pesanan
                    </p>

                    <p class="text-lg font-bold text-[#b33d00]">
                        Rp {{ number_format($totalBayar, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Checkout akan dihubungkan pada tahap berikutnya --}}
                <button type="button" disabled
                    class="flex flex-1 items-center justify-center gap-2 rounded-full bg-[#a93800] px-4 py-4 text-sm font-semibold text-white opacity-70">

                    Lanjut ke Pembayaran
                    <span class="text-lg">→</span>

                </button>

            </div>

        </div>
    @endif

@endsection
