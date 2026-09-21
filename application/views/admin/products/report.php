<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
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
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }

        .stat-label { font-size: 0.75rem; color: #64748B; text-transform: uppercase; font-weight: 700; margin-bottom: 4px; }
        .stat-val { font-size: 1.25rem; font-weight: 800; color: #4F46E5; }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            margin-bottom: 1.5rem;
        }

        .report-table th {
            background: #0F172A;
            color: white;
            padding: 0.65rem 0.75rem;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .report-table td {
            padding: 0.65rem 0.75rem;
            border-bottom: 1px solid #E2E8F0;
            color: #334155;
        }

        .report-table tbody tr:nth-child(even) { background: #FAFAFA; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .badge-status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 700;
        }
        .badge-active { background: #DCFCE7; color: #166534; }
        .badge-inactive { background: #FEE2E2; color: #991B1B; }

        .report-signature {
            display: flex;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1rem;
        }

        .sign-box {
            text-align: center;
            width: 220px;
            font-size: 0.82rem;
        }

        @media print {
            body { background: white !important; padding: 0 !important; }
            .report-toolbar { display: none !important; }
            .report-container { box-shadow: none !important; border: none !important; padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
            @page { size: A4 landscape; margin: 1cm; }
        }
    </style>
</head>
<body>
<?php $store_name = isset($store_name) ? $store_name : get_setting('store_name', 'ShopVista'); ?>

    <div class="report-toolbar">
        <div>
            <a href="<?= base_url('admin/products'); ?>" style="background:#334155;color:white;text-decoration:none;padding:0.45rem 0.9rem;border-radius:6px;font-weight:700;margin-right:8px;display:inline-flex;align-items:center;">
                &larr; Kembali ke Produk
            </a>
            <strong>Laporan Inventaris Produk - <?= htmlspecialchars($store_name); ?></strong> • Total: <?= count($products); ?> Item
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            <button type="button" onclick="downloadReportExcel()" id="btnExcel" style="background:#059669;color:white;border:none;padding:0.45rem 0.9rem;border-radius:6px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                📊 Download Laporan Excel (.xlsx)
            </button>
        </div>
    </div>

    <div class="report-container" id="reportArea">
        <div class="report-header">
            <div>
                <h2 style="font-weight:800;color:#0F172A;font-size:1.4rem;"><?= htmlspecialchars($store_name); ?></h2>
                <div style="font-size:0.8rem;color:#64748B;">Sistem Manajemen Toko Online & Inventaris</div>
            </div>
            <div class="report-title-box" style="text-align: right;">
                <h1>Laporan Data Produk</h1>
                <div style="font-size:0.78rem;color:#64748B;margin-top:2px;">
                    Dicetak pada: <?= date('d F Y, H:i'); ?> WIB
                </div>
            </div>
        </div>

        <?php
        $total_stok = 0;
        $total_nilai_aset = 0;
        foreach ($products as $p) {
            $total_stok += $p->stock;
            $price_eff = $p->sale_price ?: $p->price;
            $total_nilai_aset += ($price_eff * $p->stock);
        }
        ?>

        <div class="report-stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Jenis Produk</div>
                <div class="stat-val"><?= count($products); ?> Item</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Unit Stok Fisik</div>
                <div class="stat-val" style="color:#059669;"><?= number_format($total_stok); ?> pcs</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Estimasi Nilai Inventaris</div>
                <div class="stat-val" style="color:#2563EB;"><?= rupiah($total_nilai_aset); ?></div>
            </div>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th class="text-center" style="width:35px;">No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th class="text-center">Berat</th>
                    <th class="text-right">Harga Normal</th>
                    <th class="text-right">Harga Promo</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $idx => $p): ?>
                <tr>
                    <td class="text-center"><?= $idx + 1; ?></td>
                    <td style="font-weight:700;color:#0F172A;"><?= htmlspecialchars($p->name); ?></td>
                    <td><?= htmlspecialchars($p->category_name ?: '-'); ?></td>
                    <td class="text-center"><?= !empty($p->weight) ? htmlspecialchars($p->weight) . ' gr' : '-'; ?></td>
                    <td class="text-right"><?= rupiah($p->price); ?></td>
                    <td class="text-right" style="color:<?= $p->sale_price ? '#059669' : '#94A3B8'; ?>;font-weight:<?= $p->sale_price ? '700' : 'normal'; ?>;">
                        <?= $p->sale_price ? rupiah($p->sale_price) : '-'; ?>
                    </td>
                    <td class="text-center" style="font-weight:700;color:<?= $p->stock > 0 ? '#059669' : '#DC2626'; ?>;">
                        <?= $p->stock; ?>
                    </td>
                    <td class="text-center">
                        <span class="badge-status <?= $p->is_active ? 'badge-active' : 'badge-inactive'; ?>">
                            <?= $p->is_active ? 'Aktif' : 'Nonaktif'; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="report-signature">
            <div class="sign-box">
                <div>Penanggung Jawab Gudang,</div>
                <div style="height: 60px;"></div>
                <div style="font-weight: 700; text-decoration: underline;">Administrator <?= htmlspecialchars($store_name); ?></div>
                <div style="font-size: 0.75rem; color: #64748B;">Staff Inventaris & Logistik</div>
            </div>
        </div>
    </div>

    <script>
    function downloadReportExcel() {
        const table = document.querySelector('.report-table');
        if (!table) {
            alert('Tabel laporan tidak ditemukan');
            return;
        }
        const btn = document.getElementById('btnExcel');
        const origText = btn.innerText;
        btn.innerText = 'Memproses...';
        btn.disabled = true;

        try {
            const store = <?= json_encode(preg_replace('/[^A-Za-z0-9_\-]/', '_', $store_name)); ?>;
            const wb = XLSX.utils.table_to_book(table, { sheet: "Laporan_Produk" });
            XLSX.writeFile(wb, 'Laporan_Produk_' + store + '_<?= date('Ymd_His'); ?>.xlsx');
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
