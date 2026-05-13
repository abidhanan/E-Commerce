@php
    $pageTitle = trim($__env->yieldContent('title', 'Admin Panel'));
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>{{ $pageTitle }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/android-chrome-512x512.png') }}">

    <style>
        :root {
            --bg: #ececec;
            --bg-soft: #f7f7f7;
            --surface: rgba(255, 255, 255, 0.94);
            --surface-strong: #ffffff;
            --surface-muted: #f0f0f0;
            --sidebar: #0f0f0f;
            --sidebar-soft: #1b1b1b;
            --accent: #151515;
            --accent-strong: #000000;
            --accent-soft: #e7e7e7;
            --text: #111111;
            --text-muted: #666666;
            --line: rgba(0, 0, 0, 0.08);
            --line-strong: rgba(0, 0, 0, 0.16);
            --border: rgba(0, 0, 0, 0.12);
            --warning: #4d4d4d;
            --danger: #2b2b2b;
            --success: #111111;
            --shadow-sm: 0 10px 24px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 20px 45px rgba(0, 0, 0, 0.10);
            --radius-lg: 22px;
            --radius-md: 16px;
            --radius-sm: 12px;
        }

        .active>.page-link,
        .page-link.active {
            z-index: 3;
            color: var(--accent-soft) !important;
            background-color: #040404 !important;
            border-color: #000 !important;
        }

        .page-link {
            position: relative;
            display: block;
            color: var(--accent-strong) !important;
            text-decoration: none;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.94), transparent 32%),
                radial-gradient(circle at top right, rgba(220, 220, 220, 0.34), transparent 24%),
                linear-gradient(180deg, #fafafa 0%, #efefef 48%, #ebebeb 100%);
            font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            overflow-x: hidden;
        }

        a {
            color: inherit;
        }

        #loader {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(250, 250, 250, 0.96);
            z-index: 2000;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        #loader.is-hidden {
            opacity: 0;
            visibility: hidden;
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(12, 18, 15, 0.45);
            backdrop-filter: blur(4px);
            z-index: 1040;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .admin-layout {
            min-height: 100vh;
        }

        .sidebar {
            width: 290px;
            min-height: 100vh;
            padding: 1rem;
            background: linear-gradient(180deg, #090909 0%, #171717 100%);
            color: rgba(245, 245, 245, 0.86);
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            position: sticky;
            top: 0;
            align-self: flex-start;
            z-index: 1050;
            transition: width 0.26s ease, left 0.26s ease, transform 0.26s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.85rem 0.95rem;
            border-radius: 18px;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.06);
            text-decoration: none;
        }

        .sidebar-brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f4f4f4, #bfbfbf);
            color: #111111;
            font-weight: 700;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .sidebar-brand-copy {
            min-width: 0;
        }

        .sidebar-brand-copy strong,
        .sidebar-brand-copy span {
            display: block;
        }

        .sidebar-brand-copy strong {
            color: #fafafa;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .sidebar-brand-copy span {
            color: rgba(245, 245, 245, 0.56);
            font-size: 0.78rem;
            margin-top: 0.15rem;
        }

        .sidebar-scroll {
            max-height: calc(100vh - 190px);
            overflow-y: auto;
            padding-right: 0.2rem;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.18);
            border-radius: 999px;
        }

        .sidebar-section-label {
            color: rgba(245, 245, 245, 0.38);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0 0.85rem 0.8rem;
        }

        .sidebar .nav-link {
            color: rgba(245, 245, 245, 0.74);
            display: flex;
            align-items: center;
            gap: 0.78rem;
            border-radius: 14px;
            padding: 0.8rem 0.9rem;
            font-weight: 600;
            transition: color 0.18s ease, background-color 0.18s ease, transform 0.18s ease;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, rgba(42, 42, 42, 0.98), rgba(0, 0, 0, 0.98));
            color: #ffffff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08), 0 10px 18px rgba(0, 0, 0, 0.24);
        }

        .sidebar .nav-link i:first-child {
            width: 1.2rem;
            text-align: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sidebar .collapse .nav-link {
            font-size: 0.92rem;
            font-weight: 500;
            padding: 0.62rem 0.85rem;
        }

        .sidebar .collapse .collapse .nav-link {
            font-size: 0.88rem;
            opacity: 0.94;
        }

        .bi-chevron-down {
            transition: transform 0.2s ease;
        }

        .nav-link[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
        }

        .nav-sub-item {
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
        }

        .sidebar-footer {
            margin-top: 1rem;
            padding: 0.95rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sidebar-footer-copy {
            color: rgba(233, 240, 236, 0.62);
            font-size: 0.78rem;
            margin-bottom: 0.75rem;
        }

        .sidebar-footer .btn {
            width: 100%;
            justify-content: center;
        }

        .sidebar.icon-only {
            width: 104px;
        }

        .sidebar.icon-only .menu-text,
        .sidebar.icon-only .sidebar-brand-copy,
        .sidebar.icon-only .sidebar-section-label,
        .sidebar.icon-only .sidebar-footer-copy,
        .sidebar.icon-only .bi-chevron-down {
            display: none !important;
        }

        .sidebar.icon-only .nav-link {
            justify-content: center;
            padding-inline: 0.75rem;
        }

        .sidebar.icon-only .collapse {
            display: none !important;
        }

        .admin-main {
            min-width: 0;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            backdrop-filter: blur(18px);
            background: rgba(250, 250, 250, 0.84);
            border-bottom: 1px solid var(--line-strong);
        }

        .admin-topbar-inner {
            max-width: 1520px;
            margin: 0 auto;
        }

        .topbar-toggle {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .admin-topbar-label {
            display: block;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.72rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .admin-topbar-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1.15;
            color: var(--text);
        }

        .admin-topbar-meta {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 0.85rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid var(--line);
            color: var(--text-muted);
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .admin-user-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.45rem 0.55rem 0.45rem 0.75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid var(--line);
            text-decoration: none;
            color: var(--text);
            box-shadow: var(--shadow-sm);
        }

        .admin-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #161616, #5d5d5d);
            color: #ffffff;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .admin-user-copy {
            min-width: 0;
        }

        .admin-user-copy strong,
        .admin-user-copy span {
            display: block;
            line-height: 1.15;
        }

        .admin-user-copy strong {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text);
        }

        .admin-user-copy span {
            font-size: 0.76rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }

        .navbar .dropdown-menu,
        .dropdown-menu {
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 0.5rem;
            min-width: 220px;
            box-shadow: 0 24px 50px rgba(0, 0, 0, 0.12);
        }

        .dropdown-item {
            border-radius: 12px;
            padding: 0.75rem 0.85rem;
            font-size: 0.92rem;
            color: var(--text);
            transition: background-color 0.18s ease, color 0.18s ease;
        }

        .dropdown-item:hover {
            background: var(--surface-muted);
            color: var(--text);
        }

        .dropdown-item.text-danger {
            color: var(--danger) !important;
        }

        .dropdown-item.text-danger:hover {
            background: rgba(0, 0, 0, 0.07);
        }

        .admin-main-content {
            flex: 1;
            padding: 1.5rem clamp(1rem, 2vw, 2rem) 2.5rem;
        }

        .admin-content-frame {
            max-width: 1520px;
            margin: 0 auto;
        }

        .admin-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .admin-page-eyebrow {
            display: inline-block;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .admin-page-title {
            margin: 0;
            font-size: clamp(1.65rem, 2.2vw, 2.15rem);
            line-height: 1.08;
            font-weight: 800;
            color: var(--text);
        }

        .admin-page-subtitle {
            margin: 0.55rem 0 0;
            max-width: 760px;
            color: var(--text-muted);
            font-size: 0.98rem;
        }

        .admin-page-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(251, 252, 251, 0.94));
            border: 1px solid var(--line);
            color: var(--text);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--line);
            padding: 1rem 1.25rem;
        }

        .card-body {
            position: relative;
        }

        .table-responsive {
            border-radius: calc(var(--radius-lg) - 4px);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: transparent;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text);
            --bs-table-border-color: var(--line);
            margin-bottom: 0;
        }

        .table>:not(caption)>*>* {
            padding: 0.95rem 1rem;
            background: transparent;
            border-bottom-color: var(--line);
            vertical-align: middle;
        }

        .table thead th,
        table thead th {
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-size: 0.76rem;
            font-weight: 700;
            border-bottom: 1px solid var(--line-strong);
            background: rgba(0, 0, 0, 0.04);
        }

        .table-hover tbody tr:hover {
            background: rgba(0, 0, 0, 0.04);
        }

        table tbody tr:hover {
            background: rgba(0, 0, 0, 0.04);
        }

        .table a,
        table a {
            text-decoration: none;
        }

        .btn {
            border-radius: 12px;
            padding: 0.68rem 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn:focus,
        .btn-close:focus,
        .form-control:focus,
        .form-select:focus,
        .form-check-input:focus {
            box-shadow: 0 0 0 0.22rem rgba(0, 0, 0, 0.12);
        }

        .btn-sm {
            padding: 0.5rem 0.8rem;
            border-radius: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-strong));
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 14px 24px rgba(0, 0, 0, 0.18);
        }

        .btn-primary:hover,
        .btn-primary:active {
            background: linear-gradient(135deg, #214f40, #14342a) !important;
            border-color: transparent !important;
            color: #ffffff !important;
        }

        .btn-dark {
            background: #111915;
            border-color: #111915;
            color: #ffffff;
        }

        .btn-dark:hover,
        .btn-dark:active {
            background: #080d0b !important;
            border-color: #080d0b !important;
            color: #ffffff !important;
        }

        .btn-outline-dark {
            border-color: var(--line-strong);
            color: var(--text);
            background: rgba(255, 255, 255, 0.7);
        }

        .btn-outline-dark:hover,
        .btn-outline-dark:active {
            background: var(--surface-muted) !important;
            border-color: var(--accent) !important;
            color: var(--accent) !important;
        }

        .btn-warning {
            background: rgba(80, 80, 80, 0.12);
            border-color: rgba(0, 0, 0, 0.12);
            color: #1a1a1a;
        }

        .btn-warning:hover {
            background: rgba(60, 60, 60, 0.18);
            color: #000000;
        }

        .btn-danger {
            background: rgba(28, 28, 28, 0.10);
            border-color: rgba(0, 0, 0, 0.14);
            color: #111111;
        }

        .btn-danger:hover {
            background: rgba(0, 0, 0, 0.16);
            color: #000000;
        }

        .btn-success {
            background: #111111;
            border-color: #111111;
            color: #ffffff;
        }

        .btn-success:hover,
        .btn-success:active {
            background: #000000 !important;
            border-color: #000000 !important;
            color: #ffffff !important;
        }

        .btn-link {
            color: var(--accent);
            font-weight: 600;
        }

        .badge {
            padding: 0.55rem 0.8rem;
            border-radius: 999px;
            font-weight: 700;
        }

        .form-label {
            color: var(--text);
            font-weight: 600;
        }

        .form-control,
        .form-select,
        .input-group-text,
        textarea.form-control {
            border-radius: 14px;
            border-color: var(--line-strong);
            background: rgba(255, 255, 255, 0.84);
            color: var(--text);
            min-height: 46px;
        }

        textarea.form-control {
            min-height: 110px;
        }

        .form-control::placeholder {
            color: #8a948d;
        }

        .form-check-input {
            border-color: var(--line-strong);
        }

        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        .bg-success,
        .text-bg-success {
            background-color: #111111 !important;
            color: #ffffff !important;
        }

        .bg-danger,
        .text-bg-danger {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
        }

        .bg-warning,
        .text-bg-warning {
            background-color: #5b5b5b !important;
            color: #ffffff !important;
        }

        .text-success,
        .text-danger,
        .text-warning {
            color: #4a4a4a !important;
        }

        .alert {
            border: 1px solid transparent;
            border-radius: 16px;
            padding: 1rem 1.15rem;
            box-shadow: var(--shadow-sm);
        }

        .alert ul {
            padding-left: 1.1rem;
        }

        .alert-success {
            background: rgba(0, 0, 0, 0.05);
            border-color: rgba(0, 0, 0, 0.12);
            color: #111111;
        }

        .alert-danger {
            background: rgba(0, 0, 0, 0.06);
            border-color: rgba(0, 0, 0, 0.14);
            color: #111111;
        }

        .alert-info {
            background: rgba(0, 0, 0, 0.04);
            border-color: rgba(0, 0, 0, 0.10);
            color: #111111;
        }

        .border-top {
            border-top: 1px solid var(--line) !important;
        }

        .admin-footer {
            padding: 0 1rem 1.5rem;
        }

        .admin-footer-inner {
            max-width: 1520px;
            margin: 0 auto;
            border-top: 1px solid var(--line);
            padding-top: 1rem;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: -310px;
                top: 0;
                height: 100vh;
                box-shadow: 0 30px 70px rgba(0, 0, 0, 0.22);
            }

            .sidebar.show {
                left: 0;
            }

            .admin-topbar-title {
                font-size: 1.1rem;
            }

            .admin-topbar-meta {
                display: none;
            }

            .admin-page-header {
                flex-direction: column;
            }

            .admin-page-actions {
                justify-content: flex-start;
            }
        }

        @media (max-width: 767.98px) {
            .admin-main-content {
                padding-inline: 0.85rem;
            }

            .admin-user-copy {
                display: none;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }

        .sidebar-brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 14px;
        }

        .toast-container {
            margin: 10px;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }
    </style>

    @stack('css')
    @stack('styles')
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div id="loader">
        <div class="spinner-border text-dark"></div>
    </div>

    <div class="d-flex admin-layout">
        @include('Admin.Template.sidebar')

        <div class="admin-main">
            @include('Admin.Template.header', ['pageTitle' => $pageTitle])
            @include('Admin.message.index')
            <main class="admin-main-content">
                <div class="admin-content-frame">

                    @yield('content')
                </div>
            </main>

            @include('Admin.Template.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('Admin.Template.confirm-modal')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastEls = document.querySelectorAll('.toast');
            toastEls.forEach((toastEl, index) => {
                const delay = 5000 * (index + 1); // Delay toast 5 detik berturut-turut
                const toast = new bootstrap.Toast(toastEl, {
                    delay: delay
                });
                toast.show(); // Menampilkan toast
            });
        });
    </script>

    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('loader');

            setTimeout(() => {
                loader.classList.add('is-hidden');
            }, 220);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('toggleBtn');
            const sidebarStateKey = 'shop-admin-sidebar-collapsed';

            const closeMobileSidebar = () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('active');
            };

            const applyDesktopSidebarState = () => {
                const shouldCollapse = localStorage.getItem(sidebarStateKey) === '1';
                sidebar.classList.toggle('icon-only', shouldCollapse && window.innerWidth >= 992);
            };

            applyDesktopSidebarState();

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        sidebar.classList.toggle('show');
                        overlay.classList.toggle('active');
                        return;
                    }

                    const isCollapsed = !sidebar.classList.contains('icon-only');
                    sidebar.classList.toggle('icon-only', isCollapsed);
                    localStorage.setItem(sidebarStateKey, isCollapsed ? '1' : '0');
                });
            }

            overlay.addEventListener('click', closeMobileSidebar);

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    closeMobileSidebar();
                    applyDesktopSidebarState();
                } else {
                    sidebar.classList.remove('icon-only');
                }
            });

            document.querySelectorAll('.alert-dismissible').forEach((alertElement) => {
                setTimeout(() => {
                    const alert = bootstrap.Alert.getOrCreateInstance(alertElement);
                    alert.close();
                }, 4500);
            });
        });
    </script>

    @include('Shared.disable-submit-script')
    @stack('scripts')
</body>

</html>
