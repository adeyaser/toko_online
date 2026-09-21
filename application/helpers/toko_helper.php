<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Toko Helper - ShopVista
 * Helper functions for the online store
 */

if (!function_exists('rupiah')) {
    /**
     * Format number to Indonesian Rupiah
     */
    function rupiah($number, $prefix = true) {
        $formatted = number_format($number, 0, ',', '.');
        return $prefix ? 'Rp' . $formatted : $formatted;
    }
}

if (!function_exists('discount_percent')) {
    /**
     * Calculate discount percentage
     */
    function discount_percent($price, $sale_price) {
        if ($price <= 0 || !$sale_price) return 0;
        return round((($price - $sale_price) / $price) * 100);
    }
}

if (!function_exists('product_image')) {
    /**
     * Get product image URL or clean fallback
     */
    function product_image($image, $size = '400x400') {
        if (!empty($image)) {
            if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
                return $image;
            }
            if (file_exists(FCPATH . 'assets/images/products/' . $image)) {
                return base_url('assets/images/products/' . $image);
            }
        }
        $dims = explode('x', $size);
        $w = $dims[0];
        $h = isset($dims[1]) ? $dims[1] : $dims[0];
        return 'https://placehold.co/' . $w . 'x' . $h . '/F8FAFC/64748B?text=Produk';
    }
}

if (!function_exists('category_image')) {
    function category_image($image = '', $slug = '') {
        $fcpath = defined('FCPATH') ? FCPATH : dirname(dirname(dirname(__FILE__))) . '/';
        if (!empty($image)) {
            if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
                return $image;
            }
            if (file_exists($fcpath . 'assets/images/categories/' . $image)) {
                $ver = filemtime($fcpath . 'assets/images/categories/' . $image);
                $url = function_exists('base_url') ? base_url('assets/images/categories/' . $image) : '/assets/images/categories/' . $image;
                return $url . '?v=' . $ver;
            }
        }
        if (!empty($slug) && file_exists($fcpath . 'assets/images/categories/' . $slug . '.jpg')) {
            $ver = filemtime($fcpath . 'assets/images/categories/' . $slug . '.jpg');
            $url = function_exists('base_url') ? base_url('assets/images/categories/' . $slug . '.jpg') : '/assets/images/categories/' . $slug . '.jpg';
            return $url . '?v=' . $ver;
        }
        $defaults = [
            'elektronik' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=500&auto=format&fit=crop&q=80',
            'fashion-pria' => 'https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?w=500&auto=format&fit=crop&q=80',
            'fashion-wanita' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500&auto=format&fit=crop&q=80',
            'makanan-minuman' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&auto=format&fit=crop&q=80',
            'kesehatan-kecantikan' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=500&auto=format&fit=crop&q=80',
            'rumah-tangga' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=500&auto=format&fit=crop&q=80',
            'olahraga' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=500&auto=format&fit=crop&q=80',
            'buku-alat-tulis' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500&auto=format&fit=crop&q=80'
        ];
        if (!empty($slug) && isset($defaults[$slug])) {
            return $defaults[$slug];
        }
        return 'https://placehold.co/300x300/F8FAFC/64748B?text=Kategori';
    }
}

if (!function_exists('banner_image')) {
    function banner_image($image) {
        if (!empty($image)) {
            if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
                return $image;
            }
            if (file_exists(FCPATH . 'assets/images/banners/' . $image)) {
                return base_url('assets/images/banners/' . $image);
            }
        }
        return 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1400&auto=format&fit=crop&q=85';
    }
}

if (!function_exists('avatar_image')) {
    function avatar_image($avatar) {
        $fcpath = defined('FCPATH') ? FCPATH : dirname(dirname(dirname(__FILE__))) . '/';
        if (!empty($avatar)) {
            if (strpos($avatar, 'http://') === 0 || strpos($avatar, 'https://') === 0) {
                return $avatar;
            }
            if (file_exists($fcpath . 'assets/images/avatars/' . $avatar)) {
                $ver = filemtime($fcpath . 'assets/images/avatars/' . $avatar);
                $url = function_exists('base_url') ? base_url('assets/images/avatars/' . $avatar) : '/assets/images/avatars/' . $avatar;
                return $url . '?v=' . $ver;
            }
        }
        return 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=120&auto=format&fit=crop&q=80';
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Truncate text to specified length (30-50 chars)
     */
    function truncate_text($text, $length = 45, $suffix = '...') {
        $clean = trim(preg_replace('/\s+/', ' ', strip_tags($text ?? '')));
        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
            if (mb_strlen($clean, 'UTF-8') <= $length) return $clean;
            return mb_substr($clean, 0, $length, 'UTF-8') . $suffix;
        }
        if (strlen($clean) <= $length) return $clean;
        return substr($clean, 0, $length) . $suffix;
    }
}

if (!function_exists('generate_order_number')) {
    /**
     * Generate unique order number
     */
    function generate_order_number() {
        return 'SV-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
    }
}

