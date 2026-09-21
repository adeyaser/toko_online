<?php $this->load->view('templates/admin_header'); ?>

<!-- Include HTML5 QR/Barcode Scanner library from local assets -->
<script src="<?= base_url('assets/js/html5-qrcode.min.js'); ?>"></script>

<div class="admin-header">
    <div>
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.35rem;">
            <h1 style="margin: 0;">Detail Pesanan #<?= $order->order_number; ?></h1>
            <?= order_status_badge($order->status); ?>
        </div>
        <div style="font-size: 0.85rem; color: #64748B;">
            Dipesan pada <?= date('d F Y, H:i', strtotime($order->created_at)); ?> WIB
        </div>
    </div>
    <div style="display: flex; gap: 0.5rem; align-items: center;">
        <a href="<?= base_url('admin/orders/print_invoices?ids=' . $order->id); ?>" target="_blank" class="btn btn-primary" style="font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px;">
            <i data-feather="file-text" style="width: 14px; height: 14px;"></i> Cetak PDF
        </a>
        <a href="<?= base_url('admin/orders'); ?>" class="btn btn-secondary" style="font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px;">
            <i data-feather="arrow-left" style="width: 14px; height: 14px;"></i> Kembali
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
    <i data-feather="check-circle" style="width: 20px; height: 20px; color: #059669;"></i>
    <span><?= $this->session->flashdata('success'); ?></span>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
    <i data-feather="alert-triangle" style="width: 20px; height: 20px; color: #DC2626;"></i>
    <span><?= $this->session->flashdata('error'); ?></span>
