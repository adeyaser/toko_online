<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    public function get($id) {
        $this->db->select('orders.*, users.name as user_name, users.email as user_email');
        $this->db->from('orders');
        $this->db->join('users', 'users.id = orders.user_id', 'left');
        $this->db->where('orders.id', $id);
        return $this->db->get()->row();
    }

    public function get_by_order_number($order_number) {
        $this->db->select('orders.*, users.name as user_name, users.email as user_email');
        $this->db->from('orders');
        $this->db->join('users', 'users.id = orders.user_id', 'left');
        $this->db->where('orders.order_number', $order_number);
        return $this->db->get()->row();
    }

    public function get_items($order_id) {
        $this->db->select('order_items.*, products.slug as product_slug');
        $this->db->from('order_items');
        $this->db->join('products', 'products.id = order_items.product_id', 'left');
        $this->db->where('order_items.order_id', $order_id);
        return $this->db->get()->result();
    }

    public function create($order_data, $items) {
        $this->db->trans_begin();

        $this->db->insert('orders', $order_data);
        $order_id = $this->db->insert_id();

        foreach ($items as $item) {
            $item['order_id'] = $order_id;
            $this->db->insert('order_items', $item);

            // Reduce stock
            $this->db->set('stock', 'stock - ' . (int)$item['quantity'], FALSE);
            $this->db->where('id', $item['product_id']);
            $this->db->update('products');
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return $order_id;
    }

    public function get_user_orders($user_id, $limit = 10, $offset = 0) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get('orders')->result();
    }

    public function count_user_orders($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results('orders');
    }

    public function update_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('orders', ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('orders', $data);
    }

    public function upload_payment_proof($id, $filename) {
        $this->db->where('id', $id);
        return $this->db->update('orders', ['payment_proof' => $filename]);
    }

    // Admin functions
    public function get_all($limit = 20, $offset = 0, $status = '') {
        $this->db->select('orders.*, users.name as user_name');
        $this->db->from('orders');
        $this->db->join('users', 'users.id = orders.user_id', 'left');
        if ($status) {
            $this->db->where('orders.status', $status);
        }
        $this->db->order_by('orders.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_all($status = '') {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results('orders');
    }

    public function get_total_revenue() {
        $this->db->select_sum('grand_total');
        $this->db->where_in('status', ['processing', 'shipped', 'delivered']);
        $result = $this->db->get('orders')->row();
        return $result && $result->grand_total ? (float)$result->grand_total : 0;
    }

    public function get_monthly_revenue($months = 6) {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $this->db->select_sum('grand_total');
            $this->db->where_in('status', ['processing', 'shipped', 'delivered']);
            $this->db->where("DATE_FORMAT(created_at, '%Y-%m') =", $date);
            $result = $this->db->get('orders')->row();
            $data[] = [
                'month' => date('M', strtotime($date . '-01')),
                'total' => $result && $result->grand_total ? (float)$result->grand_total : 0
            ];
        }
        return $data;
    }

    public function get_recent($limit = 5) {
        $this->db->select('orders.*, users.name as user_name');
        $this->db->from('orders');
        $this->db->join('users', 'users.id = orders.user_id', 'left');
        $this->db->order_by('orders.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    /**
     * DataTables Server-Side Processing Query for Orders
     */
    public function get_datatables($start, $length, $search = '', $order_col = 6, $order_dir = 'DESC', $status = '', $start_date = '', $end_date = '') {
        $this->db->select('orders.*, users.name as user_name, users.email as user_email');
        $this->db->from('orders');
        $this->db->join('users', 'users.id = orders.user_id', 'left');

        if (!empty($status)) {
            $this->db->where('orders.status', $status);
        }

        if (!empty($start_date)) {
            $this->db->where('DATE(orders.created_at) >=', $start_date);
        }

        if (!empty($end_date)) {
            $this->db->where('DATE(orders.created_at) <=', $end_date);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('orders.order_number', $search);
            $this->db->or_like('users.name', $search);
            $this->db->or_like('orders.shipping_name', $search);
            $this->db->or_like('orders.shipping_courier', $search);
            $this->db->or_like('orders.tracking_number', $search);
            $this->db->or_like('orders.shipping_city', $search);
            $this->db->group_end();
        }

        $columns = [
            0 => 'orders.id',
            1 => 'orders.order_number',
            2 => 'users.name',
            3 => 'orders.shipping_courier',
            4 => 'orders.grand_total',
            5 => 'orders.status',
            6 => 'orders.created_at',
            7 => 'orders.id'
        ];

        if (isset($columns[$order_col])) {
            $this->db->order_by($columns[$order_col], $order_dir);
        } else {
            $this->db->order_by('orders.created_at', 'DESC');
        }

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    /**
     * Count filtered orders for DataTables
     */
    public function count_filtered($search = '', $status = '', $start_date = '', $end_date = '') {
        $this->db->from('orders');
        $this->db->join('users', 'users.id = orders.user_id', 'left');

        if (!empty($status)) {
            $this->db->where('orders.status', $status);
        }

        if (!empty($start_date)) {
            $this->db->where('DATE(orders.created_at) >=', $start_date);
        }

        if (!empty($end_date)) {
            $this->db->where('DATE(orders.created_at) <=', $end_date);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('orders.order_number', $search);
            $this->db->or_like('users.name', $search);
            $this->db->or_like('orders.shipping_name', $search);
            $this->db->or_like('orders.shipping_courier', $search);
            $this->db->or_like('orders.tracking_number', $search);
            $this->db->or_like('orders.shipping_city', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    /**
     * Get report orders with items and date/status filters
     */
    public function get_report_orders($start_date = '', $end_date = '', $status = '') {
        $this->db->select('orders.*, users.name as user_name, users.email as user_email');
        $this->db->from('orders');
        $this->db->join('users', 'users.id = orders.user_id', 'left');

        if (!empty($status)) {
            $this->db->where('orders.status', $status);
        }

        if (!empty($start_date)) {
            $this->db->where('DATE(orders.created_at) >=', $start_date);
        }

        if (!empty($end_date)) {
            $this->db->where('DATE(orders.created_at) <=', $end_date);
        }

        $this->db->order_by('orders.created_at', 'DESC');
        $orders = $this->db->get()->result();

        foreach ($orders as $order) {
            $order->items = $this->get_items($order->id);
        }

        return $orders;
    }
}
