<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="<?= base_url('assets/js/JsBarcode.all.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/html2pdf.bundle.min.js'); ?>"></script>
    <style>
        * { box-sizing:border-box; margin:0; padding:0; font-family:'Plus Jakarta Sans',sans-serif; }
        body { background:#CBD5E1; color:#0F172A; }

        /* Toolbar */
        .toolbar {
            position:sticky; top:0; z-index:1000;
            background:#0F172A; color:#FFF;
            padding:0.6rem 1.2rem;
            display:flex; align-items:center; justify-content:space-between;
            box-shadow:0 4px 16px rgba(0,0,0,0.25);
        }
        .toolbar-info { display:flex; align-items:center; gap:0.5rem; font-size:0.82rem; }
        .toolbar-info strong { color:#38BDF8; }
        .toolbar-actions { display:flex; align-items:center; gap:0.4rem; }
        .tbtn {
            display:inline-flex; align-items:center; gap:0.3rem;
            padding:0.42rem 0.75rem; border-radius:6px; font-size:0.76rem;
            font-weight:700; cursor:pointer; text-decoration:none;
            transition:all 0.15s; border:none; color:#FFF;
        }
        .tbtn-green { background:#10B981; }
        .tbtn-green:hover { background:#059669; }
        .tbtn-blue { background:#3B82F6; }
        .tbtn-blue:hover { background:#2563EB; }
        .tbtn-gray { background:#334155; }
        .tbtn-gray:hover { background:#475569; }
        .tbtn:disabled { opacity:0.6; cursor:wait; }

        /* Standard Thermal Shipping Label Resi Dimensions (100mm x 165mm) */
        @page {
            size: 100mm 165mm;
            margin: 0;
        }

        /* Print area container — fixed width matching standard 100mm thermal roll */
        #printArea {
            width: 100mm;
            margin: 1.5rem auto;
            background: transparent;
        }

        /* Each receipt — standard thermal label format */
        .receipt {
            width: 100mm;
            min-height: 165mm;
            padding: 5mm 6mm 4mm 6mm;
            background: #FFF;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-bottom: 24px;
            box-sizing: border-box;
            page-break-after: always;
            break-after: page;
        }
        .receipt:last-child {
            page-break-after: auto;
            break-after: auto;
            margin-bottom: 0;
        }
        .receipt-main {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
        }

        /* Dashed line between receipts (screen only) */
        .receipt-divider {
            height: 0;
            border: none;
            border-top: 2px dashed #94A3B8;
            margin: 1.5rem auto;
            width: 100mm;
        }

        /* Header */
        .hdr { display:flex; justify-content:space-between; align-items:flex-start; padding-bottom:7px; border-bottom:2px solid #0F172A; margin-bottom:8px; }
        .hdr-brand { display:flex; align-items:center; gap:6px; margin-bottom:2px; }
        .hdr-logo-img { height:22px; max-width:80px; object-fit:contain; border-radius:4px; display:block; flex-shrink:0; }
        .hdr-icon { width:22px; height:22px; background:linear-gradient(135deg,#4F46E5,#3B82F6); border-radius:4px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:9px; flex-shrink:0; }
        .hdr-name { font-size:12.5px; font-weight:800; color:#0F172A; white-space:nowrap; }
        .hdr-sub { font-size:8px; color:#64748B; line-height:1.35; }
        .hdr-r { text-align:right; }
        .hdr-r h2 { font-size:10.5px; font-weight:800; letter-spacing:0.5px; text-transform:uppercase; }
        .hdr-num { font-family:'JetBrains Mono',monospace; font-size:9.5px; font-weight:700; color:#4F46E5; margin-top:1px; }
        .hdr-bc { margin-top:2px; display:flex; justify-content:flex-end; }
        .hdr-bc svg { max-height:20px; max-width:110px; }
        .hdr-dt { font-size:7.5px; color:#64748B; margin-top:2px; }

        /* Meta */
        .meta { display:grid; grid-template-columns:1fr 1fr; gap:6px; padding:6px 8px; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:3px; margin-bottom:6px; }
        .meta-lbl { font-size:7px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; color:#64748B; margin-bottom:2px; }
        .meta-val { font-size:8.5px; color:#1E293B; line-height:1.35; }
        .meta-val strong { color:#0F172A; font-weight:700; }
        .meta-badge { display:inline-block; background:#EEF2FF; color:#4338CA; border:1px solid #C7D2FE; padding:1px 5px; border-radius:3px; font-size:8px; font-weight:700; }
        .meta-resi { font-family:'JetBrains Mono',monospace; font-size:9.5px; font-weight:700; }
        .meta-sm { font-size:8px; color:#64748B; margin-top:2px; }
        .meta-sm strong { color:#0F172A; }
        .meta-note { font-size:7.5px; color:#B45309; font-style:italic; margin-top:2px; }

        .sep { border:none; border-top:1px dashed #CBD5E1; margin:5px 0; }

        /* Table */
        .tbl { width:100%; border-collapse:collapse; margin-bottom:6px; }
        .tbl th { background:#0F172A; color:#FFF; font-size:7.5px; text-transform:uppercase; letter-spacing:0.3px; padding:3.5px 5px; text-align:left; }
        .tbl th.r { text-align:right; }
        .tbl th.c { text-align:center; }
        .tbl td { padding:3.5px 5px; font-size:8.5px; border-bottom:1px solid #E2E8F0; color:#334155; }
        .tbl td.r { text-align:right; }
        .tbl td.c { text-align:center; }
        .tbl tbody tr:nth-child(even) { background:#FAFAFA; }
        .tbl .pn { font-weight:600; color:#0F172A; }

        /* Summary */
        .summ { display:grid; grid-template-columns:1fr 1fr; gap:6px; align-items:start; }
        .summ-notes { font-size:7.2px; color:#64748B; background:#F8FAFC; padding:5px 7px; border-radius:3px; border:1px solid #E2E8F0; line-height:1.35; }
        .summ-notes strong { color:#0F172A; font-size:7.8px; }
        .summ-notes ul { padding-left:11px; margin-top:2px; }
        .calc { width:100%; border-collapse:collapse; }
        .calc td { padding:1.5px 0; font-size:8.5px; color:#475569; }
        .calc td.v { text-align:right; font-weight:600; color:#0F172A; }
        .calc tr.t td { border-top:1.5px solid #0F172A; padding-top:3px; font-size:10px; font-weight:800; color:#0F172A; }
        .calc tr.t td.v { color:#059669; }

        /* Footer */
        .ftr { margin-top:auto; padding-top:5px; border-top:1px dashed #CBD5E1; display:flex; justify-content:space-between; font-size:7px; color:#94A3B8; }

        /* Print Media Styles: Fixed 100mm x 165mm standard label sheet */
        @media print {
            @page {
                size: 100mm 165mm;
                margin: 0;
            }
            html, body {
                width: 100mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #FFF !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .toolbar, .receipt-divider {
                display: none !important;
            }
            #printArea {
                width: 100mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: transparent !important;
                box-shadow: none !important;
            }
            .receipt {
                width: 100mm !important;
                height: 165mm !important;
                min-height: 165mm !important;
                max-height: 165mm !important;
                padding: 5mm 6mm 4mm 6mm !important;
                margin: 0 !important;
                box-sizing: border-box !important;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                page-break-before: auto !important;
                page-break-after: always !important;
                break-after: page !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                overflow: hidden !important;
            }
            .receipt:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
            }
            .receipt-main {
                flex: 1 0 auto !important;
            }
            .ftr {
                margin-top: auto !important;
            }
        }
    </style>
</head>
<body>

<?php
$store_name = isset($store_name) ? $store_name : get_setting('store_name', 'ShopVista');
$contact_email = get_setting('store_email', get_setting('contact_email', 'hello@shopvista.com'));
$contact_phone = get_setting('store_phone', get_setting('contact_phone', '0812-3456-7890'));
$store_logo_url = store_logo();
$short_code = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $store_name), 0, 2)) ?: 'SV';
?>

    <!-- Toolbar -->
    <div class="toolbar">
        <div class="toolbar-info">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <span><?= count($orders) === 1 ? 'Struk: <strong>#' . $orders[0]->order_number . '</strong>' : 'Struk Masal: <strong>' . count($orders) . ' Pesanan</strong>'; ?></span>
        </div>
        <div class="toolbar-actions">
            <?php $ids_param = isset($ids_string) ? $ids_string : implode(',', array_map(function($o){ return $o->id; }, $orders)); ?>
            <a href="<?= base_url('admin/orders/print_invoices?ids=' . $ids_param . '&action=download'); ?>" class="tbtn tbtn-green" id="btnDownload">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download PDF (TCPDF)
            </a>
            <a href="<?= base_url('admin/orders/print_invoices?ids=' . $ids_param . '&format=pdf'); ?>" target="_blank" class="tbtn tbtn-blue" id="btnPrint">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Cetak PDF Asli (TCPDF)
            </a>
            <button type="button" class="tbtn" onclick="window.print()" style="background:#6366F1;" title="Cetak langsung dengan ukuran kertas standar resi (100x165mm)">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Cetak Layar (100x165mm)
            </button>
            <a href="<?= count($orders) === 1 ? base_url('admin/orders/detail/' . $orders[0]->id) : base_url('admin/orders'); ?>" class="tbtn tbtn-gray">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Print Area -->
    <div id="printArea">
        <?php foreach ($orders as $index => $order): ?>
        <?php if ($index > 0): ?><hr class="receipt-divider"><?php endif; ?>
        <div class="receipt">
            <div class="receipt-main">
                <!-- Header -->
                <div class="hdr">
                    <div>
                        <div class="hdr-brand">
                            <?php if (!empty($store_logo_url)): ?>
                                <img src="<?= $store_logo_url; ?>" alt="<?= htmlspecialchars($store_name); ?>" class="hdr-logo-img">
                            <?php else: ?>
                                <div class="hdr-icon"><?= $short_code; ?></div>
                            <?php endif; ?>
                            <div class="hdr-name"><?= htmlspecialchars($store_name); ?></div>
                        </div>
                        <div class="hdr-sub"><?= htmlspecialchars($contact_email); ?><br><?= htmlspecialchars($contact_phone); ?></div>
                    </div>
                    <div class="hdr-r">
                        <h2>Struk Pengiriman</h2>
                        <div class="hdr-num">#<?= $order->order_number; ?></div>
                        <div class="hdr-bc"><svg class="js-barcode" data-code="<?= $order->order_number; ?>"></svg></div>
                        <div class="hdr-dt"><?= date('d/m/Y H:i', strtotime($order->created_at)); ?> WIB</div>
                    </div>
                </div>

                <!-- Meta -->
                <div class="meta">
                    <div>
                        <div class="meta-lbl">Penerima</div>
                        <div class="meta-val">
                            <strong><?= htmlspecialchars($order->shipping_name); ?></strong><br>
                            <?= htmlspecialchars($order->shipping_phone); ?><br>
                            <?= nl2br(htmlspecialchars($order->shipping_address)); ?><br>
                            <?= htmlspecialchars($order->shipping_city); ?>, <?= htmlspecialchars($order->shipping_province); ?> <?= htmlspecialchars($order->shipping_postal); ?>
                            <?php if (!empty($order->notes)): ?>
                                <div class="meta-note">Catatan: <?= htmlspecialchars($order->notes); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div class="meta-lbl">Kurir &amp; Resi</div>
                        <div class="meta-val">
                            <span class="meta-badge"><?= !empty($order->shipping_courier) ? htmlspecialchars($order->shipping_courier) : htmlspecialchars($store_name) . ' Express'; ?></span>
                            <div style="margin-top:3px;">Resi: <span class="meta-resi"><?= !empty($order->tracking_number) ? htmlspecialchars($order->tracking_number) : 'Belum Terbit'; ?></span></div>
                            <div class="meta-sm">Bayar: <strong><?= $order->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'COD'; ?></strong></div>
                            <div class="meta-sm">Status: <strong style="text-transform:capitalize;color:#059669;"><?= $order->status; ?></strong></div>
                        </div>
                    </div>
                </div>

                <hr class="sep">

                <!-- Products -->
                <table class="tbl">
                    <thead>
                        <tr>
                            <th style="width:20px;" class="c">#</th>
                            <th>Produk</th>
                            <th style="width:28px;" class="c">Qty</th>
                            <th style="width:68px;" class="r">Harga</th>
                            <th style="width:72px;" class="r">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($order->items)): foreach ($order->items as $i => $item): ?>
                        <tr>
                            <td class="c"><?= $i + 1; ?></td>
                            <td class="pn"><?= htmlspecialchars($item->product_name); ?></td>
                            <td class="c"><?= $item->quantity; ?></td>
                            <td class="r"><?= rupiah($item->price); ?></td>
                            <td class="r" style="font-weight:700;"><?= rupiah($item->subtotal); ?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>

                <!-- Summary -->
                <div class="summ">
                    <div class="summ-notes">
                        <strong>Ketentuan:</strong>
                        <ul>
                            <li>Simpan struk sebagai bukti beli.</li>
                            <li>Wajib video unboxing untuk klaim.</li>
                            <li>Terima kasih berbelanja di <?= htmlspecialchars($store_name); ?>!</li>
                        </ul>
                    </div>
                    <div>
                        <table class="calc">
                            <tr><td>Subtotal:</td><td class="v"><?= rupiah($order->total); ?></td></tr>
                            <tr><td>Ongkir:</td><td class="v" style="color:#059669;"><?= $order->shipping_cost > 0 ? rupiah($order->shipping_cost) : 'GRATIS'; ?></td></tr>
                            <tr class="t"><td>Total:</td><td class="v"><?= rupiah($order->grand_total); ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="ftr">
                <div>Dicetak: <?= date('d/m/Y H:i'); ?> WIB</div>
                <div>Hal. <?= $index + 1; ?>/<?= count($orders); ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Barcodes
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof JsBarcode !== 'undefined') {
                document.querySelectorAll('.js-barcode').forEach(function(el) {
                    var code = el.getAttribute('data-code');
                    if (code) {
                        JsBarcode(el, code, { format:"CODE128", width:0.9, height:20, displayValue:false, margin:0 });
                    }
                });
            }
        });

        // Filename
        var cleanStore = <?= json_encode(preg_replace('/[^A-Za-z0-9_\-]/', '_', $store_name)); ?>;
        <?php
        if (count($orders) === 1) {
            $js_fn = "'Struk_' + " . json_encode($orders[0]->order_number) . " + '_' + cleanStore + '.pdf'";
        } else {
            $js_fn = "'Struk_Masal_' + cleanStore + '_" . date('Ymd_His') . ".pdf'";
        }
        ?>
        var pdfFilename = <?= $js_fn; ?>;

        var tcpdfDownloadUrl = <?= json_encode(base_url('admin/orders/print_invoices?ids=' . $ids_param . '&action=download')); ?>;
        var tcpdfViewUrl = <?= json_encode(base_url('admin/orders/print_invoices?ids=' . $ids_param . '&format=pdf')); ?>;

        // Real PDF Download via TCPDF
        function doDownload() {
            window.location.href = tcpdfDownloadUrl;
        }

        // Real PDF Print via TCPDF
        function doPrint() {
            window.open(tcpdfViewUrl, '_blank');
        }
    </script>
</body>
</html>
