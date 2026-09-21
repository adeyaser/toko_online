<?php $this->load->view('templates/admin_header'); ?>
<div class="admin-header">
    <h1><?= $mode == 'edit' ? 'Edit Kategori' : 'Tambah Kategori'; ?></h1>
    <a href="<?= base_url('admin/categories'); ?>" class="btn btn-secondary">← Kembali</a>
</div>
<div class="admin-form">
    <form action="<?= $mode == 'edit' ? base_url('admin/categories/edit/' . $category->id) : base_url('admin/categories/create'); ?>" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Kategori *</label>
                <input type="text" name="name" class="form-control" value="<?= $mode == 'edit' ? $category->name : ''; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Icon (Feather Icons)</label>
                <input type="text" name="icon" class="form-control" value="<?= $mode == 'edit' ? $category->icon : 'package'; ?>" placeholder="contoh: package, heart, star">
                <span class="form-text">Lihat daftar icon di <a href="https://feathericons.com" target="_blank">feathericons.com</a></span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Upload Gambar Kategori</label>
                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewCatImage(this)">
                <span class="form-text">Format: JPG, PNG, WebP. Maks 2MB.</span>
            </div>
            <div class="form-group">
                <label class="form-label">Atau URL Gambar (Opsional)</label>
                <input type="url" name="image_url" class="form-control" value="<?= ($mode == 'edit' && strpos($category->image, 'http') === 0) ? $category->image : ''; ?>" placeholder="https://images.unsplash.com/...">
                <span class="form-text">URL gambar langsung bila tidak mengupload file.</span>
            </div>
        </div>

        <div class="form-group" style="display:flex;align-items:center;gap:15px;margin-bottom:1.25rem;">
            <div>
                <label class="form-label" style="margin-bottom:4px;">Preview Gambar:</label>
                <img id="catImgPreview" src="<?= $mode == 'edit' ? category_image($category->image, $category->slug) : 'https://placehold.co/100x100/F8FAFC/64748B?text=Preview'; ?>" style="width:70px;height:70px;border-radius:14px;object-fit:cover;border:2px solid #E2E8F0;box-shadow:0 2px 6px rgba(0,0,0,0.06);display:block;">
            </div>
            <script>
            function previewCatImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('catImgPreview').src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            </script>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3"><?= $mode == 'edit' ? $category->description : ''; ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="<?= $mode == 'edit' ? $category->sort_order : 0; ?>">
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;margin-top:2rem;">
                    <input type="checkbox" name="is_active" value="1" <?= ($mode == 'edit' && $category->is_active) || $mode == 'create' ? 'checked' : ''; ?> style="accent-color:var(--primary);">
                    <span>Aktif</span>
                </label>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $mode == 'edit' ? 'Simpan' : 'Tambah'; ?></button>
            <a href="<?= base_url('admin/categories'); ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
<?php $this->load->view('templates/admin_footer'); ?>
