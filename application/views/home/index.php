<!-- Hero Section -->
<section class="hero">
    <div class="container hero-content">
        <div class="hero-slider">
            <?php if (!empty($banners)): ?>
                <?php foreach ($banners as $i => $banner): ?>
                <div class="hero-slide <?= $i === 0 ? 'active' : ''; ?>" style="background-image: url('<?= banner_image($banner->image); ?>');">
                    <div class="hero-slide-overlay"></div>
                    <div class="hero-slide-content">
                        <span class="hero-badge">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                            Promo Spesial
                        </span>
                        <h1><?= $banner->title; ?></h1>
                        <p><?= $banner->subtitle; ?></p>
                        <div class="hero-actions">
                            <a href="<?= base_url('katalog'); ?>" class="btn btn-primary btn-lg">
                                Belanja Sekarang
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </a>
                            <a href="<?= base_url('katalog?sort=popular'); ?>" class="btn btn-secondary btn-lg">Lihat Terlaris</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="hero-slide active">
                    <div class="hero-slide-overlay"></div>
                    <div class="hero-slide-content">
                        <span class="hero-badge">✨ Welcome to ShopVista</span>
                        <h1>Belanja Modern, <span class="text-gradient">Harga Terjangkau</span></h1>
                        <p>Temukan ribuan produk berkualitas dari berbagai kategori dengan harga terbaik dan pengiriman cepat.</p>
                        <div class="hero-actions">
                            <a href="<?= base_url('katalog'); ?>" class="btn btn-primary btn-lg">Mulai Belanja</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if (count($banners) > 1): ?>
        <div class="hero-dots">
            <?php foreach ($banners as $i => $banner): ?>
            <button class="dot <?= $i === 0 ? 'active' : ''; ?>" aria-label="Slide <?= $i + 1; ?>"></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Trust & Value Highlights -->
<!-- Trust & Value Highlights -->
<section class="features-section">
    <div class="container">
        <div class="features-bar">
            <div class="feature-item">
                <div class="feature-icon-box" style="background: #EEF2FF; color: var(--primary); box-shadow: 0 2px 6px rgba(79, 70, 229, 0.1);">
                    <i data-feather="truck" style="width: 22px; height: 22px;"></i>
                </div>
                <div>
                    <div class="feature-title">Pengiriman Cepat</div>
                    <div class="feature-subtitle">Kirim ke seluruh Indonesia</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon-box" style="background: #ECFDF5; color: #059669; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.1);">
                    <i data-feather="shield-check" style="width: 22px; height: 22px;"></i>
                </div>
                <div>
                    <div class="feature-title">100% Produk Original</div>
                    <div class="feature-subtitle">Jaminan kualitas terpercaya</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon-box" style="background: #FFF7ED; color: #EA580C; box-shadow: 0 2px 6px rgba(234, 88, 12, 0.1);">
                    <i data-feather="credit-card" style="width: 22px; height: 22px;"></i>
                </div>
                <div>
                    <div class="feature-title">Bayar Aman & COD</div>
                    <div class="feature-subtitle">Transfer & bayar di tempat</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon-box" style="background: #FDF2F8; color: #DB2777; box-shadow: 0 2px 6px rgba(219, 39, 119, 0.1);">
                    <i data-feather="headphones" style="width: 22px; height: 22px;"></i>
                </div>
                <div>
                    <div class="feature-title">Layanan Ramah 24/7</div>
                    <div class="feature-subtitle">CS siap membantu kamu</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section" id="categories">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2>Jelajahi Kategori</h2>
            <p>Temukan produk impian dari berbagai kategori pilihan</p>
            <div class="section-line"></div>
        </div>

        <div class="category-grid stagger-children">
            <?php foreach ($categories as $cat): ?>
            <a href="<?= base_url('katalog/' . $cat->slug); ?>" class="category-card">
                <div class="cat-image-wrap">
                    <img src="<?= category_image($cat->image, $cat->slug); ?>" alt="<?= htmlspecialchars($cat->name); ?>" class="cat-image" loading="lazy">
                </div>
                <div class="cat-name"><?= htmlspecialchars($cat->name); ?></div>
                <div class="cat-count"><?= isset($cat->product_count) ? $cat->product_count : 0; ?> Produk</div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if (!empty($featured_products)): ?>
