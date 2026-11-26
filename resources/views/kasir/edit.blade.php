@extends('kasir.layout')

@section('konten')
    <a href="/kasir" class="text-caramel hover:underline">&larr; Kembali ke antrean</a>

    <h2 class="text-2xl font-bold mt-4">Edit Pesanan {{ $pesanan->kode_unik }}</h2>
    <p class="mb-6 text-espresso/70">
        {{ $pesanan->nama_pelanggan }} · Meja {{ $pesanan->no_meja }} · {{ $pesanan->metode_bayar }}
    </p>

    {{-- Pesan error validasi --}}
    @if ($errors->any())
        <div class="bg-white border-l-4 border-chestnut rounded-lg px-4 py-3 mb-6">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Tabel item --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <table class="w-full text-left text-sm">
            <thead class="bg-caramel/20 font-semibold">
                <tr>
                    <th class="px-4 py-3">Menu</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="px-4 py-3">Qty</th>
                    <th class="px-4 py-3">Subtotal</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesanan->details as $detail)
                    <tr class="border-b border-almond">
                        <td class="px-4 py-3">{{ $detail->menu->nama_menu }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $detail->qty }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <form action="/kasir/{{ $pesanan->id }}/item/{{ $detail->id }}" method="POST"
                                  onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="border border-chestnut text-chestnut rounded-lg px-3 py-1.5 hover:bg-chestnut hover:text-white">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-espresso/60">Belum ada item.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form tambah item --}}
    <form action="/kasir/{{ $pesanan->id }}/item" method="POST"
          class="bg-white rounded-xl shadow-sm p-4 mb-6 flex gap-3 items-end">
        @csrf
        <div class="flex-1">
            <label for="menu_id" class="block text-xs font-semibold mb-1">Tambah Item</label>
            <select name="menu_id" id="menu_id" required
                    class="w-full border border-caramel/40 rounded-lg px-3 py-2">
                <option value="">Pilih menu</option>
                @foreach ($menus as $menu)
                    <option value="{{ $menu->id }}">
                        {{ $menu->nama_menu }} — Rp {{ number_format($menu->harga, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="qty" class="block text-xs font-semibold mb-1">Qty</label>
            <input type="number" name="qty" id="qty" value="1" min="1" required
                   class="w-20 border border-caramel/40 rounded-lg px-3 py-2">
        </div>
        <button class="bg-chestnut text-white rounded-lg px-4 py-2">Tambah</button>
    </form>

     {{-- Kotak perkiraan (diisi oleh AJAX) --}}
    <div id="perkiraan" class="hidden border border-dashed border-caramel rounded-xl bg-caramel/10 p-4 mb-6 text-sm space-y-1">
        <p class="text-xs text-espresso/60">Perkiraan jika ditambahkan</p>
        <p>Total tagihan baru: <span id="perkiraan-total" class="font-semibold"></span></p>
        <p id="perkiraan-selisih" class="font-bold"></p>
    </div>

    {{-- Ringkasan --}}
    <div class="bg-white rounded-xl shadow-sm p-4 space-y-1 text-sm">
        <div class="flex justify-between">
            <span>Total item</span>
            <span>Rp {{ number_format($totalItem, 0, ',', '.') }}</span>
        </div>

        @if ($pesanan->metode_bayar == 'non-tunai')
            <div class="flex justify-between">
                <span>Kode transfer</span>
                <span>+{{ $kodeTransfer }}</span>
            </div>
        @endif

        <div class="flex justify-between font-semibold border-t border-caramel/20 pt-1">
            <span>Total tagihan baru</span>
            <span>Rp {{ number_format($totalBaru, 0, ',', '.') }}</span>
        </div>

        @if ($dibayar === null)
            <p class="text-espresso/60 pt-1">Belum dibayar. Pelanggan membayar total di atas di kasir.</p>
        @else
            <div class="flex justify-between">
                <span>Dibayar pelanggan</span>
                <span>Rp {{ number_format($dibayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold text-base border-t border-caramel/20 pt-1
                        {{ $selisih >= 0 ? 'text-green-700' : 'text-chestnut' }}">
                <span>{{ $selisih >= 0 ? 'Kelebihan Bayar' : 'Kurang Bayar' }}</span>
                <span>Rp {{ number_format(abs($selisih), 0, ',', '.') }}</span>
            </div>
        @endif
    </div>

    <script>
        const pilihMenu = document.getElementById('menu_id');
        const inputQty = document.getElementById('qty');
        const kotak = document.getElementById('perkiraan');
        const teksTotal = document.getElementById('perkiraan-total');
        const teksSelisih = document.getElementById('perkiraan-selisih');

        // 48519 → "Rp 48.519"
        function rupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }

        function hitungPerkiraan() {
            // Belum pilih menu atau qty kosong: sembunyikan kotak
            if (pilihMenu.value == '' || inputQty.value < 1) {
                kotak.classList.add('hidden');
                return;
            }

            // Tanya server di belakang layar
            fetch('/kasir/{{ $pesanan->id }}/hitung?menu_id=' + pilihMenu.value + '&qty=' + inputQty.value)
                .then(response => response.json())
                .then(data => {
                    teksTotal.textContent = rupiah(data.total_tagihan);

                    if (data.selisih === null) {
                        // Tunai belum bayar: tidak ada selisih
                        teksSelisih.textContent = '';
                    } else if (data.selisih >= 0) {
                        teksSelisih.textContent = 'Kelebihan Bayar: ' + rupiah(data.selisih);
                        teksSelisih.className = 'font-bold text-green-700';
                    } else {
                        teksSelisih.textContent = 'Kurang Bayar: ' + rupiah(Math.abs(data.selisih));
                        teksSelisih.className = 'font-bold text-chestnut';
                    }

                    kotak.classList.remove('hidden');
                });
        }

        // Jalankan setiap kali menu diganti atau qty diketik
        pilihMenu.addEventListener('change', hitungPerkiraan);
        inputQty.addEventListener('input', hitungPerkiraan);
    </script>
@endsection