<?php $this->load->view('templates/admin_header'); ?>

<div class="admin-header">
    <div>
        <h1>Dashboard</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Selamat datang, <?= $this->session->userdata('user_name'); ?>! 👋</p>
    </div>
    <div class="header-actions">
        <a href="<?= base_url('admin/products/create'); ?>" class="btn btn-primary">
            <i data-feather="plus" style="width:18px;height:18px;"></i> Tambah Produk
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid stagger-children">
    <div class="stat-card">
        <div class="stat-icon"><i data-feather="dollar-sign"></i></div>
        <div class="stat-content">
            <div class="stat-value"><?= rupiah($total_revenue); ?></div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i data-feather="shopping-cart"></i></div>
        <div class="stat-content">
            <div class="stat-value"><?= number_format($total_orders); ?></div>
            <div class="stat-label">Total Pesanan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i data-feather="package"></i></div>
        <div class="stat-content">
            <div class="stat-value"><?= number_format($total_products); ?></div>
            <div class="stat-label">Total Produk</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i data-feather="users"></i></div>
        <div class="stat-content">
            <div class="stat-value"><?= number_format($total_customers); ?></div>
            <div class="stat-label">Total Pelanggan</div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Revenue Chart -->
    <div class="chart-card">
        <div class="chart-card-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
            <h3 style="margin: 0; font-size: 1.05rem;">Pendapatan 6 Bulan Terakhir</h3>
            <span class="badge badge-info" style="font-size: 0.72rem; padding: 4px 10px;">Hanya Pesanan Terkonfirmasi</span>
        </div>
        <?php
        $max_revenue = 1;
        foreach ($monthly_revenue as $m) {
            if ($m['total'] > $max_revenue) $max_revenue = $m['total'];
        }
        ?>
        <div class="chart-scroll-wrapper" style="overflow-x: auto; -webkit-overflow-scrolling: touch; padding: 34px 16px 10px 16px;">
            <div class="simple-chart" style="margin-bottom: 28px; height: 185px; min-width: 320px;">
                <?php foreach ($monthly_revenue as $m): ?>
                <?php $height_pct = $max_revenue > 0 ? ($m['total'] > 0 ? max(12, round(($m['total'] / $max_revenue) * 82)) : 5) : 5; ?>
                <div class="bar" style="height: <?= $height_pct; ?>%; transition: height 0.8s ease;" title="<?= $m['month']; ?>: <?= rupiah($m['total']); ?>">
                    <span class="bar-value"><?= $m['total'] > 0 ? rupiah($m['total']) : '-'; ?></span>
                    <span class="bar-label"><?= $m['month']; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div style="font-size: 0.78rem; color: #64748B; text-align: center; border-top: 1px solid #F1F5F9; padding-top: 10px;">
            <span style="display: inline-flex; align-items: center; gap: 5px;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--primary);"></span>
                Pendapatan dihitung dari pesanan terkonfirmasi/lunas (Diproses, Dikirim, Selesai)
            </span>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="chart-card">
        <div class="chart-card-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
            <h3 style="margin: 0; font-size: 1.05rem;">Pesanan Terbaru</h3>
            <a href="<?= base_url('admin/orders'); ?>" style="font-size: 0.8rem; font-weight: 600; color: var(--primary); text-decoration: none;">Lihat Semua &rarr;</a>
        </div>
        <div class="recent-list">
            <?php if (!empty($recent_orders)): ?>
                <?php foreach ($recent_orders as $order): ?>
                <a href="<?= base_url('admin/orders/detail/' . $order->id); ?>" class="recent-item" style="text-decoration: none;">
                    <div class="recent-icon">
                        <i data-feather="shopping-bag" style="width:18px;height:18px;"></i>
                    </div>
                    <div class="recent-info">
                        <div class="recent-title"><?= htmlspecialchars($order->user_name); ?></div>
                        <div class="recent-subtitle"><?= $order->order_number; ?> • <?= time_ago($order->created_at); ?></div>
                    </div>
                    <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 3px; flex-shrink: 0;">
                        <div class="recent-amount"><?= rupiah($order->grand_total); ?></div>
                        <div><?= order_status_badge($order->status); ?></div>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--text-muted); text-align: center; padding: 2rem;">Belum ada pesanan</p>
            <?php endif; ?>
        </div>
        
        <?php if ($pending_orders > 0): ?>
        <a href="<?= base_url('admin/orders?status=pending'); ?>" style="text-decoration: none; display: block; background: rgba(255,137,6,0.1); border: 1px solid rgba(255,137,6,0.25); border-radius: 10px; padding: 0.75rem 1rem; margin-top: 1rem; font-size: 0.82rem; font-weight: 600; color: #D97706; transition: background 0.2s;">
            ⚠️ <?= $pending_orders; ?> pesanan menunggu konfirmasi pembayaran &rarr;
        </a>
        <?php endif; ?>
    </div>
</div>

<?php $this->load->view('templates/admin_footer'); ?>
