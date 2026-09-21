<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model {

    private $cache = [];

    public function get($key, $default = '') {
        if (isset($this->cache[$key]) && $this->cache[$key] !== '' && $this->cache[$key] !== null) {
            return $this->cache[$key];
        }
        
        $row = $this->db->get_where('settings', ['setting_key' => $key])->row();
        if ($row && $row->setting_value !== null && trim($row->setting_value) !== '') {
            $this->cache[$key] = $row->setting_value;
            return $row->setting_value;
        }
        return $default;
    }

    public function set($key, $value) {
        $existing = $this->db->get_where('settings', ['setting_key' => $key])->row();
        
        if ($existing) {
            $this->db->where('setting_key', $key);
            $this->db->update('settings', ['setting_value' => $value]);
        } else {
            $this->db->insert('settings', ['setting_key' => $key, 'setting_value' => $value]);
        }
        
        $this->cache[$key] = $value;
    }

    public function get_all() {
        $settings = [];
        $rows = $this->db->get('settings')->result();
        foreach ($rows as $row) {
            $settings[$row->setting_key] = $row->setting_value;
            $this->cache[$row->setting_key] = $row->setting_value;
        }
        return $settings;
    }

    public function save_multiple($data) {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }
}
