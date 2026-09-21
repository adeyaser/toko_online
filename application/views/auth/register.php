<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <a href="<?= base_url(); ?>" class="navbar-brand" style="justify-content: center; margin-bottom: 1.5rem;">
                <div class="logo-icon">SV</div>
                <div class="brand-text">Shop<span>Vista</span></div>
            </a>
            <h2>Buat Akun Baru</h2>
            <p>Daftar gratis dan mulai belanja di ShopVista</p>
        </div>

        <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form action="<?= base_url('register'); ?>" method="post">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" placeholder="Nama lengkap Anda" required value="<?= set_value('name'); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required value="<?= set_value('email'); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">No. Telepon</label>
                <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="<?= set_value('phone'); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required minlength="6">
            </div>
            <?= render_turnstile_widget('register'); ?>
            <button type="submit" class="btn btn-primary btn-lg btn-full">
                Daftar Sekarang
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            </button>
        </form>

        <div class="auth-footer">
            Sudah punya akun? <a href="<?= base_url('login'); ?>">Masuk di sini</a>
        </div>
    </div>
</div>
