<div class="checkout-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= base_url(); ?>">Beranda</a>
            <span class="separator">›</span>
            <a href="<?= base_url('keranjang'); ?>">Keranjang</a>
            <span class="separator">›</span>
            <span>Checkout</span>
        </div>
        <h1 style="margin-bottom: 2rem;">Checkout</h1>

        <form action="<?= base_url('checkout/process'); ?>" method="post" id="checkoutForm">
        <div class="checkout-grid">
            <div>
                <!-- STEP 1: Shipping Info -->
                <div class="checkout-section">
                    <h3><span class="step-number">1</span> Alamat Pengiriman</h3>
                    <div class="form-row form-row-2">
                        <div class="form-group">
                            <label class="form-label">Nama Penerima</label>
                            <input type="text" name="shipping_name" class="form-control" value="<?= htmlspecialchars($user->name); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="shipping_phone" class="form-control" value="<?= htmlspecialchars($user->phone); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="shipping_address" class="form-control" rows="3" required><?= htmlspecialchars($user->address); ?></textarea>
                    </div>
                    <div class="form-row form-row-3">
                        <div class="form-group">
                            <label class="form-label">Provinsi *</label>
                            <select name="shipping_province" id="shipping_province" class="form-control" required>
                                <option value="">-- Memuat Provinsi... --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kota / Kabupaten *</label>
                            <select name="shipping_city" id="shipping_city" class="form-control" disabled required>
                                <option value="">-- Pilih Provinsi Dahulu --</option>
                            </select>
                            <small style="color: var(--text-muted); font-size: 0.75rem; margin-top: 0.25rem; display: block;">⚡ Ongkir dihitung otomatis saat kota dipilih.</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" name="shipping_postal" id="shipping_postal" class="form-control" value="<?= htmlspecialchars($user->postal_code); ?>" placeholder="5 digit angka">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catatan Pengiriman (opsional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Instruksi khusus untuk kurir / patokan rumah..."></textarea>
                    </div>
                </div>

                <!-- STEP 2: Shipping Method / Courier Selection -->
                <div class="checkout-section">
                    <div class="checkout-step-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                            <h3 style="margin-bottom: 0;"><span class="step-number">2</span> Pilihan Jasa Pengiriman</h3>
                            <span id="shipping_loading_badge" style="display:none; font-size: 0.8rem; color: var(--primary); font-weight: 600; align-items: center; gap: 4px;">
                                <span style="display:inline-block; width: 12px; height: 12px; border: 2px solid var(--primary); border-top-color: transparent; border-radius: 50%; animation: spin 0.6s linear infinite; vertical-align: middle;"></span>
                                Menghitung ongkir live...
                            </span>
                        </div>
                        <span style="font-size: 0.8rem; color: var(--text-muted); background: var(--bg-card); padding: 0.25rem 0.6rem; border-radius: 6px; border: 1px solid var(--border-color);">
                            Estimasi Berat: <strong><?= number_format($total_weight, 0, ',', '.'); ?> gram</strong>
                        </span>
                    </div>

                    <!-- Hidden inputs to submit selected courier data -->
                    <input type="hidden" name="shipping_cost" id="input_shipping_cost" value="<?= $shipping_cost; ?>">
                    <input type="hidden" name="shipping_courier" id="input_shipping_courier" value="<?= htmlspecialchars($shipping_courier_name); ?>">

                    <div class="shipping-options" id="shippingOptionsContainer">
                        <?php foreach ($shipping_options as $index => $opt): ?>
                            <?php 
                                $isSelected = ($selected_shipping_code === $opt['code']); 
                                $badgeClass = 'shipping-badge-primary';
                                if ($opt['is_free']) {
                                    $badgeClass = 'shipping-badge-free';
                                } elseif ($opt['badge'] === 'Cepat Sampai') {
                                    $badgeClass = 'shipping-badge-warning';
                                }
                            ?>
                            <label class="shipping-option <?= $isSelected ? 'selected' : ''; ?>">
                                <div class="shipping-option-left">
                                    <input type="radio" name="shipping_option_code" value="<?= $opt['code']; ?>" 
                                           data-cost="<?= (float)$opt['cost']; ?>" 
                                           data-name="<?= htmlspecialchars($opt['courier_name'] . ' (' . $opt['service_name'] . ')'); ?>"
                                           <?= $isSelected ? 'checked' : ''; ?>>
                                    
                                    <div class="shipping-icon-box">
                                        <?php if ($opt['icon'] === 'zap'): ?>
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                        <?php elseif ($opt['icon'] === 'package' || $opt['icon'] === 'box'): ?>
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                        <?php elseif ($opt['icon'] === 'map-pin'): ?>
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        <?php else: ?>
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                        <?php endif; ?>
                                    </div>

                                    <div class="shipping-info-text">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                            <strong style="color: var(--text-white); font-size: 0.95rem;"><?= htmlspecialchars($opt['courier_name']); ?></strong>
                                            <span style="font-size: 0.85rem; color: var(--primary); font-weight: 600;"><?= htmlspecialchars($opt['service_name']); ?></span>
                                            <?php if (!empty($opt['badge'])): ?>
                                                <span class="shipping-badge <?= $badgeClass; ?>"><?= htmlspecialchars($opt['badge']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.15rem;">
                                            <?= htmlspecialchars($opt['description']); ?> &bull; 
                                            <span style="color: var(--text-white); font-weight: 600;">Tiba: <?= htmlspecialchars($opt['etd']); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shipping-option-price">
                                    <?php if ($opt['cost'] <= 0): ?>
                                        <div class="cost free">GRATIS</div>
                                        <?php if ($opt['original_cost'] > 0): ?>
                                            <div style="font-size: 0.75rem; text-decoration: line-through; color: var(--text-muted);"><?= rupiah($opt['original_cost']); ?></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="cost"><?= rupiah($opt['cost']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- STEP 3: Payment Method -->
                <div class="checkout-section">
                    <h3><span class="step-number">3</span> Metode Pembayaran</h3>
                    <div class="payment-options">
                        <label class="payment-option selected">
                            <input type="radio" name="payment_method" value="bank_transfer" checked>
                            <div>
                                <div style="font-weight: 600; color: var(--text-white); display: flex; align-items: center; gap: 8px;">
                                    <span>Transfer Bank</span>
                                    <?php if (!empty($bank_name)): ?>
                                        <span style="font-size: 0.75rem; background: rgba(99, 102, 241, 0.15); color: var(--primary); padding: 2px 8px; border-radius: 4px; font-weight: 700;"><?= htmlspecialchars($bank_name); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 3px;">
                                    Rekening: <strong style="color: var(--text-white); letter-spacing: 0.5px;"><?= htmlspecialchars($bank_account); ?></strong>
                                    <?php if (!empty($bank_holder)): ?>
                                        &bull; a.n. <strong style="color: var(--text-white);"><?= htmlspecialchars($bank_holder); ?></strong>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cod">
                            <div>
                                <div style="font-weight: 600; color: var(--text-white);">COD (Bayar di Tempat)</div>
                                <div style="font-size: 0.85rem; color: var(--text-muted);">Bayar tunai saat pesanan sampai di alamat Anda</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="cart-summary">
                <h3>Pesanan Anda</h3>
                <?php foreach ($cart_items as $item): ?>
                <div style="display: flex; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid var(--border-light);">
                    <img src="<?= product_image($item->image); ?>" alt="" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.85rem; color: var(--text-white); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($item->name); ?></div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);"><?= $item->quantity; ?>x <?= rupiah($item->sale_price ?: $item->price); ?></div>
                    </div>
                    <div style="font-weight: 600; color: var(--text-white); font-size: 0.85rem; white-space: nowrap;">
                        <?= rupiah(($item->sale_price ?: $item->price) * $item->quantity); ?>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="summary-row" style="margin-top: 1rem;">
                    <span>Subtotal Produk</span>
                    <span id="summary-cart-total"><?= rupiah($cart_total); ?></span>
                </div>
                <div class="summary-row" style="font-size: 0.85rem;">
                    <span>Kurir Terpilih</span>
                    <span id="summary-courier-name" style="color: var(--text-white); font-weight: 600; text-align: right; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars($shipping_courier_name); ?></span>
                </div>
                <div class="summary-row">
                    <span>Ongkos Kirim</span>
                    <span id="summary-shipping-cost">
                        <?= $shipping_cost > 0 ? rupiah($shipping_cost) : '<span style="color:var(--accent-green);font-weight:700;">GRATIS</span>'; ?>
                    </span>
                </div>
                <div class="summary-row total">
                    <span>Total Bayar</span>
                    <span class="amount" id="summary-grand-total"><?= rupiah($grand_total); ?></span>
                </div>

                <div style="margin-top: 1rem; padding: 0.75rem; background: rgba(99, 102, 241, 0.08); border-radius: 8px; border: 1px dashed var(--primary); font-size: 0.78rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--primary); flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    <span>Ongkir & total terhitung otomatis sesuai kurir yang Anda pilih.</span>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-full" style="margin-top: 1.25rem;">
                    Buat Pesanan
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
// Format currency helper
function formatRupiah(number) {
    return 'Rp ' + Math.round(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

const cartSubtotal = <?= (float)$cart_total; ?>;
const optionsContainer = document.getElementById('shippingOptionsContainer');
const loadingBadge = document.getElementById('shipping_loading_badge');
const provinceSelect = document.getElementById('shipping_province');
const citySelect = document.getElementById('shipping_city');

const savedProvince = <?= json_encode(!empty($user->province) ? $user->province : 'DKI Jakarta'); ?>;
const savedCity = <?= json_encode(!empty($user->city) ? $user->city : ''); ?>;

// 1. Setup Click & Realtime Calculation on Courier Option Cards
function bindShippingOptionEvents() {
    document.querySelectorAll('.shipping-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                
                // Highlight selected option
                document.querySelectorAll('.shipping-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');

                // Read selected attributes
                const cost = parseFloat(radio.dataset.cost) || 0;
                const courierName = radio.dataset.name || 'ShopVista Express';

                // Update hidden inputs for form submission
                document.getElementById('input_shipping_cost').value = cost;
                document.getElementById('input_shipping_courier').value = courierName;

                // Update Summary UI in real-time
                document.getElementById('summary-courier-name').innerText = courierName;
                
                const shippingCostEl = document.getElementById('summary-shipping-cost');
                if (cost <= 0) {
                    shippingCostEl.innerHTML = '<span style="color:var(--accent-green);font-weight:700;">GRATIS</span>';
                } else {
                    shippingCostEl.innerText = formatRupiah(cost);
                }

                const grandTotal = cartSubtotal + cost;
                document.getElementById('summary-grand-total').innerText = formatRupiah(grandTotal);
            }
        });
    });
}

