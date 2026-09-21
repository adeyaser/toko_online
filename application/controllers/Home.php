<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model', 'product_model');
        $this->load->model('Category_model', 'category_model');
        $this->load->model('Banner_model', 'banner_model');
        $this->load->model('Setting_model', 'setting_model');
        $this->load->model('Cart_model', 'cart_model');
    }

    public function index() {
        $data['title'] = get_setting('meta_title', 'ShopVista - Toko Online');
        $data['meta_description'] = get_setting('meta_description', '');
        $data['meta_keywords'] = get_setting('meta_keywords', '');
        
        $data['banners'] = $this->banner_model->get_active();
        $data['categories'] = $this->category_model->get_with_count();
        $data['featured_products'] = $this->product_model->get_featured(8);
        $data['latest_products'] = $this->product_model->get_latest(8);
        $data['sale_products'] = $this->product_model->get_on_sale(4);
        $data['cart_count'] = $this->cart_model->count_items();

        $this->load->view('templates/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('templates/footer', $data);
    }
}
