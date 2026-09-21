<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('user_role') !== 'admin') {
            redirect('login');
        }
        $this->load->model('Setting_model', 'setting_model');
    }

    public function index() {
        if ($this->input->method() === 'post') {
            $settings = [
                'store_name' => $this->input->post('store_name'),
                'store_tagline' => $this->input->post('store_tagline'),
                'store_email' => $this->input->post('store_email'),
                'store_phone' => $this->input->post('store_phone'),
                'store_address' => $this->input->post('store_address'),
                'free_shipping_min' => $this->input->post('free_shipping_min'),
                'shipping_cost' => $this->input->post('shipping_cost'),
                'shipping_mode' => $this->input->post('shipping_mode') ?: 'hybrid',
                'rajaongkir_api_key' => $this->input->post('rajaongkir_api_key'),
                'rajaongkir_origin' => $this->input->post('rajaongkir_origin'),
                'rapidapi_key' => $this->input->post('rapidapi_key'),
                'rapidapi_host' => $this->input->post('rapidapi_host'),
                'bank_name' => $this->input->post('bank_name'),
                'bank_account' => $this->input->post('bank_account'),
                'bank_holder' => $this->input->post('bank_holder'),
                'whatsapp' => $this->input->post('whatsapp'),
                'whatsapp_enabled' => $this->input->post('whatsapp_enabled') !== null ? $this->input->post('whatsapp_enabled') : '1',
                'whatsapp_cs_name' => $this->input->post('whatsapp_cs_name') ?: 'Customer Service ShopVista',
                'whatsapp_cs_status' => $this->input->post('whatsapp_cs_status') ?: 'Online • Siap Melayani',
                'whatsapp_message' => $this->input->post('whatsapp_message') ?: 'Halo ShopVista, saya tertarik untuk bertanya seputar produk/pesanan saya...',
                'whatsapp_position' => $this->input->post('whatsapp_position') ?: 'bottom-right',
                'instagram' => $this->input->post('instagram'),
                'facebook' => $this->input->post('facebook'),
                'tiktok' => $this->input->post('tiktok'),
                'shopee' => $this->input->post('shopee'),
                'lazada' => $this->input->post('lazada'),
                'tokopedia' => $this->input->post('tokopedia'),
                'smtp_host' => $this->input->post('smtp_host'),
                'smtp_port' => $this->input->post('smtp_port'),
                'smtp_user' => $this->input->post('smtp_user'),
                'smtp_pass' => $this->input->post('smtp_pass'),
                'smtp_crypto' => $this->input->post('smtp_crypto'),
                'smtp_from_name' => $this->input->post('smtp_from_name'),
                'smtp_from_email' => $this->input->post('smtp_from_email'),
                'turnstile_enabled' => $this->input->post('turnstile_enabled') !== null ? $this->input->post('turnstile_enabled') : '0',
                'turnstile_mode' => $this->input->post('turnstile_mode') ?: 'managed',
                'turnstile_preclearance' => $this->input->post('turnstile_preclearance') ? '1' : '0',
                'turnstile_site_key' => trim($this->input->post('turnstile_site_key')),
                'turnstile_secret_key' => trim($this->input->post('turnstile_secret_key')),
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'meta_keywords' => $this->input->post('meta_keywords')
            ];

            // Handle store logo upload
            if (!empty($_FILES['store_logo']['name'])) {
                $upload_dir = './assets/images/logo/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $config['upload_path'] = $upload_dir;
                $config['allowed_types'] = 'jpg|jpeg|png|webp|svg';
                $config['max_size'] = 3072;
                $config['file_name'] = 'logo_' . time();
                $config['overwrite'] = true;

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('store_logo')) {
                    $upload_data = $this->upload->data();
                    $settings['store_logo'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', 'Upload logo gagal: ' . $this->upload->display_errors('', ''));
                }
            } elseif ($this->input->post('remove_logo') == '1') {
                $settings['store_logo'] = '';
            }

            $this->setting_model->save_multiple($settings);
            $this->session->set_flashdata('success', 'Pengaturan berhasil disimpan!');
            redirect('admin/settings');

        }

        $data['settings'] = $this->setting_model->get_all();
        $data['title'] = 'Pengaturan Toko - ShopVista';
        $data['active_menu'] = 'settings';
        $this->load->view('admin/settings', $data);
    }

    public function test_rajaongkir() {
        if ($this->session->userdata('user_role') !== 'admin') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Akses ditolak. Silakan login sebagai admin.']));
            return;
        }

        $api_key = $this->input->post('api_key') ?: get_setting('rajaongkir_api_key', '');
        if (empty($api_key)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'API Key RajaOngkir masih kosong.']));
            return;
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=Jakarta",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 6,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => ["key: " . $api_key]
        ]);
        $res = curl_exec($curl);
        $err = curl_error($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($err) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Gagal terhubung: ' . $err]));
            return;
        }

        $json = json_decode($res, true);
        if ($http_code === 200 && !empty($json['data'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Koneksi API RajaOngkir berhasil terhubung! Akun aktif & siap digunakan.'
                ]));
        } else {
            $msg = $json['meta']['message'] ?? 'API Key tidak valid atau kuota habis.';
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Error: ' . $msg]));
        }
    }

    public function test_smtp() {
        if ($this->session->userdata('user_role') !== 'admin') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Akses ditolak. Silakan login sebagai admin.']));
            return;
        }

        $recipient = trim($this->input->post('recipient'));
        $host = trim($this->input->post('smtp_host')) ?: get_setting('smtp_host', 'smtp.gmail.com');
        $port = intval($this->input->post('smtp_port')) ?: intval(get_setting('smtp_port', '587'));
        $user = trim($this->input->post('smtp_user')) ?: get_setting('smtp_user', '');
        $pass = trim($this->input->post('smtp_pass')) ?: get_setting('smtp_pass', '');
        $crypto = $this->input->post('smtp_crypto') ?: get_setting('smtp_crypto', 'tls');
        $from_name = trim($this->input->post('smtp_from_name')) ?: get_setting('smtp_from_name', get_setting('store_name', 'ShopVista Store'));
        $from_email = trim($this->input->post('smtp_from_email')) ?: get_setting('smtp_from_email', get_setting('store_email', 'noreply@shopvista.com'));

        if (empty($recipient) || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Alamat email penerima tidak valid.']));
            return;
        }

        if (empty($user) || empty($pass)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'SMTP Username dan Password harus diisi terlebih dahulu.']));
            return;
        }

        $config = [
            'protocol'    => 'smtp',
            'smtp_host'   => $host,
            'smtp_port'   => $port,
            'smtp_user'   => $user,
            'smtp_pass'   => $pass,
            'smtp_crypto' => ($crypto !== 'none') ? $crypto : '',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'crlf'        => "\r\n",
            'smtp_timeout'=> 10
        ];

        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->from($from_email, $from_name);
        $this->email->to($recipient);
        $this->email->subject('[Uji Coba SMTP] ' . $from_name . ' Berhasil Terhubung');
        $this->email->message('
            <div style="font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #E2E8F0; border-radius: 10px; background: #FFFFFF;">
                <h2 style="color: #4F46E5; margin-top: 0;">Koneksi SMTP Berhasil! 🎉</h2>
                <p style="color: #334155; line-height: 1.6;">Ini adalah email pengujian otomatis dari <strong>' . htmlspecialchars($from_name) . '</strong>.</p>
                <div style="background: #F8FAFC; padding: 12px; border-radius: 8px; font-size: 13px; color: #64748B;">
                    <strong>Host:</strong> ' . htmlspecialchars($host) . ':' . $port . '<br>
                    <strong>Enkripsi:</strong> ' . strtoupper($crypto) . '<br>
                    <strong>Pengirim:</strong> ' . htmlspecialchars($from_email) . '
                </div>
                <p style="color: #10B981; font-weight: bold; margin-top: 15px;">Konfigurasi email Anda siap digunakan untuk mengirim notifikasi produk ke pelanggan (leads).</p>
            </div>
        ');

        if ($this->email->send()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Email uji coba berhasil dikirim ke ' . htmlspecialchars($recipient) . '! Konfigurasi SMTP Anda aktif dan valid.'
                ]));
        } else {
            $error_info = strip_tags($this->email->print_debugger(['headers', 'subject', 'body']));
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Gagal mengirim email: ' . (substr($error_info, 0, 200) ?: 'Periksa kembali host, port, username, atau app password Anda.')
                ]));
        }
    }
}
