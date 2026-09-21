<?php $this->load->view('templates/admin_header'); ?>

<div class="admin-header">
    <div>
        <h1><?= $mode == 'edit' ? 'Edit Jasa Pengiriman' : 'Tambah Jasa Pengiriman'; ?></h1>
        <div style="font-size: 0.85rem; color: #64748B;">Atur ekspedisi, layanan, estimasi, dan tarif ongkos kirim manual toko</div>
    </div>
    <a href="<?= base_url('admin/couriers'); ?>" class="btn btn-secondary">
        <i data-feather="arrow-left" style="width:16px;height:16px;"></i> Kembali
    </a>
</div>

<div class="admin-card" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 2rem; max-width: 800px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <form action="" method="post">
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#0F172A;">Nama Ekspedisi / Kurir *</label>
                <input type="text" name="courier_name" class="form-control" value="<?= $mode == 'edit' ? htmlspecialchars($courier->courier_name) : ''; ?>" placeholder="Contoh: J&T Express, JNE, SiCepat, Kurir Toko" required>
            </div>
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#0F172A;">Nama Layanan *</label>
                <input type="text" name="service_name" class="form-control" value="<?= $mode == 'edit' ? htmlspecialchars($courier->service_name) : ''; ?>" placeholder="Contoh: Standar / Reguler, Kilat, Next Day, Self Pickup" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#0F172A;">Biaya Ongkos Kirim (Rp) *</label>
                <input type="number" name="cost" class="form-control" value="<?= $mode == 'edit' ? (float)$courier->cost : '15000'; ?>" min="0" placeholder="Isi 0 untuk bebas biaya / gratis ongkir" required>
                <small style="color: #64748B; font-size: 0.78rem;">Ketik 0 jika layanan ini gratis atau pembeli ambil sendiri di toko.</small>
            </div>
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#0F172A;">Estimasi Tiba (ETD) *</label>
                <input type="text" name="etd" class="form-control" value="<?= $mode == 'edit' ? htmlspecialchars($courier->etd) : '2-3 Hari'; ?>" placeholder="Contoh: 1-2 Hari, 2-3 Hari, Hari Ini" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#0F172A;">Kode Unik Opsi (Opsional)</label>
                <input type="text" name="code" class="form-control" value="<?= $mode == 'edit' ? htmlspecialchars($courier->code) : ''; ?>" placeholder="Otomatis dibuat jika dikosongkan (misal: jnt_ez)">
            </div>
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#0F172A;">Badge Label Promo (Opsional)</label>
                <input type="text" name="badge" class="form-control" value="<?= $mode == 'edit' ? htmlspecialchars($courier->badge) : ''; ?>" placeholder="Contoh: Populer, Rekomendasi, Cepat Sampai">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#0F172A;">Ikon Layanan</label>
                <select name="icon" class="form-control">
                    <?php 
                    $icons = [
                        'truck' => 'Truk Pengiriman (truck)',
                        'zap' => 'Kilat / Petir (zap)',
                        'package' => 'Paket / Dus (package)',
                        'box' => 'Kotak Pengiriman (box)',
                        'send' => 'Kirim / Pesawat Kertas (send)',
                        'map-pin' => 'Lokasi / Ambil di Tempat (map-pin)'
                    ];
                    $selected_icon = ($mode == 'edit' && !empty($courier->icon)) ? $courier->icon : 'truck';
                    foreach ($icons as $val => $label): ?>
                    <option value="<?= $val; ?>" <?= $selected_icon == $val ? 'selected' : ''; ?>><?= $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#0F172A;">Nomor Urutan Tampilan</label>
                <input type="number" name="sort_order" class="form-control" value="<?= $mode == 'edit' ? (int)$courier->sort_order : '1'; ?>" min="0">
                <small style="color: #64748B; font-size: 0.78rem;">Urutan prioritas dari atas ke bawah pada halaman checkout.</small>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" style="font-weight:700;color:#0F172A;">Keterangan / Deskripsi Layanan</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Jelaskan ringkas keunggulan atau ketentuan kurir ini..."><?= $mode == 'edit' ? htmlspecialchars($courier->description) : ''; ?></textarea>
        </div>

        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 1.5rem;">
            <div style="margin-bottom: 0.75rem;">
                <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; font-size: 0.9rem; font-weight: 600; color: #0F172A;">
                    <input type="checkbox" name="is_free_eligible" value="1" <?= ($mode == 'create' || ($mode == 'edit' && $courier->is_free_eligible)) ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: var(--primary);">
                    <span>Ikut Program Gratis Ongkir Toko</span>
                </label>
                <div style="font-size: 0.8rem; color: #64748B; margin-left: 1.75rem;">
                    Jika dicentang, layanan ini akan otomatis menjadi <strong>Rp 0 (Gratis)</strong> bila total belanja pelanggan memenuhi batas minimum belanja gratis ongkir.
                </div>
            </div>

            <div>
                <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; font-size: 0.9rem; font-weight: 600; color: #0F172A;">
                    <input type="checkbox" name="is_active" value="1" <?= ($mode == 'create' || ($mode == 'edit' && $courier->is_active)) ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: var(--primary);">
                    <span>Aktifkan Opsi Pengiriman Ini</span>
                </label>
                <div style="font-size: 0.8rem; color: #64748B; margin-left: 1.75rem;">
                    Hanya layanan yang aktif yang akan ditampilkan sebagai opsi pilihan kurir di halaman checkout pelanggan.
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">
                <i data-feather="save" style="width:16px;height:16px;"></i>
                <span><?= $mode == 'edit' ? 'Simpan Perubahan' : 'Tambah Jasa Pengiriman'; ?></span>
            </button>
            <a href="<?= base_url('admin/couriers'); ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php $this->load->view('templates/admin_footer'); ?>
