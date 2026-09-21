<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model', 'user_model');
        $this->load->model('Order_model', 'order_model');
        $this->load->model('Cart_model', 'cart_model');
        $this->load->model('Category_model', 'category_model');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->user_model->get($user_id);
        $data['orders'] = $this->order_model->get_user_orders($user_id);
        $data['order_count'] = $this->order_model->count_user_orders($user_id);
        $data['cart_count'] = $this->cart_model->count_items();
        $data['categories'] = $this->category_model->get_all_active();
        
        // Handle profile update
        if ($this->input->method() === 'post') {
            $update_data = [
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'province' => $this->input->post('province'),
                'postal_code' => $this->input->post('postal_code')
            ];

            $password = $this->input->post('password');
            if ($password) {
                $update_data['password'] = $password;
            }

            // Handle avatar upload
            if (isset($_FILES['avatar']['name']) && !empty($_FILES['avatar']['name'])) {
                $upload_dir = './assets/images/avatars/';
                if (!is_dir($upload_dir)) {
                    @mkdir($upload_dir, 0777, true);
                }

                $config['upload_path'] = $upload_dir;
                $config['allowed_types'] = 'jpg|jpeg|png|webp';
                $config['max_size'] = 2048; // Max 2MB
                $config['file_name'] = 'avatar-' . $user_id . '-' . time();

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('avatar')) {
                    $upload_data = $this->upload->data();
                    $new_avatar = $upload_data['file_name'];

                    // Remove previous local avatar if exists
                    $current_user = $this->user_model->get($user_id);
                    if (!empty($current_user->avatar) && !filter_var($current_user->avatar, FILTER_VALIDATE_URL)) {
                        $old_avatar_path = FCPATH . 'assets/images/avatars/' . $current_user->avatar;
                        if (file_exists($old_avatar_path)) {
                            @unlink($old_avatar_path);
                        }
                    }

                    $update_data['avatar'] = $new_avatar;
                    $this->session->set_userdata('user_avatar', $new_avatar);
                } else {
                    $error_msg = $this->upload->display_errors('', '');
                    $this->session->set_flashdata('error', 'Gagal mengunggah foto profil: ' . $error_msg);
                    redirect('profil?tab=profile');
                    return;
                }
            }

            $this->user_model->update($user_id, $update_data);
            $this->session->set_userdata('user_name', $update_data['name']);
            $this->session->set_flashdata('success', 'Profil berhasil diperbarui!');
            redirect('profil?tab=profile');
            return;
        }

        $data['title'] = 'Profil Saya - ShopVista';
        $data['meta_description'] = 'Kelola profil dan pesanan Anda di ShopVista';
        $data['active_tab'] = $this->input->get('tab') ?: 'orders';

        $this->load->view('templates/header', $data);
        $this->load->view('profile/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function order_detail($order_number) {
        $user_id = $this->session->userdata('user_id');
        $order = $this->order_model->get_by_order_number($order_number);
        if (!$order || ($order->user_id != $user_id && $this->session->userdata('user_role') !== 'admin')) {
            show_404();
            return;
        }

        // Handle upload payment proof
        if ($this->input->method() === 'post' && isset($_FILES['payment_proof']['name']) && !empty($_FILES['payment_proof']['name'])) {
            $config['upload_path'] = './assets/images/payments/';
            $config['allowed_types'] = 'jpg|jpeg|png|webp|pdf';
            $config['max_size'] = 3072;
            $config['file_name'] = 'proof-' . $order->order_number . '-' . time();

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('payment_proof')) {
                $file_name = $this->upload->data('file_name');
                $this->order_model->upload_payment_proof($order->id, $file_name);

                if ($this->input->is_ajax_request()) {
                    $this->output->set_content_type('application/json')->set_output(json_encode([
                        'success' => true,
                        'file_name' => $file_name,
                        'file_url' => base_url('assets/images/payments/' . $file_name),
                        'message' => 'Bukti pembayaran berhasil diunggah! Tim kami akan memverifikasinya.'
                    ]));
                    return;
                }

                $this->session->set_flashdata('success', 'Bukti pembayaran berhasil diunggah! Tim kami akan memverifikasinya.');
                redirect('profil/pesanan/' . $order_number);
                return;
            } else {
                $error_msg = $this->upload->display_errors('', '');
                if ($this->input->is_ajax_request()) {
                    $this->output->set_content_type('application/json')->set_output(json_encode([
                        'success' => false,
                        'message' => $error_msg
                    ]));
                    return;
                }
                $this->session->set_flashdata('error', $error_msg);
            }
        }

        $data['order'] = $order;
        $data['order_items'] = $this->order_model->get_items($order->id);
        $data['cart_count'] = $this->cart_model->count_items();
        $data['categories'] = $this->category_model->get_all_active();
        $data['user'] = $this->user_model->get($user_id);

        // Fetch available couriers if order is still pending payment
        $this->load->model('Product_model', 'product_model');
        $this->load->model('Shipping_model', 'shipping_model');

        $total_weight = 0;
        foreach ($data['order_items'] as $item) {
            $prod = $this->product_model->get($item->product_id);
            $w = ($prod && isset($prod->weight) && (float)$prod->weight > 0) ? (float)$prod->weight : 250;
            $total_weight += ($w * (int)$item->quantity);
        }
        if ($total_weight <= 0) $total_weight = 1000;
        $data['total_weight'] = $total_weight;

        if ($order->status === 'pending') {
            $data['available_couriers'] = $this->shipping_model->get_courier_options(
                $order->shipping_city,
                $total_weight,
                $order->total,
                $order->shipping_province
            );
        } else {
            $data['available_couriers'] = [];
        }
        
        $data['title'] = 'Pesanan ' . $order_number . ' - ShopVista';
        $data['meta_description'] = 'Detail pesanan ' . $order_number;

        $this->load->view('templates/header', $data);
        $this->load->view('profile/order_detail', $data);
        $this->load->view('templates/footer', $data);
    }

    public function update_shipping($order_number) {
        $user_id = $this->session->userdata('user_id');
        $order = $this->order_model->get_by_order_number($order_number);
        if (!$order || ($order->user_id != $user_id && $this->session->userdata('user_role') !== 'admin')) {
            show_404();
            return;
        }

        if ($order->status !== 'pending') {
            $this->session->set_flashdata('error', 'Layanan ekspedisi tidak dapat diubah karena pesanan sudah diproses atau telah dibayar.');
            redirect('profil/pesanan/' . $order_number);
            return;
        }

        $this->load->model('Product_model', 'product_model');
        $this->load->model('Shipping_model', 'shipping_model');

        $order_items = $this->order_model->get_items($order->id);
        $total_weight = 0;
        foreach ($order_items as $item) {
            $prod = $this->product_model->get($item->product_id);
            $w = ($prod && isset($prod->weight) && (float)$prod->weight > 0) ? (float)$prod->weight : 250;
            $total_weight += ($w * (int)$item->quantity);
        }
        if ($total_weight <= 0) $total_weight = 1000;

        $available_couriers = $this->shipping_model->get_courier_options(
            $order->shipping_city,
            $total_weight,
            $order->total,
            $order->shipping_province
        );

        $selected_code = $this->input->post('shipping_courier_code');
        $matched = null;
        foreach ($available_couriers as $courier) {
            if ($courier['code'] === $selected_code) {
                $matched = $courier;
                break;
            }
        }

        if ($matched) {
            $new_courier_name = $matched['courier_name'] . ' (' . $matched['service_name'] . ')';
            $new_shipping_cost = (float)$matched['cost'];
            $new_grand_total = (float)$order->total + $new_shipping_cost;

            $this->order_model->update($order->id, [
                'shipping_courier' => $new_courier_name,
                'shipping_cost' => $new_shipping_cost,
                'grand_total' => $new_grand_total,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->session->set_flashdata('success', 'Layanan ekspedisi berhasil diubah ke ' . $new_courier_name . '. Total pembayaran telah diperbarui menjadi ' . rupiah($new_grand_total) . '.');
        } else {
            $this->session->set_flashdata('error', 'Pilihan layanan ekspedisi tidak valid atau tidak tersedia.');
        }

        redirect('profil/pesanan/' . $order_number);
    }

    public function check_resi($order_number) {
        $user_id = $this->session->userdata('user_id');
        $order = $this->order_model->get_by_order_number($order_number);
        if (!$order || ($order->user_id != $user_id && $this->session->userdata('user_role') !== 'admin')) {
            $this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode([
                'success' => false,
                'message' => 'Akses ditolak.'
            ]));
            return;
        }

        if (empty($order->tracking_number)) {
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'success' => false,
                'message' => 'Nomor resi belum diinput oleh pihak toko.'
            ]));
            return;
        }

        $this->load->model('Tracking_model', 'tracking_model');
        $courier = !empty($order->shipping_courier) ? $order->shipping_courier : 'ShopVista Express';
        $tracking_result = $this->tracking_model->track($courier, $order->tracking_number, $order);

        $this->output->set_content_type('application/json')->set_output(json_encode($tracking_result));
    }

    public function invoice($order_number) {
        $user_id = $this->session->userdata('user_id');
        $order = $this->order_model->get_by_order_number($order_number);
        if (!$order || ($order->user_id != $user_id && $this->session->userdata('user_role') !== 'admin')) {
            show_404();
            return;
        }

        $order->items = $this->order_model->get_items($order->id);
        $data['order'] = $order;
        $data['title'] = 'Invoice ' . $order->order_number . ' (PDF) - ShopVista';
        $data['auto_download'] = ($this->input->get('download') == '1');

        $this->load->view('profile/invoice', $data);
    }
}