</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem;align-items:start;">
    <!-- Main Left Column -->
    <div>
        <!-- Customer & Order Meta -->
        <div class="checkout-section" style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:1.25rem;">
                <div>
                    <div style="font-size:0.75rem;color:#64748B;text-transform:uppercase;font-weight:600;margin-bottom:4px;">No. Pesanan</div>
                    <div style="font-weight:700;color:var(--primary);font-size:1.05rem;"><?= $order->order_number; ?></div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#64748B;text-transform:uppercase;font-weight:600;margin-bottom:4px;">Nama Pelanggan</div>
                    <div style="font-weight:700;color:#0F172A;font-size:0.95rem;"><?= htmlspecialchars($order->user_name); ?></div>
                    <div style="font-size:0.82rem;color:#64748B;"><?= htmlspecialchars($order->user_email); ?></div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#64748B;text-transform:uppercase;font-weight:600;margin-bottom:4px;">Metode Pembayaran</div>
                    <div style="font-weight:700;color:#0F172A;font-size:0.95rem;"><?= $order->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'COD (Bayar di Tempat)'; ?></div>
                    <?php if ($order->payment_proof): ?>
                    <a href="<?= base_url('assets/images/payments/' . $order->payment_proof); ?>" target="_blank" style="font-size:0.8rem;color:var(--primary);display:inline-flex;align-items:center;gap:3px;margin-top:2px;">
                        <i data-feather="file-text" style="width:12px;height:12px;"></i> Lihat Bukti Transfer
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Shipping & Tracking Details (Current State) -->
        <div class="checkout-section" style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid #F1F5F9;">
                <h3 style="margin:0;font-size:1rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:0.5rem;">
                    <i data-feather="truck" style="width:18px;height:18px;color:var(--primary);"></i>
                    Informasi Pengiriman & Ekspedisi
                </h3>
                <?php if (!empty($order->tracking_number)): ?>
                <span class="courier-pill-badge">
                    <i data-feather="check" style="width:13px;height:13px;"></i> Resi Sudah Terbit
                </span>
                <?php else: ?>
                <span style="font-size:0.78rem;font-weight:600;color:#D97706;background:#FEF3C7;padding:0.25rem 0.6rem;border-radius:99px;">
                    Belum Ada Resi
                </span>
                <?php endif; ?>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                <!-- Recipient Info -->
                <div>
                    <div style="font-size:0.75rem;color:#64748B;text-transform:uppercase;font-weight:600;margin-bottom:4px;">Penerima Paket</div>
                    <div style="font-weight:700;color:#0F172A;"><?= htmlspecialchars($order->shipping_name); ?> (<?= htmlspecialchars($order->shipping_phone); ?>)</div>
                    <div style="color:#475569;font-size:0.85rem;margin-top:0.35rem;line-height:1.5;">
                        <?= nl2br(htmlspecialchars($order->shipping_address)); ?><br>
                        <?= htmlspecialchars($order->shipping_city); ?>, <?= htmlspecialchars($order->shipping_province); ?> <?= htmlspecialchars($order->shipping_postal); ?>
                    </div>
                    <?php if ($order->notes): ?>
                    <div style="margin-top:0.5rem;background:#FFFBEB;border:1px solid #FDE68A;border-radius:6px;padding:0.4rem 0.65rem;font-size:0.8rem;color:#92400E;">
                        <strong>Catatan Pembeli:</strong> <?= htmlspecialchars($order->notes); ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Courier & Tracking Info -->
                <div>
                    <div style="font-size:0.75rem;color:#64748B;text-transform:uppercase;font-weight:600;margin-bottom:4px;">Jasa Pengiriman</div>
                    <div style="font-weight:700;color:#0F172A;font-size:0.95rem;margin-bottom:0.75rem;">
                        <?= !empty($order->shipping_courier) ? htmlspecialchars($order->shipping_courier) : 'ShopVista Express (Default)'; ?>
                    </div>

                    <div style="font-size:0.75rem;color:#64748B;text-transform:uppercase;font-weight:600;margin-bottom:4px;">No. Resi Pengiriman</div>
                    <?php if (!empty($order->tracking_number)): ?>
                    <div style="background:#F8FAFC;border:1px solid #CBD5E1;border-radius:8px;padding:0.6rem 0.85rem;display:flex;align-items:center;justify-content:space-between;">
                        <span style="font-family:monospace;font-size:1rem;font-weight:800;color:#0F172A;letter-spacing:0.5px;">
                            <?= htmlspecialchars($order->tracking_number); ?>
                        </span>
                        <div style="display:flex;gap:0.35rem;">
                            <button type="button" class="btn btn-primary btn-sm" style="font-size:0.75rem;padding:0.25rem 0.55rem;display:inline-flex;align-items:center;gap:0.3rem;" onclick="checkResiAdmin()">
                                <i data-feather="truck" style="width:12px;height:12px;"></i> Lacak Live
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" style="font-size:0.75rem;padding:0.25rem 0.5rem;" onclick="copyTrackingAdmin('<?= htmlspecialchars($order->tracking_number); ?>')">
                                <i data-feather="copy" style="width:12px;height:12px;"></i> Salin
                            </button>
                        </div>
                    </div>
                    <?php else: ?>
                    <div style="color:#64748B;font-size:0.85rem;font-style:italic;background:#F8FAFC;border:1px dashed #CBD5E1;border-radius:8px;padding:0.6rem 0.85rem;">
                        Resi belum diinput. Masukkan resi pada formulir di sebelah kanan (bisa ketik atau scan foto barcode).
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Ordered Items -->
        <div class="checkout-section" style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;padding:1.25rem 1.5rem;">
            <h3 style="margin:0 0 1rem 0;font-size:1rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:0.5rem;">
                <i data-feather="package" style="width:18px;height:18px;color:var(--primary);"></i>
                Item Pesanan (<?= count($order_items); ?>)
            </h3>
            
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                <?php foreach ($order_items as $item): ?>
                <div style="display:flex;gap:1rem;padding:0.85rem;border:1px solid #F1F5F9;border-radius:8px;align-items:center;background:#FAFAFA;">
                    <img src="<?= product_image($item->product_image); ?>" alt="<?= htmlspecialchars($item->product_name); ?>" style="width:64px;height:64px;border-radius:8px;object-fit:cover;border:1px solid #E2E8F0;">
                    <div style="flex:1;">
                        <a href="<?= base_url('produk/' . (!empty($item->product_slug) ? $item->product_slug : url_title($item->product_name, 'dash', TRUE))); ?>" target="_blank" style="font-weight:700;color:#0F172A;text-decoration:none;font-size:0.95rem;">
                            <?= htmlspecialchars($item->product_name); ?>
                        </a>
                        <div style="font-size:0.85rem;color:#64748B;margin-top:2px;">
                            <?= $item->quantity; ?> unit × <?= rupiah($item->price); ?>
                        </div>
                    </div>
                    <div style="font-weight:800;color:#0F172A;font-size:1.05rem;">
                        <?= rupiah($item->subtotal); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar Right Column: Fulfillment & Status Management Form -->
    <div>
        <!-- Fulfillment Form Card -->
        <div class="checkout-section" style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.05);margin-bottom:1.5rem;">
            <h3 style="margin:0 0 1.25rem 0;font-size:1.05rem;font-weight:800;color:#0F172A;display:flex;align-items:center;gap:0.5rem;padding-bottom:0.75rem;border-bottom:1px solid #F1F5F9;">
                <i data-feather="edit-3" style="width:18px;height:18px;color:var(--primary);"></i>
                Pengiriman & Status
            </h3>

            <form action="<?= base_url('admin/orders/detail/' . $order->id); ?>" method="post" id="fulfillmentForm">
                <!-- Status Selection -->
                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label class="form-label" style="font-weight:700;color:#0F172A;font-size:0.88rem;margin-bottom:0.35rem;display:block;">
                        Status Pesanan:
                    </label>
                    <select name="status" id="order_status_select" class="form-control" style="font-weight:600;padding:0.65rem 0.85rem;border-radius:8px;border:1px solid #CBD5E1;width:100%;">
                        <option value="pending" <?= $order->status == 'pending' ? 'selected' : ''; ?>>⏳ Menunggu Pembayaran (Pending)</option>
                        <option value="processing" <?= $order->status == 'processing' ? 'selected' : ''; ?>>📦 Diproses & Dikemas (Processing)</option>
                        <option value="shipped" <?= $order->status == 'shipped' ? 'selected' : ''; ?>>🚚 Sedang Dikirim (Shipped)</option>
                        <option value="delivered" <?= $order->status == 'delivered' ? 'selected' : ''; ?>>✅ Selesai Diterima (Delivered)</option>
                        <option value="cancelled" <?= $order->status == 'cancelled' ? 'selected' : ''; ?>>❌ Dibatalkan (Cancelled)</option>
                    </select>
                </div>

                <!-- Courier Service Selection -->
                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label class="form-label" style="font-weight:700;color:#0F172A;font-size:0.88rem;margin-bottom:0.35rem;display:block;">
                        Jasa Pengiriman / Ekspedisi:
                    </label>
                    <?php
                    $current_courier = !empty($order->shipping_courier) ? $order->shipping_courier : 'ShopVista Express';
                    $popular_couriers = [
                        'ShopVista Express',
                        'JNE Express',
                        'J&T Express',
                        'SiCepat Express',
                        'Anteraja',
                        'Shopee Xpress (SPX)',
                        'Ninja Xpress',
                        'ID Express',
                        'POS Indonesia',
                        'Wahana Express',
                        'Lion Parcel',
                        'GoSend / GrabExpress'
                    ];
                    $is_custom_courier = !in_array($current_courier, $popular_couriers);
                    ?>
                    <select name="shipping_courier" id="courier_select" class="form-control" style="font-weight:500;padding:0.65rem 0.85rem;border-radius:8px;border:1px solid #CBD5E1;width:100%;margin-bottom:0.5rem;" onchange="handleCourierChange(this.value)">
                        <?php foreach ($popular_couriers as $courier): ?>
                        <option value="<?= $courier; ?>" <?= $current_courier == $courier ? 'selected' : ''; ?>><?= $courier; ?></option>
                        <?php endforeach; ?>
                        <option value="custom" <?= $is_custom_courier ? 'selected' : ''; ?>>+ Lainnya (Tulis Manual)</option>
                    </select>
                    
                    <div id="custom_courier_box" style="display: <?= $is_custom_courier ? 'block' : 'none'; ?>; margin-top: 0.5rem;">
                        <input type="text" id="custom_courier_input" placeholder="Masukkan nama ekspedisi..." value="<?= $is_custom_courier ? htmlspecialchars($current_courier) : ''; ?>" class="form-control" style="font-size:0.88rem;border:1px solid #CBD5E1;border-radius:8px;padding:0.5rem 0.75rem;width:100%;">
                    </div>
                </div>

                <!-- Tracking Number (No. Resi) with Barcode / QR Scanner -->
                <div class="form-group" style="margin-bottom:1.5rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.35rem;">
                        <label class="form-label" style="font-weight:700;color:#0F172A;font-size:0.88rem;margin:0;">
                            Nomor Resi Pengiriman:
                        </label>
                    </div>

                    <div class="tracking-input-wrapper">
                        <input type="text" name="tracking_number" id="tracking_number_input" class="form-control" value="<?= htmlspecialchars($order->tracking_number ?: ''); ?>" placeholder="Ketik atau scan resi..." style="padding-right: 35px; border-radius: 8px; border: 1px solid #CBD5E1; width: 100%; padding: 0.65rem 0.85rem;">
                        <button type="button" onclick="clearResi()" style="position:absolute;right:8px;background:none;border:none;color:#94A3B8;cursor:pointer;padding:4px;" title="Hapus">
                            <i data-feather="x" style="width:14px;height:14px;"></i>
                        </button>
                    </div>

                    <!-- Scanner Buttons -->
                    <div class="scanner-action-btn-group">
                        <!-- Button 1: Live Camera Scan Modal -->
                        <button type="button" class="btn-scan-action primary-scan" onclick="startCameraScanner()">
                            <i data-feather="camera" style="width:15px;height:15px;"></i>
                            <span>Scan Kamera</span>
                        </button>

                        <!-- Button 2: Photo / File Barcode Upload -->
                        <button type="button" class="btn-scan-action" onclick="triggerBarcodeUpload()">
                            <i data-feather="image" style="width:15px;height:15px;"></i>
                            <span>Foto / Upload Resi</span>
                        </button>

                        <!-- Button 3: Live Track Check RapidAPI -->
                        <button type="button" class="btn-scan-action" onclick="checkResiAdmin()" style="background:#EEF2FF;color:#4338CA;border-color:#C7D2FE;">
                            <i data-feather="activity" style="width:15px;height:15px;"></i>
                            <span>Cek Resi Live</span>
                        </button>
                    </div>
                    <!-- Hidden File Input for Image Scan -->
                    <input type="file" id="barcodeFileInput" accept="image/*" capture="environment" style="display:none;" onchange="handleBarcodeFile(this)">
                    
                    <div style="font-size:0.75rem;color:#64748B;margin-top:6px;line-height:1.4;">
                        💡 <em>Mendukung foto/scan Barcode (garis) & QR Code semua ekspedisi (JNE, J&T, SiCepat, dll).</em>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-full" style="width:100%;justify-content:center;padding:0.75rem;font-weight:700;border-radius:8px;">
                    <i data-feather="save" style="width:16px;height:16px;"></i> Simpan Info Pesanan
                </button>
            </form>
        </div>

        <!-- Payment Summary Card -->
        <div class="cart-summary" style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <h3 style="margin:0 0 1rem 0;font-size:1rem;font-weight:700;color:#0F172A;border-bottom:1px solid #F1F5F9;padding-bottom:0.5rem;">
                Ringkasan Biaya
            </h3>
            <div class="summary-row" style="display:flex;justify-content:space-between;margin-bottom:0.5rem;font-size:0.88rem;color:#475569;">
                <span>Total Produk</span>
                <span style="font-weight:600;color:#0F172A;"><?= rupiah($order->total); ?></span>
            </div>
            <div class="summary-row" style="display:flex;justify-content:space-between;margin-bottom:0.5rem;font-size:0.88rem;color:#475569;">
                <span>Ongkos Kirim</span>
                <span style="font-weight:600;color:#059669;"><?= $order->shipping_cost > 0 ? rupiah($order->shipping_cost) : 'Gratis'; ?></span>
            </div>
            <div class="summary-row total" style="display:flex;justify-content:space-between;margin-top:0.75rem;padding-top:0.75rem;border-top:1px dashed #E2E8F0;font-size:1.1rem;font-weight:800;color:#0F172A;">
                <span>Grand Total</span>
                <span class="amount" style="color:#059669;"><?= rupiah($order->grand_total); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     Barcode & QR Code Live Scanner Modal
     ============================================================ -->