if (!function_exists('order_status_badge')) {
    /**
     * Get HTML badge for order status
     */
    function order_status_badge($status) {
        $badges = [
            'pending'    => '<span class="badge badge-warning">Menunggu</span>',
            'processing' => '<span class="badge badge-info">Diproses</span>',
            'shipped'    => '<span class="badge badge-primary">Dikirim</span>',
            'delivered'  => '<span class="badge badge-success">Selesai</span>',
            'cancelled'  => '<span class="badge badge-danger">Dibatalkan</span>',
        ];
        return isset($badges[$status]) ? $badges[$status] : '<span class="badge">' . ucfirst($status) . '</span>';
    }
}

if (!function_exists('order_status_text')) {
    function order_status_text($status) {
        $texts = [
            'pending'    => 'Menunggu Pembayaran',
            'processing' => 'Sedang Diproses',
            'shipped'    => 'Dalam Pengiriman',
            'delivered'  => 'Pesanan Selesai',
            'cancelled'  => 'Dibatalkan',
        ];
        return isset($texts[$status]) ? $texts[$status] : ucfirst($status);
    }
}

if (!function_exists('feather_icon')) {
    /**
     * Render Feather icon SVG inline (using simple SVG approach)
     */
    function feather_icon($name, $size = 24, $class = '') {
        return '<i data-feather="' . $name . '" class="' . $class . '"></i>';
    }
}

if (!function_exists('time_ago')) {
    /**
     * Convert datetime to relative time
     */
    function time_ago($datetime) {
        $now = time();
        $time = strtotime($datetime);
        $diff = $now - $time;
        
        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
        if ($diff < 2592000) return floor($diff / 86400) . ' hari lalu';
        if ($diff < 31536000) return floor($diff / 2592000) . ' bulan lalu';
        return floor($diff / 31536000) . ' tahun lalu';
    }
}

if (!function_exists('get_setting')) {
    /**
     * Quick access to store settings
     */
    function get_setting($key, $default = '') {
        $CI =& get_instance();
        if (!isset($CI->setting_model)) {
            $CI->load->model('Setting_model', 'setting_model');
        }
        return $CI->setting_model->get($key, $default);
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        $CI =& get_instance();
        return $CI->session->userdata('user_id') ? true : false;
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        $CI =& get_instance();
        return $CI->session->userdata('user_role') === 'admin';
    }
}

if (!function_exists('current_user')) {
    function current_user() {
        $CI =& get_instance();
        $user_id = $CI->session->userdata('user_id');
        if (!$user_id) return null;
        if (!isset($CI->user_model)) {
            $CI->load->model('User_model', 'user_model');
        }
        return $CI->user_model->get($user_id);
    }
}

