<div class="cart-page">
    <div class="container">
        <div class="breadcrumb" style="margin-bottom: 1rem;">
            <a href="<?= base_url(); ?>">Beranda</a>
            <span class="separator">›</span>
            <span>Keranjang Belanja</span>
        </div>

        <div class="cart-header-row" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:1.5rem;">
            <h1 style="margin:0;font-size:1.55rem;display:flex;align-items:center;gap:10px;">
                <span>Keranjang Belanja</span>
                <?php if (!empty($cart_items)): ?>
                    <span id="cartHeaderBadge" style="font-size:0.8rem;font-weight:700;color:var(--primary);background:#EEF2FF;padding:3px 10px;border-radius:20px;border:1px solid #E0E7FF;"><?= $cart_count; ?> Item</span>
                <?php endif; ?>
            </h1>
            <?php if (!empty($cart_items)): ?>
                <a href="<?= base_url('katalog'); ?>" class="btn btn-sm btn-outline-primary" style="font-size:0.82rem;display:inline-flex;align-items:center;gap:6px;border-radius:8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span>Lanjut Belanja</span>
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($cart_items)): ?>
        <div class="cart-grid">
            <div class="cart-items-list">
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item" data-cart-id="<?= $item->id; ?>">
                    <div class="cart-item-image">
                        <a href="<?= base_url('produk/' . $item->slug); ?>">
                            <img src="<?= product_image($item->image); ?>" alt="<?= htmlspecialchars($item->name); ?>" loading="lazy">
                        </a>
                    </div>
                    <div class="cart-item-info">
                        <div class="cart-item-top">
                            <h4 class="cart-item-name">
                                <a href="<?= base_url('produk/' . $item->slug); ?>"><?= htmlspecialchars($item->name); ?></a>
                            </h4>
                            <button type="button" class="btn-remove-cart" onclick="ShopVista.removeCartItem(<?= $item->id; ?>)" title="Hapus dari keranjang" aria-label="Hapus produk">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="cart-item-price-wrap">
                            <span class="cart-item-price"><?= rupiah($item->sale_price ?: $item->price); ?></span>
                            <?php if (!empty($item->sale_price) && $item->sale_price < $item->price): ?>
                                <span style="font-size:0.8rem;color:#94A3B8;text-decoration:line-through;"><?= rupiah($item->price); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="cart-item-controls">
                            <div class="quantity-selector">
                                <button type="button" class="qty-btn qty-minus" onclick="ShopVista.changeCartQty(<?= $item->id; ?>, -1)" aria-label="Kurang satu">−</button>
                                <input type="number" class="qty-input" value="<?= $item->quantity; ?>" readonly aria-label="Jumlah">
                                <button type="button" class="qty-btn qty-plus" onclick="ShopVista.changeCartQty(<?= $item->id; ?>, 1)" aria-label="Tambah satu">+</button>
                            </div>
                            <div class="cart-item-subtotal">
                                <span class="subtotal-label">Subtotal:</span>
                                <span class="subtotal-val" id="itemSubtotal_<?= $item->id; ?>"><?= rupiah(($item->sale_price ?: $item->price) * $item->quantity); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--primary);"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <span>Ringkasan Belanja</span>
                </h3>
                <div class="summary-row">
                    <span id="summarySubtotalLabel">Subtotal (<?= $cart_count; ?> item)</span>
                    <span id="summarySubtotalVal" style="font-weight:600;color:#0F172A;"><?= rupiah($cart_total); ?></span>
                </div>
                <div class="summary-row">
                    <span>Ongkos Kirim Estimasi</span>
                    <span id="summaryShippingVal"><?= $shipping_cost > 0 ? rupiah($shipping_cost) : '<span style="color:var(--accent-green);font-weight:700;">GRATIS</span>'; ?></span>
                </div>
                <div id="freeShippingNotice" style="<?= ($cart_total < $free_shipping_min && $free_shipping_min > 0) ? 'display:block;' : 'display:none;'; ?>background: #EEF2FF; border: 1px solid #E0E7FF; border-radius: var(--radius-sm); padding: 0.75rem; margin: 1rem 0; font-size: 0.82rem; color: #4338CA; line-height: 1.45;">
                    💡 Belanja <strong><?= rupiah(max(0, $free_shipping_min - $cart_total)); ?></strong> lagi untuk menikmati <strong>Gratis Ongkir</strong>!
                </div>
                <div class="summary-row total">
                    <span>Total Pembayaran</span>
                    <span class="amount" id="summaryGrandTotal"><?= rupiah($grand_total); ?></span>
                </div>
                <a href="<?= base_url('checkout'); ?>" class="btn btn-primary btn-lg btn-full d-none-mobile" style="margin-top: 1.5rem; justify-content: center; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);">
                    <span>Lanjut ke Pembayaran</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="empty-state" style="padding: 3.5rem 1.5rem; text-align: center; background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 16px; margin: 1rem 0;">
            <div class="empty-icon" style="width: 80px; height: 80px; border-radius: 50%; background: #EEF2FF; color: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            </div>
            <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #0F172A;">Keranjang Belanja Masih Kosong</h3>
            <p style="color: #64748B; max-width: 360px; margin: 0 auto 1.75rem; font-size: 0.95rem;">Temukan berbagai produk pilihan impian kamu dan nikmati promo menarik hari ini!</p>
            <a href="<?= base_url('katalog'); ?>" class="btn btn-primary btn-lg" style="padding: 12px 28px; border-radius: 10px; font-weight: 700;">
                Mulai Belanja Sekarang
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($cart_items)): ?>
<!-- Sticky Bottom Bar on Mobile (Pinned to bottom of viewport) -->
<div class="cart-mobile-sticky-bar" id="cartMobileStickyBar" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 99999;">
    <div class="sticky-bar-info">
        <span class="sticky-bar-label" id="stickyBarLabel">Total Belanja (<?= $cart_count; ?> item)</span>
        <span class="sticky-bar-amount" id="stickyBarAmount"><?= rupiah($grand_total); ?></span>
    </div>
    <a href="<?= base_url('checkout'); ?>" class="btn btn-primary sticky-checkout-btn">
        <span>Checkout</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
    </a>