// 2. Render dynamic courier cards
function renderShippingOptions(options) {
    if (!optionsContainer || !options || options.length === 0) return;

    const currentSelectedCode = document.querySelector('input[name="shipping_option_code"]:checked')?.value;
    let html = '';

    options.forEach((opt, index) => {
        const isSelected = currentSelectedCode ? (opt.code === currentSelectedCode) : (index === 0);
        let badgeClass = 'shipping-badge-primary';
        if (opt.is_free) {
            badgeClass = 'shipping-badge-free';
        } else if (opt.badge === 'Cepat Sampai' || opt.badge === 'Live RajaOngkir') {
            badgeClass = 'shipping-badge-warning';
        }

        let costHtml = '';
        if (opt.cost <= 0) {
            costHtml = '<div class="cost free">GRATIS</div>';
            if (opt.original_cost > 0) {
                costHtml += '<div style="font-size: 0.75rem; text-decoration: line-through; color: var(--text-muted);">' + formatRupiah(opt.original_cost) + '</div>';
            }
        } else {
            costHtml = '<div class="cost">' + formatRupiah(opt.cost) + '</div>';
        }

        html += `
        <label class="shipping-option ${isSelected ? 'selected' : ''}">
            <div class="shipping-option-left">
                <input type="radio" name="shipping_option_code" value="${opt.code}" 
                       data-cost="${opt.cost}" 
                       data-name="${opt.courier_name} (${opt.service_name})"
                       ${isSelected ? 'checked' : ''}>
                
                <div class="shipping-icon-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                </div>

                <div class="shipping-info-text">
                    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                        <strong style="color: var(--text-white); font-size: 0.95rem;">${opt.courier_name}</strong>
                        <span style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">${opt.service_name}</span>
                        ${opt.badge ? `<span class="shipping-badge ${badgeClass}">${opt.badge}</span>` : ''}
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.15rem;">
                        ${opt.description} &bull; 
                        <span style="color: var(--text-white); font-weight: 600;">Tiba: ${opt.etd}</span>
                    </div>
                </div>
            </div>

            <div class="shipping-option-price">
                ${costHtml}
            </div>
        </label>`;
    });

    optionsContainer.innerHTML = html;
    bindShippingOptionEvents();

    // Trigger update on selected
    const selectedEl = optionsContainer.querySelector('.shipping-option.selected') || optionsContainer.querySelector('.shipping-option');
    if (selectedEl) {
        selectedEl.click();
    }
}

