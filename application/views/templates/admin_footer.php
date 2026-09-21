        </main>
    </div>

    <script src="<?= base_url('assets/js/app.js'); ?>"></script>
    <script src="<?= base_url('assets/js/admin.js'); ?>"></script>
    <script>
        if (typeof feather !== 'undefined') feather.replace();
    </script>

    <?php if ($this->session->flashdata('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ShopVista.showToast('success', '<?= addslashes($this->session->flashdata('success')); ?>');
        });
    </script>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ShopVista.showToast('error', '<?= addslashes($this->session->flashdata('error')); ?>');
        });
    </script>
    <?php endif; ?>
</body>
</html>
