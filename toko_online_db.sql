-- ============================================================
-- ShopVista - Toko Online Database
-- Database: toko_online_db
-- Engine: InnoDB | Charset: utf8mb4
-- ============================================================

CREATE DATABASE IF NOT EXISTS `toko_online_db` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `toko_online_db`;

-- ============================================================
-- Tabel: categories
-- ============================================================
CREATE TABLE `categories` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL,
    `icon` VARCHAR(50) DEFAULT 'package',
    `description` TEXT DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT(11) DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Tabel: products
-- ============================================================
CREATE TABLE `products` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(280) NOT NULL,
    `price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `sale_price` DECIMAL(15,2) DEFAULT NULL,
    `stock` INT(11) NOT NULL DEFAULT 0,
    `description` TEXT DEFAULT NULL,
    `short_desc` VARCHAR(500) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `gallery` TEXT DEFAULT NULL,
    `weight` INT(11) DEFAULT 0 COMMENT 'gram',
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `views` INT(11) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    KEY `category_id` (`category_id`),
    KEY `is_featured` (`is_featured`),
    KEY `is_active` (`is_active`),
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Tabel: users
-- ============================================================
CREATE TABLE `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `city` VARCHAR(100) DEFAULT NULL,
    `province` VARCHAR(100) DEFAULT NULL,
    `postal_code` VARCHAR(10) DEFAULT NULL,
    `avatar` VARCHAR(255) DEFAULT NULL,
    `role` ENUM('admin','customer') NOT NULL DEFAULT 'customer',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Tabel: orders
