# Alur Logika Modul Kasir (Anggota 2)

Sistem cafe — Laravel 13, MySQL (`nafileee`), Tailwind v4.

## 1. Tugas kasir (dari dok.docx + kesepakatan tim)

| Fitur | Isi |
|---|---|
| Dashboard antrean | Tabel pesanan non-tunai berstatus `menunggu_validasi` |
| Validasi bukti transfer | Modal gambar, tombol **Konfirmasi** (`diproses`) dan **Tolak** (`menunggu_pembayaran`) |
| Edit pesanan | Hapus / tambah item di `detail_pesanans`, total dihitung ulang |
| AJAX selisih bayar | Label "Kelebihan Bayar: Rp X" / "Kurang Bayar: Rp X" tanpa reload |
| Bayar tunai (tambahan, disetujui tim) | Antrean tunai `menunggu_pembayaran`, modal terima uang + kembalian, status jadi `diproses` |

Login dan middleware: bukan tugas kasir (Anggota 4).

## 2. Siklus status pesanan

```
Pelanggan checkout (Anggota 1)
 ├─ non-tunai: upload bukti ─► menunggu_validasi
 │                               ├─ Konfirmasi ─► diproses
 │                               └─ Tolak ─────► menunggu_pembayaran (upload ulang)
 └─ tunai ───────────────────► menunggu_pembayaran
                                 └─ Terima Bayar (uang >= total) ─► diproses
diproses ─► selesai (dapur / laporan Owner)
```

## 3. Tabel yang dipakai

```
categories 1 ──< menus 1 ──< detail_pesanans >── 1 pesanans
```

- `pesanans`: nama_pelanggan, no_meja, kode_unik, metode_bayar (`tunai`/`non-tunai`),
  bukti_transfer, total_tagihan, total_dibayar (null = belum bayar), status.
- `detail_pesanans`: pesanan_id, menu_id, qty, harga_satuan (harga dikunci), subtotal.
- `menus.is_available`: hanya menu tersedia yang bisa ditambahkan kasir.

## 4. Alur MVC

```
Browser ─URL─► routes/web.php ─► KasirController ─► Model ─► Database
                                       │
                                       ▼
                               resources/views/kasir/*.blade.php ─► Browser
```

| Route | Controller | Hasil |
|---|---|---|
| `GET /kasir` | `index` | Antrean transfer + antrean tunai |
| `PATCH /kasir/{pesanan}/konfirmasi` | `konfirmasi` | `menunggu_validasi` → `diproses` |
| `PATCH /kasir/{pesanan}/tolak` | `tolak` | `menunggu_validasi` → `menunggu_pembayaran` |
| `PATCH /kasir/{pesanan}/bayar-tunai` | `bayarTunai` | tunai `menunggu_pembayaran` → `diproses` |
| `GET /kasir/{pesanan}/edit` | `edit` | Halaman edit |
| `DELETE /kasir/{pesanan}/item/{detail}` | `hapusItem` | Hapus item + hitung ulang |
| `POST /kasir/{pesanan}/item` | `tambahItem` | Tambah item + hitung ulang |
| `GET /kasir/{pesanan}/hitung` | `hitung` | JSON untuk AJAX |

## 5. Aturan logika penting

1. **Kode transfer 3 digit**: hanya non-tunai. `kode = total_tagihan % 1000`. Tunai = 0.
2. **Hitung ulang total** setelah edit: `total_tagihan = jumlah subtotal + kode transfer`.
3. **Selisih bayar** = `total_dibayar − total_tagihan`. Positif = kelebihan, negatif = kurang.
   Kalau `total_dibayar` null (tunai belum bayar) → tidak ada selisih.
4. **Harga item** diambil dari tabel `menus`, bukan dari form (anti manipulasi).
5. **Pengaman status**: konfirmasi/tolak hanya untuk `menunggu_validasi`;
   bayar tunai hanya untuk tunai + `menunggu_pembayaran`.
6. **Pengaman item**: item yang dihapus harus milik pesanan di URL.
7. **Bayar tunai**: uang diterima ≥ total tagihan (dicek di browser dan server).
   Uang diterima & kembalian tidak disimpan (tidak ada kolom).

## 6. Tampilan

Palet (`resources/css/app.css` → `@theme`):
`almond #f2ede7` latar · `chestnut #64120c` tombol utama · `caramel #ab7843` aksen · `espresso #552919` teks/sidebar.

Layout: sidebar espresso + konten almond. Modal pakai `<dialog>` + `showModal()`, posisi tengah `m-auto`.

## 7. Batasan yang diketahui

- Belum ada login (Anggota 4).
- Halaman edit belum membatasi status (pesanan `selesai` masih bisa diedit lewat URL).
- Pengembalian kelebihan bayar tidak tercatat.
- Gambar bukti dibaca dari `storage/app/public/...` lewat `php artisan storage:link`.
