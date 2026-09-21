<?php $this->load->view('templates/admin_header'); ?>

<div class="admin-header">
    <div>
        <h1 style="margin-bottom: 0.25rem;">Kelola Jasa Pengiriman</h1>
        <div style="font-size: 0.85rem; color: #64748B;">Manajemen kurir pengiriman manual, tarif ongkos kirim, dan
            integrasi API</div>
    </div>
    <div class="header-actions" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
        <!-- <a href="<?= base_url('admin/couriers/export_excel'); ?>" class="btn btn-success" style="font-size: 0.85rem; background: #059669; color: white;">
            <i data-feather="download" style="width:15px;height:15px;"></i> Cetak Laporan (Excel)
        </a> -->
        <a href="<?= base_url('admin/couriers/create'); ?>" class="btn btn-primary" style="font-size: 0.85rem;">
            <i data-feather="plus" style="width:16px;height:16px;"></i> Tambah Jasa Pengiriman
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

<div class="admin-table-wrapper"
    style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <table id="couriersTable" class="admin-table" style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 50px;">Urut</th>
                <th>Nama Ekspedisi</th>
                <th>Layanan & Keterangan</th>
                <th style="width: 130px;">Tarif Ongkir</th>
                <th style="width: 110px;">Estimasi</th>
                <th style="width: 110px;">Bebas Ongkir</th>
                <th style="width: 90px;">Status</th>
                <th style="width: 120px; text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loaded dynamically via DataTables Server-Side -->
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#couriersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url('admin/couriers/ajax_list'); ?>",
                type: "POST"
            },
            language: {
                search: "Cari Jasa Pengiriman:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ opsi",
                infoEmpty: "Menampilkan 0 opsi",
                infoFiltered: "(disaring dari _MAX_ total opsi)",
                zeroRecords: "Tidak ditemukan jasa pengiriman yang cocok",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "&rarr;",
                    previous: "&larr;"
                }
            },
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [
                { targets: [7], orderable: false, className: 'text-right' }
            ],
            drawCallback: function () {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
        });
    });
</script>

<?php $this->load->view('templates/admin_footer'); ?>