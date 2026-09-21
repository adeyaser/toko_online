<div class="order-detail-page">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?= base_url(); ?>">Beranda</a>
            <span class="separator">›</span>
            <a href="<?= base_url('profil'); ?>">Profil Saya</a>
            <span class="separator">›</span>
            <span>Pesanan #<?= $order->order_number; ?></span>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success" style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.75rem; background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; border-radius: var(--radius-md); padding: 0.85rem 1.15rem;">
            <i data-feather="check-circle" style="width:20px;height:20px;color:#059669;flex-shrink:0;"></i>
            <div style="font-size: 0.92rem; font-weight: 600;"><?= $this->session->flashdata('success'); ?></div>
        </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger" style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.75rem; background: #FEF2F2; border: 1px solid #FCA5A5; color: #991B1B; border-radius: var(--radius-md); padding: 0.85rem 1.15rem;">
            <i data-feather="alert-circle" style="width:20px;height:20px;color:#DC2626;flex-shrink:0;"></i>
            <div style="font-size: 0.92rem; font-weight: 600;"><?= $this->session->flashdata('error'); ?></div>
        </div>
        <?php endif; ?>

        <!-- Header Box -->
        <div class="order-header-box">
            <div class="order-header-left">
                <div class="order-header-title-row">
                    <h1>Detail Pesanan</h1>
                    <span class="order-id-chip" onclick="copyOrderNumber('<?= $order->order_number; ?>')" title="Klik untuk menyalin nomor pesanan">
                        <i data-feather="copy" style="width:13px;height:13px;"></i>
                        <span><?= $order->order_number; ?></span>
                    </span>
                </div>
                <div class="order-header-meta">
                    <span><i data-feather="calendar" style="width:14px;height:14px;margin-right:4px;"></i> <?= date('d M Y, H:i', strtotime($order->created_at)); ?> WIB</span>
                    <span>•</span>
                    <span><i data-feather="credit-card" style="width:14px;height:14px;margin-right:4px;"></i> <?= $order->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'COD (Bayar di Tempat)'; ?></span>
                </div>
            </div>

            <div class="order-header-actions">
                <div style="margin-right: 0.5rem;">
                    <?= order_status_badge($order->status); ?>
                </div>
                <a href="<?= base_url('profil/invoice/' . $order->order_number); ?>" target="_blank" class="btn btn-secondary btn-sm" title="Cetak / Download Invoice PDF">
                    <i data-feather="file-text" style="width:14px;height:14px;color:var(--primary);"></i>
                    <span>Cetak Invoice (PDF)</span>
                </a>
                <?php
                $store_wa_raw = get_setting('whatsapp', '081298765432');
                $store_wa = format_whatsapp_number($store_wa_raw) ?: '6281298765432';
                ?>
                <a href="https://wa.me/<?= $store_wa; ?>?text=<?= rawurlencode('Halo Admin ShopVista, saya ingin bertanya tentang pesanan ' . $order->order_number); ?>" target="_blank" class="btn btn-secondary btn-sm" style="color:#059669;border-color:#A7F3D0;background:#ECFDF5;">
                    <i data-feather="message-circle" style="width:14px;height:14px;"></i>
                    <span>Bantuan CS</span>
                </a>
                <a href="<?= base_url('profil'); ?>" class="btn btn-secondary btn-sm">
                    <i data-feather="arrow-left" style="width:14px;height:14px;"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <?php
        $courier_name = !empty($order->shipping_courier) ? $order->shipping_courier : 'ShopVista Express';
        $has_real_resi = !empty($order->tracking_number);
        $resi = $has_real_resi ? $order->tracking_number : null;
        ?>

        <!-- Stepper / Status Tracking Timeline -->
        <?php if ($order->status == 'cancelled'): ?>
        <div class="alert alert-danger" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
            <i data-feather="alert-triangle" style="width:24px;height:24px;flex-shrink:0;"></i>
            <div>
                <strong>Pesanan Dibatalkan</strong>
                <p style="margin: 0; font-size: 0.88rem;">Pesanan ini telah dibatalkan. Jika Anda memiliki pertanyaan atau kendala pembayaran, silakan hubungi layanan pelanggan kami.</p>
            </div>
        </div>
        <?php else: ?>
        <?php
        // Stepper state calculation
        $steps = [
            'pending' => 1,
            'processing' => 2,
            'shipped' => 3,
            'delivered' => 4
        ];
        $current_step = isset($steps[$order->status]) ? $steps[$order->status] : 1;
        // Progress fill percentage (0% to 100% across the 3 spans)
        $progress_pct = min(100, max(0, ($current_step - 1) * (100 / 3)));
        ?>

        <div class="order-stepper-card">
            <div class="order-stepper-header-row">
                <div class="order-stepper-title">
                    <i data-feather="navigation" style="width:20px;height:20px;color:var(--primary);"></i>
                    <span>Status Pelacakan Pesanan</span>
                </div>
                <div style="display: flex; gap: 0.6rem; align-items: center; flex-wrap: wrap;">
                    <div class="order-stepper-courier-tag">
                        <span class="courier-dot"></span>
                        <span><?= htmlspecialchars($courier_name); ?></span>
                        <span style="opacity: 0.5;">•</span>
                        <?php if ($has_real_resi): ?>
                        <span>Resi: <strong style="font-family: monospace; font-size: 0.88rem;"><?= htmlspecialchars($resi); ?></strong></span>
                        <?php else: ?>
                        <span style="color: #64748B; font-style: italic; font-size: 0.82rem;">Resi belum terbit</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($has_real_resi): ?>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openLiveTrackingModal()" style="padding: 0.35rem 0.75rem; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 6px; box-shadow: 0 2px 6px rgba(37,99,235,0.2);">
                        <i data-feather="truck" style="width:13px;height:13px;"></i> Lacak Ekspedisi Live
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="order-stepper-wrapper">
                <!-- Continuous Timeline Track Behind Steps -->
                <div class="order-stepper-track">
                    <div class="order-stepper-track-fill" style="width: <?= number_format($progress_pct, 2, '.', ''); ?>%;"></div>
                </div>

                <div class="order-stepper-steps">
                    <!-- Step 1: Pesanan Dibuat -->
                    <div class="order-step <?= $current_step > 1 ? 'completed' : ($current_step == 1 ? 'active' : 'pending'); ?>">
                        <div class="order-step-icon-wrap">
                            <?php if ($current_step > 1): ?>
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <?php else: ?>
                            <i data-feather="file-text" style="width:22px;height:22px;stroke-width:2.2;color:#FFFFFF;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="order-step-info">
                            <div class="order-step-label">Pesanan Dibuat</div>
                            <div class="order-step-time"><?= date('d M, H:i', strtotime($order->created_at)); ?> WIB</div>
                            <?php if ($current_step == 1): ?>
                            <div class="step-active-pill"><span class="pulse-dot"></span> Menunggu Pembayaran</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Step 2: Diproses & Dikemas -->
                    <div class="order-step <?= $current_step > 2 ? 'completed' : ($current_step == 2 ? 'active' : 'pending'); ?>">
                        <div class="order-step-icon-wrap">
                            <?php if ($current_step > 2): ?>
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <?php elseif ($current_step == 2): ?>
                            <i data-feather="package" style="width:22px;height:22px;stroke-width:2.2;color:#FFFFFF;"></i>
                            <?php else: ?>
                            <i data-feather="package" style="width:20px;height:20px;stroke-width:1.8;color:#94A3B8;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="order-step-info">
                            <div class="order-step-label">Diproses & Dikemas</div>
                            <div class="order-step-time"><?= $current_step >= 2 ? 'Penjual Menyiapkan Barang' : 'Menunggu Diproses'; ?></div>
                            <?php if ($current_step == 2): ?>
                            <div class="step-active-pill"><span class="pulse-dot"></span> Sedang Dikemas</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Step 3: Dalam Pengiriman -->
                    <div class="order-step <?= $current_step > 3 ? 'completed' : ($current_step == 3 ? 'active' : 'pending'); ?>">
                        <div class="order-step-icon-wrap">
                            <?php if ($current_step > 3): ?>
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <?php elseif ($current_step == 3): ?>
                            <i data-feather="truck" style="width:22px;height:22px;stroke-width:2.2;color:#FFFFFF;"></i>
                            <?php else: ?>
                            <i data-feather="truck" style="width:20px;height:20px;stroke-width:1.8;color:#94A3B8;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="order-step-info">
                            <div class="order-step-label">Dalam Pengiriman</div>
                            <div class="order-step-time"><?= $current_step >= 3 ? 'Dalam Perjalanan Kurir' : 'Estimasi 1-3 Hari'; ?></div>
                            <?php if ($current_step == 3): ?>
                            <div class="step-active-pill"><span class="pulse-dot"></span> Sedang Berjalan</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Step 4: Pesanan Selesai -->
                    <div class="order-step <?= $current_step >= 4 ? 'completed active' : 'pending'; ?>">
                        <div class="order-step-icon-wrap">
                            <?php if ($current_step >= 4): ?>
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <?php else: ?>
                            <i data-feather="check" style="width:20px;height:20px;stroke-width:2.2;color:#CBD5E1;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="order-step-info">
                            <div class="order-step-label">Pesanan Selesai</div>
                            <div class="order-step-time"><?= $current_step >= 4 ? 'Pesanan Telah Tiba' : 'Konfirmasi Penerimaan'; ?></div>
                            <?php if ($current_step >= 4): ?>
                            <div class="step-active-pill" style="background:#ECFDF5;color:#059669;"><span class="pulse-dot" style="background:#10B981;"></span> Selesai</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 2-Column Detail Grid -->
        <div class="order-detail-grid">
            <!-- Left Column: Products, Shipping, Payment Proof -->
            <div class="order-main-col">
                <!-- Items Card -->
                <div class="order-card">
                    <div class="order-card-header">
                        <div class="order-card-title">
                            <i data-feather="shopping-bag" style="width:18px;height:18px;color:var(--primary);"></i>
                            <span>Daftar Produk yang Dipesan (<?= count($order_items); ?>)</span>
                        </div>
                    </div>

                    <div class="order-items-list">
                        <?php foreach ($order_items as $item): ?>
                        <div class="order-item-box">
                            <img src="<?= product_image($item->product_image); ?>" alt="<?= htmlspecialchars($item->product_name); ?>" class="order-item-img">
                            <div class="order-item-info">
                                <a href="<?= base_url('produk/' . (!empty($item->product_slug) ? $item->product_slug : url_title($item->product_name, 'dash', TRUE))); ?>" class="order-item-name">
                                    <?= htmlspecialchars($item->product_name); ?>
                                </a>
                                <div class="order-item-meta">
                                    <span>Harga Satuan: <?= rupiah($item->price); ?></span>
                                    <span>•</span>
                                    <span style="font-weight: 700; color: #0F172A;">Qty: <?= $item->quantity; ?> item</span>
                                </div>
                                <div style="margin-top: 0.5rem; display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('produk/' . (!empty($item->product_slug) ? $item->product_slug : url_title($item->product_name, 'dash', TRUE))); ?>" class="btn btn-secondary btn-sm" style="font-size:0.75rem;padding:0.25rem 0.6rem;">
                                        Lihat Produk
                                    </a>
                                    <button type="button" class="btn btn-primary btn-sm" style="font-size:0.75rem;padding:0.25rem 0.6rem;" onclick="ShopVista.addToCart(<?= $item->product_id; ?>, 1)">
                                        Beli Lagi
                                    </button>
                                </div>
                            </div>
                            <div class="order-item-subtotal">
                                <?= rupiah($item->subtotal); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Shipping & Courier Info Card -->
                <div class="order-card">
                    <div class="order-card-header">
                        <div class="order-card-title">
                            <i data-feather="truck" style="width:18px;height:18px;color:var(--primary);"></i>
                            <span>Informasi Pengiriman & Kurir</span>
                        </div>
                    </div>

                    <div class="shipping-info-grid">
                        <!-- Recipient Card -->
                        <div class="shipping-subcard">
                            <div class="shipping-subcard-title">
                                <i data-feather="map-pin" style="width:13px;height:13px;color:var(--primary);"></i>
                                <span>Alamat Penerima</span>
                            </div>
                            <div style="font-weight: 700; color: #0F172A; font-size: 0.95rem; margin-bottom: 2px;">
                                <?= htmlspecialchars($order->shipping_name); ?>
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <a href="tel:<?= $order->shipping_phone; ?>" style="color: var(--primary); text-decoration: none; font-size: 0.88rem; font-weight: 500;">
                                    <i data-feather="phone" style="width:12px;height:12px;"></i> <?= htmlspecialchars($order->shipping_phone); ?>
                                </a>
                            </div>
                            <div style="color: #475569; font-size: 0.88rem; line-height: 1.5;">
                                <?= nl2br(htmlspecialchars($order->shipping_address)); ?><br>
                                <?= htmlspecialchars($order->shipping_city); ?>, <?= htmlspecialchars($order->shipping_province); ?> <?= htmlspecialchars($order->shipping_postal); ?>
                            </div>
                            <?php if (!empty($order->notes)): ?>
                            <div style="margin-top: 0.75rem; padding: 0.5rem 0.75rem; background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 6px; font-size: 0.82rem; color: #92400E;">
                                <strong>Catatan:</strong> <?= htmlspecialchars($order->notes); ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Courier Card -->
                        <div class="shipping-subcard">
                            <div class="shipping-subcard-title" style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i data-feather="package" style="width:13px;height:13px;color:var(--primary);"></i>
                                    <span>Layanan Ekspedisi</span>
                                </div>
                                <?php if ($order->status == 'pending'): ?>
                                <button type="button" class="btn btn-outline btn-sm" onclick="openChangeShippingModal()" style="font-size: 0.76rem; padding: 0.25rem 0.65rem; border-color: var(--primary); color: var(--primary); display: inline-flex; align-items: center; gap: 4px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                    <i data-feather="edit-2" style="width:12px;height:12px;"></i> Ubah Ekspedisi
                                </button>
                                <?php endif; ?>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem; margin-top: 0.5rem; flex-wrap: wrap; gap: 0.35rem;">
                                <span style="font-weight: 700; color: #0F172A; font-size: 0.95rem;"><?= htmlspecialchars($courier_name); ?></span>
                                <?php if ($order->shipping_cost == 0): ?>
                                    <span class="badge-shipping" style="background:#ECFDF5; color:#059669; border: 1px solid #A7F3D0;">Bebas Ongkir</span>
                                <?php else: ?>
                                    <span class="badge-shipping" style="background:#EFF6FF; color:#2563EB; border: 1px solid #BFDBFE; font-weight:700;"><?= rupiah($order->shipping_cost); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($order->status == 'pending'): ?>
                            <div style="font-size: 0.8rem; color: #059669; margin-bottom: 0.65rem; display: flex; align-items: center; gap: 5px; background: #F0FDF4; padding: 4px 8px; border-radius: 6px; border: 1px solid #DCFCE7;">
                                <i data-feather="info" style="width:12px;height:12px;flex-shrink:0;"></i>
                                <span>Kurir/layanan ekspedisi masih dapat diubah sebelum Anda melakukan pembayaran.</span>
                            </div>
                            <?php else: ?>
                            <div style="font-size: 0.85rem; color: #64748B; margin-bottom: 0.5rem;">
                                Layanan Pengiriman Resmi • Estimasi tiba 1 - 3 Hari Kerja
                            </div>
                            <?php endif; ?>
                            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 0.65rem 0.85rem; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="font-size: 0.72rem; color: #94A3B8; text-transform: uppercase; font-weight: 600;">No. Resi Pengiriman</div>
                                    <?php if ($has_real_resi): ?>
                                    <div style="font-family: monospace; font-size: 0.9rem; font-weight: 700; color: #0F172A;"><?= htmlspecialchars($resi); ?></div>
                                    <?php else: ?>
                                    <div style="font-size: 0.85rem; color: #64748B; font-style: italic;">Belum Terbit (Menunggu Pengiriman)</div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($has_real_resi): ?>
                                <div style="display:flex;gap:0.35rem;">
                                    <button type="button" class="btn btn-primary btn-sm" style="padding: 0.25rem 0.55rem; font-size: 0.75rem;" onclick="openLiveTrackingModal()">
                                        Lacak
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 0.25rem 0.55rem; font-size: 0.75rem;" onclick="copyResi('<?= htmlspecialchars($resi); ?>')">
                                        Salin
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Verification / Proof Card -->
                <?php if ($order->payment_method == 'bank_transfer'): ?>
                <div class="order-card">
                    <div class="order-card-header">
                        <div class="order-card-title">
                            <i data-feather="credit-card" style="width:18px;height:18px;color:var(--primary);"></i>
                            <span>Status Pembayaran & Rekening Toko</span>
                        </div>
                        <div>
                            <?php if ($order->status == 'pending'): ?>
                            <span class="badge badge-warning">Menunggu Pembayaran</span>
                            <?php else: ?>
                            <span class="badge badge-success"><i data-feather="check" style="width:12px;height:12px;margin-right:2px;"></i> Pembayaran Terverifikasi</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($order->status == 'pending'): ?>
                    <div style="background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: var(--radius-lg); padding: 1.25rem; margin-bottom: 1.25rem;">
                        <div style="font-weight: 700; color: #1E1B4B; margin-bottom: 0.35rem;">Silakan Lakukan Transfer ke Rekening Resmi:</div>
                        <div style="font-size: 0.88rem; color: #4338CA; margin-bottom: 1rem;">Pastikan nominal transfer tepat hingga digit terakhir agar otomatis terverifikasi.</div>

                        <div style="background: #FFFFFF; border: 1px solid #E0E7FF; border-radius: var(--radius-md); padding: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                            <div>
                                <div style="font-size: 0.8rem; color: #64748B;">Bank Tujuan:</div>
                                <div style="font-weight: 800; color: #0F172A; font-size: 1.05rem;"><?= get_setting('bank_name') ?: 'BCA (Bank Central Asia)'; ?></div>
                                <div style="font-size: 0.85rem; color: #64748B; margin-top: 2px;">a.n <?= get_setting('bank_holder') ?: 'ShopVista Indonesia'; ?></div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 0.8rem; color: #64748B;">Nomor Rekening:</div>
                                <?php $rek = get_setting('bank_account') ?: '8830192831'; ?>
                                <div style="font-family: monospace; font-size: 1.2rem; font-weight: 800; color: var(--primary);"><?= $rek; ?></div>
                                <button type="button" class="btn btn-secondary btn-sm" style="margin-top: 4px; padding: 0.2rem 0.6rem; font-size: 0.75rem;" onclick="copyRek('<?= $rek; ?>')">
                                    <i data-feather="copy" style="width:12px;height:12px;"></i> Salin No. Rek
                                </button>
                            </div>
                        </div>

                        <!-- Upload Proof Form -->
                        <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px dashed #C7D2FE;">
                            <form id="formPaymentProof" action="<?= base_url('profil/pesanan/' . $order->order_number); ?>" method="post" enctype="multipart/form-data">
                                <label style="display: block; font-weight: 600; font-size: 0.88rem; color: #1E1B4B; margin-bottom: 0.35rem;">
                                    Upload Struk / Bukti Transfer (Opsional untuk percepat proses):
                                </label>
                                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                                    <input type="file" id="inputPaymentProof" name="payment_proof" accept="image/*,.pdf" class="form-control" style="background:#FFFFFF; font-size: 0.85rem; flex: 1; min-width: 220px;" required>
                                    <button type="submit" id="btnSubmitProof" class="btn btn-primary" style="white-space: nowrap; font-size: 0.85rem; padding: 0.65rem 1.15rem; background: #059669; border-color: #059669; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25); cursor: pointer;">
                                        <i data-feather="send" style="width:14px;height:14px;"></i>
                                        <span>Kirim Bukti ke WhatsApp</span>
                                    </button>
                                </div>
                                <div id="proofPreviewContainer" style="display: none; margin-top: 0.75rem; align-items: center; gap: 10px; padding: 8px 12px; background: #FFFFFF; border: 1px solid #C7D2FE; border-radius: 8px;">
                                    <img id="proofPreviewImg" src="" alt="Preview Bukti" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #E2E8F0;">
                                    <div style="flex: 1; font-size: 0.82rem; overflow: hidden;">
                                        <div id="proofFileName" style="font-weight: 700; color: #1E1B4B; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;"></div>
                                        <div id="proofFileSize" style="color: #64748B; font-size: 0.75rem;"></div>
                                    </div>
                                    <span style="font-size: 0.75rem; color: #059669; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                        <i data-feather="check-circle" style="width:14px;height:14px;"></i> Siap Kirim ke WA
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php else: ?>
                    <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: var(--radius-lg); padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: #10B981; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i data-feather="check" style="width:24px;height:24px;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #065F46; font-size: 0.98rem;">Pembayaran Sukses Diverifikasi</div>
                            <div style="color: #047857; font-size: 0.85rem; margin-top: 2px;">Terima kasih! Pembayaran sebesar <strong><?= rupiah($order->grand_total); ?></strong> via <?= $order->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'COD'; ?> telah kami terima dan pesanan Anda langsung diproses.</div>
                            <?php if (!empty($order->payment_proof)): ?>
                            <div style="margin-top: 0.5rem;">
                                <a href="<?= base_url('assets/images/payments/' . $order->payment_proof); ?>" target="_blank" class="btn btn-secondary btn-sm" style="font-size:0.75rem;">
                                    <i data-feather="external-link" style="width:12px;height:12px;"></i> Lihat Bukti Transfer
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Invoice Summary & Trust Box -->
            <div class="order-sidebar-col">
                <div class="order-sidebar-sticky">
                    <!-- Payment Summary Card -->
                    <div class="order-card" style="margin-bottom: 0;">
                        <div class="order-card-header">
                            <div class="order-card-title">
                                <i data-feather="file-text" style="width:18px;height:18px;color:var(--primary);"></i>
                                <span>Rincian Pembayaran</span>
                            </div>
                        </div>

                        <div class="order-summary-row">
                            <span>Total Harga (<?= count($order_items); ?> Produk)</span>
                            <span style="font-weight: 600; color: #0F172A;"><?= rupiah($order->total); ?></span>
                        </div>
                        <div class="order-summary-row">
                            <span>Total Ongkos Kirim</span>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="color: var(--accent-green); font-weight: 700;">
                                    <?= $order->shipping_cost > 0 ? rupiah($order->shipping_cost) : 'GRATIS'; ?>
                                </span>
                                <?php if ($order->status == 'pending'): ?>
                                <button type="button" onclick="openChangeShippingModal()" style="background: none; border: none; padding: 0; color: var(--primary); font-size: 0.75rem; text-decoration: underline; cursor: pointer; font-weight: 600;">
                                    (Ubah)
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="order-summary-row">
                            <span>Biaya Asuransi Pengiriman</span>
                            <span style="color: var(--accent-green); font-weight: 700;">GRATIS</span>
                        </div>
                        <div class="order-summary-row">
                            <span>Biaya Layanan</span>
                            <span style="color: #0F172A;">Rp0</span>
                        </div>

                        <div class="order-summary-row total">
                            <div>
                                <div>Total Pembayaran</div>
                                <div style="font-size: 0.75rem; color: #64748B; font-weight: 400;"><?= $order->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'COD (Bayar di Tempat)'; ?></div>
                            </div>
                            <div class="order-summary-total-amount">
                                <?= rupiah($order->grand_total); ?>
                            </div>
                        </div>

                        <div style="margin-top: 1.25rem; padding: 0.85rem; background: #F8FAFC; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 0.82rem; color: #64748B; display: flex; align-items: center; gap: 8px;">
                            <i data-feather="shield" style="width:16px;height:16px;color:#10B981;flex-shrink:0;"></i>
                            <span>Transaksi aman dilindungi oleh sistem keamanan ShopVista</span>
                        </div>

                        <div style="margin-top: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem;">
                            <a href="<?= base_url('profil/invoice/' . $order->order_number); ?>" target="_blank" class="btn btn-outline" style="width: 100%; justify-content: center; display: inline-flex; align-items: center; gap: 6px;">
                                <i data-feather="file-text" style="width:15px;height:15px;color:var(--primary);"></i>
                                <span>Cetak Invoice (PDF)</span>
                            </a>
                            <a href="<?= base_url('katalog'); ?>" class="btn btn-primary" style="width: 100%; justify-content: center;">
                                <i data-feather="shopping-bag" style="width:15px;height:15px;"></i> Lanjut Belanja
                            </a>
                        </div>
                    </div>

                    <!-- Customer Care Box -->
                    <div class="order-card" style="background: linear-gradient(135deg, #F8FAFC 0%, #EEF2FF 100%); margin-bottom: 0;">
                        <div style="font-weight: 700; color: #0F172A; font-size: 0.95rem; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 6px;">
                            <i data-feather="help-circle" style="width:16px;height:16px;color:var(--primary);"></i>
                            <span>Ada Kendala Pesanan?</span>
                        </div>
                        <p style="font-size: 0.82rem; color: #64748B; margin-bottom: 0.85rem; line-height: 1.4;">
                            Tim Layanan Pelanggan kami siap membantu status pesanan, kurir, dan kendala pembayaran 24 jam.
                        </p>
                        <a href="https://wa.me/<?= $store_wa; ?>?text=<?= rawurlencode('Halo Admin ShopVista, mohon bantuan untuk pesanan ' . $order->order_number); ?>" target="_blank" class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center; background: #FFFFFF; color: #059669; border-color: #A7F3D0; font-weight: 600;">
                            <i data-feather="message-circle" style="width:14px;height:14px;"></i> Chat WhatsApp CS
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($order->status == 'pending'): ?>
<!-- Change Shipping Modal -->
<div id="modalChangeShipping" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15,23,42,0.65);z-index:9999;backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#FFFFFF;border-radius:16px;max-width:560px;width:100%;max-height:92vh;display:flex;flex-direction:column;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);border:1px solid #E2E8F0;overflow:hidden;animation:modalFadeIn 0.2s ease-out;">
        <!-- Modal Header -->
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #E2E8F0;display:flex;justify-content:space-between;align-items:center;background:#F8FAFC;">
            <div>
                <h3 style="margin:0;font-size:1.05rem;font-weight:800;color:#0F172A;display:flex;align-items:center;gap:0.5rem;">
                    <i data-feather="truck" style="width:18px;height:18px;color:var(--primary);"></i>
                    Pilih Layanan Ekspedisi
                </h3>
                <div style="font-size:0.8rem;color:#64748B;margin-top:2px;">
                    Pesanan #<?= htmlspecialchars($order->order_number); ?> &bull; Sebelum Pembayaran
                </div>
            </div>
            <button type="button" onclick="closeChangeShippingModal()" style="background:none;border:none;cursor:pointer;color:#64748B;padding:4px;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                <i data-feather="x" style="width:20px;height:20px;"></i>
            </button>
        </div>

        <form action="<?= base_url('profil/ubah_ekspedisi/' . $order->order_number); ?>" method="post" style="display:flex;flex-direction:column;flex:1;overflow:hidden;margin:0;">
            <!-- Modal Body -->
            <div style="padding:1.25rem 1.5rem;overflow-y:auto;flex:1;">
                <!-- Destination & Weight Info -->
                <div style="background:#F1F5F9;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1.25rem;display:flex;justify-content:space-between;align-items:center;font-size:0.82rem;flex-wrap:wrap;gap:0.5rem;">
                    <div>
                        <span style="color:#64748B;">Tujuan:</span>
                        <strong style="color:#0F172A;"><?= htmlspecialchars($order->shipping_city); ?>, <?= htmlspecialchars($order->shipping_province); ?></strong>
                    </div>
                    <div>
                        <span style="color:#64748B;">Berat:</span>
                        <strong style="color:#0F172A;"><?= isset($total_weight) ? number_format($total_weight, 0) : '250'; ?> g</strong>
                    </div>
                </div>

                <div style="font-size:0.82rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.75rem;">
                    Opsi Ekspedisi Tersedia:
                </div>

                <div class="shipping-courier-list" style="display:flex;flex-direction:column;gap:0.75rem;">
                    <?php 
                    $has_checked = false;
                    if (!empty($available_couriers)):
                        foreach ($available_couriers as $chk) {
                            $chk_title = $chk['courier_name'] . ' (' . $chk['service_name'] . ')';
                            if (trim($order->shipping_courier) === trim($chk_title) || 
                                (stripos($order->shipping_courier, $chk['courier_name']) !== false && stripos($order->shipping_courier, $chk['service_name']) !== false)) {
                                $has_checked = true;
                                break;
                            }
                        }

                        foreach ($available_couriers as $idx => $opt): 
                            $opt_title = $opt['courier_name'] . ' (' . $opt['service_name'] . ')';
                            $is_current = false;
                            if (trim($order->shipping_courier) === trim($opt_title) || 
                                (stripos($order->shipping_courier, $opt['courier_name']) !== false && stripos($order->shipping_courier, $opt['service_name']) !== false)) {
                                $is_current = true;
                            } elseif (!$has_checked && $idx === 0) {
                                $is_current = true;
                            }
                    ?>
                        <label class="courier-choice-card <?= $is_current ? 'active-choice' : ''; ?>" style="display:flex;align-items:flex-start;gap:0.75rem;padding:0.85rem 1rem;border:1.5px solid <?= $is_current ? 'var(--primary)' : '#E2E8F0'; ?>;border-radius:10px;cursor:pointer;background:<?= $is_current ? '#F0F7FF' : '#FFFFFF'; ?>;transition:all 0.2s ease;">
                            <input type="radio" name="shipping_courier_code" value="<?= htmlspecialchars($opt['code']); ?>" <?= $is_current ? 'checked' : ''; ?> data-cost="<?= (float)$opt['cost']; ?>" data-name="<?= htmlspecialchars($opt_title); ?>" onchange="onCourierChange(this)" style="margin-top:3px;accent-color:var(--primary);">
                            <div style="flex:1;">
                                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:4px;">
                                    <div style="font-weight:700;font-size:0.92rem;color:#0F172A;">
                                        <?= htmlspecialchars($opt['courier_name']); ?>
                                        <span style="font-weight:500;color:#64748B;font-size:0.85rem;">- <?= htmlspecialchars($opt['service_name']); ?></span>
                                    </div>
                                    <div style="font-weight:800;font-size:0.95rem;color:<?= $opt['cost'] == 0 ? '#059669' : '#0F172A'; ?>;">
                                        <?= $opt['cost'] == 0 ? 'GRATIS' : rupiah($opt['cost']); ?>
                                    </div>
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;font-size:0.8rem;color:#64748B;">
                                    <span><?= !empty($opt['etd']) ? 'Estimasi: ' . htmlspecialchars($opt['etd']) : 'Pengiriman Standar'; ?></span>
                                    <?php if (!empty($opt['badge'])): ?>
                                    <span style="font-size:0.7rem;padding:2px 6px;border-radius:4px;background:#DEF7EC;color:#03543F;font-weight:700;">
                                        <?= htmlspecialchars($opt['badge']); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($opt['description'])): ?>
                                <div style="font-size:0.75rem;color:#94A3B8;margin-top:2px;">
                                    <?= htmlspecialchars($opt['description']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align:center;padding:1.5rem;color:#64748B;font-size:0.88rem;">
                            Tidak ada opsi ekspedisi yang tersedia saat ini.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Live Price Calculation Box -->
                <div style="margin-top:1.25rem;padding:1rem;background:#F8FAFC;border:1px dashed #CBD5E1;border-radius:10px;font-size:0.85rem;">
                    <div style="display:flex;justify-content:space-between;color:#64748B;margin-bottom:4px;">
                        <span>Subtotal Produk:</span>
                        <span style="font-weight:600;color:#0F172A;"><?= rupiah($order->total); ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;color:#64748B;margin-bottom:6px;">
                        <span>Ongkir Baru:</span>
                        <span id="previewModalShippingCost" style="font-weight:700;color:var(--primary);"><?= $order->shipping_cost > 0 ? rupiah($order->shipping_cost) : 'GRATIS'; ?></span>
                    </div>
                    <div style="border-top:1px solid #E2E8F0;padding-top:6px;display:flex;justify-content:space-between;font-weight:800;font-size:0.95rem;color:#0F172A;">
                        <span>Total Tagihan Baru:</span>
                        <span id="previewModalGrandTotal" style="color:var(--primary);"><?= rupiah($order->grand_total); ?></span>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div style="padding:1rem 1.5rem;border-top:1px solid #E2E8F0;background:#F8FAFC;display:flex;justify-content:flex-end;align-items:center;gap:0.75rem;">
                <button type="button" onclick="closeChangeShippingModal()" class="btn btn-secondary btn-sm" style="padding:0.5rem 1rem;font-size:0.85rem;">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary btn-sm" style="padding:0.5rem 1.25rem;font-size:0.85rem;display:inline-flex;align-items:center;gap:6px;">
                    <i data-feather="check" style="width:14px;height:14px;"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Live Tracking Modal -->
<div id="modalLiveTracking" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15,23,42,0.65);z-index:9999;backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#FFFFFF;border-radius:16px;max-width:600px;width:100%;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);border:1px solid #E2E8F0;overflow:hidden;animation:modalFadeIn 0.2s ease-out;">
        <!-- Modal Header -->
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #E2E8F0;display:flex;justify-content:space-between;align-items:center;background:#F8FAFC;">
            <div>
                <h3 style="margin:0;font-size:1.1rem;font-weight:800;color:#0F172A;display:flex;align-items:center;gap:0.5rem;">
                    <i data-feather="truck" style="width:18px;height:18px;color:#2563EB;"></i>
                    Pelacakan Paket Real-time
                </h3>
                <div style="font-size:0.8rem;color:#64748B;margin-top:2px;">
                    Powered by RapidAPI &bull; Ekspedisi Resmi Indonesia
                </div>
            </div>
            <button type="button" onclick="closeLiveTrackingModal()" style="background:none;border:none;cursor:pointer;color:#64748B;padding:4px;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                <i data-feather="x" style="width:20px;height:20px;"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div id="trackingModalBody" style="padding:1.5rem;overflow-y:auto;flex:1;">
            <div id="trackingLoader" style="text-align:center;padding:3rem 1rem;">
                <div class="tracking-spinner" style="width:40px;height:40px;border:3px solid #E2E8F0;border-top-color:#2563EB;border-radius:50%;margin:0 auto 1rem;animation:spin 0.8s linear infinite;"></div>
                <div style="font-weight:600;color:#0F172A;font-size:0.95rem;">Menghubungkan ke Server Ekspedisi...</div>
                <div style="font-size:0.8rem;color:#64748B;margin-top:0.25rem;">Mengambil status dan riwayat pelacakan terkini</div>
            </div>
            <div id="trackingContent" style="display:none;"></div>
        </div>

        <!-- Modal Footer -->
        <div style="padding:1rem 1.5rem;border-top:1px solid #E2E8F0;background:#F8FAFC;display:flex;justify-content:space-between;align-items:center;">
            <button type="button" onclick="refreshLiveTracking()" class="btn btn-secondary btn-sm" style="font-size:0.8rem;display:inline-flex;align-items:center;gap:0.35rem;">
                <i data-feather="refresh-cw" style="width:13px;height:13px;"></i> Segarkan Data
            </button>
            <button type="button" onclick="closeLiveTrackingModal()" class="btn btn-primary btn-sm" style="font-size:0.8rem;padding:0.45rem 1.25rem;">
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
.order-sidebar-sticky {
    position: sticky;
    top: 90px;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    z-index: 10;
}
@media (max-width: 992px) {
    .order-sidebar-sticky {
        position: static !important;
        top: auto !important;
        gap: 1rem;
    }
}
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
@keyframes modalFadeIn { from { opacity: 0; transform: scale(0.96); } to { opacity: 1; transform: scale(1); } }
.tracking-timeline { position: relative; padding-left: 2rem; margin-top: 1.25rem; }
.tracking-timeline::before { content: ''; position: absolute; left: 7px; top: 8px; bottom: 8px; width: 2px; background: #E2E8F0; }
.tracking-item { position: relative; margin-bottom: 1.25rem; }
.tracking-item:last-child { margin-bottom: 0; }
.tracking-dot { position: absolute; left: -2rem; top: 3px; width: 16px; height: 16px; border-radius: 50%; background: #CBD5E1; border: 3px solid #FFFFFF; box-shadow: 0 0 0 1px #CBD5E1; }
.tracking-item.active .tracking-dot { background: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,0.25); }
.tracking-date { font-size: 0.75rem; color: #64748B; font-weight: 600; margin-bottom: 2px; }
.tracking-desc { font-size: 0.88rem; color: #0F172A; font-weight: 600; line-height: 1.4; }
.tracking-loc { font-size: 0.78rem; color: #2563EB; margin-top: 2px; font-weight: 500; }

/* Responsive Order Detail & Payment Page - Fixed Mobile Layout */
@media (max-width: 768px) {
    .order-detail-page {
        padding-top: 1rem !important;
        overflow-x: hidden !important;
        max-width: 100% !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .order-header-box {
        padding: 1.15rem 1rem !important;
        border-radius: 16px !important;
        margin-bottom: 1rem !important;
        width: 100% !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 0.85rem !important;
    }
    .order-header-title-row {
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
        width: 100% !important;
    }
    .order-header-title-row h1 {
        font-size: 1.25rem !important;
        margin: 0 !important;
    }
    .order-id-chip {
        font-size: 0.76rem !important;
        padding: 0.25rem 0.6rem !important;
        max-width: 100% !important;
    }
    .order-header-meta {
        font-size: 0.76rem !important;
        gap: 0.4rem !important;
        flex-wrap: wrap !important;
    }
    .order-header-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 0.5rem !important;
        width: 100% !important;
        margin-top: 0.35rem !important;
    }
    .order-header-actions > div:first-child {
        grid-column: 1 / -1 !important;
        margin-right: 0 !important;
        margin-bottom: 0.25rem !important;
    }
    .order-header-actions .btn {
        width: 100% !important;
        justify-content: center !important;
        padding: 0.45rem 0.5rem !important;
        font-size: 0.78rem !important;
        white-space: nowrap !important;
    }
    .order-stepper-card {
        padding: 1.15rem 1rem !important;
        border-radius: 16px !important;
        margin-bottom: 1rem !important;
        width: 100% !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
    }
    .order-stepper-header-row {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 0.65rem !important;
        margin-bottom: 1.25rem !important;
        padding-bottom: 0.75rem !important;
    }
    .order-stepper-courier-tag {
        font-size: 0.74rem !important;
        padding: 0.3rem 0.65rem !important;
        max-width: 100% !important;
        flex-wrap: wrap !important;
        word-break: break-word !important;
    }
    .order-stepper-wrapper {
        padding: 0 !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .order-stepper-track {
        display: none !important;
    }
    .order-stepper-steps {
        display: flex !important;
        flex-direction: column !important;
        gap: 1.25rem !important;
        width: 100% !important;
    }
    .order-step {
        display: flex !important;
        flex-direction: row !important;
        align-items: flex-start !important;
        text-align: left !important;
        width: 100% !important;
        padding: 0 !important;
        position: relative !important;
    }
    .order-step:not(:last-child)::after {
        content: '' !important;
        position: absolute !important;
        top: 42px !important;
        left: 21px !important;
        bottom: -20px !important;
        width: 2.5px !important;
        background: #E2E8F0 !important;
        transform: translateX(-50%) !important;
        z-index: 1 !important;
    }
    .order-step.completed:not(:last-child)::after {
        background: #10B981 !important;
    }
    .order-step-icon-wrap {
        width: 42px !important;
        height: 42px !important;
        min-width: 42px !important;
        flex-shrink: 0 !important;
        margin: 0 !important;
        z-index: 2 !important;
    }
    .order-step-icon-wrap svg,
    .order-step-icon-wrap i {
        width: 18px !important;
        height: 18px !important;
    }
    .order-step-info {
        margin-left: 0.85rem !important;
        flex: 1 !important;
        align-items: flex-start !important;
        text-align: left !important;
        min-width: 0 !important;
    }
    .order-step-label {
        margin-top: 0 !important;
        font-size: 0.88rem !important;
    }
    .order-step-time {
        font-size: 0.74rem !important;
        margin-top: 2px !important;
    }
    .step-active-pill {
        font-size: 0.7rem !important;
        padding: 2px 7px !important;
        margin-top: 4px !important;
    }
    .order-detail-grid {
        grid-template-columns: 1fr !important;
        gap: 1.25rem !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    .shipping-info-grid {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
        width: 100% !important;
    }
    .shipping-subcard {
        padding: 1rem 0.85rem !important;
        box-sizing: border-box !important;
        width: 100% !important;
    }
    .order-card {
        padding: 1.15rem 1rem !important;
        border-radius: 16px !important;
        margin-bottom: 1rem !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        width: 100% !important;
    }
    .order-card-header {
        margin-bottom: 1rem !important;
        padding-bottom: 0.75rem !important;
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
    .order-card-title {
        font-size: 0.95rem !important;
    }
    .order-item-box {
        gap: 0.75rem !important;
        padding: 0.85rem 0 !important;
        align-items: flex-start !important;
    }
    .order-item-img {
        width: 54px !important;
        height: 54px !important;
    }
    .order-item-name {
        font-size: 0.86rem !important;
    }
    .order-item-meta {
        font-size: 0.76rem !important;
        gap: 0.4rem !important;
        flex-wrap: wrap !important;
    }
    .order-item-subtotal {
        font-size: 0.92rem !important;
    }
    #formPaymentProof div[style*="display: flex"] {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    #inputPaymentProof {
        min-width: 0 !important;
        width: 100% !important;
    }
    #btnSubmitProof {
        width: 100% !important;
        justify-content: center !important;
    }
}
</style>


<!-- Copy helper scripts -->
<script>
function copyOrderNumber(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            if (window.ShopVista && ShopVista.showToast) {
                ShopVista.showToast('success', 'Nomor pesanan ' + text + ' berhasil disalin!');
            } else {
                alert('Nomor pesanan ' + text + ' disalin!');
            }
        });
    }
}

function copyResi(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            if (window.ShopVista && ShopVista.showToast) {
                ShopVista.showToast('success', 'Nomor resi ' + text + ' disalin!');
            } else {
                alert('Nomor resi ' + text + ' disalin!');
            }
        });
    }
}

