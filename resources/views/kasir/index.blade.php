@extends('kasir.layout')

@section('konten')

    {{-- Pesan error validasi (misal uang kurang) --}}
    @if ($errors->any())
        <div class="bg-white border-l-4 border-chestnut rounded-lg px-4 py-3 mb-6">
            {{ $errors->first() }}
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-6">Antrean Validasi Transfer</h2>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-caramel/20 font-semibold">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Pelanggan</th>
                    <th class="px-4 py-3">Meja</th>
                    <th class="px-4 py-3">Total Tagihan</th>
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesanans as $pesanan)
                    <tr class="border-b border-almond hover:bg-almond/50">
                        <td class="px-4 py-3 font-mono font-semibold">{{ $pesanan->kode_unik }}</td>
                        <td class="px-4 py-3">{{ $pesanan->nama_pelanggan }}</td>
                        <td class="px-4 py-3">{{ $pesanan->no_meja }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($pesanan->total_tagihan, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $pesanan->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3 space-x-2">
                            {{-- BARU: tombol membuka modal milik pesanan ini --}}
                            <button type="button"
                                    onclick="document.getElementById('modal-{{ $pesanan->id }}').showModal()"
                                    class="bg-chestnut text-white rounded-lg px-3 py-1.5">
                                Lihat Bukti
                            </button>
                            <a href="/kasir/{{ $pesanan->id }}/edit"    
                               class="inline-block border border-caramel text-caramel rounded-lg px-3 py-1.5 hover:bg-caramel hover:text-white">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-espresso/60">
                            Tidak ada pesanan menunggu validasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal bukti transfer: di luar tabel supaya HTML valid --}}
    @foreach ($pesanans as $pesanan)
        <dialog id="modal-{{ $pesanan->id }}" class="m-auto w-full max-w-md rounded-xl backdrop:bg-espresso/70">
            <div class="bg-espresso text-almond flex justify-between px-5 py-3">
                <h3 class="font-semibold">Bukti Transfer — {{ $pesanan->kode_unik }}</h3>
                <button type="button" onclick="this.closest('dialog').close()" class="text-xl">&times;</button>
            </div>

            <div class="p-5 space-y-3">
                <p>{{ $pesanan->nama_pelanggan }} · Meja {{ $pesanan->no_meja }}</p>
                <p>
                    Total tagihan:
                    <span class="font-bold text-chestnut">
                        Rp {{ number_format($pesanan->total_tagihan, 0, ',', '.') }}
                    </span>
                </p>

                <img src="{{ asset('storage/' . $pesanan->bukti_transfer) }}"
                     alt="Bukti transfer {{ $pesanan->kode_unik }}"
                     class="w-full max-h-[400px] object-contain rounded-lg bg-almond">

                <div class="flex justify-end gap-3 pt-2">
                    <form action="/kasir/{{ $pesanan->id }}/tolak" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="border border-caramel text-caramel rounded-lg px-4 py-2 hover:bg-caramel hover:text-white">
                            Tolak
                        </button>
                    </form>

                    <form action="/kasir/{{ $pesanan->id }}/konfirmasi" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="bg-chestnut text-white rounded-lg px-4 py-2">
                            Konfirmasi
                        </button>
                    </form>
                </div>
            </div>
        </dialog>
    @endforeach

    {{-- ===== Antrean Bayar Tunai ===== --}}
    <h2 class="text-2xl font-bold mt-10 mb-6">Bayar di Kasir (Tunai)</h2>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-caramel/20 font-semibold">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Pelanggan</th>
                    <th class="px-4 py-3">Meja</th>
                    <th class="px-4 py-3">Total Tagihan</th>
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tunais as $pesanan)
                    <tr class="border-b border-almond hover:bg-almond/50">
                        <td class="px-4 py-3 font-mono font-semibold">{{ $pesanan->kode_unik }}</td>
                        <td class="px-4 py-3">{{ $pesanan->nama_pelanggan }}</td>
                        <td class="px-4 py-3">{{ $pesanan->no_meja }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($pesanan->total_tagihan, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $pesanan->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3 space-x-2">
                            <button type="button"
                                    onclick="document.getElementById('tunai-{{ $pesanan->id }}').showModal()"
                                    class="bg-chestnut text-white rounded-lg px-3 py-1.5">
                                Terima Bayar
                            </button>
                            <a href="/kasir/{{ $pesanan->id }}/edit"
                               class="inline-block border border-caramel text-caramel rounded-lg px-3 py-1.5 hover:bg-caramel hover:text-white">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-espresso/60">
                            Tidak ada pesanan tunai menunggu pembayaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal bayar tunai: di luar tabel supaya HTML valid --}}
    @foreach ($tunais as $pesanan)
        <dialog id="tunai-{{ $pesanan->id }}" class="m-auto w-full max-w-sm rounded-xl backdrop:bg-espresso/70">
                        <div class="bg-espresso text-almond flex justify-between px-5 py-3">
                            <h3 class="font-semibold">Bayar Tunai — {{ $pesanan->kode_unik }}</h3>
                            <button type="button" onclick="this.closest('dialog').close()" class="text-xl">&times;</button>
                        </div>

                        <form action="/kasir/{{ $pesanan->id }}/bayar-tunai" method="POST" class="p-5 space-y-4">
                            @csrf
                            @method('PATCH')

                            <div class="flex justify-between">
                                <span>Total tagihan</span>
                                <span class="font-bold text-chestnut">
                                    Rp {{ number_format($pesanan->total_tagihan, 0, ',', '.') }}
                                </span>
                            </div>

                            <div>
                                <label for="uang-{{ $pesanan->id }}" class="block text-xs font-semibold mb-1">Uang diterima</label>
                                <input type="number" name="uang_diterima" id="uang-{{ $pesanan->id }}"
                                       min="{{ $pesanan->total_tagihan }}" required
                                       oninput="hitungKembalian(this, {{ $pesanan->total_tagihan }})"
                                       class="w-full border border-caramel/40 rounded-lg px-3 py-2">
                            </div>

                            <p class="kembalian font-semibold text-espresso/60">Masukkan nominal uang.</p>

                            <div class="flex justify-end gap-3 pt-2">
                                <button type="button" onclick="this.closest('dialog').close()"
                                        class="border border-caramel text-caramel rounded-lg px-4 py-2 hover:bg-caramel hover:text-white">
                                    Batal
                                </button>
                                <button type="submit" disabled
                                        class="bg-chestnut text-white rounded-lg px-4 py-2 disabled:opacity-40">
                                    Terima Bayar
                                </button>
                            </div>
                        </form>
        </dialog>
    @endforeach

    <script>
        // Dipanggil setiap kali angka di input "Uang diterima" berubah
        function hitungKembalian(input, total) {
            const form = input.closest('form');
            const teks = form.querySelector('.kembalian');
            const tombol = form.querySelector('[type=submit]');

            const uang = parseInt(input.value) || 0;
            const selisih = uang - total;

            if (selisih < 0) {
                teks.textContent = 'Uang kurang Rp ' + Math.abs(selisih).toLocaleString('id-ID');
                teks.className = 'kembalian font-semibold text-chestnut';
                tombol.disabled = true;
            } else {
                teks.textContent = 'Kembalian: Rp ' + selisih.toLocaleString('id-ID');
                teks.className = 'kembalian font-semibold text-green-700';
                tombol.disabled = false;
            }
        }
    </script>
@endsection