<?php $this->load->view('templates/admin_header'); ?>

<div class="admin-header">
    <div>
        <h1 style="margin-bottom: 0.25rem;">Kelola Kategori</h1>
        <div style="font-size: 0.85rem; color: #64748B;">Manajemen kategori produk dan pengelompokan katalog</div>
    </div>
    <div class="header-actions" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
        <a href="<?= base_url('admin/categories/export_excel'); ?>" class="btn btn-success" style="font-size: 0.85rem; background: #059669; color: white; display: inline-flex; align-items: center; gap: 6px;">
            <i data-feather="file-text" style="width:15px;height:15px;"></i>
            <span>Cetak Laporan (Excel)</span>
        </a>
        <a href="<?= base_url('admin/categories/create'); ?>" class="btn btn-primary" style="font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px;">
            <i data-feather="plus" style="width:16px;height:16px;"></i>
            <span>Tambah Kategori</span>
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success" style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
        <i data-feather="check-circle" style="width: 18px; height: 18px; color: #059669;"></i>
        <span><?= $this->session->flashdata('success'); ?></span>
    </div>
<?php endif; ?>

<div class="admin-table-wrapper"
    style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <table id="categoriesTable" class="admin-table" style="width: 100%;">
        <thead>
            <tr>
                <th>Nama Kategori</th>
                <th>Slug URL</th>
                <th style="width: 130px;">Ikon</th>
                <th style="width: 130px;">Total Produk</th>
                <th style="width: 110px;">Status</th>
                <th style="width: 100px; text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loaded dynamically via DataTables Server-Side -->
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#categoriesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url('admin/categories/ajax_list'); ?>",
                type: "POST"
            },
            language: {
                search: "Cari Kategori:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ kategori",
                infoEmpty: "Menampilkan 0 kategori",
                infoFiltered: "(disaring dari _MAX_ total kategori)",
                zeroRecords: "Tidak ditemukan kategori yang cocok",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                processing: '<div style="padding:8px;font-weight:700;color:var(--primary);">Memuat data kategori...</div>'
            },
            order: [[0, 'asc']],
            columns: [
                { orderable: true },
                { orderable: true },
                { orderable: false },
                { orderable: true },
                { orderable: true },
                { orderable: false, className: "text-right" }
            ],
            drawCallback: function () {
                if (typeof feather !== 'undefined') feather.replace();
            }
        });
    });
</script>

<?php $this->load->view('templates/admin_footer'); ?>