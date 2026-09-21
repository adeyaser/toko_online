<?php
/**
 * WhatsApp Floating Chat Widget for ShopVista
 * Configurable from Admin > Settings
 */
$wa_enabled = get_setting('whatsapp_enabled', '1');
if ($wa_enabled === '0') {
    return; // Widget is disabled
}

$wa_raw = get_setting('whatsapp', '0812-3456-7890');
$wa_number = format_whatsapp_number($wa_raw);

if (empty($wa_number)) {
    return; // No phone number configured
}

$wa_cs_name = get_setting('whatsapp_cs_name', 'Customer Service ShopVista');
$wa_cs_status = get_setting('whatsapp_cs_status', 'Online • Siap Melayani');
$wa_default_msg = get_setting('whatsapp_message', 'Halo ShopVista, saya tertarik untuk bertanya seputar produk/pesanan saya...');
$wa_position = get_setting('whatsapp_position', 'bottom-right');
$is_left = ($wa_position === 'bottom-left');
$encoded_default_msg = rawurlencode($wa_default_msg);
$direct_wa_url = "https://wa.me/{$wa_number}?text={$encoded_default_msg}";
?>

<!-- WhatsApp Floating Chat Widget -->
<div id="shopvista-wa-widget" class="sv-wa-widget <?= $is_left ? 'sv-wa-left' : 'sv-wa-right'; ?>">
    <!-- Chat Popup Box -->
    <div id="sv-wa-popup" class="sv-wa-popup">
        <div class="sv-wa-header">
            <div class="sv-wa-avatar-wrap">
                <div class="sv-wa-avatar">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="sv-wa-online-dot"></span>
                </div>
                <div class="sv-wa-cs-info">
                    <div class="sv-wa-name-row">
                        <span class="sv-wa-name"><?= htmlspecialchars($wa_cs_name); ?></span>
                        <svg class="sv-wa-badge-check" width="14" height="14" viewBox="0 0 24 24" fill="#25D366" stroke="#ffffff" stroke-width="2">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <span class="sv-wa-status"><?= htmlspecialchars($wa_cs_status); ?></span>
                </div>
            </div>
            <button type="button" id="sv-wa-close-btn" class="sv-wa-close" title="Tutup Chat">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="sv-wa-body">
            <div class="sv-wa-timestamp">Hari ini</div>
            <div class="sv-wa-bubble">
                <p>Halo! 👋 Selamat datang di <strong>ShopVista</strong>.</p>
                <p style="margin-top: 6px;">Ada yang bisa kami bantu mengenai ketersediaan produk, promo, atau status pesanan Anda?</p>
                <span class="sv-wa-time"><?= date('H:i'); ?></span>
            </div>
        </div>

        <div class="sv-wa-footer">
            <div class="sv-wa-input-wrap">
                <input type="text" id="sv-wa-custom-msg" placeholder="Tulis pesan Anda..." value="<?= htmlspecialchars($wa_default_msg); ?>">
            </div>
            <a href="<?= $direct_wa_url; ?>" id="sv-wa-send-btn" target="_blank" rel="noopener noreferrer" class="sv-wa-btn-send">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.062-2.115-.527-1.706-.707-2.793-2.445-2.879-2.56-.084-.114-.689-.917-.689-1.748 0-.832.433-1.24.588-1.398.154-.157.337-.197.45-.197.113 0 .227.001.326.006.104.005.244-.04.381.289.144.347.491 1.2.534 1.287.043.088.072.19.014.304-.058.113-.087.185-.173.286-.087.1-.183.224-.262.301-.087.086-.177.18-.076.353.101.174.45 1.744 1.83 2.133.208.059.336.052.463-.038.127-.089.544-.634.689-.851.145-.218.289-.182.487-.109.198.073 1.258.593 1.474.701.217.109.362.163.415.253.053.09.053.524-.091.929z"/>
                </svg>
                <span>Mulai Chat di WhatsApp</span>
            </a>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button type="button" id="sv-wa-toggle-btn" class="sv-wa-fab" aria-label="Chat WhatsApp ShopVista">
        <span class="sv-wa-pulse"></span>
        <div class="sv-wa-fab-icon">
            <!-- WhatsApp Official Vector Icon -->
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2.05 21.95l4.908-1.332A9.957 9.957 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm.014 17.5a7.96 7.96 0 0 1-4.08-1.118l-.292-.174-3.033.823.837-2.956-.19-.303A7.957 7.957 0 0 1 4.014 12c0-4.411 3.589-8 8-8s8 3.589 8 8c0 4.411-3.589 8-7.986 8zm4.37-5.992c-.24-.12-1.42-.7-1.64-.78-.22-.08-.38-.12-.54.12-.16.24-.62.78-.76.94-.14.16-.28.18-.52.06s-1.014-.374-1.932-1.192c-.714-.637-1.196-1.424-1.336-1.664-.14-.24-.015-.37.105-.49.108-.107.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.195-.467-.393-.404-.54-.411-.14-.008-.3-.01-.46-.01s-.42.06-.64.3c-.22.24-.84.82-.84 2 0 1.18.86 2.32.98 2.48.12.16 1.69 2.58 4.1 3.62.573.248 1.02.396 1.37.507.575.183 1.098.157 1.512.095.46-.069 1.42-.58 1.62-1.14.2-.56.2-1.04.14-1.14-.06-.1-.22-.16-.46-.28z" fill="#FFFFFF"/>
            </svg>
        </div>
        <span class="sv-wa-badge-online"></span>
        <div class="sv-wa-tooltip">
            <span>Chat dengan Kami 👋</span>
        </div>
    </button>
