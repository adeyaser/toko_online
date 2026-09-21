<?php $this->load->view('templates/admin_header'); ?>

<div class="admin-header">
    <div>
        <h1 style="margin-bottom: 0.25rem;">Kelola Pesanan</h1>
        <div style="font-size: 0.85rem; color: #64748B;">Manajemen transaksi pesanan, nomor resi, dan cetak invoice
            massal</div>
    </div>
    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
        <!-- Report Button -->
        <!-- <a href="<?= base_url('admin/orders/report' . ($current_status ? '?status=' . $current_status : '')); ?>" id="btnCetakLaporan" target="_blank" class="btn btn-secondary" style="font-size: 0.85rem;">
            <i data-feather="file-text" style="width: 15px; height: 15px;"></i>
            <span>Cetak Laporan Penjualan</span>
        </a> -->

        <!-- Export Excel Button -->
        <a href="<?= base_url('admin/orders/export_excel' . ($current_status ? '?status=' . $current_status : '')); ?>"
            id="btnExportExcel" class="btn btn-success" style="font-size: 0.85rem; background: #059669; color: white;">
            <i data-feather="download" style="width: 15px; height: 15px;"></i>
            <span>Export Excel</span>
        </a>

        <!-- Bulk Invoice Print Button -->
        <button type="button" id="btnHeaderPrint" class="btn btn-primary" onclick="submitBulkPrint()" disabled
            style="opacity: 0.5; transition: all 0.2s; font-size: 0.85rem;">
            <i data-feather="printer" style="width: 15px; height: 15px;"></i>
            <span>Cetak Invoice Masal (PDF)</span>
            <span id="headerSelectedCount"
                style="display:none; background: #FFFFFF; color: var(--primary); padding: 1px 7px; border-radius: 99px; font-size: 0.75rem; font-weight: 800; margin-left: 4px;">0</span>
        </button>
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

<!-- Filter Bar: Date Range & Status Shortcuts -->
<div class="admin-filter-bar">
    <!-- Status Tabs -->
    <div style="display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap;">
        <span style="font-size: 0.8rem; font-weight: 700; color: #64748B; margin-right: 4px;">STATUS:</span>
        <button type="button" class="quick-filter-btn <?= !$current_status ? 'active' : ''; ?>"
            onclick="filterByStatus('')">Semua</button>
        <button type="button" class="quick-filter-btn <?= $current_status == 'pending' ? 'active' : ''; ?>"
            onclick="filterByStatus('pending')">Pending</button>
        <button type="button" class="quick-filter-btn <?= $current_status == 'processing' ? 'active' : ''; ?>"
            onclick="filterByStatus('processing')">Diproses</button>
        <button type="button" class="quick-filter-btn <?= $current_status == 'shipped' ? 'active' : ''; ?>"
            onclick="filterByStatus('shipped')">Dikirim</button>
        <button type="button" class="quick-filter-btn <?= $current_status == 'delivered' ? 'active' : ''; ?>"
            onclick="filterByStatus('delivered')">Selesai</button>
        <button type="button" class="quick-filter-btn <?= $current_status == 'cancelled' ? 'active' : ''; ?>"
            onclick="filterByStatus('cancelled')">Batal</button>
    </div>

    <!-- Date Range Inputs -->
    <div class="date-filter-group">
        <span style="font-size: 0.8rem; font-weight: 700; color: #64748B;">RENTANG TANGGAL:</span>
        <input type="date" id="filter_start_date" class="date-filter-input" title="Dari Tanggal">
        <span style="color: #94A3B8;">s/d</span>
        <input type="date" id="filter_end_date" class="date-filter-input" title="Sampai Tanggal">

        <button type="button" class="btn btn-sm btn-primary" onclick="applyDateFilter()"
            style="padding: 0.45rem 0.85rem; font-size: 0.82rem;">
            Terapkan
        </button>
        <button type="button" class="btn btn-sm btn-secondary" onclick="resetDateFilter()"
            style="padding: 0.45rem 0.75rem; font-size: 0.82rem;" title="Reset Filter Tanggal">
            Reset
        </button>

        <!-- Quick Dates -->
        <div style="display: flex; gap: 4px; margin-left: 6px;">
            <button type="button" class="quick-filter-btn" onclick="setQuickDate('today')">Hari Ini</button>
            <button type="button" class="quick-filter-btn" onclick="setQuickDate('week')">7 Hari</button>
            <button type="button" class="quick-filter-btn" onclick="setQuickDate('month')">Bulan Ini</button>
        </div>
    </div>
