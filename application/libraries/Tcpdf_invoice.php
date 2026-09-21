<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tcpdf_invoice Library
 * 
 * Generates thermal / compact invoice PDFs using TCPDF with exact design parity
 * for ShopVista online store orders.
 */
class Tcpdf_invoice {

    protected $ci;

    public function __construct() {
        if (function_exists('get_instance')) {
            $this->ci =& get_instance();
        }
        if (!class_exists('TCPDF')) {
            if (file_exists(FCPATH . 'vendor/autoload.php')) {
                require_once FCPATH . 'vendor/autoload.php';
            }
        }
    }

    /**
     * Generate PDF for one or multiple orders
     * 
     * @param array $orders Array of order objects with items
     * @param string $mode 'I' = inline browser, 'D' = download, 'S' = return string, 'F' = save file
     * @param string $filename Output filename
     * @return string|void
     */
    public function generate($orders, $mode = 'I', $filename = null) {
        if (!is_array($orders)) {
            $orders = [$orders];
        }

        if (empty($filename)) {
            $filename = (count($orders) === 1)
                ? 'Invoice_' . $orders[0]->order_number . '.pdf'
                : 'Invoices_' . count($orders) . '_Pesanan_' . date('Ymd_His') . '.pdf';
        }

        $store_name = get_setting('store_name', 'ShopVista');
        $contact_email = get_setting('store_email', get_setting('contact_email', 'hello@shopvista.com'));
        $contact_phone = get_setting('store_phone', get_setting('contact_phone', '0812-3456-7890'));
        $logo_src = $this->get_store_logo_src($store_name);

        // Initialize TCPDF with 100mm width, 165mm height (compact thermal / label format)
        $pdf = new TCPDF('P', 'mm', [100, 165], true, 'UTF-8', false);

        $pdf->SetCreator($store_name);
        $pdf->SetAuthor($store_name . ' Admin');
        $pdf->SetTitle('Invoice PDF - ' . $store_name);
        $pdf->SetSubject('Struk & Invoice Pesanan');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(4, 4, 4);
        $pdf->SetAutoPageBreak(true, 4);
        $pdf->SetFont('helvetica', '', 7.5);

        $total_orders = count($orders);

        foreach ($orders as $index => $order) {
            $pdf->AddPage();

            // Generate Barcode PNG Base64
            $barcode_base64 = '';
            if (class_exists('TCPDFBarcode')) {
                try {
                    $barcodeObj = new TCPDFBarcode($order->order_number, 'C128');
                    $barcode_png_data = $barcodeObj->getBarcodePngData(1.4, 20, [15, 23, 42]);
                    if ($barcode_png_data) {
                        $barcode_base64 = 'data:image/png;base64,' . base64_encode($barcode_png_data);
                    }
                } catch (Exception $e) {
                    $barcode_base64 = '';
                }
            }

            $courier_label = !empty($order->shipping_courier) ? $order->shipping_courier : $store_name . ' Express';
            $resi_label = !empty($order->tracking_number) ? $order->tracking_number : 'Belum Terbit';
            $payment_label = ($order->payment_method == 'bank_transfer') ? 'Transfer Bank' : 'COD';
            $status_label = strtoupper($order->status);

            $html = '
            <!-- HEADER -->
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="63%" style="vertical-align:top;">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="24" style="vertical-align:middle;">
                                    <img src="' . $logo_src . '" height="20" style="vertical-align:middle;" />
                                </td>
                                <td width="5"></td>
                                <td style="font-size:10pt; font-weight:bold; color:#0F172A; vertical-align:middle; line-height:20px; white-space:nowrap;">' . htmlspecialchars($store_name) . '</td>
                            </tr>
                        </table>
                        <div style="font-size:6.2pt; color:#64748B; margin-top:2px; line-height:1.25;">' . htmlspecialchars($contact_email) . '<br>' . htmlspecialchars($contact_phone) . '</div>
                    </td>
                    <td width="37%" align="right" style="vertical-align:top;">
                        <div style="font-size:8pt; font-weight:bold; color:#0F172A;">STRUK PENGIRIMAN</div>
                        <div style="font-size:7.2pt; font-weight:bold; color:#4F46E5;">#' . $order->order_number . '</div>
                        ' . ($barcode_base64 ? '<div style="margin-top:1px;"><img src="' . $barcode_base64 . '" height="15" /></div>' : '') . '
                        <div style="font-size:6pt; color:#64748B; margin-top:1px;">' . date('d/m/Y H:i', strtotime($order->created_at)) . ' WIB</div>
                    </td>
                </tr>
            </table>

            <div style="line-height:2px;">&nbsp;</div>
            <div style="border-bottom:1.5px solid #0F172A;"></div>
            <div style="line-height:3px;">&nbsp;</div>

            <!-- RECIPIENT & COURIER META -->
            <table cellpadding="3" cellspacing="0" width="100%" style="background-color:#F8FAFC; border:1px solid #E2E8F0;">
                <tr>
                    <td width="55%" style="border-right:1px solid #E2E8F0; vertical-align:top;">
                        <div style="font-size:5.8pt; font-weight:bold; color:#64748B;">PENERIMA:</div>
                        <div style="font-size:6.8pt; color:#1E293B; line-height:1.25;">
                            <strong>' . htmlspecialchars($order->shipping_name) . '</strong><br>
                            ' . htmlspecialchars($order->shipping_phone) . '<br>
                            ' . htmlspecialchars($order->shipping_address) . '<br>
                            ' . htmlspecialchars($order->shipping_city) . ', ' . htmlspecialchars($order->shipping_province) . ' ' . htmlspecialchars($order->shipping_postal) .
                            (!empty($order->notes) ? '<br><span style="color:#B45309;font-style:italic;font-size:5.8pt;">Catatan: ' . htmlspecialchars($order->notes) . '</span>' : '') . '
                        </div>
                    </td>
                    <td width="45%" style="vertical-align:top;">
                        <div style="font-size:5.8pt; font-weight:bold; color:#64748B;">KURIR &amp; RESI:</div>
                        <div style="font-size:6.8pt; color:#1E293B; line-height:1.25;">
                            <span style="background-color:#EEF2FF; color:#4338CA; font-weight:bold;">' . htmlspecialchars($courier_label) . '</span><br>
                            Resi: <strong>' . htmlspecialchars($resi_label) . '</strong><br>
                            Bayar: <strong>' . $payment_label . '</strong><br>
                            Status: <strong style="color:#059669;">' . $status_label . '</strong>
                        </div>
                    </td>
                </tr>
            </table>

            <div style="line-height:2px;">&nbsp;</div>
            <div style="border-bottom:1px dashed #CBD5E1;"></div>
            <div style="line-height:3px;">&nbsp;</div>

            <!-- ITEMS TABLE -->
            <table cellpadding="2.5" cellspacing="0" width="100%" style="border-collapse:collapse;">
                <thead>
                    <tr style="background-color:#0F172A; color:#FFFFFF;">
                        <th width="7%" align="center" style="font-size:6.2pt; font-weight:bold;">#</th>
                        <th width="49%" style="font-size:6.2pt; font-weight:bold;">PRODUK</th>
                        <th width="10%" align="center" style="font-size:6.2pt; font-weight:bold;">QTY</th>
                        <th width="17%" align="right" style="font-size:6.2pt; font-weight:bold;">HARGA</th>
                        <th width="17%" align="right" style="font-size:6.2pt; font-weight:bold;">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>';

            if (!empty($order->items)) {
                foreach ($order->items as $idx => $it) {
                    $bg = ($idx % 2 == 1) ? 'background-color:#F8FAFC;' : 'background-color:#FFFFFF;';
                    $html .= '
                    <tr style="' . $bg . '">
                        <td align="center" style="font-size:6.5pt; border-bottom:1px solid #E2E8F0;">' . ($idx + 1) . '</td>
                        <td style="font-size:6.5pt; font-weight:bold; color:#0F172A; border-bottom:1px solid #E2E8F0;">' . htmlspecialchars($it->product_name) . '</td>
                        <td align="center" style="font-size:6.5pt; border-bottom:1px solid #E2E8F0;">' . $it->quantity . '</td>
                        <td align="right" style="font-size:6.5pt; border-bottom:1px solid #E2E8F0;">' . rupiah($it->price) . '</td>
                        <td align="right" style="font-size:6.5pt; font-weight:bold; border-bottom:1px solid #E2E8F0;">' . rupiah($it->subtotal) . '</td>
                    </tr>';
                }
            } else {
                $html .= '<tr><td colspan="5" align="center" style="font-size:6.5pt; color:#64748B;">Tidak ada item produk.</td></tr>';
            }

            $html .= '
                </tbody>
            </table>

            <div style="line-height:3px;">&nbsp;</div>

            <!-- SUMMARY & NOTES -->
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="48%" style="vertical-align:top;">
                        <table cellpadding="2.5" cellspacing="0" width="100%" style="background-color:#F8FAFC; border:1px solid #E2E8F0;">
                            <tr>
                                <td style="font-size:5.8pt; color:#64748B; line-height:1.25;">
                                    <strong style="color:#0F172A;">Ketentuan:</strong><br>
                                    &bull; Simpan struk sebagai bukti beli.<br>
                                    &bull; Wajib video unboxing untuk klaim.<br>
                                    &bull; Terima kasih belanja di ' . htmlspecialchars($store_name) . '!
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="4%"></td>
                    <td width="48%" style="vertical-align:top;">
                        <table cellpadding="1" cellspacing="0" width="100%">
                            <tr>
                                <td style="font-size:6.5pt; color:#475569;">Subtotal:</td>
                                <td align="right" style="font-size:6.5pt; font-weight:bold; color:#0F172A;">' . rupiah($order->total) . '</td>
                            </tr>
                            <tr>
                                <td style="font-size:6.5pt; color:#475569;">Ongkir:</td>
                                <td align="right" style="font-size:6.5pt; font-weight:bold; color:#059669;">' . ($order->shipping_cost > 0 ? rupiah($order->shipping_cost) : 'GRATIS') . '</td>
                            </tr>
                            <tr style="border-top:1.5px solid #0F172A;">
                                <td style="font-size:7.8pt; font-weight:bold; color:#0F172A; padding-top:2px;">TOTAL:</td>
                                <td align="right" style="font-size:8.5pt; font-weight:bold; color:#059669; padding-top:2px;">' . rupiah($order->grand_total) . '</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div style="line-height:4px;">&nbsp;</div>

            <!-- FOOTER -->
            <table cellpadding="0" cellspacing="0" width="100%" style="border-top:1px dashed #CBD5E1;">
                <tr>
                    <td width="60%" style="font-size:5.8pt; color:#94A3B8; padding-top:2px;">Dicetak: ' . date('d/m/Y H:i') . ' WIB</td>
                    <td width="40%" align="right" style="font-size:5.8pt; color:#94A3B8; padding-top:2px;">Hal. ' . ($index + 1) . '/' . $total_orders . '</td>
                </tr>
            </table>
            ';

            $pdf->writeHTML($html, true, false, true, false, '');
        }

        // Clean any stray output buffers before sending PDF to browser
        while (ob_get_level()) {
            ob_end_clean();
        }

        return $pdf->Output($filename, $mode);
    }

