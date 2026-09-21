<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_model extends CI_Model {

    public function get($id) {
        return $this->db->get_where('banners', ['id' => $id])->row();
    }

    public function get_active() {
        $this->db->where('is_active', 1);
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('banners')->result();
    }

    public function get_all() {
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('banners')->result();
    }

    public function insert($data) {
        $this->db->insert('banners', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('banners', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('banners');
    }
}