// 3. Fetch live rates from RajaOngkir
function fetchShippingRates(cityName, provinceName = '') {
    if (!cityName || cityName.trim().length < 2) return;

    if (loadingBadge) loadingBadge.style.display = 'inline-flex';

    const formData = new FormData();
    formData.append('city', cityName.trim());
    if (provinceName) {
        formData.append('province', provinceName.trim());
    }

    fetch('<?= base_url("checkout/get_shipping_rates"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (loadingBadge) loadingBadge.style.display = 'none';
        if (data.status === 'success' && data.options && data.options.length > 0) {
            renderShippingOptions(data.options);
        }
    })
    .catch(err => {
        if (loadingBadge) loadingBadge.style.display = 'none';
        console.error('Gagal mengambil tarif pengiriman:', err);
    });
}

// 4. Load Regencies (Kabupaten / Kota) from wilayah.id via Checkout proxy
function loadRegencies(provinceCode, targetCityToSelect = null) {
    if (!citySelect) return;

    citySelect.disabled = true;
    citySelect.innerHTML = '<option value="">-- Memuat Kota / Kabupaten... --</option>';

    fetch('<?= base_url("checkout/get_regencies/"); ?>' + provinceCode)
    .then(res => res.json())
    .then(resData => {
        const regencies = resData.data || [];
        citySelect.innerHTML = '<option value="">-- Pilih Kota / Kabupaten --</option>';

        let matchedOpt = null;

        regencies.forEach(reg => {
            const opt = document.createElement('option');
            opt.value = reg.name;
            opt.textContent = reg.name;
            opt.dataset.code = reg.code;

            if (targetCityToSelect && reg.name.toLowerCase().includes(targetCityToSelect.toLowerCase())) {
                opt.selected = true;
                matchedOpt = opt;
            }

            citySelect.appendChild(opt);
        });

        citySelect.disabled = false;

        // Auto select first or matched
        if (matchedOpt) {
            matchedOpt.selected = true;
            fetchShippingRates(citySelect.value, provinceSelect.value);
        } else if (regencies.length > 0) {
            citySelect.selectedIndex = 1; // Pick the first actual city
            fetchShippingRates(citySelect.value, provinceSelect.value);
        }
    })
    .catch(err => {
        citySelect.disabled = false;
        citySelect.innerHTML = '<option value="">-- Gagal Memuat Kota --</option>';
        console.error('Gagal memuat regencies:', err);
    });
}

