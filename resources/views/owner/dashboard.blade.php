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
        <x-app-sidebar />
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
                </div>
            </header>

            <div class="mx-auto max-w-[1600px] px-5 py-7 sm:px-8">
                <div class="grid grid-cols-1 gap-6">
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
                                <p class="mt-1 text-xs text-[#552919]/60">Pesanan berstatus selesai</p>
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
                                                <td class="px-5 py-4 text-right font-semibold text-[#552919]">
                                                    Rp {{ number_format($transaction->total_tagihan, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-5 py-12 text-center text-sm text-[#552919]/60">
                                                    Belum ada transaksi.
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
                </div>
            </div>
        </main>
    </div>
</body>
</html>