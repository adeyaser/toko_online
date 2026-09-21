<div class="profile-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= base_url(); ?>">Beranda</a>
            <span class="separator">›</span>
            <span>Profil Saya</span>
        </div>

        <div class="profile-grid">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-sidebar-user">
                    <div class="profile-avatar">
                        <img src="<?= avatar_image($user->avatar); ?>" alt="<?= $user->name; ?>">
                    </div>
                    <div class="profile-meta">
                        <h3 class="profile-name"><?= $user->name; ?></h3>
                        <p class="profile-email"><?= $user->email; ?></p>
                    </div>
                </div>
                <div class="profile-menu">
                    <a href="<?= base_url('profil?tab=orders'); ?>" class="<?= $active_tab == 'orders' ? 'active' : ''; ?>">
                        <i data-feather="shopping-bag"></i> <span>Pesanan Saya</span>
                    </a>
                    <a href="<?= base_url('profil?tab=profile'); ?>" class="<?= $active_tab == 'profile' ? 'active' : ''; ?>">
                        <i data-feather="user"></i> <span>Edit Profil</span>
                    </a>
                    <?php if (is_admin()): ?>
                    <a href="<?= base_url('admin'); ?>">
                        <i data-feather="settings"></i> <span>Admin Panel</span>
                    </a>
                    <?php endif; ?>
                    <a href="<?= base_url('logout'); ?>" class="profile-menu-logout" style="color: #EF4444;">
                        <i data-feather="log-out"></i> <span>Keluar</span>
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="profile-content">
                <?php if ($active_tab == 'orders'): ?>
                <div class="profile-content-header" style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                    <h2 style="margin: 0; font-size: 1.45rem;">Pesanan Saya</h2>
                    <?php if (!empty($orders)): ?>
                        <span class="badge badge-primary" style="font-size: 0.8rem; padding: 4px 12px;"><?= count($orders); ?> Pesanan</span>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($orders)): ?>
                <!-- Desktop Table View -->
                <div class="admin-table-wrapper d-none-mobile">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><span class="order-number"><?= $order->order_number; ?></span></td>
                                <td><?= date('d M Y', strtotime($order->created_at)); ?></td>
                                <td style="font-weight: 700; color: #0F172A;"><?= rupiah($order->grand_total); ?></td>
                                <td><?= order_status_badge($order->status); ?></td>
                                <td style="text-align: right;">
                                    <a href="<?= base_url('profil/pesanan/' . $order->order_number); ?>" class="btn btn-sm btn-secondary">Detail</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Responsive Orders Cards -->
                <div class="orders-mobile-list d-block-mobile">
                    <?php foreach ($orders as $order): ?>
                    <div class="order-mobile-card">
                        <div class="order-mobile-top">
                            <div class="order-mobile-id-wrap">
                                <span class="order-mobile-tag-icon"><i data-feather="package" style="width:14px;height:14px;"></i></span>
                                <span class="order-mobile-number"><?= $order->order_number; ?></span>
                            </div>
                            <div class="order-mobile-status-badge">
                                <?= order_status_badge($order->status); ?>
                            </div>
                        </div>
                        <div class="order-mobile-middle">
                            <div class="order-mobile-row">
                                <span class="order-mobile-label">Tanggal Transaksi</span>
                                <span class="order-mobile-val"><?= date('d M Y, H:i', strtotime($order->created_at)); ?> WIB</span>
                            </div>
                            <div class="order-mobile-row">
                                <span class="order-mobile-label">Total Pembayaran</span>
                                <span class="order-mobile-val order-mobile-total"><?= rupiah($order->grand_total); ?></span>
                            </div>
                        </div>
                        <div class="order-mobile-bottom">
                            <a href="<?= base_url('profil/pesanan/' . $order->order_number); ?>" class="btn btn-outline-primary btn-sm btn-full" style="justify-content: center; gap: 6px; border-radius: 9px; font-weight: 600;">
                                <span>Lihat Detail Pesanan</span>
                                <i data-feather="chevron-right" style="width:15px;height:15px;"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line></svg>
                    </div>
                    <h3>Belum Ada Pesanan</h3>
                    <p>Anda belum membuat pesanan apapun. Mulai belanja sekarang!</p>
                    <a href="<?= base_url('katalog'); ?>" class="btn btn-primary">Mulai Belanja</a>
                </div>
                <?php endif; ?>

                <?php else: ?>
                <!-- Profile Edit -->
                <div class="profile-card profile-edit-card">
                    <div class="profile-card-header" style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <h2 style="margin: 0 0 0.35rem 0; font-size: 1.35rem; display: flex; align-items: center; gap: 8px;">
                            <i data-feather="user-check" style="width: 20px; height: 20px; color: var(--primary);"></i>
                            <span>Edit Profil & Alamat</span>
                        </h2>
                        <p style="margin: 0; color: #64748B; font-size: 0.88rem;">Kelola informasi profil, kontak, alamat pengiriman, dan keamanan akun Anda.</p>
                    </div>

                    <form action="<?= base_url('profil?tab=profile'); ?>" method="post" enctype="multipart/form-data" class="profile-form">
                        <!-- Section: Foto Profil -->
                        <div class="profile-form-section profile-avatar-section">
                            <h4 class="profile-form-section-title">
                                <i data-feather="camera" style="width: 15px; height: 15px;"></i>
                                <span>Foto Profil</span>
                            </h4>
                            <div class="avatar-upload-box">
                                <div class="avatar-preview-container">
                                    <img id="avatarPreviewImg" src="<?= avatar_image($user->avatar); ?>" alt="<?= htmlspecialchars($user->name); ?>" class="avatar-preview-img" data-original-src="<?= avatar_image($user->avatar); ?>">
                                    <label for="avatarInput" class="avatar-edit-overlay" title="Pilih Foto Baru">
                                        <i data-feather="camera" style="width: 16px; height: 16px;"></i>
                                    </label>
                                </div>
                                <div class="avatar-upload-details">
                                    <div class="avatar-actions">
                                        <label for="avatarInput" class="btn btn-outline-primary btn-sm avatar-select-btn">
                                            <i data-feather="upload" style="width: 14px; height: 14px;"></i>
                                            <span>Pilih Foto Baru</span>
                                        </label>
                                        <button type="button" id="avatarCancelBtn" class="btn btn-outline-secondary btn-sm avatar-cancel-btn" style="display: none;">
                                            <i data-feather="x" style="width: 14px; height: 14px;"></i>
                                            <span>Batal</span>
                                        </button>
                                        <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/jpg,image/webp" style="display: none;">
                                    </div>
                                    <div id="avatarFileName" class="avatar-file-name" style="display: none;"></div>
                                    <p class="avatar-hint">Format yang didukung: JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Data Pribadi -->
                        <div class="profile-form-section">
                            <h4 class="profile-form-section-title">
                                <i data-feather="user" style="width: 15px; height: 15px;"></i>
                                <span>Informasi Pribadi</span>
                            </h4>
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user->name); ?>" placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="form-row form-row-2">
                                <div class="form-group">
                                    <label class="form-label">
                                        <span>Email</span>
                                        <span class="badge" style="font-size: 0.68rem; font-weight: 600; background: #F1F5F9; color: #64748B; padding: 2px 6px; border-radius: 4px; margin-left: 6px;">Tetap</span>
                                    </label>
                                    <input type="email" class="form-control" value="<?= htmlspecialchars($user->email); ?>" disabled style="background: #F8FAFC; color: #64748B; cursor: not-allowed;">
                                    <span class="form-text" style="font-size: 0.76rem; color: #94A3B8; margin-top: 3px; display: block;">Email digunakan untuk login & verifikasi pesanan</span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No. Telepon / WhatsApp</label>
                                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user->phone); ?>" placeholder="Contoh: 081234567890">
                                </div>
                            </div>
                        </div>

                        <!-- Section: Alamat Pengiriman -->
                        <div class="profile-form-section" style="margin-top: 1.5rem;">
                            <h4 class="profile-form-section-title">
                                <i data-feather="map-pin" style="width: 15px; height: 15px;"></i>
                                <span>Alamat Pengiriman Utama</span>
                            </h4>
                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan..."><?= htmlspecialchars($user->address); ?></textarea>
                            </div>
                            <div class="form-row form-row-3">
                                <div class="form-group">
                                    <label class="form-label">Kota / Kabupaten</label>
                                    <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user->city); ?>" placeholder="Contoh: Jakarta Selatan">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Provinsi</label>
                                    <input type="text" name="province" class="form-control" value="<?= htmlspecialchars($user->province); ?>" placeholder="Contoh: DKI Jakarta">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kode Pos</label>
                                    <input type="text" name="postal_code" class="form-control" value="<?= htmlspecialchars($user->postal_code); ?>" placeholder="Contoh: 12345">
                                </div>
                            </div>
                        </div>

                        <!-- Section: Keamanan Akun -->
                        <div class="profile-form-section" style="margin-top: 1.5rem;">
                            <h4 class="profile-form-section-title">
                                <i data-feather="lock" style="width: 15px; height: 15px;"></i>
                                <span>Keamanan & Password</span>
                            </h4>
                            <div class="form-group">
                                <label class="form-label">Password Baru (opsional)</label>
                                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password" minlength="6">
                                <span class="form-text" style="font-size: 0.76rem; color: #94A3B8; margin-top: 3px; display: block;">Minimal 6 karakter kombinasi huruf dan angka</span>
                            </div>
                        </div>

                        <div class="profile-form-actions" style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--border-color);">
                            <button type="submit" class="btn btn-primary btn-lg btn-save-profile">
                                <i data-feather="check-circle" style="width: 18px; height: 18px;"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var avatarInput = document.getElementById('avatarInput');
    var avatarPreview = document.getElementById('avatarPreviewImg');
    var avatarCancelBtn = document.getElementById('avatarCancelBtn');
    var avatarFileName = document.getElementById('avatarFileName');
    
    if (avatarInput && avatarPreview) {
        var defaultSrc = avatarPreview.getAttribute('data-original-src');

        avatarInput.addEventListener('change', function(e) {
            var file = e.target.files && e.target.files[0];
            if (!file) return;

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                if (window.ShopVista && typeof ShopVista.showToast === 'function') {
                    ShopVista.showToast('error', 'Ukuran foto terlalu besar! Maksimal 2MB.');
                } else {
                    alert('Ukuran foto terlalu besar! Maksimal 2MB.');
                }
                avatarInput.value = '';
                return;
            }

            // Validate file type
            var validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (validTypes.indexOf(file.type.toLowerCase()) === -1) {
                if (window.ShopVista && typeof ShopVista.showToast === 'function') {
                    ShopVista.showToast('error', 'Format file tidak didukung! Gunakan JPG, PNG, atau WEBP.');
                } else {
                    alert('Format file tidak didukung! Gunakan JPG, PNG, atau WEBP.');
                }
                avatarInput.value = '';
                return;
            }

            // Preview image
            var reader = new FileReader();
            reader.onload = function(event) {
                avatarPreview.src = event.target.result;
            };
            reader.readAsDataURL(file);

            // Display file info and cancel button
            if (avatarFileName) {
                var sizeInKB = Math.round(file.size / 1024);
                avatarFileName.textContent = file.name + ' (' + sizeInKB + ' KB)';
                avatarFileName.style.display = 'block';
            }
            if (avatarCancelBtn) {
                avatarCancelBtn.style.display = 'inline-flex';
                if (typeof feather !== 'undefined') feather.replace();
            }
        });

        if (avatarCancelBtn) {
            avatarCancelBtn.addEventListener('click', function() {
                avatarInput.value = '';
                avatarPreview.src = defaultSrc;
                avatarCancelBtn.style.display = 'none';
                if (avatarFileName) {
                    avatarFileName.style.display = 'none';
                    avatarFileName.textContent = '';
                }
            });
        }
    }
});
</script>
