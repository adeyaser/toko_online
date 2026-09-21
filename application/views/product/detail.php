<div class="product-detail">
    <div class="container">
        <div class="product-detail-grid">
            <!-- Product Gallery -->
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?= product_image($product->image, '600x600'); ?>" alt="<?= htmlspecialchars($product->name); ?>" id="mainProductImage">
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info-section">
                <div class="product-breadcrumb">
                    <a href="<?= base_url(); ?>">Beranda</a>
                    <span>›</span>
                    <a href="<?= base_url('katalog'); ?>">Katalog</a>
                    <span>›</span>
                    <?php if (isset($category) && $category): ?>
                    <a href="<?= base_url('katalog/' . $category->slug); ?>"><?= $category->name; ?></a>
                    <span>›</span>
                    <?php endif; ?>
                    <span><?= truncate_text($product->name, 40); ?></span>
                </div>

                <h1 class="product-title"><?= $product->name; ?></h1>

                <div class="product-meta">
                    <?php if (isset($review_stats) && $review_stats->total_reviews > 0): ?>
                    <div class="meta-item">
                        <?= star_rating(round($review_stats->avg_rating)); ?>
                        <span><?= number_format($review_stats->avg_rating, 1); ?> (<?= $review_stats->total_reviews; ?> ulasan)</span>
                    </div>
                    <?php endif; ?>
                    <div class="meta-item">
                        <i data-feather="eye" style="width:16px;height:16px;"></i>
                        <span><?= number_format($product->views); ?> dilihat</span>
                    </div>
                    <div class="meta-item">
                        <i data-feather="package" style="width:16px;height:16px;"></i>
                        <span>Stok: <?= $product->stock > 0 ? $product->stock . ' tersedia' : '<span style="color:var(--secondary)">Habis</span>'; ?></span>
                    </div>
                </div>

                <!-- Price -->
                <div class="price-section">
                    <span class="price-current-lg"><?= rupiah($product->sale_price ?: $product->price); ?></span>
                    <?php if ($product->sale_price): ?>
                    <span class="price-original-lg"><?= rupiah($product->price); ?></span>
                    <span class="badge badge-sale price-discount">-<?= discount_percent($product->price, $product->sale_price); ?>%</span>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div class="product-desc" style="line-height: 1.7; color: var(--text-secondary);">
                    <?= (strip_tags($product->description) !== $product->description) ? $product->description : nl2br(htmlspecialchars($product->description)); ?>
                </div>

                <!-- Quantity & Add to Cart -->
                <?php if ($product->stock > 0): ?>
                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">Jumlah</label>
                    <div class="quantity-selector">
                        <button class="qty-btn qty-minus" type="button">−</button>
                        <input type="number" class="qty-input" id="productQty" value="1" min="1" max="<?= $product->stock; ?>">
                        <button class="qty-btn qty-plus" type="button">+</button>
                    </div>
                </div>

                <div class="product-actions-row">
                    <button class="btn btn-primary btn-lg" onclick="ShopVista.addToCart(<?= $product->id; ?>, document.getElementById('productQty').value)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        Tambah ke Keranjang
                    </button>
                </div>
                <?php else: ?>
                <button class="btn btn-secondary btn-lg btn-full" disabled>Stok Habis</button>
                <?php endif; ?>

                <!-- Features -->
                <div class="product-features">
                    <div class="feature-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>Produk Original</span>
                    </div>
                    <div class="feature-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>Pengiriman Cepat</span>
                    </div>
                    <div class="feature-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>Garansi Uang Kembali</span>
                    </div>
                    <div class="feature-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>Berat: <?= $product->weight; ?>g</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-section">
            <h2 style="margin-bottom: 1.5rem;">Ulasan Pembeli</h2>
            
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <div class="review-header">
                        <div class="review-avatar">
                            <img src="<?= avatar_image($review->avatar); ?>" alt="<?= $review->user_name; ?>">
                        </div>
                        <div>
                            <div class="review-author"><?= $review->user_name; ?></div>
                            <?= star_rating($review->rating); ?>
                            <div class="review-date"><?= time_ago($review->created_at); ?></div>
                        </div>
                    </div>
                    <div class="review-content"><?= nl2br(htmlspecialchars($review->comment)); ?></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--text-muted);">Belum ada ulasan untuk produk ini.</p>
            <?php endif; ?>

            <?php if (isset($can_review) && $can_review): ?>
            <div class="review-card" style="margin-top: 1.5rem;">
                <h4 style="margin-bottom: 1rem;">Tulis Ulasan</h4>
                <form action="<?= base_url('product/add_review'); ?>" method="post">
                    <input type="hidden" name="product_id" value="<?= $product->id; ?>">
                    <div class="form-group">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-control" style="width:auto;">
                            <option value="5">★★★★★ (5 - Sangat Puas)</option>
                            <option value="4">★★★★☆ (4 - Puas)</option>
                            <option value="3">★★★☆☆ (3 - Cukup)</option>
                            <option value="2">★★☆☆☆ (2 - Kurang Puas)</option>
                            <option value="1">★☆☆☆☆ (1 - Kecewa)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Komentar</label>
                        <textarea name="comment" class="form-control" placeholder="Bagikan pengalaman Anda..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                </form>
            </div>
            <?php endif; ?>
        </div>

        <!-- Related Products -->
        <?php if (!empty($related_products)): ?>
        <section class="section">
            <h2 style="margin-bottom: 1.5rem;">Produk Terkait</h2>
            <div class="product-grid">
                <?php foreach ($related_products as $rp): 
                    $rpmeta = product_meta_data($rp);
                ?>
                <div class="product-card">
                    <a href="<?= base_url('produk/' . $rp->slug); ?>" class="product-card-link-overlay" aria-label="<?= htmlspecialchars($rp->name); ?>"><?= htmlspecialchars($rp->name); ?></a>
                    <div class="product-image">
                        <img src="<?= product_image($rp->image); ?>" alt="<?= htmlspecialchars($rp->name); ?>" loading="lazy">
                        <?php if ($rp->sale_price): ?>
                        <div class="product-badges">
                            <span class="badge badge-sale">-<?= discount_percent($rp->price, $rp->sale_price); ?>%</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><a href="<?= base_url('produk/' . $rp->slug); ?>"><?= $rp->name; ?></a></h3>
                        <p class="product-short-desc"><?= truncate_text(!empty($rp->short_desc) ? $rp->short_desc : $rp->description, 45); ?></p>
                        <div class="product-price">
                            <span class="price-current"><?= rupiah($rp->sale_price ?: $rp->price); ?></span>
                            <?php if ($rp->sale_price): ?><span class="price-original"><?= rupiah($rp->price); ?></span><?php endif; ?>
                        </div>

                        <div class="product-badge-row">
                            <?php if ($rpmeta['free_shipping']): ?>
                            <span class="badge-shipping">
                                <i data-feather="truck" style="width:12px;height:12px;"></i> Bebas Ongkir
                            </span>
                            <?php endif; ?>
                        </div>

                        <div class="product-meta-row">
                            <div class="product-meta-left">
                                <span class="product-meta-star">★ <?= $rpmeta['rating']; ?></span>
                                <span class="product-meta-sold">• Terjual <?= $rpmeta['sold']; ?></span>
                            </div>
                            <span class="product-store-city">
                                <i data-feather="map-pin" style="width:12px;height:12px;"></i> <?= $rpmeta['city']; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</div>
