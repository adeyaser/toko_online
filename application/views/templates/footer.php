    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="<?= base_url(); ?>" class="navbar-brand">
                        <div class="logo-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </div>
                        <div class="brand-text">Shop<span>Vista</span></div>
                    </a>
                    <p class="brand-desc">
                        Belanja online mudah, aman, dan terpercaya. Temukan ribuan produk berkualitas dengan harga terbaik dan pengiriman cepat ke seluruh Indonesia.
                    </p>
                    <div class="footer-social">
                        <?php $ig = get_setting('instagram', ''); ?>
                        <a href="<?= !empty($ig) ? (strpos($ig, 'http') === 0 ? $ig : 'https://instagram.com/' . ltrim($ig, '@')) : '#'; ?>" target="_blank" title="Instagram"><i data-feather="instagram"></i></a>
                        <?php $fb = get_setting('facebook', ''); ?>
                        <a href="<?= !empty($fb) ? (strpos($fb, 'http') === 0 ? $fb : 'https://facebook.com/' . $fb) : '#'; ?>" target="_blank" title="Facebook"><i data-feather="facebook"></i></a>
                        <?php 
                            $footer_wa = format_whatsapp_number(get_setting('whatsapp', ''));
                            $footer_wa_url = !empty($footer_wa) ? 'https://wa.me/' . $footer_wa . '?text=' . rawurlencode(get_setting('whatsapp_message', 'Halo ShopVista...')) : '#';
                        ?>
                        <a href="<?= $footer_wa_url; ?>" target="_blank" rel="noopener noreferrer" title="WhatsApp"><i data-feather="message-circle"></i></a>
                    </div>
                </div>


                <div class="footer-col">
                    <h4>Belanja</h4>
                    <ul>
                        <li><a href="<?= base_url('katalog'); ?>">Semua Produk</a></li>
                        <li><a href="<?= base_url('katalog?sort=popular'); ?>">Terlaris</a></li>
                        <li><a href="<?= base_url('katalog?sort=newest'); ?>">Terbaru</a></li>
                        <li><a href="<?= base_url('katalog?sort=price_low'); ?>">Harga Terendah</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Informasi</h4>
                    <ul>
                        <li><a href="<?= base_url('tentang-kami'); ?>">Tentang Kami</a></li>
                        <li><a href="<?= base_url('cara-belanja'); ?>">Cara Belanja</a></li>
                        <li><a href="<?= base_url('kebijakan-privasi'); ?>">Kebijakan Privasi</a></li>
                        <li><a href="<?= base_url('syarat-ketentuan'); ?>">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Hubungi Kami</h4>
                    <ul>
                        <li><a href="#"><?= get_setting('store_email', 'hello@shopvista.com'); ?></a></li>
                        <li><a href="#"><?= get_setting('store_phone', '0812-3456-7890'); ?></a></li>
                        <li><a href="#"><?= get_setting('store_address', 'Jakarta, Indonesia'); ?></a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; <?= date('Y'); ?> <?= get_setting('store_name', 'ShopVista'); ?>. All rights reserved.</span>
                <span>Made with ❤ in Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <?php $app_js_ver = file_exists(FCPATH . 'assets/js/app.js') ? filemtime(FCPATH . 'assets/js/app.js') : time(); ?>
    <script src="<?= base_url('assets/js/app.js?v=' . $app_js_ver); ?>"></script>
    <script>
        // Initialize Feather Icons
        if (typeof feather !== 'undefined') feather.replace();
    </script>

    <!-- WhatsApp Floating Chat Widget -->
    <?php $this->load->view('templates/whatsapp_widget'); ?>
</body>
</html>

