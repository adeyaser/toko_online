-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2026 at 11:05 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_online_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(500) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `link`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'Flash Sale Spectacular', 'Diskon hingga 70% untuk semua kategori. Promo terbatas!', 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1400&auto=format&fit=crop&q=85', '/product/catalog', 1, 1, '2026-09-20 20:48:19'),
(2, 'Koleksi Terbaru 2026', 'Temukan produk-produk terkini pilihan editor kami', 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1400&auto=format&fit=crop&q=85', '/product/catalog', 1, 2, '2026-09-20 20:48:19'),
(3, 'Gratis Ongkir', 'Belanja minimal Rp200.000 gratis ongkir ke seluruh Indonesia', 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=1400&auto=format&fit=crop&q=85', '/product/catalog', 1, 3, '2026-09-20 20:48:19');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `session_id`, `product_id`, `quantity`, `created_at`) VALUES
(1, NULL, '27lf06916km2rpp398tg4gv9dvateln6', 4, 2, '2026-09-20 21:00:52'),
(2, 1, NULL, 8, 1, '2026-09-20 21:18:51'),
(3, 1, NULL, 1, 2, '2026-09-20 21:22:29'),
(4, NULL, 'b2dq8d0k6tqv1da5581ta49p9upp32hf', 6, 1, '2026-09-20 21:28:21'),
(8, 1, NULL, 3, 5, '2026-09-20 22:01:53'),
(20, 2, NULL, 5, 13, '2026-09-21 13:13:47');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `icon` varchar(50) DEFAULT 'package',
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `image`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'Elektronik', 'elektronik', 'cpu', 'Gadget, laptop, smartphone, dan aksesoris elektronik terbaru', 'elektronik.jpg', 1, 1, '2026-09-20 20:48:19'),
(2, 'Fashion Pria', 'fashion-pria', 'user', 'Koleksi pakaian, sepatu, dan aksesoris pria terkini', 'fashion-pria.jpg', 1, 2, '2026-09-20 20:48:19'),
(3, 'Fashion Wanita', 'fashion-wanita', 'heart', 'Busana, tas, sepatu, dan aksesoris wanita trendy', 'fashion-wanita.jpg', 1, 3, '2026-09-20 20:48:19'),
(4, 'Makanan & Minuman', 'makanan-minuman', 'coffee', 'Snack, minuman, dan makanan kemasan pilihan', 'makanan-minuman.jpg', 1, 4, '2026-09-20 20:48:19'),
(5, 'Kesehatan & Kecantikan', 'kesehatan-kecantikan', 'star', 'Skincare, makeup, vitamin, dan produk kesehatan', 'kesehatan-kecantikan.jpg', 1, 5, '2026-09-20 20:48:19'),
(6, 'Rumah Tangga', 'rumah-tangga', 'home', 'Peralatan rumah, dekorasi, dan kebutuhan harian', 'rumah-tangga.jpg', 1, 6, '2026-09-20 20:48:19'),
(7, 'Olahraga', 'olahraga', 'activity', 'Peralatan olahraga, fitness, dan outdoor', 'olahraga.jpg', 1, 7, '2026-09-20 20:48:19'),
(8, 'Buku & Alat Tulis', 'buku-alat-tulis', 'book-open', 'Buku, novel, alat tulis, dan perlengkapan kantor', 'buku-alat-tulis.jpg', 1, 8, '2026-09-20 20:48:19');

-- --------------------------------------------------------

--
-- Table structure for table `couriers`
--

CREATE TABLE `couriers` (
  `id` int(11) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `courier_name` varchar(100) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `etd` varchar(50) DEFAULT '2-3 Hari',
  `cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `icon` varchar(50) DEFAULT 'truck',
  `badge` varchar(50) DEFAULT NULL,
  `is_free_eligible` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `couriers`
--

INSERT INTO `couriers` (`id`, `code`, `courier_name`, `service_name`, `description`, `etd`, `cost`, `icon`, `badge`, `is_free_eligible`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'shopvista_std', 'ShopVista Express', 'Standar / Reguler', 'Layanan pengiriman andalan toko, aman & terpercaya', '2-3 Hari', '15000.00', 'truck', 'Rekomendasi', 1, 1, 1, '2026-09-20 23:39:30', NULL),
(2, 'shopvista_express', 'ShopVista Express', 'Kilat / Express', 'Prioritas pengiriman kilat sampai esok hari', '1 Hari (Next Day)', '25000.00', 'zap', 'Cepat Sampai', 0, 1, 2, '2026-09-20 23:39:30', NULL),
(3, 'jnt_ez', 'J&T Express', 'EZ (Reguler)', 'Pengiriman ekspedisi J&T seluruh pelosok Indonesia', '2-3 Hari', '18000.00', 'package', 'Populer', 0, 1, 3, '2026-09-20 23:39:30', NULL),
(4, 'jne_reg', 'JNE', 'REG (Reguler)', 'Layanan reguler terpercaya dari kurir JNE', '2-4 Hari', '16000.00', 'box', '', 0, 1, 4, '2026-09-20 23:39:30', NULL),
(5, 'sicepat_reg', 'SiCepat', 'REG (Reguler)', 'Pengiriman cepat SiCepat Ekspres', '2-3 Hari', '17000.00', 'send', '', 0, 1, 5, '2026-09-20 23:39:30', NULL),
(6, 'store_pickup', 'Ambil di Toko', 'Self Pickup', 'Ambil pesanan langsung di toko fisik kami tanpa biaya', 'Hari Ini', '0.00', 'map-pin', 'Bebas Ongkir', 1, 1, 6, '2026-09-20 23:39:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `source` varchar(50) DEFAULT 'newsletter_home',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `email`, `name`, `source`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'pembeli.promo@gmail.com', '', 'newsletter_home', 1, '2026-09-21 07:47:40', NULL),
(2, 'realuser@example.com', '', 'test', 1, '2026-09-21 08:10:45', NULL),
(3, 'ade.yaser19@gmail.com', '', 'newsletter_home', 1, '2026-09-21 09:11:33', NULL),
(4, 'ade.yaser191@gmail.com', '', 'newsletter_home', 1, '2026-09-21 09:15:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lead_email_logs`
--

CREATE TABLE `lead_email_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `recipient_count` int(11) NOT NULL DEFAULT 0,
  `recipients` text DEFAULT NULL,
  `status` enum('sent','failed','logged') NOT NULL DEFAULT 'sent',
  `message` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lead_email_logs`
--

INSERT INTO `lead_email_logs` (`id`, `product_id`, `subject`, `recipient_count`, `recipients`, `status`, `message`, `created_at`) VALUES
(1, 1, '???? [ShopVista] Produk Baru: Smartphone Galaxy Ultra X1', 1, 'pembeli.promo@gmail.com', 'logged', 'Unable to send email using PHP mail(). Your server might not be configured to send mail using this method.Date: Mon, 21 Sep 2026 07:50:04 +0200\r\nFrom: &quot;ShopVista Store&quot; &lt;noreply@shopvista.com&gt;\r\nReturn-Path: &lt;noreply@shopvista.com&gt;\r\nBcc: pembeli.promo@gmail.com\r\nReply-To: &lt;noreply@shopvista.com&gt;\r\nUser-Agent: CodeIgniter\r\nX-Sender: noreply@shopvista.com\r\nX-Mailer: CodeIgniter\r\nX-Priority: 3 (Normal)\r\nMessage-ID: &lt;6ab0c58c8b690@shopvista.com&gt;\r\nMime-Version: 1.0\r\nContent-Type: multipart/alternative; boundary=&quot;B_ALT_6ab0c58c8b6a0&quot;\n', '2026-09-21 07:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `order_number` varchar(30) NOT NULL,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'bank_transfer',
  `payment_proof` varchar(255) DEFAULT NULL,
  `shipping_name` varchar(100) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_province` varchar(100) DEFAULT NULL,
  `shipping_postal` varchar(10) DEFAULT NULL,
  `shipping_courier` varchar(100) DEFAULT 'ShopVista Express',
  `tracking_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total`, `shipping_cost`, `grand_total`, `status`, `payment_method`, `payment_proof`, `shipping_name`, `shipping_phone`, `shipping_address`, `shipping_city`, `shipping_province`, `shipping_postal`, `shipping_courier`, `tracking_number`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'SV-20260920-E7CD07', '46497000.00', '0.00', '46497000.00', 'shipped', 'bank_transfer', NULL, 'John Customer', '081298765432', 'jl.mawar 1 kalibaru bekasi kp.rawa bambu rt.003 rw .005\r\nBekasi Kota', 'Bekasi Kota', 'Jawa Barat', '17133', 'JNE Express', 'JNE8829103982', 'dekat toko', '2026-09-20 22:14:47', '2026-09-20 17:39:01'),
(2, 2, 'SV-20260920-B1BC47', '69000.00', '15000.00', '84000.00', 'pending', 'bank_transfer', 'proof-SV-20260920-B1BC47-1789961371.png', 'John Customer', '081298765432', 'jl.mawar 1 kalibaru bekasi kp.rawa bambu rt.003 rw .005\r\nBekasi Kota', 'Bekasi Kota', 'Jawa Barat', '17133', 'J&T Express', NULL, '', '2026-09-20 22:59:43', '2026-09-21 10:29:31'),
(8, 2, 'SV-20260921-D45B27', '69000.00', '16000.00', '85000.00', 'pending', 'bank_transfer', NULL, 'John Customer', '081298765432', 'jl.mawar 1 kalibaru bekasi kp.rawa bambu rt.003 rw .005\r\nBekasi Kota', 'Kabupaten Kuantan Singingi', 'Riau', '17133', 'JNE (REG (Reguler))', NULL, '', '2026-09-21 09:59:27', '2026-09-21 05:16:24'),
(9, 2, 'SV-20260921-F9675D', '7499000.00', '17000.00', '7516000.00', 'pending', 'bank_transfer', NULL, 'John Customer', '081298765432', 'jl.mawar 1 kalibaru bekasi kp.rawa bambu rt.003 rw .005\r\nBekasi Kota', 'Kota Bekasi', 'Jawa Barat', '17133', 'SiCepat (REG (Reguler))', NULL, '', '2026-09-21 10:12:36', '2026-09-21 10:29:00'),
(10, 2, 'SV-20260921-431529', '7499000.00', '16000.00', '7515000.00', 'delivered', 'bank_transfer', 'proof-SV-20260921-431529-1789965295.png', 'John Customer', '081298765432', 'Bekasi ', 'Kota Bekasi', 'Jawa Barat', '', 'JNE (REG (Reguler))', 'JNE12345678', '', '2026-09-21 11:34:39', '2026-09-21 06:39:29');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `quantity`, `price`, `subtotal`) VALUES
(1, 1, 2, 'Laptop ProBook Elite 15', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&auto=format&fit=crop&q=80', 3, '15499000.00', '46497000.00'),
(2, 2, 18, 'Notebook Premium A5 Hardcover Dotted', 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?w=600&auto=format&fit=crop&q=80', 1, '69000.00', '69000.00'),
(8, 8, 18, 'Notebook Premium A5 Hardcover Dotted', 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?w=600&auto=format&fit=crop&q=80', 1, '69000.00', '69000.00'),
(9, 9, 1, 'Smartphone Galaxy Ultra X1', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&auto=format&fit=crop&q=80', 1, '7499000.00', '7499000.00'),
(10, 10, 1, 'Smartphone Galaxy Ultra X1', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&auto=format&fit=crop&q=80', 1, '7499000.00', '7499000.00');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'file-text',
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `icon`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Tentang Kami', 'tentang-kami', '<h3>Selamat Datang di ShopVista!</h3>\n<p><strong>ShopVista</strong> adalah platform belanja online modern yang berdedikasi untuk menghadirkan pengalaman berbelanja digital yang praktis, cepat, aman, dan menyenangkan bagi seluruh masyarakat Indonesia.</p>\n\n<h4>Visi & Misi Kami</h4>\n<ul>\n    <li><strong>Visi:</strong> Menjadi destinasi e-commerce pilihan utama di Indonesia dengan mengedepankan kepuasan pelanggan, kualitas produk, dan integritas transaksi.</li>\n    <li><strong>Misi:</strong> Menyediakan beragam pilihan produk kebutuhan harian, elektronik, fashion, hingga gaya hidup dengan harga yang bersaing serta jangkauan pengiriman ke seluruh pelosok tanah air.</li>\n</ul>\n\n<h4>Nilai-Nilai Utama ShopVista</h4>\n<ol>\n    <li><strong>100% Produk Berkualitas:</strong> Kami memastikan seluruh produk yang dikirimkan telah melalui seleksi dan pengecekan mutu yang ketat.</li>\n    <li><strong>Keamanan Transaksi:</strong> Data dan privasi Anda terenkripsi aman, didukung metode pembayaran transfer bank dan verifikasi terpercaya.</li>\n    <li><strong>Layanan Cepat & Tanggap:</strong> Tim Customer Care kami siap membantu segala pertanyaan dan kebutuhan belanja Anda melalui WhatsApp Live Chat.</li>\n    <li><strong>Pengiriman Luas & Fleksibel:</strong> Didukung berbagai pilihan kurir reguler, ekspres, hingga promo Bebas Ongkir ke berbagai kota.</li>\n</ol>\n\n<p>Terima kasih telah mempercayakan kebutuhan belanja Anda bersama ShopVista. Selamat menikmati pengalaman belanja online masa kini!</p>', 'Tentang Kami - ShopVista Indonesia', 'Kenali ShopVista lebih dekat, platform belanja online modern dan terpercaya di Indonesia.', 'info', 1, 1, '2026-09-20 18:50:53', '2026-09-20 18:50:53'),
(2, 'Cara Belanja', 'cara-belanja', '<h3>Panduan Berbelanja di ShopVista</h3>\n<p>Belanja di ShopVista sangat mudah dan hanya memerlukan beberapa langkah sederhana:</p>\n\n<div class=\"step-guide\" style=\"display:flex;flex-direction:column;gap:1.5rem;margin:1.5rem 0;\">\n    <div style=\"background:#F8FAFC;border-left:4px solid #4F46E5;padding:1rem 1.25rem;border-radius:0 8px 8px 0;\">\n        <h4 style=\"margin:0 0 6px;color:#1E293B;\">Langkah 1: Temukan Produk Pilihan Anda</h4>\n        <p style=\"margin:0;color:#475569;\">Gunakan menu <strong>Katalog</strong>, kategori produk, atau kotak pencarian di bagian atas untuk menemukan barang yang Anda inginkan. Klik pada produk untuk melihat rincian spesifikasi, harga, dan foto produk.</p>\n    </div>\n\n    <div style=\"background:#F8FAFC;border-left:4px solid #4F46E5;padding:1rem 1.25rem;border-radius:0 8px 8px 0;\">\n        <h4 style=\"margin:0 0 6px;color:#1E293B;\">Langkah 2: Masukkan ke Keranjang</h4>\n        <p style=\"margin:0;color:#475569;\">Tentukan jumlah barang yang ingin dibeli, lalu tekan tombol <strong>+ Keranjang</strong> atau <strong>Beli Sekarang</strong> untuk langsung melanjutkan ke halaman checkout.</p>\n    </div>\n\n    <div style=\"background:#F8FAFC;border-left:4px solid #4F46E5;padding:1rem 1.25rem;border-radius:0 8px 8px 0;\">\n        <h4 style=\"margin:0 0 6px;color:#1E293B;\">Langkah 3: Pilih Alamat & Jasa Pengiriman</h4>\n        <p style=\"margin:0;color:#475569;\">Lengkapi alamat pengiriman dengan benar. Pilih opsi kurir pengiriman (ShopVista Standar, Express, J&T, JNE, SiCepat, atau Ambil di Toko). Nikmati promo <strong>Gratis Ongkir</strong> apabila belanjaan Anda memenuhi syarat promo!</p>\n    </div>\n\n    <div style=\"background:#F8FAFC;border-left:4px solid #4F46E5;padding:1rem 1.25rem;border-radius:0 8px 8px 0;\">\n        <h4 style=\"margin:0 0 6px;color:#1E293B;\">Langkah 4: Lakukan Pembayaran & Upload Bukti</h4>\n        <p style=\"margin:0;color:#475569;\">Transfer total pembayaran sesuai nominal pesanan ke rekening resmi toko yang tertera. Buka menu <strong>Profil > Riwayat Pesanan</strong> untuk mengunggah bukti transfer Anda.</p>\n    </div>\n\n    <div style=\"background:#F8FAFC;border-left:4px solid #4F46E5;padding:1rem 1.25rem;border-radius:0 8px 8px 0;\">\n        <h4 style=\"margin:0 0 6px;color:#1E293B;\">Langkah 5: Pantau Pesanan & Lacak Resi</h4>\n        <p style=\"margin:0;color:#475569;\">Setelah pembayaran terverifikasi, pesanan akan segera dikemas dan dikirimkan. Anda dapat melacak posisi paket secara live melalui tombol <strong>Lacak Ekspedisi Live</strong>.</p>\n    </div>\n</div>\n\n<p>Jika Anda mengalami kendala saat bertransaksi, silakan hubungi Customer Service kami melalui tombol WhatsApp yang tersedia di pojok layar.</p>', 'Panduan Cara Belanja Mudah di ShopVista', 'Langkah mudah dan praktis berbelanja di ShopVista dari memilih produk hingga pesanan tiba di rumah.', 'shopping-bag', 1, 2, '2026-09-20 18:50:53', '2026-09-20 18:50:53'),
(3, 'Kebijakan Privasi', 'kebijakan-privasi', '<h3>Kebijakan Privasi ShopVista</h3>\n<p>Privasi Anda merupakan prioritas mutlak bagi kami. Kebijakan Privasi ini menjelaskan bagaimana <strong>ShopVista</strong> mengumpulkan, mengelola, melindungi, dan menggunakan informasi pribadi yang Anda berikan saat menggunakan situs web kami.</p>\n\n<h4>1. Informasi yang Kami Kumpulkan</h4>\n<p>Kami hanya mengumpulkan data yang diperlukan untuk memproses pesanan dan meningkatkan kenyamanan berbelanja Anda, mencakup:</p>\n<ul>\n    <li>Nama lengkap dan alamat email.</li>\n    <li>Nomor telepon / WhatsApp untuk konfirmasi pesanan dan koordinasi pengiriman oleh kurir.</li>\n    <li>Alamat lengkap pengiriman barang (kota, provinsi, kode pos).</li>\n    <li>Catatan riwayat transaksi dan status pembayaran.</li>\n</ul>\n\n<h4>2. Penggunaan Informasi</h4>\n<p>Informasi yang terkumpul digunakan untuk:</p>\n<ul>\n    <li>Memproses dan mengirimkan pesanan Anda secara tepat sasaran.</li>\n    <li>Mengirimkan konfirmasi status transaksi, invoice, dan nomor resi pengiriman.</li>\n    <li>Merespons pertanyaan atau bantuan melalui Customer Support WhatsApp.</li>\n    <li>Mencegah aktivitas penipuan dan menjaga keamanan ekosistem toko online kami.</li>\n</ul>\n\n<h4>3. Perlindungan & Keamanan Data</h4>\n<p>Kami menerapkan standar keamanan teknis dan prosedur organisasi yang ketat untuk mencegah akses tanpa izin, perubahan, maupun penyalahgunaan data pribadi Anda. Kata sandi akun pelanggan disimpan menggunakan enkripsi satu arah (hashing) yang aman.</p>\n\n<h4>4. Pembagian Data kepada Pihak Ketiga</h4>\n<p>ShopVista <strong>TIDAK PERNAH</strong> menjual, menyewakan, atau memperdagangkan data pribadi Anda kepada pihak lain. Kami hanya membagikan informasi pengiriman (nama, alamat, telepon) kepada mitra logistik/kurir pengiriman yang sah demi kelancaran pengantaran barang ke alamat Anda.</p>\n\n<p>Jika Anda memiliki pertanyaan seputar kebijakan privasi ini, Anda dapat menghubungi kami melalui kontak resmi yang tercantum di halaman ini.</p>', 'Kebijakan Privasi - Perlindungan Data Pelanggan ShopVista', 'Pelajari bagaimana ShopVista menjaga dan melindungi kerahasiaan data pribadi Anda.', 'shield', 1, 3, '2026-09-20 18:50:53', '2026-09-20 18:50:53'),
(4, 'Syarat & Ketentuan', 'syarat-ketentuan', '<h3>Syarat & Ketentuan Penggunaan</h3>\n<p>Selamat datang di ShopVista. Harap membaca Syarat & Ketentuan ini dengan seksama sebelum melakukan transaksi belanja online pada situs kami. Dengan melakukan pemesanan, Anda dianggap telah memahami dan menyetujui seluruh ketentuan di bawah ini.</p>\n\n<h4>1. Akun Pengguna</h4>\n<ul>\n    <li>Pengguna wajib memberikan informasi data diri yang akurat dan lengkap saat mendaftar maupun melakukan pemesanan.</li>\n    <li>Pengguna bertanggung jawab penuh atas kerahasiaan kata sandi dan keamanan akun masing-masing.</li>\n</ul>\n\n<h4>2. Pemesanan & Ketersediaan Produk</h4>\n<ul>\n    <li>Semua pesanan tergantung pada ketersediaan stok barang. Jika terjadi kekosongan stok mendadak, tim kami akan segera menginformasikan kepada pembeli melalui WhatsApp atau email untuk solusi alternatif atau pengembalian dana penuh.</li>\n    <li>Harga produk yang tertera adalah harga resmi saat transaksi dibuat dan dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya.</li>\n</ul>\n\n<h4>3. Pembayaran & Konfirmasi</h4>\n<ul>\n    <li>Pembeli wajib menyelesaikan transfer pembayaran dalam batas waktu 1x24 jam setelah pesanan dibuat. Pesanan yang tidak dibayar dalam batas waktu tersebut dapat dibatalkan secara otomatis oleh sistem.</li>\n    <li>Harap mengunggah bukti pembayaran yang valid pada halaman Detail Pesanan agar proses verifikasi dapat diselesaikan oleh tim admin.</li>\n</ul>\n\n<h4>4. Pengiriman & Penerimaan</h4>\n<ul>\n    <li>Pengiriman dilakukan melalui kurir resmi yang dipilih saat checkout. Estimasi waktu pengiriman (ETD) adalah perkiraan dari pihak kurir dan dapat dipengaruhi oleh kondisi cuaca maupun kendala operasional logistik di lapangan.</li>\n    <li>Pembeli disarankan merekam video unboxing (buka paket tanpa jeda) saat pertama kali menerima paket dari kurir sebagai bukti sah apabila terjadi kerusakan atau ketidaksesuaian barang.</li>\n</ul>\n\n<h4>5. Kebijakan Retur & Pengembalian Dana</h4>\n<ul>\n    <li>Klaim retur atau penukaran barang dapat diajukan maksimal 2x24 jam setelah paket diterima menurut data pelacakan kurir.</li>\n    <li>Klaim wajib menyertakan video unboxing dan nomor pesanan yang jelas.</li>\n</ul>', 'Syarat & Ketentuan Layanan Belanja ShopVista', 'Ketentuan dan aturan penggunaan layanan belanja online pada platform ShopVista.', 'file-text', 1, 4, '2026-09-20 18:50:53', '2026-09-20 18:50:53');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(280) NOT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `short_desc` varchar(500) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` text DEFAULT NULL,
  `weight` int(11) DEFAULT 0 COMMENT 'gram',
  `location` varchar(100) DEFAULT 'Jakarta Pusat',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `price`, `sale_price`, `stock`, `description`, `short_desc`, `image`, `gallery`, `weight`, `location`, `is_featured`, `is_active`, `views`, `created_at`, `updated_at`) VALUES
(1, 1, 'Smartphone Galaxy Ultra X1', 'smartphone-galaxy-ultra-x1', '8999000.00', '7499000.00', 43, 'Smartphone flagship terbaru dengan layar AMOLED 6.8 inch, kamera 200MP, baterai 5000mAh, RAM 12GB, dan penyimpanan 256GB. Dilengkapi dengan prosesor terbaru untuk performa gaming dan multitasking tanpa hambatan.', 'Smartphone flagship layar AMOLED 6.8\", kamera 200MP, RAM 12GB', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&auto=format&fit=crop&q=80', NULL, 220, 'Jakarta Barat', 1, 1, 354, '2026-09-20 20:48:19', '2026-09-21 13:09:51'),
(2, 1, 'Laptop ProBook Elite 15', 'laptop-probook-elite-15', '15499000.00', NULL, 22, 'Laptop premium untuk profesional dengan layar IPS 15.6 inch Full HD, prosesor Intel Core i7 Gen 13, RAM 16GB DDR5, SSD 512GB NVMe, dan kartu grafis dedicated. Bobot ringan hanya 1.7kg.', 'Laptop Intel i7 Gen 13, RAM 16GB, SSD 512GB, layar 15.6\" FHD', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&auto=format&fit=crop&q=80', NULL, 1700, 'Jakarta Selatan', 1, 1, 217, '2026-09-20 20:48:19', '2026-09-20 22:15:06'),
(3, 1, 'TWS Earbuds Pro Max', 'tws-earbuds-pro-max', '1299000.00', '899000.00', 100, 'True Wireless Stereo earbuds dengan Active Noise Cancellation, driver 12mm, Bluetooth 5.3, baterai hingga 36 jam dengan case, IPX5 water resistant. Suara bass yang powerful dan jernih.', 'TWS ANC, Bluetooth 5.3, 36 jam baterai, IPX5', 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=600&auto=format&fit=crop&q=80', NULL, 55, 'Surabaya', 1, 1, 192, '2026-09-20 20:48:19', '2026-09-20 22:15:06'),
(4, 1, 'Smartwatch FitPro Ultra', 'smartwatch-fitpro-ultra', '2499000.00', '1999000.00', 75, 'Smartwatch premium dengan layar AMOLED 1.9 inch always-on display, GPS built-in, monitor detak jantung & SpO2, 100+ mode olahraga, baterai 14 hari, water resistant 5ATM.', 'Smartwatch AMOLED 1.9\", GPS, SpO2, 14 hari baterai', 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=600&auto=format&fit=crop&q=80', NULL, 68, 'Bandung', 1, 1, 269, '2026-09-20 20:48:19', '2026-09-20 22:15:06'),
(5, 2, 'Kemeja Slim Fit Premium Oxford', 'kemeja-slim-fit-premium-oxford', '359000.00', '289000.00', 200, 'Kemeja pria slim fit bahan Oxford premium, tersedia dalam berbagai warna. Jahitan rapi, kancing berkualitas, cocok untuk formal maupun casual. Bahan adem dan nyaman dipakai seharian.', 'Kemeja slim fit bahan Oxford premium, nyaman & stylish', 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&auto=format&fit=crop&q=80', NULL, 250, 'Tangerang', 1, 1, 165, '2026-09-20 20:48:19', '2026-09-21 13:22:43'),
(6, 2, 'Sneakers Urban Runner V2', 'sneakers-urban-runner-v2', '599000.00', '449000.00', 150, 'Sepatu sneakers pria dengan desain modern dan sporty. Sol EVA ultra ringan, upper mesh breathable, insole memory foam untuk kenyamanan maksimal. Cocok untuk daily wear dan olahraga ringan.', 'Sneakers sporty, sol EVA ringan, memory foam insole', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80', NULL, 350, 'Semarang', 0, 1, 99, '2026-09-20 20:48:19', '2026-09-20 22:15:06'),
(7, 3, 'Tas Tote Bag Leather Look', 'tas-tote-bag-leather-look', '299000.00', '249000.00', 120, 'Tas tote bag wanita dengan bahan leather look premium. Desain minimalis dan elegan, kompartemen luas, dilengkapi inner pocket dan magnetic closure. Cocok untuk kerja dan hangout.', 'Tote bag leather look, minimalis & elegan', 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=600&auto=format&fit=crop&q=80', NULL, 400, 'Jakarta Pusat', 1, 1, 134, '2026-09-20 20:48:19', '2026-09-20 21:26:04'),
(8, 3, 'Dress Floral Summer Collection', 'dress-floral-summer-collection', '459000.00', NULL, 80, 'Dress wanita motif floral koleksi summer terbaru. Bahan chiffon lembut dan adem, cutting A-line flattering, tersedia size S-XL. Sempurna untuk acara casual dan semi formal.', 'Dress floral chiffon, cutting A-line, size S-XL', 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600&auto=format&fit=crop&q=80', NULL, 200, 'Jakarta Barat', 0, 1, 89, '2026-09-20 20:48:19', '2026-09-21 13:04:50'),
(9, 4, 'Kopi Arabica Specialty Blend 250g', 'kopi-arabica-specialty-blend-250g', '125000.00', '99000.00', 300, 'Kopi arabica specialty grade dari pegunungan Gayo, Aceh. Single origin, roasting medium-dark, notes: chocolate, caramel, citrus. Freshly roasted, dikemas dalam valve bag untuk menjaga kesegaran.', 'Kopi arabica Gayo, specialty grade, medium-dark roast', 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&auto=format&fit=crop&q=80', NULL, 280, 'Jakarta Selatan', 1, 1, 201, '2026-09-20 20:48:19', '2026-09-20 22:15:06'),
(10, 4, 'Teh Matcha Premium Grade 100g', 'teh-matcha-premium-grade-100g', '189000.00', NULL, 150, 'Teh matcha premium grade dari Uji, Jepang. Warna hijau cerah, rasa umami yang kaya, cocok untuk latte, smoothie, atau baking. Dikemas dalam tin container kedap udara.', 'Matcha premium grade Uji, Jepang, 100g tin', 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=600&auto=format&fit=crop&q=80', NULL, 130, 'Surabaya', 0, 1, 112, '2026-09-20 20:48:19', '2026-09-20 22:15:06'),
(11, 5, 'Serum Vitamin C 20% + Hyaluronic Acid', 'serum-vitamin-c-20-hyaluronic-acid', '249000.00', '199000.00', 200, 'Serum wajah dengan Vitamin C 20% dan Hyaluronic Acid untuk mencerahkan, melembapkan, dan anti-aging. Formula ringan, cepat menyerap, cocok untuk semua jenis kulit. Dermatologically tested.', 'Serum Vitamin C 20% + HA, brightening & hydrating', 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=600&auto=format&fit=crop&q=80', NULL, 50, 'Bandung', 1, 1, 348, '2026-09-20 20:48:19', '2026-09-21 14:39:12'),
(12, 5, 'Sunscreen SPF50+ PA++++ 50ml', 'sunscreen-spf50-pa-50ml', '179000.00', '149000.00', 250, 'Sunscreen dengan perlindungan SPF50+ PA++++ broad spectrum. Tekstur ringan, tidak lengket, tidak white cast. Mengandung Centella Asiatica dan Niacinamide untuk perlindungan dan perawatan kulit.', 'Sunscreen SPF50+ ringan, tidak white cast', 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=600&auto=format&fit=crop&q=80', NULL, 70, 'Tangerang', 0, 1, 279, '2026-09-20 20:48:19', '2026-09-20 22:15:06'),
(13, 6, 'Diffuser Aromatherapy Kayu + 3 Essential Oil', 'diffuser-aromatherapy-kayu-3-essential-oil', '399000.00', '329000.00', 60, 'Diffuser aromatherapy dengan body kayu natural, kapasitas 300ml, 7 warna LED, timer otomatis. Bonus 3 botol essential oil (lavender, eucalyptus, peppermint). Tenang dan nyaman.', 'Diffuser kayu 300ml + 3 essential oil gratis', 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&auto=format&fit=crop&q=80', NULL, 500, 'Semarang', 1, 1, 167, '2026-09-20 20:48:19', '2026-09-20 22:15:06'),
(14, 6, 'Set Organizer Bambu 5 in 1', 'set-organizer-bambu-5-in-1', '259000.00', NULL, 90, 'Set organizer bambu multifungsi 5 in 1: tissue holder, remote holder, phone stand, pen holder, dan tray. Desain Scandinavian minimalis, ramah lingkungan, cocok untuk meja kerja dan ruang tamu.', 'Organizer bambu 5in1, Scandinavian style', 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=600&auto=format&fit=crop&q=80', NULL, 600, 'Jakarta Pusat', 0, 1, 89, '2026-09-20 20:48:19', '2026-09-20 21:26:04'),
(15, 7, 'Yoga Mat Premium TPE 6mm', 'yoga-mat-premium-tpe-6mm', '349000.00', '279000.00', 100, 'Matras yoga premium bahan TPE eco-friendly, tebal 6mm, anti-slip double side, ringan dan mudah dibawa. Dilengkapi tali pengikat. Ukuran 183x61cm, tersedia dalam 5 warna pastel.', 'Yoga mat TPE 6mm, anti-slip, eco-friendly', 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=600&auto=format&fit=crop&q=80', NULL, 800, 'Jakarta Barat', 0, 1, 76, '2026-09-20 20:48:19', '2026-09-20 22:15:06'),
(16, 7, 'Resistance Band Set 5 Level', 'resistance-band-set-5-level', '149000.00', '119000.00', 180, 'Set resistance band 5 level ketebalan (extra light hingga extra heavy). Bahan latex premium, tahan lama, dilengkapi door anchor, ankle strap, dan carry bag. Ideal untuk home workout.', 'Resistance band 5 level + aksesoris lengkap', 'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=600&auto=format&fit=crop&q=80', NULL, 350, 'Jakarta Selatan', 1, 1, 144, '2026-09-20 20:48:19', '2026-09-20 22:15:06'),
(17, 8, 'Buku \"Atomic Habits\" - James Clear', 'buku-atomic-habits-james-clear', '99000.00', NULL, 200, 'Buku bestseller \"Atomic Habits\" karya James Clear. Panduan praktis untuk membangun kebiasaan baik dan menghilangkan kebiasaan buruk. Edisi terjemahan Bahasa Indonesia, soft cover.', 'Buku Atomic Habits, terjemahan Indonesia', 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&auto=format&fit=crop&q=80', NULL, 300, 'Surabaya', 0, 1, 235, '2026-09-20 20:48:19', '2026-09-21 15:22:29'),
(18, 8, 'Notebook Premium A5 Hardcover Dotted', 'notebook-premium-a5-hardcover-dotted', '89000.00', '69000.00', 248, 'Notebook premium A5 dengan cover hardbound, 200 halaman dotted 100gsm (fountain pen friendly), bookmark ribbon, back pocket, dan elastic closure. Cocok untuk bullet journal.', 'Notebook A5 dotted 200hal, hardcover premium', 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?w=600&auto=format&fit=crop&q=80', NULL, 280, 'Bandung', 0, 1, 100, '2026-09-20 20:48:19', '2026-09-21 09:59:27');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `rating` tinyint(1) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `is_approved`, `created_at`) VALUES
(1, 1, 2, 5, 'Smartphone luar biasa! Kamera sangat jernih dan baterai tahan seharian. Worth it banget!', 1, '2026-09-20 20:48:19'),
(2, 1, 2, 4, 'Performa cepat, layar sangat tajam. Hanya saja agak berat di tangan.', 1, '2026-09-20 20:48:19'),
(3, 3, 2, 5, 'Earbuds terbaik di range harga ini. ANC-nya efektif banget, bass powerful.', 1, '2026-09-20 20:48:19'),
(4, 5, 2, 5, 'Bahan Oxford-nya premium, jahitan rapi, fitting pas. Sangat recommended!', 1, '2026-09-20 20:48:19'),
(5, 9, 2, 5, 'Kopinya mantap! Aroma kuat, rasa balanced. Best seller emang gak bohong.', 1, '2026-09-20 20:48:19'),
(6, 11, 2, 5, 'Serum ini game changer! Kulit jadi cerah dan glowing setelah 2 minggu pemakaian.', 1, '2026-09-20 20:48:19'),
(7, 13, 2, 4, 'Diffuser-nya bagus, aroma terapi memang bikin relax. LED-nya cantik.', 1, '2026-09-20 20:48:19');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'store_name', 'ShopVista'),
(2, 'store_tagline', 'Belanja Modern, Harga Terjangkau'),
(3, 'store_email', 'hello@shopvista.com'),
(4, 'store_phone', '0812-3456-7890'),
(5, 'store_address', 'Jl. Sudirman No. 123, Jakarta Pusat'),
(6, 'store_logo', NULL),
(7, 'store_favicon', NULL),
(8, 'currency', 'IDR'),
(9, 'free_shipping_min', '50000'),
(10, 'shipping_cost', '15000'),
(11, 'bank_name', 'Bank BCA'),
(12, 'bank_account', '123-456-7890'),
(13, 'bank_holder', 'PT ShopVista Indonesia'),
(14, 'whatsapp', '081298765432'),
(15, 'instagram', ''),
(16, 'facebook', ''),
(17, 'meta_title', ''),
(18, 'meta_description', ''),
(19, 'meta_keywords', ''),
(20, 'rapidapi_key', ''),
(21, 'rapidapi_host', 'cek-resi-cek-ongkir.p.rapidapi.com'),
(22, 'shipping_mode', 'hybrid'),
(23, 'rajaongkir_api_key', ''),
(24, 'rajaongkir_origin', '17601'),
(25, 'whatsapp_enabled', '1'),
(26, 'whatsapp_cs_name', 'Layanan Pelanggan ShopVista'),
(27, 'whatsapp_cs_status', 'Online • Respon Cepat'),
(28, 'whatsapp_message', 'Halo Admin ShopVista, saya mau tanya stok produk...'),
(29, 'whatsapp_position', 'bottom-right'),
(30, 'tiktok', NULL),
(31, 'shopee', NULL),
(32, 'lazada', NULL),
(33, 'tokopedia', NULL),
(34, 'smtp_host', 'smtp.gmail.com'),
(35, 'smtp_port', '587'),
(36, 'smtp_user', ''),
(37, 'smtp_pass', ''),
(38, 'smtp_crypto', 'tls'),
(39, 'smtp_from_name', 'ShopVista Store'),
(40, 'smtp_from_email', 'noreply@shopvista.com'),
(41, 'turnstile_enabled', '1'),
(42, 'turnstile_mode', 'managed'),
(43, 'turnstile_preclearance', '0'),
(44, 'turnstile_site_key', ''),
(45, 'turnstile_secret_key', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `city`, `province`, `postal_code`, `avatar`, `role`, `is_active`, `created_at`) VALUES
(1, 'Administrator', 'admin@shopvista.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', NULL, NULL, NULL, NULL, NULL, 'admin', 1, '2026-09-20 20:48:19'),
(2, 'John Customer', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081298765432', NULL, NULL, NULL, NULL, NULL, 'customer', 1, '2026-09-20 20:48:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `couriers`
--
ALTER TABLE `couriers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_email` (`email`);

--
-- Indexes for table `lead_email_logs`
--
ALTER TABLE `lead_email_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `is_featured` (`is_featured`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `couriers`
--
ALTER TABLE `couriers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lead_email_logs`
--
ALTER TABLE `lead_email_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
