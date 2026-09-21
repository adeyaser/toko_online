<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Page_model', 'page_model');
        $this->load->model('Cart_model', 'cart_model');
        $this->load->model('Category_model', 'category_model');
    }

    public function view($slug = '') {
        if (empty($slug)) {
            show_404();
            return;
        }

        $page = $this->page_model->get_active_by_slug($slug);
        if (!$page) {
            show_404();
            return;
        }

        $data['page'] = $page;
        $data['all_pages'] = $this->page_model->get_all_active();
        $data['title'] = !empty($page->meta_title) ? $page->meta_title : $page->title . ' - ShopVista';
        $data['meta_description'] = !empty($page->meta_description) ? $page->meta_description : strip_tags(substr($page->content, 0, 160));
        $data['cart_count'] = $this->cart_model->count_items();
        $data['categories'] = $this->category_model->get_all_active();

        $this->load->view('templates/header', $data);
        $this->load->view('page/detail', $data);
        $this->load->view('templates/footer', $data);
    }
}
