<?php $this->load->view('templates/admin_header'); ?>
<div class="admin-header">
    <h1><?= $mode == 'edit' ? 'Edit Banner' : 'Tambah Banner'; ?></h1>
    <a href="<?= base_url('admin/banners'); ?>" class="btn btn-secondary">← Kembali</a>
</div>
<div class="admin-form">
    <form action="<?= $mode == 'edit' ? base_url('admin/banners/edit/' . $banner->id) : base_url('admin/banners/create'); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" value="<?= $mode == 'edit' ? $banner->title : ''; ?>">
        </div>
        <div class="form-group">
            <label class="form-label">Subtitle</label>
            <textarea name="subtitle" class="form-control" rows="2"><?= $mode == 'edit' ? $banner->subtitle : ''; ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Link</label>
                <input type="text" name="link" class="form-control" value="<?= $mode == 'edit' ? $banner->link : ''; ?>" placeholder="/katalog">
            </div>
            <div class="form-group">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="<?= $mode == 'edit' ? $banner->sort_order : 0; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Gambar Banner</label>
            <div class="image-upload">
                <input type="file" name="image" accept="image/*">
                <div class="upload-icon"><i data-feather="upload-cloud" style="width:40px;height:40px;"></i></div>
                <div class="upload-text">Upload gambar banner</div>
                <div class="upload-hint">Rekomendasi: 1200x500px • JPG/PNG/WebP • Maks 5MB</div>
                <?php if ($mode == 'edit' && $banner->image): ?>
                <img src="<?= banner_image($banner->image); ?>" class="image-preview" alt="" style="display:block;margin:1rem auto 0;max-width:100%;">
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group">
            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                <input type="checkbox" name="is_active" value="1" <?= ($mode == 'edit' && $banner->is_active) || $mode == 'create' ? 'checked' : ''; ?> style="accent-color:var(--primary);">
                <span>Aktif</span>
            </label>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $mode == 'edit' ? 'Simpan' : 'Tambah'; ?></button>
            <a href="<?= base_url('admin/banners'); ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
<?php $this->load->view('templates/admin_footer'); ?>
