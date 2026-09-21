<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('user_role') !== 'admin') {
            redirect('login');
        }
        $this->load->model('Category_model', 'category_model');
    }

    public function index() {
        $data['categories'] = $this->category_model->get_admin_list();
        $data['title'] = 'Kelola Kategori - ShopVista';
        $data['active_menu'] = 'categories';
        $this->load->view('admin/categories/list', $data);
    }

    public function create() {
        if ($this->input->method() === 'post') {
            $cat_data = [
                'name' => $this->input->post('name'),
                'slug' => url_title($this->input->post('name'), 'dash', TRUE),
                'icon' => $this->input->post('icon') ?: 'package',
                'description' => $this->input->post('description'),
                'sort_order' => (int) $this->input->post('sort_order'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            // Handle image upload or image url
            if (!empty($_FILES['image']['name'])) {
                $upload_dir = './assets/images/categories/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $config['upload_path'] = $upload_dir;
                $config['allowed_types'] = 'jpg|jpeg|png|webp';
                $config['max_size'] = 2048;
                $config['file_name'] = 'cat_' . time() . '_' . rand(100, 999);
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image')) {
                    $cat_data['image'] = $this->upload->data('file_name');
                }
            } elseif ($this->input->post('image_url')) {
                $cat_data['image'] = trim($this->input->post('image_url'));
            }

            $this->category_model->insert($cat_data);
            $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan!');
            redirect('admin/categories');
        }

        $data['title'] = 'Tambah Kategori - ShopVista';
        $data['active_menu'] = 'categories';
        $data['mode'] = 'create';
        $this->load->view('admin/categories/form', $data);
    }

    public function edit($id) {
        $category = $this->category_model->get($id);
        if (!$category) { show_404(); return; }

        if ($this->input->method() === 'post') {
            $cat_data = [
                'name' => $this->input->post('name'),
                'slug' => url_title($this->input->post('name'), 'dash', TRUE),
                'icon' => $this->input->post('icon') ?: 'package',
                'description' => $this->input->post('description'),
                'sort_order' => (int) $this->input->post('sort_order'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            // Handle image upload or image url
            if (!empty($_FILES['image']['name'])) {
                $upload_dir = './assets/images/categories/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $config['upload_path'] = $upload_dir;
                $config['allowed_types'] = 'jpg|jpeg|png|webp';
                $config['max_size'] = 2048;
                $config['file_name'] = 'cat_' . time() . '_' . rand(100, 999);
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image')) {
                    $cat_data['image'] = $this->upload->data('file_name');
                }
            } elseif ($this->input->post('image_url')) {
                $cat_data['image'] = trim($this->input->post('image_url'));
            }

            $this->category_model->update($id, $cat_data);
            $this->session->set_flashdata('success', 'Kategori berhasil diperbarui!');
            redirect('admin/categories');
        }

        $data['category'] = $category;
        $data['title'] = 'Edit Kategori - ShopVista';
        $data['active_menu'] = 'categories';
        $data['mode'] = 'edit';
        $this->load->view('admin/categories/form', $data);
    }

    public function delete($id) {
        $this->category_model->delete($id);
        $this->session->set_flashdata('success', 'Kategori berhasil dihapus!');
        redirect('admin/categories');
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

        $categories = $this->category_model->get_datatables($start, $length, $search, $order_col, $order_dir);
        $recordsTotal = $this->category_model->count_all();
        $recordsFiltered = $this->category_model->count_filtered($search);

        $data = [];
        foreach ($categories as $cat) {
            $row = [];
            // Col 0: Image & Name
            $imgUrl = category_image($cat->image, $cat->slug);
            $row[] = '<div style="display:flex;align-items:center;gap:10px;">'
                . '<img src="' . $imgUrl . '" style="width:38px;height:38px;border-radius:10px;object-fit:cover;border:1px solid #E2E8F0;background:#F8FAFC;flex-shrink:0;">'
                . '<div><div style="font-weight: 700; color: #0F172A; font-size: 0.92rem;">' . htmlspecialchars($cat->name) . '</div>'
                . ($cat->description ? '<div style="font-size:0.75rem;color:#64748B;line-height:1.2;">' . htmlspecialchars(substr($cat->description, 0, 40)) . '...</div>' : '')
                . '</div></div>';
            // Col 1: Slug
            $row[] = '<code style="font-size: 0.8rem; color: #64748B;">' . htmlspecialchars($cat->slug) . '</code>';
            // Col 2: Icon
            $row[] = '<div style="display:flex;align-items:center;gap:6px;"><i data-feather="' . htmlspecialchars($cat->icon ?: 'package') . '" style="width:18px;height:18px;color:var(--primary);"></i> <span style="font-size:0.8rem;color:#64748B;">' . htmlspecialchars($cat->icon ?: 'package') . '</span></div>';
            // Col 3: Product Count
            $row[] = '<span style="font-weight: 700; color: #0F172A;">' . intval($cat->product_count) . ' produk</span>';
            // Col 4: Status
            $row[] = $cat->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>';
            // Col 5: Actions
            $actions = '<div class="actions-cell" style="display:flex;gap:4px;justify-content:flex-end;">'
                . '<a href="' . base_url('admin/categories/edit/' . $cat->id) . '" class="action-btn" title="Edit"><i data-feather="edit-2" style="width:14px;height:14px;"></i></a>'
                . '<a href="' . base_url('admin/categories/delete/' . $cat->id) . '" class="action-btn delete" onclick="return confirm(\'Hapus kategori ini?\')" title="Hapus"><i data-feather="trash-2" style="width:14px;height:14px;"></i></a>'
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
        $data['categories'] = $this->category_model->get_report_data();
        $data['store_name'] = $store_name;
        $data['title'] = 'Laporan Kategori Produk - ' . $store_name;
        $this->load->view('admin/categories/report', $data);
    }

    public function export_excel() {
        $store_name = get_setting('store_name', 'ShopVista');
        $clean_store = preg_replace('/[^A-Za-z0-9_\-]/', '_', $store_name);
        $categories = $this->category_model->get_report_data();
        $filename = 'Laporan_Kategori_' . $clean_store . '_' . date('Ymd_His') . '.xls';

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="utf-8"><style>table{border-collapse:collapse;font-family:sans-serif;font-size:12px;} th{background:#2563EB;color:#fff;border:1px solid #94A3B8;padding:6px;} td{border:1px solid #CBD5E1;padding:6px;}</style></head><body>';
        echo '<h3>LAPORAN KATEGORI PRODUK - ' . strtoupper(htmlspecialchars($store_name)) . '</h3>';
        echo '<p>Tanggal Ekspor: ' . date('d/m/Y H:i') . ' WIB | Total: ' . count($categories) . ' Kategori</p>';
        echo '<table border="1">';
        echo '<thead><tr>';
        echo '<th>No</th><th>Nama Kategori</th><th>Slug</th><th>Deskripsi</th><th>Jumlah Produk</th><th>Urutan</th><th>Status</th>';
        echo '</tr></thead><tbody>';

        $no = 1;
        $total_produk_semua = 0;
        foreach ($categories as $c) {
            $prod_count = isset($c->product_count) ? (int)$c->product_count : (isset($c->total_products) ? (int)$c->total_products : 0);
            $total_produk_semua += $prod_count;

            echo '<tr>';
            echo '<td align="center">' . $no++ . '</td>';
            echo '<td>' . htmlspecialchars($c->name) . '</td>';
            echo '<td>' . htmlspecialchars($c->slug) . '</td>';
            echo '<td>' . htmlspecialchars($c->description ?: '-') . '</td>';
            echo '<td align="center">' . $prod_count . '</td>';
            echo '<td align="center">' . (int)$c->sort_order . '</td>';
            echo '<td align="center">' . ($c->is_active ? 'Aktif' : 'Nonaktif') . '</td>';
            echo '</tr>';
        }
        echo '<tr><th colspan="4" align="right">TOTAL PRODUK TERHUBUNG</th><th align="center">' . $total_produk_semua . '</th><th colspan="2"></th></tr>';
        echo '</tbody></table></body></html>';
        exit;
    }
}