function copyRek(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            if (window.ShopVista && ShopVista.showToast) {
                ShopVista.showToast('success', 'Nomor rekening ' + text + ' disalin!');
            } else {
                alert('Nomor rekening ' + text + ' disalin!');
            }
        });
    }
}

function openLiveTrackingModal() {
    const modal = document.getElementById('modalLiveTracking');
    modal.style.display = 'flex';
    fetchLiveTrackingData();
}

function closeLiveTrackingModal() {
    const modal = document.getElementById('modalLiveTracking');
    modal.style.display = 'none';
}

function refreshLiveTracking() {
    fetchLiveTrackingData();
}

function fetchLiveTrackingData() {
    const loader = document.getElementById('trackingLoader');
    const content = document.getElementById('trackingContent');
    loader.style.display = 'block';
    content.style.display = 'none';
    content.innerHTML = '';

    fetch('<?= base_url('profile/check_resi/' . $order->order_number); ?>')
        .then(response => response.json())
        .then(data => {
            loader.style.display = 'none';
            content.style.display = 'block';

            if (!data.success) {
                content.innerHTML = `
                    <div style="background:#FEF2F2;border:1px solid #F87171;border-radius:10px;padding:1.25rem;text-align:center;">
                        <div style="font-weight:700;color:#991B1B;font-size:0.95rem;">Gagal Memuat Data Pelacakan</div>
                        <div style="color:#B91C1C;font-size:0.85rem;margin-top:0.35rem;">${data.message || 'Data pelacakan belum tersedia.'}</div>
                    </div>
                `;
                return;
            }

            let statusColor = '#2563EB';
            let statusBg = '#EFF6FF';
            let statusText = data.status || 'DALAM PENGIRIMAN';
            if (statusText.indexOf('DELIVERED') !== -1 || statusText.indexOf('SELESAI') !== -1) {
                statusColor = '#059669';
                statusBg = '#ECFDF5';
            }

            let historyHtml = '';
            if (data.history && data.history.length > 0) {
                historyHtml = '<div class="tracking-timeline">';
                data.history.forEach((h, index) => {
                    const isActive = index === 0;
                    historyHtml += `
                        <div class="tracking-item ${isActive ? 'active' : ''}">
                            <div class="tracking-dot"></div>
                            <div class="tracking-date">${h.date || '-'}</div>
                            <div class="tracking-desc">${h.desc || h.description || '-'}</div>
                            ${h.location ? `<div class="tracking-loc"><i data-feather="map-pin" style="width:11px;height:11px;display:inline-block;vertical-align:middle;"></i> ${h.location}</div>` : ''}
                        </div>
                    `;
                });
                historyHtml += '</div>';
            } else {
                historyHtml = '<div style="color:#64748B;font-size:0.85rem;text-align:center;padding:1.5rem;font-style:italic;">Riwayat perjalanan paket belum tersedia dari ekspedisi.</div>';
            }

            content.innerHTML = `
                <!-- Overview Card -->
                <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:0.75rem;margin-bottom:0.85rem;">
                        <div>
                            <div style="font-size:0.75rem;color:#64748B;text-transform:uppercase;font-weight:700;">Kurir & Ekspedisi</div>
                            <div style="font-size:1.05rem;font-weight:800;color:#0F172A;">${data.courier_name}</div>
                            <div style="font-family:monospace;font-size:0.9rem;color:#2563EB;font-weight:700;">${data.tracking_number}</div>
                        </div>
                        <div style="text-align:right;">
                            <span style="background:${statusBg};color:${statusColor};font-size:0.75rem;font-weight:800;padding:0.35rem 0.75rem;border-radius:99px;display:inline-block;border:1px solid ${statusColor}33;">
                                ${statusText}
                            </span>
                            <div style="font-size:0.72rem;color:#94A3B8;margin-top:4px;">Pembaruan: ${data.date || '-'}</div>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;padding-top:0.75rem;border-top:1px dashed #CBD5E1;font-size:0.82rem;">
                        <div>
                            <span style="color:#64748B;">Asal:</span> <strong style="color:#0F172A;">${data.origin || 'Jakarta'}</strong>
                        </div>
                        <div>
                            <span style="color:#64748B;">Tujuan:</span> <strong style="color:#0F172A;">${data.destination || '-'}</strong>
                        </div>
                        <div>
                            <span style="color:#64748B;">Pengirim:</span> <span style="color:#0F172A;">${data.shipper || 'ShopVista'}</span>
                        </div>
                        <div>
                            <span style="color:#64748B;">Penerima:</span> <span style="color:#0F172A;">${data.receiver || '-'}</span>
                        </div>
                    </div>
                </div>

                ${data.note ? `
                <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:8px;padding:0.75rem;font-size:0.78rem;color:#92400E;margin-bottom:1.25rem;display:flex;gap:0.5rem;align-items:flex-start;">
                    <i data-feather="info" style="width:16px;height:16px;flex-shrink:0;margin-top:2px;"></i>
                    <div>${data.note}</div>
                </div>` : ''}

                <!-- Timeline Section -->
                <div>
                    <div style="font-weight:700;font-size:0.9rem;color:#0F172A;margin-bottom:0.5rem;">Riwayat Perjalanan Paket</div>
                    ${historyHtml}
                </div>
            `;

            if (window.feather) {
                feather.replace();
            }
        })
        .catch(err => {
            loader.style.display = 'none';
            content.style.display = 'block';
            content.innerHTML = `
                <div style="background:#FEF2F2;border:1px solid #F87171;border-radius:10px;padding:1.25rem;text-align:center;">
                    <div style="font-weight:700;color:#991B1B;font-size:0.95rem;">Terjadi Kesalahan Jaringan</div>
                    <div style="color:#B91C1C;font-size:0.85rem;margin-top:0.35rem;">Tidak dapat menghubungi server pelacakan saat ini. Silakan coba beberapa saat lagi.</div>
                </div>
            `;
        });
}

