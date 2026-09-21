<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banners extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('user_role') !== 'admin') {
            redirect('login');
        }
        $this->load->model('Banner_model', 'banner_model');
    }

    public function index() {
        $data['banners'] = $this->banner_model->get_all();
        $data['title'] = 'Kelola Banner - ShopVista';
        $data['active_menu'] = 'banners';
        $this->load->view('admin/banners/list', $data);
    }

    public function create() {
        if ($this->input->method() === 'post') {
            $banner_data = [
                'title' => $this->input->post('title'),
                'subtitle' => $this->input->post('subtitle'),
                'link' => $this->input->post('link'),
                'sort_order' => (int) $this->input->post('sort_order'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            if ($_FILES['image']['name']) {
                $config['upload_path'] = './assets/images/banners/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size'] = 5120;
                $config['file_name'] = 'banner-' . time();
                
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image')) {
                    $banner_data['image'] = $this->upload->data('file_name');
                }
            }

            $this->banner_model->insert($banner_data);
            $this->session->set_flashdata('success', 'Banner berhasil ditambahkan!');
            redirect('admin/banners');
        }

        $data['title'] = 'Tambah Banner - ShopVista';
        $data['active_menu'] = 'banners';
        $data['mode'] = 'create';
        $this->load->view('admin/banners/form', $data);
    }

    public function edit($id) {
        $banner = $this->banner_model->get($id);
        if (!$banner) { show_404(); return; }

        if ($this->input->method() === 'post') {
            $banner_data = [
                'title' => $this->input->post('title'),
                'subtitle' => $this->input->post('subtitle'),
                'link' => $this->input->post('link'),
                'sort_order' => (int) $this->input->post('sort_order'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            if ($_FILES['image']['name']) {
                $config['upload_path'] = './assets/images/banners/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size'] = 5120;
                $config['file_name'] = 'banner-' . time();

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image')) {
                    $banner_data['image'] = $this->upload->data('file_name');
                }
            }

            $this->banner_model->update($id, $banner_data);
            $this->session->set_flashdata('success', 'Banner berhasil diperbarui!');
            redirect('admin/banners');
        }

        $data['banner'] = $banner;
        $data['title'] = 'Edit Banner - ShopVista';
        $data['active_menu'] = 'banners';
        $data['mode'] = 'edit';
        $this->load->view('admin/banners/form', $data);
    }

    public function delete($id) {
        $this->banner_model->delete($id);
        $this->session->set_flashdata('success', 'Banner berhasil dihapus!');
        redirect('admin/banners');
    }
}
