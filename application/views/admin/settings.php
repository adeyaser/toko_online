<?php $this->load->view('templates/admin_header'); ?>
<div class="admin-header">
    <h1>Pengaturan Toko</h1>
</div>
<div class="admin-form">
    <form action="<?= base_url('admin/settings'); ?>" method="post" enctype="multipart/form-data">
        <h3 style="margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:8px;">
            <i data-feather="shopping-bag" style="width:20px;height:20px;color:var(--primary);"></i> Informasi Toko & Logo
        </h3>

        <!-- Logo Toko Dinamis -->
        <div class="form-group" style="background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <label class="form-label" style="font-weight: 700; color: #0F172A; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                <span>Logo Toko (Dinamis)</span>
                <span style="font-size: 0.8rem; color: #64748B; font-weight: normal;">Format: PNG, JPG, WebP, SVG • Maks 3MB</span>
            </label>
            <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
                <div style="text-align: center;">
                    <div style="width: 140px; height: 65px; border-radius: 8px; border: 1px solid #E2E8F0; background: #FFFFFF; display: flex; align-items: center; justify-content: center; padding: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <?php $current_logo = store_logo(); ?>
                        <?php if (!empty($current_logo)): ?>
                            <img src="<?= $current_logo; ?>" id="logoPreviewImg" alt="Logo Toko" style="max-height: 50px; max-width: 125px; object-fit: contain;">
                        <?php else: ?>
                            <div id="defaultLogoPlaceholder" style="display: flex; align-items: center; gap: 6px; font-weight: 800; font-family: var(--font-display); color: #0F172A; font-size: 1.05rem;">
                                <div style="width: 26px; height: 26px; border-radius: 6px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center;">
                                    <i data-feather="shopping-bag" style="width: 15px; height: 15px;"></i>
                                </div>
                                <span>Shop<span style="color: var(--primary);">Vista</span></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <small style="color: #64748B; font-size: 0.75rem; margin-top: 4px; display: block;">Pratinjau Saat Ini</small>
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <input type="file" name="store_logo" id="storeLogoInput" accept="image/*" class="form-control" style="padding: 7px 10px; font-size: 0.88rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-top: 8px;">
                        <small style="color: #64748B; font-size: 0.8rem;">
                            Logo akan otomatis tampil di Navbar Header, Mobile Bar, Footer, dan Sidebar Admin.
                        </small>
                        <?php if (!empty($current_logo)): ?>
                            <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; margin-left: auto; font-size: 0.82rem; color: #DC2626; font-weight: 600;">
                                <input type="checkbox" name="remove_logo" value="1" style="accent-color: #DC2626;"> Hapus & Pakai Default
                            </label>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Toko</label>
                <input type="text" name="store_name" class="form-control" value="<?= isset($settings['store_name']) ? $settings['store_name'] : ''; ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Tagline</label>
                <input type="text" name="store_tagline" class="form-control" value="<?= isset($settings['store_tagline']) ? $settings['store_tagline'] : ''; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="store_email" class="form-control" value="<?= isset($settings['store_email']) ? $settings['store_email'] : ''; ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Telepon</label>
                <input type="text" name="store_phone" class="form-control" value="<?= isset($settings['store_phone']) ? $settings['store_phone'] : ''; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="store_address" class="form-control" rows="2"><?= isset($settings['store_address']) ? $settings['store_address'] : ''; ?></textarea>
        </div>


        <h3 style="margin:2rem 0 1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border-light);">
            <i data-feather="truck" style="width:20px;height:20px;margin-right:8px;"></i> Pengiriman & Pembayaran
        </h3>
        <div class="form-group" style="margin-bottom: 1.25rem;">
            <label class="form-label" style="font-weight:700;color:#0F172A;">Mode Jasa Pengiriman di Toko *</label>
            <?php $cur_mode = isset($settings['shipping_mode']) ? $settings['shipping_mode'] : 'hybrid'; ?>
            <select name="shipping_mode" class="form-control" style="font-weight: 600;">
                <option value="hybrid" <?= $cur_mode == 'hybrid' ? 'selected' : ''; ?>>Gabungan (Hybrid: Kurir Manual Toko + REST API Luar) - Rekomendasi</option>
                <option value="manual" <?= $cur_mode == 'manual' ? 'selected' : ''; ?>>Manual Saja (Hanya gunakan daftar kurir yang diatur di menu Admin > Jasa Pengiriman)</option>
                <option value="api" <?= $cur_mode == 'api' ? 'selected' : ''; ?>>REST API Saja (Hanya gunakan tarif live dari API RajaOngkir / Ekspedisi Luar)</option>
            </select>
            <small style="color: #64748B; font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                Untuk menambah atau mengubah tarif kurir manual, buka menu <a href="<?= base_url('admin/couriers'); ?>" style="color: var(--primary); font-weight: 600;">Jasa Pengiriman</a>.
            </small>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Ongkos Kirim Standar (Rp)</label>
                <input type="number" name="shipping_cost" class="form-control" value="<?= isset($settings['shipping_cost']) ? $settings['shipping_cost'] : '15000'; ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Min. Belanja Gratis Ongkir (Rp)</label>
                <input type="number" name="free_shipping_min" class="form-control" value="<?= isset($settings['free_shipping_min']) ? $settings['free_shipping_min'] : '200000'; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" style="display:flex; justify-content:space-between; align-items:center;">
                    <span>API Key RajaOngkir (Komerce)</span>
                    <button type="button" id="btnTestRajaongkir" class="btn btn-sm btn-outline-primary" style="padding: 2px 10px; font-size: 0.75rem; border-radius: 4px; border: 1px solid var(--primary); color: var(--primary); background: transparent; cursor: pointer;">
                        ⚡ Uji Koneksi API
                    </button>
                </label>
                <input type="text" name="rajaongkir_api_key" id="rajaongkir_api_key" class="form-control" value="<?= isset($settings['rajaongkir_api_key']) ? $settings['rajaongkir_api_key'] : ''; ?>" placeholder="Masukkan API Key dari rajaongkir.com">
                <div id="rajaongkir_test_msg" style="margin-top: 6px; font-size: 0.8rem; display: none;"></div>
            </div>
            <div class="form-group">
                <label class="form-label">ID Kota/Kecamatan Asal Toko</label>
                <input type="text" name="rajaongkir_origin" class="form-control" value="<?= isset($settings['rajaongkir_origin']) ? $settings['rajaongkir_origin'] : '17601'; ?>" placeholder="Default: 17601 (Gambir, Jakarta Pusat)">
                <small style="color: #64748B; font-size: 0.75rem;">Default <code>17601</code> = Gambir, Jakarta Pusat.</small>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">X-RapidAPI-Key (Cek Resi & Ekspedisi)</label>
                <input type="text" name="rapidapi_key" class="form-control" value="<?= isset($settings['rapidapi_key']) ? htmlspecialchars($settings['rapidapi_key']) : ''; ?>" placeholder="API Key dari rapidapi.com">
            </div>
            <div class="form-group">
                <label class="form-label">RapidAPI Host</label>
                <input type="text" name="rapidapi_host" class="form-control" value="<?= isset($settings['rapidapi_host']) ? $settings['rapidapi_host'] : 'cek-resi-cek-ongkir.p.rapidapi.com'; ?>" placeholder="cek-resi-cek-ongkir.p.rapidapi.com">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Bank</label>
                <input type="text" name="bank_name" class="form-control" value="<?= isset($settings['bank_name']) ? $settings['bank_name'] : ''; ?>" placeholder="Contoh: Bank BCA / Mandiri / BRI">
            </div>
            <div class="form-group">
                <label class="form-label">No. Rekening</label>
                <input type="text" name="bank_account" class="form-control" value="<?= isset($settings['bank_account']) ? $settings['bank_account'] : ''; ?>" placeholder="Contoh: 123-456-7890">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Atas Nama (Pemilik Rekening)</label>
            <input type="text" name="bank_holder" class="form-control" value="<?= isset($settings['bank_holder']) ? $settings['bank_holder'] : ''; ?>" placeholder="Contoh: PT ShopVista Indonesia">
            <small style="color: #64748B; font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                ℹ️ Informasi rekening ini akan otomatis ditampilkan pada opsi <strong>Transfer Bank</strong> di halaman Checkout dan rincian pesanan pelanggan.
            </small>
        </div>

        <h3 style="margin:2rem 0 1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:rgba(37,211,102,0.15);color:#25D366;border-radius:8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                </svg>
            </span>
            <span>WhatsApp Chat Widget (Floating Button)</span>
        </h3>
        <div style="background:rgba(37,211,102,0.04);border:1px solid rgba(37,211,102,0.25);border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Status Widget WA Chat *</label>
                    <?php $wa_status = isset($settings['whatsapp_enabled']) ? $settings['whatsapp_enabled'] : '1'; ?>
                    <select name="whatsapp_enabled" class="form-control" style="font-weight:600;">
                        <option value="1" <?= $wa_status == '1' ? 'selected' : ''; ?>>🟢 Aktif (Tampilkan Tombol Floating WA di Web)</option>
                        <option value="0" <?= $wa_status == '0' ? 'selected' : ''; ?>>⚪ Nonaktif (Sembunyikan dari Tampilan Web)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Nomor WhatsApp Toko *</label>
                    <input type="text" name="whatsapp" class="form-control" value="<?= isset($settings['whatsapp']) ? $settings['whatsapp'] : ''; ?>" placeholder="Contoh: 081234567890 atau 6281234567890" style="font-weight:600;color:#0F172A;" required>
                    <small style="color:#64748B;font-size:0.8rem;margin-top:4px;display:block;">Otomatis disesuaikan ke format internasional (misal 08xx menjadi 628xx).</small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Petugas / Customer Service</label>
                    <input type="text" name="whatsapp_cs_name" class="form-control" value="<?= isset($settings['whatsapp_cs_name']) ? $settings['whatsapp_cs_name'] : 'Customer Service ShopVista'; ?>" placeholder="Customer Service ShopVista">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Petugas (Online Note)</label>
                    <input type="text" name="whatsapp_cs_status" class="form-control" value="<?= isset($settings['whatsapp_cs_status']) ? $settings['whatsapp_cs_status'] : 'Online • Siap Melayani'; ?>" placeholder="Online • Siap Melayani">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Posisi Floating Button di Layar</label>
                    <?php $wa_pos = isset($settings['whatsapp_position']) ? $settings['whatsapp_position'] : 'bottom-right'; ?>
                    <select name="whatsapp_position" class="form-control">
                        <option value="bottom-right" <?= $wa_pos == 'bottom-right' ? 'selected' : ''; ?>>Kanan Bawah (Default)</option>
                        <option value="bottom-left" <?= $wa_pos == 'bottom-left' ? 'selected' : ''; ?>>Kiri Bawah</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pesan Awal Otomatis (Template Text)</label>
                    <textarea name="whatsapp_message" class="form-control" rows="2" placeholder="Teks pembuka ketika pengunjung mengklik tombol chat"><?= isset($settings['whatsapp_message']) ? $settings['whatsapp_message'] : 'Halo ShopVista, saya tertarik untuk bertanya seputar produk/pesanan saya...'; ?></textarea>
                </div>
            </div>
        </div>

        <h3 style="margin:2rem 0 1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border-light);">
            <i data-feather="share-2" style="width:20px;height:20px;margin-right:8px;"></i> Sosial Media Lainnya
        </h3>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Instagram</label>
                <input type="text" name="instagram" class="form-control" value="<?= isset($settings['instagram']) ? $settings['instagram'] : ''; ?>" placeholder="@shopvista.id">
            </div>
            <div class="form-group">
                <label class="form-label">Facebook</label>
                <input type="text" name="facebook" class="form-control" value="<?= isset($settings['facebook']) ? $settings['facebook'] : ''; ?>" placeholder="shopvista.id">
            </div>
        </div>


        <h3 style="margin:2rem 0 1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:8px;">
            <i data-feather="mail" style="width:20px;height:20px;color:var(--primary);"></i> Konfigurasi Email SMTP
        </h3>
        <p style="color:#64748B;font-size:0.85rem;margin-top:-0.75rem;margin-bottom:1.25rem;">
            Digunakan untuk mengirim email notifikasi otomatis ke pelanggan terdaftar (leads) saat ada produk baru atau pembaruan katalog.
        </p>

        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">SMTP Host *</label>
                    <input type="text" name="smtp_host" id="smtp_host" class="form-control" value="<?= isset($settings['smtp_host']) ? $settings['smtp_host'] : 'smtp.gmail.com'; ?>" placeholder="smtp.gmail.com">
                    <span class="form-text">Contoh: <code>smtp.gmail.com</code> atau <code>mail.domainanda.com</code></span>
                </div>
                <div class="form-group">
                    <label class="form-label">SMTP Port *</label>
                    <input type="number" name="smtp_port" id="smtp_port" class="form-control" value="<?= isset($settings['smtp_port']) ? $settings['smtp_port'] : '587'; ?>" placeholder="587">
                    <span class="form-text">Port standar: <code>587</code> (TLS) atau <code>465</code> (SSL)</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Protokol Enkripsi (Crypto) *</label>
                    <?php $cur_crypto = isset($settings['smtp_crypto']) ? $settings['smtp_crypto'] : 'tls'; ?>
                    <select name="smtp_crypto" id="smtp_crypto" class="form-control">
                        <option value="tls" <?= $cur_crypto == 'tls' ? 'selected' : ''; ?>>TLS (Rekomendasi untuk Port 587)</option>
                        <option value="ssl" <?= $cur_crypto == 'ssl' ? 'selected' : ''; ?>>SSL (Untuk Port 465)</option>
                        <option value="none" <?= $cur_crypto == 'none' ? 'selected' : ''; ?>>Tanpa Enkripsi (None)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">SMTP Username / Email Akun</label>
                    <input type="text" name="smtp_user" id="smtp_user" class="form-control" value="<?= isset($settings['smtp_user']) ? $settings['smtp_user'] : ''; ?>" placeholder="alamat.email@gmail.com">
                    <span class="form-text">Alamat email akun pengirim</span>
                </div>
                <div class="form-group">
                    <label class="form-label">SMTP Password / App Password</label>
                    <div style="position: relative;">
                        <input type="password" name="smtp_pass" id="smtp_pass" class="form-control" value="<?= isset($settings['smtp_pass']) ? $settings['smtp_pass'] : ''; ?>" placeholder="Kata sandi aplikasi" style="padding-right: 40px;">
                        <button type="button" onclick="togglePasswordVisibility('smtp_pass', this)" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;color:#64748B;cursor:pointer;padding:4px;">
                            <i data-feather="eye" style="width:16px;height:16px;"></i>
                        </button>
                    </div>
                    <span class="form-text">Untuk Gmail, gunakan <strong>App Password (Sandi Aplikasi)</strong> 16 digit.</span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Pengirim (From Name)</label>
                    <input type="text" name="smtp_from_name" id="smtp_from_name" class="form-control" value="<?= isset($settings['smtp_from_name']) ? $settings['smtp_from_name'] : 'ShopVista Store'; ?>" placeholder="ShopVista Promo">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Pengirim (From Email)</label>
                    <input type="email" name="smtp_from_email" id="smtp_from_email" class="form-control" value="<?= isset($settings['smtp_from_email']) ? $settings['smtp_from_email'] : 'noreply@shopvista.com'; ?>" placeholder="noreply@shopvista.com">
                </div>
            </div>

            <!-- Test SMTP Tool -->
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #CBD5E1; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 8px; flex: 1; min-width: 260px;">
                    <input type="email" id="test_email_recipient" class="form-control" placeholder="Kirim email uji coba ke..." style="max-width: 280px; font-size: 0.85rem;" value="<?= $this->session->userdata('user_email') ?: 'admin@example.com'; ?>">
                    <button type="button" id="btnTestSmtp" class="btn btn-secondary" style="font-size: 0.85rem; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px;">
                        <i data-feather="send" style="width:14px;height:14px;"></i> Uji Kirim Email (Test SMTP)
                    </button>
                </div>
                <div id="smtp_test_msg" style="display:none;font-size:0.85rem;width:100%;padding:8px 12px;border-radius:8px;background:#F1F5F9;"></div>
            </div>
        </div>

        <!-- Cloudflare Turnstile Section -->
        <h3 style="margin:2.5rem 0 1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border-light);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(244,129,32,0.12);color:#F48120;border-radius:8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </span>
                <div>
                    <span style="font-weight:700;color:#0F172A;font-size:1.15rem;">Cloudflare Turnstile</span>
                    <span style="margin-left:8px;font-size:0.75rem;background:#FEF3C7;color:#B45309;padding:2px 8px;border-radius:10px;font-weight:600;">Bot Protection</span>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <label style="font-size:0.85rem;color:#475569;font-weight:600;margin:0;">Status Turnstile:</label>
                <?php $cur_ts_enabled = isset($settings['turnstile_enabled']) ? $settings['turnstile_enabled'] : '0'; ?>
                <select name="turnstile_enabled" id="turnstile_enabled" class="form-control" style="width:auto;display:inline-block;padding:5px 12px;font-size:0.85rem;font-weight:600;">
                    <option value="1" <?= $cur_ts_enabled == '1' ? 'selected' : ''; ?>>🟢 Aktif (Proteksi Turnstile Hidup)</option>
                    <option value="0" <?= $cur_ts_enabled == '0' ? 'selected' : ''; ?>>⚪ Nonaktif (Bypass)</option>
                </select>
            </div>
        </h3>

        <!-- Widget Mode -->
        <div style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div style="margin-bottom:1.25rem;">
                <h4 style="margin:0 0 4px 0;font-size:1.05rem;font-weight:700;color:#0F172A;">Widget Mode</h4>
                <p style="margin:0;font-size:0.875rem;color:#64748B;line-height:1.5;">Choose how Turnstile appears and behaves on your site. Each mode offers different levels of user interaction and security.</p>
            </div>

            <?php $cur_mode = isset($settings['turnstile_mode']) ? $settings['turnstile_mode'] : 'managed'; ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:1rem;margin-bottom:1.5rem;">
                <!-- Managed -->
                <label class="turnstile-mode-card <?= $cur_mode === 'managed' ? 'active' : ''; ?>" id="card_mode_managed">
                    <input type="radio" name="turnstile_mode" value="managed" <?= $cur_mode === 'managed' ? 'checked' : ''; ?> style="position:absolute;opacity:0;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <span style="font-weight:700;font-size:0.95rem;color:#0F172A;display:flex;align-items:center;gap:6px;">
                            <span class="mode-dot"></span> Managed
                        </span>
                        <span style="background:#E0E7FF;color:#4338CA;font-size:0.75rem;font-weight:700;padding:2px 8px;border-radius:12px;">Recommended</span>
                    </div>
                    <p style="margin:0;font-size:0.82rem;color:#64748B;line-height:1.45;">
                        Let Cloudflare decide the verification method based on traffic risk. Most visitors encounter a non-interactive or invisible check. High-risk visitors see additional challenges.
                    </p>
                </label>

                <!-- Non-interactive -->
                <label class="turnstile-mode-card <?= $cur_mode === 'non-interactive' ? 'active' : ''; ?>" id="card_mode_non_interactive">
                    <input type="radio" name="turnstile_mode" value="non-interactive" <?= $cur_mode === 'non-interactive' ? 'checked' : ''; ?> style="position:absolute;opacity:0;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <span style="font-weight:700;font-size:0.95rem;color:#0F172A;display:flex;align-items:center;gap:6px;">
                            <span class="mode-dot"></span> Non-interactive
                        </span>
                    </div>
                    <p style="margin:0;font-size:0.82rem;color:#64748B;line-height:1.45;">
                        Show visitors a loading spinner during verification. No interaction required. Use this to maintain consistent experience without prompting visitors.
                    </p>
                </label>

                <!-- Invisible -->
                <label class="turnstile-mode-card <?= $cur_mode === 'invisible' ? 'active' : ''; ?>" id="card_mode_invisible">
                    <input type="radio" name="turnstile_mode" value="invisible" <?= $cur_mode === 'invisible' ? 'checked' : ''; ?> style="position:absolute;opacity:0;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <span style="font-weight:700;font-size:0.95rem;color:#0F172A;display:flex;align-items:center;gap:6px;">
                            <span class="mode-dot"></span> Invisible
                        </span>
                    </div>
                    <p style="margin:0;font-size:0.82rem;color:#64748B;line-height:1.45;">
                        Verify visitors silently with no visual indication. Nothing appears on screen. Use this to eliminate visible security checks.
                    </p>
                </label>
            </div>

            <!-- Pre-clearance -->
            <div style="border-top:1px solid #F1F5F9;padding-top:1.25rem;margin-top:0.5rem;">
                <label style="display:flex;align-items:flex-start;gap:12px;cursor:pointer;">
                    <input type="checkbox" name="turnstile_preclearance" value="1" <?= (!empty($settings['turnstile_preclearance']) && $settings['turnstile_preclearance'] == '1') ? 'checked' : ''; ?> style="margin-top:3px;accent-color:#F48120;width:18px;height:18px;">
                    <div>
                        <div style="font-weight:700;color:#0F172A;font-size:0.92rem;margin-bottom:3px;">
                            Skip future security rule challenges for verified visitors
                        </div>
                        <div style="font-size:0.82rem;color:#64748B;line-height:1.5;">
                            Pre-clearance allows visitors who pass Turnstile to also bypass <a href="https://dash.cloudflare.com/?to=/10b5980204347a63f171faa60ba876bb/:zone/security/rules" target="_blank" rel="noopener noreferrer" style="color:#F48120;text-decoration:underline;">security rules</a> that have the same or a lower clearance level. This only works if your site is proxied through Cloudflare. Bypass duration is controlled by your domain's <a href="https://dash.cloudflare.com/?to=/10b5980204347a63f171faa60ba876bb/:zone/security/settings?tab=ddos-attacks" target="_blank" rel="noopener noreferrer" style="color:#F48120;text-decoration:underline;">Challenge Passage setting</a> (default: 30 minutes).
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Widget Keys -->
        <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
            <div style="margin-bottom:1.25rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                    <h4 style="margin:0 0 4px 0;font-size:1.05rem;font-weight:700;color:#0F172A;">Widget Keys</h4>
                    <button type="button" id="btnFillTestKeys" style="background:#FFF7ED;border:1px solid #FFEDD5;color:#C2410C;padding:4px 10px;border-radius:6px;font-size:0.78rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                        ⚡ Isi Kunci Percobaan (Cloudflare Test Keys)
                    </button>
                </div>
                <p style="margin:0;font-size:0.85rem;color:#64748B;line-height:1.5;">Use these keys to integrate Turnstile into your application. The site key is used in your client-side code, while the secret key must be kept secure on your server for validation.</p>
            </div>

            <div class="form-row">
                <!-- Site key -->
                <div class="form-group" style="flex:1;">
                    <label class="form-label" style="font-weight:700;">Site key</label>
                    <div style="display:flex;gap:8px;">
                        <input type="text" name="turnstile_site_key" id="turnstile_site_key" class="form-control" value="<?= isset($settings['turnstile_site_key']) ? htmlspecialchars($settings['turnstile_site_key']) : ''; ?>" placeholder="0x4AAAAAA..." style="font-family:monospace;font-size:0.88rem;">
                        <button type="button" class="btn btn-secondary btn-copy" data-target="turnstile_site_key" style="white-space:nowrap;display:inline-flex;align-items:center;gap:6px;font-size:0.82rem;padding:0 14px;">
                            <i data-feather="copy" style="width:14px;height:14px;"></i> <span>Click to copy</span>
                        </button>
                    </div>
                    <small style="color:#64748B;font-size:0.75rem;margin-top:4px;display:block;">Client-side Site Key untuk memunculkan widget Turnstile di form Login & Register.</small>
                </div>

                <!-- Secret key -->
                <div class="form-group" style="flex:1;">
                    <label class="form-label" style="font-weight:700;">Secret key</label>
                    <div style="display:flex;gap:8px;">
                        <div style="position:relative;flex:1;">
                            <input type="password" name="turnstile_secret_key" id="turnstile_secret_key" class="form-control" value="<?= isset($settings['turnstile_secret_key']) ? htmlspecialchars($settings['turnstile_secret_key']) : ''; ?>" placeholder="0x4AAAAAA..." style="font-family:monospace;font-size:0.88rem;padding-right:36px;">
                            <button type="button" onclick="togglePasswordVisibility('turnstile_secret_key', this)" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;color:#64748B;cursor:pointer;padding:4px;">
                                <i data-feather="eye" style="width:16px;height:16px;"></i>
                            </button>
                        </div>
                        <button type="button" class="btn btn-secondary btn-copy" data-target="turnstile_secret_key" style="white-space:nowrap;display:inline-flex;align-items:center;gap:6px;font-size:0.82rem;padding:0 14px;">
                            <i data-feather="copy" style="width:14px;height:14px;"></i> <span>Click to copy</span>
                        </button>
                    </div>
                    <small style="color:#64748B;font-size:0.75rem;margin-top:4px;display:block;">Server-side Secret Key untuk validasi API Turnstile.</small>
                </div>
            </div>

            <!-- Rotation Notice -->
            <div style="background:#EFF6FF;border:1px solid #DBEAFE;border-radius:8px;padding:10px 14px;margin-top:8px;display:flex;align-items:flex-start;gap:8px;">
                <i data-feather="info" style="width:16px;height:16px;color:#2563EB;flex-shrink:0;margin-top:2px;"></i>
                <div style="font-size:0.8rem;color:#1E40AF;line-height:1.45;">
                    Please note you can only rotate your secret key once every 2 hours. Your old secret key will be valid while the new secret key is being updated.
                </div>
            </div>

            <!-- Protected Areas List -->
            <div style="margin-top:1rem;padding-top:1rem;border-top:1px dashed #CBD5E1;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <span style="font-size:0.82rem;font-weight:700;color:#0F172A;display:inline-flex;align-items:center;gap:6px;">
                    <i data-feather="shield" style="width:15px;height:15px;color:#10B981;"></i> Area Terproteksi Otomatis:
                </span>
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    <span style="background:#F1F5F9;color:#334155;font-size:0.75rem;padding:3px 10px;border-radius:6px;font-weight:600;border:1px solid #E2E8F0;">🔑 Form Login</span>
                    <span style="background:#F1F5F9;color:#334155;font-size:0.75rem;padding:3px 10px;border-radius:6px;font-weight:600;border:1px solid #E2E8F0;">📝 Form Registrasi</span>
                    <span style="background:#EEF2FF;color:#4338CA;font-size:0.75rem;padding:3px 10px;border-radius:6px;font-weight:600;border:1px solid #C7D2FE;">📬 Info Promo & Penawaran Eksklusif (Newsletter)</span>
                </div>
            </div>
        </div>

        <h3 style="margin:2rem 0 1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border-light);">
            <i data-feather="search" style="width:20px;height:20px;margin-right:8px;"></i> SEO
        </h3>
        <div class="form-group">
            <label class="form-label">Meta Title</label>
            <input type="text" name="meta_title" class="form-control" value="<?= isset($settings['meta_title']) ? $settings['meta_title'] : ''; ?>">
        </div>
        <div class="form-group">
            <label class="form-label">Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="2"><?= isset($settings['meta_description']) ? $settings['meta_description'] : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Meta Keywords</label>
            <input type="text" name="meta_keywords" class="form-control" value="<?= isset($settings['meta_keywords']) ? $settings['meta_keywords'] : ''; ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <i data-feather="save" style="width:18px;height:18px;"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i data-feather="eye-off" style="width:16px;height:16px;"></i>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<i data-feather="eye" style="width:16px;height:16px;"></i>';
    }
    feather.replace();
}

