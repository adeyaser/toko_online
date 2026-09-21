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

        .badge-role {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: capitalize;
        }
        .badge-admin { background: #E0E7FF; color: #3730A3; }
        .badge-customer { background: #E0F2FE; color: #0369A1; }

        .badge-status {
            display: inline-block;
            padding: 0.2rem 0.45rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .badge-active { background: #D1FAE5; color: #065F46; }
        .badge-inactive { background: #FEE2E2; color: #991B1B; }

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
            <a href="<?= base_url('admin/users'); ?>" class="btn btn-light">
                &larr; Kembali ke Pengguna
            </a>
            <span style="font-weight:600; font-size: 0.95rem;">Laporan Data Pengguna - <?= htmlspecialchars($store_name); ?></span>
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
                    <span style="font-size: 0.7rem; background: #EEF2FF; color: #4F46E5; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700;">USER DIRECTORY</span>
                </div>
                <div style="font-size: 0.8rem; color: #64748B;">Sistem Manajemen Toko Online <?= htmlspecialchars($store_name); ?></div>
                <div style="font-size: 0.8rem; color: #64748B;"><?= get_setting('store_address', 'Indonesia'); ?></div>
            </div>
            <div style="text-align: right;" class="report-title-box">
                <h1>Laporan Data Pengguna</h1>
                <div style="font-size: 0.82rem; color: #64748B; margin-top: 0.25rem;">
                    Tanggal Cetak: <strong><?= date('d/m/Y H:i'); ?> WIB</strong>
                </div>
                <div style="font-size: 0.82rem; color: #64748B;">
                    Pencetak: <strong><?= $this->session->userdata('user_name') ?: 'Administrator'; ?></strong>
                </div>
            </div>
        </div>

        <?php
            $total_users = count($users);
            $total_customers = 0;
            $total_admins = 0;
            $total_active = 0;
            $total_inactive = 0;
            foreach ($users as $u) {
                if ($u->role === 'customer') $total_customers++;
                if ($u->role === 'admin') $total_admins++;
                if ($u->is_active) $total_active++;
                else $total_inactive++;
            }
        ?>

        <!-- SUMMARY STATISTICS -->
        <div class="report-stats-grid">
            <div class="stat-card">
                <div class="label">Total Terdaftar</div>
                <div class="value"><?= $total_users; ?> <span style="font-size:0.75rem; color:#64748B; font-weight:normal;">user</span></div>
            </div>
            <div class="stat-card">
                <div class="label">Total Pelanggan</div>
                <div class="value" style="color: #2563EB;"><?= $total_customers; ?></div>
            </div>
            <div class="stat-card">
                <div class="label">Akun Aktif</div>
                <div class="value" style="color: #059669;"><?= $total_active; ?></div>
            </div>
            <div class="stat-card">
                <div class="label">Administrator</div>
                <div class="value" style="color: #4F46E5;"><?= $total_admins; ?></div>
            </div>
        </div>

        <!-- USERS TABLE -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">No</th>
                    <th>Nama Lengkap</th>
                    <th>Alamat Email</th>
                    <th style="width: 120px;">No. Telepon</th>
                    <th style="width: 120px;">Kota Domisili</th>
                    <th style="width: 90px; text-align: center;">Peran (Role)</th>
                    <th style="width: 80px; text-align: center;">Status</th>
                    <th style="width: 100px; text-align: center;">Terdaftar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: #94A3B8;">
                            Belum ada data pengguna yang terdaftar di sistem.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($users as $user): ?>
                        <tr>
                            <td style="text-align: center; color: #64748B;"><?= $no++; ?></td>
                            <td>
                                <strong style="color: #0F172A;"><?= htmlspecialchars($user->name); ?></strong>
                            </td>
                            <td>
                                <span style="color: #334155;"><?= htmlspecialchars($user->email); ?></span>
                            </td>
                            <td>
                                <?= htmlspecialchars($user->phone ?: '-'); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(!empty($user->city) ? $user->city : '-'); ?>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-role badge-<?= $user->role; ?>">
                                    <?= ucfirst(htmlspecialchars($user->role)); ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-status <?= $user->is_active ? 'badge-active' : 'badge-inactive'; ?>">
                                    <?= $user->is_active ? 'Aktif' : 'Nonaktif'; ?>
                                </span>
                            </td>
                            <td style="text-align: center; color: #64748B; font-size: 0.78rem;">
                                <?= date('d/m/Y', strtotime($user->created_at)); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- FOOTER SIGNATURE -->
        <div class="report-footer">
            <div style="color: #64748B; font-size: 0.75rem;">
                * Dokumen data pengguna ini bersifat rahasia untuk keperluan audit internal.<br>
                * Dihasilkan oleh Modul Administrasi Pengguna <?= htmlspecialchars($store_name); ?>.
            </div>
            <div style="text-align: center; width: 220px;">
                <div style="font-size: 0.8rem; color: #475569; margin-bottom: 3.5rem;">
                    Divalidasi Oleh,<br><strong>Kepala Operasional</strong>
                </div>
                <div style="border-top: 1px dashed #94A3B8; padding-top: 0.25rem;">
                    <strong><?= $this->session->userdata('user_name') ?: 'Administrator'; ?></strong><br>
                    <span style="font-size: 0.75rem; color: #64748B;"><?= htmlspecialchars($store_name); ?> Management</span>
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
                const wb = XLSX.utils.table_to_book(table, { sheet: "Laporan_Pengguna" });
                XLSX.writeFile(wb, 'Laporan_Pengguna_' + store + '_<?= date('Ymd_His'); ?>.xlsx');
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