</div>
<?php endif; ?>

<script>
// Standalone AJAX Handler for Cart Quantity
window.ShopVista = window.ShopVista || {};
window.ShopVista.baseUrl = window.ShopVista.baseUrl || '<?= base_url(); ?>';

window.ShopVista.changeCartQty = function(cartId, delta) {
    const itemEl = document.querySelector('[data-cart-id="' + cartId + '"]');
    if (!itemEl) return;
    const inputEl = itemEl.querySelector('.qty-input');
    if (!inputEl) return;

    let currentQty = parseInt(inputEl.value) || 1;
    let newQty = Math.max(1, currentQty + delta);

    if (newQty === currentQty) return;

    // Optimistic UI update
    inputEl.value = newQty;

    const subtotalEl = document.getElementById('itemSubtotal_' + cartId);
    if (subtotalEl) subtotalEl.style.opacity = '0.5';

    const formData = new FormData();
    formData.append('cart_id', cartId);
    formData.append('quantity', newQty);

    fetch('<?= base_url("cart/update"); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (subtotalEl) subtotalEl.style.opacity = '1';

        if (data.status === 'success') {
            // Update item subtotal
            if (subtotalEl && data.item_subtotal) {
                subtotalEl.textContent = data.item_subtotal;
            }

            // Ensure input matches server
            if (inputEl && data.quantity) {
                inputEl.value = data.quantity;
            }

            // Update header badge
            const headerBadge = document.getElementById('cartHeaderBadge');
            if (headerBadge && data.cart_count !== undefined) {
                headerBadge.textContent = data.cart_count + ' Item';
            }

            // Update subtotal summary
            const subtotalLabel = document.getElementById('summarySubtotalLabel');
            if (subtotalLabel && data.cart_count !== undefined) {
                subtotalLabel.textContent = 'Subtotal (' + data.cart_count + ' item)';
            }
            const subtotalVal = document.getElementById('summarySubtotalVal');
            if (subtotalVal && data.cart_total) {
                subtotalVal.textContent = data.cart_total;
            }

            // Update shipping
            const shippingVal = document.getElementById('summaryShippingVal');
            if (shippingVal && data.shipping_cost !== undefined) {
                if (data.is_free_shipping || data.shipping_cost === 'GRATIS') {
                    shippingVal.innerHTML = '<span style="color:var(--accent-green);font-weight:700;">GRATIS</span>';
                } else {
                    shippingVal.textContent = data.shipping_cost;
                }
            }

            // Update free shipping banner
            const freeNotice = document.getElementById('freeShippingNotice');
            if (freeNotice) {
                if (data.is_free_shipping || data.free_shipping_needed_raw <= 0) {
                    freeNotice.style.display = 'none';
                } else {
                    freeNotice.style.display = 'block';
                    freeNotice.innerHTML = '💡 Belanja <strong>' + data.free_shipping_needed + '</strong> lagi untuk menikmati <strong>Gratis Ongkir</strong>!';
                }
            }

            // Update grand total
            const grandTotal = document.getElementById('summaryGrandTotal');
            if (grandTotal && data.grand_total) {
                grandTotal.textContent = data.grand_total;
            }

            // Update mobile sticky bar
            const stickyLabel = document.getElementById('stickyBarLabel');
            if (stickyLabel && data.cart_count !== undefined) {
                stickyLabel.textContent = 'Total Belanja (' + data.cart_count + ' item)';
            }
            const stickyAmount = document.getElementById('stickyBarAmount');
            if (stickyAmount && data.grand_total) {
                stickyAmount.textContent = data.grand_total;
            }

            // Update navbar count
            if (typeof window.ShopVista.updateCartCount === 'function') {
                window.ShopVista.updateCartCount();
            } else {
                const navBadges = document.querySelectorAll('.cart-count');
                navBadges.forEach(b => {
                    b.textContent = data.cart_count;
                    b.style.display = data.cart_count > 0 ? 'flex' : 'none';
                });
            }
        }
    })
    .catch(() => {
        if (subtotalEl) subtotalEl.style.opacity = '1';
    });
};
</script>


