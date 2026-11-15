<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    @vite('resources/css/app.css')
    <title>Dashboard Owner</title>
</head>
<body class="min-h-screen bg-[#f2ede7] text-[#552919] antialiased">
    <div class="min-h-screen md:flex">
        <aside class="hidden w-64 flex-col bg-[#552919] text-white md:fixed md:inset-y-0 md:left-0 md:z-40 md:flex md:overflow-y-auto">
            <div class="border-b border-white/10 px-6 py-7">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#f2ede7] text-lg font-bold text-white">
                        <img src="{{ asset('img/Nafil Cafe & Resto Logo.png') }}" alt="Nafil" class="flex h-10 w-13">
                    </div>
                    <div>
                        <p class="font-bold">Nafil Cafe & resto</p>
                        <p class="text-xs text-white/60">Owner dashboard</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 space-y-1 px-4 py-6">
                <a href="#ringkasan"
                   class="flex items-center gap-3 rounded-xl bg-white/10 px-4 py-3 text-sm font-semibold text-white">
                    <span class="material-symbols-outlined text-xl" aria-hidden="true">dashboard</span>
                    Ringkasan
                </a>

                <a href="#transaksi"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-white/70 transition hover:bg-white/10 hover:text-white">
                    <span class="material-symbols-outlined text-xl" aria-hidden="true">receipt_long</span>
                    Riwayat transaksi
                </a>

                <a href="#pembayaran"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-white/70 transition hover:bg-white/10 hover:text-white">
                    <span class="material-symbols-outlined text-xl" aria-hidden="true">payments</span>
                    Pembayaran
                </a>

                <a href="#menu-terlaris"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-white/70 transition hover:bg-white/10 hover:text-white">
                    <span class="material-symbols-outlined text-xl" aria-hidden="true">restaurant_menu</span>
                    Menu terlaris
                </a>
            </nav>

            <div class="border-t border-white/10 p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-sm font-medium text-white/70 transition hover:bg-white/10 hover:text-white">
                        <span class="material-symbols-outlined text-xl" aria-hidden="true">logout</span>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <main class="min-w-0 flex-1 md:ml-64">
            <header class="flex items-center justify-between border-b border-[#ab7843]/20 bg-white px-5 py-4 sm:px-8">
                <div>
                    <p class="text-xs text-[#ab7843]">Dashboard / Owner</p>
                    <p class="mt-1 font-semibold text-[#552919]">{{ auth()->user()->name }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="hidden rounded-full bg-[#f2ede7] px-3 py-1.5 text-xs font-semibold text-[#64120c] sm:inline-flex">
                        OWNER
                    </span>

                    <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1.5 rounded-lg border border-[#ab7843]/30 px-3 py-2 text-sm font-medium text-[#552919]">
                            <span class="material-symbols-outlined text-lg" aria-hidden="true">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <div class="mx-auto max-w-[1600px] px-5 py-7 sm:px-8">
                <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                    <div class="min-w-0 space-y-6">
                        <section id="ringkasan"
                                 class="overflow-hidden rounded-2xl bg-gradient-to-r from-[#64120c] to-[#ab7843] px-6 py-7 text-white shadow-lg sm:px-8">
                            <p class="text-sm font-medium text-white/75">{{ now()->format('d/m/Y') }}</p>
                            <h1 class="mt-2 text-2xl font-bold sm:text-3xl">
                                Selamat datang, {{ auth()->user()->name }}!
                            </h1>
                            <p class="mt-2 max-w-xl text-sm leading-6 text-white/80">
                                Berikut ringkasan transaksi dan performa restoran Anda.
                            </p>
                        </section>

                        <section class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-3">
                            <article class="rounded-2xl border border-[#ab7843]/20 bg-white p-5 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-[#552919]/70">Total pendapatan</p>
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f2ede7] text-[#64120c]">
                                        <span class="material-symbols-outlined" aria-hidden="true">payments</span>
                                    </span>
                                </div>
                                <p class="mt-4 text-2xl font-bold text-[#552919]">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </p>
                                <p class="mt-1 text-xs text-[#552919]/60">Pesanan diproses dan selesai</p>
                            </article>

                            <article class="rounded-2xl border border-[#ab7843]/20 bg-white p-5 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-[#552919]/70">Pesanan selesai</p>
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f2ede7] text-[#64120c]">
                                        <span class="material-symbols-outlined" aria-hidden="true">task_alt</span>
                                    </span>
                                </div>
                                <p class="mt-4 text-2xl font-bold text-[#552919]">
                                    {{ number_format($selesai, 0, ',', '.') }}
                                </p>
                                <p class="mt-1 text-xs text-[#552919]/60">Dalam rentang tanggal terpilih</p>
                            </article>

                            <article class="rounded-2xl border border-[#ab7843]/20 bg-white p-5 shadow-sm sm:col-span-2 2xl:col-span-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-[#552919]/70">Total transaksi</p>
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f2ede7] text-[#64120c]">
                                        <span class="material-symbols-outlined" aria-hidden="true">receipt_long</span>
                                    </span>
                                </div>
                                <p class="mt-4 text-2xl font-bold text-[#552919]">
                                    {{ number_format($transactions->total(), 0, ',', '.') }}
                                </p>
                                <p class="mt-1 text-xs text-[#552919]/60">Diproses dan selesai</p>
                            </article>
                        </section>

                        <section class="rounded-2xl border border-[#ab7843]/20 bg-white p-5 shadow-sm sm:p-6">
                            <div class="mb-5">
                                <h2 class="font-semibold text-[#552919]">Filter laporan</h2>
                                <p class="mt-1 text-sm text-[#552919]/60">
                                    Pilih tanggal atau rentang tanggal.
                                </p>
                            </div>

                            <form method="GET" action="{{ route('owner.dashboard') }}"
                                  class="grid gap-4 sm:grid-cols-[1fr_1fr_auto_auto] sm:items-end">
                                <div>
                                    <label for="from" class="mb-1.5 block text-sm font-medium text-[#552919]">
                                        Dari tanggal
                                    </label>
                                    <input id="from" name="from" type="date" value="{{ $from }}"
                                           class="w-full rounded-lg border border-[#ab7843]/30 px-3 py-2.5 text-sm text-[#552919] outline-none focus:border-[#64120c] focus:ring-2 focus:ring-[#64120c]/10">
                                    @error('from')
                                        <p class="mt-1 text-sm text-[#64120c]">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="to" class="mb-1.5 block text-sm font-medium text-[#552919]">
                                        Sampai tanggal
                                    </label>
                                    <input id="to" name="to" type="date" value="{{ $to }}"
                                           class="w-full rounded-lg border border-[#ab7843]/30 px-3 py-2.5 text-sm text-[#552919] outline-none focus:border-[#64120c] focus:ring-2 focus:ring-[#64120c]/10">
                                    @error('to')
                                        <p class="mt-1 text-sm text-[#64120c]">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                        class="rounded-lg bg-[#64120c] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#552919]">
                                    Terapkan
                                </button>

                                <a href="{{ route('owner.dashboard') }}"
                                   class="rounded-lg border border-[#ab7843]/30 px-5 py-2.5 text-center text-sm font-semibold text-[#552919] transition hover:bg-[#f2ede7]">
                                    Reset
                                </a>
                            </form>
                        </section>

                        <section id="transaksi"
                                 class="overflow-hidden rounded-2xl border border-[#ab7843]/20 bg-white shadow-sm">
                            <div class="flex items-center justify-between border-b border-[#ab7843]/20 px-5 py-5 sm:px-6">
                                <div>
                                    <h2 class="font-semibold text-[#552919]">Riwayat transaksi</h2>
                                    <p class="mt-1 text-sm text-[#552919]/60">
                                        Pesanan diproses dan selesai
                                    </p>
                                </div>
                                <span class="rounded-lg bg-[#f2ede7] px-3 py-1.5 text-xs font-semibold text-[#64120c]">
                                    {{ number_format($transactions->total(), 0, ',', '.') }} pesanan
                                </span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] text-left text-sm">
                                    <thead class="bg-[#f2ede7] text-xs uppercase tracking-wide text-[#552919]/70">
                                        <tr>
                                            <th class="px-5 py-3 font-semibold">Pesanan</th>
                                            <th class="px-5 py-3 font-semibold">Pelanggan</th>
                                            <th class="px-5 py-3 font-semibold">Meja</th>
                                            <th class="px-5 py-3 font-semibold">Status</th>
                                            <th class="px-5 py-3 font-semibold">Pembayaran</th>
                                            <th class="px-5 py-3 text-right font-semibold">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#ab7843]/15">
                                        @forelse ($transactions as $transaction)
                                            <tr class="transition hover:bg-[#f2ede7]/60">
                                                <td class="px-5 py-4">
                                                    <p class="font-semibold text-[#552919]">
                                                        {{ $transaction->kode_unik }}
                                                    </p>
                                                    <p class="mt-1 text-xs text-[#552919]/60">
                                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                                    </p>
                                                </td>
                                                <td class="px-5 py-4 font-medium text-[#552919]">
                                                    {{ $transaction->nama_pelanggan }}
                                                </td>
                                                <td class="px-5 py-4 text-[#552919]/75">
                                                    {{ $transaction->no_meja }}
                                                </td>
                                                <td class="px-5 py-4">
                                                    @if ($transaction->status === 'selesai')
                                                        <span class="rounded-full bg-[#64120c]/10 px-2.5 py-1 text-xs font-semibold text-[#64120c]">
                                                            Selesai
                                                        </span>
                                                    @else
                                                        <span class="rounded-full bg-[#ab7843]/20 px-2.5 py-1 text-xs font-semibold text-[#552919]">
                                                            Diproses
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 capitalize text-[#552919]/75">
                                                    {{ $transaction->metode_bayar }}
                                                </td>
                                                <td class="px-5 py-4 text-right font-semibold text-[#552919]">
                                                    Rp {{ number_format($transaction->total_tagihan, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-5 py-12 text-center text-sm text-[#552919]/60">
                                                    Tidak ada transaksi pada rentang tanggal ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($transactions->hasPages())
                                <div class="flex items-center justify-between border-t border-[#ab7843]/20 px-5 py-4 sm:px-6">
                                    @if ($transactions->previousPageUrl())
                                        <a href="{{ $transactions->previousPageUrl() }}"
                                           class="rounded-lg border border-[#ab7843]/30 px-3 py-2 text-sm font-medium text-[#552919] hover:bg-[#f2ede7]">
                                            Sebelumnya
                                        </a>
                                    @else
                                        <span class="rounded-lg border border-[#ab7843]/20 px-3 py-2 text-sm text-[#552919]/40">
                                            Sebelumnya
                                        </span>
                                    @endif

                                    <span class="text-sm text-[#552919]/70">
                                        Halaman {{ $transactions->currentPage() }} dari {{ $transactions->lastPage() }}
                                    </span>

                                    @if ($transactions->nextPageUrl())
                                        <a href="{{ $transactions->nextPageUrl() }}"
                                           class="rounded-lg border border-[#ab7843]/30 px-3 py-2 text-sm font-medium text-[#552919] hover:bg-[#f2ede7]">
                                            Berikutnya
                                        </a>
                                    @else
                                        <span class="rounded-lg border border-[#ab7843]/20 px-3 py-2 text-sm text-[#552919]/40">
                                            Berikutnya
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </section>
                    </div>

                    <aside class="space-y-6 xl:sticky xl:top-6 xl:self-start">
                        <section id="pembayaran"
                                 class="rounded-2xl border border-[#ab7843]/20 bg-white p-5 shadow-sm">
                            <div class="mb-5">
                                <h2 class="font-semibold text-[#552919]">Metode pembayaran</h2>
                                <p class="mt-1 text-sm text-[#552919]/60">
                                    Pemasukan berdasarkan metode
                                </p>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between rounded-xl bg-[#f2ede7] p-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-[#64120c]">
                                            <span class="material-symbols-outlined" aria-hidden="true">payments</span>
                                        </span>
                                        <span class="text-sm font-medium text-[#552919]">Tunai</span>
                                    </div>
                                    <span class="text-sm font-bold text-[#552919]">
                                        Rp {{ number_format($tunai, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between rounded-xl bg-[#f2ede7] p-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-[#ab7843]">
                                            <span class="material-symbols-outlined" aria-hidden="true">credit_card</span>
                                        </span>
                                        <span class="text-sm font-medium text-[#552919]">Non-tunai</span>
                                    </div>
                                    <span class="text-sm font-bold text-[#552919]">
                                        Rp {{ number_format($nonTunai, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </section>

                        <section id="menu-terlaris"
                                 class="rounded-2xl border border-[#ab7843]/20 bg-white p-5 shadow-sm">
                            <div class="mb-4">
                                <h2 class="font-semibold text-[#552919]">Menu terlaris</h2>
                                <p class="mt-1 text-sm text-[#552919]/60">
                                    Berdasarkan jumlah porsi
                                </p>
                            </div>

                            <div class="divide-y divide-[#ab7843]/15">
                                @forelse ($topMenus as $index => $menu)
                                    <div class="flex items-center justify-between gap-3 py-3">
                                        <div class="flex min-w-0 items-center gap-3">
                                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#f2ede7] text-sm font-bold text-[#64120c]">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="truncate text-sm font-medium text-[#552919]">
                                                {{ $menu->nama_menu }}
                                            </span>
                                        </div>
                                        <span class="shrink-0 text-xs font-semibold text-[#552919]/70">
                                            {{ number_format($menu->total_terjual, 0, ',', '.') }} porsi
                                        </span>
                                    </div>
                                @empty
                                    <p class="py-6 text-center text-sm text-[#552919]/60">
                                        Belum ada data penjualan.
                                    </p>
                                @endforelse
                            </div>
                        </section>
                    </aside>
                </div>
            </div>
        </main>
    </div>
</body>
</html>