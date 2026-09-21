<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function get($id) {
        return $this->db->get_where('categories', ['id' => $id])->row();
    }

    public function get_by_slug($slug) {
        return $this->db->get_where('categories', ['slug' => $slug, 'is_active' => 1])->row();
    }

    public function get_all_active() {
        $this->db->where('is_active', 1);
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('categories')->result();
    }

    public function get_with_count() {
        $this->db->select('categories.*, COUNT(products.id) as product_count');
        $this->db->from('categories');
        $this->db->join('products', 'products.category_id = categories.id AND products.is_active = 1', 'left');
        $this->db->where('categories.is_active', 1);
        $this->db->group_by('categories.id');
        $this->db->order_by('categories.sort_order', 'ASC');
        return $this->db->get()->result();
    }

    public function get_admin_list() {
        $this->db->select('categories.*, COUNT(products.id) as product_count');
        $this->db->from('categories');
        $this->db->join('products', 'products.category_id = categories.id', 'left');
        $this->db->group_by('categories.id');
        $this->db->order_by('categories.sort_order', 'ASC');
        return $this->db->get()->result();
    }

    public function insert($data) {
        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }

    public function count_all() {
        return $this->db->count_all('categories');
    }

    /**
     * DataTables Server-Side Processing Query
     */
    public function get_datatables($start, $length, $search = '', $order_col = 0, $order_dir = 'ASC') {
        $this->db->select('categories.*, COUNT(products.id) as product_count');
        $this->db->from('categories');
        $this->db->join('products', 'products.category_id = categories.id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('categories.name', $search);
            $this->db->or_like('categories.slug', $search);
            $this->db->or_like('categories.description', $search);
            $this->db->group_end();
        }

        $this->db->group_by('categories.id');

        $columns = [
            0 => 'categories.name',
            1 => 'categories.slug',
            2 => 'categories.icon',
            3 => 'product_count',
            4 => 'categories.is_active',
            5 => 'categories.id'
        ];

        if (isset($columns[$order_col])) {
            $this->db->order_by($columns[$order_col], $order_dir);
        } else {
            $this->db->order_by('categories.sort_order', 'ASC');
        }

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    /**
     * Count filtered categories for DataTables
     */
    public function count_filtered($search = '') {
        $this->db->from('categories');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('categories.name', $search);
            $this->db->or_like('categories.slug', $search);
            $this->db->or_like('categories.description', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    /**
     * Get report data for categories
     */
    public function get_report_data() {
        $this->db->select('categories.*, COUNT(products.id) as product_count, COUNT(products.id) as total_products');
        $this->db->from('categories');
        $this->db->join('products', 'products.category_id = categories.id', 'left');
        $this->db->group_by('categories.id');
        $this->db->order_by('categories.sort_order', 'ASC');
        return $this->db->get()->result();
    }
}