// 5. Initialize Provinces from wilayah.id
function initWilayah() {
    if (!provinceSelect) return;

    fetch('<?= base_url("checkout/get_provinces"); ?>')
    .then(res => res.json())
    .then(resData => {
        const provinces = resData.data || [];
        provinceSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';

        let selectedCode = null;

        provinces.forEach(prov => {
            const opt = document.createElement('option');
            opt.value = prov.name;
            opt.textContent = prov.name;
            opt.dataset.code = prov.code;

            // Pre-select if matches savedProvince or default DKI Jakarta
            if (savedProvince && prov.name.toLowerCase().includes(savedProvince.toLowerCase())) {
                opt.selected = true;
                selectedCode = prov.code;
            }

            provinceSelect.appendChild(opt);
        });

        if (selectedCode) {
            loadRegencies(selectedCode, savedCity);
        }
    })
    .catch(err => {
        provinceSelect.innerHTML = '<option value="">-- Gagal Memuat Provinsi --</option>';
        console.error('Gagal memuat provinsi:', err);
    });
}

// Event Listeners for Province and City Select
if (provinceSelect) {
    provinceSelect.addEventListener('change', function() {
        const selectedOpt = this.options[this.selectedIndex];
        const provCode = selectedOpt ? selectedOpt.dataset.code : null;

        if (provCode) {
            loadRegencies(provCode);
        } else {
            if (citySelect) {
                citySelect.disabled = true;
                citySelect.innerHTML = '<option value="">-- Pilih Provinsi Dahulu --</option>';
            }
        }
    });
}

