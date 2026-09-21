# Dokumentasi Proyek Toko Online (Tambahan)

## Tambahan Fitur Produk dan Pengiriman

---

## 7. Foto Produk dan Keranjang Belanja

### a. Foto Produk
- Setiap produk memiliki satu atau beberapa foto yang bisa diupload oleh admin.
- Foto disimpan di folder terstruktur pada server dan URL-nya tersimpan di database.
- Tampilan foto utama dan galeri thumbnail di halaman produk.
- Optimasi ukuran foto agar tidak memperlambat loading halaman.

### b. Keranjang Belanja
- Pelanggan bisa menambahkan produk beserta jumlah ke keranjang.
- Keranjang menyimpan data sementara (session atau database) termasuk foto produk, nama, dan harga.
- Tampilan keranjang memperlihatkan ringkasan produk dengan foto, jumlah, harga subtotal.
- Fitur update jumlah dan hapus produk dari keranjang.

---

## 8. Pengecekan Pengiriman dengan RajaOngkir (Gratis)

### a. Integrasi RajaOngkir
- Gunakan API RajaOngkir dengan paket yang menyediakan cek ongkos kirim gratis (Starter package).
- Input data alamat asal (alamat toko) dan alamat tujuan (alamat pelanggan).
- Pilih jenis layanan pengiriman (jne, pos, tiki).
- Sistem menampilkan estimasi ongkir dan lama pengiriman sebelum checkout.

### b. Proses dalam Checkout
- Setelah pelanggan isi alamat lengkap, otomatis sistem akan memanggil API RajaOngkir untuk cek ongkos kirim.
- Ongkir ditambahkan ke total harga checkout.
- Pelanggan melihat detail ongkos kirim dan estimasi waktu pengiriman.

### c. Manfaat dan Catatan
- Mengoptimalkan transparansi ongkir kepada pelanggan.
- Memudahkan pelanggan memilih layanan pengiriman paling cocok.
- Layanan gratis RajaOngkir cukup untuk kebutuhan pengecekan ongkir dasar.

---

## 9. Perubahan Database

Tambahkan tabel baru untuk menyimpan gambar produk dan alamat toko asal:

| Tabel            | Deskripsi                         | Kolom Kunci Penting                    |
|------------------|----------------------------------|--------------------------------------|
| `product_images` | Menyimpan satu atau beberapa gambar produk | id, product_id, image_url, is_main  |
| `store_info`     | Data toko termasuk alamat asal pengiriman | id, store_name, address, city_id     |

---

## 10. Alur Kerja Pengecekan Ongkos Kirim

1. Pelanggan masukkan alamat lengkap di halaman checkout.
2. Sistem panggil API RajaOngkir dengan data asal toko dan data tujuan pelanggan.
3. Sistem menerima response ongkos kirim dan estimasi waktu pengiriman.
4. Ongkir tampil di halaman checkout sebagai bagian dari total pembayaran.
5. Pelanggan konfirmasi pembayaran dan lanjut proses pemesanan.

---

Dengan tambahan fitur ini, toko online Anda akan lebih lengkap dan profesional dengan kemudahan pengelolaan foto produk, keranjang belanja interaktif, serta transparansi biaya pengiriman.

---

Jika Anda ingin, saya juga siap membantu membuat contoh kode atau panduan integrasi untuk RajaOngkir dan manajemen foto produk.