<section class="section" style="background: var(--bg-elevated);">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2>Produk Unggulan</h2>
            <p>Pilihan terbaik dengan penilaian tertinggi dan paling diminati pembeli</p>
            <div class="section-line"></div>
        </div>

        <div class="product-grid">
            <?php foreach ($featured_products as $product): 
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
                        <?php if ($product->is_featured): ?>
                        <span class="badge badge-featured">Unggulan</span>
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

        <div class="text-center mt-2">
            <a href="<?= base_url('katalog'); ?>" class="btn btn-outline btn-lg">
                Lihat Semua Produk
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Flash Sale Section -->
<?php if (!empty($sale_products)): ?>
<section class="section">
    <div class="container">
        <div class="flash-sale-card animate-on-scroll">
            <div class="flash-sale-header">
                <div class="flash-sale-title-group">
                    <span class="flash-badge">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        FLASH SALE
                    </span>
                    <div class="flash-countdown">
                        <span>Berakhir dalam:</span>
                        <span class="timer-box" id="flash-hours">03</span>
                        <span class="timer-colon">:</span>
                        <span class="timer-box" id="flash-mins">45</span>
                        <span class="timer-colon">:</span>
                        <span class="timer-box" id="flash-secs">20</span>
                    </div>
                </div>
                <a href="<?= base_url('katalog?sort=price_low'); ?>" class="btn btn-outline btn-sm" style="font-weight: 600;">
                    Lihat Semua Flash Sale
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>

            <div class="product-grid">
                <?php foreach ($sale_products as $product): 
                    $meta = product_meta_data($product);
                    $pct = discount_percent($product->price, $product->sale_price);
                    $stock_percent = min(92, max(40, 100 - ($product->stock % 40)));
                ?>
                <div class="product-card">
                    <a href="<?= base_url('produk/' . $product->slug); ?>" class="product-card-link-overlay" aria-label="<?= htmlspecialchars($product->name); ?>"><?= htmlspecialchars($product->name); ?></a>
                    <div class="product-image">
                        <a href="<?= base_url('produk/' . $product->slug); ?>" class="product-image-link">
                            <img src="<?= product_image($product->image); ?>" alt="<?= htmlspecialchars($product->name); ?>" loading="lazy">
                        </a>
                        <div class="product-badges">
                            <span class="badge badge-sale">-<?= $pct; ?>%</span>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-category"><?= $product->category_name; ?></div>
                        <h3 class="product-name">
                            <a href="<?= base_url('produk/' . $product->slug); ?>"><?= $product->name; ?></a>
                        </h3>
                        <p class="product-short-desc">
                            <?= truncate_text(!empty($product->short_desc) ? $product->short_desc : $product->description, 45); ?>
                        </p>
                        <div class="product-price">
                            <span class="price-current" style="color: #EF4444;"><?= rupiah($product->sale_price); ?></span>
                            <span class="price-original"><?= rupiah($product->price); ?></span>
                        </div>

                        <div class="flash-stock-bar">
                            <div class="flash-progress">
                                <div class="flash-progress-fill" style="width: <?= $stock_percent; ?>%;"></div>
                            </div>
                            <div class="flash-stock-text" style="margin-top: 4px;">
                                <span style="color: #EF4444; font-weight: 700;">Tersisa <?= max(3, $product->stock % 15); ?> pcs</span>
                                <span>Terjual <?= $stock_percent; ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Latest Products -->