</div>

<style>
/* ============================================================
   ShopVista - WhatsApp Floating Chat Widget Styles
   ============================================================ */
.sv-wa-widget {
    position: fixed;
    bottom: 26px;
    z-index: 99999;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}
.sv-wa-right {
    right: 26px;
}
.sv-wa-left {
    left: 26px;
}

/* Floating Action Button (FAB) */
.sv-wa-fab {
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    border: none;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4), 0 2px 6px rgba(0, 0, 0, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    outline: none;
    -webkit-tap-highlight-color: transparent;
}
.sv-wa-fab:hover {
    transform: scale(1.08) translateY(-2px);
    box-shadow: 0 12px 28px rgba(37, 211, 102, 0.5), 0 4px 10px rgba(0, 0, 0, 0.15);
}
.sv-wa-fab:active {
    transform: scale(0.96);
}

/* Pulse Animation Ring */
.sv-wa-pulse {
    position: absolute;
    top: -4px;
    left: -4px;
    right: -4px;
    bottom: -4px;
    border-radius: 50%;
    border: 2px solid #25D366;
    animation: svWaPulse 2.2s infinite cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
    opacity: 0.6;
}
@keyframes svWaPulse {
    0% {
        transform: scale(0.95);
        opacity: 0.8;
    }
    70% {
        transform: scale(1.35);
        opacity: 0;
    }
    100% {
        transform: scale(1.35);
        opacity: 0;
    }
}

.sv-wa-fab-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
}

.sv-wa-badge-online {
    position: absolute;
    top: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    background: #10B981;
    border: 2.5px solid #FFFFFF;
    border-radius: 50%;
    z-index: 3;
}

/* Tooltip */
.sv-wa-tooltip {
    position: absolute;
    right: 74px;
    background: #0F172A;
    color: #FFFFFF;
    font-size: 0.82rem;
    font-weight: 600;
    padding: 7px 14px;
    border-radius: 20px;
    white-space: nowrap;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.18);
    opacity: 0;
    visibility: hidden;
    transform: translateX(10px);
    transition: all 0.25s ease;
    pointer-events: none;
}
.sv-wa-left .sv-wa-tooltip {
    right: auto;
    left: 74px;
    transform: translateX(-10px);
}
.sv-wa-fab:hover .sv-wa-tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
}

/* Popup Chat Box */
.sv-wa-popup {
    position: absolute;
    bottom: 76px;
    width: 340px;
    max-width: calc(100vw - 40px);
    background: #FFFFFF;
    border-radius: 18px;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.16), 0 4px 12px rgba(15, 23, 42, 0.08);
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    transform: scale(0.92) translateY(15px);
    transform-origin: bottom right;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    z-index: 99999;
}
.sv-wa-right .sv-wa-popup {
    right: 0;
    transform-origin: bottom right;
}
.sv-wa-left .sv-wa-popup {
    left: 0;
    transform-origin: bottom left;
}
.sv-wa-popup.sv-wa-active {
    opacity: 1;
    visibility: visible;
    transform: scale(1) translateY(0);
}

/* Popup Header */
.sv-wa-header {
    background: linear-gradient(135deg, #075E54 0%, #128C7E 100%);
    padding: 16px 18px;
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.sv-wa-avatar-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}
.sv-wa-avatar {
    position: relative;
    width: 42px;
    height: 42px;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
}
.sv-wa-online-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background: #25D366;
    border: 2px solid #075E54;
    border-radius: 50%;
}
.sv-wa-cs-info {
    display: flex;
    flex-direction: column;
}
.sv-wa-name-row {
    display: flex;
    align-items: center;
    gap: 5px;
}
.sv-wa-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: #FFFFFF;
    line-height: 1.2;
}
.sv-wa-badge-check {
    flex-shrink: 0;
}
.sv-wa-status {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.85);
    margin-top: 3px;
}
.sv-wa-close {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.8);
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.sv-wa-close:hover {
    color: #FFFFFF;
    background: rgba(255, 255, 255, 0.15);
}

