<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('user_role') !== 'admin') {
            redirect('login');
        }
        $this->load->model('Product_model', 'product_model');
        $this->load->model('Category_model', 'category_model');
    }

    public function index() {
        $data['products'] = $this->product_model->get_admin_list(50, 0);
        $data['title'] = 'Kelola Produk - ShopVista';
        $data['active_menu'] = 'products';
        $this->load->view('admin/products/list', $data);
    }

    public function create() {
        if ($this->input->method() === 'post') {
            $slug = url_title($this->input->post('name'), 'dash', TRUE);
            
            $product_data = [
                'name' => $this->input->post('name'),
                'slug' => $slug,
                'category_id' => $this->input->post('category_id'),
                'price' => $this->input->post('price'),
                'sale_price' => $this->input->post('sale_price') ?: null,
                'stock' => $this->input->post('stock'),
                'weight' => $this->input->post('weight'),
                'short_desc' => $this->input->post('short_desc'),
                'description' => $this->input->post('description'),
                'location' => $this->input->post('location') ?: 'Jakarta Pusat',
                'is_featured' => $this->input->post('is_featured') ? 1 : 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            // Handle image upload
            if ($_FILES['image']['name']) {
                $config['upload_path'] = './assets/images/products/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size'] = 2048;
                $config['file_name'] = $slug . '-' . time();
                
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image')) {
                    $product_data['image'] = $this->upload->data('file_name');
                }
            }

            $new_product_id = $this->product_model->insert($product_data);

            // Handle notification to leads
            $lead_msg = '';
            if ($this->input->post('notify_leads') && $product_data['is_active']) {
                $this->load->library('Lead_notifier', null, 'lead_notifier');
                $notify_res = $this->lead_notifier->notify_product($new_product_id, 'created');
                if (!empty($notify_res['count'])) {
                    $lead_msg = ' & notifikasi dikirim ke ' . $notify_res['count'] . ' leads';
                }
            }

            $this->session->set_flashdata('success', 'Produk berhasil ditambahkan' . $lead_msg . '!');
            redirect('admin/products');
        }

        $data['categories'] = $this->category_model->get_all_active();
        $data['title'] = 'Tambah Produk - ShopVista';
        $data['active_menu'] = 'products';
        $data['mode'] = 'create';
        $this->load->view('admin/products/form', $data);
    }

    public function edit($id) {
        $product = $this->product_model->get($id);
        if (!$product) {
            show_404();
            return;
        }

        if ($this->input->method() === 'post') {
            $product_data = [
                'name' => $this->input->post('name'),
                'slug' => url_title($this->input->post('name'), 'dash', TRUE),
                'category_id' => $this->input->post('category_id'),
                'price' => $this->input->post('price'),
                'sale_price' => $this->input->post('sale_price') ?: null,
                'stock' => $this->input->post('stock'),
                'weight' => $this->input->post('weight'),
                'short_desc' => $this->input->post('short_desc'),
                'description' => $this->input->post('description'),
                'location' => $this->input->post('location') ?: 'Jakarta Pusat',
                'is_featured' => $this->input->post('is_featured') ? 1 : 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            if ($_FILES['image']['name']) {
                $config['upload_path'] = './assets/images/products/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size'] = 2048;
                $config['file_name'] = $product_data['slug'] . '-' . time();
                
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image')) {
                    $product_data['image'] = $this->upload->data('file_name');
                }
            }

            $this->product_model->update($id, $product_data);

            // Handle notification to leads
            $lead_msg = '';
            if ($this->input->post('notify_leads') && $product_data['is_active']) {
                $this->load->library('Lead_notifier', null, 'lead_notifier');
                $notify_res = $this->lead_notifier->notify_product($id, 'updated');
                if (!empty($notify_res['count'])) {
                    $lead_msg = ' & notifikasi pembaruan dikirim ke ' . $notify_res['count'] . ' leads';
                }
            }

            $this->session->set_flashdata('success', 'Produk berhasil diperbarui' . $lead_msg . '!');
            redirect('admin/products');
        }

        $data['product'] = $product;
        $data['categories'] = $this->category_model->get_all_active();
        $data['title'] = 'Edit Produk - ShopVista';
        $data['active_menu'] = 'products';
        $data['mode'] = 'edit';
        $this->load->view('admin/products/form', $data);
    }

    public function delete($id) {
        $this->product_model->delete($id);
        $this->session->set_flashdata('success', 'Produk berhasil dihapus!');
        redirect('admin/products');
    }

    public function ajax_list() {
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $searchPost = $this->input->post('search');
        $search = isset($searchPost['value']) ? trim($searchPost['value']) : '';
        $orderPost = $this->input->post('order');
        $order_col = isset($orderPost[0]['column']) ? intval($orderPost[0]['column']) : 0;
        $order_dir = isset($orderPost[0]['dir']) ? $orderPost[0]['dir'] : 'DESC';

        $products = $this->product_model->get_datatables($start, $length, $search, $order_col, $order_dir);
        $recordsTotal = $this->product_model->count_admin();
        $recordsFiltered = $this->product_model->count_filtered($search);

        $data = [];
        foreach ($products as $p) {
            $row = [];
            // Col 0: Product Image + Name + Category + Location
            $img = product_image($p->image);
            $productCell = '<div class="product-cell" style="display:flex;align-items:center;gap:0.75rem;">'
                . '<img src="' . $img . '" alt="" style="width:48px;height:48px;border-radius:8px;object-fit:cover;border:1px solid #E2E8F0;">'
                . '<div>'
                . '<div class="product-name" style="font-weight:700;color:#0F172A;font-size:0.92rem;">' . htmlspecialchars($p->name) . '</div>'
                . '<div class="product-cat" style="font-size:0.78rem;color:#64748B;">' . htmlspecialchars($p->category_name ?: 'Tanpa Kategori') . ' • 📍 ' . htmlspecialchars($p->location ?: 'Jakarta Pusat') . '</div>'
                . '</div></div>';
            $row[] = $productCell;

            // Col 1: Price
            $priceHtml = '<div style="font-weight: 700; color: #0F172A;">' . rupiah($p->sale_price ?: $p->price) . '</div>';
            if ($p->sale_price) {
                $priceHtml .= '<div style="font-size: 0.8rem; text-decoration: line-through; color: #94A3B8;">' . rupiah($p->price) . '</div>';
            }
            $row[] = $priceHtml;

            // Col 2: Stock
            $stockColor = $p->stock > 10 ? '#059669' : ($p->stock > 0 ? '#D97706' : '#DC2626');
            $row[] = '<span style="color: ' . $stockColor . '; font-weight: 700;">' . $p->stock . ' pcs</span>';

            // Col 3: Category
            $row[] = '<span style="color:#334155;font-weight:500;">' . htmlspecialchars($p->category_name ?: '-') . '</span>';

            // Col 4: Status
            $statusHtml = $p->is_active 
                ? '<span class="badge badge-success">Aktif</span>' 
                : '<span class="badge badge-danger">Nonaktif</span>';
            if ($p->is_featured) {
                $statusHtml .= ' <span class="badge badge-featured">⭐ Unggulan</span>';
            }
            $row[] = $statusHtml;

            // Col 5: Actions
            $actions = '<div class="actions-cell" style="display:flex;gap:4px;justify-content:flex-end;">'
                . '<a href="' . base_url('admin/products/edit/' . $p->id) . '" class="action-btn" title="Edit"><i data-feather="edit-2" style="width:14px;height:14px;"></i></a>'
                . '<a href="' . base_url('admin/products/delete/' . $p->id) . '" class="action-btn delete" title="Hapus" onclick="return confirm(\'Yakin ingin menghapus produk ini?\')"><i data-feather="trash-2" style="width:14px;height:14px;"></i></a>'
                . '<a href="' . base_url('produk/' . $p->slug) . '" target="_blank" class="action-btn" title="Lihat di Web"><i data-feather="external-link" style="width:14px;height:14px;"></i></a>'
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
        $category_id = $this->input->get('category_id');
        $is_active = $this->input->get('is_active');
        $store_name = get_setting('store_name', 'ShopVista');
        $data['products'] = $this->product_model->get_report_data($category_id, $is_active);
        $data['store_name'] = $store_name;
        $data['title'] = 'Laporan Data Produk & Inventaris - ' . $store_name;
        $this->load->view('admin/products/report', $data);
    }

    public function export_excel() {
        $category_id = $this->input->get('category_id');
        $is_active = $this->input->get('is_active');
        $store_name = get_setting('store_name', 'ShopVista');
        $clean_store = preg_replace('/[^A-Za-z0-9_\-]/', '_', $store_name);
        $products = $this->product_model->get_report_data($category_id, $is_active);
        $filename = 'Laporan_Produk_' . $clean_store . '_' . date('Ymd_His') . '.xls';

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="utf-8"><style>table{border-collapse:collapse;font-family:sans-serif;font-size:12px;} th{background:#2563EB;color:#fff;border:1px solid #94A3B8;padding:6px;} td{border:1px solid #CBD5E1;padding:6px;}</style></head><body>';
        echo '<h3>LAPORAN INVENTARIS PRODUK - ' . strtoupper(htmlspecialchars($store_name)) . '</h3>';
        echo '<p>Tanggal Ekspor: ' . date('d/m/Y H:i') . ' WIB | Total: ' . count($products) . ' Produk</p>';
        echo '<table border="1">';
        echo '<thead><tr>';
        echo '<th>No</th><th>Nama Produk</th><th>Kategori</th><th>Berat</th><th>Harga Normal (Rp)</th><th>Harga Promo (Rp)</th><th>Stok</th><th>Nilai Aset Stok (Rp)</th><th>Status</th>';
        echo '</tr></thead><tbody>';

        $no = 1;
        $total_stok = 0;
        $total_aset = 0;
        foreach ($products as $p) {
            $total_stok += (int)$p->stock;
            $eff_price = (!empty($p->sale_price) && $p->sale_price > 0) ? (float)$p->sale_price : (float)$p->price;
            $subtotal_aset = $eff_price * (int)$p->stock;
            $total_aset += $subtotal_aset;

            echo '<tr>';
            echo '<td align="center">' . $no++ . '</td>';
            echo '<td>' . htmlspecialchars($p->name) . '</td>';
            echo '<td>' . htmlspecialchars($p->category_name ?: '-') . '</td>';
            echo '<td align="center">' . htmlspecialchars(!empty($p->weight) ? $p->weight . ' gr' : '-') . '</td>';
            echo '<td align="right">' . $p->price . '</td>';
            echo '<td align="right">' . ($p->sale_price ?: '-') . '</td>';
            echo '<td align="center">' . $p->stock . '</td>';
            echo '<td align="right">' . $subtotal_aset . '</td>';
            echo '<td align="center">' . ($p->is_active ? 'Aktif' : 'Nonaktif') . '</td>';
            echo '</tr>';
        }
        echo '<tr>';
        echo '<th colspan="6" align="right">TOTAL UNIT STOK & NILAI INVENTARIS</th>';
        echo '<th align="center">' . $total_stok . '</th>';
        echo '<th align="right">' . $total_aset . '</th>';
        echo '<th></th>';
        echo '</tr>';
        echo '</tbody></table></body></html>';
        exit;
    }
}