-- ============================================================
CREATE TABLE `orders` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `order_number` VARCHAR(30) NOT NULL,
    `total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `shipping_cost` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `grand_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `status` ENUM('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
    `payment_method` VARCHAR(50) DEFAULT 'bank_transfer',
    `payment_proof` VARCHAR(255) DEFAULT NULL,
    `shipping_name` VARCHAR(100) DEFAULT NULL,
    `shipping_phone` VARCHAR(20) DEFAULT NULL,
    `shipping_address` TEXT DEFAULT NULL,
    `shipping_city` VARCHAR(100) DEFAULT NULL,
    `shipping_province` VARCHAR(100) DEFAULT NULL,
    `shipping_postal` VARCHAR(10) DEFAULT NULL,
    `shipping_courier` VARCHAR(100) DEFAULT 'ShopVista Express',
    `tracking_number` VARCHAR(100) DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `order_number` (`order_number`),
    KEY `user_id` (`user_id`),
    KEY `status` (`status`),
    CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Tabel: order_items
-- ============================================================
CREATE TABLE `order_items` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_id` INT(11) UNSIGNED NOT NULL,
    `product_id` INT(11) UNSIGNED NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `product_image` VARCHAR(255) DEFAULT NULL,
    `quantity` INT(11) NOT NULL DEFAULT 1,
    `price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `subtotal` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (`id`),
    KEY `order_id` (`order_id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Tabel: cart
-- ============================================================
CREATE TABLE `cart` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED DEFAULT NULL,
    `session_id` VARCHAR(128) DEFAULT NULL,
    `product_id` INT(11) UNSIGNED NOT NULL,
    `quantity` INT(11) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `session_id` (`session_id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Tabel: banners
-- ============================================================
CREATE TABLE `banners` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) DEFAULT NULL,
    `subtitle` VARCHAR(500) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `link` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT(11) DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Tabel: settings
-- ============================================================
CREATE TABLE `settings` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `setting_key` VARCHAR(100) NOT NULL,
    `setting_value` TEXT DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Tabel: reviews
-- ============================================================
CREATE TABLE `reviews` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INT(11) UNSIGNED NOT NULL,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `rating` TINYINT(1) NOT NULL DEFAULT 5,
    `comment` TEXT DEFAULT NULL,
    `is_approved` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `product_id` (`product_id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- SEED DATA
-- ============================================================

-- Admin user (password: admin123)
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`, `is_active`) VALUES
('Administrator', 'admin@shopvista.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'admin', 1),
('John Customer', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081298765432', 'customer', 1);

-- Categories
INSERT INTO `categories` (`name`, `slug`, `icon`, `description`, `is_active`, `sort_order`) VALUES
('Elektronik', 'elektronik', 'cpu', 'Gadget, laptop, smartphone, dan aksesoris elektronik terbaru', 1, 1),
('Fashion Pria', 'fashion-pria', 'user', 'Koleksi pakaian, sepatu, dan aksesoris pria terkini', 1, 2),
('Fashion Wanita', 'fashion-wanita', 'heart', 'Busana, tas, sepatu, dan aksesoris wanita trendy', 1, 3),
('Makanan & Minuman', 'makanan-minuman', 'coffee', 'Snack, minuman, dan makanan kemasan pilihan', 1, 4),
('Kesehatan & Kecantikan', 'kesehatan-kecantikan', 'star', 'Skincare, makeup, vitamin, dan produk kesehatan', 1, 5),
('Rumah Tangga', 'rumah-tangga', 'home', 'Peralatan rumah, dekorasi, dan kebutuhan harian', 1, 6),
('Olahraga', 'olahraga', 'activity', 'Peralatan olahraga, fitness, dan outdoor', 1, 7),
('Buku & Alat Tulis', 'buku-alat-tulis', 'book-open', 'Buku, novel, alat tulis, dan perlengkapan kantor', 1, 8);

-- Products
INSERT INTO `products` (`category_id`, `name`, `slug`, `price`, `sale_price`, `stock`, `description`, `short_desc`, `image`, `weight`, `is_featured`, `is_active`, `views`) VALUES
(1, 'Smartphone Galaxy Ultra X1', 'smartphone-galaxy-ultra-x1', 8999000, 7499000, 50, 'Smartphone flagship terbaru dengan layar AMOLED 6.8 inch, kamera 200MP, baterai 5000mAh, RAM 12GB, dan penyimpanan 256GB. Dilengkapi dengan prosesor terbaru untuk performa gaming dan multitasking tanpa hambatan.', 'Smartphone flagship layar AMOLED 6.8", kamera 200MP, RAM 12GB', NULL, 220, 1, 1, 342),
(1, 'Laptop ProBook Elite 15', 'laptop-probook-elite-15', 15499000, NULL, 25, 'Laptop premium untuk profesional dengan layar IPS 15.6 inch Full HD, prosesor Intel Core i7 Gen 13, RAM 16GB DDR5, SSD 512GB NVMe, dan kartu grafis dedicated. Bobot ringan hanya 1.7kg.', 'Laptop Intel i7 Gen 13, RAM 16GB, SSD 512GB, layar 15.6" FHD', NULL, 1700, 1, 1, 215),
(1, 'TWS Earbuds Pro Max', 'tws-earbuds-pro-max', 1299000, 899000, 100, 'True Wireless Stereo earbuds dengan Active Noise Cancellation, driver 12mm, Bluetooth 5.3, baterai hingga 36 jam dengan case, IPX5 water resistant. Suara bass yang powerful dan jernih.', 'TWS ANC, Bluetooth 5.3, 36 jam baterai, IPX5', NULL, 55, 1, 1, 189),
(1, 'Smartwatch FitPro Ultra', 'smartwatch-fitpro-ultra', 2499000, 1999000, 75, 'Smartwatch premium dengan layar AMOLED 1.9 inch always-on display, GPS built-in, monitor detak jantung & SpO2, 100+ mode olahraga, baterai 14 hari, water resistant 5ATM.', 'Smartwatch AMOLED 1.9", GPS, SpO2, 14 hari baterai', NULL, 68, 1, 1, 267),
(2, 'Kemeja Slim Fit Premium Oxford', 'kemeja-slim-fit-premium-oxford', 359000, 289000, 200, 'Kemeja pria slim fit bahan Oxford premium, tersedia dalam berbagai warna. Jahitan rapi, kancing berkualitas, cocok untuk formal maupun casual. Bahan adem dan nyaman dipakai seharian.', 'Kemeja slim fit bahan Oxford premium, nyaman & stylish', NULL, 250, 1, 1, 156),
(2, 'Sneakers Urban Runner V2', 'sneakers-urban-runner-v2', 599000, 449000, 150, 'Sepatu sneakers pria dengan desain modern dan sporty. Sol EVA ultra ringan, upper mesh breathable, insole memory foam untuk kenyamanan maksimal. Cocok untuk daily wear dan olahraga ringan.', 'Sneakers sporty, sol EVA ringan, memory foam insole', NULL, 350, 0, 1, 98),
(3, 'Tas Tote Bag Leather Look', 'tas-tote-bag-leather-look', 299000, 249000, 120, 'Tas tote bag wanita dengan bahan leather look premium. Desain minimalis dan elegan, kompartemen luas, dilengkapi inner pocket dan magnetic closure. Cocok untuk kerja dan hangout.', 'Tote bag leather look, minimalis & elegan', NULL, 400, 1, 1, 134),
(3, 'Dress Floral Summer Collection', 'dress-floral-summer-collection', 459000, NULL, 80, 'Dress wanita motif floral koleksi summer terbaru. Bahan chiffon lembut dan adem, cutting A-line flattering, tersedia size S-XL. Sempurna untuk acara casual dan semi formal.', 'Dress floral chiffon, cutting A-line, size S-XL', NULL, 200, 0, 1, 87),
(4, 'Kopi Arabica Specialty Blend 250g', 'kopi-arabica-specialty-blend-250g', 125000, 99000, 300, 'Kopi arabica specialty grade dari pegunungan Gayo, Aceh. Single origin, roasting medium-dark, notes: chocolate, caramel, citrus. Freshly roasted, dikemas dalam valve bag untuk menjaga kesegaran.', 'Kopi arabica Gayo, specialty grade, medium-dark roast', NULL, 280, 1, 1, 201),
(4, 'Teh Matcha Premium Grade 100g', 'teh-matcha-premium-grade-100g', 189000, NULL, 150, 'Teh matcha premium grade dari Uji, Jepang. Warna hijau cerah, rasa umami yang kaya, cocok untuk latte, smoothie, atau baking. Dikemas dalam tin container kedap udara.', 'Matcha premium grade Uji, Jepang, 100g tin', NULL, 130, 0, 1, 112),
(5, 'Serum Vitamin C 20% + Hyaluronic Acid', 'serum-vitamin-c-20-hyaluronic-acid', 249000, 199000, 200, 'Serum wajah dengan Vitamin C 20% dan Hyaluronic Acid untuk mencerahkan, melembapkan, dan anti-aging. Formula ringan, cepat menyerap, cocok untuk semua jenis kulit. Dermatologically tested.', 'Serum Vitamin C 20% + HA, brightening & hydrating', NULL, 50, 1, 1, 345),
(5, 'Sunscreen SPF50+ PA++++ 50ml', 'sunscreen-spf50-pa-50ml', 179000, 149000, 250, 'Sunscreen dengan perlindungan SPF50+ PA++++ broad spectrum. Tekstur ringan, tidak lengket, tidak white cast. Mengandung Centella Asiatica dan Niacinamide untuk perlindungan dan perawatan kulit.', 'Sunscreen SPF50+ ringan, tidak white cast', NULL, 70, 0, 1, 278),
(6, 'Diffuser Aromatherapy Kayu + 3 Essential Oil', 'diffuser-aromatherapy-kayu-3-essential-oil', 399000, 329000, 60, 'Diffuser aromatherapy dengan body kayu natural, kapasitas 300ml, 7 warna LED, timer otomatis. Bonus 3 botol essential oil (lavender, eucalyptus, peppermint). Tenang dan nyaman.', 'Diffuser kayu 300ml + 3 essential oil gratis', NULL, 500, 1, 1, 167),
(6, 'Set Organizer Bambu 5 in 1', 'set-organizer-bambu-5-in-1', 259000, NULL, 90, 'Set organizer bambu multifungsi 5 in 1: tissue holder, remote holder, phone stand, pen holder, dan tray. Desain Scandinavian minimalis, ramah lingkungan, cocok untuk meja kerja dan ruang tamu.', 'Organizer bambu 5in1, Scandinavian style', NULL, 600, 0, 1, 89),
(7, 'Yoga Mat Premium TPE 6mm', 'yoga-mat-premium-tpe-6mm', 349000, 279000, 100, 'Matras yoga premium bahan TPE eco-friendly, tebal 6mm, anti-slip double side, ringan dan mudah dibawa. Dilengkapi tali pengikat. Ukuran 183x61cm, tersedia dalam 5 warna pastel.', 'Yoga mat TPE 6mm, anti-slip, eco-friendly', NULL, 800, 0, 1, 76),
(7, 'Resistance Band Set 5 Level', 'resistance-band-set-5-level', 149000, 119000, 180, 'Set resistance band 5 level ketebalan (extra light hingga extra heavy). Bahan latex premium, tahan lama, dilengkapi door anchor, ankle strap, dan carry bag. Ideal untuk home workout.', 'Resistance band 5 level + aksesoris lengkap', NULL, 350, 1, 1, 143),
(8, 'Buku "Atomic Habits" - James Clear', 'buku-atomic-habits-james-clear', 99000, NULL, 200, 'Buku bestseller "Atomic Habits" karya James Clear. Panduan praktis untuk membangun kebiasaan baik dan menghilangkan kebiasaan buruk. Edisi terjemahan Bahasa Indonesia, soft cover.', 'Buku Atomic Habits, terjemahan Indonesia', NULL, 300, 0, 1, 234),
(8, 'Notebook Premium A5 Hardcover Dotted', 'notebook-premium-a5-hardcover-dotted', 89000, 69000, 250, 'Notebook premium A5 dengan cover hardbound, 200 halaman dotted 100gsm (fountain pen friendly), bookmark ribbon, back pocket, dan elastic closure. Cocok untuk bullet journal.', 'Notebook A5 dotted 200hal, hardcover premium', NULL, 280, 0, 1, 98);

-- Banners
INSERT INTO `banners` (`title`, `subtitle`, `image`, `link`, `is_active`, `sort_order`) VALUES
('Flash Sale Spectacular', 'Diskon hingga 70% untuk semua kategori. Promo terbatas!', NULL, '/product/catalog', 1, 1),
('Koleksi Terbaru 2026', 'Temukan produk-produk terkini pilihan editor kami', NULL, '/product/catalog', 1, 2),
('Gratis Ongkir', 'Belanja minimal Rp200.000 gratis ongkir ke seluruh Indonesia', NULL, '/product/catalog', 1, 3);

-- Settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('store_name', 'ShopVista'),
('store_tagline', 'Belanja Modern, Harga Terjangkau'),
('store_email', 'hello@shopvista.com'),
('store_phone', '0812-3456-7890'),
('store_address', 'Jl. Sudirman No. 123, Jakarta Pusat'),
('store_logo', NULL),
('store_favicon', NULL),
('currency', 'IDR'),
('free_shipping_min', '200000'),
('shipping_cost', '15000'),
('bank_name', 'Bank BCA'),
('bank_account', '1234567890'),
('bank_holder', 'PT ShopVista Indonesia'),
('whatsapp', '6281234567890'),
('instagram', '@shopvista.id'),
('facebook', 'shopvista.id'),
('meta_title', 'ShopVista - Toko Online Terlengkap & Terpercaya'),
('meta_description', 'Belanja online mudah dan aman di ShopVista. Temukan ribuan produk berkualitas dengan harga terbaik. Gratis ongkir, garansi uang kembali.'),
('meta_keywords', 'toko online, belanja online, shopvista, ecommerce indonesia');

-- Reviews
INSERT INTO `reviews` (`product_id`, `user_id`, `rating`, `comment`, `is_approved`) VALUES
(1, 2, 5, 'Smartphone luar biasa! Kamera sangat jernih dan baterai tahan seharian. Worth it banget!', 1),
(1, 2, 4, 'Performa cepat, layar sangat tajam. Hanya saja agak berat di tangan.', 1),
(3, 2, 5, 'Earbuds terbaik di range harga ini. ANC-nya efektif banget, bass powerful.', 1),
(5, 2, 5, 'Bahan Oxford-nya premium, jahitan rapi, fitting pas. Sangat recommended!', 1),
(9, 2, 5, 'Kopinya mantap! Aroma kuat, rasa balanced. Best seller emang gak bohong.', 1),
(11, 2, 5, 'Serum ini game changer! Kulit jadi cerah dan glowing setelah 2 minggu pemakaian.', 1),
(13, 2, 4, 'Diffuser-nya bagus, aroma terapi memang bikin relax. LED-nya cantik.', 1);
