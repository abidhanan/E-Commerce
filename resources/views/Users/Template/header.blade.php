 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>@yield('title', 'Gloaming Imagine - Performance Cycling')</title>
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@300;400;500;600;700&display=swap"
     rel="stylesheet">
 <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
 <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
 <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
 <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">


 <style>
     *,
     *::before,
     *::after {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
     }

     body {
         font-family: 'Libre Franklin', -apple-system, BlinkMacSystemFont,
             'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
         background: #fff;
         color: #000;
         overflow-x: hidden;
     }


     body {
         transition:
             background-color 0.55s cubic-bezier(0.4, 0, 0.2, 1),
             color 0.55s cubic-bezier(0.4, 0, 0.2, 1);
     }

     *,
     *::before,
     *::after {
         transition:
             background-color 0.55s cubic-bezier(0.4, 0, 0.2, 1),
             border-color 0.55s cubic-bezier(0.4, 0, 0.2, 1),
             color 0.55s cubic-bezier(0.4, 0, 0.2, 1),
             box-shadow 0.55s cubic-bezier(0.4, 0, 0.2, 1),
             filter 0.55s cubic-bezier(0.4, 0, 0.2, 1) !important;
     }

     /* ── Ripple overlay saat toggle tema ── */
     .theme-ripple {
         position: fixed;
         border-radius: 50%;
         pointer-events: none;
         z-index: 99998;
         transform: scale(0);
         opacity: 0.18;
         will-change: transform, opacity;
     }

     .theme-ripple.expanding {
         transform: scale(1) !important;
         opacity: 0 !important;
     }

     /* ── Animasi spin icon toggle dark mode ── */
     .dark-mode-toggle .toggle-icon {
         display: inline-block;
     }

     .dark-mode-toggle.spinning .toggle-icon {
         animation: spin-bounce 0.55s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
     }

     @keyframes spin-bounce {
         0% {
             transform: rotate(0deg) scale(1);
         }

         50% {
             transform: rotate(200deg) scale(1.5);
         }

         100% {
             transform: rotate(360deg) scale(1);
         }
     }

     /* ── Tombol Toggle Dark Mode ── */
     .dark-mode-toggle {
         background: none;
         border: none;
         cursor: pointer;
         display: flex;
         align-items: center;
         gap: 6px;
         font-family: 'Libre Franklin', sans-serif;
         font-size: 14px;
         color: #000;
         padding: 0;
         /* PERBAIKAN: hapus transition di sini, sudah di-handle global */
     }

     .dark-mode-toggle:hover {
         opacity: 0.6;
     }

     /* Ikon berbentuk lingkaran setengah (crescent) */
     .dark-mode-toggle .toggle-icon {
         width: 16px;
         height: 16px;
         border: 1px solid currentColor;
         border-radius: 50%;
         line-height: 1;
         position: relative;
         flex-shrink: 0;
     }

     /* Light mode: setengah lingkaran kanan terisi */
     .dark-mode-toggle .toggle-icon::after {
         content: '';
         position: absolute;
         top: 2px;
         right: 2px;
         width: 6px;
         height: 10px;
         border-radius: 50%;
         background: currentColor;
     }

     /* ── DARK MODE: Warna Dasar Body ── */
     body.dark-mode {
         background: #111;
         color: #f0f0f0;
         scrollbar-color: #fff #222;
         /* Firefox scrollbar */
     }

     /* Dark mode: scrollbar WebKit */
     body.dark-mode::-webkit-scrollbar {
         width: 8px;
     }

     body.dark-mode::-webkit-scrollbar-track {
         background: #222;
     }

     body.dark-mode::-webkit-scrollbar-thumb {
         background: #fff;
         border-radius: 4px;
     }

     body.dark-mode::-webkit-scrollbar-thumb:hover {
         background: #ccc;
     }

     /* Dark mode: icon toggle — titik penuh (full moon) */
     body.dark-mode .dark-mode-toggle {
         color: #f0f0f0;
     }

     body.dark-mode .dark-mode-toggle .toggle-icon::after {
         inset: 3px;
         width: auto;
         height: auto;
         border-radius: 50%;
         background: currentColor;
     }

     /* ── DARK MODE: Komponen Umum ──
   Kelompok selector berikut menimpa warna background & teks
   untuk semua section utama halaman. */

     /* Backgrounds section utama */
     body.dark-mode .collection-header,
     body.dark-mode .block-wrapper-outer,
     body.dark-mode .pns-scrollbar,
     body.dark-mode .pns-col-scrollbar,
     body.dark-mode .pns-categories-section,
     body.dark-mode .pns-tab-content,
     body.dark-mode .our-story-section,
     body.dark-mode .pns-marquee-section,
     body.dark-mode .category-nav {
         background: #111 !important;
     }

     /* Warna teks elemen-elemen kategori & story */
     body.dark-mode .collection-title,
     body.dark-mode .pns-cat-label,
     body.dark-mode .pns-cat-item,
     body.dark-mode .our-story-card,
     body.dark-mode .our-story-title,
     body.dark-mode .our-story-cta,
     body.dark-mode .category-name {
         color: #f0f0f0 !important;
     }

     /* Product block dark */
     body.dark-mode .product-block,
     body.dark-mode .product-info {
         background: #111 !important;
     }

     body.dark-mode .product-name {
         color: #f0f0f0 !important;
     }

     body.dark-mode .product-price {
         color: #aaa !important;
     }

     body.dark-mode .product-image-wrapper {
         background: #222 !important;
     }

     /* Tabs dark */
     body.dark-mode .pns-tab {
         color: #666 !important;
     }

     body.dark-mode .pns-tab.active,
     body.dark-mode .pns-tab:hover {
         color: #f0f0f0 !important;
         border-bottom-color: #f0f0f0 !important;
     }

     body.dark-mode .pns-categories-tabs {
         border-bottom-color: #333 !important;
     }

     /* Image wrappers dark */
     body.dark-mode .pns-cat-img-wrap,
     body.dark-mode .our-story-img-wrap {
         background: #222 !important;
     }

     /* Marquee dark */
     body.dark-mode .pns-marquee-text {
         color: #f0f0f0 !important;
     }

     /* Scrollbar tracks dark */
     body.dark-mode .pns-scrollbar-track,
     body.dark-mode .pns-col-scrollbar-track {
         background: #333 !important;
     }

     /* Collection header border dark */
     body.dark-mode .collection-header {
         border-top-color: #333 !important;
     }

     /* Collection nav buttons dark */
     body.dark-mode .collection-nav button {
         border-color:rgba(240, 240, 240, 0) !important;
         color: #f0f0f0 !important;
     }

     body.dark-mode .collection-nav button:hover {
         background:rgba(240, 240, 240, 0) !important;
         color: #111 !important;
     }

     /* Our story border dark */
     body.dark-mode .our-story-section {
         border-top-color: #333 !important;
     }

     body.dark-mode .our-story-card-desc,
     body.dark-mode .subcategory-list li {
         color: #aaa !important;
     }

     body.dark-mode .our-story-card-tag {
         color: #666 !important;
     }

     /* Search dark */
     body.dark-mode .search-box {
         background: #1a1a1a !important;
         border-color: #333 !important;
     }

     body.dark-mode .search-input {
         background: #1a1a1a !important;
         color: #f0f0f0 !important;
     }

     body.dark-mode .search-dropdown {
         background: #1a1a1a !important;
         border-color: #333 !important;
     }

     body.dark-mode .search-result-item {
         color: #f0f0f0 !important;
         border-bottom-color: #2a2a2a !important;
     }

     body.dark-mode .search-result-item:hover {
         background: #2a2a2a !important;
     }

     body.dark-mode .search-no-results {
         color: #888 !important;
     }

     body.dark-mode .search-footer {
         border-top-color: #333 !important;
     }

     /* Sidebars dark (shop & cart) */
     body.dark-mode .cart-sidebar,
     body.dark-mode .shop-sidebar {
         background: #1a1a1a !important;
         color: #f0f0f0 !important;
     }

     body.dark-mode .shop-sidebar-header,
     body.dark-mode .cart-header {
         border-bottom-color: #333 !important;
     }

     body.dark-mode .shop-sidebar-close,
     body.dark-mode .cart-close {
         color: #f0f0f0 !important;
     }

     body.dark-mode .shop-section-title {
         color: #666 !important;
     }

     body.dark-mode .shop-category-item,
     body.dark-mode .shop-mobile-link,
     body.dark-mode .shop-link-simple {
         color: #f0f0f0 !important;
         border-bottom-color: #2a2a2a !important;
     }

     body.dark-mode .shop-sidebar-empty {
         color: #aaa !important;
     }

     body.dark-mode .shop-divider {
         background: #2a2a2a !important;
     }

     /* Wishlist & size selector dark */
     body.dark-mode .wishlist-btn {
         background: #222 !important;
     }

     body.dark-mode .wishlist-btn svg {
         stroke: #f0f0f0 !important;
     }

     body.dark-mode .size-selector {
         background: rgba(26, 26, 26, 0.97) !important;
     }

     body.dark-mode .size-selector .size-option {
         color: #f0f0f0 !important;
     }

     body.dark-mode .size-selector .size-option:hover {
         border-bottom-color: #f0f0f0 !important;
     }

     /* Cart detail items dark */
     body.dark-mode .cart-title {
         color: #f0f0f0 !important;
     }

     body.dark-mode .cart-close {
         color: #f0f0f0 !important;
     }

     body.dark-mode .cart-item {
         border-bottom-color: #2a2a2a !important;
     }

     body.dark-mode .cart-item-image {
         background: #2a2a2a !important;
     }

     body.dark-mode .cart-item-name,
     body.dark-mode .cart-item-price,
     body.dark-mode .cart-empty-title {
         color: #f0f0f0 !important;
     }

     body.dark-mode .cart-item-variant,
     body.dark-mode .cart-item-remove,
     body.dark-mode .cart-empty-text {
         color: #888 !important;
     }

     body.dark-mode .cart-item-remove:hover {
         color: #f0f0f0 !important;
     }

     /* Cart quantity dark */
     body.dark-mode .cart-quantity {
         border-color: #444 !important;
         background: #222 !important;
     }

     body.dark-mode .cart-quantity button {
         color: #f0f0f0 !important;
         background: transparent !important;
     }

     body.dark-mode .cart-quantity button:hover {
         color: #ccc !important;
     }

     body.dark-mode .cart-quantity span {
         color: #f0f0f0 !important;
     }

     /* Cart gift dark */
     body.dark-mode .cart-gift {
         background: #222 !important;
     }

     body.dark-mode .cart-gift-title,
     body.dark-mode .cart-gift-name,
     body.dark-mode .cart-gift-select {
         color: #f0f0f0 !important;
     }

     body.dark-mode .cart-gift-subtitle,
     body.dark-mode .cart-gift-color {
         color: #888 !important;
     }

     /* Cart recommendations dark */
     body.dark-mode .cart-recommendations-title {
         color: #f0f0f0 !important;
     }

     body.dark-mode .cart-recommendation-image {
         background: #2a2a2a !important;
     }

     /* Cart footer dark */
     body.dark-mode .cart-footer {
         background: #1a1a1a !important;
         border-top-color: #333 !important;
     }

     body.dark-mode .cart-subtotal,
     body.dark-mode .cart-total {
         color: #f0f0f0 !important;
     }

     /* Payment icons — invert agar terlihat di bg gelap */
     body.dark-mode .cart-payment-icon {
         filter: invert(1) brightness(0.85) !important;
     }

     body.dark-mode .cart-payment-methods span {
         color: #666 !important;
     }

     /* Checkout button dark (invert) */
     body.dark-mode .cart-checkout {
         background: #f0f0f0 !important;
         color: #111 !important;
     }

     body.dark-mode .cart-checkout:hover {
         background: #ccc !important;
     }

     /* Newsletter dark */
     body.dark-mode .newsletter-section {
         background: #1a1a1a !important;
         border-bottom-color: #333 !important;
     }

     body.dark-mode .newsletter-label,
     body.dark-mode .newsletter-title {
         color: #f0f0f0 !important;
     }

     body.dark-mode .newsletter-btn {
         background: #f0f0f0 !important;
         color: #111 !important;
         border-color: #f0f0f0 !important;
     }

     body.dark-mode .newsletter-btn:hover {
         background: #333 !important;
         color: #f0f0f0 !important;
     }

     /* Footer dark */
     body.dark-mode footer,
     body.dark-mode .footer {
         background: #111 !important;
     }

     body.dark-mode .footer-top {
         border-bottom-color: #333 !important;
     }

     body.dark-mode .footer-column h3 {
         color: #f0f0f0 !important;
     }

     body.dark-mode .footer-links a {
         color: #ccc !important;
     }

     body.dark-mode .footer-links a:hover {
         opacity: 0.6;
     }

     body.dark-mode .footer-legal a,
     body.dark-mode .footer-social a,
     body.dark-mode .footer-copyright {
         color: #aaa !important;
     }

     body.dark-mode .shipping-info select {
         background-color: #222 !important;
         color: #f0f0f0 !important;
         border-color: #555 !important;
     }

     /* Header badge dark */
     body.dark-mode .header-badge {
         background: #fff;
         color: #111;
     }

     /* Site main dark */
     body.dark-mode .site-main {
         background: #111;
     }


     /* ============================================================
   03. HEADER & NAVIGASI
   ============================================================ */

     /* ── 03a. Header Dasar & Scroll States ── */

     header {
         position: sticky;
         top: 0;
         background: #fff;
         /* border-bottom: 1px solid #e0e0e0; */
         z-index: 100;
         
         transition:
             background-color 0.45s cubic-bezier(0.4, 0, 0.2, 1),
             border-color 0.45s cubic-bezier(0.4, 0, 0.2, 1),
             box-shadow 0.45s cubic-bezier(0.4, 0, 0.2, 1),
             backdrop-filter 0.45s cubic-bezier(0.4, 0, 0.2, 1) !important;
     }

     /* Light mode — posisi paling atas: solid putih */
     header.at-top {
         background: #fff !important;
         /* border-bottom-color: #e0e0e0 !important; */
         backdrop-filter: none !important;
         box-shadow: none !important;
     }

     /* Light mode — scroll ke atas: solid + shadow */
     header.scrolled {
         background: #fff !important;
         backdrop-filter: none !important;
         -webkit-backdrop-filter: none !important;
         box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07) !important;
     }

     /* Light mode — scroll ke bawah: frosted glass transparan */
     header.scrolling-down {
         background: rgba(255, 255, 255, 0.55) !important;
         backdrop-filter: blur(18px) saturate(180%) !important;
         -webkit-backdrop-filter: blur(18px) saturate(180%) !important;
         box-shadow: none !important;
     }

     /* ── 03b. Header Dark Mode States ── */

     /* Dark mode — posisi paling atas: transparan penuh */
     body.dark-mode header.at-top {
         background: transparent !important;
         /* border-bottom-color: transparent !important; */
         backdrop-filter: none !important;
         box-shadow: none !important;
     }

     body.dark-mode header.at-top .header-main {
         border-bottom-color: transparent !important;
     }

     /* Teks header saat dark + at-top (di atas hero gelap, teks harus putih) */
     body.dark-mode header.at-top .header-left a,
     body.dark-mode header.at-top .header-right a,
     body.dark-mode header.at-top .search-toggle,
     body.dark-mode header.at-top .dark-mode-toggle {
         color: #fff !important;
     }

     /* Dark mode — scroll ke atas: solid gelap */
     body.dark-mode header.scrolled {
         background: #1a1a1a !important;
         backdrop-filter: none !important;
         -webkit-backdrop-filter: none !important;
         /* border-bottom-color: #333 !important; */
         box-shadow: 0 2px 16px rgba(0, 0, 0, 0.4) !important;
     }

     body.dark-mode header.scrolled .header-main {
         border-bottom-color: transparent !important;
     }

     /* Dark mode — scroll ke bawah: frosted glass gelap */
     body.dark-mode header.scrolling-down {
         background: rgba(17, 17, 17, 0.45) !important;
         backdrop-filter: blur(18px) saturate(160%) !important;
         -webkit-backdrop-filter: blur(18px) saturate(160%) !important;
         border-bottom-color: transparent !important;
         box-shadow: none !important;
     }

     body.dark-mode header.scrolling-down .header-main {
         border-bottom-color: transparent !important;
     }

     /* Teks header saat dark + scrolling-down */
     body.dark-mode header.scrolling-down .header-left a,
     body.dark-mode header.scrolling-down .header-right a,
     body.dark-mode header.scrolling-down .search-toggle,
     body.dark-mode header.scrolling-down .dark-mode-toggle {
         color: #fff !important;
     }

     /* Dark mode — teks header default */
     body.dark-mode header {
         background: #1a1a1a !important;
         border-bottom-color: #333 !important;
     }

     body.dark-mode .header-main {
         border-bottom-color: #333 !important;
     }

     body.dark-mode .header-left a,
     body.dark-mode .header-right a,
     body.dark-mode .search-toggle {
         color: #f0f0f0 !important;
     }

     /* ── 03c. Logo & Header Layout ── */

     .header-main {
         padding: 20px 40px;
         display: flex;
         justify-content: space-between;
         align-items: center;
         /* border-bottom: 1px solid #e0e0e0; */
         min-height: 76px;
         gap: 24px;
     }

     .header-left {
         display: flex;
         gap: 35px;
         align-items: center;
         min-width: 0;
         flex: 1;
     }

     .header-left a {
         color: #000;
         text-decoration: none;
         font-size: 14px;
         /* PERBAIKAN: hapus transition eksplisit, sudah di-handle global */
     }

     .header-left a:hover {
         opacity: 0.6;
     }

     /* Logo — center flex item */
     .logo {
         text-align: center;
         flex: 0 1 auto;
         min-width: 160px;
     }

     .logo-image {
         height: 35px;
         width: auto;
         object-fit: contain;
     }

     /* Default: tampilkan light, sembunyikan dark */
    .logo-dark  { display: none; }
    .logo-light { display: block; }


    body.dark-mode .logo-dark  { display: block; }
    body.dark-mode .logo-light { display: none; }

     .logo-text {
         font-size: 16px;
         font-weight: 700;
         letter-spacing: 1.5px;
         text-transform: uppercase;
         line-height: 1.2;
         display: block;
     }

     .logo-subtitle {
         font-size: 8px;
         font-weight: 400;
         letter-spacing: 2px;
         margin-top: 2px;
         display: block;
     }

     .header-right {
         display: flex;
         gap: 30px;
         align-items: center;
         min-width: 0;
         flex: 1;
         justify-content: flex-end;
     }

     .header-right a {
         color: #000;
         text-decoration: none;
         font-size: 14px;
     }

     .header-right a:hover {
         opacity: 0.6;
     }

     /* Badge cart (counter angka di atas icon) */
     .header-badge-wrapper {
         position: relative;
         display: inline-block;
     }

     .header-badge {
         display: none;
         /* tampil via JS saat ada item di cart */
         position: absolute;
         top: -8px;
         right: -12px;
         background: #000;
         color: #fff;
         font-size: 9px;
         font-weight: 700;
         min-width: 16px;
         height: 16px;
         border-radius: 50%;
         text-align: center;
         line-height: 16px;
         padding: 0 3px;
         letter-spacing: 0;
     }

     /* ── 03d. Search Box & Dropdown ── */

     .search-container {
         position: relative;
     }

     .search-toggle {
         color: #000;
         text-decoration: none;
         font-size: 14px;
         cursor: pointer;
         /* PERBAIKAN: gunakan appearance reset agar konsisten lintas browser */
         appearance: none;
         background: none;
         border: 0;
         border-radius: 0;
         font: inherit;
         padding: 0;
     }

     .search-toggle:hover {
         opacity: 0.6;
     }

     /* Search box — awalnya collapsed (width: 0), expand saat .active */
     .search-box {
         position: absolute;
         right: 0;
         top: 50%;
         transform: translateY(-50%);
         width: 0;
         opacity: 0;
         visibility: hidden;
         /* PERBAIKAN: tidak perlu transition eksplisit, sudah global.
     Namun width & visibility tidak ada di global, tambahkan: */
         transition:
             width 0.3s ease,
             opacity 0.3s ease,
             visibility 0.3s ease !important;
         background: #fff;
         border: 1px solid #e0e0e0;
         overflow: visible;
         z-index: 1000;
     }

     .search-box.active {
         width: min(350px, 92vw);
         /* PERBAIKAN: gabung dengan versi responsive */
         opacity: 1;
         visibility: visible;
     }

     .search-input-wrapper {
         position: relative;
     }

     .search-input {
         width: 100%;
         padding: 10px 35px 10px 12px;
         border: none;
         outline: none;
         font-size: 14px;
         font-family: inherit;
     }

     .search-close {
         position: absolute;
         right: 10px;
         top: 50%;
         transform: translateY(-50%);
         background: none;
         border: none;
         font-size: 18px;
         cursor: pointer;
         color: #666;
         padding: 0;
         width: 20px;
         height: 20px;
         display: flex;
         align-items: center;
         justify-content: center;
     }

     .search-close:hover {
         color: #000;
     }

     /* Dropdown hasil pencarian */
     .search-dropdown {
         position: absolute;
         top: 100%;
         left: 0;
         right: 0;
         background: #fff;
         border: 1px solid #e0e0e0;
         border-top: none;
         max-height: 400px;
         overflow-y: auto;
         display: none;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
     }

     .search-dropdown.active {
         display: block;
     }

     .search-result-item {
         display: flex;
         align-items: center;
         gap: 12px;
         padding: 12px;
         text-decoration: none;
         color: #000;
         border-bottom: 1px solid #f5f5f5;
         /* transition background sudah di global */
     }

     .search-result-item:hover {
         background: #f8f8f8;
     }

     .search-result-image {
         width: 50px;
         height: 50px;
         object-fit: cover;
         background: #f5f5f5;
         flex-shrink: 0;
     }

     .search-result-info {
         flex: 1;
     }

     .search-result-name {
         font-size: 13px;
         font-weight: 500;
         margin-bottom: 2px;
     }

     .search-result-variant {
         font-size: 11px;
         color: #666;
     }

     .search-result-price {
         font-size: 13px;
         font-weight: 600;
         flex-shrink: 0;
     }

     .search-footer {
         padding: 12px;
         text-align: center;
         border-top: 1px solid #e0e0e0;
     }

     .search-see-all {
         display: inline-block;
         padding: 8px 24px;
         background: #000;
         color: #fff;
         text-decoration: none;
         font-size: 12px;
         font-weight: 600;
         letter-spacing: 0.5px;
     }

     .search-see-all:hover {
         background: #333;
     }

     .search-no-results {
         padding: 24px;
         text-align: center;
         color: #666;
         font-size: 13px;
     }

     /* Mobile menu toggle (hamburguer) — hidden di desktop */
     .mobile-menu-toggle {
         display: none;
         font-size: 14px;
         appearance: none;
         background: none;
         border: 0;
         border-radius: 0;
         color: #000;
         cursor: pointer;
         font: inherit;
         padding: 0;
     }


     /* ============================================================
   04. TOP BANNER
   ============================================================ */

     .top-banner {
         background: #000;
         color: #fff;
         text-align: center;
         padding: 8px 20px;
         font-size: 11px;
         letter-spacing: 0.5px;
     }


     /* ============================================================
   05. HERO SECTION
   ============================================================
   PERBAIKAN:
   - Gunakan `min-height: clamp(620px, 100svh, 100vh)` agar lebih
     akurat di mobile (svh = small viewport height, menghindari
     address bar browser).
   - Konsolidasikan hero_1/2/3 pseudo-elements karena identik
     kecuali variabel background image.
   ============================================================ */

     .hero {
         position: relative;
         height: 100vh;
         min-height: calc(100svh - 64px);
         background: #2c3e50;
         /* fallback saat gambar belum load */
         display: flex;
         flex-direction: column;
         justify-content: flex-end;
         align-items: flex-start;
         color: #fff;
         overflow: hidden;
         padding: 0 50px 60px 50px;
     }

     /* Background image overlay — gunakan ::before untuk opacity control */
     /* PERBAIKAN: Ketiga varian (hero_1, hero_2, hero_3) identik kecuali
   variabel CSS --bg-img-N. Cukup satu rule, bedakan hanya di HTML
   lewat style="--bg-img-1: url(...)". Jika tetap ingin kelas terpisah,
   pertahankan struktur berikut: */
     .hero_1::before,
     .hero_2::before,
     .hero_3::before {
         content: '';
         position: absolute;
         inset: 0;
         /* PERBAIKAN: ganti top/left/right/bottom dengan inset
         opacity: 0.4; */
     }

     .hero_1::before {
         background: var(--bg-img-1) center center / contain no-repeat;
     }

     .hero_2::before {
         background: var(--bg-img-2) center / contain no-repeat;
     }

     .hero_3::before {
         background: var(--bg-img-3) center / contain no-repeat;
     }

     .hero-content {
         position: relative;
         z-index: 1;
         max-width: 1000px;
         padding: 0 40px;
     }

     .hero-subtitle {
         font-size: 11px;
         letter-spacing: 4px;
         margin-bottom: 30px;
         opacity: 0.95;
         font-weight: 400;
         text-transform: uppercase;
         line-height: 1.7;
     }

     .hero-title {
         font-size: clamp(34px, 6vw, 64px);
         /* PERBAIKAN: gabung dengan helper */
         font-weight: 300;
         letter-spacing: 2px;
         margin-bottom: 50px;
         line-height: 1.1;
         text-transform: uppercase;
     }

     .hero-title .highlight {
         font-weight: 400;
     }

     .hero-buttons {
         display: flex;
         gap: 10px;
     }

     /* ── CTA Button ── */
     .btn {
         padding: 16px 40px;
         font-size: 10px;
         font-weight: 500;
         letter-spacing: 2.5px;
         text-transform: uppercase;
         text-decoration: none;
         border: 1px solid rgba(255, 255, 255, 0.8);
         background: transparent;
         color: #fff;
         cursor: pointer;
         /* transition sudah global */
     }

     .btn:hover {
         background: #fff;
         color: #000;
         border-color: #fff;
     }


     /* ============================================================
   06. PRODUCT CAROUSEL (BLOCK WRAPPER)
   ============================================================ */

     /* ── 06a. Collection Header ── */

     .collection-header {
         padding: 32px 40px 24px;
         background: #fff;
         color: #000;
         display: flex;
         justify-content: space-between;
         align-items: center;
         border-top: 1px solid #e5e5e5;
     }

     .collection-title {
         font-size: 14px;
         letter-spacing: 0.3px;
         font-weight: 400;
         color: #000;
     }

     .collection-nav {
         display: flex;
         gap: 8px;
     }

     .collection-nav button {
         width: 36px;
         height: 36px;
         border: 1px solid #d0d0d000;
         background: transparent;
         color: #000;
         cursor: pointer;
         font-size: 16px;
         display: flex;
         align-items: center;
         justify-content: center;
         font-weight: 300;
     }

     .collection-nav button:hover {
         background: #000;
         border-color: #000;
         color: #fff;
     }

     /* ── 06b. Block Wrapper & Custom Scrollbar ── */

     .block-wrapper-outer {
         background: #fff;
         overflow: hidden;
         padding-bottom: 60px;
     }

     /* Horizontal scroll carousel — scrollbar disembunyikan, diganti custom */
     .block-wrapper {
         display: flex;
         gap: 12px;
         overflow-x: auto;
         scroll-behavior: smooth;
         scrollbar-width: none;
         /* Firefox */
         -ms-overflow-style: none;
         /* IE/Edge */
         cursor: grab;
         user-select: none;
         padding: 0 40px;
     }

     .block-wrapper::-webkit-scrollbar {
         display: none;
     }

     .block-wrapper.grabbing {
         cursor: grabbing;
     }

     /* Custom scrollbar track (centered, minimalis) */
     .pns-scrollbar {
         display: flex;
         justify-content: center;
         align-items: center;
         padding: 16px 0 24px;
         background: #fff;
     }

     .pns-scrollbar-track {
         width: 80px;
         height: 4px;
         background: #e0e0e0;
         position: relative;
         border-radius: 5px;
     }

     .pns-scrollbar-thumb {
         position: absolute;
         top: 0;
         left: 0;
         height: 4px;
         background: #000;
         border-radius: 2px;
         /* transition left dihandle JS, tapi tetap tambahkan untuk smooth */
         transition: left 0.1s linear !important;
     }

     /* ── 06c. Product Block, Image, Info ── */

     .product-block {
         position: relative;
         background: #fff;
         border: none;
         text-decoration: none;
         display: flex;
         flex-direction: column;
         /* Menampilkan 4.2 item sekaligus */
         flex: 0 0 calc((100% - 40px * 2 - 12px * 3) / 4.2);
         min-width: calc((100% - 40px * 2 - 12px * 3) / 4.2);
         /* PERBAIKAN: hapus transition:none karena ini blok parent.
     Transisi pada child element sudah spesifik. */
     }

     .product-block:last-child {
         border-right: none;
     }

     .product-block:hover {
         z-index: 10;
     }

     /* Wrapper gambar produk dengan aspect ratio 3:4 */
     .product-image-wrapper {
         position: relative;
         width: 100%;
         padding-bottom: 130%;
         /* trick aspect-ratio lama, bisa ganti dengan aspect-ratio: 3/4 */
         overflow: hidden;
         background: #f0f0f0;
         border-radius: 8px;
     }

     /* PERBAIKAN: Ganti padding-bottom trick dengan property modern */
     /* .product-image-wrapper { aspect-ratio: 3 / 4; } */

     .product-image {
         position: absolute;
         inset: 0;
         /* PERBAIKAN: ganti top/left + width/height 100% dengan inset */
         width: 100%;
         height: 100%;
         object-fit: cover;
         /* CATATAN: transition opacity di sini akan ditimpa !important global.
     Tambahkan exception jika butuh 0.4s berbeda dari 0.55s global. */
         transition: opacity 0.4s ease !important;
     }

     .product-image.img-main {
         opacity: 1;
         z-index: 1;
     }

     .product-image.img-hover {
         opacity: 0;
         z-index: 2;
     }

     /* Swap gambar saat hover */
     .product-block:hover .img-main {
         opacity: 0;
     }

     .product-block:hover .img-hover {
         opacity: 1;
     }

     /* Info teks di bawah gambar */
     .product-info {
         padding: 10px 2px 14px;
         background: #fff;
         flex-grow: 1;
         display: flex;
         flex-direction: column;
     }

     .product-name {
         font-size: 14px;
         font-weight: 500;
         color: #000;
         margin-bottom: 5px;
         line-height: 1.3;
     }

     .product-price {
         font-size: 13px;
         color: #666;
         font-weight: 400;
     }

     /* Hidden by design — bisa dihapus jika tidak dipakai */
     .product-variant,
     .product-sizes {
         display: none;
     }

     /* ── 06d. Badge, Wishlist, Size Selector ── */

     .product-badges {
         position: absolute;
         top: 0;
         left: 0;
         right: 0;
         display: flex;
         justify-content: space-between;
         align-items: flex-start;
         padding: 10px;
         z-index: 5;
     }

     .new-arrival-badge {
         background: #000;
         color: #fff;
         font-size: 11px;
         letter-spacing: 0.5px;
         padding: 5px 10px;
         text-transform: uppercase;
         font-weight: 600;
     }

     /* Wishlist — muncul saat hover product */
     .wishlist-btn {
         width: 40px;
         height: 40px;
         background: #fff;
         border: none;
         border-radius: 50%;
         cursor: pointer;
         display: flex;
         align-items: center;
         justify-content: center;
         z-index: 4;
         opacity: 0;
         box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
         margin-left: auto;
         flex-shrink: 0;
         /* PERBAIKAN: transition opacity 0.3s berbeda dari global 0.55s */
         transition: opacity 0.3s !important;
     }

     .product-block:hover .wishlist-btn {
         opacity: 1;
     }

     .wishlist-btn svg {
         width: 20px;
         height: 20px;
         stroke: #000;
         fill: none;
     }

     .wishlist-btn:hover svg,
     .wishlist-btn.active svg {
         fill: #000;
     }

     .wishlist-btn.active {
         background: #000;
     }

     .wishlist-btn.active svg {
         stroke: #fff;
         fill: #fff;
     }

     /* Size selector — slide up saat hover */
     .size-selector {
         position: absolute;
         bottom: 0;
         left: 0;
         right: 0;
         background: rgba(255, 255, 255, 0.95);
         padding: 15px;
         display: flex;
         justify-content: center;
         gap: 10px;
         opacity: 0;
         transform: translateY(10px);
         /* PERBAIKAN: opacity & transform transition tidak ada di global */
         transition: opacity 0.3s ease, transform 0.3s ease !important;
         z-index: 3;
     }

     .product-block:hover .size-selector {
         opacity: 1;
         transform: translateY(0);
     }

     .size-selector .size-option {
         padding: 8px;
         font-size: 12px;
         font-weight: 500;
         cursor: pointer;
         border-bottom: 2px solid transparent;
         color: #000;
     }

     .size-selector .size-option:hover {
         border-bottom-color: #000;
     }


     /* ============================================================
   07. CONTENT BLOCKS (2-KOLOM EDITORIAL)
   ============================================================ */

     .content-blocks {
         display: grid;
         grid-template-columns: repeat(2, 1fr);
         background: #fff;
     }

     .content-block {
         position: relative;
         overflow: hidden;
         height: 100vh;
         min-height: 700px;
         max-height: 900px;
         background: #000;
         cursor: pointer;
         text-decoration: none;
         display: block;
     }

     .content-block img {
         width: 100%;
         height: 100%;
         object-fit: cover;
         display: block;
         /* PERBAIKAN: transition transform perlu !important karena global hanya cover bg/color */
         transition: transform 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
     }

     .content-block:hover img {
         transform: scale(1.06);
     }

     /* Overlay teks di atas gambar */
     .block-overlay {
         position: absolute;
         bottom: 50px;
         left: 50px;
         color: #fff;
         z-index: 2;
     }

     .block-category {
         font-size: 10px;
         letter-spacing: 3px;
         text-transform: uppercase;
         margin-bottom: 10px;
         opacity: 0.9;
         font-weight: 400;
     }

     .block-title {
         font-size: 36px;
         font-weight: 300;
         line-height: 1.1;
         text-transform: uppercase;
     }


     /* ============================================================
   08. PNS CATEGORIES SECTION
   ============================================================ */

     /* ── 08a. Tabs ── */

     .pns-categories-section {
         background: #fff;
         padding: 0 0 60px 0;
     }

     .pns-categories-tabs {
         display: flex;
         padding: 24px 40px 0;
         border-bottom: 1px solid #e5e5e5;
     }

     .pns-tab {
         background: none;
         border: none;
         border-bottom: 2px solid transparent;
         font-family: 'Libre Franklin', sans-serif;
         font-size: 14px;
         font-weight: 400;
         color: #999;
         cursor: pointer;
         padding: 0 0 14px 0;
         margin-right: 28px;
         /* transition color & border sudah di global */
     }

     .pns-tab.active {
         color: #000;
         border-bottom-color: #000;
     }

     .pns-tab:hover {
         color: #000;
     }

     .pns-tab-content {
         display: none;
         padding: 40px 40px 0;
     }

     .pns-tab-content.active {
         display: block;
     }

     /* ── 08b. Grid Categories & Collections ── */

     /* Tab Categories — 6 kolom, max 850px, centered */
     #tab-categories .pns-grid {
         max-width: 850px;
         margin: 0 auto;
         display: grid;
         grid-template-columns: repeat(6, 1fr);
         gap: 40px;
     }

     /* Tab Collections — horizontal scroll */
     #tab-collections .pns-grid {
         display: flex;
         flex-wrap: nowrap;
         overflow-x: auto;
         scrollbar-width: none;
         -ms-overflow-style: none;
         cursor: grab;
         scroll-snap-type: x mandatory;
     }

     #tab-collections .pns-grid::-webkit-scrollbar {
         display: none;
     }

     #tab-collections .pns-grid.grabbing {
         cursor: grabbing;
     }

     #tab-collections .pns-cat-item {
         flex: 0 0 25%;
         min-width: 25%;
         padding: 0 10px;
         box-sizing: border-box;
         scroll-snap-align: start;
     }

     #tab-collections .pns-cat-img-wrap {
         border-radius: 16px;
     }

     /* Grid default (fallback) */
     .pns-grid {
         display: grid;
         grid-template-columns: repeat(6, 1fr);
         gap: 2px;
     }

     /* Item kategori */
     .pns-cat-item {
         text-decoration: none;
         color: #000;
         display: flex;
         flex-direction: column;
         gap: 10px;
         cursor: pointer;
     }

     .pns-cat-img-wrap {
         width: 100%;
         aspect-ratio: 3 / 4;
         /* PERBAIKAN: gunakan property modern */
         background: #f0f0f0;
         overflow: hidden;
         position: relative;
     }

     /* Aktif state — outline */
     .pns-cat-item--active .pns-cat-img-wrap {
         outline: 2px solid #000;
         outline-offset: -2px;
     }

     .pns-cat-img-wrap img {
         width: 100%;
         height: 100%;
         object-fit: cover;
         object-position: center top;
         display: block;
         /* transition transform perlu !important */
         transition: transform 0.5s ease !important;
     }

     .pns-cat-item:hover .pns-cat-img-wrap img {
         transform: scale(1.04);
     }

     .pns-cat-label {
         font-size: 13px;
         font-weight: 500;
         text-align: left;
         color: #000;
         padding: 0 2px 14px;
         line-height: 1.3;
     }

     /* ── 08c. Scrollbar Collections ── */

     .pns-col-scrollbar {
         display: flex;
         justify-content: center;
         align-items: center;
         padding: 16px 0 24px;
         background: #fff;
     }

     .pns-col-scrollbar-track {
         width: 80px;
         height: 4px;
         background: #e0e0e0;
         position: relative;
         border-radius: 1px;
     }

     .pns-col-scrollbar-thumb {
         position: absolute;
         top: 0;
         left: 0;
         height: 4px;
         background: #000;
         border-radius: 2px;
         transition: left 0.1s linear !important;
     }


     /* ============================================================
   09. PNS MARQUEE TICKER
   ============================================================
   PERBAIKAN:
   - Tambahkan `prefers-reduced-motion` untuk aksesibilitas.
   ============================================================ */

     .pns-marquee-section {
         background: #fff;
         overflow: hidden;
         padding: 0;
     }

     .pns-marquee-track {
         overflow: hidden;
         width: 100%;
     }

     .pns-marquee-inner {
         display: flex;
         align-items: center;
         white-space: nowrap;
         animation: pns-marquee-scroll 8s linear infinite;
         will-change: transform;
     }

     /* Pause on hover */
     .pns-marquee-section:hover .pns-marquee-inner {
         animation-play-state: paused;
     }

     /* PERBAIKAN: Hentikan animasi bagi user yang prefer reduced motion */
     @media (prefers-reduced-motion: reduce) {
         .pns-marquee-inner {
             animation: none;
         }
     }

     @keyframes pns-marquee-scroll {
         0% {
             transform: translateX(0);
         }

         100% {
             transform: translateX(-50%);
         }
     }

     .pns-marquee-item {
         display: inline-flex;
         align-items: center;
         gap: 20px;
         padding: 0 40px;
         flex-shrink: 0;
     }

     .pns-marquee-text {
         font-family: 'Libre Franklin', sans-serif;
         font-size: clamp(52px, 14vw, 120px);
         /* PERBAIKAN: gabung dengan helper */
         font-weight: 400;
         color: #000;
         letter-spacing: -0.5px;
         line-height: 1;
         white-space: nowrap;
         padding: 60px 0 40px;
     }

     .pns-marquee-img {
         display: inline-flex;
         align-items: center;
         flex-shrink: 0;
     }

     .pns-marquee-img img {
         width: clamp(60px, 7vw, 110px);
         height: clamp(75px, 9vw, 140px);
         object-fit: cover;
         object-position: top center;
         display: block;
         vertical-align: middle;
     }


     /* ============================================================
   10. CATEGORY NAVIGATION (footer-style nav grid)
   ============================================================ */

     .category-nav {
         padding: 80px 40px;
         background: #f5f5f5;
     }

     .category-grid {
         display: grid;
         grid-template-columns: repeat(6, 1fr);
         gap: 40px;
         max-width: 1400px;
         margin: 0 auto;
     }

     .category-grid--spaced {
         margin-top: 60px;
     }

     .category-item {
         text-align: left;
     }

     .category-name {
         font-size: 13px;
         font-weight: 500;
         margin-bottom: 15px;
         letter-spacing: 0.5px;
     }

     .subcategory-list {
         list-style: none;
         padding: 0;
     }

     .subcategory-list li {
         font-size: 12px;
         color: #666;
         margin-bottom: 8px;
         cursor: pointer;
     }

     .subcategory-list li:hover {
         color: #000;
     }


     /* ============================================================
   11. FEATURED SECTION (dark CTA block)
   ============================================================ */

     .featured-section {
         padding: 120px 40px;
         background: #1a1a1a;
         color: #fff;
         text-align: center;
     }

     .featured-content {
         max-width: 700px;
         margin: 0 auto;
     }

     .featured-subtitle {
         font-size: 10px;
         letter-spacing: 3px;
         margin-bottom: 20px;
         opacity: 0.8;
         font-weight: 400;
     }

     .featured-title {
         font-size: 42px;
         font-weight: 300;
         letter-spacing: 1px;
         margin-bottom: 25px;
     }

     .featured-description {
         font-size: 14px;
         line-height: 1.8;
         color: rgba(255, 255, 255, 0.8);
         margin-bottom: 40px;
         font-weight: 300;
     }

     .featured-section .btn {
         border-color: rgba(255, 255, 255, 0.6);
     }


     /* ============================================================
   12. OUR STORY SECTION
   ============================================================ */

     .our-story-section {
         background: #fff;
         padding: 80px 40px 100px;
         border-top: 1px solid #e5e5e5;
     }

     .our-story-header {
         display: flex;
         justify-content: space-between;
         align-items: flex-end;
         margin-bottom: 48px;
     }

     .our-story-label {
         font-size: 11px;
         letter-spacing: 4px;
         text-transform: uppercase;
         color: #999;
         font-weight: 400;
         margin-bottom: 10px;
     }

     .our-story-title {
         font-size: 42px;
         font-weight: 300;
         letter-spacing: -0.5px;
         line-height: 1.1;
         color: #000;
     }

     .our-story-cta {
         font-size: 12px;
         font-weight: 500;
         letter-spacing: 1.5px;
         text-transform: uppercase;
         color: #000;
         text-decoration: none;
         border-bottom: 1px solid #000;
         padding-bottom: 2px;
         white-space: nowrap;
         align-self: flex-end;
     }

     .our-story-cta:hover {
         opacity: 0.5;
     }

     /* 3-kolom grid kartu editorial */
     .our-story-grid {
         display: grid;
         grid-template-columns: repeat(3, 1fr);
         gap: 20px;
     }

     .our-story-card {
         display: flex;
         flex-direction: column;
         gap: 20px;
         text-decoration: none;
         color: #000;
     }

     .our-story-img-wrap {
         width: 100%;
         aspect-ratio: 3 / 4;
         /* PERBAIKAN: property modern */
         overflow: hidden;
         background: #f0f0f0;
         position: relative;
     }

     .our-story-img-wrap img {
         width: 100%;
         height: 100%;
         object-fit: cover;
         object-position: center top;
         display: block;
         transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
     }

     .our-story-card:hover .our-story-img-wrap img {
         transform: scale(1.04);
     }

     .our-story-card-body {
         padding: 0 4px;
     }

     .our-story-card-tag {
         font-size: 10px;
         letter-spacing: 3px;
         text-transform: uppercase;
         color: #999;
         margin-bottom: 10px;
     }

     .our-story-card-title {
         font-size: 20px;
         font-weight: 400;
         line-height: 1.3;
         margin-bottom: 12px;
         letter-spacing: -0.2px;
     }

     .our-story-card-desc {
         font-size: 13px;
         color: #666;
         line-height: 1.7;
         font-weight: 300;
     }


     /* ============================================================
   13. CART SIDEBAR
   ============================================================ */

     /* Overlay backdrop saat cart terbuka */
     .cart-overlay {
         position: fixed;
         inset: 0;
         /* PERBAIKAN: ganti top/left/width/height */
         background: rgba(0, 0, 0, 0.5);
         z-index: 9999;
         opacity: 0;
         pointer-events: none;
         transition: opacity 0.4s ease !important;
     }

     .cart-overlay.open {
         opacity: 1;
         pointer-events: all;
     }

     /* Sidebar panel — slide dari kanan */
     .cart-sidebar {
         position: fixed;
         top: 0;
         right: -450px;
         width: 450px;
         height: 100vh;
         background: #fff;
         z-index: 10000;
         box-shadow: -2px 0 20px rgba(0, 0, 0, 0.1);
         transition: right 0.4s ease !important;
         overflow-y: auto;
     }

     .cart-sidebar.open {
         right: 0;
     }

     /* ── 13a. Cart Header, Item, Quantity, Gift ── */

     .cart-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         padding: 25px 30px;
         border-bottom: 1px solid #e0e0e0;
     }

     .cart-title {
         font-size: 18px;
         font-weight: 600;
         letter-spacing: 0.5px;
     }

     .cart-close {
         background: none;
         border: none;
         font-size: 24px;
         cursor: pointer;
         padding: 0;
         width: 30px;
         height: 30px;
         display: flex;
         align-items: center;
         justify-content: center;
     }

     .cart-close:hover {
         opacity: 0.6;
     }

     .cart-content {
         padding: 30px;
     }

     .cart-item {
         display: flex;
         gap: 20px;
         padding-bottom: 30px;
         margin-bottom: 30px;
         border-bottom: 1px solid #e0e0e0;
     }

     .cart-item-image {
         width: 80px;
         height: 80px;
         object-fit: cover;
         background: #f5f5f5;
         flex-shrink: 0;
         /* PERBAIKAN: tambah agar tidak mengkerut */
     }

     .cart-item-details {
         flex: 1;
     }

     .cart-item-name {
         font-size: 14px;
         font-weight: 500;
         margin-bottom: 5px;
     }

     .cart-item-variant {
         font-size: 12px;
         color: #666;
         margin-bottom: 10px;
     }

     .cart-item-price {
         font-size: 14px;
         font-weight: 600;
     }

     .cart-item-actions {
         display: flex;
         justify-content: space-between;
         align-items: center;
         margin-top: 15px;
     }

     .cart-item-remove {
         font-size: 12px;
         color: #666;
         background: none;
         border: none;
         cursor: pointer;
         text-decoration: underline;
         padding: 0;
     }

     .cart-item-remove:hover {
         color: #000;
     }

     .cart-quantity {
         display: flex;
         align-items: center;
         gap: 15px;
         border: 1px solid #e0e0e0;
         padding: 5px 10px;
     }

     .cart-quantity button {
         background: none;
         border: none;
         font-size: 16px;
         cursor: pointer;
         padding: 0;
         width: 20px;
         height: 20px;
         display: flex;
         align-items: center;
         justify-content: center;
     }

     .cart-quantity span {
         font-size: 14px;
         min-width: 20px;
         text-align: center;
     }

     /* Gift dengan item bag free */
     .cart-gift {
         background: #f9f9f9;
         padding: 20px;
         margin-bottom: 30px;
         border-radius: 4px;
     }

     .cart-gift-title {
         font-size: 14px;
         font-weight: 600;
         margin-bottom: 5px;
     }

     .cart-gift-subtitle {
         font-size: 12px;
         color: #666;
         margin-bottom: 15px;
     }

     .cart-gift-item {
         display: flex;
         gap: 15px;
         align-items: center;
     }

     .cart-gift-image {
         width: 50px;
         height: 50px;
         object-fit: cover;
     }

     .cart-gift-name {
         font-size: 13px;
         font-weight: 500;
     }

     .cart-gift-color {
         font-size: 12px;
         color: #666;
     }

     .cart-gift-select {
         margin-left: auto;
         font-size: 12px;
         text-decoration: underline;
         cursor: pointer;
     }

     /* ── 13b. Cart Recommendations ── */

     .cart-recommendations {
         margin-bottom: 30px;
     }

     .cart-recommendations-title {
         font-size: 14px;
         font-weight: 600;
         margin-bottom: 20px;
     }

     .cart-recommendations-grid {
         display: grid;
         grid-template-columns: repeat(3, 1fr);
         gap: 15px;
     }

     .cart-recommendation-item {
         cursor: pointer;
     }

     .cart-recommendation-image {
         width: 100%;
         height: 120px;
         object-fit: cover;
         background: #f5f5f5;
         margin-bottom: 8px;
     }

     /* ── 13c. Cart Footer & Checkout ── */

     .cart-footer {
         padding: 30px;
         border-top: 1px solid #e0e0e0;
         position: sticky;
         bottom: 0;
         background: #fff;
     }

     .cart-subtotal {
         display: flex;
         justify-content: space-between;
         margin-bottom: 10px;
         font-size: 14px;
     }

     .cart-total {
         display: flex;
         justify-content: space-between;
         margin-bottom: 20px;
         font-size: 18px;
         font-weight: 600;
     }

     .cart-payment-methods {
         display: flex;
         gap: 10px;
         justify-content: flex-end;
         margin-bottom: 20px;
         align-items: center;
     }

     .cart-payment-methods span {
         font-size: 11px;
         color: #999;
     }

     .cart-payment-icon {
         width: 35px;
         height: auto;
     }

     .cart-checkout {
         display: block;
         /* PERBAIKAN: gunakan display:block agar width:100% bekerja tanpa flex */
         width: 100%;
         padding: 18px;
         background: #000;
         color: #fff;
         border: none;
         cursor: pointer;
         font-size: 13px;
         letter-spacing: 1.5px;
         text-align: center;
         text-decoration: none;
     }

     .cart-checkout:hover {
         background: #333;
     }

     /* State kosong */
     .cart-empty {
         text-align: center;
         padding: 60px 30px;
     }

     .cart-empty-title {
         font-size: 16px;
         margin-bottom: 10px;
     }

     .cart-empty-text {
         font-size: 13px;
         color: #666;
     }


     /* ============================================================
   14. SHOP SIDEBAR
   ============================================================ */

     .shop-overlay {
         position: fixed;
         inset: 0;
         /* PERBAIKAN: shorthand */
         background: rgba(0, 0, 0, 0.5);
         z-index: 9998;
         opacity: 0;
         pointer-events: none;
         transition: opacity 0.35s ease !important;
     }

     .shop-overlay.open {
         opacity: 1;
         pointer-events: all;
     }

     /* Slide dari kiri */
     .shop-sidebar {
         position: fixed;
         top: 0;
         left: -560px;
         width: 560px;
         height: 100vh;
         background: #fff;
         z-index: 9999;
         transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
         overflow-y: auto;
         display: flex;
         flex-direction: column;
     }

     .shop-sidebar.open {
         left: 0;
     }

     .shop-sidebar-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         padding: 28px 36px 20px;
         border-bottom: 1px solid #f0f0f0;
     }

     .shop-sidebar-header span {
         font-size: 13px;
         font-weight: 600;
         letter-spacing: 1px;
         text-transform: uppercase;
     }

     .shop-sidebar-close {
         background: none;
         border: none;
         font-size: 22px;
         cursor: pointer;
         color: #000;
         line-height: 1;
         padding: 0;
     }

     .shop-sidebar-close:hover {
         opacity: 0.5;
     }

     /* Banner gambar di atas sidebar */
     .shop-sidebar-banner {
         display: block;
         width: 100%;
         height: 300px;
         overflow: hidden;
         position: relative;
         flex-shrink: 0;
         color: inherit;
         text-decoration: none;
     }

     .shop-sidebar-banner img {
         width: 100%;
         height: 100%;
         object-fit: cover;
     }

     .shop-sidebar-banner-label {
         position: absolute;
         bottom: 20px;
         left: 30px;
         background: #fff;
         padding: 8px 16px;
         font-size: 14px;
         font-weight: 600;
     }

     .shop-sidebar-body {
         padding: 0 36px 36px;
         flex: 1;
     }

     .shop-mobile-links {
         display: none;
     }

     .shop-mobile-link {
         display: flex;
         align-items: center;
         min-height: 44px;
         padding: 12px 0;
         border-bottom: 1px solid #f0f0f0;
         color: #000;
         font-size: 14px;
         font-weight: 500;
         letter-spacing: 0.04em;
         text-decoration: none;
         text-transform: uppercase;
     }

     .shop-sidebar-empty {
         padding: 12px 0;
         color: #777;
         font-size: 13px;
         line-height: 1.6;
     }

     .shop-section-title {
         font-size: 11px;
         font-weight: 700;
         letter-spacing: 1.5px;
         text-transform: uppercase;
         color: #999;
         margin: 28px 0 16px;
     }

     .shop-category-grid {
         display: grid;
         grid-template-columns: 1fr 1fr;
         gap: 6px 24px;
     }

     .shop-category-item {
         display: flex;
         align-items: center;
         gap: 14px;
         padding: 10px 0;
         text-decoration: none;
         color: #000;
         font-size: 14px;
         border-bottom: 1px solid #f5f5f5;
     }

     .shop-category-item:hover {
         opacity: 0.5;
     }

     .shop-category-icon {
         width: 36px;
         height: 36px;
         object-fit: cover;
         flex-shrink: 0;
         filter: grayscale(100%);
     }

     .shop-divider {
         height: 1px;
         background: #e8e8e8;
         margin: 8px 0 4px;
     }

     .shop-link-simple {
         display: block;
         padding: 11px 0;
         text-decoration: none;
         color: #000;
         font-size: 14px;
         border-bottom: 1px solid #f5f5f5;
     }

     .shop-link-simple:hover {
         opacity: 0.5;
     }


     /* ============================================================
   15. NEWSLETTER SECTION
   ============================================================ */

     .newsletter-section {
         background: #fff;
         padding: 80px 40px;
         border-bottom: 1px solid #e5e5e5;
     }

     .newsletter-container {
         max-width: 1400px;
         margin: 0 auto;
         display: flex;
         justify-content: space-between;
         align-items: center;
         gap: 40px;
     }

     .newsletter-left {
         flex: 1;
     }

     .newsletter-label {
         font-size: 11px;
         font-weight: 600;
         letter-spacing: 2px;
         text-transform: uppercase;
         margin-bottom: 16px;
         color: #000;
     }

     .newsletter-title {
         font-size: 32px;
         font-weight: 4;
         line-height: 1.3;
         color: #000;
         letter-spacing: -0.5px;
     }

     .newsletter-right {
         flex-shrink: 0;
     }

     .newsletter-btn {
         padding: 16px 48px;
         background: #000;
         color: #fff;
         border: 0px solid #000;
         font-size: 13px;
         font-weight: 500;
         cursor: pointer;
         text-transform: uppercase;
         letter-spacing: 1px;
     }

     .newsletter-btn:hover {
         background: #fff;
         color: #000;
     }


     /* ============================================================
   16. FOOTER
   ============================================================ */

     /* ── 16a. Footer Top (grid kolom) ── */

     .footer {
         background: #fff;
         padding: 60px 40px 40px;
     }

     .footer-container {
         max-width: 1400px;
         margin: 0 40px;
     }

     .footer-top {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
         gap: 200px;
         margin-bottom: 0px;
         padding-bottom: 60px;
         border-bottom: 1px solid #e5e5e5;
     }

     .footer-column h3 {
         font-size: 11px;
         font-weight: 600;
         margin-bottom: 20px;
         text-transform: uppercase;
         letter-spacing: 2px;
         color: #000;
     }

     .footer-links {
         list-style: none;
     }

     .footer-links li {
         margin-bottom: 12px;
     }

     .footer-links a {
         color: #000;
         text-decoration: none;
         font-size: 15px;
         font-weight: 400;
         display: inline-block;
     }

     .footer-links a:hover {
         opacity: 0.6;
     }

     .footer-links--spaced {
         margin-top: 30px;
     }

     /* Shipping info dropdown */
     .shipping-info {
         display: flex;
         gap: 12px;
         align-items: flex-start;
     }

     .shipping-info select {
         padding: 8px 32px 8px 12px;
         border: 1px solid #000;
         background: #fff;
         font-size: 13px;
         font-weight: 500;
         cursor: pointer;
         appearance: none;
         /* SVG arrow icon sebagai background */
         background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 6L11 1' stroke='black' stroke-width='2'/%3E%3C/svg%3E");
         background-repeat: no-repeat;
         background-position: right 12px center;
     }

     /* ── 16b. Footer Bottom (legal, social, copyright) ── */

     .footer-bottom {
         display: flex;
         justify-content: space-between;
         align-items: center;
         padding-top: 30px;
     }

     .footer-legal {
         display: flex;
         gap: 30px;
         align-items: center;
         flex-wrap: wrap;
     }

     .footer-legal a {
         color: #000;
         text-decoration: none;
         font-size: 13px;
     }

     .footer-legal a:hover {
         opacity: 0.6;
     }

     .footer-social {
         display: flex;
         gap: 20px;
         align-items: center;
         flex-wrap: wrap;
     }

     .footer-social a {
         color: #000;
         text-decoration: none;
         font-size: 13px;
         font-weight: 500;
         text-transform: uppercase;
         letter-spacing: 1px;
     }

     .footer-social a:hover {
         opacity: 0.6;
     }

     .footer-copyright {
         font-size: 13px;
         color: #000;
     }


     /* ============================================================
   17. HELPERS & UTILITY CLASSES
   ============================================================ */

     /* Lock scroll saat sidebar/overlay terbuka */
     .body-lock {
         overflow: hidden;
         touch-action: none;
     }

     /* Site main content area */
     .site-main {
         min-height: 65vh;
         background: #fff;
     }

     /* Desktop nav links (disembunyikan di mobile) */
     /* Kelas ini dikendalikan di responsive section */


     /* ============================================================
   18. RESPONSIVE BREAKPOINTS
   ============================================================
   PERBAIKAN:
   - Satukan semua @media dengan breakpoint yang sama agar mudah
     di-maintain (file asli memiliki blok 768px yang terpisah-pisah).
   - Urutkan dari yang paling lebar ke paling sempit (desktop-first).
   ============================================================ */

     /* ── 18a. max-width: 1200px ── */
     @media (max-width: 1200px) {
         .product-block {
             flex: 0 0 calc(100% / 3.2);
             min-width: calc(100% / 3.2);
         }

         #tab-categories .pns-grid {
             grid-template-columns: repeat(4, 1fr);
         }

         #tab-collections .pns-grid {
             grid-template-columns: repeat(2, 1fr);
         }

         .content-block {
             height: 80vh;
             min-height: 600px;
         }
     }

     /* ── 18b. max-width: 1024px ── */
     @media (max-width: 1024px) {
         .category-grid {
             grid-template-columns: repeat(3, 1fr);
             gap: 30px;
         }

         .newsletter-container {
             flex-direction: column;
             align-items: flex-start;
         }

         .newsletter-btn {
             width: 100%;
         }

         .footer-top {
             grid-template-columns: repeat(2, minmax(0, 1fr));
             gap: 40px;
         }
     }

     /* ── 18c. max-width: 768px ── */
     @media (max-width: 768px) {

         /* Header mobile — grid 3 kolom: menu | logo | actions */
         .header-main {
             display: grid;
             grid-template-columns: minmax(64px, 1fr) auto minmax(64px, 1fr);
             padding: 12px 16px;
             min-height: 64px;
             gap: 10px;
         }

         .header-left {
             gap: 0;
             justify-content: flex-start;
         }

         .header-right {
             gap: 14px;
             justify-content: flex-end;
         }

         .mobile-menu-toggle {
             display: inline-flex;
             align-items: center;
             min-height: 36px;
         }

         /* Sembunyikan link desktop */
         .desktop-nav-link,
         .header-link-optional {
             display: none;
         }

         /* Toggle dark mode — hide label, tampilkan hanya icon */
         .dark-mode-toggle .toggle-label {
             display: none;
         }

         .dark-mode-toggle {
             min-width: 24px;
             min-height: 36px;
             justify-content: center;
         }

         .search-toggle,
         #cartToggle {
             min-height: 36px;
             display: inline-flex;
             align-items: center;
         }

         /* Logo */
         .logo {
             min-width: 0;
         }

         .logo-image {
             max-width: min(42vw, 120px);
             height: 34px;
         }

         /* Search box — fixed position di mobile */
         .search-box {
             position: fixed;
             top: 74px;
             left: 16px;
             right: 16px;
             transform: none;
             width: auto;
         }

         .search-box.active {
             width: auto;
         }

         .search-dropdown {
             max-height: min(60vh, 420px);
         }

         /* Shop sidebar — full width */
         .shop-sidebar {
             width: 100%;
             left: -100%;
         }

         .shop-sidebar.open {
             left: 0;
         }

         .shop-sidebar-header {
             padding: 20px 20px 16px;
         }

         .shop-sidebar-banner {
             height: 220px;
         }

         .shop-sidebar-body {
             padding: 20px;
         }

         .shop-mobile-links {
             display: grid;
             grid-template-columns: repeat(2, minmax(0, 1fr));
             column-gap: 20px;
             margin-bottom: 8px;
         }

         .shop-category-grid {
             grid-template-columns: repeat(2, minmax(0, 1fr));
             gap: 8px 14px;
         }

         /* Cart sidebar — full width */
         .cart-sidebar {
             width: 100%;
             right: -100%;
         }

         .cart-header,
         .cart-content,
         .cart-footer {
             padding-inline: 20px;
         }

         .cart-item {
             gap: 14px;
         }

         .cart-recommendations-grid {
             grid-template-columns: 1fr 1fr;
         }

         /* Hero */
         .hero {
             height: auto;
             min-height: 0px !important;
             padding: 0 20px 44px;
             justify-content: flex-end;
             aspect-ratio: 16 / 9;
         }

         .hero-content {
             width: 100%;
             padding: 0;
             text-align: left;
         }

         .hero-title {
             margin-bottom: 28px;
             letter-spacing: 0.5px;
         }

         .hero-subtitle {
             font-size: 10px;
             letter-spacing: 2px;
             margin-bottom: 22px;
         }

         .hero-buttons {
             flex-direction: column;
             align-items: stretch;
             width: min(100%, 320px);
         }

         .btn {
             display: inline-flex;
             align-items: center;
             justify-content: center;
             min-height: 48px;
             padding: 14px 20px;
             text-align: center;
         }

         /* Product carousel */
         .block-wrapper {
             padding: 0 16px;
         }

         .product-block {
             flex-basis: 46vw;
             min-width: 46vw;
         }

         /* Touch device — tampilkan wishlist & size selector tanpa hover */
         .wishlist-btn,
         .size-selector {
             opacity: 1;
             transform: none;
         }

         /* Collection */
         .collection-header {
             padding: 24px 20px 18px;
         }

         .collection-nav button {
             width: 40px;
             height: 40px;
         }

         /* Content blocks — stack vertikal */
         .content-blocks {
             grid-template-columns: 1fr;
         }

         .content-block {
             height: 70vh;
             min-height: 500px;
         }

         .block-overlay {
             bottom: 30px;
             left: 30px;
         }

         .block-title {
             font-size: 28px;
         }

         .block-category {
             font-size: 9px;
         }

         /* Categories */
         #tab-categories .pns-grid,
         #tab-collections .pns-grid,
         .pns-grid {
             grid-template-columns: repeat(2, 1fr);
             gap: 1px;
         }

         .pns-categories-tabs {
             padding: 18px 20px 0;
         }

         .pns-tab-content {
             padding: 24px 20px 0;
         }

         #tab-collections .pns-cat-item {
             flex: 0 0 78%;
             min-width: 78%;
         }

         /* Our Story */
         .our-story-section {
             padding: 60px 20px 80px;
         }

         .our-story-title {
             font-size: 28px;
         }

         .our-story-header {
             flex-direction: column;
             align-items: flex-start;
             gap: 20px;
         }

         .our-story-grid {
             grid-template-columns: 1fr;
             gap: 40px;
         }

         .our-story-img-wrap {
             aspect-ratio: 4 / 3;
         }

         /* Featured */
         .featured-section {
             padding: 80px 20px;
         }

         .featured-title {
             font-size: 32px;
         }

         /* Category nav */
         .category-grid {
             grid-template-columns: repeat(2, 1fr);
             gap: 30px;
         }

         .category-nav {
             padding: 50px 20px;
         }

         /* Newsletter */
         .newsletter-section {
             padding: 60px 24px;
         }

         .newsletter-title {
             font-size: 24px;
         }

         .newsletter-container {
             gap: 28px;
         }

         /* Footer */
         .footer {
             padding: 40px 24px;
         }

         .footer-top {
             grid-template-columns: 1fr;
             gap: 40px;
         }

         .footer-bottom {
             flex-direction: column;
             gap: 30px;
             align-items: flex-start;
         }

         .footer-legal {
             flex-direction: column;
             gap: 15px;
             align-items: flex-start;
         }

         .footer-social {
             order: -1;
         }
     }

     /* ── 18d. max-width: 480px ── */
     @media (max-width: 480px) {
         .header-main {
             padding-inline: 12px;
         }

         .header-right {
             gap: 10px;
         }

         /* PERBAIKAN: batasi lebar search toggle agar tidak geser layout */
         .search-toggle {
             max-width: 52px;
             overflow: hidden;
         }

         .shop-sidebar-banner {
             height: 170px;
         }

         .shop-category-grid {
             grid-template-columns: 1fr;
         }

         .cart-header,
         .cart-content,
         .cart-footer {
             padding-inline: 20px;
         }

         .cart-item {
             gap: 14px;
         }

         .cart-recommendations-grid {
             grid-template-columns: 1fr 1fr;
         }

         .collection-header {
             padding-inline: 16px;
         }

         /* Tabs horizontal scroll di layar sangat kecil */
         .pns-categories-tabs {
             overflow-x: auto;
             scrollbar-width: none;
         }

         .pns-tab {
             white-space: nowrap;
         }

         .newsletter-section {
             padding-inline: 18px;
         }

         .footer {
             padding-inline: 18px;
         }
     }

     /* ── 18e. Our Story: tablet (769px – 1200px) ── */
     @media (min-width: 769px) and (max-width: 1200px) {
         .our-story-grid {
             grid-template-columns: repeat(2, 1fr);
         }
     }
 </style>
 <style>
     .newsletter-section {
         background: rgb(250, 250, 250);
         padding: 60px 20px;
     }

     .newsletter-container {
         max-width: 1200px;
         margin: 0 auto;
         display: flex;
         flex-wrap: wrap;
         gap: 40px;
         align-items: center;
     }

     .newsletter-left {
         flex: 1;
     }

     .newsletter-label {
         font-size: 12px;
         letter-spacing: 0.08em;
         text-transform: uppercase;
         font-weight: 700;
         color: #777;
         margin-bottom: 12px;
     }

     .newsletter-title {
         font-size: clamp(24px, 5vw, 48px);
         line-height: 1.2;
         font-weight: 8;
         color: #111;
     }

     .newsletter-right {
         flex-shrink: 0;
     }

     .newsletter-form {
         display: flex;
         gap: 10px;
     }

     .newsletter-form input[type="email"] {
         padding: 12px 16px;
         border: 1px solid #ccc;
         border-radius: 4px;
         font-size: 14px;
     }

     .newsletter-btn {
         padding: 12px 24px;
         background-color: #111;
         color: #fff;
         border: none;
         border-radius: 0px;
         font-size: 14px;
         cursor: pointer;
         text-decoration: none !important;
     }

     .newsletter-logged-in a {
         padding: 12px 24px;
         background-color: #111;
         color: #fff !important;
         /* Ensure the text is white */
         /* border-radius: 4px; */
         font-size: 14px;
         font-weight: 400;

     }
 </style>
 <style>
     .admin-shortcut-header {
         padding: 16px 40px;
         background: #111 !important;
         display: flex;
         justify-content: flex-end;
         align-items: center;

     }

     .admin-shortcut-link {
         color: #ffffff;
         text-decoration: none;
         font-size: 14px;
         font-weight: 600;
         letter-spacing: 0.03em;
         transition: opacity 0.3s ease;
     }

     .admin-shortcut-link:hover {
         opacity: 0.7;
     }

     @media (max-width: 768px) {
         .admin-shortcut-header {
             padding: 14px 20px;
         }

         .admin-shortcut-link {
             font-size: 13px;
         }
     }
 </style>


 <style>
     .hidden-category,
     .hidden-collection {
         display: none;
     }

     .shop-load-more-btn {
         width: 100%;
         margin-top: 14px;
         padding: 12px;
         border: 1px solid #ddd;
         background: #fff;
         cursor: pointer;
         font-size: 13px;
         font-weight: 600;
         letter-spacing: 1px;
         text-transform: uppercase;
         transition: 0.3s ease;
     }

     .shop-load-more-btn:hover {
         background: #000;
         color: #fff;
     }
 </style>
 @stack('css')
