@extends('kasir.layout')

@section('title', 'Antrean Validasi')

@section('content')
    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
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
                        <td class="px-4 py-3 space-x-2 whitespace-nowrap">
                            <button type="button"
                                onclick="document.getElementById('modal-{{ $pesanan->id }}').showModal()"
                                class="rounded-lg bg-chestnut px-3 py-1.5 text-white hover:bg-chestnut/90">
                                Lihat Bukti
                            </button>
                            <a href="{{ route('kasir.edit', $pesanan) }}"
                               class="inline-block rounded-lg border border-caramel px-3 py-1.5 text-caramel hover:bg-caramel hover:text-white">
                                Edit
                            </a>
                        </td>
                    </tr>

                    {{-- Modal Bukti Transfer --}}
                    <dialog id="modal-{{ $pesanan->id }}"
                            class="max-w-lg w-full rounded-xl p-0 backdrop:bg-espresso/70 m-auto">
                        <div class="bg-espresso text-almond flex items-center justify-between px-5 py-3">
                            <h3 class="font-semibold">Bukti Transfer — {{ $pesanan->kode_unik }}</h3>
                            <button onclick="this.closest('dialog').close()" class="text-xl leading-none hover:text-caramel">&times;</button>
                        </div>
                        <div class="p-5 space-y-4">
                            @if ($pesanan->bukti_transfer)
                                <img src="{{ asset('storage/' . $pesanan->bukti_transfer) }}"
                                     alt="Bukti transfer {{ $pesanan->kode_unik }}"
                                     class="w-full mx-auto max-h-[70vh] object-contain rounded-lg bg-almond">
                            @else
                                <p class="text-espresso/60 text-center py-8">Bukti transfer belum diunggah.</p>
                            @endif

                            <p class="text-center font-semibold text-chestnut text-lg">
                                Total: Rp {{ number_format($pesanan->total_tagihan, 0, ',', '.') }}
                            </p>

                            <div class="flex justify-end gap-3 pt-2">
                                <form action="{{ route('kasir.tolak', $pesanan) }}" method="POST"
                                      onsubmit="return confirm('Tolak bukti transfer ini? Status kembali ke menunggu pembayaran.')">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="rounded-lg border border-chestnut px-4 py-2 text-chestnut hover:bg-chestnut hover:text-white">
                                        Tolak
                                    </button>
                                </form>
                                <form action="{{ route('kasir.konfirmasi', $pesanan) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="rounded-lg bg-chestnut px-4 py-2 text-white hover:bg-chestnut/90">
                                        Konfirmasi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </dialog>
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

    @if (session('error'))
        <script>alert(@json(session('error')))</script>
    @endif
@endsection