<?php if (!empty($latest_products)): ?>
<section class="section" style="background: var(--bg-elevated);">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2>Produk Terbaru</h2>
            <p>Koleksi item terbaru yang baru saja mendarat di toko kami</p>
            <div class="section-line"></div>
        </div>

        <div class="product-grid">
            <?php foreach ($latest_products as $product): 
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
    </div>
</section>
<?php endif; ?>

<!-- Newsletter Section -->
<section class="section" id="promo-newsletter">
    <div class="container">
        <div class="promo-section animate-on-scroll">
            <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(79,70,229,0.1);color:var(--primary);padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:700;margin-bottom:0.75rem;">
                <i data-feather="bell" style="width:14px;height:14px;"></i> Info Promo & Update Produk
            </div>
            <h2>Dapatkan Info Promo & Penawaran Eksklusif</h2>
            <p>Daftarkan email kamu untuk menjadi yang pertama menerima voucher diskon dan update produk terbaru langsung ke inbox email.</p>
            <form id="newsletterForm" class="newsletter-form-container" onsubmit="handleNewsletterSubscribe(event)" style="max-width: 500px; margin: 0 auto;">
                <div class="newsletter-form">
                    <input type="email" id="newsletterEmail" name="email" placeholder="Masukkan alamat email kamu..." required autocomplete="email">
                    <button type="submit" id="btnNewsletterSubmit" class="btn btn-primary">
                        <span id="btnNewsletterText">Daftar Sekarang</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
                
                <?php if (turnstile_enabled()): ?>
                <!-- Turnstile Cloudflare Card: Muncul ketika hit Kirim / Daftar Sekarang -->
                <div id="turnstileNewsletterBox" style="display: none; margin-top: 1rem; padding: 14px 16px; background: #FFFFFF; border-radius: 14px; border: 1.5px dashed #CBD5E1; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08); text-align: center;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                        <div style="font-size: 0.84rem; font-weight: 700; color: #1E293B; display: flex; align-items: center; gap: 6px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#F48120" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Verifikasi Keamanan Cloudflare</span>
                        </div>
                        <button type="button" onclick="cancelNewsletterTurnstile()" style="background:none; border:none; color:#94A3B8; cursor:pointer; padding:2px;" title="Batal">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div id="newsletterTurnstileWidget" style="display: flex; justify-content: center; min-height: 65px;"></div>
                    <div id="turnstileHint" style="font-size: 0.76rem; color: #64748B; margin-top: 8px;">
                        Silakan selesaikan verifikasi di atas untuk menyimpan pendaftaran promo kamu.
                    </div>
                </div>
                <?php endif; ?>
            </form>
            <div id="newsletterFeedback" style="display:none;margin-top:0.75rem;font-size:0.88rem;font-weight:600;"></div>
        </div>
    </div>
</section>

<script>
let turnstileWidgetId = null;
let currentPendingEmail = '';
let isSavingNewsletter = false;

function cancelNewsletterTurnstile() {
    const box = document.getElementById('turnstileNewsletterBox');
    const submitBtn = document.getElementById('btnNewsletterSubmit');
    const btnText = document.getElementById('btnNewsletterText');
    const widgetContainer = document.getElementById('newsletterTurnstileWidget');

    if (turnstileWidgetId !== null && typeof turnstile !== 'undefined') {
        try { turnstile.remove(turnstileWidgetId); } catch(e) {}
    }
    if (widgetContainer) widgetContainer.innerHTML = '';
    turnstileWidgetId = null;

    if (box) box.style.display = 'none';
    if (submitBtn) submitBtn.disabled = false;
    if (btnText) btnText.textContent = 'Daftar Sekarang';
    currentPendingEmail = '';
    isSavingNewsletter = false;
}