document.getElementById('btnTestSmtp')?.addEventListener('click', function() {
    const recipient = document.getElementById('test_email_recipient').value.trim();
    const host = document.getElementById('smtp_host').value.trim();
    const port = document.getElementById('smtp_port').value.trim();
    const user = document.getElementById('smtp_user').value.trim();
    const pass = document.getElementById('smtp_pass').value.trim();
    const crypto = document.getElementById('smtp_crypto').value;
    const from_name = document.getElementById('smtp_from_name').value.trim();
    const from_email = document.getElementById('smtp_from_email').value.trim();

    const msgEl = document.getElementById('smtp_test_msg');
    const btn = this;

    if (!recipient) {
        msgEl.style.display = 'block';
        msgEl.style.background = '#FEE2E2';
        msgEl.style.color = '#DC2626';
        msgEl.innerHTML = '⚠️ Silakan masukkan email tujuan uji coba.';
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '⏳ Menguji SMTP...';
    msgEl.style.display = 'block';
    msgEl.style.background = '#F1F5F9';
    msgEl.style.color = '#64748B';
    msgEl.innerHTML = 'Sedang menghubungkan ke server SMTP dan mengirim email percobaan...';

    const formData = new FormData();
    formData.append('recipient', recipient);
    formData.append('smtp_host', host);
    formData.append('smtp_port', port);
    formData.append('smtp_user', user);
    formData.append('smtp_pass', pass);
    formData.append('smtp_crypto', crypto);
    formData.append('smtp_from_name', from_name);
    formData.append('smtp_from_email', from_email);

    fetch('<?= base_url("admin/settings/test_smtp"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i data-feather="send" style="width:14px;height:14px;"></i> Uji Kirim Email (Test SMTP)';
        feather.replace();
        if (data.status === 'success') {
            msgEl.style.background = '#ECFDF5';
            msgEl.style.color = '#059669';
            msgEl.innerHTML = '✅ <strong>Sukses!</strong> ' + data.message;
        } else {
            msgEl.style.background = '#FEF2F2';
            msgEl.style.color = '#DC2626';
            msgEl.innerHTML = '❌ <strong>Gagal:</strong> ' + data.message;
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i data-feather="send" style="width:14px;height:14px;"></i> Uji Kirim Email (Test SMTP)';
        feather.replace();
        msgEl.style.background = '#FEF2F2';
        msgEl.style.color = '#DC2626';
        msgEl.innerHTML = '❌ Terjadi kendala saat menghubungi server.';
    });
});

document.getElementById('btnTestRajaongkir')?.addEventListener('click', function() {
    const apiKey = document.getElementById('rajaongkir_api_key').value.trim();
    const msgEl = document.getElementById('rajaongkir_test_msg');
    const btn = this;

    if (!apiKey) {
        msgEl.style.display = 'block';
        msgEl.style.color = '#EF4444';
        msgEl.innerHTML = '⚠️ Silakan isi API Key RajaOngkir terlebih dahulu.';
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '⏳ Menghubungkan...';
    msgEl.style.display = 'block';
    msgEl.style.color = '#64748B';
    msgEl.innerHTML = 'Sedang menguji koneksi ke server RajaOngkir...';

    const formData = new FormData();
    formData.append('api_key', apiKey);

    fetch('<?= base_url("admin/settings/test_rajaongkir"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '⚡ Uji Koneksi API';
        if (data.status === 'success') {
            msgEl.style.color = '#10B981';
            msgEl.innerHTML = '✅ <strong>Sukses!</strong> ' + data.message;
        } else {
            msgEl.style.color = '#EF4444';
            msgEl.innerHTML = '❌ ' + data.message;
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '⚡ Uji Koneksi API';
        msgEl.style.color = '#EF4444';
        msgEl.innerHTML = '❌ Terjadi kendala saat menghubungi server.';
    });
});

// Turnstile Mode Card Selection
document.querySelectorAll('.turnstile-mode-card').forEach(card => {
    card.addEventListener('click', function(e) {
        document.querySelectorAll('.turnstile-mode-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        const radio = this.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
    });
});

// Copy to Clipboard Buttons
document.querySelectorAll('.btn-copy').forEach(btn => {
    btn.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        if (!input || !input.value.trim()) {
            alert('Nilai kunci masih kosong. Silakan isi terlebih dahulu.');
            return;
        }

        const textToCopy = input.value.trim();
        const originalContent = this.innerHTML;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(textToCopy).then(() => showCopiedFeedback(this, originalContent));
        } else {
            // Fallback
            input.focus();
            input.select();
            try {
                document.execCommand('copy');
                showCopiedFeedback(this, originalContent);
            } catch (err) {
                alert('Gagal menyalin otomatis. Silakan salin manual.');
            }
        }
    });
});

