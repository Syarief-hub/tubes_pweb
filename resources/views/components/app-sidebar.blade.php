@php
    $role = auth()->user()->role;

    $dashboardLabel = [
        'owner' => 'Owner dashboard',
        'admin' => 'Admin dashboard',
        'kasir' => 'Kasir dashboard',
    ][$role] ?? 'Dashboard';
@endphp

<aside class="flex w-full flex-col bg-[#552919] text-white md:fixed md:inset-y-0 md:left-0 md:z-40 md:w-64 md:overflow-y-auto">
    <div class="border-b border-white/10 px-6 py-7">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#f2ede7]">
                <img
                    src="{{ asset('img/Nafil Cafe & Resto Logo.png') }}"
                    alt="Nafil"
                    class="h-10 w-10 object-contain"
                >
            </div>

            <div>
                <p class="font-bold">Nafil Cafe &amp; resto</p>
                <p class="text-xs text-white/60">{{ $dashboardLabel }}</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 space-y-1 px-4 py-6">
        @if ($role === 'owner')
            <a href="{{ route('owner.dashboard') }}#ringkasan"
               class="flex items-center gap-3 rounded-xl bg-white/10 px-4 py-3 text-sm font-semibold text-white">
                <span class="material-symbols-outlined text-xl" aria-hidden="true">dashboard</span>
                Ringkasan
            </a>

            <a href="{{ route('owner.dashboard') }}#transaksi"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-white/70 transition hover:bg-white/10 hover:text-white">
                <span class="material-symbols-outlined text-xl" aria-hidden="true">receipt_long</span>
                Riwayat transaksi
            </a>
        @elseif ($role === 'admin')
            <a href="{{ route('menus.index') }}"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition hover:bg-white/10 hover:text-white {{ request()->routeIs('menus.*') ? 'bg-white/10 text-white' : 'text-white/70' }}">
                <span class="material-symbols-outlined text-xl" aria-hidden="true">restaurant_menu</span>
                Kelola Menu
            </a>

            <a href="{{ route('categories.index') }}"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition hover:bg-white/10 hover:text-white {{ request()->routeIs('categories.*') ? 'bg-white/10 text-white' : 'text-white/70' }}">
                <span class="material-symbols-outlined text-xl" aria-hidden="true">category</span>
                Kelola Kategori
            </a>
        @elseif ($role === 'kasir')
            <a href="{{ route('kasir.index') }}"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition hover:bg-white/10 hover:text-white {{ request()->routeIs('kasir.*') ? 'bg-white/10 text-white' : 'text-white/70' }}">
                <span class="material-symbols-outlined text-xl" aria-hidden="true">receipt_long</span>
                Antrean
            </a>
        @endif
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