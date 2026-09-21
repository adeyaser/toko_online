<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Couriers extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('user_role') !== 'admin') {
            redirect('login');
        }
        $this->load->model('Courier_model', 'courier_model');
    }

    public function index() {
        $data['title'] = 'Kelola Jasa Pengiriman - ShopVista';
        $data['active_menu'] = 'couriers';
        $this->load->view('admin/couriers/list', $data);
    }

    public function create() {
        if ($this->input->method() === 'post') {
            $data = [
                'code' => trim($this->input->post('code')),
                'courier_name' => trim($this->input->post('courier_name')),
                'service_name' => trim($this->input->post('service_name')),
                'description' => trim($this->input->post('description')),
                'etd' => trim($this->input->post('etd')),
                'cost' => (float)$this->input->post('cost'),
                'icon' => trim($this->input->post('icon')) ?: 'truck',
                'badge' => trim($this->input->post('badge')) ?: NULL,
                'is_free_eligible' => $this->input->post('is_free_eligible') ? 1 : 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0,
                'sort_order' => (int)$this->input->post('sort_order') ?: 0
            ];

            if (empty($data['code'])) {
                $data['code'] = strtolower(url_title($data['courier_name'] . '-' . $data['service_name'], 'underscore', TRUE));
            }

            $id = $this->courier_model->create($data);
            if ($id) {
                $this->session->set_flashdata('success', 'Jasa pengiriman berhasil ditambahkan!');
                redirect('admin/couriers');
                return;
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan jasa pengiriman. Pastikan kode unik belum terpakai.');
            }
        }

        $data['title'] = 'Tambah Jasa Pengiriman - ShopVista';
        $data['active_menu'] = 'couriers';
        $data['mode'] = 'create';
        $data['courier'] = null;
        $this->load->view('admin/couriers/form', $data);
    }

    public function edit($id) {
        $courier = $this->courier_model->get($id);
        if (!$courier) {
            show_404();
            return;
        }

        if ($this->input->method() === 'post') {
            $data = [
                'code' => trim($this->input->post('code')),
                'courier_name' => trim($this->input->post('courier_name')),
                'service_name' => trim($this->input->post('service_name')),
                'description' => trim($this->input->post('description')),
                'etd' => trim($this->input->post('etd')),
                'cost' => (float)$this->input->post('cost'),
                'icon' => trim($this->input->post('icon')) ?: 'truck',
                'badge' => trim($this->input->post('badge')) ?: NULL,
                'is_free_eligible' => $this->input->post('is_free_eligible') ? 1 : 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0,
                'sort_order' => (int)$this->input->post('sort_order') ?: 0
            ];

            $this->courier_model->update($id, $data);
            $this->session->set_flashdata('success', 'Jasa pengiriman berhasil diperbarui!');
            redirect('admin/couriers');
            return;
        }

        $data['title'] = 'Edit Jasa Pengiriman - ShopVista';
        $data['active_menu'] = 'couriers';
        $data['mode'] = 'edit';
        $data['courier'] = $courier;
        $this->load->view('admin/couriers/form', $data);
    }

    public function delete($id) {
        $this->courier_model->delete($id);
        $this->session->set_flashdata('success', 'Jasa pengiriman berhasil dihapus!');
        redirect('admin/couriers');
    }

    public function toggle($id) {
        $new_status = $this->courier_model->toggle_active($id);
        $this->session->set_flashdata('success', 'Status jasa pengiriman berhasil diubah!');
        redirect('admin/couriers');
    }

    public function ajax_list() {
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $searchPost = $this->input->post('search');
        $search = isset($searchPost['value']) ? trim($searchPost['value']) : '';
        $orderPost = $this->input->post('order');
        $order_col = isset($orderPost[0]['column']) ? intval($orderPost[0]['column']) : 0;
        $order_dir = isset($orderPost[0]['dir']) ? $orderPost[0]['dir'] : 'ASC';

        $couriers = $this->courier_model->get_datatables($start, $length, $search, $order_col, $order_dir);
        $recordsTotal = $this->courier_model->count_all();
        $recordsFiltered = $this->courier_model->count_filtered($search);

        $data = [];
        foreach ($couriers as $c) {
            $row = [];

            // Col 0: Urutan
            $row[] = '<span style="font-weight:700;color:#64748B;">#' . $c->sort_order . '</span>';

            // Col 1: Kurir & Ikon
            $iconName = $c->icon ?: 'truck';
            $badgeHtml = !empty($c->badge) ? ' <span style="font-size:0.7rem;padding:0.15rem 0.45rem;border-radius:4px;background:#EEF2FF;color:#4338CA;font-weight:700;">' . htmlspecialchars($c->badge) . '</span>' : '';
            $row[] = '<div style="display:flex;align-items:center;gap:0.6rem;">'
                   . '<div style="width:32px;height:32px;border-radius:6px;background:#F1F5F9;display:flex;align-items:center;justify-content:center;color:#2563EB;flex-shrink:0;">'
                   . '<i data-feather="' . htmlspecialchars($iconName) . '" style="width:16px;height:16px;"></i></div>'
                   . '<div><div style="font-weight:700;color:#0F172A;">' . htmlspecialchars($c->courier_name) . $badgeHtml . '</div>'
                   . '<div style="font-size:0.75rem;color:#64748B;font-family:monospace;">' . htmlspecialchars($c->code) . '</div></div></div>';

            // Col 2: Layanan & Deskripsi
            $row[] = '<div><strong style="color:#0F172A;">' . htmlspecialchars($c->service_name) . '</strong>'
                   . '<div style="font-size:0.78rem;color:#64748B;">' . htmlspecialchars($c->description ?: '-') . '</div></div>';

            // Col 3: Tarif Ongkir
            $costHtml = $c->cost <= 0 
                ? '<span style="font-weight:800;color:#059669;">GRATIS</span>' 
                : '<span style="font-weight:700;color:#0F172A;">' . rupiah($c->cost) . '</span>';
            $row[] = $costHtml;

            // Col 4: Estimasi Tiba (ETD)
            $row[] = '<span style="color:#475569;font-weight:600;font-size:0.85rem;">' . htmlspecialchars($c->etd) . '</span>';

            // Col 5: Syarat Bebas Ongkir
            $row[] = $c->is_free_eligible 
                ? '<span class="badge badge-success" style="font-size:0.72rem;">Ikut Promo</span>' 
                : '<span class="badge badge-secondary" style="font-size:0.72rem;background:#F1F5F9;color:#64748B;">Tidak Ikut</span>';

            // Col 6: Status
            $statusBadge = $c->is_active 
                ? '<a href="' . base_url('admin/couriers/toggle/' . $c->id) . '" class="badge badge-success" title="Klik untuk nonaktifkan" style="text-decoration:none;cursor:pointer;">Aktif</a>' 
                : '<a href="' . base_url('admin/couriers/toggle/' . $c->id) . '" class="badge badge-danger" title="Klik untuk aktifkan" style="text-decoration:none;cursor:pointer;">Nonaktif</a>';
            $row[] = $statusBadge;

            // Col 7: Aksi
            $actions = '<div style="display:inline-flex;gap:0.35rem;align-items:center;justify-content:flex-end;">'
                     . '<a href="' . base_url('admin/couriers/edit/' . $c->id) . '" class="btn btn-sm btn-secondary" style="font-size:0.75rem;padding:0.3rem 0.65rem;">Edit</a>'
                     . '<a href="' . base_url('admin/couriers/delete/' . $c->id) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus opsi pengiriman ini?\')" style="font-size:0.75rem;padding:0.3rem 0.65rem;">Hapus</a>'
                     . '</div>';
            $row[] = $actions;

            $data[] = $row;
        }

        $output = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    public function report() {
        $store_name = get_setting('store_name', 'ShopVista');
        $data['couriers'] = $this->courier_model->get_report_data();
        $data['store_name'] = $store_name;
        $data['title'] = 'Laporan Jasa Pengiriman & Tarif Ekspedisi - ' . $store_name;
        $this->load->view('admin/couriers/report', $data);
    }

    public function export_excel() {
        $store_name = get_setting('store_name', 'ShopVista');
        $clean_store = preg_replace('/[^A-Za-z0-9_\-]/', '_', $store_name);
        $couriers = $this->courier_model->get_report_data();
        $filename = 'Laporan_Ekspedisi_' . $clean_store . '_' . date('Ymd_His') . '.xls';

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="utf-8"><style>table{border-collapse:collapse;font-family:sans-serif;font-size:12px;} th{background:#2563EB;color:#fff;border:1px solid #94A3B8;padding:6px;} td{border:1px solid #CBD5E1;padding:6px;}</style></head><body>';
        echo '<h3>LAPORAN JASA PENGIRIMAN & TARIF - ' . strtoupper(htmlspecialchars($store_name)) . '</h3>';
        echo '<p>Tanggal Ekspor: ' . date('d/m/Y H:i') . ' WIB | Total: ' . count($couriers) . ' Layanan</p>';
        echo '<table border="1">';
        echo '<thead><tr>';
        echo '<th>No</th><th>Kode Ekspedisi</th><th>Nama Ekspedisi</th><th>Nama Layanan</th><th>Tarif Ongkir (Rp)</th><th>Estimasi Pengiriman</th><th>Promo Gratis Ongkir</th><th>Status</th>';
        echo '</tr></thead><tbody>';

        $no = 1;
        foreach ($couriers as $c) {
            echo '<tr>';
            echo '<td align="center">' . $no++ . '</td>';
            echo '<td>' . strtoupper($c->code) . '</td>';
            echo '<td>' . htmlspecialchars($c->courier_name) . '</td>';
            echo '<td>' . htmlspecialchars($c->service_name) . '</td>';
            echo '<td align="right">' . $c->cost . '</td>';
            echo '<td align="center">' . htmlspecialchars($c->etd ?: '-') . '</td>';
            echo '<td align="center">' . ($c->is_free_eligible ? 'Ikut Promo' : 'Tidak') . '</td>';
            echo '<td align="center">' . ($c->is_active ? 'Aktif' : 'Nonaktif') . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></body></html>';
        exit;
    }
}