    /**
     * Get store logo image source (file path, data URI, or generated badge)
     */
    protected function get_store_logo_src($store_name) {
        $logo_file = get_setting('store_logo', '');
        $fcpath = defined('FCPATH') ? FCPATH : dirname(dirname(dirname(__FILE__))) . '/';

        if (!empty($logo_file)) {
            $local_path = $fcpath . 'assets/images/logo/' . $logo_file;
            if (file_exists($local_path) && is_readable($local_path)) {
                $ext = strtolower(pathinfo($local_path, PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif'])) {
                    return $local_path;
                }
                if ($ext === 'webp' && function_exists('imagecreatefromwebp')) {
                    $im = @imagecreatefromwebp($local_path);
                    if ($im) {
                        ob_start();
                        imagepng($im);
                        $data = ob_get_clean();
                        imagedestroy($im);
                        return 'data:image/png;base64,' . base64_encode($data);
                    }
                }
            } elseif (strpos($logo_file, 'http://') === 0 || strpos($logo_file, 'https://') === 0) {
                return $logo_file;
            }
        }

        // Fallback: Generate clean brand badge image
        $short_code = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $store_name), 0, 2)) ?: 'SV';
        return $this->generate_badge_data_uri($short_code);
    }

    /**
     * Generate 1:1 square badge PNG as base64 data URI to prevent vertical stretching in TCPDF
     */
    protected function generate_badge_data_uri($code = 'SV') {
        $size = 48;
        if (function_exists('imagecreatetruecolor')) {
            $im = imagecreatetruecolor($size, $size);
            $bg = imagecolorallocate($im, 79, 70, 229); // #4F46E5
            imagefilledrectangle($im, 0, 0, $size - 1, $size - 1, $bg);
            $white = imagecolorallocate($im, 255, 255, 255);

            $len = strlen($code);
            $char_w = 9;
            $char_h = 15;
            $x = (int)(($size - ($len * $char_w)) / 2);
            $y = (int)(($size - $char_h) / 2);

            imagestring($im, 5, $x, $y, $code, $white);

            ob_start();
            imagepng($im);
            $data = ob_get_clean();
            imagedestroy($im);

            return 'data:image/png;base64,' . base64_encode($data);
        }

        // Fallback SVG data URI if GD is absent
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"><rect width="48" height="48" rx="6" fill="#4F46E5"/><text x="24" y="31" font-family="Helvetica, Arial, sans-serif" font-size="20" font-weight="bold" fill="#FFFFFF" text-anchor="middle">' . htmlspecialchars($code) . '</text></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}

