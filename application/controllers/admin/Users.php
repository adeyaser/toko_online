<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('user_role') !== 'admin') {
            redirect('login');
        }
        $this->load->model('User_model', 'user_model');
    }

    public function index() {
        $data['title'] = 'Kelola Pengguna - ShopVista';
        $data['active_menu'] = 'users';
        $this->load->view('admin/users/list', $data);
    }

    public function ajax_list() {
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $searchPost = $this->input->post('search');
        $search = isset($searchPost['value']) ? trim($searchPost['value']) : '';
        $orderPost = $this->input->post('order');
        $order_col = isset($orderPost[0]['column']) ? intval($orderPost[0]['column']) : 5;
        $order_dir = isset($orderPost[0]['dir']) ? $orderPost[0]['dir'] : 'DESC';

        $users = $this->user_model->get_datatables($start, $length, $search, $order_col, $order_dir);
        $recordsTotal = $this->user_model->count_all();
        $recordsFiltered = $this->user_model->count_filtered($search);

        $data = [];
        foreach ($users as $user) {
            $row = [];
            // Col 0: Nama
            $row[] = '<div style="font-weight:600;color:var(--text-white);">' . htmlspecialchars($user->name) . '</div>';

            // Col 1: Email
            $row[] = '<span style="color:#94A3B8;">' . htmlspecialchars($user->email) . '</span>';

            // Col 2: Telepon
            $row[] = htmlspecialchars($user->phone ?: '-');

            // Col 3: Role
            $roleBadgeClass = $user->role === 'admin' ? 'badge-primary' : 'badge-info';
            $row[] = '<span class="badge ' . $roleBadgeClass . '">' . ucfirst(htmlspecialchars($user->role)) . '</span>';

            // Col 4: Status
            $statusBadge = $user->is_active 
                ? '<span class="badge badge-success">Aktif</span>' 
                : '<span class="badge badge-danger">Nonaktif</span>';
            $row[] = $statusBadge;

            // Col 5: Terdaftar
            $row[] = '<span style="color:var(--text-muted);font-size:0.85rem;">' . date('d M Y', strtotime($user->created_at)) . '</span>';

            // Col 6: Aksi
            if ($user->role !== 'admin') {
                $toggleText = $user->is_active ? 'Nonaktifkan' : 'Aktifkan';
                $row[] = '<a href="' . base_url('admin/users/toggle/' . $user->id) . '" class="btn btn-sm btn-secondary" onclick="return confirm(\'Ubah status pengguna ini?\')">' . $toggleText . '</a>';
            } else {
                $row[] = '<span style="color:var(--text-muted);font-size:0.8rem;font-style:italic;">Super Admin</span>';
            }

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
        $role = $this->input->get('role') ?: '';
        $is_active = $this->input->get('is_active');
        if ($is_active === null || $is_active === '') {
            $is_active = '';
        }

        $store_name = get_setting('store_name', 'ShopVista');
        $data['users'] = $this->user_model->get_report_data($role, $is_active);
        $data['role_filter'] = $role;
        $data['status_filter'] = $is_active;
        $data['store_name'] = $store_name;
        $data['title'] = 'Laporan Data Pengguna - ' . $store_name;

        $this->load->view('admin/users/report', $data);
    }

    public function export_excel() {
        $role = $this->input->get('role') ?: '';
        $is_active = $this->input->get('is_active');
        if ($is_active === null || $is_active === '') {
            $is_active = '';
        }

        $store_name = get_setting('store_name', 'ShopVista');
        $clean_store = preg_replace('/[^A-Za-z0-9_\-]/', '_', $store_name);
        $users = $this->user_model->get_report_data($role, $is_active);
        $filename = 'Laporan_Pengguna_' . $clean_store . '_' . date('Ymd_His') . '.xls';

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="utf-8"><style>table{border-collapse:collapse;font-family:sans-serif;font-size:12px;} th{background:#2563EB;color:#fff;border:1px solid #94A3B8;padding:6px;} td{border:1px solid #CBD5E1;padding:6px;}</style></head><body>';
        echo '<h3>LAPORAN DATA PENGGUNA - ' . strtoupper(htmlspecialchars($store_name)) . '</h3>';
        echo '<p>Tanggal Ekspor: ' . date('d/m/Y H:i') . ' WIB | Total: ' . count($users) . ' Pengguna</p>';
        echo '<table border="1">';
        echo '<thead><tr>';
        echo '<th>No</th><th>Nama Lengkap</th><th>Email</th><th>No. Telepon</th><th>Role</th><th>Status Akun</th><th>Tanggal Terdaftar</th>';
        echo '</tr></thead><tbody>';

        $no = 1;
        foreach ($users as $u) {
            echo '<tr>';
            echo '<td align="center">' . $no++ . '</td>';
            echo '<td>' . htmlspecialchars($u->name) . '</td>';
            echo '<td>' . htmlspecialchars($u->email) . '</td>';
            echo '<td style="mso-number-format:\'\@\';">' . ($u->phone ?: '-') . '</td>';
            echo '<td align="center">' . strtoupper($u->role) . '</td>';
            echo '<td align="center">' . ($u->is_active ? 'Aktif' : 'Nonaktif') . '</td>';
            echo '<td>' . date('d/m/Y H:i', strtotime($u->created_at)) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></body></html>';
        exit;
    }

    public function toggle($id) {
        $this->user_model->toggle_active($id);
        $this->session->set_flashdata('success', 'Status pengguna berhasil diubah!');
        redirect('admin/users');
    }
}