<div class="scanner-modal-backdrop" id="scannerModal">
    <div class="scanner-modal-content">
        <div class="scanner-modal-header">
            <h3>
                <i data-feather="camera" style="width:18px;height:18px;color:var(--primary);"></i>
                Scan Barcode / QR Code Resi
            </h3>
            <button type="button" class="scanner-modal-close" onclick="stopCameraScanner()">
                <i data-feather="x" style="width:20px;height:20px;"></i>
            </button>
        </div>
        <div class="scanner-modal-body">
            <!-- Viewfinder Area -->
            <div class="scanner-viewfinder-wrapper">
                <div id="barcode-scanner-viewfinder"></div>
            </div>

            <!-- Camera Switcher -->
            <div class="scanner-controls-row">
                <select id="camera-selection-dropdown" class="scanner-camera-select" onchange="switchCamera(this.value)">
                    <option value="">Memuat kamera perangkat...</option>
                </select>
                <button type="button" class="btn btn-secondary btn-sm" onclick="triggerBarcodeUpload(); stopCameraScanner();" style="white-space:nowrap;">
                    <i data-feather="image" style="width:13px;height:13px;"></i> Pilih Foto
                </button>
            </div>

            <div id="scanner-error-msg" style="display:none;margin-top:0.75rem;background:#FEE2E2;border:1px solid #FCA5A5;border-radius:8px;padding:0.6rem;color:#B91C1C;font-size:0.8rem;text-align:center;"></div>

            <div class="scanner-hint-text">
                Posisikan <strong>Barcode garis</strong> atau <strong>QR Code</strong> pada resi di dalam kotak pemindai. Sistem akan otomatis mendeteksi nomor resi.
            </div>
        </div>
    </div>
