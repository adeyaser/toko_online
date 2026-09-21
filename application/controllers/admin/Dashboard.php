<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('user_role') !== 'admin') {
            redirect('login');
        }
        $this->load->model('Product_model', 'product_model');
        $this->load->model('Category_model', 'category_model');
        $this->load->model('Order_model', 'order_model');
        $this->load->model('User_model', 'user_model');
    }

    public function index() {
        $data['total_products'] = $this->product_model->count_admin();
        $data['total_orders'] = $this->order_model->count_all();
        $data['total_revenue'] = $this->order_model->get_total_revenue();
        $data['total_customers'] = $this->user_model->count_customers();
        $data['pending_orders'] = $this->order_model->count_all('pending');
        $data['recent_orders'] = $this->order_model->get_recent(10);
        $data['monthly_revenue'] = $this->order_model->get_monthly_revenue(6);
        
        $data['title'] = 'Dashboard Admin - ShopVista';
        $data['active_menu'] = 'dashboard';
        
        $this->load->view('admin/dashboard', $data);
    }
}