if (!function_exists('star_rating')) {
    /**
     * Render star rating HTML
     */
    function star_rating($rating, $max = 5) {
        $html = '<div class="star-rating">';
        for ($i = 1; $i <= $max; $i++) {
            if ($i <= $rating) {
                $html .= '<span class="star filled">★</span>';
            } else {
                $html .= '<span class="star">★</span>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('get_category_meta')) {
    /**
     * Get natural, professional category icon and color scheme
     */
    function get_category_meta($slug) {
        $meta = [
            'elektronik' => [
                'icon'  => 'smartphone',
                'bg'    => '#EFF6FF',
                'color' => '#2563EB',
                'label' => 'Gadget & Elektronik'
            ],
            'fashion-pria' => [
                'icon'  => 'user',
                'bg'    => '#EEF2FF',
                'color' => '#4F46E5',
                'label' => 'Pakaian & Sepatu Pria'
            ],
            'fashion-wanita' => [
                'icon'  => 'shopping-bag',
                'bg'    => '#FDF2F8',
                'color' => '#DB2777',
                'label' => 'Busana & Tas Wanita'
            ],
            'makanan-minuman' => [
                'icon'  => 'coffee',
                'bg'    => '#FFFBEB',
                'color' => '#D97706',
                'label' => 'Kuliner & Minuman'
            ],
            'kesehatan-kecantikan' => [
                'icon'  => 'heart',
                'bg'    => '#ECFDF5',
                'color' => '#059669',
                'label' => 'Skincare & Kecantikan'
            ],
            'rumah-tangga' => [
                'icon'  => 'home',
                'bg'    => '#F8FAFC',
                'color' => '#475569',
                'label' => 'Peralatan Rumah'
            ],
            'olahraga' => [
                'icon'  => 'activity',
                'bg'    => '#ECFEFF',
                'color' => '#0891B2',
                'label' => 'Perlengkapan Olahraga'
            ],
            'buku-alat-tulis' => [
                'icon'  => 'book-open',
                'bg'    => '#FAF5FF',
                'color' => '#7C3AED',
                'label' => 'Buku & Alat Tulis'
            ],
        ];

        return isset($meta[$slug]) ? $meta[$slug] : [
            'icon'  => 'package',
            'bg'    => '#F1F5F9',
            'color' => '#4F46E5',
            'label' => 'Produk'
        ];
    }
}

if (!function_exists('product_meta_data')) {
    /**
     * Get natural, realistic e-commerce metadata (rating, sold count, location, shipping)
     */
    function product_meta_data($product_or_id) {
        $product_id = is_object($product_or_id) ? $product_or_id->id : (is_array($product_or_id) ? ($product_or_id['id'] ?? 1) : (int)$product_or_id);
        $custom_city = is_object($product_or_id) ? ($product_or_id->location ?? '') : (is_array($product_or_id) ? ($product_or_id['location'] ?? '') : '');

        $cities = ['Jakarta Pusat', 'Jakarta Barat', 'Jakarta Selatan', 'Surabaya', 'Bandung', 'Tangerang', 'Semarang'];
        $ratings = ['4.9', '4.8', '4.9', '5.0', '4.8', '4.9', '4.8', '5.0'];
        $solds = [150, 280, 95, 340, 210, 460, 85, 175, 590, 120, 310, 165, 410, 130, 250, 90, 480, 195];
        
        $idx = abs((int)$product_id) % count($cities);
        $sold_idx = abs((int)$product_id) % count($solds);
        $rating_idx = abs((int)$product_id) % count($ratings);

        // If product has location column populated in DB, use it directly!
        $city = !empty($custom_city) ? $custom_city : $cities[$idx];

        return [
            'city'          => $city,
            'rating'        => $ratings[$rating_idx],
            'sold'          => $solds[$sold_idx] . '+',
            'free_shipping' => true // Uniform free shipping badge so all card bottom lines are perfectly level!
        ];
    }
}

if (!function_exists('format_whatsapp_number')) {
    /**
     * Clean and format phone number for WhatsApp wa.me links
     * E.g. '0812-3456-7890' -> '6281234567890'
     */
    function format_whatsapp_number($phone) {
        $cleaned = preg_replace('/[^0-9]/', '', (string)$phone);
        if (empty($cleaned)) {
            return '';
        }
        if (substr($cleaned, 0, 1) === '0') {
            $cleaned = '62' . substr($cleaned, 1);
        } elseif (substr($cleaned, 0, 2) !== '62') {
            $cleaned = '62' . $cleaned;
        }
        return $cleaned;
    }
}

if (!function_exists('store_logo')) {
    /**
     * Get dynamic store logo image URL, or empty if using default SVG/text brand
     */
    function store_logo() {
        $logo = get_setting('store_logo', '');
        if (!empty($logo)) {
            if (strpos($logo, 'http://') === 0 || strpos($logo, 'https://') === 0) {
                return $logo;
            }
            if (file_exists(FCPATH . 'assets/images/logo/' . $logo)) {
                return base_url('assets/images/logo/' . $logo);
            }
        }
        return '';
    }
}

if (!function_exists('turnstile_enabled')) {
    /**
     * Check if Cloudflare Turnstile is enabled and configured
     */
    function turnstile_enabled() {
        $enabled = get_setting('turnstile_enabled', '0');
        $site_key = get_setting('turnstile_site_key', '');
        return ($enabled === '1' && !empty($site_key));
    }
}

if (!function_exists('render_turnstile_widget')) {
    /**
     * Render Cloudflare Turnstile widget HTML & Script
     */
    function render_turnstile_widget($action = 'login') {
        if (!turnstile_enabled()) {
            return '';
        }
        $site_key = get_setting('turnstile_site_key', '');
        $mode = get_setting('turnstile_mode', 'managed');

        $output = '<!-- Cloudflare Turnstile -->' . "\n";
        $output .= '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>' . "\n";
        $output .= '<div class="turnstile-container" style="margin: 16px 0; display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%;">' . "\n";
        $output .= '    <div class="cf-turnstile" data-sitekey="' . htmlspecialchars($site_key) . '" data-action="' . htmlspecialchars($action) . '" data-theme="auto"';
        if ($mode === 'invisible') {
            $output .= ' data-size="invisible"';
        } else {
            $output .= ' data-size="flexible"';
        }
        $output .= '></div>' . "\n";
        $output .= '</div>' . "\n";
        return $output;
    }
}

if (!function_exists('verify_turnstile')) {
    /**
     * Verify Cloudflare Turnstile token server-side
     */
    function verify_turnstile($token = null) {
        if (!turnstile_enabled()) {
            return true;
        }

        $secret_key = get_setting('turnstile_secret_key', '');
        if (empty($secret_key)) {
            // Secret key not configured yet, don't lock out legitimate users
            return true;
        }

        $CI =& get_instance();
        $cf_response = $token !== null ? $token : $CI->input->post('cf-turnstile-response');
        if (empty($cf_response)) {
            return false;
        }

        $remote_ip = $CI->input->ip_address();

        $post_fields = http_build_query([
            'secret'   => $secret_key,
            'response' => $cf_response,
            'remoteip' => $remote_ip
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post_fields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 6,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err || !$response) {
            log_message('error', 'Cloudflare Turnstile verification curl error: ' . $err);
            return false;
        }

        $result = json_decode($response, true);
        return isset($result['success']) && ($result['success'] === true);
    }
}



