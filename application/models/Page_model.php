<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($id) {
        return $this->db->get_where('pages', ['id' => (int)$id])->row();
    }

    public function get_by_slug($slug) {
        return $this->db->get_where('pages', ['slug' => $slug])->row();
    }

    public function get_active_by_slug($slug) {
        return $this->db->get_where('pages', ['slug' => $slug, 'is_active' => 1])->row();
    }

    public function get_all() {
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get('pages')->result();
    }

    public function get_all_active() {
        $this->db->where('is_active', 1);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get('pages')->result();
    }

    public function create($data) {
        if (empty($data['slug'])) {
            $data['slug'] = strtolower(url_title($data['title'], 'dash', TRUE));
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert('pages', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = strtolower(url_title($data['title'], 'dash', TRUE));
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', (int)$id);
        return $this->db->update('pages', $data);
    }

    public function delete($id) {
        $this->db->where('id', (int)$id);
        return $this->db->delete('pages');
    }

    public function toggle_active($id) {
        $page = $this->get($id);
        if ($page) {
            $new_status = $page->is_active ? 0 : 1;
            $this->db->where('id', (int)$id);
            $this->db->update('pages', [
                'is_active' => $new_status,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return $new_status;
        }
        return false;
    }

    /**
     * DataTables Server-Side Processing Query
     */
    public function get_datatables($start, $length, $search = '', $order_col = 0, $order_dir = 'ASC') {
        $this->db->from('pages');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('title', $search);
            $this->db->or_like('slug', $search);
            $this->db->or_like('content', $search);
            $this->db->group_end();
        }

        $columns = [
            0 => 'sort_order',
            1 => 'title',
            2 => 'slug',
            3 => 'icon',
            4 => 'is_active',
            5 => 'updated_at',
            6 => 'id'
        ];

        $order_by = isset($columns[$order_col]) ? $columns[$order_col] : 'sort_order';
        $this->db->order_by($order_by, $order_dir);

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    public function count_all() {
        return $this->db->count_all('pages');
    }

    public function count_filtered($search = '') {
        $this->db->from('pages');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('title', $search);
            $this->db->or_like('slug', $search);
            $this->db->or_like('content', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }
}
