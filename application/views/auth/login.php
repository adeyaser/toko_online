<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <a href="<?= base_url(); ?>" class="navbar-brand" style="justify-content: center; margin-bottom: 1.5rem;">
                <div class="logo-icon">SV</div>
                <div class="brand-text">Shop<span>Vista</span></div>
            </a>
            <h2>Selamat Datang Kembali</h2>
            <p>Masuk ke akun Anda untuk melanjutkan belanja</p>
        </div>

        <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form action="<?= base_url('login'); ?>" method="post" id="loginForm">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required value="<?= set_value('email'); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <?= render_turnstile_widget('login'); ?>
            <button type="submit" class="btn btn-primary btn-lg btn-full">
                Masuk
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
            </button>
        </form>

        <div class="auth-footer">
            Belum punya akun? <a href="<?= base_url('register'); ?>">Daftar sekarang</a>
        </div>

        <div style="text-align: center; margin-top: 1rem;">
            <p style="font-size: 0.8rem; color: var(--text-muted);">Demo: admin@shopvista.com / password</p>
        </div>
    </div>
</div>
