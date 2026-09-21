<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get single product by ID
     */
    public function get($id) {
        return $this->db->get_where('products', ['id' => $id])->row();
    }

    /**
     * Get product by slug
     */
    public function get_by_slug($slug) {
        return $this->db->get_where('products', ['slug' => $slug, 'is_active' => 1])->row();
    }

    /**
     * Get all active products with optional filters
     */
    public function get_all($filters = [], $limit = 12, $offset = 0) {
        $this->db->select('products.*, categories.name as category_name, categories.slug as category_slug');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.is_active', 1);

        // Category filter
        if (!empty($filters['category_id'])) {
            $this->db->where('products.category_id', $filters['category_id']);
        }

        if (!empty($filters['category_slug'])) {
            $this->db->where('categories.slug', $filters['category_slug']);
        }

        // Search
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('products.name', $filters['search']);
            $this->db->or_like('products.description', $filters['search']);
            $this->db->or_like('products.short_desc', $filters['search']);
            $this->db->group_end();
        }

        // Price range
        if (!empty($filters['min_price'])) {
            $this->db->where('COALESCE(products.sale_price, products.price) >= ' . (float)$filters['min_price'], NULL, FALSE);
        }
        if (!empty($filters['max_price'])) {
            $this->db->where('COALESCE(products.sale_price, products.price) <= ' . (float)$filters['max_price'], NULL, FALSE);
        }

        // Sort
        $sort = !empty($filters['sort']) ? $filters['sort'] : 'newest';
        switch ($sort) {
            case 'price_low':
                $this->db->order_by('COALESCE(products.sale_price, products.price)', 'ASC', FALSE);
                break;
            case 'price_high':
                $this->db->order_by('COALESCE(products.sale_price, products.price)', 'DESC', FALSE);
                break;
            case 'popular':
                $this->db->order_by('products.views', 'DESC');
                break;
            case 'name':
                $this->db->order_by('products.name', 'ASC');
                break;
            default:
                $this->db->order_by('products.created_at', 'DESC');
        }

        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    /**
     * Count all products with filters
     */
    public function count_all($filters = []) {
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.is_active', 1);

        if (!empty($filters['category_id'])) {
            $this->db->where('products.category_id', $filters['category_id']);
        }
        if (!empty($filters['category_slug'])) {
            $this->db->where('categories.slug', $filters['category_slug']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('products.name', $filters['search']);
            $this->db->or_like('products.description', $filters['search']);
            $this->db->group_end();
        }

        // Price range
        if (!empty($filters['min_price'])) {
            $this->db->where('COALESCE(products.sale_price, products.price) >= ' . (float)$filters['min_price'], NULL, FALSE);
        }
        if (!empty($filters['max_price'])) {
            $this->db->where('COALESCE(products.sale_price, products.price) <= ' . (float)$filters['max_price'], NULL, FALSE);
        }

        return $this->db->count_all_results();
    }

    /**
     * Get featured products
     */
    public function get_featured($limit = 8) {
        $this->db->select('products.*, categories.name as category_name, categories.slug as category_slug');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.is_active', 1);
        $this->db->where('products.is_featured', 1);
        $this->db->order_by('products.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    /**
     * Get latest products
     */
    public function get_latest($limit = 8) {
        $this->db->select('products.*, categories.name as category_name, categories.slug as category_slug');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.is_active', 1);
        $this->db->order_by('products.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    /**
     * Get sale products
     */
    public function get_on_sale($limit = 8) {
        $this->db->select('products.*, categories.name as category_name, categories.slug as category_slug');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.is_active', 1);
        $this->db->where('products.sale_price IS NOT NULL');
        $this->db->where('products.sale_price >', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    /**
     * Get related products
     */
    public function get_related($category_id, $exclude_id, $limit = 4) {
        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.is_active', 1);
        $this->db->where('products.category_id', $category_id);
        $this->db->where('products.id !=', $exclude_id);
        $this->db->order_by('RAND()');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    /**
     * Increment views
     */
    public function increment_views($id) {
        $this->db->set('views', 'views + 1', FALSE);
        $this->db->where('id', $id);
        $this->db->update('products');
    }

    /**
     * Insert new product
     */
    public function insert($data) {
        $this->db->insert('products', $data);
        return $this->db->insert_id();
    }

    /**
     * Update product
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    /**
     * Delete product
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('products');
    }

    /**
     * Get all products for admin
     */
    public function get_admin_list($limit = 20, $offset = 0) {
        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->order_by('products.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_admin() {
        return $this->db->count_all('products');
    }

    public function get_average_rating($product_id) {
        $this->db->select_avg('rating');
        $this->db->select('COUNT(*) as count', FALSE);
        $this->db->where('product_id', $product_id);
        $this->db->where('is_approved', 1);
        $result = $this->db->get('reviews')->row();
        return $result;
    }

    /**
     * DataTables Server-Side Processing Query
     */
    public function get_datatables($start, $length, $search = '', $order_col = 0, $order_dir = 'DESC') {
        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('products.name', $search);
            $this->db->or_like('products.location', $search);
            $this->db->or_like('categories.name', $search);
            $this->db->group_end();
        }

        $columns = [
            0 => 'products.name',
            1 => 'products.price',
            2 => 'products.stock',
            3 => 'categories.name',
            4 => 'products.is_active',
            5 => 'products.id'
        ];

        if (isset($columns[$order_col])) {
            $this->db->order_by($columns[$order_col], $order_dir);
        } else {
            $this->db->order_by('products.id', 'DESC');
        }

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    /**
     * Count filtered records for DataTables
     */
    public function count_filtered($search = '') {
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('products.name', $search);
            $this->db->or_like('products.location', $search);
            $this->db->or_like('categories.name', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    /**
     * Get all product data for reporting
     */
    public function get_report_data($category_id = null, $is_active = null) {
        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        if (!empty($category_id)) {
            $this->db->where('products.category_id', (int)$category_id);
        }
        if ($is_active !== null && $is_active !== '') {
            $this->db->where('products.is_active', (int)$is_active);
        }
        $this->db->order_by('categories.name', 'ASC');
        $this->db->order_by('products.name', 'ASC');
        return $this->db->get()->result();
    }
}
