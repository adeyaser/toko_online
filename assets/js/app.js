/**
 * ShopVista - Main Application JavaScript
 * Cart, Search, Toast, Animations, UI Interactions
 */

const ShopVista = {
    baseUrl: document.querySelector('meta[name="base-url"]')?.content || '/toko_online/',

    init() {
        this.initNavbar();
        this.initScrollAnimations();
        this.initHeroSlider();
        this.initQuantitySelector();
        this.initMobileMenu();
        this.initToast();
        this.initLazyLoad();
        this.updateCartCount();
        this.initFeatherIcons();
    },

    // ============================================================
    // Navbar Scroll Effect
    // ============================================================
    initNavbar() {
        const navbar = document.querySelector('.navbar');
        if (!navbar) return;

        const handleScroll = () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll();
    },

    // ============================================================
    // Mobile Menu
    // ============================================================
    initMobileMenu() {
        const toggle = document.querySelector('.nav-toggle');
        const navLinks = document.querySelector('.nav-links');
        if (!toggle || !navLinks) return;

        toggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            toggle.classList.toggle('active');
        });

        // Category dropdown toggle
        const catDropdown = document.getElementById('navCategoryDropdown');
        const catToggleBtn = document.getElementById('categoryDropdownBtn');
        if (catDropdown && catToggleBtn) {
            // Auto expand on mobile if currently viewing a category
            if (catToggleBtn.classList.contains('active') && window.innerWidth <= 768) {
                catDropdown.classList.add('open');
                catToggleBtn.setAttribute('aria-expanded', 'true');
            }

            catToggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                catDropdown.classList.toggle('open');
                const isExpanded = catDropdown.classList.contains('open');
                catToggleBtn.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
            });

            document.addEventListener('click', (e) => {
                if (!catDropdown.contains(e.target)) {
                    catDropdown.classList.remove('open');
                    catToggleBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // Close mobile drawer on link click
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                toggle.classList.remove('active');
                if (catDropdown) catDropdown.classList.remove('open');
            });
        });
    },

    // ============================================================
    // Hero Slider
    // ============================================================
    initHeroSlider() {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.hero-dots .dot');
        if (slides.length === 0) return;

        let currentSlide = 0;
        let interval;

        const showSlide = (index) => {
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            
            currentSlide = index;
            if (currentSlide >= slides.length) currentSlide = 0;
            if (currentSlide < 0) currentSlide = slides.length - 1;

            slides[currentSlide].classList.add('active');
            if (dots[currentSlide]) dots[currentSlide].classList.add('active');
        };

        const startAutoplay = () => {
            interval = setInterval(() => showSlide(currentSlide + 1), 5000);
        };

        const stopAutoplay = () => {
            clearInterval(interval);
        };

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                stopAutoplay();
                showSlide(i);
                startAutoplay();
            });
        });

        startAutoplay();
    },

    // ============================================================
    // Scroll Animations
    // ============================================================
    initScrollAnimations() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        if (elements.length === 0) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        elements.forEach(el => observer.observe(el));
    },

    // ============================================================
    // Cart Functions
    // ============================================================
    addToCart(productId, quantity = 1) {
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);

        fetch(this.baseUrl + 'cart/add', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.showToast('success', data.message || 'Produk ditambahkan ke keranjang!');
                this.updateCartCount();
            } else {
                this.showToast('error', data.message || 'Gagal menambahkan produk');
            }
        })
        .catch(() => {
            this.showToast('error', 'Terjadi kesalahan. Coba lagi.');
        });
    },

    changeCartQty(cartId, delta) {
        const itemEl = document.querySelector(`[data-cart-id="${cartId}"]`);
        if (!itemEl) return;
        const inputEl = itemEl.querySelector('.qty-input');
        if (!inputEl) return;

        let currentQty = parseInt(inputEl.value) || 1;
        let newQty = Math.max(1, currentQty + delta);

        if (newQty === currentQty) return;

        // Optimistic UI update
        inputEl.value = newQty;

        // Dim subtotal slightly during sync
        const subtotalEl = document.getElementById(`itemSubtotal_${cartId}`);
        if (subtotalEl) subtotalEl.style.opacity = '0.5';

        this.updateCartItem(cartId, newQty);
    },

    updateCartItem(cartId, quantity) {
        const formData = new FormData();
        formData.append('cart_id', cartId);
        formData.append('quantity', quantity);

        fetch(this.baseUrl + 'cart/update', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            const subtotalEl = document.getElementById(`itemSubtotal_${cartId}`);
            if (subtotalEl) subtotalEl.style.opacity = '1';

            if (data.status === 'success') {
                this.updateCartCount();

                // 1. Update item subtotal
                if (subtotalEl && data.item_subtotal) {
                    subtotalEl.textContent = data.item_subtotal;
                }

                // 2. Ensure quantity input matches server
                const itemEl = document.querySelector(`[data-cart-id="${cartId}"]`);
                if (itemEl) {
                    const inputEl = itemEl.querySelector('.qty-input');
                    if (inputEl && data.quantity) inputEl.value = data.quantity;
                }

                // 3. Update cart header badge
                const headerBadge = document.getElementById('cartHeaderBadge');
                if (headerBadge && data.cart_count !== undefined) {
                    headerBadge.textContent = data.cart_count + ' Item';
                }

                // 4. Update Summary Subtotal
                const summarySubtotalLabel = document.getElementById('summarySubtotalLabel');
                if (summarySubtotalLabel && data.cart_count !== undefined) {
                    summarySubtotalLabel.textContent = `Subtotal (${data.cart_count} item)`;
                }
                const summarySubtotalVal = document.getElementById('summarySubtotalVal');
                if (summarySubtotalVal && data.cart_total) {
                    summarySubtotalVal.textContent = data.cart_total;
                }

                // 5. Update Summary Shipping
                const summaryShippingVal = document.getElementById('summaryShippingVal');
                if (summaryShippingVal && data.shipping_cost !== undefined) {
                    if (data.is_free_shipping || data.shipping_cost === 'GRATIS') {
                        summaryShippingVal.innerHTML = '<span style="color:var(--accent-green);font-weight:700;">GRATIS</span>';
                    } else {
                        summaryShippingVal.textContent = data.shipping_cost;
                    }
                }

                // 6. Update Free Shipping Notice
                const freeNotice = document.getElementById('freeShippingNotice');
                if (freeNotice) {
                    if (data.is_free_shipping || data.free_shipping_needed_raw <= 0) {
                        freeNotice.style.display = 'none';
                    } else {
                        freeNotice.style.display = 'block';
                        freeNotice.innerHTML = `💡 Belanja <strong>${data.free_shipping_needed}</strong> lagi untuk menikmati <strong>Gratis Ongkir</strong>!`;
                    }
                }

                // 7. Update Grand Total
                const summaryGrandTotal = document.getElementById('summaryGrandTotal');
                if (summaryGrandTotal && data.grand_total) {
                    summaryGrandTotal.textContent = data.grand_total;
                }

                // 8. Update Mobile Sticky Bottom Bar
                const stickyLabel = document.getElementById('stickyBarLabel');
                if (stickyLabel && data.cart_count !== undefined) {
                    stickyLabel.textContent = `Total Belanja (${data.cart_count} item)`;
                }
                const stickyAmount = document.getElementById('stickyBarAmount');
                if (stickyAmount && data.grand_total) {
                    stickyAmount.textContent = data.grand_total;
                }

                // Only reload if server explicitly requests it
                if (data.reload) location.reload();
            } else {
                this.showToast('error', data.message || 'Gagal mengupdate keranjang');
            }
        })
        .catch(() => {
            const subtotalEl = document.getElementById(`itemSubtotal_${cartId}`);
            if (subtotalEl) subtotalEl.style.opacity = '1';
            this.showToast('error', 'Gagal mengupdate keranjang');
        });
    },

    removeCartItem(cartId) {
        if (!confirm('Hapus produk ini dari keranjang?')) return;

        const formData = new FormData();
        formData.append('cart_id', cartId);

        fetch(this.baseUrl + 'cart/remove', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.showToast('success', 'Produk dihapus dari keranjang');
                this.updateCartCount();

                const item = document.querySelector(`[data-cart-id="${cartId}"]`);
                if (item) {
                    item.style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => {
                        item.remove();

                        // If cart is now empty, reload to show empty state
                        if (data.is_empty || document.querySelectorAll('.cart-item').length === 0) {
                            location.reload();
                            return;
                        }

                        // Otherwise update totals seamlessly via AJAX
                        const headerBadge = document.getElementById('cartHeaderBadge');
                        if (headerBadge && data.cart_count !== undefined) {
                            headerBadge.textContent = data.cart_count + ' Item';
                        }
                        const summarySubtotalLabel = document.getElementById('summarySubtotalLabel');
                        if (summarySubtotalLabel && data.cart_count !== undefined) {
                            summarySubtotalLabel.textContent = `Subtotal (${data.cart_count} item)`;
                        }
                        const summarySubtotalVal = document.getElementById('summarySubtotalVal');
                        if (summarySubtotalVal && data.cart_total) {
                            summarySubtotalVal.textContent = data.cart_total;
                        }
                        const summaryShippingVal = document.getElementById('summaryShippingVal');
                        if (summaryShippingVal && data.shipping_cost !== undefined) {
                            if (data.is_free_shipping || data.shipping_cost === 'GRATIS') {
                                summaryShippingVal.innerHTML = '<span style="color:var(--accent-green);font-weight:700;">GRATIS</span>';
                            } else {
                                summaryShippingVal.textContent = data.shipping_cost;
                            }
                        }
                        const freeNotice = document.getElementById('freeShippingNotice');
                        if (freeNotice) {
                            if (data.is_free_shipping || data.free_shipping_needed_raw <= 0) {
                                freeNotice.style.display = 'none';
                            } else {
                                freeNotice.style.display = 'block';
                                freeNotice.innerHTML = `💡 Belanja <strong>${data.free_shipping_needed}</strong> lagi untuk menikmati <strong>Gratis Ongkir</strong>!`;
                            }
                        }
                        const summaryGrandTotal = document.getElementById('summaryGrandTotal');
                        if (summaryGrandTotal && data.grand_total) {
                            summaryGrandTotal.textContent = data.grand_total;
                        }
                        const stickyLabel = document.getElementById('stickyBarLabel');
                        if (stickyLabel && data.cart_count !== undefined) {
                            stickyLabel.textContent = `Total Belanja (${data.cart_count} item)`;
                        }
                        const stickyAmount = document.getElementById('stickyBarAmount');
                        if (stickyAmount && data.grand_total) {
                            stickyAmount.textContent = data.grand_total;
                        }
                    }, 300);
                } else {
                    location.reload();
                }
            }
        })
        .catch(() => {
            this.showToast('error', 'Gagal menghapus produk');
        });
    },

    updateCartCount() {
        fetch(this.baseUrl + 'cart/count', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            const badges = document.querySelectorAll('.cart-count');
            badges.forEach(badge => {
                badge.textContent = data.count || 0;
                if (data.count > 0) {
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            });
        })
        .catch(() => {});
    },

    // ============================================================
    // Quantity Selector
    // ============================================================
    initQuantitySelector() {
        document.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.qty-input');
                let val = parseInt(input.value) || 1;
                const max = parseInt(input.getAttribute('max')) || 999;

                if (this.classList.contains('qty-minus')) {
                    val = Math.max(1, val - 1);
                } else {
                    val = Math.min(max, val + 1);
                }

                input.value = val;
                // Trigger change event
                input.dispatchEvent(new Event('change'));
            });
        });
    },

    // ============================================================
    // Toast Notifications
    // ============================================================
    initToast() {
        if (!document.querySelector('.toast-container')) {
            const container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
    },

    showToast(type, message, duration = 4000) {
        const container = document.querySelector('.toast-container');
        if (!container) return;

        const icons = {
            success: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2CB67D" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            error: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FF6584" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            info: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00D2FF" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
        };

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <span class="toast-icon">${icons[type] || icons.info}</span>
            <span class="toast-message">${message}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100px)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    // ============================================================
    // Lazy Loading
    // ============================================================
    initLazyLoad() {
        const images = document.querySelectorAll('img[data-src]');
        if (images.length === 0) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    img.addEventListener('load', () => {
                        img.style.opacity = '1';
                    });
                    observer.unobserve(img);
                }
            });
        }, { rootMargin: '100px' });

        images.forEach(img => {
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.5s ease';
            observer.observe(img);
        });
    },

    // ============================================================
    // Feather Icons
    // ============================================================
    initFeatherIcons() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    },

    // ============================================================
    // Search
    // ============================================================
    initSearch() {
        const searchInput = document.querySelector('.nav-search input');
        if (!searchInput) return;

        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const q = searchInput.value.trim();
                if (q.length > 0) {
                    window.location.href = this.baseUrl + 'cari?q=' + encodeURIComponent(q);
                }
            }
        });
    },

    // ============================================================
    // Form Validation
    // ============================================================
    validateForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return false;

        let isValid = true;
        const required = form.querySelectorAll('[required]');

        required.forEach(input => {
            const errorEl = input.parentElement.querySelector('.form-error');
            if (!input.value.trim()) {
                input.style.borderColor = 'var(--secondary)';
                if (errorEl) errorEl.style.display = 'block';
                isValid = false;
            } else {
                input.style.borderColor = '';
                if (errorEl) errorEl.style.display = 'none';
            }
        });

        return isValid;
    },

    // ============================================================
    // Image Preview
    // ============================================================
    previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (!preview || !input.files || !input.files[0]) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
};

window.ShopVista = ShopVista;

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    ShopVista.init();
    ShopVista.initSearch();
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Full Clickable Product Cards
document.addEventListener('click', function(e) {
    const card = e.target.closest('.product-card');
    if (!card) return;

    // If user clicked an anchor tag directly, let normal navigation occur
    if (e.target.closest('a') || e.target.closest('button')) return;

    // Find the product link
    const link = card.querySelector('.product-card-link-overlay') || 
                 card.querySelector('.product-image-link') || 
                 card.querySelector('.product-name a');

    if (link && link.href) {
        // Support Ctrl/Cmd click or middle click for new tab
        if (e.ctrlKey || e.metaKey || e.button === 1) {
            window.open(link.href, '_blank');
        } else {
            window.location.href = link.href;
        }
    }
});
