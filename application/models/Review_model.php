<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model {

    public function get_product_reviews($product_id, $limit = 10) {
        $this->db->select('reviews.*, users.name as user_name, users.avatar');
        $this->db->from('reviews');
        $this->db->join('users', 'users.id = reviews.user_id');
        $this->db->where('reviews.product_id', $product_id);
        $this->db->where('reviews.is_approved', 1);
        $this->db->order_by('reviews.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_average($product_id) {
        $this->db->select_avg('rating', 'avg_rating');
        $this->db->select('COUNT(*) as total_reviews', FALSE);
        $this->db->where('product_id', $product_id);
        $this->db->where('is_approved', 1);
        return $this->db->get('reviews')->row();
    }

    public function add($data) {
        $this->db->insert('reviews', $data);
        return $this->db->insert_id();
    }

    public function has_reviewed($product_id, $user_id) {
        $this->db->where('product_id', $product_id);
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results('reviews') > 0;
    }
}
