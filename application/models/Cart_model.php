<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model {

    /**
     * Get session identifier (user_id or session_id)
     */
    private function get_identifier() {
        $user_id = $this->session->userdata('user_id');
        if ($user_id) {
            return ['user_id' => $user_id];
        }
        return ['session_id' => session_id()];
    }

    /**
     * Get cart items with product details
     */
    public function get_items() {
        $identifier = $this->get_identifier();
        
        $this->db->select('cart.*, products.name, products.slug, products.price, products.sale_price, products.image, products.stock');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id');
        
        foreach ($identifier as $key => $val) {
            $this->db->where('cart.' . $key, $val);
        }
        
        $this->db->where('products.is_active', 1);
        return $this->db->get()->result();
    }

    /**
     * Add item to cart
     */
    public function add($product_id, $quantity = 1) {
        $identifier = $this->get_identifier();
        
        // Check if product already in cart
        $this->db->where('product_id', $product_id);
        foreach ($identifier as $key => $val) {
            $this->db->where($key, $val);
        }
        $existing = $this->db->get('cart')->row();

        if ($existing) {
            // Update quantity
            $new_qty = $existing->quantity + $quantity;
            $this->db->where('id', $existing->id);
            return $this->db->update('cart', ['quantity' => $new_qty]);
        } else {
            // Insert new
            $data = array_merge($identifier, [
                'product_id' => $product_id,
                'quantity' => $quantity
            ]);
            return $this->db->insert('cart', $data);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update_quantity($cart_id, $quantity) {
        $identifier = $this->get_identifier();
        
        $this->db->where('id', $cart_id);
        foreach ($identifier as $key => $val) {
            $this->db->where($key, $val);
        }
        return $this->db->update('cart', ['quantity' => max(1, $quantity)]);
    }

    /**
     * Remove cart item
     */
    public function remove($cart_id) {
        $identifier = $this->get_identifier();
        
        $this->db->where('id', $cart_id);
        foreach ($identifier as $key => $val) {
            $this->db->where($key, $val);
        }
        return $this->db->delete('cart');
    }

    /**
     * Get cart count
     */
    public function count_items() {
        $identifier = $this->get_identifier();
        
        $this->db->select_sum('quantity');
        foreach ($identifier as $key => $val) {
            $this->db->where($key, $val);
        }
        $result = $this->db->get('cart')->row();
        return $result ? (int)$result->quantity : 0;
    }

    /**
     * Get cart total
     */
    public function get_total() {
        $items = $this->get_items();
        $total = 0;
        foreach ($items as $item) {
            $price = $item->sale_price ? $item->sale_price : $item->price;
            $total += $price * $item->quantity;
        }
        return $total;
    }

    /**
     * Clear cart
     */
    public function clear() {
        $identifier = $this->get_identifier();
        foreach ($identifier as $key => $val) {
            $this->db->where($key, $val);
        }
        return $this->db->delete('cart');
    }

    /**
     * Merge guest cart to user cart on login
     */
    public function merge_on_login($user_id) {
        $session_id = session_id();
        
        // Get guest cart items
        $guest_items = $this->db->get_where('cart', ['session_id' => $session_id])->result();
        
        foreach ($guest_items as $item) {
            // Check if product already in user's cart
            $existing = $this->db->get_where('cart', [
                'user_id' => $user_id,
                'product_id' => $item->product_id
            ])->row();

            if ($existing) {
                $this->db->where('id', $existing->id);
                $this->db->update('cart', ['quantity' => $existing->quantity + $item->quantity]);
                $this->db->where('id', $item->id);
                $this->db->delete('cart');
            } else {
                $this->db->where('id', $item->id);
                $this->db->update('cart', ['user_id' => $user_id, 'session_id' => NULL]);
            }
        }
    }
}