</div>

<!-- Hidden Scanner Container for File Decode -->
<div id="hidden-file-scanner" style="display:none;"></div>

<script>
// Scanner global state
let html5QrCodeScanner = null;
let currentCameraId = null;

function handleCourierChange(val) {
    const customBox = document.getElementById('custom_courier_box');
    const customInput = document.getElementById('custom_courier_input');
    if (val === 'custom') {
        customBox.style.display = 'block';
        customInput.required = true;
        customInput.focus();
    } else {
        customBox.style.display = 'none';
        customInput.required = false;
    }
}

// Form submit handler to swap courier name if custom
document.getElementById('fulfillmentForm').addEventListener('submit', function(e) {
    const courierSelect = document.getElementById('courier_select');
    const customInput = document.getElementById('custom_courier_input');
    if (courierSelect.value === 'custom' && customInput.value.trim() !== '') {
        // Create hidden input or update select option value
        const option = document.createElement('option');
        option.value = customInput.value.trim();
        option.text = customInput.value.trim();
        option.selected = true;
        courierSelect.appendChild(option);
    }
});

function clearResi() {
    const input = document.getElementById('tracking_number_input');
    input.value = '';
    input.focus();
}

function copyTrackingAdmin(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Nomor resi ' + text + ' berhasil disalin!');
        });
    }
}

