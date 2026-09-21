<?php $this->load->view('templates/admin_header'); ?>
<div class="admin-header">
    <h1>Kelola Banner</h1>
    <a href="<?= base_url('admin/banners/create'); ?>" class="btn btn-primary"><i data-feather="plus" style="width:18px;height:18px;"></i> Tambah Banner</a>
</div>
<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead><tr><th>Preview</th><th>Judul</th><th>Subtitle</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php if (!empty($banners)): foreach ($banners as $b): ?>
            <tr>
                <td><img src="<?= banner_image($b->image); ?>" alt="" style="width:120px;height:50px;border-radius:6px;object-fit:cover;"></td>
                <td style="font-weight:600;color:var(--text-white);"><?= $b->title ?: '-'; ?></td>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= $b->subtitle ?: '-'; ?></td>
                <td><?= $b->sort_order; ?></td>
                <td><?= $b->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>'; ?></td>
                <td>
                    <div class="actions-cell">
                        <a href="<?= base_url('admin/banners/edit/' . $b->id); ?>" class="action-btn"><i data-feather="edit-2" style="width:14px;height:14px;"></i></a>
                        <a href="<?= base_url('admin/banners/delete/' . $b->id); ?>" class="action-btn delete" onclick="return confirm('Hapus banner ini?')"><i data-feather="trash-2" style="width:14px;height:14px;"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--text-muted);">Belum ada banner</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $this->load->view('templates/admin_footer'); ?>
