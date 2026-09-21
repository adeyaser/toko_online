<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead_notifier {

    protected $ci;

    public function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->model('Lead_model', 'lead_model');
        $this->ci->load->model('Product_model', 'product_model');
        $this->ci->load->model('Setting_model', 'setting_model');
        $this->ci->load->helper('toko');
    }

    /**
     * Send email notification to active leads when a product is created or updated
     * 
     * @param int $product_id
     * @param string $action 'created' or 'updated'
     * @return array
     */
    public function notify_product($product_id, $action = 'created') {
        $product = $this->ci->product_model->get($product_id);
        if (!$product || !$product->is_active) {
            return [
                'status'  => 'skipped',
                'message' => 'Produk tidak ditemukan atau berstatus nonaktif.'
            ];
        }

        $leads = $this->ci->lead_model->get_active_leads();
        if (empty($leads)) {
            return [
                'status'  => 'skipped',
                'message' => 'Belum ada email pelanggan aktif di tabel leads.'
            ];
        }

        $store_name = get_setting('store_name', 'ShopVista');
        $from_email = get_setting('smtp_from_email', get_setting('store_email', 'noreply@shopvista.com'));
        $from_name  = get_setting('smtp_from_name', $store_name);

        $action_label = ($action === 'created') ? 'Produk Baru' : 'Update Produk';
        $subject = ($action === 'created') 
            ? "🔥 [{$store_name}] Produk Baru: " . $product->name 
            : "✨ [{$store_name}] Pembaruan Produk: " . $product->name;

        $html_body = $this->build_email_html($product, $action_label, $store_name);

        $recipient_emails = [];
        foreach ($leads as $l) {
            $recipient_emails[] = $l->email;
        }

        // Configure CI Email
        $smtp_host   = get_setting('smtp_host', 'smtp.gmail.com');
        $smtp_port   = (int) get_setting('smtp_port', 587);
        $smtp_user   = get_setting('smtp_user', '');
        $smtp_pass   = get_setting('smtp_pass', '');
        $smtp_crypto = get_setting('smtp_crypto', 'tls');

        $email_config = [
            'mailtype'     => 'html',
            'charset'      => 'utf-8',
            'newline'      => "\r\n",
            'crlf'         => "\r\n",
            'smtp_timeout' => 8
        ];

        if (!empty($smtp_user) && !empty($smtp_pass)) {
            $email_config['protocol']    = 'smtp';
            $email_config['smtp_host']   = $smtp_host;
            $email_config['smtp_port']   = $smtp_port;
            $email_config['smtp_user']   = $smtp_user;
            $email_config['smtp_pass']   = $smtp_pass;
            $email_config['smtp_crypto'] = ($smtp_crypto !== 'none') ? $smtp_crypto : '';
        } else {
            $email_config['protocol'] = 'mail';
        }

        $this->ci->load->library('email');
        $this->ci->email->initialize($email_config);

        $sent_count = 0;
        $status = 'sent';
        $error_msg = null;

        try {
            // Use BCC or send in batches to protect subscriber privacy
            $this->ci->email->from($from_email, $from_name);
            $this->ci->email->to($from_email); // primary recipient is store
            $this->ci->email->bcc($recipient_emails);
            $this->ci->email->subject($subject);
            $this->ci->email->message($html_body);

            if (@$this->ci->email->send()) {
                $sent_count = count($recipient_emails);
                $status = 'sent';
            } else {
                $status = 'logged';
                $error_msg = strip_tags($this->ci->email->print_debugger(['headers']));
                $sent_count = count($recipient_emails);
            }
        } catch (Exception $e) {
            $status = 'logged';
            $error_msg = $e->getMessage();
            $sent_count = count($recipient_emails);
        }

        // Record log to lead_email_logs
        $this->ci->lead_model->log_email([
            'product_id'      => $product->id,
            'subject'         => $subject,
            'recipient_count' => $sent_count,
            'recipients'      => implode(', ', array_slice($recipient_emails, 0, 50)) . (count($recipient_emails) > 50 ? ' ...dan lainnya' : ''),
            'status'          => $status,
            'message'         => $error_msg ?: "Email notifikasi $action_label berhasil diproses ke $sent_count leads."
        ]);

        return [
            'status' => $status,
            'count'  => $sent_count,
            'subject'=> $subject
        ];
    }

    /**
     * Build modern responsive HTML email body
     */
    protected function build_email_html($product, $action_label, $store_name) {
        $product_url = base_url('produk/' . $product->slug);
        $product_img = product_image($product->image);
        $price_current = rupiah($product->sale_price ?: $product->price);
        $price_original = $product->sale_price ? rupiah($product->price) : '';
        $desc = !empty($product->short_desc) ? $product->short_desc : truncate_text($product->description, 120);

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . htmlspecialchars($product->name) . '</title>
        </head>
        <body style="margin: 0; padding: 0; background-color: #F1F5F9; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F1F5F9; padding: 30px 10px;">
                <tr>
                    <td align="center">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #E2E8F0;">
                            <!-- Header -->
                            <tr>
                                <td align="center" style="background: linear-gradient(135deg, #4F46E5 0%, #3730A3 100%); padding: 25px 20px;">
                                    <h1 style="color: #FFFFFF; font-size: 24px; margin: 0; font-weight: 800; letter-spacing: -0.5px;">' . htmlspecialchars($store_name) . '</h1>
                                    <div style="display: inline-block; background: rgba(255,255,255,0.2); color: #FFFFFF; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;">
                                        ' . htmlspecialchars($action_label) . '
                                    </div>
                                </td>
                            </tr>

                            <!-- Product Showcase Card -->
                            <tr>
                                <td style="padding: 25px 25px 15px;">
                                    <p style="color: #475569; font-size: 14px; margin-top: 0; margin-bottom: 20px; text-align: center;">
                                        Hai pelanggan setia, ada produk menarik yang baru saja hadir di koleksi toko kami!
                                    </p>

                                    <!-- Product Card Box -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; background: #F8FAFC;">
                                        <tr>
                                            <td align="center" style="padding: 15px; background: #FFFFFF; border-bottom: 1px solid #E2E8F0;">
                                                <img src="' . $product_img . '" alt="' . htmlspecialchars($product->name) . '" style="max-width: 240px; max-height: 240px; border-radius: 10px; object-fit: cover; display: block;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 20px;">
                                                <h2 style="font-size: 18px; color: #0F172A; margin: 0 0 8px 0; font-weight: 700; line-height: 1.3;">
                                                    ' . htmlspecialchars($product->name) . '
                                                </h2>
                                                
                                                <p style="color: #64748B; font-size: 13px; line-height: 1.5; margin: 0 0 15px 0;">
                                                    ' . htmlspecialchars($desc) . '
                                                </p>

                                                <!-- Price Section -->
                                                <div style="margin-bottom: 20px;">
                                                    <span style="font-size: 22px; font-weight: 800; color: #4F46E5;">' . $price_current . '</span>
                                                    ' . (!empty($price_original) ? '<span style="font-size: 14px; color: #94A3B8; text-decoration: line-through; margin-left: 8px;">' . $price_original . '</span>' : '') . '
                                                </div>

                                                <!-- CTA Button -->
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td align="center">
                                                            <a href="' . $product_url . '" target="_blank" style="display: block; width: 100%; text-align: center; background: #4F46E5; color: #FFFFFF; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; text-decoration: none; box-sizing: border-box;">
                                                                Lihat Produk Sekarang &rarr;
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td style="padding: 20px 25px 25px; border-top: 1px solid #E2E8F0; text-align: center; background-color: #FAFAFA;">
                                    <p style="color: #94A3B8; font-size: 12px; line-height: 1.5; margin: 0;">
                                        Anda menerima email ini karena mendaftarkan email Anda di newsletter <strong>' . htmlspecialchars($store_name) . '</strong>.<br>
                                        &copy; ' . date('Y') . ' ' . htmlspecialchars($store_name) . '. Hak cipta dilindungi undang-undang.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ';
    }
}