// ============================================================
// Barcode & QR Code Scanning Engine
// ============================================================

function playSuccessBeep() {
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = "sine";
        osc.frequency.value = 880; // Note A5
        gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.18);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + 0.18);
    } catch(e) {}
}

function onScanSuccess(decodedText) {
    const cleanText = decodedText.trim();
    if (!cleanText) return;

    playSuccessBeep();

    const input = document.getElementById('tracking_number_input');
    input.value = cleanText;
    input.classList.add('scan-success-glow');
    setTimeout(() => input.classList.remove('scan-success-glow'), 2500);

    // If status is currently pending or processing, suggest or set to shipped
    const statusSelect = document.getElementById('order_status_select');
    if (statusSelect.value === 'pending' || statusSelect.value === 'processing') {
        statusSelect.value = 'shipped';
    }

    stopCameraScanner();

    // Show nice alert/toast
    alert('Berhasil scan nomor resi: ' + cleanText);
}

// 1. Live Camera Scanning
function startCameraScanner() {
    const modal = document.getElementById('scannerModal');
    modal.classList.add('active');
    document.getElementById('scanner-error-msg').style.display = 'none';

    if (typeof Html5Qrcode === 'undefined') {
        alert('Library pemindai barcode sedang dimuat. Silakan tunggu 2 detik.');
        return;
    }

    if (!html5QrCodeScanner) {
        html5QrCodeScanner = new Html5Qrcode("barcode-scanner-viewfinder");
    }

    // Get available cameras
    Html5Qrcode.getCameras().then(devices => {
        const select = document.getElementById('camera-selection-dropdown');
        select.innerHTML = '';

        if (devices && devices.length) {
            let backCameraId = devices[0].id;
            devices.forEach((device, index) => {
                const option = document.createElement('option');
                option.value = device.id;
                option.text = device.label || ('Kamera ' + (index + 1));
                // Prefer back/environment camera
                if (device.label.toLowerCase().includes('back') || device.label.toLowerCase().includes('rear') || device.label.toLowerCase().includes('belakang')) {
                    backCameraId = device.id;
                    option.selected = true;
                }
                select.appendChild(option);
            });

            currentCameraId = backCameraId;
            launchCamera(currentCameraId);
        } else {
            // Try facing mode fallback
            launchCamera({ facingMode: "environment" });
        }
    }).catch(err => {
        // Fallback directly to facingMode
        launchCamera({ facingMode: "environment" });
    });
}

