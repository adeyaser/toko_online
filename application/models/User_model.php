<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function get($id) {
        return $this->db->get_where('users', ['id' => $id])->row();
    }

    public function get_by_email($email) {
        return $this->db->get_where('users', ['email' => $email])->row();
    }

    public function register($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function login($email, $password) {
        $user = $this->get_by_email($email);
        if ($user && password_verify($password, $user->password)) {
            if ($user->is_active) {
                return $user;
            }
        }
        return false;
    }

    public function update($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function get_all($limit = 20, $offset = 0) {
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get('users')->result();
    }

    public function count_all() {
        return $this->db->count_all('users');
    }

    public function count_customers() {
        $this->db->where('role', 'customer');
        return $this->db->count_all_results('users');
    }

    public function toggle_active($id) {
        $user = $this->get($id);
        if ($user) {
            $new_status = $user->is_active ? 0 : 1;
            $this->db->where('id', $id);
            $this->db->update('users', ['is_active' => $new_status]);
            return $new_status;
        }
        return false;
    }

    /**
     * DataTables Server-Side Processing Query for Users
     */
    public function get_datatables($start, $length, $search = '', $order_col = 5, $order_dir = 'DESC', $role = '') {
        $this->db->from('users');

        if (!empty($role)) {
            $this->db->where('role', $role);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('city', $search);
            $this->db->group_end();
        }

        $columns = [
            0 => 'name',
            1 => 'email',
            2 => 'phone',
            3 => 'role',
            4 => 'is_active',
            5 => 'created_at',
            6 => 'id'
        ];

        if (isset($columns[$order_col])) {
            $this->db->order_by($columns[$order_col], $order_dir);
        } else {
            $this->db->order_by('created_at', 'DESC');
        }

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    public function count_filtered($search = '', $role = '') {
        $this->db->from('users');

        if (!empty($role)) {
            $this->db->where('role', $role);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('city', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_report_data($role = '', $is_active = '') {
        $this->db->from('users');

        if (!empty($role)) {
            $this->db->where('role', $role);
        }

        if ($is_active !== '') {
            $this->db->where('is_active', (int)$is_active);
        }

        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result();
    }
}

