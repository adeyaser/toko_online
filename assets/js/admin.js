/**
 * ShopVista - Admin Panel JavaScript
 */

const AdminPanel = {
    init() {
        this.initSidebar();
        this.initDeleteConfirm();
        this.initImageUpload();
        this.initSimpleChart();
    },

    initSidebar() {
        const toggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.admin-sidebar');
        if (!toggle || !sidebar) return;

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('active') && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    },

    initDeleteConfirm() {
        document.querySelectorAll('.btn-delete, .action-btn.delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                    e.preventDefault();
                }
            });
        });
    },

    initImageUpload() {
        document.querySelectorAll('.image-upload input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const preview = this.closest('.image-upload').querySelector('.image-preview');
                const uploadText = this.closest('.image-upload').querySelector('.upload-text');

                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        if (preview) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        }
                        if (uploadText) {
                            uploadText.textContent = this.files[0].name;
                        }
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    },

    initSimpleChart() {
        const chart = document.querySelector('.simple-chart');
        if (!chart) return;

        // Animate bars on load
        const bars = chart.querySelectorAll('.bar');
        bars.forEach((bar, i) => {
            const height = bar.style.height;
            bar.style.height = '0';
            setTimeout(() => {
                bar.style.height = height;
            }, 100 + (i * 50));
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    AdminPanel.init();
    
    // Init Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
