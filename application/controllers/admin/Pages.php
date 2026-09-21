<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('user_role') !== 'admin') {
            redirect('login');
        }
        $this->load->model('Page_model', 'page_model');
    }

    public function index() {
        $data['title'] = 'Kelola Halaman Statis - ShopVista';
        $data['active_menu'] = 'pages';
        $data['total_pages'] = $this->page_model->count_all();
        $this->load->view('admin/pages/list', $data);
    }

    public function ajax_list() {
        $start = (int)$this->input->post('start');
        $length = (int)$this->input->post('length');
        $search = $this->input->post('search')['value'] ?? '';
        $order_col = (int)($this->input->post('order')[0]['column'] ?? 0);
        $order_dir = $this->input->post('order')[0]['dir'] ?? 'ASC';

        $pages = $this->page_model->get_datatables($start, $length, $search, $order_col, $order_dir);
        $records_total = $this->page_model->count_all();
        $records_filtered = $this->page_model->count_filtered($search);

        $data = [];
        foreach ($pages as $p) {
            $status_badge = $p->is_active 
                ? '<a href="' . base_url('admin/pages/toggle/' . $p->id) . '" class="badge badge-success" title="Klik untuk nonaktifkan" style="text-decoration:none;cursor:pointer;"><i data-feather="check-circle" style="width:12px;height:12px;margin-right:3px;"></i> Aktif</a>' 
                : '<a href="' . base_url('admin/pages/toggle/' . $p->id) . '" class="badge badge-danger" title="Klik untuk aktifkan" style="text-decoration:none;cursor:pointer;"><i data-feather="x-circle" style="width:12px;height:12px;margin-right:3px;"></i> Nonaktif</a>';

            $icon_badge = '<div style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;background:rgba(79,70,229,0.08);color:var(--primary);"><i data-feather="' . (!empty($p->icon) ? htmlspecialchars($p->icon) : 'file-text') . '" style="width:18px;height:18px;"></i></div>';

            $actions = '<div class="table-actions" style="display:flex;gap:6px;">'
                . '<a href="' . base_url($p->slug) . '" target="_blank" class="btn btn-sm btn-outline" title="Lihat di Web"><i data-feather="external-link" style="width:14px;height:14px;"></i></a>'
                . '<a href="' . base_url('admin/pages/edit/' . $p->id) . '" class="btn btn-sm btn-outline" title="Edit Halaman"><i data-feather="edit" style="width:14px;height:14px;"></i></a>'
                . '<a href="' . base_url('admin/pages/delete/' . $p->id) . '" class="btn btn-sm btn-danger" title="Hapus Halaman" onclick="return confirm(\'Apakah Anda yakin ingin menghapus halaman ' . htmlspecialchars($p->title, ENT_QUOTES) . '?\');"><i data-feather="trash-2" style="width:14px;height:14px;"></i></a>'
                . '</div>';

            $title_html = '<div><strong style="color:#0F172A;font-size:0.95rem;">' . htmlspecialchars($p->title) . '</strong>'
                . '<div style="color:#64748B;font-size:0.8rem;margin-top:2px;">' . htmlspecialchars(substr(strip_tags($p->content), 0, 70)) . '...</div></div>';

            $slug_badge = '<code style="background:#F1F5F9;color:#475569;padding:3px 8px;border-radius:4px;font-size:0.82rem;">/' . htmlspecialchars($p->slug) . '</code>';

            $data[] = [
                $p->sort_order,
                $title_html,
                $slug_badge,
                $icon_badge,
                $status_badge,
                date('d/m/Y H:i', strtotime($p->updated_at)),
                $actions
            ];
        }

        echo json_encode([
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_filtered,
            'data' => $data
        ]);
    }

    public function create() {
        if ($this->input->method() === 'post') {
            $title = trim($this->input->post('title'));
            $slug = trim($this->input->post('slug'));
            if (empty($slug)) {
                $slug = strtolower(url_title($title, 'dash', TRUE));
            }

            // Check slug uniqueness
            $existing = $this->page_model->get_by_slug($slug);
            if ($existing) {
                $this->session->set_flashdata('error', 'Slug URL "' . $slug . '" sudah digunakan. Silakan gunakan slug lain.');
                redirect('admin/pages/create');
                return;
            }

            $page_data = [
                'title' => $title,
                'slug' => $slug,
                'icon' => $this->input->post('icon') ?: 'file-text',
                'content' => $this->input->post('content'),
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'sort_order' => (int)$this->input->post('sort_order'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            $this->page_model->create($page_data);
            $this->session->set_flashdata('success', 'Halaman baru berhasil ditambahkan!');
            redirect('admin/pages');
            return;
        }

        $data['title'] = 'Tambah Halaman Baru - ShopVista';
        $data['active_menu'] = 'pages';
        $this->load->view('admin/pages/form', $data);
    }

    public function edit($id = null) {
        if (!$id) {
            redirect('admin/pages');
        }

        $page = $this->page_model->get($id);
        if (!$page) {
            $this->session->set_flashdata('error', 'Halaman tidak ditemukan.');
            redirect('admin/pages');
            return;
        }

        if ($this->input->method() === 'post') {
            $title = trim($this->input->post('title'));
            $slug = trim($this->input->post('slug'));
            if (empty($slug)) {
                $slug = strtolower(url_title($title, 'dash', TRUE));
            }

            // Check slug uniqueness excluding this record
            $existing = $this->page_model->get_by_slug($slug);
            if ($existing && $existing->id != $id) {
                $this->session->set_flashdata('error', 'Slug URL "' . $slug . '" sudah digunakan halaman lain.');
                redirect('admin/pages/edit/' . $id);
                return;
            }

            $page_data = [
                'title' => $title,
                'slug' => $slug,
                'icon' => $this->input->post('icon') ?: 'file-text',
                'content' => $this->input->post('content'),
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'sort_order' => (int)$this->input->post('sort_order'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            $this->page_model->update($id, $page_data);
            $this->session->set_flashdata('success', 'Halaman berhasil diperbarui!');
            redirect('admin/pages');
            return;
        }

        $data['page'] = $page;
        $data['title'] = 'Edit Halaman: ' . $page->title . ' - ShopVista';
        $data['active_menu'] = 'pages';
        $this->load->view('admin/pages/form', $data);
    }

    public function toggle($id = null) {
        if ($id) {
            $new_status = $this->page_model->toggle_active($id);
            $msg = $new_status ? 'Halaman berhasil diaktifkan.' : 'Halaman berhasil dinonaktifkan.';
            $this->session->set_flashdata('success', $msg);
        }
        redirect('admin/pages');
    }

    public function delete($id = null) {
        if ($id) {
            $this->page_model->delete($id);
            $this->session->set_flashdata('success', 'Halaman berhasil dihapus.');
        }
        redirect('admin/pages');
    }

    public function report() {
        $store_name = get_setting('store_name', 'ShopVista');
        $data['store_name'] = $store_name;
        $data['title'] = 'Laporan Halaman Statis & Informasi - ' . $store_name;
        $data['pages'] = $this->page_model->get_all();
        $this->load->view('admin/pages/report', $data);
    }

    public function export_excel() {
        $store_name = get_setting('store_name', 'ShopVista');
        $clean_store = preg_replace('/[^A-Za-z0-9_\-]/', '_', $store_name);
        $pages = $this->page_model->get_all();
        $filename = 'Laporan_Halaman_' . $clean_store . '_' . date('Ymd_His') . '.xls';

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="utf-8"><style>table{border-collapse:collapse;font-family:sans-serif;font-size:12px;} th{background:#2563EB;color:#fff;border:1px solid #94A3B8;padding:6px;} td{border:1px solid #CBD5E1;padding:6px;}</style></head><body>';
        echo '<h3>LAPORAN HALAMAN STATIS & INFORMASI TOKO - ' . strtoupper(htmlspecialchars($store_name)) . '</h3>';
        echo '<p>Tanggal Ekspor: ' . date('d/m/Y H:i') . ' WIB | Total: ' . count($pages) . ' Halaman</p>';
        echo '<table border="1">';
        echo '<thead><tr>';
        echo '<th>No</th><th>Judul Halaman</th><th>Slug / URL</th><th>Status Publikasi</th><th>Terakhir Diperbarui</th>';
        echo '</tr></thead><tbody>';

        $no = 1;
        foreach ($pages as $p) {
            echo '<tr>';
            echo '<td align="center">' . $no++ . '</td>';
            echo '<td>' . htmlspecialchars($p->title) . '</td>';
            echo '<td>' . htmlspecialchars($p->slug) . '</td>';
            echo '<td align="center">' . ($p->is_active ? 'Aktif' : 'Nonaktif') . '</td>';
            echo '<td>' . date('d/m/Y H:i', strtotime($p->updated_at ?: $p->created_at)) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></body></html>';
        exit;
    }
}
