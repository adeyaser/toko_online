<div class="catalog-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= base_url(); ?>">Beranda</a>
            <span class="separator">›</span>
            <span>Pencarian: "<?= htmlspecialchars($search_query); ?>"</span>
        </div>

        <h1>Hasil Pencarian</h1>
        <p style="margin-bottom: 1.25rem;">Menampilkan <?= $total_products; ?> hasil untuk "<strong><?= htmlspecialchars($search_query); ?></strong>"</p>

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
                    <?php if ($product->sale_price): ?>
                    <div class="product-badges">
                        <span class="badge badge-sale">-<?= discount_percent($product->price, $product->sale_price); ?>%</span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <div class="product-category"><?= $product->category_name; ?></div>
                    <h3 class="product-name"><a href="<?= base_url('produk/' . $product->slug); ?>"><?= $product->name; ?></a></h3>
                    <p class="product-short-desc"><?= truncate_text(!empty($product->short_desc) ? $product->short_desc : $product->description, 45); ?></p>
                    <div class="product-price">
                        <span class="price-current"><?= rupiah($product->sale_price ?: $product->price); ?></span>
                        <?php if ($product->sale_price): ?><span class="price-original"><?= rupiah($product->price); ?></span><?php endif; ?>
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

        <?php if ($total_pages > 1): ?>
        <nav class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="<?= base_url('cari?q=' . urlencode($search_query) . '&page=' . $i); ?>" class="<?= $i == $current_page ? 'active' : ''; ?>"><?= $i; ?></a>
            <?php endfor; ?>
        </nav>
        <?php endif; ?>

        <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <h3>Tidak Ditemukan</h3>
            <p>Produk dengan kata kunci "<?= htmlspecialchars($search_query); ?>" tidak ditemukan. Coba kata kunci lain.</p>
            <a href="<?= base_url('katalog'); ?>" class="btn btn-primary">Lihat Semua Produk</a>
        </div>
        <?php endif; ?>
    </div>
</div>