</div>

<!-- Bulk Print Form Wrapping DataTables Table -->
<form action="<?= base_url('admin/orders/print_invoices'); ?>" method="post" id="bulkPrintForm" target="_blank">
    <div class="admin-table-wrapper"
        style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <table id="ordersTable" class="admin-table" style="width: 100%;">
            <thead>
                <tr>
                    <th style="width: 44px; text-align: center;">
                        <input type="checkbox" id="checkAllOrders" onchange="toggleSelectAllOrders(this)"
                            style="cursor: pointer; width: 18px; height: 18px; accent-color: var(--primary);"
                            title="Pilih Semua">
                    </th>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Ekspedisi & Resi</th>
                    <th style="width: 125px;">Total</th>
                    <th style="width: 110px;">Status</th>
                    <th style="width: 120px;">Tanggal</th>
                    <th style="width: 140px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loaded dynamically by DataTables Server-Side -->
            </tbody>
        </table>
    </div>
</form>

<!-- Floating Bottom Sticky Bar when checkboxes are checked -->
<div id="stickySelectionBar"
    style="display: none; position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); background: #0F172A; color: #FFFFFF; padding: 0.75rem 1.5rem; border-radius: 99px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); z-index: 1000; align-items: center; gap: 1.25rem;">
    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 600;">
        <span id="stickyCountBadge"
            style="background: #3B82F6; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800;">0</span>
        <span>Pesanan Dipilih</span>
    </div>
    <div style="display: flex; align-items: center; gap: 0.5rem;">
        <button type="button" onclick="submitBulkPrint()" class="btn btn-sm btn-primary"
            style="border-radius: 99px; padding: 0.4rem 1rem; font-weight: 700; font-size: 0.85rem;">
            <i data-feather="printer" style="width: 14px; height: 14px;"></i> Cetak Invoice (PDF)
        </button>
        <button type="button" onclick="clearAllSelections()" class="btn btn-sm btn-secondary"
            style="border-radius: 99px; padding: 0.4rem 0.85rem; font-size: 0.82rem; background: #334155; color: #F1F5F9; border: none;">
            Batal
        </button>
    </div>
</div>

