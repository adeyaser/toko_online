<div class="page-header" style="background: linear-gradient(135deg, #EEF2FF 0%, #E0F2FE 100%); padding: 2.5rem 0; margin-bottom: 2rem; border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <div class="breadcrumb" style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; color: var(--text-muted); margin-bottom: 0.75rem;">
            <a href="<?= base_url(); ?>" style="color: var(--text-muted); text-decoration: none;">Beranda</a>
            <span>/</span>
            <span style="color: var(--text-muted);">Informasi</span>
            <span>/</span>
            <span style="color: var(--primary); font-weight: 600;"><?= htmlspecialchars($page->title); ?></span>
        </div>
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--primary); color: #FFFFFF; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                <i data-feather="<?= !empty($page->icon) ? htmlspecialchars($page->icon) : 'file-text'; ?>" style="width: 24px; height: 24px;"></i>
            </div>
            <div>
                <h1 style="font-size: 2rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.2; font-family: var(--font-display);">
                    <?= htmlspecialchars($page->title); ?>
                </h1>
                <p style="color: var(--text-muted); margin: 4px 0 0 0; font-size: 0.88rem;">
                    Terakhir diperbarui: <?= date('d F Y', strtotime($page->updated_at)); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-bottom: 4rem;">
    <div class="page-layout-grid" style="display: grid; grid-template-columns: 1fr 320px; gap: 2rem; align-items: start;">
        
        <!-- Main Content Area -->
        <article class="page-article-card" style="background: #FFFFFF; border-radius: 16px; border: 1px solid var(--border-color); padding: 2.5rem; box-shadow: var(--shadow-sm); line-height: 1.8;">
            <div class="page-rendered-content">
                <?= $page->content; ?>
            </div>

            <!-- Page Article Footer -->
            <div style="margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div style="color: var(--text-muted); font-size: 0.85rem;">
                    Apakah informasi ini membantu Anda? <span style="font-weight: 600; color: #0F172A;">Ada pertanyaan lain?</span>
                </div>
                <div>
                    <?php 
                        $wa_num = format_whatsapp_number(get_setting('whatsapp', ''));
                        $wa_url = !empty($wa_num) ? 'https://wa.me/' . $wa_num . '?text=' . rawurlencode('Halo CS ShopVista, saya ingin bertanya terkait halaman ' . $page->title) : '#';
                    ?>
                    <a href="<?= $wa_url; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 8px; border-color: #25D366; color: #128C7E; font-weight: 600; padding: 8px 16px; border-radius: 20px; text-decoration: none;">
                        <i data-feather="message-circle" style="width: 16px; height: 16px;"></i> Hubungi CS via WhatsApp
                    </a>
                </div>
            </div>
        </article>

        <!-- Right Sidebar Navigation -->
        <aside class="page-sidebar" style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Information Links Box -->
            <div style="background: #FFFFFF; border-radius: 16px; border: 1px solid var(--border-color); padding: 1.5rem; box-shadow: var(--shadow-sm);">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #0F172A; margin: 0 0 1rem 0; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; gap: 8px;">
                    <i data-feather="book-open" style="width: 18px; height: 18px; color: var(--primary);"></i> Menu Informasi
                </h3>
                <nav style="display: flex; flex-direction: column; gap: 6px;">
                    <?php if (!empty($all_pages)): ?>
                        <?php foreach ($all_pages as $item): ?>
                            <?php $is_active = ($item->slug === $page->slug); ?>
                            <a href="<?= base_url($item->slug); ?>" style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 10px; text-decoration: none; font-size: 0.92rem; font-weight: <?= $is_active ? '700' : '500'; ?>; background: <?= $is_active ? 'rgba(79, 70, 229, 0.08)' : 'transparent'; ?>; color: <?= $is_active ? 'var(--primary)' : 'var(--text-secondary)'; ?>; transition: all 0.2s;" onmouseover="if(!<?= $is_active ? 'true':'false'; ?>) this.style.background='#F8FAFC'" onmouseout="if(!<?= $is_active ? 'true':'false'; ?>) this.style.background='transparent'">
                                <i data-feather="<?= !empty($item->icon) ? htmlspecialchars($item->icon) : 'file-text'; ?>" style="width: 16px; height: 16px; color: <?= $is_active ? 'var(--primary)' : 'var(--text-muted)'; ?>;"></i>
                                <span style="flex: 1;"><?= htmlspecialchars($item->title); ?></span>
                                <?php if ($is_active): ?>
                                    <i data-feather="chevron-right" style="width: 14px; height: 14px; color: var(--primary);"></i>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Customer Service Quick Help Box -->
            <div style="background: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%); border-radius: 16px; border: 1px solid #DBEAFE; padding: 1.5rem; text-align: center;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: #25D366; color: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 0.75rem; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35);">
                    <i data-feather="headphones" style="width: 22px; height: 22px;"></i>
                </div>
                <h4 style="margin: 0 0 6px 0; color: #0F172A; font-weight: 700;">Customer Care</h4>
                <p style="font-size: 0.85rem; color: #475569; margin: 0 0 1rem 0; line-height: 1.5;">
                    Senin - Minggu (08:00 - 21:00 WIB)<br>
                    Siap membantu pertanyaan belanja Anda.
                </p>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <a href="<?= $wa_url; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-block" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); border: none; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px;">
                        <i data-feather="message-circle" style="width: 16px; height: 16px;"></i> WhatsApp Live Chat
                    </a>
                    <a href="mailto:<?= get_setting('store_email', 'hello@shopvista.com'); ?>" class="btn btn-secondary btn-block" style="border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.85rem; padding: 8px;">
                        <i data-feather="mail" style="width: 14px; height: 14px;"></i> <?= get_setting('store_email', 'hello@shopvista.com'); ?>
                    </a>
                </div>
            </div>

        </aside>
    </div>
</div>

<style>
/* Page Rendered Content Typography */
.page-rendered-content {
    color: #334155;
    font-size: 1rem;
}
.page-rendered-content h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #0F172A;
    margin: 1.5rem 0 1rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--border-light);
}
.page-rendered-content h3:first-child {
    margin-top: 0;
}
.page-rendered-content h4 {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1E293B;
    margin: 1.5rem 0 0.75rem 0;
}
.page-rendered-content p {
    margin: 0 0 1rem 0;
    color: #475569;
}
.page-rendered-content ul, 
.page-rendered-content ol {
    margin: 0 0 1.25rem 1.25rem;
    padding: 0;
    color: #475569;
}
.page-rendered-content li {
    margin-bottom: 0.5rem;
}
.page-rendered-content strong {
    color: #0F172A;
}

@media (max-width: 991px) {
    .page-layout-grid {
        grid-template-columns: 1fr !important;
    }
    .page-article-card {
        padding: 1.75rem !important;
    }
}
</style>
