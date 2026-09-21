<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Cart_model', 'cart_model');
        $this->load->model('Order_model', 'order_model');
        $this->load->model('Product_model', 'product_model');
        $this->load->model('User_model', 'user_model');
        $this->load->model('Category_model', 'category_model');
        $this->load->model('Shipping_model', 'shipping_model');
    }

    public function index() {
        // Must be logged in
        if (!$this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', 'Silakan login terlebih dahulu untuk checkout.');
            redirect('login');
            return;
        }

        $cart_items = $this->cart_model->get_items();
        if (empty($cart_items)) {
            redirect('keranjang');
            return;
        }

        $user = $this->user_model->get($this->session->userdata('user_id'));

        $data['cart_items'] = $cart_items;
        $data['cart_total'] = $this->cart_model->get_total();
        $data['cart_count'] = $this->cart_model->count_items();

        // Calculate total weight (in grams)
        $total_weight = 0;
        foreach ($cart_items as $item) {
            $w = isset($item->weight) && (float)$item->weight > 0 ? (float)$item->weight : 250;
            $total_weight += ($w * $item->quantity);
        }
        $data['total_weight'] = $total_weight;

        // Fetch shipping courier options
        $city = !empty($user->city) ? $user->city : 'Jakarta';
        $shipping_options = $this->shipping_model->get_courier_options($city, $total_weight, $data['cart_total']);
        $data['shipping_options'] = $shipping_options;

        // Default selected shipping option is the first one
        $default_shipping = !empty($shipping_options) ? $shipping_options[0] : null;
        $data['selected_shipping_code'] = $default_shipping ? $default_shipping['code'] : 'shopvista_std';
        $data['shipping_cost'] = $default_shipping ? (float)$default_shipping['cost'] : 15000;
        $data['shipping_courier_name'] = $default_shipping ? $default_shipping['courier_name'] . ' (' . $default_shipping['service_name'] . ')' : 'ShopVista Express - Standar';

        $data['grand_total'] = $data['cart_total'] + $data['shipping_cost'];
        $data['user'] = $user;
        $data['title'] = 'Checkout - ShopVista';
        $data['meta_description'] = 'Selesaikan pesanan Anda di ShopVista';
        $data['categories'] = $this->category_model->get_all_active();

        // Bank info from store settings
        $data['bank_name'] = get_setting('bank_name', 'Bank BCA');
        $data['bank_account'] = get_setting('bank_account', '123-456-7890');
        $data['bank_holder'] = get_setting('bank_holder', 'PT ShopVista Indonesia');

        $this->load->view('templates/header', $data);
        $this->load->view('checkout/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function get_provinces() {
        $cache_file = sys_get_temp_dir() . '/wilayah_provinces.json';
        if (file_exists($cache_file) && (time() - filemtime($cache_file) < 86400)) {
            $json = file_get_contents($cache_file);
        } else {
            $curl = curl_init('https://wilayah.id/api/provinces.json');
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 6,
                CURLOPT_SSL_VERIFYPEER => false
            ]);
            $json = curl_exec($curl);
            curl_close($curl);
            if ($json) {
                file_put_contents($cache_file, $json);
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output($json ?: json_encode(['data' => []]));
    }

    public function get_regencies($province_code = '') {
        $province_code = preg_replace('/[^0-9]/', '', $province_code);
        if (empty($province_code)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['data' => []]));
            return;
        }

        $cache_file = sys_get_temp_dir() . '/wilayah_regencies_' . $province_code . '.json';
        if (file_exists($cache_file) && (time() - filemtime($cache_file) < 86400)) {
            $json = file_get_contents($cache_file);
        } else {
            $curl = curl_init('https://wilayah.id/api/regencies/' . $province_code . '.json');
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 6,
                CURLOPT_SSL_VERIFYPEER => false
            ]);
            $json = curl_exec($curl);
            curl_close($curl);
            if ($json) {
                file_put_contents($cache_file, $json);
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output($json ?: json_encode(['data' => []]));
    }

    public function get_shipping_rates() {
        $city = trim($this->input->post('city')) ?: 'Jakarta';
        $province = trim($this->input->post('province')) ?: '';
        $cart_total = (float)$this->cart_model->get_total();
        $weight = 0;
        $cart_items = $this->cart_model->get_items();
        foreach ($cart_items as $item) {
            $w = isset($item->weight) && (float)$item->weight > 0 ? (float)$item->weight : 250;
            $weight += ($w * $item->quantity);
        }
        if ($weight <= 0) $weight = 1000;

        $options = $this->shipping_model->get_courier_options($city, $weight, $cart_total, $province);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'options' => $options
            ]));
    }

    public function process() {
        if (!$this->session->userdata('user_id')) {
            redirect('login');
            return;
        }

        $cart_items = $this->cart_model->get_items();
        if (empty($cart_items)) {
            redirect('keranjang');
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $cart_total = $this->cart_model->get_total();

        // Calculate total weight
        $total_weight = 0;
        foreach ($cart_items as $item) {
            $w = isset($item->weight) && (float)$item->weight > 0 ? (float)$item->weight : 250;
            $total_weight += ($w * $item->quantity);
        }

        // Get shipping options to validate selected courier
        $city = $this->input->post('shipping_city');
        $available_options = $this->shipping_model->get_courier_options($city, $total_weight, $cart_total);

        $selected_code = $this->input->post('shipping_option_code');
        $shipping_courier = 'ShopVista Express';
        $shipping_cost = (float) get_setting('shipping_cost', 15000);

        // Match selected courier from available options
        $matched = false;
        foreach ($available_options as $opt) {
            if ($opt['code'] === $selected_code) {
                $shipping_courier = $opt['courier_name'] . ' (' . $opt['service_name'] . ')';
                $shipping_cost = (float)$opt['cost'];
                $matched = true;
                break;
            }
        }

        // Fallback if not matched by code
        if (!$matched && !empty($available_options)) {
            $shipping_courier = $available_options[0]['courier_name'] . ' (' . $available_options[0]['service_name'] . ')';
            $shipping_cost = (float)$available_options[0]['cost'];
        }

        $order_data = [
            'user_id' => $user_id,
            'order_number' => generate_order_number(),
            'total' => $cart_total,
            'shipping_cost' => $shipping_cost,
            'grand_total' => $cart_total + $shipping_cost,
            'status' => 'pending',
            'payment_method' => $this->input->post('payment_method') ?: 'bank_transfer',
            'shipping_name' => $this->input->post('shipping_name'),
            'shipping_phone' => $this->input->post('shipping_phone'),
            'shipping_address' => $this->input->post('shipping_address'),
            'shipping_city' => $this->input->post('shipping_city'),
            'shipping_province' => $this->input->post('shipping_province'),
            'shipping_postal' => $this->input->post('shipping_postal'),
            'shipping_courier' => $shipping_courier,
            'notes' => $this->input->post('notes')
        ];

        $order_items = [];
        foreach ($cart_items as $item) {
            $price = $item->sale_price ? $item->sale_price : $item->price;
            $order_items[] = [
                'product_id' => $item->product_id,
                'product_name' => $item->name,
                'product_image' => $item->image,
                'quantity' => $item->quantity,
                'price' => $price,
                'subtotal' => $price * $item->quantity
            ];
        }

        $order_id = $this->order_model->create($order_data, $order_items);

        if ($order_id) {
            // Clear cart
            $this->cart_model->clear();
            $this->session->set_flashdata('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
            redirect('profil/pesanan/' . $order_data['order_number']);
        } else {
            $this->session->set_flashdata('error', 'Gagal membuat pesanan. Silakan coba lagi.');
            redirect('checkout');
        }
    }
}
