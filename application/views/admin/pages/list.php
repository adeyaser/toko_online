<?php $this->load->view('templates/admin_header'); ?>

<div class="admin-header">
    <div>
        <h1 style="margin-bottom: 0.25rem;">Kelola Halaman Statis & Informasi</h1>
        <div style="font-size: 0.85rem; color: #64748B;">Manajemen konten halaman publik: Tentang Kami, Cara Belanja, Kebijakan Privasi, dan Syarat & Ketentuan</div>
    </div>
    <div class="header-actions" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
        <a href="<?= base_url('admin/pages/export_excel'); ?>" class="btn btn-success" style="font-size: 0.85rem; background: #059669; color: white;">
            <i data-feather="file-text" style="width:15px;height:15px;"></i> Cetak Laporan (Excel)
        </a>
        <a href="<?= base_url('admin/pages/create'); ?>" class="btn btn-primary" style="font-size: 0.85rem;">
            <i data-feather="plus" style="width:16px;height:16px;"></i> Tambah Halaman Baru
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success" style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
    <i data-feather="check-circle" style="width: 18px; height: 18px; color: #059669;"></i>
    <span><?= $this->session->flashdata('success'); ?></span>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger" style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
    <i data-feather="alert-triangle" style="width: 18px; height: 18px; color: #DC2626;"></i>
    <span><?= $this->session->flashdata('error'); ?></span>
</div>
<?php endif; ?>

<div class="admin-table-wrapper" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <table id="pagesTable" class="admin-table" style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 50px;">Urut</th>
                <th>Judul & Ringkasan</th>
                <th style="width: 160px;">URL / Slug</th>
                <th style="width: 70px; text-align: center;">Ikon</th>
                <th style="width: 100px;">Status</th>
                <th style="width: 130px;">Update Terakhir</th>
                <th style="width: 130px; text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loaded dynamically via DataTables Server-Side -->
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#pagesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url('admin/pages/ajax_list'); ?>",
            type: "POST"
        },
        order: [[0, "asc"]],
        columns: [
            { data: 0, className: "text-center", width: "50px" },
            { data: 1 },
            { data: 2 },
            { data: 3, className: "text-center", orderable: false },
            { data: 4, className: "text-center" },
            { data: 5 },
            { data: 6, className: "text-right", orderable: false }
        ],
        language: {
            search: "Cari Halaman:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Tidak ada halaman yang cocok",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ total halaman",
            infoEmpty: "Menampilkan 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        drawCallback: function() {
            if (typeof feather !== 'undefined') feather.replace();
        }
    });
});
</script>

<?php $this->load->view('templates/admin_footer'); ?>
