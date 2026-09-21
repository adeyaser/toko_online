<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    <script src="<?= base_url('assets/js/html2pdf.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/xlsx.full.min.js'); ?>"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #F8FAFC; color: #0F172A; padding-bottom: 3rem; }
        
        .report-toolbar {
            position: sticky;
            top: 0;
            background: #0F172A;
            color: white;
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
        }

        .report-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: #FFFFFF;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            border: 1px solid #E2E8F0;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 1.25rem;
            border-bottom: 2px solid #0F172A;
            margin-bottom: 1.5rem;
        }

        .report-title-box h1 {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 1rem;
        }

        .stat-card .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748B;
            font-weight: 600;
            margin-bottom: 0.35rem;
        }

        .stat-card .value {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0F172A;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            margin-top: 1rem;
        }

        .report-table th {
            background: #F1F5F9;
            color: #334155;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.5px;
            padding: 0.65rem 0.6rem;
            border: 1px solid #CBD5E1;
            text-align: left;
        }

        .report-table td {
            padding: 0.65rem 0.6rem;
            border: 1px solid #E2E8F0;
            color: #1E293B;
            vertical-align: middle;
        }

        .report-table tr:nth-child(even) td {
            background: #FAFAFA;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-primary { background: #2563EB; color: white; }
        .btn-primary:hover { background: #1D4ED8; }
        .btn-success { background: #059669; color: white; }
        .btn-success:hover { background: #047857; }
        .btn-light { background: #334155; color: white; }
        .btn-light:hover { background: #475569; }

        .report-footer {
            margin-top: 3rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 0.85rem;
        }

        @media print {
            .report-toolbar { display: none !important; }
            body { background: white; padding: 0; }
            .report-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 1.5cm !important;
                box-shadow: none !important;
                border: none !important;
            }
            .report-table { page-break-inside: auto; }
            .report-table tr { page-break-inside: avoid; page-break-after: auto; }
            .report-footer { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
<?php $store_name = isset($store_name) ? $store_name : get_setting('store_name', 'ShopVista'); ?>

    <!-- STICKY CONTROL TOOLBAR -->
    <div class="report-toolbar">
        <div style="display:flex; align-items:center; gap: 1rem;">
            <a href="<?= base_url('admin/couriers'); ?>" class="btn btn-light">
                &larr; Kembali ke Jasa Pengiriman
            </a>
            <span style="font-weight:600; font-size: 0.95rem;">Laporan Tarif & Jasa Ekspedisi - <?= htmlspecialchars($store_name); ?></span>
        </div>
        <div style="display:flex; align-items:center; gap: 0.75rem;">
            <button onclick="downloadExcel()" class="btn btn-success" id="btn-excel">
                📊 Download Laporan Excel (.xlsx)
            </button>
        </div>
    </div>

    <!-- MAIN REPORT DOCUMENT -->
    <div class="report-container" id="report-content">
        <!-- HEADER -->
        <div class="report-header">
            <div>
                <div style="font-size: 1.35rem; font-weight: 800; color: #2563EB; display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.25rem;">
                    <span><?= htmlspecialchars($store_name); ?></span>
                    <span style="font-size: 0.7rem; background: #EEF2FF; color: #4F46E5; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700;">LOGISTICS DIRECTORY</span>
                </div>
                <div style="font-size: 0.8rem; color: #64748B;">Sistem Manajemen Toko Online <?= htmlspecialchars($store_name); ?></div>
                <div style="font-size: 0.8rem; color: #64748B;"><?= get_setting('store_address', 'Indonesia'); ?></div>
            </div>
            <div style="text-align: right;" class="report-title-box">
                <h1>Laporan Jasa & Tarif Ekspedisi</h1>
                <div style="font-size: 0.82rem; color: #64748B; margin-top: 0.25rem;">
                    Tanggal Cetak: <strong><?= date('d/m/Y H:i'); ?> WIB</strong>
                </div>
                <div style="font-size: 0.82rem; color: #64748B;">
                    Pencetak: <strong><?= $this->session->userdata('user_name') ?: 'Administrator'; ?></strong>
                </div>
            </div>
        </div>

        <?php
            $total_couriers = count($couriers);
            $total_active = 0;
            $free_eligible_count = 0;
            $avg_cost = 0;
            $sum_cost = 0;
            foreach ($couriers as $c) {
                if ($c->is_active) $total_active++;
                if ($c->is_free_eligible) $free_eligible_count++;
                $sum_cost += (float)$c->cost;
            }
            $avg_cost = $total_couriers > 0 ? $sum_cost / $total_couriers : 0;
        ?>

        <!-- SUMMARY STATISTICS -->
        <div class="report-stats-grid">
            <div class="stat-card">
                <div class="label">Total Layanan</div>
                <div class="value"><?= $total_couriers; ?> <span style="font-size:0.75rem; color:#64748B; font-weight:normal;">opsi</span></div>
            </div>
            <div class="stat-card">
                <div class="label">Layanan Aktif</div>
                <div class="value" style="color: #059669;"><?= $total_active; ?></div>
            </div>
            <div class="stat-card">
                <div class="label">Ikut Bebas Ongkir</div>
                <div class="value" style="color: #2563EB;"><?= $free_eligible_count; ?></div>
            </div>
            <div class="stat-card">
                <div class="label">Rata-rata Tarif</div>
                <div class="value" style="color: #0F172A;"><?= rupiah($avg_cost); ?></div>
            </div>
        </div>

        <!-- COURIERS TABLE -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">No</th>
                    <th>Nama Ekspedisi / Kurir</th>
                    <th>Nama Layanan</th>
                    <th style="width: 130px; text-align: right;">Tarif Ongkir</th>
                    <th style="width: 100px; text-align: center;">Estimasi (ETD)</th>
                    <th style="width: 110px; text-align: center;">Bebas Ongkir</th>
                    <th style="width: 80px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($couriers)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #94A3B8;">
                            Belum ada opsi jasa pengiriman yang terdaftar.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($couriers as $c): ?>
                        <tr>
                            <td style="text-align: center; color: #64748B;"><?= $no++; ?></td>
                            <td>
                                <strong style="color: #0F172A;"><?= htmlspecialchars($c->courier_name); ?></strong>
                                <?php if (!empty($c->badge)): ?>
                                    <span style="font-size: 0.68rem; background: #EEF2FF; color: #4338CA; padding: 0.1rem 0.4rem; border-radius: 4px; font-weight: 700; margin-left: 4px;">
                                        <?= htmlspecialchars($c->badge); ?>
                                    </span>
                                <?php endif; ?>
                                <div style="font-size: 0.72rem; color: #64748B; font-family: 'JetBrains Mono', monospace;"><?= htmlspecialchars($c->code); ?></div>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($c->service_name); ?></strong>
                                <div style="font-size: 0.74rem; color: #64748B;"><?= htmlspecialchars($c->description ?: '-'); ?></div>
                            </td>
                            <td style="text-align: right; font-weight: 700; font-family: 'JetBrains Mono', monospace; color: <?= $c->cost <= 0 ? '#059669' : '#0F172A'; ?>;">
                                <?= $c->cost <= 0 ? 'GRATIS' : rupiah($c->cost); ?>
                            </td>
                            <td style="text-align: center; font-weight: 600; color: #475569;">
                                <?= htmlspecialchars($c->etd); ?>
                            </td>
                            <td style="text-align: center;">
                                <span style="display:inline-block;padding:0.15rem 0.45rem;border-radius:4px;font-size:0.7rem;font-weight:700;background:<?= $c->is_free_eligible ? '#D1FAE5' : '#F1F5F9'; ?>;color:<?= $c->is_free_eligible ? '#065F46' : '#64748B'; ?>;">
                                    <?= $c->is_free_eligible ? 'Ikut Promo' : 'Tidak'; ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span style="display:inline-block;padding:0.15rem 0.45rem;border-radius:4px;font-size:0.7rem;font-weight:700;background:<?= $c->is_active ? '#D1FAE5' : '#FEE2E2'; ?>;color:<?= $c->is_active ? '#065F46' : '#991B1B'; ?>;">
                                    <?= $c->is_active ? 'Aktif' : 'Nonaktif'; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- FOOTER SIGNATURE -->
        <div class="report-footer">
            <div style="color: #64748B; font-size: 0.75rem;">
                * Dokumen tarif pengiriman ini berlaku untuk pengaturan logistik <?= htmlspecialchars($store_name); ?>.<br>
                * Tarif dapat disesuaikan sewaktu-waktu melalui modul administrasi.
            </div>
            <div style="text-align: center; width: 220px;">
                <div style="font-size: 0.8rem; color: #475569; margin-bottom: 3.5rem;">
                    Divalidasi Oleh,<br><strong>Manajer Operasional & Logistik</strong>
                </div>
                <div style="border-top: 1px dashed #94A3B8; padding-top: 0.25rem;">
                    <strong><?= $this->session->userdata('user_name') ?: 'Administrator'; ?></strong><br>
                    <span style="font-size: 0.75rem; color: #64748B;"><?= htmlspecialchars($store_name); ?> Logistics</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function downloadExcel() {
            const table = document.querySelector('.report-table');
            if (!table) {
                alert('Tabel laporan tidak ditemukan');
                return;
            }
            const btn = document.getElementById('btn-excel');
            const originalText = btn.innerText;
            btn.innerText = 'Memproses Excel...';
            btn.disabled = true;

            try {
                const store = <?= json_encode(preg_replace('/[^A-Za-z0-9_\-]/', '_', $store_name)); ?>;
                const wb = XLSX.utils.table_to_book(table, { sheet: "Laporan_Ekspedisi" });
                XLSX.writeFile(wb, 'Laporan_Ekspedisi_' + store + '_<?= date('Ymd_His'); ?>.xlsx');
            } catch (e) {
                console.error(e);
                alert('Gagal mengunduh Excel: ' + e.message);
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
