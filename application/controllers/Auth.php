<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model', 'user_model');
        $this->load->model('Cart_model', 'cart_model');
        $this->load->model('Category_model', 'category_model');
    }

    public function login() {
        if ($this->session->userdata('user_id')) {
            redirect('');
            return;
        }

        if ($this->input->method() === 'post') {
            // Verify Cloudflare Turnstile if enabled
            if (turnstile_enabled() && !verify_turnstile()) {
                $data['error'] = 'Verifikasi keamanan Cloudflare Turnstile gagal. Silakan coba lagi.';
            } else {
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                $user = $this->user_model->login($email, $password);
                
                if ($user) {
                    $this->session->set_userdata([
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'user_role' => $user->role,
                        'user_avatar' => $user->avatar
                    ]);

                    // Merge guest cart
                    $this->cart_model->merge_on_login($user->id);

                    if ($user->role === 'admin') {
                        redirect('admin');
                    } else {
                        $redirect = $this->session->userdata('redirect_after_login');
                        if ($redirect) {
                            $this->session->unset_userdata('redirect_after_login');
                            redirect($redirect);
                        }
                        redirect('');
                    }
                } else {
                    $data['error'] = 'Email atau password salah.';
                }
            }
        }

        $data['title'] = 'Login - ShopVista';
        $data['meta_description'] = 'Masuk ke akun ShopVista Anda';
        $data['cart_count'] = $this->cart_model->count_items();
        $data['categories'] = $this->category_model->get_all_active();

        $this->load->view('templates/header', $data);
        $this->load->view('auth/login', $data);
        $this->load->view('templates/footer', $data);
    }

    public function register() {
        if ($this->session->userdata('user_id')) {
            redirect('');
            return;
        }

        if ($this->input->method() === 'post') {
            // Verify Cloudflare Turnstile if enabled
            if (turnstile_enabled() && !verify_turnstile()) {
                $data['error'] = 'Verifikasi keamanan Cloudflare Turnstile gagal. Silakan coba lagi.';
            } else {
                $email = $this->input->post('email');
                
                // Check if email exists
                if ($this->user_model->get_by_email($email)) {
                    $data['error'] = 'Email sudah terdaftar. Silakan gunakan email lain.';
                } else {
                    $user_data = [
                        'name' => $this->input->post('name'),
                        'email' => $email,
                        'password' => $this->input->post('password'),
                        'phone' => $this->input->post('phone'),
                        'role' => 'customer'
                    ];

                    $user_id = $this->user_model->register($user_data);
                    
                    if ($user_id) {
                        $this->session->set_userdata([
                            'user_id' => $user_id,
                            'user_name' => $user_data['name'],
                            'user_email' => $user_data['email'],
                            'user_role' => 'customer',
                            'user_avatar' => null
                        ]);

                        $this->cart_model->merge_on_login($user_id);
                        $this->session->set_flashdata('success', 'Registrasi berhasil! Selamat berbelanja.');
                        redirect('');
                    } else {
                        $data['error'] = 'Gagal mendaftar. Silakan coba lagi.';
                    }
                }
            }
        }

        $data['title'] = 'Daftar Akun - ShopVista';
        $data['meta_description'] = 'Buat akun baru di ShopVista';
        $data['cart_count'] = $this->cart_model->count_items();
        $data['categories'] = $this->category_model->get_all_active();

        $this->load->view('templates/header', $data);
        $this->load->view('auth/register', $data);
        $this->load->view('templates/footer', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('');
    }
}
