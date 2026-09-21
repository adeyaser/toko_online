<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Lead_model', 'lead_model');
    }

    /**
     * Handle newsletter subscription via AJAX or POST
     */
    public function subscribe() {
        $email = trim($this->input->post('email'));
        $name = trim($this->input->post('name'));
        $source = $this->input->post('source') ?: 'newsletter_home';

        if (empty($email)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => 'error',
                    'message' => 'Silakan masukkan alamat email Anda.'
                ]));
            return;
        }

        // Verify Cloudflare Turnstile if enabled
        if (turnstile_enabled() && !verify_turnstile()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => 'error',
                    'message' => 'Verifikasi keamanan Cloudflare Turnstile gagal. Silakan coba lagi.'
                ]));
            return;
        }

        $result = $this->lead_model->subscribe($email, $source, $name);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}
