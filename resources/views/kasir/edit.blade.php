@extends('kasir.layout')

@section('title', 'Edit Pesanan ' . $pesanan->kode_unik)

@section('content')
    <div class="rounded-xl bg-white shadow-sm p-6 space-y-6">

        {{-- Info pesanan --}}
        <div class="text-espresso/70">
            <span class="font-semibold text-espresso">{{ $pesanan->nama_pelanggan }}</span>
            &middot; Meja {{ $pesanan->no_meja }}
            &middot; {{ $pesanan->metode_bayar }}
        </div>

        {{-- Tabel item --}}
        <div class="overflow-x-auto rounded-lg border border-almond">
            <table class="w-full text-left text-sm">
                <thead class="bg-caramel/20 font-semibold">
                    <tr>
                        <th class="px-4 py-3">Menu</th>
                        <th class="px-4 py-3 text-right">Harga</th>
                        <th class="px-4 py-3 text-center">Qty</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="item-list">
                    @forelse ($pesanan->details as $detail)
                        <tr class="border-b border-almond" data-subtotal="{{ $detail->subtotal }}">
                            <td class="px-4 py-3">{{ $detail->menu->nama_menu }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">{{ $detail->qty }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('kasir.hapus-item', [$pesanan, $detail]) }}" method="POST"
                                      onsubmit="return confirm('Hapus item ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="rounded bg-chestnut/10 px-2 py-1 text-chestnut text-xs hover:bg-chestnut hover:text-white">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-espresso/60">Belum ada item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Form tambah item --}}
        <form action="{{ route('kasir.tambah-item', $pesanan) }}" method="POST"
              class="flex flex-wrap items-end gap-3">
            @csrf
            <div class="flex-1 min-w-[180px]">
                <label for="menu_id" class="block text-xs font-semibold mb-1">Tambah Item</label>
                <select name="menu_id" id="menu_id" required
                        class="w-full rounded-lg border border-caramel/40 px-3 py-2 text-sm focus:border-caramel focus:ring-1 focus:ring-caramel">
                    <option value="">— Pilih menu —</option>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}" data-harga="{{ $menu->harga }}">
                            {{ $menu->nama_menu }} — Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-20">
                <label for="qty" class="block text-xs font-semibold mb-1">Qty</label>
                <input type="number" name="qty" id="qty" value="1" min="1" required
                       class="w-full rounded-lg border border-caramel/40 px-3 py-2 text-sm text-center focus:border-caramel focus:ring-1 focus:ring-caramel">
            </div>
            <button type="submit"
                    class="rounded-lg bg-caramel px-4 py-2 text-sm text-white hover:bg-caramel/90">
                + Tambah
            </button>
        </form>

        @error('menu_id') <p class="text-chestnut text-xs">{{ $message }}</p> @enderror
        @error('qty') <p class="text-chestnut text-xs">{{ $message }}</p> @enderror

        {{-- Preview: simulasi harga jika item di form tambah ditambahkan --}}
        <div id="preview-tambah" class="hidden rounded-lg border border-dashed border-caramel/40 bg-caramel/5 p-3 text-sm text-espresso/80">
            Jika ditambahkan: <span id="preview-nama" class="font-semibold"></span>
            × <span id="preview-qty"></span>
            = <strong id="preview-subtotal"></strong>
            &rarr; Selisih menjadi: <strong id="preview-selisih"></strong>
        </div>

        {{-- Ringkasan selisih --}}
        @php
            $totalBaru = $pesanan->details->sum('subtotal');
            $kodeTransfer = $pesanan->total_tagihan % 1000;
            $totalDenganKode = $totalBaru + $kodeTransfer;
            $dibayar = $pesanan->total_dibayar ?? 0;
            $selisih = $dibayar - $totalDenganKode;
        @endphp
        <div id="ringkasan" class="rounded-lg bg-almond p-4 space-y-1 text-sm">
            <div class="flex justify-between">
                <span>Total item</span>
                <span id="total-item">Rp {{ number_format($totalBaru, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kode transfer</span>
                <span id="kode-transfer">+{{ $kodeTransfer }}</span>
            </div>
            <div class="flex justify-between font-semibold border-t border-caramel/20 pt-1">
                <span>Total tagihan baru</span>
                <span id="total-tagihan">Rp {{ number_format($totalDenganKode, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Dibayar pelanggan</span>
                <span id="dibayar">Rp {{ number_format($dibayar, 0, ',', '.') }}</span>
            </div>
            <div id="baris-selisih"
                 class="flex justify-between font-bold text-base pt-1 border-t border-caramel/20
                        {{ $selisih >= 0 ? 'text-green-700' : 'text-chestnut' }}">
                <span id="label-selisih">{{ $selisih >= 0 ? 'Kelebihan Bayar' : 'Kurang Bayar' }}</span>
                <span id="nilai-selisih">Rp {{ number_format(abs($selisih), 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Navigasi --}}
        <div class="flex justify-between pt-2">
            <a href="{{ route('kasir.index') }}"
               class="rounded-lg border border-caramel px-4 py-2 text-caramel hover:bg-caramel hover:text-white">
                &larr; Kembali
            </a>
        </div>
    </div>

    <script>
    (function () {
        const hitungUrl = @json(route('kasir.hitung', $pesanan));
        const menuSelect = document.getElementById('menu_id');
        const qtyInput = document.getElementById('qty');

        // Elemen ringkasan
        const elTotalItem = document.getElementById('total-item');
        const elTotalTagihan = document.getElementById('total-tagihan');
        const elLabelSelisih = document.getElementById('label-selisih');
        const elNilaiSelisih = document.getElementById('nilai-selisih');
        const elBarisSelisih = document.getElementById('baris-selisih');

        // Elemen preview
        const elPreview = document.getElementById('preview-tambah');
        const elPreviewNama = document.getElementById('preview-nama');
        const elPreviewQty = document.getElementById('preview-qty');
        const elPreviewSub = document.getElementById('preview-subtotal');
        const elPreviewSelisih = document.getElementById('preview-selisih');

        function formatRp(n) {
            return 'Rp ' + Math.abs(n).toLocaleString('id-ID');
        }

        // Ambil data aktual dari server lalu update ringkasan
        function refreshRingkasan() {
            fetch(hitungUrl)
                .then(r => r.json())
                .then(d => {
                    elTotalItem.textContent = formatRp(d.total_item);
                    elTotalTagihan.textContent = formatRp(d.total_tagihan);

                    if (d.selisih >= 0) {
                        elLabelSelisih.textContent = 'Kelebihan Bayar';
                        elBarisSelisih.classList.remove('text-chestnut');
                        elBarisSelisih.classList.add('text-green-700');
                    } else {
                        elLabelSelisih.textContent = 'Kurang Bayar';
                        elBarisSelisih.classList.remove('text-green-700');
                        elBarisSelisih.classList.add('text-chestnut');
                    }
                    elNilaiSelisih.textContent = formatRp(d.selisih);
                });
        }

        // Preview: simulasi selisih jika item dari form tambah ikut dihitung
        function updatePreview() {
            const opt = menuSelect.selectedOptions[0];
            const harga = parseInt(opt?.dataset.harga || 0);
            const qty = parseInt(qtyInput.value) || 0;

            if (!harga || !qty) {
                elPreview.classList.add('hidden');
                return;
            }

            const subtotalTambahan = harga * qty;
            elPreviewNama.textContent = opt.textContent.split('—')[0].trim();
            elPreviewQty.textContent = qty;
            elPreviewSub.textContent = formatRp(subtotalTambahan);

            // Hitung selisih baru berdasarkan data ringkasan + tambahan
            fetch(hitungUrl)
                .then(r => r.json())
                .then(d => {
                    const selisihBaru = d.selisih - subtotalTambahan;
                    const label = selisihBaru >= 0 ? 'Kelebihan Bayar' : 'Kurang Bayar';
                    elPreviewSelisih.textContent = label + ' ' + formatRp(selisihBaru);
                    elPreview.classList.remove('hidden');
                });
        }

        menuSelect.addEventListener('change', updatePreview);
        qtyInput.addEventListener('input', updatePreview);

        // Refresh ringkasan saat halaman dimuat (sinkronkan dengan DB)
        refreshRingkasan();
    })();
    </script>
@endsection
