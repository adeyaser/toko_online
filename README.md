# 🛍️ ShopVista - Aplikasi Toko Online Modern & Multi-Fitur

Aplikasi toko online (*e-commerce*) berbasis web yang responsif, cepat, dan kaya fitur, dibangun menggunakan **PHP (CodeIgniter 3)** dan **MySQL**. Dilengkapi dengan fitur unggulan manajemen produk & kategori, integrasi kurir pengiriman (ShopVista Express, JNE, J&T, SiCepat), pelacakan nomor resi, widget interaktif customer service WhatsApp, serta cetak struk label pengiriman standar resi thermal (100x165mm) berbasis **TCPDF**.

---

## ✨ Fitur Unggulan

### 🛒 Sisi Pelanggan (Customer Facing)
- **Katalog & Navigasi Responsif**: Tampilan modern dengan tata letak adaptif (optimal di layar desktop, tablet, dan smartphone).
- **Keranjang Belanja & Checkout Real-time**: Keranjang dinamis dengan kalkulasi otomatis subtotal dan ongkir.
- **Pilihan Kurir & Layanan Pengiriman**: Pilihan berbagai ekspedisi (ShopVista Express, JNE, J&T, SiCepat, Pickup Toko) dengan estimasi waktu tiba (ETD) dan kualifikasi Bebas Ongkir.
- **Metode Pembayaran**: Transfer Bank (dengan unggah bukti pembayaran) dan COD (*Cash on Delivery*).
- **Lacak Resi Pengiriman Live**: Cek status pelacakan paket pesanan secara real-time.
- **Floating WhatsApp Live Chat Widget**: Widget chat responsif untuk konsultasi produk langsung ke admin toko.
- **Newsletter & Lead Capture**: Fitur berlangganan promo toko untuk calon pembeli.

### ⚙️ Sisi Admin (Admin Dashboard)
- **Dashboard Statistik & Ringkasan**: Pantau total transaksi, status pesanan, dan tren penjualan.
- **Manajemen Pesanan (Orders)**: Verifikasi bukti transfer, pembaruan status pesanan (*Pending, Processing, Shipped, Delivered, Cancelled*), dan input nomor resi pengiriman.
- **Cetak Struk / Invoice Pengiriman (Real PDF via TCPDF)**:
  - Format kertas standar resi pengiriman thermal (**100mm × 165mm**).
  - Cetak massal (*bulk print*) atau per pesanan.
  - Barcode Code-128 otomatis.
  - Logo toko dan identitas toko dinamis langsung dari database pengaturan.
- **Cetak Layar Browser Presisi**: Dialog cetak layar yang dikunci pada ukuran fixed nota resi thermal 100x165mm.
- **Manajemen Produk & Kategori**: CRUD produk, penentuan produk unggulan (*featured*), stok, diskon, dan manajemen kategori.
- **Pengaturan Toko Fleksibel**: Nama toko, logo dinamis, informasi kontak, rekening bank, hingga integrasi API.

---

## 🛠️ Tech Stack

- **Backend**: PHP 7.x / 8.x, Framework CodeIgniter 3
- **Database**: MySQL / MariaDB (InnoDB, utf8mb4)
- **PDF Engine**: TCPDF (via Composer)
- **Frontend**: HTML5, Vanilla CSS3 (Custom Responsive Design System), JavaScript (ES6)
- **Icons & Barcode**: Feather Icons, JsBarcode

---

## 🚀 Panduan Instalasi Cepat

### 1. Prasyarat Sistem
- Web Server: Apache (XAMPP / Laragon / Nginx)
- PHP: Versi 7.3 ke atas (disarankan PHP 7.4 atau PHP 8.x) dengan ekstensi `mysqli`, `gd`, `mbstring` aktif.
- Database: MySQL 5.7+ atau MariaDB 10.4+
- Composer (opsional jika dependensi `vendor/` sudah disertakan)

### 2. Clone Repositori
```bash
git clone https://github.com/adeyaser/toko_online.git
```
Pindahkan folder ke direktori web server Anda (misal `c:/xampp/htdocs/toko_online`).

### 3. Import Database
1. Buka **phpMyAdmin** atau GUI Database Anda.
2. Buat database baru bernama `toko_online_db` (atau `toko_online`).
3. Import file database yang telah disediakan:
   ```text
   toko_online_db.sql
   ```

### 4. Konfigurasi Database
Buka file `application/config/database.php` dan sesuaikan kredensial database Anda:
```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'toko_online_db',
'dbdriver' => 'mysqli',
```

### 5. Akses Aplikasi
Buka peramban (browser) dan akses:
- **Toko (Customer)**: `http://localhost/toko_online/`
- **Portal Admin**: `http://localhost/toko_online/admin/`

---

## 🔐 Akun Default untuk Pengujian

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@shopvista.com` | `admin123` |
| **Customer** | `john@example.com` | `admin123` |

---

## 📄 Lisensi
Proyek ini dikembangkan untuk kebutuhan operasional toko online dan terbuka untuk pengembangan lebih lanjut.
