<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    @vite('resources/css/app.css')

    <title>Login | RestoPanel</title>
</head>
<body class="flex min-h-screen items-center justify-center bg-[#f2ede7] px-4 py-10 text-[#552919]">
    <main class="w-full max-w-md">
        <section class="overflow-hidden rounded-3xl border border-[#ab7843]/25 bg-white shadow-xl shadow-[#552919]/10">
            <div class="h-2 bg-gradient-to-r from-[#64120c] to-[#ab7843]"></div>

            <div class="p-8 sm:p-10">
                <div class="mb-3 text-center">
                    <img src="{{ asset('img/Nafil Cafe & Resto Logo.png') }}" alt="Logo Nafil Cafe & Resto" class="mx-auto mb-2 block h-25 w-25 object-contain">

                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#ab7843]">Nafil Cafe & Resto</p>
                    <h1 class="mt-2 text-2xl font-bold text-[#552919]">Selamat datang</h1>
                    <p class="mt-2 text-sm text-[#552919]/65">Masuk untuk melanjutkan ke dashboard.</p>
                </div>

                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-[#552919]">Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-xl text-[#ab7843]" aria-hidden="true">mail</span>
                            <input id="email" type="email" name="email" placeholder="nama@email.com" value="{{ old('email') }}" autocomplete="email" required class="w-full rounded-xl border border-[#ab7843]/35 bg-[#f2ede7]/40 py-3 pl-11 pr-4 text-sm text-[#552919] outline-none transition placeholder:text-[#552919]/40 focus:border-[#64120c] focus:ring-4 focus:ring-[#64120c]/10">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm font-medium text-[#64120c]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-[#552919]">Password</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-xl text-[#ab7843]" aria-hidden="true">lock</span>
                            <input id="password" type="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required class="w-full rounded-xl border border-[#ab7843]/35 bg-[#f2ede7]/40 py-3 pl-11 pr-4 text-sm text-[#552919] outline-none transition placeholder:text-[#552919]/40 focus:border-[#64120c] focus:ring-4 focus:ring-[#64120c]/10">
                        </div>
                    </div>

                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#64120c] py-3 text-sm font-semibold text-white shadow-md shadow-[#64120c]/15 transition hover:bg-[#552919] focus:outline-none focus:ring-4 focus:ring-[#64120c]/20">
                        <span class="material-symbols-outlined text-xl" aria-hidden="true">login</span>Masuk
                    </button>
                </form>

                <div class="mt-8 border-t border-[#ab7843]/20 pt-5 text-center">
                    <p class="text-xs text-[#552919]/55">Akses khusus untuk pengelola restoran</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>