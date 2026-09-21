<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead_model extends CI_Model {

    protected $table = 'leads';
    protected $log_table = 'lead_email_logs';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Subscribe an email address
     * Returns: ['status' => 'success'|'exists'|'reactivated'|'error', 'message' => '...']
     */
    public function subscribe($email, $source = 'newsletter_home', $name = null) {
        $email = strtolower(trim($email));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'status'  => 'error',
                'message' => 'Format alamat email tidak valid.'
            ];
        }

        $existing = $this->db->get_where($this->table, ['email' => $email])->row();

        if ($existing) {
            if ($existing->is_active == 1) {
                return [
                    'status'  => 'exists',
                    'message' => 'Email Anda sudah terdaftar sebagai penerima promo kami!'
                ];
            } else {
                $this->db->where('id', $existing->id);
                $this->db->update($this->table, [
                    'is_active'   => 1,
                    'source'      => $source,
                    'updated_at'  => date('Y-m-d H:i:s')
                ]);
                return [
                    'status'  => 'success',
                    'message' => 'Langganan promo Anda berhasil diaktifkan kembali. Selamat datang kembali!'
                ];
            }
        }

        $inserted = $this->db->insert($this->table, [
            'email'      => $email,
            'name'       => $name,
            'source'     => $source,
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if ($inserted) {
            return [
                'status'  => 'success',
                'message' => 'Terima kasih! Email berhasil didaftarkan untuk menerima info promo & produk terbaru.'
            ];
        }

        return [
            'status'  => 'error',
            'message' => 'Terjadi kesalahan saat menyimpan data. Silakan coba beberapa saat lagi.'
        ];
    }

    /**
     * Get all active leads
     */
    public function get_active_leads() {
        $this->db->where('is_active', 1);
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Count active leads
     */
    public function count_active() {
        $this->db->where('is_active', 1);
        return $this->db->count_all_results($this->table);
    }

    /**
     * Log email blast/notification
     */
    public function log_email($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->log_table, $data);
    }

    /**
     * Get email logs
     */
    public function get_logs($limit = 50, $offset = 0) {
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->log_table, $limit, $offset)->result();
    }
}
