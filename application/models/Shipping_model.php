<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Courier_model', 'courier_model');
    }

    /**
     * Get list of courier options with rates
     * Supports Manual Admin CRUD couriers and External REST API
     */
    public function get_courier_options($city = '', $weight = 1000, $cart_total = 0, $province = '') {
        $shipping_mode = get_setting('shipping_mode', 'hybrid'); // 'manual', 'api', or 'hybrid'
        $free_shipping_min = (float) get_setting('free_shipping_min', 200000);
        $is_free_eligible = ($cart_total >= $free_shipping_min && $free_shipping_min > 0);

        $options = [];

        // 1. Fetch Manual Couriers from Database
        if ($shipping_mode === 'manual' || $shipping_mode === 'hybrid') {
            $db_couriers = $this->courier_model->get_all_active();

            foreach ($db_couriers as $c) {
                $cost = (float)$c->cost;
                $original_cost = $cost;
                $is_free = false;

                // If eligible for free shipping and courier allows free promo
                if ($is_free_eligible && $c->is_free_eligible) {
                    $cost = 0;
                    $is_free = true;
                }

                $badge = $c->badge;
                if ($is_free && $original_cost > 0) {
                    $badge = 'GRATIS ONGKIR';
                }

                $options[] = [
                    'code' => $c->code,
                    'courier_name' => $c->courier_name,
                    'service_name' => $c->service_name,
                    'description' => $c->description,
                    'etd' => $c->etd,
                    'original_cost' => $original_cost,
                    'cost' => $cost,
                    'is_free' => $is_free,
                    'badge' => $badge,
                    'icon' => $c->icon ?: 'truck'
                ];
            }
        }

        // 2. Fetch Live REST API rates from RajaOngkir if mode is 'api' or 'hybrid'
        if ($shipping_mode === 'api' || $shipping_mode === 'hybrid') {
            $api_key = get_setting('rajaongkir_api_key', '');
            $origin = get_setting('rajaongkir_origin', '17601'); // Default 17601 = Gambir, Jakarta Pusat

            if (!empty($api_key)) {
                $target_dest = !empty(trim($city)) ? trim($city) : 'Jakarta';
                $raja_rates = $this->_fetch_rajaongkir_rates($api_key, $origin, $target_dest, $weight, $province);
                if (!empty($raja_rates)) {
                    foreach ($raja_rates as $rr) {
                        $options[] = $rr;
                    }
                }
            }
        }

        // Fallback default if empty
        if (empty($options)) {
            $shipping_cost_default = (float) get_setting('shipping_cost', 15000);
            $options[] = [
                'code' => 'shopvista_std',
                'courier_name' => 'ShopVista Express',
                'service_name' => 'Standar / Reguler',
                'description' => 'Layanan pengiriman andalan toko',
                'etd' => '2-3 Hari',
                'original_cost' => $shipping_cost_default,
                'cost' => $is_free_eligible ? 0 : $shipping_cost_default,
                'is_free' => $is_free_eligible,
                'badge' => $is_free_eligible ? 'GRATIS ONGKIR' : 'Rekomendasi',
                'icon' => 'truck'
            ];
        }

        return $options;
    }

    /**
     * Live rates from RajaOngkir (Komerce) API
     */
    private function _fetch_rajaongkir_rates($api_key, $origin, $destination, $weight, $province = '') {
        $results = [];

        if (empty($api_key)) {
            return [];
        }

        // Clean & normalize origin (default to 17601: Gambir, Jakarta Pusat if empty or legacy 152)
        $origin_id = trim($origin);
        if (empty($origin_id) || $origin_id === '152') {
            $origin_id = '17601'; // Default: Gambir, Jakarta Pusat
        } elseif (!is_numeric($origin_id)) {
            $origin_id = $this->_resolve_destination_id($api_key, $origin_id) ?: '17601';
        }

        // Resolve destination to ID if text
        $dest_id = trim($destination);
        if (empty($dest_id)) {
            return [];
        }
        if (!is_numeric($dest_id)) {
            $resolved = $this->_resolve_destination_id($api_key, $dest_id, $province);
            if ($resolved) {
                $dest_id = $resolved;
            } else {
                return [];
            }
        }

        // Minimum weight 100g
        $weight_in_grams = max(100, (int)$weight);

        // Request rates for fast active couriers (JNE & POS)
        $couriers = 'jne:pos';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query([
                'origin' => $origin_id,
                'destination' => $dest_id,
                'weight' => $weight_in_grams,
                'courier' => $couriers
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/x-www-form-urlencoded",
                "key: " . $api_key
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if (!$err && $response) {
            $data = json_decode($response, true);
            if (!empty($data['data']) && is_array($data['data'])) {
                $ignored_patterns = ['dangerous', 'valuable', 'jtr<', 'jtr>', 'sps'];

                foreach ($data['data'] as $c) {
                    $service_code = strtoupper($c['service'] ?? '');
                    $desc = $c['description'] ?? '';
                    $courier_code = strtolower($c['code'] ?? 'jne');
                    $cost_val = (float)($c['cost'] ?? 0);

                    if ($cost_val <= 0) continue;

                    $skip = false;
                    foreach ($ignored_patterns as $p) {
                        if (stripos($service_code, $p) !== false || stripos($desc, $p) !== false) {
                            $skip = true;
                            break;
                        }
                    }
                    if ($skip) continue;

                    $etd_val = !empty($c['etd']) ? $c['etd'] : '1-3 hari';
                    $etd_val = str_ireplace(['days', 'day'], 'hari', $etd_val);

                    $results[] = [
                        'code' => $courier_code . '_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $service_code)),
                        'courier_name' => $c['name'] ?? strtoupper($courier_code),
                        'service_name' => $service_code,
                        'description' => $desc ?: 'Layanan ' . $service_code,
                        'etd' => $etd_val,
                        'original_cost' => $cost_val,
                        'cost' => $cost_val,
                        'is_free' => false,
                        'badge' => 'Live RajaOngkir',
                        'icon' => 'truck'
                    ];
                }
            }
        }

        return $results;
    }

    private static $_dest_cache = [];

    /**
     * Helper to resolve destination text (City or District name) to RajaOngkir Komerce Destination ID
     */
    public function _resolve_destination_id($api_key, $query, $province = '') {
        if (empty($query) || empty($api_key)) return null;

        $clean_query = trim(preg_replace('/^(kota administrasi|kabupaten administrasi|kota|kabupaten|kab\.)\s+/i', '', $query));
        $clean_prov = !empty($province) ? trim(preg_replace('/^(daerah istimewa|dki|propinsi|provinsi)\s+/i', '', $province)) : '';

        $cache_key = strtolower($clean_query . '_' . $clean_prov);

        if (isset(self::$_dest_cache[$cache_key])) {
            return self::$_dest_cache[$cache_key];
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=" . urlencode($clean_query),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                "key: " . $api_key
            ]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            $data = json_decode($response, true);
            if (!empty($data['data']) && is_array($data['data'])) {
                // If province is specified, try dual match first
                if (!empty($clean_prov)) {
                    foreach ($data['data'] as $item) {
                        $match_city = (stripos($item['city_name'] ?? '', $clean_query) !== false || stripos($item['district_name'] ?? '', $clean_query) !== false);
                        $match_prov = (stripos($item['province_name'] ?? '', $clean_prov) !== false || stripos($clean_prov, $item['province_name'] ?? '') !== false);
                        if ($match_city && $match_prov) {
                            self::$_dest_cache[$cache_key] = $item['id'];
                            return $item['id'];
                        }
                    }
                }

                // Try finding matching city or district name
                foreach ($data['data'] as $item) {
                    if (strcasecmp($item['city_name'] ?? '', $clean_query) === 0 || strcasecmp($item['district_name'] ?? '', $clean_query) === 0) {
                        self::$_dest_cache[$cache_key] = $item['id'];
                        return $item['id'];
                    }
                }

                // Fallback to first result
                $first_id = $data['data'][0]['id'] ?? null;
                if ($first_id) {
                    self::$_dest_cache[$cache_key] = $first_id;
                    return $first_id;
                }
            }
        }

        return null;
    }
}