if (citySelect) {
    citySelect.addEventListener('change', function() {
        if (this.value) {
            fetchShippingRates(this.value, provinceSelect.value);
        }
    });
}

// Run Wilayah Initialization
initWilayah();

// Initial bind for any existing options
bindShippingOptionEvents();

// Setup Payment Option Click
document.querySelectorAll('.payment-option').forEach(opt => {
    opt.addEventListener('click', function() {
        const radio = this.querySelector('input[type="radio"]');
        if (radio) {
            radio.checked = true;
            document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
        }
    });
});
</script>

<style>
/* Responsive Checkout - Fixed Mobile Layout */
@media (max-width: 768px) {
    .checkout-page {
        padding-top: 1rem !important;
        overflow-x: hidden !important;
        max-width: 100% !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .checkout-page h1 {
        font-size: 1.35rem !important;
        margin-bottom: 1.25rem !important;
    }
    .checkout-grid {
        grid-template-columns: 1fr !important;
        gap: 1.25rem !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    .checkout-section {
        padding: 1.15rem 1rem !important;
        border-radius: 16px !important;
        margin-bottom: 1rem !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
    }
    .checkout-section h3 {
        font-size: 1.05rem !important;
        margin-bottom: 1rem !important;
        padding-bottom: 0.75rem !important;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .checkout-step-header {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 0.65rem !important;
    }
    .shipping-options {
        gap: 0.65rem !important;
        width: 100% !important;
    }
    .shipping-option {
        padding: 0.85rem 0.8rem !important;
        gap: 0.65rem !important;
        flex-direction: column !important;
        align-items: stretch !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .shipping-option-left {
        display: flex !important;
        align-items: flex-start !important;
        gap: 0.65rem !important;
        width: 100% !important;
        min-width: 0 !important;
    }
    .shipping-option input[type="radio"] {
        margin-top: 0.25rem !important;
        flex-shrink: 0 !important;
    }
    .shipping-icon-box {
        width: 36px !important;
        height: 36px !important;
        min-width: 36px !important;
        flex-shrink: 0 !important;
    }
    .shipping-info-text {
        flex: 1 1 auto !important;
        min-width: 0 !important;
        word-break: break-word !important;
    }
    .shipping-option-price {
        width: 100% !important;
        padding-top: 0.55rem !important;
        margin-top: 0.15rem !important;
        border-top: 1px dashed var(--border-color) !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        text-align: right !important;
    }
    .shipping-option-price::before {
        content: 'Biaya Pengiriman:';
        font-size: 0.78rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    .shipping-option-price .cost {
        font-size: 0.95rem !important;
    }
    .payment-option {
        padding: 0.85rem !important;
        gap: 0.75rem !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .payment-option > div {
        min-width: 0 !important;
        word-break: break-word !important;
    }
    .cart-summary {
        padding: 1.15rem 1rem !important;
        border-radius: 16px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    .cart-summary .summary-row span:last-child {
        max-width: 50% !important;
        word-break: break-word !important;
    }
}
</style>
