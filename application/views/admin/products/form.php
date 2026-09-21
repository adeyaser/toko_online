<?php $this->load->view('templates/admin_header'); ?>

<div class="admin-header">
    <h1><?= $mode == 'edit' ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h1>
    <a href="<?= base_url('admin/products'); ?>" class="btn btn-secondary">← Kembali</a>
</div>

<div class="admin-form">
    <form action="<?= $mode == 'edit' ? base_url('admin/products/edit/' . $product->id) : base_url('admin/products/create'); ?>" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Produk *</label>
                <input type="text" name="name" class="form-control" value="<?= $mode == 'edit' ? $product->name : set_value('name'); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Kategori *</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->id; ?>" <?= ($mode == 'edit' && $product->category_id == $cat->id) ? 'selected' : ''; ?>><?= $cat->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Harga Normal (Rp) *</label>
                <input type="number" name="price" class="form-control" value="<?= $mode == 'edit' ? $product->price : ''; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Harga Diskon (Rp)</label>
                <input type="number" name="sale_price" class="form-control" value="<?= $mode == 'edit' ? $product->sale_price : ''; ?>" placeholder="Kosongkan jika tidak ada diskon">
            </div>
        </div>

        <div class="form-row form-row-3">
            <div class="form-group">
                <label class="form-label">Stok *</label>
                <input type="number" name="stock" class="form-control" value="<?= $mode == 'edit' ? $product->stock : '0'; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Berat (gram)</label>
                <input type="number" name="weight" class="form-control" value="<?= $mode == 'edit' ? $product->weight : '0'; ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Kota / Lokasi Pengiriman</label>
                <input type="text" name="location" class="form-control" value="<?= $mode == 'edit' ? (isset($product->location) ? $product->location : 'Jakarta Pusat') : 'Jakarta Pusat'; ?>" placeholder="Contoh: Jakarta Pusat, Bandung">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi Singkat</label>
            <input type="text" name="short_desc" class="form-control" value="<?= $mode == 'edit' ? $product->short_desc : ''; ?>" maxlength="500" placeholder="Ringkasan produk (maks 500 karakter)">
        </div>

        <div class="form-group">
            <label class="form-label" style="display:flex;align-items:center;justify-content:space-between;">
                <span>Deskripsi Lengkap (TinyMCE WYSIWYG Editor)</span>
                <span style="font-size:0.8rem;color:#64748B;font-weight:normal;">Mendukung teks berformat, poin, spesifikasi, dan tabel</span>
            </label>
            <textarea name="description" id="productDescription" class="form-control" rows="8"><?= $mode == 'edit' ? htmlspecialchars($product->description) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Gambar Produk</label>
            <div class="image-upload">
                <input type="file" name="image" accept="image/*">
                <div class="upload-icon"><i data-feather="upload-cloud" style="width:40px;height:40px;"></i></div>
                <div class="upload-text">Klik atau drag gambar ke sini</div>
                <div class="upload-hint">JPG, PNG, WebP • Maks 2MB</div>
                <?php if ($mode == 'edit' && $product->image): ?>
                <img src="<?= product_image($product->image); ?>" class="image-preview" alt="Current image" style="display: block; margin: 1rem auto 0;">
                <?php else: ?>
                <img src="" class="image-preview" alt="" style="display: none; margin: 1rem auto 0;">
                <?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_featured" value="1" <?= ($mode == 'edit' && $product->is_featured) ? 'checked' : ''; ?> style="accent-color: var(--primary);">
                    <span class="form-label" style="margin: 0;">Produk Unggulan</span>
                </label>
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" <?= ($mode == 'edit' && $product->is_active) || $mode == 'create' ? 'checked' : ''; ?> style="accent-color: var(--primary);">
                    <span class="form-label" style="margin: 0;">Aktif</span>
                </label>
            </div>
        </div>

        <?php $active_leads_count = $this->db->where('is_active', 1)->count_all_results('leads'); ?>
        <div style="background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: 10px; padding: 12px 16px; margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer; margin: 0;">
                <input type="checkbox" name="notify_leads" value="1" checked style="accent-color: var(--primary); width: 18px; height: 18px; margin-top: 2px;">
                <div>
                    <div style="font-weight: 700; color: #1E1B4B; font-size: 0.92rem; display: flex; align-items: center; gap: 8px;">
                        <span>Kirim Notifikasi Email ke Pelanggan Terdaftar (Leads)</span>
                        <span style="background: #4F46E5; color: white; padding: 1px 8px; border-radius: 12px; font-size: 0.72rem; font-weight: 600;">
                            <?= $active_leads_count; ?> Kontak Aktif
                        </span>
                    </div>
                    <div style="font-size: 0.8rem; color: #4338CA; margin-top: 3px;">
                        Kirim email pemberitahuan otomatis seputar produk ini ke seluruh email di tabel leads saat tombol simpan ditekan.
                    </div>
                </div>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $mode == 'edit' ? 'Simpan Perubahan' : 'Tambah Produk'; ?></button>
            <a href="<?= base_url('admin/products'); ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<!-- TinyMCE 8 Official Cloud CDN -->
<script src="https://cdn.tiny.cloud/1/uq9m2x4nadwo1ao9z1zsu9nccp2isn74pmh4uurx6qw73lna/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<script>
// Initialize TinyMCE on productDescription textarea
tinymce.init({
    selector: '#productDescription',
    height: 420,
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | code removeformat',
    branding: false,
    promotion: false,
    content_style: 'body { font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif; font-size: 15px; line-height: 1.6; color: #1E293B; }',
    setup: function (editor) {
        editor.on('change keyup NodeChange', function () {
            editor.save();
        });
    }
});

// Synchronize TinyMCE content before submitting form
document.querySelector('form').addEventListener('submit', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.triggerSave();
    }
});
</script>

<?php $this->load->view('templates/admin_footer'); ?>

