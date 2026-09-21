<div class="catalog-page">
    <div class="container">
        <div class="catalog-header">
            <div class="breadcrumb">
                <a href="<?= base_url(); ?>">Beranda</a>
                <span class="separator">›</span>
                <span>Katalog</span>
                <?php if (isset($current_category) && $current_category): ?>
                <span class="separator">›</span>
                <span><?= $current_category->name; ?></span>
                <?php endif; ?>
            </div>
            <h1><?= isset($current_category) && $current_category ? $current_category->name : 'Semua Produk'; ?></h1>
            <?php if (isset($current_category) && $current_category && $current_category->description): ?>
            <p style="margin-top: 0.5rem;"><?= $current_category->description; ?></p>
            <?php endif; ?>
        </div>

        <div class="catalog-grid">
            <!-- Sidebar Filters -->
            <aside class="catalog-sidebar">
                <div class="filter-section">
                    <h4><i data-feather="grid" style="width:16px;height:16px;margin-right:6px;"></i> Kategori</h4>
                    <div class="filter-list">
                        <a href="<?= base_url('katalog'); ?>" class="filter-item <?= !isset($current_category) || !$current_category ? 'active' : ''; ?>">
                            <span>Semua Kategori</span>
                            <span><?= $total_products; ?></span>
                        </a>
                        <?php foreach ($categories as $cat): ?>
                        <a href="<?= base_url('katalog/' . $cat->slug); ?>" class="filter-item <?= isset($current_category) && $current_category && $current_category->slug == $cat->slug ? 'active' : ''; ?>">
                            <span><?= $cat->name; ?></span>
                            <span><?= isset($cat->product_count) ? $cat->product_count : 0; ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-section">
                    <h4><i data-feather="sliders" style="width:16px;height:16px;margin-right:6px;"></i> Urutkan</h4>
                    <div class="filter-list">
                        <?php
                        $sorts = [
                            'newest' => 'Terbaru',
                            'popular' => 'Terpopuler',
                            'price_low' => 'Harga Terendah',
                            'price_high' => 'Harga Tertinggi',
                            'name' => 'Nama A-Z'
                        ];
                        $base = isset($current_category) && $current_category ? 'katalog/' . $current_category->slug : 'katalog';
                        foreach ($sorts as $key => $label):
                        ?>
                        <a href="<?= base_url($base . '?sort=' . $key); ?>" class="filter-item <?= $current_sort == $key ? 'active' : ''; ?>">
                            <span><?= $label; ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>

            <!-- Product Grid Column -->
            <div>
                <!-- Mobile Filter Dropdowns (Kategori & Urutan) -->
                <div class="catalog-mobile-filters">
                    <div class="mobile-filter-item">
                        <label class="mobile-filter-label">
                            <i data-feather="grid" style="width:13px;height:13px;"></i> Kategori
                        </label>
                        <select class="mobile-filter-select" onchange="if(this.value) window.location.href=this.value;">
                            <option value="<?= base_url('katalog' . ($current_sort != 'newest' ? '?sort=' . $current_sort : '')); ?>" <?= !isset($current_category) || !$current_category ? 'selected' : ''; ?>>
                                Semua Kategori (<?= $total_products; ?>)
                            </option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= base_url('katalog/' . $cat->slug . ($current_sort != 'newest' ? '?sort=' . $current_sort : '')); ?>" <?= isset($current_category) && $current_category && $current_category->slug == $cat->slug ? 'selected' : ''; ?>>
                                <?= $cat->name; ?> (<?= isset($cat->product_count) ? $cat->product_count : 0; ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mobile-filter-item">
                        <label class="mobile-filter-label">
                            <i data-feather="sliders" style="width:13px;height:13px;"></i> Urutkan
                        </label>
                        <select class="mobile-filter-select" onchange="if(this.value) window.location.href=this.value;">
                            <?php
                            $base = isset($current_category) && $current_category ? 'katalog/' . $current_category->slug : 'katalog';
                            foreach ($sorts as $key => $label):
                            ?>
                            <option value="<?= base_url($base . '?sort=' . $key); ?>" <?= $current_sort == $key ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="catalog-toolbar">
                    <span class="result-count">Menampilkan <?= count($products); ?> dari <?= $total_products; ?> produk</span>
                </div>

                <?php if (!empty($products)): ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): 
                        $meta = product_meta_data($product);
                    ?>
                    <div class="product-card animate-on-scroll">
                        <a href="<?= base_url('produk/' . $product->slug); ?>" class="product-card-link-overlay" aria-label="<?= htmlspecialchars($product->name); ?>"><?= htmlspecialchars($product->name); ?></a>
                        <div class="product-image">
                            <a href="<?= base_url('produk/' . $product->slug); ?>" class="product-image-link">
                                <img src="<?= product_image($product->image); ?>" alt="<?= htmlspecialchars($product->name); ?>" loading="lazy">
                            </a>
                            <div class="product-badges">
                                <?php if ($product->sale_price): ?>
                                <span class="badge badge-sale">-<?= discount_percent($product->price, $product->sale_price); ?>%</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-category"><?= $product->category_name; ?></div>
                            <h3 class="product-name">
                                <a href="<?= base_url('produk/' . $product->slug); ?>"><?= $product->name; ?></a>
                            </h3>
                            <p class="product-short-desc"><?= truncate_text(!empty($product->short_desc) ? $product->short_desc : $product->description, 45); ?></p>
                            <div class="product-price">
                                <span class="price-current"><?= rupiah($product->sale_price ?: $product->price); ?></span>
                                <?php if ($product->sale_price): ?>
                                <span class="price-original"><?= rupiah($product->price); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="product-badge-row">
                                <?php if ($meta['free_shipping']): ?>
                                <span class="badge-shipping">
                                    <i data-feather="truck" style="width:12px;height:12px;"></i> Bebas Ongkir
                                </span>
                                <?php endif; ?>
                            </div>

                            <div class="product-meta-row">
                                <div class="product-meta-left">
                                    <span class="product-meta-star">★ <?= $meta['rating']; ?></span>
                                    <span class="product-meta-sold">• Terjual <?= $meta['sold']; ?></span>
                                </div>
                                <span class="product-store-city">
                                    <i data-feather="map-pin" style="width:12px;height:12px;"></i> <?= $meta['city']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav class="pagination">
                    <?php
                    $qs = '?sort=' . $current_sort;
                    $base_pg = isset($current_category) && $current_category ? 'katalog/' . $current_category->slug : 'katalog';
                    ?>
                    <?php if ($current_page > 1): ?>
                    <a href="<?= base_url($base_pg . $qs . '&page=' . ($current_page - 1)); ?>">‹</a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                    <a href="<?= base_url($base_pg . $qs . '&page=' . $i); ?>" class="<?= $i == $current_page ? 'active' : ''; ?>"><?= $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                    <a href="<?= base_url($base_pg . $qs . '&page=' . ($current_page + 1)); ?>">›</a>
                    <?php endif; ?>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>
                    <h3>Tidak Ada Produk</h3>
                    <p>Produk yang Anda cari belum tersedia. Coba kategori atau kata kunci lain.</p>
                    <a href="<?= base_url('katalog'); ?>" class="btn btn-primary">Lihat Semua Produk</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
