<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta -->
    <title><?= isset($title) ? $title : 'ShopVista - Toko Online Terlengkap'; ?></title>
    <meta name="description" content="<?= isset($meta_description) ? $meta_description : 'Belanja online mudah dan aman di ShopVista'; ?>">
    <meta name="keywords" content="<?= isset($meta_keywords) ? $meta_keywords : 'toko online, belanja online, shopvista'; ?>">
    <meta name="author" content="ShopVista">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= isset($title) ? $title : 'ShopVista'; ?>">
    <meta property="og:description" content="<?= isset($meta_description) ? $meta_description : ''; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= current_url(); ?>">
    
    <!-- Base URL -->
    <meta name="base-url" content="<?= base_url(); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <?php $style_css_ver = file_exists(FCPATH . 'assets/css/style.css') ? filemtime(FCPATH . 'assets/css/style.css') : time(); ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=' . $style_css_ver); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animations.css'); ?>">
    
    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body>
    <!-- Top Announcement Bar -->
    <div class="top-announcement-bar">
        <div class="container flex-between">
            <div class="top-bar-left">
                <span><i data-feather="check-circle" style="width:13px;height:13px;vertical-align:-1px;color:var(--accent-green);"></i> 100% Produk Original & Bergaransi</span>
                <span class="d-none-mobile"><i data-feather="truck" style="width:13px;height:13px;vertical-align:-1px;color:var(--primary);"></i> Gratis Ongkir min. belanja Rp 200rb</span>
                <span class="d-none-mobile"><i data-feather="shield" style="width:13px;height:13px;vertical-align:-1px;color:var(--accent-orange);"></i> Garansi Kepuasan & COD</span>
            </div>
            <div class="top-bar-right">
                <a href="<?= base_url('katalog?sort=popular'); ?>">Produk Terlaris</a>
                <span class="sep">•</span>
                <a href="<?= base_url('katalog'); ?>">Promo Hari Ini</a>
                <span class="sep">•</span>
                <a href="<?= base_url('admin'); ?>">Portal Admin</a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar" id="mainNavbar">
        <div class="container">
            <a href="<?= base_url(); ?>" class="navbar-brand">
                <div class="logo-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
                <div class="brand-text">Shop<span>Vista</span></div>
            </a>

            <div class="nav-links" id="navLinks">
                <a href="<?= base_url(); ?>" class="nav-link-item <?= $this->uri->segment(1) == '' ? 'active' : ''; ?>">
                    <svg class="mobile-nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>Beranda</span>
                </a>
                <a href="<?= base_url('katalog'); ?>" class="nav-link-item <?= $this->uri->segment(1) == 'katalog' && !$this->uri->segment(2) ? 'active' : ''; ?>">
                    <svg class="mobile-nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>Katalog</span>
                </a>
                <?php 
                if (!isset($categories) || empty($categories)) {
                    $ci =& get_instance();
                    if (isset($ci->category_model)) {
                        $categories = $ci->category_model->get_all_active();
                    }
                }
                ?>
                <?php if(isset($categories) && is_array($categories) && !empty($categories)): ?>
                <div class="nav-dropdown" id="navCategoryDropdown">
                    <button type="button" class="nav-dropdown-toggle <?= $this->uri->segment(1) == 'katalog' && $this->uri->segment(2) ? 'active' : ''; ?>" id="categoryDropdownBtn" aria-expanded="false">
                        <div class="nav-dropdown-label-wrap">
                            <svg class="mobile-nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                            <span>Kategori</span>
                        </div>
                        <svg class="dropdown-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="nav-dropdown-menu" id="navCategoryMenu">
                        <div class="nav-dropdown-header">Pilihan Kategori</div>
                        <div class="nav-dropdown-grid">
                            <?php foreach($categories as $cat): ?>
                            <a href="<?= base_url('katalog/' . $cat->slug); ?>" class="nav-dropdown-item <?= $this->uri->segment(2) == $cat->slug ? 'active' : ''; ?>">
                                <span class="nav-dropdown-dot"></span>
                                <span class="nav-dropdown-name"><?= $cat->name; ?></span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="nav-dropdown-footer">
                            <a href="<?= base_url('katalog'); ?>" class="nav-dropdown-all-link">
                                <span>Semua Kategori & Produk</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="nav-actions">
                <div class="nav-search">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" placeholder="Cari gadget, fashion, skincare..." id="navSearchInput" value="<?= isset($search_query) ? htmlspecialchars($search_query) : ''; ?>">
                </div>

                <a href="<?= base_url('keranjang'); ?>" class="nav-icon-btn" title="Keranjang">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-count" style="<?= isset($cart_count) && $cart_count > 0 ? '' : 'display:none'; ?>"><?= isset($cart_count) ? $cart_count : 0; ?></span>
                </a>

                <?php if ($this->session->userdata('user_id')): ?>
                    <a href="<?= $this->session->userdata('user_role') === 'admin' ? base_url('admin') : base_url('profil'); ?>" class="nav-user" title="Profil Saya">
                        <img src="<?= avatar_image($this->session->userdata('user_avatar')); ?>" alt="<?= $this->session->userdata('user_name'); ?>">
                        <span class="nav-user-name"><?= $this->session->userdata('user_name'); ?></span>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('login'); ?>" class="btn btn-primary btn-sm">Masuk</a>
                <?php endif; ?>
            </div>

            <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ShopVista.showToast('success', '<?= addslashes($this->session->flashdata('success')); ?>');
        });
    </script>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ShopVista.showToast('error', '<?= addslashes($this->session->flashdata('error')); ?>');
        });
    </script>
    <?php endif; ?>
    <?php if ($this->session->flashdata('warning')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ShopVista.showToast('info', '<?= addslashes($this->session->flashdata('warning')); ?>');
        });
    </script>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="page-content">