function showCopiedFeedback(btn, originalContent) {
    btn.innerHTML = '<i data-feather="check" style="width:14px;height:14px;color:#10B981;"></i> <span style="color:#10B981;font-weight:700;">Copied!</span>';
    feather.replace();
    setTimeout(() => {
        btn.innerHTML = originalContent;
        feather.replace();
    }, 2000);
}

// Fill Cloudflare Official Test Keys
document.getElementById('btnFillTestKeys')?.addEventListener('click', function() {
    if (confirm('Gunakan Kunci Percobaan Resmi Cloudflare (Always Passes) untuk pengujian lokal?')) {
        document.getElementById('turnstile_site_key').value = '1x00000000000000000000AA';
        document.getElementById('turnstile_secret_key').value = '1x0000000000000000000000000000000AA';
        document.getElementById('turnstile_enabled').value = '1';
        
        // Select Managed
        const managedCard = document.getElementById('card_mode_managed');
        if (managedCard) managedCard.click();
    }
});
</script>

<style>
.turnstile-mode-card {
    border: 2px solid #E2E8F0;
    border-radius: 12px;
    padding: 1.15rem;
    cursor: pointer;
    background: #FFFFFF;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    position: relative;
    user-select: none;
}
.turnstile-mode-card:hover {
    border-color: #CBD5E1;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(0,0,0,0.04);
}
.turnstile-mode-card.active {
    border-color: #F48120;
    background: #FFFDF9;
    box-shadow: 0 0 0 1px #F48120, 0 4px 16px rgba(244,129,32,0.12);
}
.mode-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid #CBD5E1;
    background: #FFFFFF;
    transition: all 0.2s;
}
.turnstile-mode-card.active .mode-dot {
    border-color: #F48120;
    background: #F48120;
    box-shadow: 0 0 0 2px rgba(244,129,32,0.25);
}
</style>

<?php $this->load->view('templates/admin_footer'); ?>
