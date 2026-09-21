<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="<?= base_url('assets/js/JsBarcode.all.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/html2pdf.bundle.min.js'); ?>"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #F1F5F9;
            color: #0F172A;
            padding-bottom: 3rem;
        }

        /* Top Action Bar (No-Print) */
        .print-toolbar {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            background: #0F172A;
            color: #FFFFFF;
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            z-index: 1000;
        }

        .print-toolbar-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
        }

        .print-toolbar-info strong {
            color: #38BDF8;
            font-family: 'JetBrains Mono', monospace;
        }

        .print-toolbar-actions {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }

        .btn-print {
            background: #3B82F6;
            color: #FFFFFF;
        }
        .btn-print:hover {
            background: #2563EB;
        }

        .btn-download {
            background: #10B981;
            color: #FFFFFF;
        }
        .btn-download:hover {
            background: #059669;
        }

        .btn-back {
            background: #334155;
            color: #FFFFFF;
        }
        .btn-back:hover {
            background: #475569;
        }

        /* Invoice Container */
        .invoices-wrapper {
            max-width: 680px;
            margin: 1.25rem auto;
            padding: 0 0.75rem;
        }

        .invoice-sheet {
            background: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 1.35rem 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #E2E8F0;
            position: relative;
        }

        /* Invoice Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1.5px solid #0F172A;
            padding-bottom: 0.65rem;
            margin-bottom: 0.85rem;
        }

        .brand-logo-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.2rem;
        }

        .brand-logo-icon {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #4F46E5, #3B82F6);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 0.85rem;
        }

        .brand-name {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.3px;
        }
        .brand-name span { color: #4F46E5; }

        .brand-subtitle {
            font-size: 0.72rem;
            color: #64748B;
            line-height: 1.35;
        }

        .invoice-title-block {
            text-align: right;
        }

        .invoice-title-block h2 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .invoice-order-number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.92rem;
            font-weight: 700;
            color: #4F46E5;
            margin-top: 1px;
        }

        .invoice-barcode-box {
            margin-top: 2px;
            display: flex;
            justify-content: flex-end;
        }

        .invoice-barcode-box svg {
            max-height: 28px;
            max-width: 135px;
        }

        /* 2-Column Meta Details */
        .invoice-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            background: #F8FAFC;
            border-radius: 6px;
            border: 1px solid #E2E8F0;
            margin-bottom: 0.85rem;
        }

        .meta-title {
            font-size: 0.66rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748B;
            margin-bottom: 0.2rem;
        }

        .meta-content {
            font-size: 0.78rem;
            color: #1E293B;
            line-height: 1.35;
        }

        .meta-content strong {
            color: #0F172A;
            font-weight: 700;
        }

        .badge-courier {
            display: inline-block;
            background: #EEF2FF;
            color: #4338CA;
            border: 1px solid #C7D2FE;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 700;
            margin-top: 2px;
        }

        .badge-status {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-status-pending {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FCD34D;
        }
        .badge-status-success {
            background: #DEF7EC;
            color: #03543F;
            border: 1px solid #A7F3D0;
        }

        /* Products Table */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.85rem;
        }

        .invoice-table th {
            background: #0F172A;
            color: #FFFFFF;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 0.45rem 0.65rem;
            text-align: left;
        }

        .invoice-table th.text-right { text-align: right; }
        .invoice-table th.text-center { text-align: center; }

        .invoice-table td {
            padding: 0.45rem 0.65rem;
            font-size: 0.78rem;
            border-bottom: 1px solid #E2E8F0;
            color: #334155;
        }

        .invoice-table td.text-right { text-align: right; }
        .invoice-table td.text-center { text-align: center; }

        .invoice-table tbody tr:nth-child(even) {
            background: #FAFAFA;
        }

        .product-name-col {
            font-weight: 600;
            color: #0F172A;
        }

        /* Calculation Summary */
        .invoice-bottom-grid {
            display: grid;
            grid-template-columns: 1fr 250px;
            gap: 0.85rem;
            align-items: start;
        }

        .invoice-notes-box {
            font-size: 0.72rem;
            color: #475569;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 0.55rem 0.75rem;
            line-height: 1.35;
        }

        .invoice-summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-summary-table td {
            padding: 0.2rem 0;
            font-size: 0.78rem;
            color: #64748B;
        }

        .invoice-summary-table td.amount {
            text-align: right;
            font-weight: 700;
            color: #0F172A;
        }

        .invoice-summary-table tr.total td {
            padding-top: 0.4rem;
            border-top: 1.5px solid #0F172A;
            font-weight: 800;
            color: #0F172A;
            font-size: 0.9rem;
        }

        .invoice-summary-table tr.total td.amount {
            color: #4F46E5;
            font-size: 0.98rem;
        }

        /* Footer */
        .invoice-footer {
            margin-top: 0.85rem;
            padding-top: 0.45rem;
            border-top: 1px dashed #CBD5E1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.68rem;
            color: #94A3B8;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #FFFFFF !important;
                padding: 0 !important;
            }

            .print-toolbar {
                display: none !important;
            }

            .invoices-wrapper {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .invoice-sheet {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            @page {
                size: A4 portrait;
                margin: 0.8cm 1cm;
            }
        }
    </style>
</head>
<body>

    <!-- Sticky Print Control Toolbar -->
    <div class="print-toolbar no-print">
        <div class="print-toolbar-info">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            <span>Invoice Resmi: <strong><?= $order->order_number; ?></strong></span>
        </div>

        <div class="print-toolbar-actions">
            <button type="button" class="btn-action btn-download" id="btnDownloadPdf" onclick="downloadPdf()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span>Download PDF</span>
            </button>

            <button type="button" class="btn-action btn-print" onclick="window.print()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak / Print</span>
            </button>

            <a href="<?= base_url('profil/pesanan/' . $order->order_number); ?>" class="btn-action btn-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Detail Pesanan</span>
            </a>
        </div>
    </div>

    <!-- Container for Invoice -->
    <div class="invoices-wrapper" id="invoicePrintArea">
        <div class="invoice-sheet" id="invoice-sheet-<?= $order->id; ?>">
            <!-- Header -->
            <div class="invoice-header">
                <div>
                    <div class="brand-logo-title">
                        <div class="brand-logo-icon">SV</div>
                        <div class="brand-name">Shop<span>Vista</span></div>
                    </div>
                    <div class="brand-subtitle">
                        <?= get_setting('site_name', 'ShopVista'); ?> &bull; Toko Online Terpercaya Indonesia<br>
                        Email: <?= get_setting('store_email', 'cs@shopvista.com'); ?> &bull; Telp: <?= get_setting('whatsapp', '081298765432'); ?>
                    </div>
                </div>

                <div class="invoice-title-block">
                    <h2>INVOICE PESANAN</h2>
                    <div class="invoice-order-number"><?= $order->order_number; ?></div>
                    <div style="font-size: 0.8rem; color: #64748B; margin-top: 3px;">
                        Tanggal: <strong><?= date('d M Y, H:i', strtotime($order->created_at)); ?> WIB</strong>
                    </div>
                    <div class="invoice-barcode-box">
                        <svg class="js-barcode" data-code="<?= $order->order_number; ?>"></svg>
                    </div>
                </div>
            </div>

            <!-- Meta Grid: Destination & Shipping/Payment -->
            <div class="invoice-meta-grid">
                <div>
                    <div class="meta-title">Tujuan Pengiriman</div>
                    <div class="meta-content">
                        <strong><?= htmlspecialchars($order->shipping_name); ?></strong> (<?= htmlspecialchars($order->shipping_phone); ?>)<br>
                        <?= nl2br(htmlspecialchars($order->shipping_address)); ?><br>
                        <?= htmlspecialchars($order->shipping_city); ?>, <?= htmlspecialchars($order->shipping_province); ?> <?= htmlspecialchars($order->shipping_postal); ?>
                        <?php if (!empty($order->notes)): ?>
                        <div style="margin-top: 4px; font-size: 0.8rem; color: #B45309;">
                            <em>Catatan: <?= htmlspecialchars($order->notes); ?></em>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <div class="meta-title">Ekspedisi & Status Pembayaran</div>
                    <div class="meta-content">
                        <span class="badge-courier"><?= !empty($order->shipping_courier) ? htmlspecialchars($order->shipping_courier) : 'ShopVista Express'; ?></span>
                        <div style="margin-top: 6px;">
                            No. Resi: <strong style="font-family: 'JetBrains Mono', monospace; font-size: 0.95rem;"><?= !empty($order->tracking_number) ? htmlspecialchars($order->tracking_number) : 'Belum Terbit'; ?></strong>
                        </div>
                        <div style="margin-top: 4px; font-size: 0.82rem; color: #64748B;">
                            Metode Bayar: <strong><?= $order->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'COD (Bayar di Tempat)'; ?></strong>
                        </div>
                        <div style="margin-top: 4px; font-size: 0.82rem; color: #64748B;">
                            Status: 
                            <?php if ($order->status == 'pending'): ?>
                                <span class="badge-status badge-status-pending">Menunggu Pembayaran</span>
                            <?php else: ?>
                                <span class="badge-status badge-status-success">Lunas / Terverifikasi</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Itemized Products Table -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 40px;" class="text-center">No</th>
                        <th>Nama Produk</th>
                        <th style="width: 70px;" class="text-center">Qty</th>
                        <th style="width: 140px;" class="text-right">Harga Satuan</th>
                        <th style="width: 150px;" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($order->items)): foreach ($order->items as $i => $item): ?>
                    <tr>
                        <td class="text-center"><?= $i + 1; ?></td>
                        <td class="product-name-col"><?= htmlspecialchars($item->product_name); ?></td>
                        <td class="text-center"><?= $item->quantity; ?></td>
                        <td class="text-right"><?= rupiah($item->price); ?></td>
                        <td class="text-right" style="font-weight: 700;"><?= rupiah($item->subtotal); ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>

            <!-- Bottom: Notes and Price Summary -->
            <div class="invoice-bottom-grid">
                <div class="invoice-notes-box">
                    <strong>Syarat & Ketentuan Pembelian:</strong>
                    <ul style="padding-left: 1.1rem; margin-top: 5px; line-height: 1.45;">
                        <li>Simpan faktur/invoice resmi ini sebagai bukti pembelian yang sah untuk klaim garansi.</li>
                        <li>Pastikan membuat video unboxing tanpa jeda saat paket diterima untuk mempermudah klaim asuransi atau kendala pengiriman.</li>
                        <li>Terima kasih telah berbelanja di <?= htmlspecialchars(get_setting('store_name', 'ShopVista')); ?>!</li>
                    </ul>
                </div>

                <div>
                    <table class="invoice-summary-table">
                        <tr>
                            <td>Subtotal Produk:</td>
                            <td class="amount"><?= rupiah($order->total); ?></td>
                        </tr>
                        <tr>
                            <td>Ongkos Kirim:</td>
                            <td class="amount" style="color: #059669;"><?= $order->shipping_cost > 0 ? rupiah($order->shipping_cost) : 'GRATIS'; ?></td>
                        </tr>
                        <tr>
                            <td>Biaya Asuransi:</td>
                            <td class="amount" style="color: #059669;">GRATIS</td>
                        </tr>
                        <tr class="total">
                            <td>Total Tagihan:</td>
                            <td class="amount"><?= rupiah($order->grand_total); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div class="invoice-footer">
                <div>Dicetak secara sistem otomatis pada <?= date('d M Y, H:i:s'); ?> WIB &bull; <?= htmlspecialchars(get_setting('store_name', 'ShopVista')); ?></div>
                <div>Status Dokumen: Asli / Sah</div>
            </div>
        </div>
    </div>

    <script>
        // Render Barcode
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof JsBarcode !== 'undefined') {
                const barcodes = document.querySelectorAll('.js-barcode');
                barcodes.forEach(el => {
                    const code = el.getAttribute('data-code');
                    if (code) {
                        JsBarcode(el, code, {
                            format: "CODE128",
                            width: 1.15,
                            height: 25,
                            displayValue: false,
                            margin: 0
                        });
                    }
                });
            }

            <?php if (!empty($auto_download)): ?>
            // Automatically trigger PDF download if parameter ?download=1 is supplied
            setTimeout(function() {
                downloadPdf();
            }, 600);
            <?php endif; ?>
        });

        // Direct PDF file download using html2pdf
        function downloadPdf() {
            const btn = document.getElementById('btnDownloadPdf');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span>Sedang Menghasilkan PDF...</span>';
            btn.disabled = true;

            const element = document.getElementById('invoicePrintArea');
            const opt = {
                margin:       [6, 8, 6, 8], // mm
                filename:     'Invoice_<?= $order->order_number; ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }).catch(err => {
                alert('Gagal mendownload PDF: ' + err);
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>