const orderBaseTotal = <?= (float)$order->total; ?>;

function openChangeShippingModal() {
    const modal = document.getElementById('modalChangeShipping');
    if (modal) {
        modal.style.display = 'flex';
        if (window.feather) feather.replace();
    }
}

function closeChangeShippingModal() {
    const modal = document.getElementById('modalChangeShipping');
    if (modal) {
        modal.style.display = 'none';
    }
}

function onCourierChange(radio) {
    document.querySelectorAll('.courier-choice-card').forEach(function(card) {
        card.style.borderColor = '#E2E8F0';
        card.style.backgroundColor = '#FFFFFF';
        card.classList.remove('active-choice');
    });
    const parentCard = radio.closest('.courier-choice-card');
    if (parentCard) {
        parentCard.style.borderColor = 'var(--primary)';
        parentCard.style.backgroundColor = '#F0F7FF';
        parentCard.classList.add('active-choice');
    }

    const cost = parseFloat(radio.dataset.cost) || 0;
    const grandTotal = orderBaseTotal + cost;

    const elCost = document.getElementById('previewModalShippingCost');
    const elGrand = document.getElementById('previewModalGrandTotal');
    if (elCost) {
        elCost.textContent = cost === 0 ? 'GRATIS' : 'Rp' + cost.toLocaleString('id-ID');
    }
    if (elGrand) {
        elGrand.textContent = 'Rp' + grandTotal.toLocaleString('id-ID');
    }
}

