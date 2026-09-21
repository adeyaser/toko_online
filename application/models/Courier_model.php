<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courier_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($id) {
        return $this->db->get_where('couriers', ['id' => (int)$id])->row();
    }

    public function get_by_code($code) {
        return $this->db->get_where('couriers', ['code' => $code])->row();
    }

    public function get_all() {
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get('couriers')->result();
    }

    public function get_all_active() {
        $this->db->where('is_active', 1);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get('couriers')->result();
    }

    public function create($data) {
        if (empty($data['code'])) {
            $data['code'] = strtolower(url_title($data['courier_name'] . '-' . $data['service_name'], 'underscore', TRUE));
        }
        $this->db->insert('couriers', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', (int)$id);
        return $this->db->update('couriers', $data);
    }

    public function delete($id) {
        $this->db->where('id', (int)$id);
        return $this->db->delete('couriers');
    }

    public function toggle_active($id) {
        $courier = $this->get($id);
        if ($courier) {
            $new_status = $courier->is_active ? 0 : 1;
            $this->db->where('id', (int)$id);
            $this->db->update('couriers', ['is_active' => $new_status]);
            return $new_status;
        }
        return false;
    }

    /**
     * DataTables Server-Side Processing Query
     */
    public function get_datatables($start, $length, $search = '', $order_col = 0, $order_dir = 'ASC') {
        $this->db->from('couriers');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('courier_name', $search);
            $this->db->or_like('service_name', $search);
            $this->db->or_like('description', $search);
            $this->db->or_like('badge', $search);
            $this->db->or_like('etd', $search);
            $this->db->group_end();
        }

        $columns = [
            0 => 'sort_order',
            1 => 'courier_name',
            2 => 'service_name',
            3 => 'cost',
            4 => 'etd',
            5 => 'is_free_eligible',
            6 => 'is_active',
            7 => 'id'
        ];

        if (isset($columns[$order_col])) {
            $this->db->order_by($columns[$order_col], $order_dir);
        } else {
            $this->db->order_by('sort_order', 'ASC');
        }

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    public function count_all() {
        return $this->db->count_all('couriers');
    }

    public function count_filtered($search = '') {
        $this->db->from('couriers');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('courier_name', $search);
            $this->db->or_like('service_name', $search);
            $this->db->or_like('description', $search);
            $this->db->or_like('badge', $search);
            $this->db->or_like('etd', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_report_data() {
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('couriers')->result();
    }
}
