<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model', 'product_model');
        $this->load->model('Category_model', 'category_model');
        $this->load->model('Review_model', 'review_model');
        $this->load->model('Cart_model', 'cart_model');
        $this->load->library('pagination');
    }

    /**
     * Product catalog page
     */
    public function catalog($category_slug = null) {
        $filters = [];
        $data['current_category'] = null;

        // Category filter
        if ($category_slug) {
            $category = $this->category_model->get_by_slug($category_slug);
            if ($category) {
                $filters['category_slug'] = $category_slug;
                $data['current_category'] = $category;
            }
        }

        // Sort
        $filters['sort'] = $this->input->get('sort') ?: 'newest';
        $data['current_sort'] = $filters['sort'];

        // Search
        $search = $this->input->get('q');
        if ($search) {
            $filters['search'] = $search;
            $data['search_query'] = $search;
        }

        // Pagination
        $per_page = 12;
        $total = $this->product_model->count_all($filters);
        $page = (int) $this->input->get('page') ?: 1;
        $offset = ($page - 1) * $per_page;

        $data['products'] = $this->product_model->get_all($filters, $per_page, $offset);
        $data['total_products'] = $total;
        $data['current_page'] = $page;
        $data['total_pages'] = ceil($total / $per_page);
        $data['per_page'] = $per_page;

        $data['categories'] = $this->category_model->get_with_count();
        $data['cart_count'] = $this->cart_model->count_items();

        $data['title'] = $data['current_category'] 
            ? $data['current_category']->name . ' - ShopVista' 
            : 'Katalog Produk - ShopVista';
        $data['meta_description'] = $data['current_category']
            ? $data['current_category']->description
            : 'Temukan ribuan produk berkualitas dengan harga terbaik di ShopVista.';

        $this->load->view('templates/header', $data);
        $this->load->view('product/catalog', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Product detail page
     */
    public function detail($slug) {
        $product = $this->product_model->get_by_slug($slug);
        
        if (!$product) {
            show_404();
            return;
        }

        // Increment views
        $this->product_model->increment_views($product->id);

        // Get category
        $data['category'] = $this->category_model->get($product->category_id);
        
        // Get reviews
        $data['reviews'] = $this->review_model->get_product_reviews($product->id);
        $data['review_stats'] = $this->review_model->get_average($product->id);
        
        // Check if user can review
        $data['can_review'] = false;
        if ($this->session->userdata('user_id')) {
            $data['can_review'] = !$this->review_model->has_reviewed(
                $product->id, 
                $this->session->userdata('user_id')
            );
        }

        // Related products
        $data['related_products'] = $this->product_model->get_related($product->category_id, $product->id);

        $data['product'] = $product;
        $data['cart_count'] = $this->cart_model->count_items();
        $data['title'] = $product->name . ' - ShopVista';
        $data['meta_description'] = $product->short_desc ?: truncate_text($product->description, 160);

        $this->load->view('templates/header', $data);
        $this->load->view('product/detail', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Submit review
     */
    public function add_review() {
        if (!$this->session->userdata('user_id')) {
            redirect('login');
            return;
        }

        $product_id = $this->input->post('product_id');
        $product = $this->product_model->get($product_id);
        
        if (!$product) {
            redirect('katalog');
            return;
        }

        $data = [
            'product_id' => $product_id,
            'user_id' => $this->session->userdata('user_id'),
            'rating' => (int) $this->input->post('rating'),
            'comment' => $this->input->post('comment'),
            'is_approved' => 1
        ];

        $this->review_model->add($data);
        $this->session->set_flashdata('success', 'Ulasan berhasil ditambahkan!');
        redirect('produk/' . $product->slug);
    }
}