function launchCamera(cameraIdOrConfig) {
    const config = {
        fps: 12,
        qrbox: { width: 300, height: 180 }, // Aspect ratio fits courier barcodes
        aspectRatio: 1.777778
    };

    html5QrCodeScanner.start(
        cameraIdOrConfig,
        config,
        (decodedText, decodedResult) => {
            onScanSuccess(decodedText);
        },
        (errorMessage) => {
            // ignore scan parsing frame errors
        }
    ).catch(err => {
        const errorMsg = document.getElementById('scanner-error-msg');
        errorMsg.innerText = 'Gagal membuka kamera: ' + (err || 'Izin kamera ditolak.');
        errorMsg.style.display = 'block';
    });
}

function switchCamera(newCameraId) {
    if (!newCameraId || !html5QrCodeScanner) return;
    html5QrCodeScanner.stop().then(() => {
        launchCamera(newCameraId);
    });
}

function stopCameraScanner() {
    const modal = document.getElementById('scannerModal');
    modal.classList.remove('active');

    if (html5QrCodeScanner && html5QrCodeScanner.isScanning) {
        html5QrCodeScanner.stop().catch(e => console.log(e));
    }
}

// 2. Photo / File Upload Scanning
function triggerBarcodeUpload() {
    const fileInput = document.getElementById('barcodeFileInput');
    fileInput.value = '';
    fileInput.click();
}

