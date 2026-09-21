<?php $this->load->view('templates/admin_header'); ?>

<div class="admin-header">
    <div>
        <h1 style="margin-bottom: 0.25rem;">Kelola Pengguna</h1>
        <div style="font-size: 0.85rem; color: #64748B;">Manajemen akun pelanggan, staf, dan hak akses pengguna</div>
    </div>
    <div class="header-actions" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
        <a href="<?= base_url('admin/users/export_excel'); ?>" class="btn btn-success" style="font-size: 0.85rem; background: #059669; color: white;">
            <i data-feather="download" style="width:15px;height:15px;"></i> Cetak Laporan (Excel)
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success" style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
    <i data-feather="check-circle" style="width: 18px; height: 18px; color: #059669;"></i>
    <span><?= $this->session->flashdata('success'); ?></span>
</div>
<?php endif; ?>

<div class="admin-table-wrapper" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <table id="usersTable" class="admin-table" style="width: 100%;">
        <thead>
            <tr>
                <th>Nama Pengguna</th>
                <th>Email</th>
                <th>Telepon</th>
                <th style="width: 100px;">Role</th>
                <th style="width: 100px;">Status</th>
                <th style="width: 130px;">Terdaftar</th>
                <th style="width: 110px; text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loaded dynamically via DataTables Server-Side -->
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url('admin/users/ajax_list'); ?>",
            type: "POST"
        },
        language: {
            search: "Cari Pengguna:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ pengguna",
            infoEmpty: "Menampilkan 0 pengguna",
            infoFiltered: "(disaring dari _MAX_ total pengguna)",
            zeroRecords: "Tidak ditemukan data pengguna yang cocok",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "&rarr;",
                previous: "&larr;"
            }
        },
        pageLength: 10,
        order: [[5, 'desc']],
        columnDefs: [
            { targets: [6], orderable: false, className: 'text-right' }
        ],
        drawCallback: function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    });
});
</script>

<?php $this->load->view('templates/admin_footer'); ?>
