<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model', 'product_model');
        $this->load->model('Category_model', 'category_model');
        $this->load->model('Cart_model', 'cart_model');
    }

    public function index() {
        $q = $this->input->get('q');
        $filters = ['search' => $q];
        $filters['sort'] = $this->input->get('sort') ?: 'newest';

        $per_page = 12;
        $page = (int) $this->input->get('page') ?: 1;
        $offset = ($page - 1) * $per_page;

        $data['products'] = $this->product_model->get_all($filters, $per_page, $offset);
        $data['total_products'] = $this->product_model->count_all($filters);
        $data['current_page'] = $page;
        $data['total_pages'] = ceil($data['total_products'] / $per_page);
        $data['search_query'] = $q;
        $data['current_sort'] = $filters['sort'];
        $data['categories'] = $this->category_model->get_all_active();
        $data['cart_count'] = $this->cart_model->count_items();

        $data['title'] = 'Pencarian: ' . htmlspecialchars($q) . ' - ShopVista';
        $data['meta_description'] = 'Hasil pencarian untuk "' . htmlspecialchars($q) . '" di ShopVista';

        $this->load->view('templates/header', $data);
        $this->load->view('search/index', $data);
        $this->load->view('templates/footer', $data);
    }
}