function handleBarcodeFile(input) {
    if (!input.files || input.files.length === 0) return;
    const file = input.files[0];

    if (typeof Html5Qrcode === 'undefined') {
        alert('Library pemindai sedang dimuat.');
        return;
    }

    const html5QrCode = new Html5Qrcode("hidden-file-scanner");
    html5QrCode.scanFile(file, true)
        .then(decodedText => {
            onScanSuccess(decodedText);
        })
        .catch(err => {
            alert('Barcode atau QR Code tidak terdeteksi pada foto yang diunggah. Pastikan gambar tidak buram, barcode terlihat jelas, dan pencahayaan cukup.');
        });
}

// 3. Live Tracking via RapidAPI
function checkResiAdmin() {
    let resi = document.getElementById('tracking_number_input').value.trim();
    let courier = document.getElementById('courier_select').value;
    if (courier === 'custom') {
        courier = document.getElementById('custom_courier_input').value.trim();
    }

    if (!resi) {
        alert('Silakan masukkan nomor resi terlebih dahulu.');
        return;
    }

    const modal = document.getElementById('modalAdminLiveTracking');
    modal.style.display = 'flex';
    fetchAdminTrackingData(resi, courier);
}

function closeAdminTrackingModal() {
    document.getElementById('modalAdminLiveTracking').style.display = 'none';
}