// Close modals when clicking backdrop or pressing ESC
window.addEventListener('click', function(e) {
    const shippingModal = document.getElementById('modalChangeShipping');
    if (shippingModal && e.target === shippingModal) {
        closeChangeShippingModal();
    }
    const trackingModal = document.getElementById('modalLiveTracking');
    if (trackingModal && e.target === trackingModal) {
        closeLiveTrackingModal();
    }
});

window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeChangeShippingModal();
        closeLiveTrackingModal();
    }
});

// Payment Proof WhatsApp & Upload Handler
const waStoreNumber = "<?= $store_wa; ?>";
const orderNumber = "<?= $order->order_number; ?>";
const customerName = "<?= addslashes($order->shipping_name); ?>";
const totalAmountFormatted = "<?= rupiah($order->grand_total); ?>";
const bankName = "<?= addslashes(get_setting('bank_name') ?: 'BCA'); ?>";
const bankAccount = "<?= addslashes(get_setting('bank_account') ?: '8830192831'); ?>";
const bankHolder = "<?= addslashes(get_setting('bank_holder') ?: 'ShopVista Indonesia'); ?>";

function buildWaPaymentUrl(proofUrl) {
    let msg = "Halo Admin ShopVista, saya sudah melakukan pembayaran untuk pesanan:\n"
            + "• *No. Pesanan:* " + orderNumber + "\n"
            + "• *Nama Pemesan:* " + customerName + "\n"
            + "• *Total Transfer:* " + totalAmountFormatted + "\n"
            + "• *Bank Tujuan:* " + bankName + " (" + bankAccount + " a.n " + bankHolder + ")\n\n";

    if (proofUrl) {
        msg += "• *Bukti Transfer (Web):* " + proofUrl + "\n\n";
    }
    msg += "Berikut saya lampirkan struk / bukti transfer untuk diverifikasi. Mohon bantuannya untuk segera diproses ya. Terima kasih!";

    return "https://wa.me/" + waStoreNumber + "?text=" + encodeURIComponent(msg);
}

