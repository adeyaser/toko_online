<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('user_role') !== 'admin') {
            redirect('login');
        }
        $this->load->model('Order_model', 'order_model');
    }

    public function index() {
        $status = $this->input->get('status') ?: '';
        $data['orders'] = $this->order_model->get_all(50, 0, $status);
        $data['current_status'] = $status;
        $data['title'] = 'Kelola Pesanan - ShopVista';
        $data['active_menu'] = 'orders';
        $this->load->view('admin/orders/list', $data);
    }

    public function detail($id) {
        $order = $this->order_model->get($id);
        if (!$order) { show_404(); return; }

        if ($this->input->method() === 'post') {
            $new_status = $this->input->post('status');
            $shipping_courier = $this->input->post('shipping_courier');
            $tracking_number = trim($this->input->post('tracking_number'));

            $update_data = [
                'status' => $new_status,
                'shipping_courier' => $shipping_courier ?: (get_setting('store_name', 'ShopVista') . ' Express'),
                'tracking_number' => $tracking_number ?: NULL,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->order_model->update($id, $update_data);
            $this->session->set_flashdata('success', 'Status pesanan dan informasi pengiriman berhasil diperbarui!');
            redirect('admin/orders/detail/' . $id);
        }

        $data['order'] = $order;
        $data['order_items'] = $this->order_model->get_items($order->id);
        $data['title'] = 'Detail Pesanan #' . $order->order_number . ' - ShopVista';
        $data['active_menu'] = 'orders';
        $this->load->view('admin/orders/detail', $data);
    }

    public function check_resi($id = null) {
        $id = (int)$id;
        $order = $this->order_model->get($id);
        if (!$order) {
            $this->output->set_status_header(404)->set_content_type('application/json')->set_output(json_encode([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.'
            ]));
            return;
        }

        $resi = $this->input->get('resi') ?: $order->tracking_number;
        $courier = $this->input->get('courier') ?: $order->shipping_courier;

        if (empty($resi)) {
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'success' => false,
                'message' => 'Nomor resi belum diisi.'
            ]));
            return;
        }

        $this->load->model('Tracking_model', 'tracking_model');
        $tracking_result = $this->tracking_model->track($courier, $resi, $order);

        $this->output->set_content_type('application/json')->set_output(json_encode($tracking_result));
    }

    public function print_invoices() {
        $order_ids = $this->input->post('order_ids');
        if (empty($order_ids)) {
            $ids_get = $this->input->get('ids');
            if ($ids_get) {
                $order_ids = explode(',', $ids_get);
            }
        }

        if (empty($order_ids) || !is_array($order_ids)) {
            $this->session->set_flashdata('error', 'Silakan pilih setidaknya satu pesanan untuk dicetak invoicenya.');
            redirect('admin/orders');
            return;
        }

        $orders = [];
        foreach ($order_ids as $id) {
            $id = (int)$id;
            if ($id <= 0) continue;
            $order = $this->order_model->get($id);
            if ($order) {
                $order->items = $this->order_model->get_items($order->id);
                $orders[] = $order;
            }
        }

        if (empty($orders)) {
            $this->session->set_flashdata('error', 'Pesanan yang dipilih tidak ditemukan.');
            redirect('admin/orders');
            return;
        }

        $store_name = get_setting('store_name', 'ShopVista');
        $format = $this->input->get('format');
        $action = $this->input->get('action');

        // Stream or download real PDF via TCPDF if format=pdf, action=pdf/download, or pdf=1
        if ($format === 'pdf' || $this->input->get('pdf') == '1' || $action === 'pdf' || $action === 'download') {
            $this->load->library('tcpdf_invoice');
            $mode = ($action === 'download' || $this->input->get('download') == '1') ? 'D' : 'I';
            $filename = (count($orders) === 1)
                ? 'Invoice_' . $orders[0]->order_number . '.pdf'
                : 'Invoices_' . count($orders) . '_Pesanan_' . date('Ymd_His') . '.pdf';

            $this->tcpdf_invoice->generate($orders, $mode, $filename);
            return;
        }

        $clean_ids = array_map(function($o) { return $o->id; }, $orders);
        $data['store_name'] = $store_name;
        $data['orders'] = $orders;
        $data['ids_string'] = implode(',', $clean_ids);
        $data['title'] = (count($orders) === 1) 
            ? 'Invoice #' . $orders[0]->order_number . ' (PDF) - ' . $store_name 
            : 'Cetak ' . count($orders) . ' Invoice Pesanan (PDF) - ' . $store_name;
        $this->load->view('admin/orders/print_invoices', $data);
    }

    public function ajax_list() {
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $searchPost = $this->input->post('search');
        $search = isset($searchPost['value']) ? trim($searchPost['value']) : '';
        $orderPost = $this->input->post('order');
        $order_col = isset($orderPost[0]['column']) ? intval($orderPost[0]['column']) : 6;
        $order_dir = isset($orderPost[0]['dir']) ? $orderPost[0]['dir'] : 'DESC';

        $status = $this->input->post('status') ?: '';
        $start_date = $this->input->post('start_date') ?: '';
        $end_date = $this->input->post('end_date') ?: '';

        $orders = $this->order_model->get_datatables($start, $length, $search, $order_col, $order_dir, $status, $start_date, $end_date);
        $recordsTotal = $this->order_model->count_all($status);
        $recordsFiltered = $this->order_model->count_filtered($search, $status, $start_date, $end_date);

        $data = [];
        foreach ($orders as $order) {
            $row = [];
            // Col 0: Checkbox
            $row[] = '<div style="text-align:center;"><input type="checkbox" name="order_ids[]" value="' . $order->id . '" class="order-checkbox" onchange="onOrderCheckboxChange()" style="cursor:pointer;width:18px;height:18px;accent-color:var(--primary);"></div>';

            // Col 1: Order Number
            $row[] = '<a href="' . base_url('admin/orders/detail/' . $order->id) . '" style="font-weight:700;color:var(--primary);text-decoration:none;font-family:monospace;font-size:0.95rem;">' . htmlspecialchars($order->order_number) . '</a>';

            // Col 2: Customer
            $row[] = '<div><div style="font-weight:600;color:#0F172A;font-size:0.9rem;">' . htmlspecialchars($order->user_name) . '</div><div style="font-size:0.78rem;color:#64748B;">' . htmlspecialchars($order->shipping_city ?: '-') . '</div></div>';

            // Col 3: Courier & Resi
            $courierHtml = '<div><div style="font-size:0.85rem;font-weight:600;color:#0F172A;">' . htmlspecialchars(!empty($order->shipping_courier) ? $order->shipping_courier : (get_setting('store_name', 'ShopVista') . ' Express')) . '</div>';
            if (!empty($order->tracking_number)) {
                $courierHtml .= '<div style="font-family:monospace;font-size:0.78rem;color:#059669;font-weight:700;">Resi: ' . htmlspecialchars($order->tracking_number) . '</div>';
            } else {
                $courierHtml .= '<div style="font-size:0.75rem;color:#94A3B8;font-style:italic;">Resi belum ada</div>';
            }
            $courierHtml .= '</div>';
            $row[] = $courierHtml;

            // Col 4: Total
            $row[] = '<span style="font-weight:700;color:#059669;font-size:0.95rem;">' . rupiah($order->grand_total) . '</span>';

            // Col 5: Status
            $row[] = order_status_badge($order->status);

            // Col 6: Date
            $row[] = '<div style="color:#64748B;font-size:0.82rem;">' . date('d M Y', strtotime($order->created_at)) . '<br><span style="font-size:0.75rem;color:#94A3B8;">' . date('H:i', strtotime($order->created_at)) . ' WIB</span></div>';

            // Col 7: Actions
            $actions = '<div style="display:inline-flex;gap:0.35rem;align-items:center;justify-content:flex-end;">'
                . '<a href="' . base_url('admin/orders/print_invoices?ids=' . $order->id) . '" target="_blank" class="btn btn-sm btn-secondary" title="Cetak Invoice PDF" style="font-size:0.75rem;padding:0.3rem 0.55rem;"><i data-feather="printer" style="width:12px;height:12px;"></i> Cetak</a>'
                . '<a href="' . base_url('admin/orders/detail/' . $order->id) . '" class="btn btn-sm btn-secondary" style="font-size:0.75rem;padding:0.3rem 0.65rem;">Detail</a>'
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
        $start_date = $this->input->get('start_date') ?: '';
        $end_date = $this->input->get('end_date') ?: '';
        $status = $this->input->get('status') ?: '';

        $store_name = get_setting('store_name', 'ShopVista');
        $data['orders'] = $this->order_model->get_report_orders($start_date, $end_date, $status);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['status'] = $status;
        $data['store_name'] = $store_name;
        $data['title'] = 'Laporan Rekapitulasi Transaksi Penjualan - ' . $store_name;

        $this->load->view('admin/orders/report', $data);
    }

    public function export_excel() {
        $start_date = $this->input->get('start_date') ?: '';
        $end_date = $this->input->get('end_date') ?: '';
        $status = $this->input->get('status') ?: '';

        $store_name = get_setting('store_name', 'ShopVista');
        $clean_store = preg_replace('/[^A-Za-z0-9_\-]/', '_', $store_name);
        $orders = $this->order_model->get_report_orders($start_date, $end_date, $status);
        $filename = 'Laporan_Penjualan_' . $clean_store . '_' . date('Ymd_His') . '.xls';

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="utf-8"><style>table{border-collapse:collapse;font-family:sans-serif;font-size:12px;} th{background:#2563EB;color:#fff;border:1px solid #94A3B8;padding:6px;} td{border:1px solid #CBD5E1;padding:6px;}</style></head><body>';
        echo '<h3>LAPORAN TRANSAKSI PENJUALAN - ' . strtoupper(htmlspecialchars($store_name)) . '</h3>';
        echo '<p>Tanggal Ekspor: ' . date('d/m/Y H:i') . ' WIB | Filter Status: ' . ($status ? strtoupper($status) : 'SEMUA') . '</p>';
        echo '<table border="1">';
        echo '<thead><tr>';
        echo '<th>No</th><th>No. Pesanan</th><th>Tanggal</th><th>Nama Pelanggan</th><th>Email</th><th>Kurir</th><th>No. Resi</th><th>Metode Bayar</th><th>Total Item</th><th>Grand Total</th><th>Status</th>';
        echo '</tr></thead><tbody>';

        $no = 1;
        $total_nominal = 0;
        foreach ($orders as $o) {
            $total_nominal += (float)$o->grand_total;

            $total_items = 0;
            if (!empty($o->items)) {
                foreach ($o->items as $it) {
                    $total_items += (int)$it->quantity;
                }
            }

            echo '<tr>';
            echo '<td align="center">' . $no++ . '</td>';
            echo '<td style="mso-number-format:\'\@\';">' . htmlspecialchars($o->order_number) . '</td>';
            echo '<td>' . date('d/m/Y H:i', strtotime($o->created_at)) . '</td>';
            echo '<td>' . htmlspecialchars($o->user_name ?: $o->shipping_name) . '</td>';
            echo '<td>' . htmlspecialchars($o->user_email ?: '-') . '</td>';
            echo '<td>' . htmlspecialchars($o->shipping_courier ?: '-') . '</td>';
            echo '<td style="mso-number-format:\'\@\';">' . htmlspecialchars($o->tracking_number ?: '-') . '</td>';
            echo '<td>' . htmlspecialchars(strtoupper(str_replace('_', ' ', $o->payment_method ?: 'TRANSFER'))) . '</td>';
            echo '<td align="center">' . $total_items . '</td>';
            echo '<td align="right">' . $o->grand_total . '</td>';
            echo '<td align="center">' . strtoupper($o->status) . '</td>';
            echo '</tr>';
        }
        echo '<tr><th colspan="9" align="right">TOTAL NILAI TRANSAKSI</th><th align="right">' . $total_nominal . '</th><th></th></tr>';
        echo '</tbody></table></body></html>';
        exit;
    }
}