/* Popup Body */
.sv-wa-body {
    background: #EFEAE2 url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" opacity="0.04" viewBox="0 0 60 60"><path d="M30 10l5 10h10l-8 6 3 10-10-6-10 6 3-10-8-6h10z" fill="%23000"/></svg>') repeat;
    padding: 18px 16px;
    min-height: 150px;
    display: flex;
    flex-direction: column;
}
.sv-wa-timestamp {
    align-self: center;
    background: rgba(255, 255, 255, 0.85);
    color: #54656F;
    font-size: 0.72rem;
    font-weight: 500;
    padding: 3px 10px;
    border-radius: 12px;
    margin-bottom: 12px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.sv-wa-bubble {
    align-self: flex-start;
    background: #FFFFFF;
    padding: 10px 14px 8px 14px;
    border-radius: 0 12px 12px 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    max-width: 90%;
    position: relative;
}
.sv-wa-bubble::before {
    content: '';
    position: absolute;
    top: 0;
    left: -8px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 8px 8px 0;
    border-color: transparent #FFFFFF transparent transparent;
}
.sv-wa-bubble p {
    margin: 0;
    font-size: 0.85rem;
    color: #111B21;
    line-height: 1.4;
}
.sv-wa-time {
    display: block;
    text-align: right;
    font-size: 0.68rem;
    color: #667781;
    margin-top: 5px;
}

/* Popup Footer */
.sv-wa-footer {
    background: #F0F2F5;
    padding: 14px 16px;
    border-top: 1px solid #E9EDEF;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.sv-wa-input-wrap input {
    width: 100%;
    background: #FFFFFF;
    border: 1px solid #D1D7DB;
    border-radius: 20px;
    padding: 9px 14px;
    font-size: 0.82rem;
    color: #111B21;
    outline: none;
    box-sizing: border-box;
    transition: border-color 0.2s;
}
.sv-wa-input-wrap input:focus {
    border-color: #25D366;
    box-shadow: 0 0 0 2px rgba(37, 211, 102, 0.2);
}
.sv-wa-btn-send {
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    color: #FFFFFF;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 24px;
    font-size: 0.88rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35);
    transition: all 0.25s ease;
}
.sv-wa-btn-send:hover {
    color: #FFFFFF;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(37, 211, 102, 0.45);
}
.sv-wa-btn-send:active {
    transform: scale(0.98);
}

@media (max-width: 576px) {
    .sv-wa-widget {
        bottom: 20px;
    }
    .sv-wa-right {
        right: 18px;
    }
    .sv-wa-left {
        left: 18px;
    }
    .sv-wa-fab {
        width: 54px;
        height: 54px;
    }
    .sv-wa-fab svg {
        width: 30px;
        height: 30px;
    }
    .sv-wa-popup {
        width: calc(100vw - 36px);
        bottom: 70px;
    }
    .sv-wa-tooltip {
        display: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggleBtn = document.getElementById('sv-wa-toggle-btn');
    var closeBtn = document.getElementById('sv-wa-close-btn');
    var popup = document.getElementById('sv-wa-popup');
    var customMsgInput = document.getElementById('sv-wa-custom-msg');
    var sendBtn = document.getElementById('sv-wa-send-btn');
    var baseWaNumber = "<?= $wa_number; ?>";

    if (!toggleBtn || !popup) return;

    function openChat() {
        popup.classList.add('sv-wa-active');
        if (customMsgInput) {
            setTimeout(function() {
                customMsgInput.focus();
            }, 250);
        }
    }

    function closeChat() {
        popup.classList.remove('sv-wa-active');
    }

    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (popup.classList.contains('sv-wa-active')) {
            closeChat();
        } else {
            openChat();
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeChat();
        });
    }

    // Update WhatsApp wa.me URL dynamically based on user typed text
    function updateSendUrl() {
        var msg = customMsgInput ? customMsgInput.value.trim() : '';
        if (!msg) {
            msg = "Halo ShopVista, saya tertarik untuk bertanya seputar produk/pesanan saya...";
        }
        sendBtn.href = "https://wa.me/" + baseWaNumber + "?text=" + encodeURIComponent(msg);
    }

    if (customMsgInput) {
        customMsgInput.addEventListener('input', updateSendUrl);
        customMsgInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                updateSendUrl();
                window.open(sendBtn.href, '_blank');
            }
        });
    }

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        var widget = document.getElementById('shopvista-wa-widget');
        if (widget && !widget.contains(e.target)) {
            closeChat();
        }
    });
});
</script>