const inputProof = document.getElementById('inputPaymentProof');
const previewContainer = document.getElementById('proofPreviewContainer');
const previewImg = document.getElementById('proofPreviewImg');
const previewName = document.getElementById('proofFileName');
const previewSize = document.getElementById('proofFileSize');

if (inputProof) {
    inputProof.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            if (previewName) previewName.textContent = file.name;
            if (previewSize) previewSize.textContent = (file.size / 1024).toFixed(1) + ' KB';

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImg) {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            } else {
                if (previewImg) previewImg.style.display = 'none';
            }

            if (previewContainer) previewContainer.style.display = 'flex';
            if (window.feather) feather.replace();
        } else {
            if (previewContainer) previewContainer.style.display = 'none';
        }
    });
}

const formProof = document.getElementById('formPaymentProof');
if (formProof) {
    formProof.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!inputProof || !inputProof.files || inputProof.files.length === 0) {
            alert('Silakan pilih file bukti pembayaran / struk transfer terlebih dahulu.');
            return;
        }

        const btn = document.getElementById('btnSubmitProof');
        btn.disabled = true;
        btn.innerHTML = '<span class="tracking-spinner" style="display:inline-block;width:14px;height:14px;border:2px solid #FFFFFF;border-top-color:transparent;border-radius:50%;margin-right:6px;animation:spin 0.8s linear infinite;vertical-align:middle;"></span> <span>Mengunggah & Membuka WA...</span>';

        // Pre-open blank window on desktop to guarantee no popup blocker interception
        const isMobile = /Android|iPhone|iPad|iPod|Windows Phone/i.test(navigator.userAgent);
        let waWindow = null;
        if (!isMobile) {
            waWindow = window.open('about:blank', '_blank');
        }

        const formData = new FormData(formProof);

        fetch(formProof.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(function(res) {
            return res.json();
        })
        .then(function(data) {
            const uploadedUrl = (data && data.file_url) ? data.file_url : '';
            const targetWaUrl = buildWaPaymentUrl(uploadedUrl);

            if (waWindow) {
                waWindow.location.href = targetWaUrl;
            } else {
                window.open(targetWaUrl, '_blank') || (window.location.href = targetWaUrl);
            }

            btn.innerHTML = '<i data-feather="check" style="width:14px;height:14px;"></i> <span>Terkirim! Memuat Ulang...</span>';
            if (window.feather) feather.replace();

            setTimeout(function() {
                window.location.reload();
            }, 1200);
        })
        .catch(function(err) {
            const fallbackWaUrl = buildWaPaymentUrl('');
            if (waWindow) {
                waWindow.location.href = fallbackWaUrl;
            } else {
                window.open(fallbackWaUrl, '_blank') || (window.location.href = fallbackWaUrl);
            }
            formProof.submit();
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.feather) {
        feather.replace();
    }
});
</script>

