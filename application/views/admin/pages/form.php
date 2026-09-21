<?php $this->load->view('templates/admin_header'); ?>

<div class="admin-header">
    <div>
        <h1 style="margin-bottom: 0.25rem;"><?= isset($page) ? 'Edit Halaman: ' . htmlspecialchars($page->title) : 'Tambah Halaman Baru'; ?></h1>
        <div style="font-size: 0.85rem; color: #64748B;">Atur konten artikel informasi publik, URL slug, dan optimasi SEO halaman</div>
    </div>
    <div class="header-actions">
        <a href="<?= base_url('admin/pages'); ?>" class="btn btn-secondary">
            <i data-feather="arrow-left" style="width:16px;height:16px;"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger" style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
    <i data-feather="alert-triangle" style="width: 18px; height: 18px; color: #DC2626;"></i>
    <span><?= $this->session->flashdata('error'); ?></span>
</div>
<?php endif; ?>

<div class="admin-form" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <form action="<?= isset($page) ? base_url('admin/pages/edit/' . $page->id) : base_url('admin/pages/create'); ?>" method="post">
        
        <h3 style="margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-light); font-size: 1.1rem; color: #0F172A; display: flex; align-items: center; gap: 8px;">
            <i data-feather="file-text" style="width: 18px; height: 18px; color: var(--primary);"></i> Informasi Utama Halaman
        </h3>

        <div class="form-row">
            <div class="form-group" style="flex: 2;">
                <label class="form-label" style="font-weight: 700; color: #0F172A;">Judul Halaman *</label>
                <input type="text" name="title" id="pageTitle" class="form-control" value="<?= isset($page) ? htmlspecialchars($page->title) : ''; ?>" placeholder="Contoh: Tentang Kami, Cara Belanja, dll." required style="font-size: 1rem; font-weight: 600;">
            </div>
            <div class="form-group" style="flex: 1.5;">
                <label class="form-label" style="font-weight: 700; color: #0F172A;">URL Slug *</label>
                <div style="display: flex; align-items: center;">
                    <span style="background: #F1F5F9; border: 1px solid #CBD5E1; border-right: none; padding: 9px 12px; border-radius: 8px 0 0 8px; color: #64748B; font-size: 0.88rem;">/</span>
                    <input type="text" name="slug" id="pageSlug" class="form-control" value="<?= isset($page) ? htmlspecialchars($page->slug) : ''; ?>" placeholder="tentang-kami" style="border-radius: 0 8px 8px 0; font-family: monospace; font-size: 0.9rem;" required>
                </div>
                <small style="color: #64748B; font-size: 0.78rem; margin-top: 4px; display: block;">Hanya huruf kecil, angka, dan tanda hubung (-).</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Ikon Halaman</label>
                <?php $current_icon = isset($page) ? $page->icon : 'file-text'; ?>
                <select name="icon" class="form-control">
                    <option value="info" <?= $current_icon == 'info' ? 'selected' : ''; ?>>ℹ️ info (Tentang Kami)</option>
                    <option value="shopping-bag" <?= $current_icon == 'shopping-bag' ? 'selected' : ''; ?>>🛍️ shopping-bag (Cara Belanja)</option>
                    <option value="shield" <?= $current_icon == 'shield' ? 'selected' : ''; ?>>🛡️ shield (Kebijakan Privasi / Keamanan)</option>
                    <option value="file-text" <?= $current_icon == 'file-text' ? 'selected' : ''; ?>>📄 file-text (Syarat & Ketentuan / Regulasi)</option>
                    <option value="help-circle" <?= $current_icon == 'help-circle' ? 'selected' : ''; ?>>❓ help-circle (FAQ / Bantuan)</option>
                    <option value="truck" <?= $current_icon == 'truck' ? 'selected' : ''; ?>>🚚 truck (Info Pengiriman)</option>
                    <option value="phone" <?= $current_icon == 'phone' ? 'selected' : ''; ?>>📞 phone (Kontak CS)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Urutan Prioritas (Sort Order)</label>
                <input type="number" name="sort_order" class="form-control" value="<?= isset($page) ? (int)$page->sort_order : 0; ?>" min="0">
                <small style="color: #64748B; font-size: 0.78rem; margin-top: 4px; display: block;">Angka lebih kecil tampil lebih dulu pada menu sidebar.</small>
            </div>

            <div class="form-group" style="display: flex; align-items: center; margin-top: 1.8rem;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                    <input type="checkbox" name="is_active" value="1" <?= (!isset($page) || $page->is_active) ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: var(--primary);">
                    <span style="font-weight: 600; color: #0F172A; font-size: 0.92rem;">Publikasikan / Aktifkan Halaman Ini</span>
                </label>
            </div>
        </div>

        <h3 style="margin: 2rem 0 1.25rem 0; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-light); font-size: 1.1rem; color: #0F172A; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i data-feather="edit-3" style="width: 18px; height: 18px; color: var(--primary);"></i> Isi Konten Halaman (TinyMCE WYSIWYG Editor) *
            </div>
            <span style="font-size: 0.8rem; color: #64748B; font-weight: normal;">
                Mendukung teks tebal, miring, judul, gambar, link, dan tabel secara visual
            </span>
        </h3>

        <div class="form-group">
            <textarea name="content" id="pageContent" class="form-control" rows="16" placeholder="Tuliskan isi halaman di sini..."><?= isset($page) ? htmlspecialchars($page->content) : ''; ?></textarea>
        </div>

        <h3 style="margin: 2rem 0 1.25rem 0; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-light); font-size: 1.1rem; color: #0F172A; display: flex; align-items: center; gap: 8px;">
            <i data-feather="search" style="width: 18px; height: 18px; color: var(--primary);"></i> Optimasi Mesin Pencari (SEO Opsional)
        </h3>

        <div class="form-group">
            <label class="form-label">Meta Title (Judul Tab Browser & Google)</label>
            <input type="text" name="meta_title" class="form-control" value="<?= isset($page) ? htmlspecialchars($page->meta_title) : ''; ?>" placeholder="Contoh: Tentang Kami - ShopVista Indonesia">
        </div>

        <div class="form-group">
            <label class="form-label">Meta Description (Ringkasan Cuplikan Mesin Pencari)</label>
            <textarea name="meta_description" class="form-control" rows="2" placeholder="Ringkasan singkat maksimal 160 karakter untuk ditampilkan di Google..."><?= isset($page) ? htmlspecialchars($page->meta_description) : ''; ?></textarea>
        </div>

        <div class="form-actions" style="margin-top: 2rem; display: flex; gap: 1rem; align-items: center;">
            <button type="submit" class="btn btn-primary btn-lg" style="padding: 12px 28px; font-weight: 700;">
                <i data-feather="save" style="width: 18px; height: 18px;"></i> <?= isset($page) ? 'Simpan Perubahan' : 'Terbitkan Halaman'; ?>
            </button>
            <a href="<?= base_url('admin/pages'); ?>" class="btn btn-secondary btn-lg">
                Batal
            </a>
            <?php if (isset($page)): ?>
                <a href="<?= base_url($page->slug); ?>" target="_blank" class="btn btn-outline btn-lg" style="margin-left: auto;">
                    <i data-feather="external-link" style="width: 16px; height: 16px;"></i> Pratinjau di Toko
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- TinyMCE 8 Official Cloud CDN -->
<script src="https://cdn.tiny.cloud/1/uq9m2x4nadwo1ao9z1zsu9nccp2isn74pmh4uurx6qw73lna/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<script>
// Initialize TinyMCE specifically on pageContent textarea
tinymce.init({
    selector: '#pageContent',
    height: 480,
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

// Auto-slug generator on create
<?php if (!isset($page)): ?>
document.getElementById('pageTitle').addEventListener('input', function() {
    var title = this.value;
    var slug = title.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
    document.getElementById('pageSlug').value = slug;
});
<?php endif; ?>

// Synchronize TinyMCE content before submitting form
document.querySelector('form').addEventListener('submit', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.triggerSave();
    }
});
</script>

<?php $this->load->view('templates/admin_footer'); ?>