<script>
    let currentStatusFilter = "<?= $current_status; ?>";
    let ordersDataTable = null;

    $(document).ready(function () {
        ordersDataTable = $('#ordersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url('admin/orders/ajax_list'); ?>",
                type: "POST",
                data: function (d) {
                    d.status = currentStatusFilter;
                    d.start_date = $('#filter_start_date').val();
                    d.end_date = $('#filter_end_date').val();
                }
            },
            language: {
                search: "Cari Pesanan:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ pesanan",
                infoEmpty: "Menampilkan 0 pesanan",
                infoFiltered: "(disaring dari _MAX_ total pesanan)",
                zeroRecords: "Tidak ada data pesanan yang cocok",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                processing: '<div style="padding:8px;font-weight:700;color:var(--primary);">Memuat data pesanan...</div>'
            },
            order: [[6, 'desc']], // Sort by Date
            columns: [
                { orderable: false, className: "text-center" },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: false, className: "text-right" }
            ],
            drawCallback: function () {
                if (typeof feather !== 'undefined') feather.replace();
                updateBulkPrintUI();
                updateReportUrl();
            }
        });
    });

    function filterByStatus(status) {
        currentStatusFilter = status;
        $('.quick-filter-btn').removeClass('active');
        $(event.target).addClass('active');
        ordersDataTable.ajax.reload();
    }

    function applyDateFilter() {
        ordersDataTable.ajax.reload();
    }

    function resetDateFilter() {
        $('#filter_start_date').val('');
        $('#filter_end_date').val('');
        ordersDataTable.ajax.reload();
    }

    function setQuickDate(type) {
        const today = new Date();
        const formatDate = (d) => d.toISOString().split('T')[0];

        if (type === 'today') {
            const dStr = formatDate(today);
            $('#filter_start_date').val(dStr);
            $('#filter_end_date').val(dStr);
        } else if (type === 'week') {
            const prev = new Date();
            prev.setDate(today.getDate() - 7);
            $('#filter_start_date').val(formatDate(prev));
            $('#filter_end_date').val(formatDate(today));
        } else if (type === 'month') {
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            $('#filter_start_date').val(formatDate(firstDay));
            $('#filter_end_date').val(formatDate(today));
        }
        ordersDataTable.ajax.reload();
    }

    function updateReportUrl() {
        const sDate = $('#filter_start_date').val();
        const eDate = $('#filter_end_date').val();
        let url = "<?= base_url('admin/orders/report'); ?>?status=" + encodeURIComponent(currentStatusFilter);
        let excelUrl = "<?= base_url('admin/orders/export_excel'); ?>?status=" + encodeURIComponent(currentStatusFilter);
        if (sDate) {
            url += "&start_date=" + encodeURIComponent(sDate);
            excelUrl += "&start_date=" + encodeURIComponent(sDate);
        }
        if (eDate) {
            url += "&end_date=" + encodeURIComponent(eDate);
            excelUrl += "&end_date=" + encodeURIComponent(eDate);
        }
        $('#btnCetakLaporan').attr('href', url);
        $('#btnExportExcel').attr('href', excelUrl);
    }

    // Bulk Selection Management
    function toggleSelectAllOrders(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
            const row = cb.closest('tr');
            if (row) {
                row.style.backgroundColor = cb.checked ? '#EEF2FF' : '';
            }
        });
        updateBulkPrintUI();
    }

    function onOrderCheckboxChange() {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        let allChecked = true;

        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            if (cb.checked) {
                if (row) row.style.backgroundColor = '#EEF2FF';
            } else {
                allChecked = false;
                if (row) row.style.backgroundColor = '';
            }
        });

        const masterCheckbox = document.getElementById('checkAllOrders');
        if (masterCheckbox) {
            masterCheckbox.checked = checkboxes.length > 0 && allChecked;
        }

        updateBulkPrintUI();
    }

    function updateBulkPrintUI() {
        const checked = document.querySelectorAll('.order-checkbox:checked');
        const count = checked.length;

        const btnHeader = document.getElementById('btnHeaderPrint');
        const headerBadge = document.getElementById('headerSelectedCount');
        const stickyBar = document.getElementById('stickySelectionBar');
        const stickyBadge = document.getElementById('stickyCountBadge');

        if (count > 0) {
            if (btnHeader) {
                btnHeader.disabled = false;
                btnHeader.style.opacity = '1';
            }
            if (headerBadge) {
                headerBadge.style.display = 'inline-block';
                headerBadge.innerText = count;
            }
            if (stickyBar) {
                stickyBar.style.display = 'flex';
            }
            if (stickyBadge) {
                stickyBadge.innerText = count;
            }
        } else {
            if (btnHeader) {
                btnHeader.disabled = true;
                btnHeader.style.opacity = '0.5';
            }
            if (headerBadge) {
                headerBadge.style.display = 'none';
            }
            if (stickyBar) {
                stickyBar.style.display = 'none';
            }
        }
    }

    function clearAllSelections() {
        const masterCheckbox = document.getElementById('checkAllOrders');
        if (masterCheckbox) masterCheckbox.checked = false;
        toggleSelectAllOrders({ checked: false });
    }

    function submitBulkPrint() {
        const checked = document.querySelectorAll('.order-checkbox:checked');
        if (checked.length === 0) {
            alert('Silakan pilih setidaknya satu pesanan.');
            return;
        }
        const ids = Array.from(checked).map(cb => cb.value).join(',');
        window.open("<?= base_url('admin/orders/print_invoices'); ?>?ids=" + ids, '_blank');
    }
</script>

<?php $this->load->view('templates/admin_footer'); ?>