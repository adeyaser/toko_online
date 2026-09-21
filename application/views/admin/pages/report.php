<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
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

        .report-toolbar h2 {
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toolbar-actions {
            display: flex;
            gap: 8px;
        }

        .btn-tool {
            padding: 6px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-print { background: #3B82F6; color: white; }
        .btn-print:hover { background: #2563EB; }
        .btn-excel { background: #059669; color: white; }
        .btn-excel:hover { background: #047857; }
        .btn-pdf { background: #4F46E5; color: white; }
        .btn-pdf:hover { background: #4338CA; }
        .btn-back { background: #334155; color: white; }
        .btn-back:hover { background: #475569; }

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

        .report-title-box p {
            font-size: 0.85rem;
            color: #64748B;
            margin-top: 4px;
        }

        .report-meta-box {
            text-align: right;
            font-size: 0.82rem;
            color: #475569;
            line-height: 1.5;
        }

        .report-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            text-align: left;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0F172A;
            margin-top: 4px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            margin-top: 1rem;
        }

        .report-table th {
            background: #F1F5F9;
            color: #1E293B;
            font-weight: 700;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 2px solid #CBD5E1;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .report-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #E2E8F0;
            color: #334155;
            vertical-align: top;
        }

        .report-table tr:nth-child(even) {
            background: #FAFAFA;
        }

        .badge-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-active { background: #DCFCE7; color: #166534; }
        .badge-inactive { background: #FEE2E2; color: #991B1B; }

        .report-footer {
            margin-top: 3rem;
            padding-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 0.8rem;
            color: #64748B;
            border-top: 1px dashed #CBD5E1;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            margin-top: 55px;
            border-bottom: 1px solid #0F172A;
        }

        @media print {
            .report-toolbar { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .report-container { border: none !important; box-shadow: none !important; margin: 0 !important; max-width: 100% !important; padding: 0 !important; }
        }
    </style>
</head>
<body>
<?php $store_name = isset($store_name) ? $store_name : get_setting('store_name', 'ShopVista'); ?>

    <div class="report-toolbar">
        <h2>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            Laporan Halaman Statis & Informasi - <?= htmlspecialchars($store_name); ?>
        </h2>
        <div class="toolbar-actions" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            <button onclick="exportToExcel()" class="btn-tool btn-excel" id="btnExcel">
                📊 Download Laporan Excel (.xlsx)
            </button>
            <a href="<?= base_url('admin/pages'); ?>" class="btn-tool btn-back">
                &larr; Kembali
            </a>
        </div>
    </div>

    <div id="printArea" class="report-container">
        <div class="report-header">
            <div class="report-title-box">
                <h1><?= htmlspecialchars($store_name); ?></h1>
                <p>Laporan Data Halaman Statis & Kebijakan Informasi Publik</p>
            </div>
            <div class="report-meta-box">
                <div>Tanggal: <strong><?= date('d F Y, H:i'); ?> WIB</strong></div>
                <div>Dicetak Oleh: <strong><?= $this->session->userdata('user_name') ?: 'Administrator'; ?></strong></div>
                <div>Status Toko: <strong>Aktif</strong></div>
            </div>
        </div>

        <?php
            $total_count = count($pages);
            $active_count = 0;
            foreach ($pages as $p) {
                if ($p->is_active) $active_count++;
            }
            $inactive_count = $total_count - $active_count;
        ?>

        <div class="report-stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Halaman Terdaftar</div>
                <div class="stat-value"><?= $total_count; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Halaman Aktif / Publik</div>
                <div class="stat-value" style="color: #059669;"><?= $active_count; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Halaman Nonaktif / Draf</div>
                <div class="stat-value" style="color: #DC2626;"><?= $inactive_count; ?></div>
            </div>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th>Judul Halaman</th>
                    <th>URL Path (Slug)</th>
                    <th>Ikon</th>
                    <th>Panjang Konten</th>
                    <th style="width: 100px; text-align: center;">Status</th>
                    <th>Update Terakhir</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pages)): ?>
                    <?php $no = 1; foreach ($pages as $p): ?>
                        <tr>
                            <td style="text-align: center; font-weight: 700;"><?= $no++; ?></td>
                            <td>
                                <strong><?= htmlspecialchars($p->title); ?></strong>
                                <?php if (!empty($p->meta_title)): ?>
                                    <div style="font-size: 0.75rem; color: #64748B; margin-top: 2px;">SEO: <?= htmlspecialchars($p->meta_title); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><code style="font-family: monospace; font-size: 0.85rem; color: #4F46E5;">/<?= htmlspecialchars($p->slug); ?></code></td>
                            <td><code><?= htmlspecialchars($p->icon); ?></code></td>
                            <td><?= strlen(strip_tags($p->content)); ?> karakter</td>
                            <td style="text-align: center;">
                                <span class="badge-status <?= $p->is_active ? 'badge-active' : 'badge-inactive'; ?>">
                                    <?= $p->is_active ? 'PUBLIK' : 'DRAF'; ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($p->updated_at)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #64748B;">Tidak ada data halaman.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="report-footer">
            <div>
                <div>Dokumen ini digenerate secara otomatis oleh sistem <?= htmlspecialchars($store_name); ?>.</div>
                <div>URL Toko: <?= base_url(); ?></div>
            </div>
            <div class="signature-box">
                <div>Penanggung Jawab,</div>
                <div class="signature-line"></div>
                <div style="margin-top: 4px; font-weight: 700;"><?= $this->session->userdata('user_name') ?: 'Administrator'; ?></div>
            </div>
        </div>
    </div>

    <script>
    function exportToExcel() {
        var table = document.querySelector('.report-table');
        if (!table) {
            alert('Tabel laporan tidak ditemukan');
            return;
        }
        var btn = document.getElementById('btnExcel');
        var origText = btn.innerText;
        btn.innerText = 'Memproses...';
        btn.disabled = true;

        try {
            var wb = XLSX.utils.table_to_book(table, { sheet: "Laporan_Halaman" });
            const store = <?= json_encode(preg_replace('/[^A-Za-z0-9_\-]/', '_', $store_name)); ?>;
            XLSX.writeFile(wb, 'Laporan_Halaman_' + store + '_<?= date('Ymd_His'); ?>.xlsx');
        } catch (err) {
            console.error(err);
            alert('Gagal mengunduh Excel: ' + err.message);
        } finally {
            btn.innerText = origText;
            btn.disabled = false;
        }
    }
    </script>
</body>
</html>