function ensureTurnstileLoaded(callback) {
    if (typeof window.turnstile !== 'undefined') {
        callback();
        return;
    }
    const existingScript = document.querySelector('script[src*="challenges.cloudflare.com/turnstile"]');
    if (!existingScript) {
        const script = document.createElement('script');
        script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
        script.async = true;
        script.defer = true;
        script.onload = function() {
            const check = setInterval(function() {
                if (typeof window.turnstile !== 'undefined') {
                    clearInterval(check);
                    callback();
                }
            }, 50);
        };
        document.head.appendChild(script);
    } else {
        const check = setInterval(function() {
            if (typeof window.turnstile !== 'undefined') {
                clearInterval(check);
                callback();
            }
        }, 50);
    }
}

function handleNewsletterSubscribe(e) {
    e.preventDefault();
    if (isSavingNewsletter) return;

    const emailInput = document.getElementById('newsletterEmail');
    const submitBtn = document.getElementById('btnNewsletterSubmit');
    const btnText = document.getElementById('btnNewsletterText');
    const feedback = document.getElementById('newsletterFeedback');
    const email = emailInput.value.trim();

    if (!email) {
        if (typeof ShopVista !== 'undefined') {
            ShopVista.showToast('error', 'Silakan masukkan alamat email Anda.');
        }
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        if (typeof ShopVista !== 'undefined') {
            ShopVista.showToast('error', 'Format email tidak valid.');
        }
        return;
    }

    currentPendingEmail = email;
    if (feedback) {
        feedback.style.display = 'none';
        feedback.textContent = '';
    }

    const turnstileEnabled = <?= turnstile_enabled() ? 'true' : 'false'; ?>;
    const turnstileSiteKey = '<?= htmlspecialchars(get_setting("turnstile_site_key", "")); ?>';

    if (!turnstileEnabled || !turnstileSiteKey) {
        // Jika Turnstile tidak diaktifkan, langsung simpan
        executeSaveNewsletter(email, '');
        return;
    }

    // Tampilkan Cloudflare Turnstile ketika tombol Kirim / Daftar ditekan
    const box = document.getElementById('turnstileNewsletterBox');
    const hint = document.getElementById('turnstileHint');
    const widgetContainer = document.getElementById('newsletterTurnstileWidget');

    if (box) {
        box.style.display = 'block';
        box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    if (hint) {
        hint.innerHTML = 'Silakan selesaikan verifikasi di atas untuk menyimpan pendaftaran.';
    }

    submitBtn.disabled = true;
    btnText.textContent = 'Verifikasi...';

    // Bersihkan widget lama sebelum render ulang
    if (turnstileWidgetId !== null && typeof turnstile !== 'undefined') {
        try { turnstile.remove(turnstileWidgetId); } catch(e) {}
    }
    if (widgetContainer) widgetContainer.innerHTML = '';
    turnstileWidgetId = null;

    ensureTurnstileLoaded(function() {
        if (!widgetContainer) return;

        turnstileWidgetId = turnstile.render('#newsletterTurnstileWidget', {
            sitekey: turnstileSiteKey,
            action: 'newsletter',
            theme: 'auto',
            callback: function(token) {
                // Cegah pemanggilan berulang
                if (isSavingNewsletter) return;

                if (hint) {
                    hint.innerHTML = '<span style="color:#059669;font-weight:600;">✓ Verifikasi berhasil! Menyimpan data promo...</span>';
                }
                btnText.textContent = 'Menyimpan...';

                // Jalankan penyimpanan tepat SATU KALI
                executeSaveNewsletter(currentPendingEmail, token);
            },
            'error-callback': function() {
                if (hint) {
                    hint.innerHTML = '<span style="color:#DC2626;font-weight:600;">⚠️ Verifikasi gagal. Silakan coba lagi.</span>';
                }
                submitBtn.disabled = false;
                btnText.textContent = 'Daftar Sekarang';
                isSavingNewsletter = false;
            },
            'expired-callback': function() {
                if (hint) {
                    hint.innerHTML = '<span style="color:#D97706;">Sesi verifikasi kadaluarsa. Silakan verifikasi ulang.</span>';
                }
                submitBtn.disabled = false;
                btnText.textContent = 'Daftar Sekarang';
                isSavingNewsletter = false;
            }
        });
    });
}

function executeSaveNewsletter(email, turnstileToken) {
    if (isSavingNewsletter) return;
    isSavingNewsletter = true;

    const emailInput = document.getElementById('newsletterEmail');
    const submitBtn = document.getElementById('btnNewsletterSubmit');
    const btnText = document.getElementById('btnNewsletterText');
    const feedback = document.getElementById('newsletterFeedback');
    const box = document.getElementById('turnstileNewsletterBox');
    const widgetContainer = document.getElementById('newsletterTurnstileWidget');

    submitBtn.disabled = true;
    btnText.textContent = 'Menyimpan...';

    const formData = new FormData();
    formData.append('email', email);
    formData.append('source', 'newsletter_home');
    if (turnstileToken) {
        formData.append('cf-turnstile-response', turnstileToken);
    }

    fetch('<?= base_url("newsletter/subscribe"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        // Sembunyikan kotak verifikasi
        if (box) box.style.display = 'none';

        // Hancurkan Turnstile widget agar TIDAK memicu callback lagi
        if (turnstileWidgetId !== null && typeof turnstile !== 'undefined') {
            try { turnstile.remove(turnstileWidgetId); } catch(err) {}
        }
        if (widgetContainer) widgetContainer.innerHTML = '';
        turnstileWidgetId = null;

        submitBtn.disabled = false;
        btnText.textContent = 'Daftar Sekarang';

        if (data.status === 'success') {
            emailInput.value = '';
            currentPendingEmail = '';
            if (feedback) {
                feedback.style.display = 'block';
                feedback.style.color = '#059669';
                feedback.textContent = '✅ ' + data.message;
            }
            if (typeof ShopVista !== 'undefined') {
                ShopVista.showToast('success', data.message);
            }
        } else if (data.status === 'exists') {
            if (feedback) {
                feedback.style.display = 'block';
                feedback.style.color = '#0284C7';
                feedback.textContent = 'ℹ️ ' + data.message;
            }
            if (typeof ShopVista !== 'undefined') {
                ShopVista.showToast('info', data.message);
            }
        } else {
            if (feedback) {
                feedback.style.display = 'block';
                feedback.style.color = '#DC2626';
                feedback.textContent = '⚠️ ' + data.message;
            }
            if (typeof ShopVista !== 'undefined') {
                ShopVista.showToast('error', data.message);
            }
        }

        setTimeout(function() {
            isSavingNewsletter = false;
        }, 1200);
    })
    .catch(err => {
        if (box) box.style.display = 'none';
        if (turnstileWidgetId !== null && typeof turnstile !== 'undefined') {
            try { turnstile.remove(turnstileWidgetId); } catch(e) {}
        }
        if (widgetContainer) widgetContainer.innerHTML = '';
        turnstileWidgetId = null;

        submitBtn.disabled = false;
        btnText.textContent = 'Daftar Sekarang';
        if (feedback) {
            feedback.style.display = 'block';
            feedback.style.color = '#DC2626';
            feedback.textContent = '❌ Terjadi kesalahan jaringan. Silakan coba lagi.';
        }
        if (typeof ShopVista !== 'undefined') {
            ShopVista.showToast('error', 'Terjadi kesalahan jaringan.');
        }

        setTimeout(function() {
            isSavingNewsletter = false;
        }, 1200);
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var h = 3, m = 45, s = 20;
    var elH = document.getElementById('flash-hours');
    var elM = document.getElementById('flash-mins');
    var elS = document.getElementById('flash-secs');
    if (elH && elM && elS) {
        setInterval(function() {
            s--;
            if (s < 0) { s = 59; m--; }
            if (m < 0) { m = 59; h--; }
            if (h < 0) { h = 12; m = 0; s = 0; }
            elH.textContent = String(h).padStart(2, '0');
            elM.textContent = String(m).padStart(2, '0');
            elS.textContent = String(s).padStart(2, '0');
        }, 1000);
    }
});
</script>