function fetchAdminTrackingData(resi, courier) {
    const loader = document.getElementById('adminTrackingLoader');
    const content = document.getElementById('adminTrackingContent');
    loader.style.display = 'block';
    content.style.display = 'none';
    content.innerHTML = '';

    const url = '<?= base_url('admin/orders/check_resi/' . $order->id); ?>?resi=' + encodeURIComponent(resi) + '&courier=' + encodeURIComponent(courier);

    fetch(url)
        .then(res => res.json())
        .then(data => {
            loader.style.display = 'none';
            content.style.display = 'block';

            if (!data.success) {
                content.innerHTML = `
                    <div style="background:#FEF2F2;border:1px solid #F87171;border-radius:8px;padding:1.25rem;text-align:center;">
                        <div style="font-weight:700;color:#991B1B;">Pelacakan Tidak Ditemukan</div>
                        <div style="color:#B91C1C;font-size:0.85rem;margin-top:0.35rem;">${data.message || 'Nomor resi tidak valid atau belum terdaftar di ekspedisi.'}</div>
                    </div>
                `;
                return;
            }

            let historyHtml = '';
            if (data.history && data.history.length > 0) {
                historyHtml = '<div style="margin-top:1rem;border-left:2px solid #E2E8F0;padding-left:1.25rem;margin-left:0.5rem;">';
                data.history.forEach((h, idx) => {
                    const isFirst = idx === 0;
                    historyHtml += `
                        <div style="position:relative;margin-bottom:1rem;">
                            <div style="position:absolute;left:-1.65rem;top:3px;width:12px;height:12px;border-radius:50%;background:${isFirst ? '#2563EB' : '#CBD5E1'};border:2px solid #FFF;"></div>
                            <div style="font-size:0.75rem;color:#64748B;font-weight:600;">${h.date || '-'}</div>
                            <div style="font-size:0.85rem;color:#0F172A;font-weight:600;margin-top:2px;">${h.desc || h.description || '-'}</div>
                            ${h.location ? `<div style="font-size:0.75rem;color:#2563EB;">Lokasi: ${h.location}</div>` : ''}
                        </div>
                    `;
                });
                historyHtml += '</div>';
            } else {
                historyHtml = '<div style="color:#64748B;font-size:0.85rem;padding:1rem;text-align:center;font-style:italic;">Belum ada riwayat pergerakan dari ekspedisi.</div>';
            }

            content.innerHTML = `
                <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;padding:1rem;margin-bottom:1rem;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                        <div>
                            <div style="font-size:0.75rem;color:#64748B;text-transform:uppercase;font-weight:700;">Ekspedisi</div>
                            <div style="font-size:1.05rem;font-weight:800;color:#0F172A;">${data.courier_name}</div>
                            <div style="font-family:monospace;font-size:0.9rem;font-weight:700;color:#2563EB;">${data.tracking_number}</div>
                        </div>
                        <span style="background:#EFF6FF;color:#2563EB;font-size:0.75rem;font-weight:800;padding:0.25rem 0.65rem;border-radius:99px;border:1px solid #BFDBFE;">
                            ${data.status}
                        </span>
                    </div>
                    <div style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px dashed #CBD5E1;font-size:0.8rem;display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;">
                        <div><span style="color:#64748B;">Asal:</span> <strong>${data.origin || 'Jakarta'}</strong></div>
                        <div><span style="color:#64748B;">Tujuan:</span> <strong>${data.destination || '-'}</strong></div>
                    </div>
                </div>

                ${data.note ? `<div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:6px;padding:0.6rem 0.85rem;font-size:0.78rem;color:#92400E;margin-bottom:1rem;">${data.note}</div>` : ''}

                <div style="font-weight:700;font-size:0.88rem;color:#0F172A;">Riwayat Perjalanan Paket:</div>
                ${historyHtml}
            `;

            if (window.feather) feather.replace();
        })
        .catch(err => {
            loader.style.display = 'none';
            content.style.display = 'block';
            content.innerHTML = `
                <div style="background:#FEF2F2;border:1px solid #F87171;border-radius:8px;padding:1rem;text-align:center;color:#991B1B;font-size:0.85rem;">
                    Gagal terhubung ke API RapidAPI. Periksa koneksi internet Anda.
                </div>
            `;
        });
}

// Close modal on escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        stopCameraScanner();
        closeAdminTrackingModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    if (window.feather) feather.replace();
});
</script>

<!-- Admin Live Tracking Modal -->
<div id="modalAdminLiveTracking" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15,23,42,0.65);z-index:9999;backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#FFFFFF;border-radius:12px;max-width:550px;width:100%;max-height:88vh;display:flex;flex-direction:column;box-shadow:0 20px 40px rgba(0,0,0,0.2);border:1px solid #E2E8F0;overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #E2E8F0;display:flex;justify-content:space-between;align-items:center;background:#F8FAFC;">
            <div>
                <h3 style="margin:0;font-size:1rem;font-weight:800;color:#0F172A;display:flex;align-items:center;gap:0.4rem;">
                    <i data-feather="truck" style="width:16px;height:16px;color:#2563EB;"></i>
                    Pelacakan Resi (RapidAPI)
                </h3>
                <div style="font-size:0.75rem;color:#64748B;">cek-resi-cek-ongkir.p.rapidapi.com</div>
            </div>
            <button type="button" onclick="closeAdminTrackingModal()" style="background:none;border:none;cursor:pointer;color:#64748B;">
                <i data-feather="x" style="width:18px;height:18px;"></i>
            </button>
        </div>

        <div style="padding:1.25rem;overflow-y:auto;flex:1;">
            <div id="adminTrackingLoader" style="text-align:center;padding:2.5rem 1rem;">
                <div style="width:32px;height:32px;border:3px solid #E2E8F0;border-top-color:#2563EB;border-radius:50%;margin:0 auto 0.75rem;animation:spin 0.8s linear infinite;"></div>
                <div style="font-size:0.85rem;color:#64748B;">Menghubungkan ke server kurir...</div>
            </div>
            <div id="adminTrackingContent" style="display:none;"></div>
        </div>

        <div style="padding:0.75rem 1.25rem;border-top:1px solid #E2E8F0;background:#F8FAFC;text-align:right;">
            <button type="button" onclick="closeAdminTrackingModal()" class="btn btn-secondary btn-sm" style="font-size:0.8rem;">
                Tutup
            </button>
        </div>
    </div>
</div>


<?php $this->load->view('templates/admin_footer'); ?>
