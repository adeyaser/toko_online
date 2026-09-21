<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <meta name="base-url" content="<?= base_url(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animations.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/jquery.dataTables.min.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar Toggle (Mobile) -->
        <button class="sidebar-toggle" aria-label="Toggle sidebar">
            <i data-feather="menu"></i>
        </button>

        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <a href="<?= base_url('admin'); ?>" class="sidebar-brand">
                <div class="logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
                <div class="brand-text">Shop<span>Vista</span></div>
            </a>

            <div class="sidebar-label">Menu Utama</div>
            <div class="sidebar-menu">
                <a href="<?= base_url('admin/dashboard'); ?>" class="<?= $active_menu == 'dashboard' ? 'active' : ''; ?>">
                    <i data-feather="home"></i> Dashboard
                </a>
                <a href="<?= base_url('admin/products'); ?>" class="<?= $active_menu == 'products' ? 'active' : ''; ?>">
                    <i data-feather="package"></i> Produk
                </a>
                <a href="<?= base_url('admin/categories'); ?>" class="<?= $active_menu == 'categories' ? 'active' : ''; ?>">
                    <i data-feather="grid"></i> Kategori
                </a>
                <a href="<?= base_url('admin/orders'); ?>" class="<?= $active_menu == 'orders' ? 'active' : ''; ?>">
                    <i data-feather="shopping-cart"></i> Pesanan
                </a>
            </div>

            <div class="sidebar-label">Manajemen</div>
            <div class="sidebar-menu">
                <a href="<?= base_url('admin/users'); ?>" class="<?= $active_menu == 'users' ? 'active' : ''; ?>">
                    <i data-feather="users"></i> Pengguna
                </a>
                <a href="<?= base_url('admin/banners'); ?>" class="<?= $active_menu == 'banners' ? 'active' : ''; ?>">
                    <i data-feather="image"></i> Banner
                </a>
                <a href="<?= base_url('admin/couriers'); ?>" class="<?= $active_menu == 'couriers' ? 'active' : ''; ?>">
                    <i data-feather="truck"></i> Jasa Pengiriman
                </a>
                <a href="<?= base_url('admin/pages'); ?>" class="<?= $active_menu == 'pages' ? 'active' : ''; ?>">
                    <i data-feather="file-text"></i> Halaman Statis
                </a>
                <a href="<?= base_url('admin/settings'); ?>" class="<?= $active_menu == 'settings' ? 'active' : ''; ?>">
                    <i data-feather="settings"></i> Pengaturan
                </a>
            </div>

            <div class="sidebar-label">Lainnya</div>
            <div class="sidebar-menu">
                <a href="<?= base_url(); ?>" target="_blank">
                    <i data-feather="external-link"></i> Lihat Toko
                </a>
                <a href="<?= base_url('logout'); ?>">
                    <i data-feather="log-out"></i> Keluar
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
