<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Cart_model', 'cart_model');
        $this->load->model('Product_model', 'product_model');
        $this->load->model('Category_model', 'category_model');
    }

    /**
     * Cart page
     */
    public function index() {
        $data['cart_items'] = $this->cart_model->get_items();
        $data['cart_total'] = $this->cart_model->get_total();
        $data['cart_count'] = $this->cart_model->count_items();
        $data['shipping_cost'] = (float) get_setting('shipping_cost', 15000);
        $data['free_shipping_min'] = (float) get_setting('free_shipping_min', 200000);
        
        // Apply free shipping
        if ($data['cart_total'] >= $data['free_shipping_min']) {
            $data['shipping_cost'] = 0;
        }
        
        $data['grand_total'] = $data['cart_total'] + $data['shipping_cost'];
        $data['title'] = 'Keranjang Belanja - ShopVista';
        $data['meta_description'] = 'Keranjang belanja Anda di ShopVista';
        $data['categories'] = $this->category_model->get_all_active();

        $this->load->view('templates/header', $data);
        $this->load->view('cart/index', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Add to cart (AJAX)
     */
    public function add() {
        $product_id = $this->input->post('product_id');
        $quantity = (int) $this->input->post('quantity') ?: 1;

        // Validate product
        $product = $this->product_model->get($product_id);
        if (!$product || !$product->is_active) {
            echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
            return;
        }

        if ($product->stock < $quantity) {
            echo json_encode(['status' => 'error', 'message' => 'Stok tidak mencukupi']);
            return;
        }

        $this->cart_model->add($product_id, $quantity);
        echo json_encode([
            'status' => 'success',
            'message' => $product->name . ' ditambahkan ke keranjang!',
            'count' => $this->cart_model->count_items()
        ]);
    }

    /**
     * Update cart item (AJAX)
     */
    public function update() {
        $cart_id = (int) $this->input->post('cart_id');
        $quantity = max(1, (int) $this->input->post('quantity'));

        $this->cart_model->update_quantity($cart_id, $quantity);

        $items = $this->cart_model->get_items();
        $item_subtotal = 0;
        foreach ($items as $it) {
            if ($it->id == $cart_id) {
                $price = !empty($it->sale_price) ? $it->sale_price : $it->price;
                $item_subtotal = $price * $it->quantity;
                break;
            }
        }

        $cart_total = $this->cart_model->get_total();
        $cart_count = $this->cart_model->count_items();
        $shipping_cost = (float) get_setting('shipping_cost', 15000);
        $free_shipping_min = (float) get_setting('free_shipping_min', 200000);

        $is_free_shipping = false;
        if ($cart_total >= $free_shipping_min && $free_shipping_min > 0) {
            $shipping_cost = 0;
            $is_free_shipping = true;
        }

        $free_shipping_needed = max(0, $free_shipping_min - $cart_total);
        $grand_total = $cart_total + $shipping_cost;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'                   => 'success',
                'cart_id'                  => $cart_id,
                'quantity'                 => $quantity,
                'item_subtotal'            => rupiah($item_subtotal),
                'item_subtotal_raw'        => $item_subtotal,
                'cart_count'               => $cart_count,
                'cart_total'               => rupiah($cart_total),
                'cart_total_raw'           => $cart_total,
                'shipping_cost'            => $shipping_cost > 0 ? rupiah($shipping_cost) : 'GRATIS',
                'is_free_shipping'         => $is_free_shipping,
                'free_shipping_min'        => $free_shipping_min,
                'free_shipping_needed'     => rupiah($free_shipping_needed),
                'free_shipping_needed_raw' => $free_shipping_needed,
                'grand_total'              => rupiah($grand_total),
                'grand_total_raw'          => $grand_total,
                'reload'                   => false
            ]));
    }

    /**
     * Remove cart item (AJAX)
     */
    public function remove() {
        $cart_id = (int) $this->input->post('cart_id');
        $this->cart_model->remove($cart_id);

        $cart_total = $this->cart_model->get_total();
        $cart_count = $this->cart_model->count_items();
        $shipping_cost = (float) get_setting('shipping_cost', 15000);
        $free_shipping_min = (float) get_setting('free_shipping_min', 200000);

        $is_free_shipping = false;
        if ($cart_total >= $free_shipping_min && $free_shipping_min > 0) {
            $shipping_cost = 0;
            $is_free_shipping = true;
        }

        $free_shipping_needed = max(0, $free_shipping_min - $cart_total);
        $grand_total = $cart_total + $shipping_cost;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'                   => 'success',
                'cart_id'                  => $cart_id,
                'cart_count'               => $cart_count,
                'cart_total'               => rupiah($cart_total),
                'cart_total_raw'           => $cart_total,
                'shipping_cost'            => $shipping_cost > 0 ? rupiah($shipping_cost) : 'GRATIS',
                'is_free_shipping'         => $is_free_shipping,
                'free_shipping_needed'     => rupiah($free_shipping_needed),
                'free_shipping_needed_raw' => $free_shipping_needed,
                'grand_total'              => rupiah($grand_total),
                'grand_total_raw'          => $grand_total,
                'is_empty'                 => ($cart_count === 0)
            ]));
    }

    /**
     * Get cart count (AJAX)
     */
    public function count() {
        echo json_encode(['count' => $this->cart_model->count_items()]);
    }
}
