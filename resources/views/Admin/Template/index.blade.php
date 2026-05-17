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
            --gold: #9e8035;
            --black: #000000;
            --dark: #111111;
            --soft-bg: #f8f8f8;
            --white: #ffffff;
            --border-soft: #e5e5e5;
            --bg: #f8f8f8;
            --bg-soft: #ffffff;
            --surface: #ffffff;
            --surface-strong: #ffffff;
            --surface-muted: #f8f8f8;
            --sidebar: #000000;
            --sidebar-soft: #111111;
            --accent: #9e8035;
            --accent-strong: #000000;
            --accent-soft: #f8f8f8;
            --text: #111111;
            --text-muted: #111111;
            --line: #e5e5e5;
            --line-strong: #e5e5e5;
            --border: #e5e5e5;
            --warning: #9e8035;
            --danger: #111111;
            --success: #9e8035;
            --shadow-sm: 0 12px 30px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 24px 60px rgba(0, 0, 0, 0.10);
            --radius-lg: 18px;
            --radius-md: 14px;
            --radius-sm: 10px;
        }

        .active>.page-link,
        .page-link.active {
            z-index: 3;
            color: var(--accent-soft) !important;
            background-color: var(--gold) !important;
            border-color: var(--gold) !important;
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
            background: var(--soft-bg);
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
            background: var(--white);
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
            background: rgba(0, 0, 0, 0.45);
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
            background: var(--black);
            color: var(--white);
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
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
            background: var(--dark);
            border: 1px solid var(--dark);
            text-decoration: none;
        }

        .sidebar-brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--white);
            color: var(--black);
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
            color: var(--white);
            font-size: 0.95rem;
            font-weight: 700;
        }

        .sidebar-brand-copy span {
            color: var(--border-soft);
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
            background: var(--gold);
            border-radius: 999px;
        }

        .sidebar-section-label {
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0 0.85rem 0.8rem;
        }

        .sidebar .nav-link {
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 0.78rem;
            border-radius: var(--radius-sm);
            padding: 0.8rem 0.9rem;
            font-weight: 600;
            transition: color 0.22s ease, background-color 0.22s ease, transform 0.22s ease, border-color 0.22s ease;
        }

        .sidebar .nav-link:hover {
            background: var(--dark);
            color: var(--white);
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            background: var(--gold);
            color: var(--white) !important;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.24);
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
            border-radius: var(--radius-md);
            background: var(--dark);
            border: 1px solid var(--dark);
        }

        .sidebar-footer-copy {
            color: var(--border-soft);
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
            background: var(--white);
            border-bottom: 1px solid var(--border-soft);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
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
            background: var(--soft-bg);
            border: 1px solid var(--border-soft);
            color: var(--text-muted);
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .admin-topbar-search {
            position: relative;
            width: min(340px, 30vw);
        }

        .admin-topbar-search i {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gold);
            pointer-events: none;
        }

        .admin-topbar-search .form-control {
            min-height: 42px;
            border-radius: 999px;
            padding-left: 2.45rem;
            background: var(--soft-bg);
            border-color: var(--border-soft);
        }

        .admin-icon-button {
            width: 42px;
            height: 42px;
            padding: 0;
            border-radius: 50%;
            background: var(--soft-bg);
            border: 1px solid var(--border-soft);
            color: var(--black);
            position: relative;
        }

        .admin-icon-button::after {
            display: none;
        }

        .admin-notification-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--gold);
            border: 2px solid var(--white);
        }

        .admin-notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--gold);
            color: var(--white);
            border: 2px solid var(--white);
            font-size: 0.68rem;
            font-weight: 800;
            line-height: 1;
        }

        .admin-notification-menu {
            width: min(390px, calc(100vw - 1.5rem));
            padding: 0;
            overflow: hidden;
        }

        .admin-notification-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border-soft);
            background: var(--white);
        }

        .admin-notification-header h6 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--black);
        }

        .admin-notification-header span {
            display: block;
            margin-top: 0.2rem;
            color: var(--text-muted);
            font-size: 0.78rem;
        }

        .admin-notification-read {
            border: 0;
            background: transparent;
            color: var(--gold);
            font-size: 0.78rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .admin-notification-list {
            max-height: 420px;
            overflow-y: auto;
            background: var(--white);
        }

        .admin-notification-item {
            display: flex;
            gap: 0.8rem;
            padding: 0.9rem 1rem;
            text-decoration: none;
            color: var(--dark);
            border-bottom: 1px solid var(--border-soft);
            transition: background-color 0.18s ease;
        }

        .admin-notification-item:hover {
            background: var(--soft-bg);
            color: var(--dark);
        }

        .admin-notification-item.is-unread {
            background: var(--soft-bg);
        }

        .admin-notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 36px;
            background: var(--black);
            color: var(--white);
        }

        .admin-notification-item[data-severity="gold"] .admin-notification-icon {
            background: var(--gold);
        }

        .admin-notification-body {
            min-width: 0;
            flex: 1;
        }

        .admin-notification-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.2rem;
        }

        .admin-notification-title strong {
            min-width: 0;
            color: var(--black);
            font-size: 0.88rem;
            font-weight: 800;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-notification-title span {
            color: var(--text-muted);
            font-size: 0.72rem;
            white-space: nowrap;
        }

        .admin-notification-message {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.8rem;
            line-height: 1.45;
        }

        .admin-notification-empty {
            padding: 1.25rem;
            color: var(--text-muted);
            text-align: center;
            font-size: 0.88rem;
        }

        .admin-user-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.45rem 0.55rem 0.45rem 0.75rem;
            border-radius: 999px;
            background: var(--white);
            border: 1px solid var(--border-soft);
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
            background: var(--black);
            color: var(--white);
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
            border: 1px solid var(--border-soft);
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
            background: var(--soft-bg);
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
            background: var(--white);
            border: 1px solid var(--border-soft);
            color: var(--text);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-soft);
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
            --bs-table-border-color: var(--border-soft);
            margin-bottom: 0;
        }

        .table>:not(caption)>*>* {
            padding: 0.95rem 1rem;
            background: transparent;
            border-bottom-color: var(--border-soft);
            vertical-align: middle;
        }

        .table thead th,
        table thead th {
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-size: 0.76rem;
            font-weight: 700;
            border-bottom: 1px solid var(--border-soft);
            background: var(--soft-bg);
        }

        .table-hover tbody tr:hover {
            background: var(--soft-bg);
        }

        table tbody tr:hover {
            background: var(--soft-bg);
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
            box-shadow: 0 0 0 0.22rem rgba(158, 128, 53, 0.22);
        }

        .btn-sm {
            padding: 0.5rem 0.8rem;
            border-radius: 10px;
        }

        .btn-primary {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--white);
            box-shadow: 0 14px 24px rgba(0, 0, 0, 0.18);
        }

        .btn-primary:hover,
        .btn-primary:active {
            background: var(--black) !important;
            border-color: var(--black) !important;
            color: var(--white) !important;
        }

        .btn-dark {
            background: var(--black);
            border-color: var(--black);
            color: var(--white);
        }

        .btn-dark:hover,
        .btn-dark:active {
            background: var(--gold) !important;
            border-color: var(--gold) !important;
            color: var(--white) !important;
        }

        .btn-outline-dark {
            border-color: var(--line-strong);
            color: var(--text);
            background: var(--white);
        }

        .btn-outline-dark:hover,
        .btn-outline-dark:active {
            background: var(--gold) !important;
            border-color: var(--accent) !important;
            color: var(--white) !important;
        }

        .btn-outline-primary,
        .btn-outline-secondary {
            border-color: var(--gold);
            color: var(--gold);
            background: var(--white);
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:active,
        .btn-outline-secondary:hover,
        .btn-outline-secondary:active {
            background: var(--gold) !important;
            border-color: var(--gold) !important;
            color: var(--white) !important;
        }

        .btn-warning {
            background: var(--white);
            border-color: var(--gold);
            color: var(--gold);
        }

        .btn-warning:hover {
            background: var(--gold);
            color: var(--white);
        }

        .btn-danger {
            background: var(--dark);
            border-color: var(--dark);
            color: var(--white);
        }

        .btn-danger:hover {
            background: var(--black);
            color: var(--white);
        }

        .btn-success {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--white);
        }

        .btn-success:hover,
        .btn-success:active {
            background: var(--black) !important;
            border-color: var(--black) !important;
            color: var(--white) !important;
        }

        .btn-link {
            color: var(--accent);
            font-weight: 600;
        }

        .badge {
            padding: 0.55rem 0.8rem;
            border-radius: 999px;
            font-weight: 700;
            border: 1px solid var(--border-soft);
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
            background: var(--white);
            color: var(--text);
            min-height: 46px;
        }

        textarea.form-control {
            min-height: 110px;
        }

        .form-control::placeholder {
            color: var(--text-muted);
            opacity: 0.62;
        }

        .form-control:focus,
        .form-select:focus,
        textarea.form-control:focus {
            border-color: var(--gold);
        }

        .form-check-input {
            border-color: var(--line-strong);
        }

        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        .table-light {
            --bs-table-bg: var(--soft-bg);
            --bs-table-color: var(--black);
            --bs-table-border-color: var(--border-soft);
        }

        .bg-primary,
        .text-bg-primary,
        .bg-info,
        .text-bg-info,
        .bg-secondary,
        .text-bg-secondary,
        .bg-dark,
        .text-bg-dark {
            background-color: var(--black) !important;
            color: var(--white) !important;
        }

        .bg-success,
        .text-bg-success {
            background-color: var(--gold) !important;
            color: var(--white) !important;
        }

        .bg-danger,
        .text-bg-danger {
            background-color: var(--dark) !important;
            color: var(--white) !important;
        }

        .bg-warning,
        .text-bg-warning {
            background-color: var(--gold) !important;
            color: var(--white) !important;
        }

        .text-success,
        .text-danger,
        .text-warning,
        .text-info,
        .text-primary {
            color: var(--gold) !important;
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
            background: var(--soft-bg);
            border-color: var(--border-soft);
            color: var(--dark);
        }

        .alert-danger {
            background: var(--soft-bg);
            border-color: var(--border-soft);
            color: var(--dark);
        }

        .alert-info {
            background: var(--soft-bg);
            border-color: var(--border-soft);
            color: var(--dark);
        }

        .modal-content {
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }

        .modal-header,
        .modal-footer {
            border-color: var(--border-soft);
            padding: 1rem 1.25rem;
        }

        .modal-body {
            padding: 1.25rem;
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

        /* Handcrafted admin redesign: editorial rail, calm command bar, precise data surfaces. */
        :root {
            --ink: #000000;
            --ink-2: #111111;
            --paper: #ffffff;
            --canvas: #f8f8f8;
            --rule: #e5e5e5;
            --muted: #777777;
            --hairline: rgba(0, 0, 0, 0.08);
            --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-panel: 0 18px 44px rgba(0, 0, 0, 0.07);
            --radius-xl: 26px;
            --radius-lg: 18px;
            --radius-md: 12px;
            --radius-sm: 9px;
        }

        body {
            background: var(--canvas);
            color: var(--ink-2);
            font-family: Inter, "SF Pro Display", "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            letter-spacing: 0;
        }

        .admin-layout {
            background:
                linear-gradient(90deg, var(--ink) 0 292px, var(--canvas) 292px 100%);
        }

        .sidebar {
            width: 292px;
            padding: 22px 16px;
            background: var(--ink);
            border-right: 0;
            color: var(--paper);
        }

        .sidebar-brand {
            min-height: 76px;
            padding: 12px 10px 18px;
            margin: 0 0 18px;
            gap: 12px;
            border: 0;
            border-radius: 0;
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .sidebar-brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--paper);
            box-shadow: none;
        }

        .sidebar-brand-mark img {
            border-radius: 50%;
        }

        .sidebar-brand-copy strong {
            font-size: 0.86rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .sidebar-brand-copy span {
            max-width: 170px;
            color: rgba(255, 255, 255, 0.56);
            font-size: 0.72rem;
            line-height: 1.45;
        }

        .sidebar-scroll {
            max-height: calc(100vh - 184px);
            padding: 4px 2px 0 0;
        }

        .sidebar-section-label {
            padding: 10px 10px 12px;
            color: rgba(255, 255, 255, 0.38);
            font-size: 0.66rem;
            font-weight: 800;
            letter-spacing: 0.16em;
        }

        .sidebar .nav.flex-column {
            gap: 4px !important;
        }

        .sidebar .nav-link {
            position: relative;
            min-height: 42px;
            padding: 10px 11px;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.86rem;
            font-weight: 650;
            letter-spacing: 0;
            transition: background-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.07);
            color: var(--paper);
            transform: translateX(1px);
        }

        .sidebar .nav-link.active {
            background: rgba(158, 128, 53, 0.16);
            color: var(--paper) !important;
            box-shadow: none;
        }

        .sidebar .nav-link.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 11px;
            bottom: 11px;
            width: 3px;
            border-radius: 999px;
            background: var(--gold);
        }

        .sidebar .nav-link i:first-child {
            width: 22px;
            color: var(--gold);
            font-size: 0.95rem;
        }

        .sidebar .collapse .nav-link {
            min-height: 36px;
            padding: 8px 10px;
            color: rgba(255, 255, 255, 0.58);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .sidebar .collapse ul {
            margin-left: 18px !important;
            padding-left: 10px;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-footer {
            margin: 18px 0 0;
            padding: 14px;
            border: 1px solid rgba(158, 128, 53, 0.28);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.04);
        }

        .sidebar-footer-copy {
            color: rgba(255, 255, 255, 0.52);
            font-size: 0.75rem;
            line-height: 1.55;
        }

        .admin-main {
            background: var(--canvas);
        }

        .admin-topbar {
            position: sticky;
            top: 0;
            padding: 18px 22px 10px !important;
            background: var(--canvas);
            border: 0;
            box-shadow: none;
        }

        .admin-topbar-inner {
            max-width: 1480px;
            min-height: 72px;
            padding: 12px 16px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: var(--shadow-xs);
        }

        .topbar-toggle {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: var(--ink);
            color: var(--paper);
            border-color: var(--ink);
        }

        .topbar-toggle:hover {
            background: var(--gold) !important;
            border-color: var(--gold) !important;
            color: var(--paper) !important;
        }

        .admin-topbar-label {
            margin-bottom: 3px;
            color: var(--gold);
            font-size: 0.64rem;
            font-weight: 900;
            letter-spacing: 0.18em;
        }

        .admin-topbar-title {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .admin-topbar-search {
            width: min(360px, 28vw);
        }

        .admin-topbar-search .form-control {
            min-height: 44px;
            border: 0;
            border-radius: 16px;
            background: var(--canvas);
            box-shadow: inset 0 0 0 1px var(--rule);
        }

        .admin-topbar-meta,
        .admin-icon-button,
        .admin-user-toggle {
            min-height: 44px;
            border: 0;
            border-radius: 16px;
            background: var(--canvas);
            box-shadow: inset 0 0 0 1px var(--rule);
        }

        .admin-icon-button {
            width: 44px;
        }

        .admin-user-toggle {
            padding: 4px 6px 4px 12px;
            box-shadow: inset 0 0 0 1px var(--rule);
        }

        .admin-user-avatar {
            width: 34px;
            height: 34px;
            background: var(--ink);
        }

        .admin-main-content {
            padding: 22px clamp(18px, 2vw, 34px) 44px;
        }

        .admin-content-frame {
            max-width: 1480px;
        }

        .admin-page-header {
            align-items: flex-end;
            margin: 4px 0 26px;
            padding: 0 2px;
        }

        .admin-page-eyebrow {
            color: var(--gold);
            font-size: 0.68rem;
            font-weight: 900;
            letter-spacing: 0.18em;
        }

        .admin-page-title {
            max-width: 860px;
            font-size: clamp(1.85rem, 2.4vw, 2.7rem);
            font-weight: 820;
            line-height: 0.98;
            letter-spacing: -0.025em;
        }

        .admin-page-subtitle {
            max-width: 680px;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.65;
        }

        .card {
            border: 0;
            border-radius: 24px;
            background: var(--paper);
            box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.06), var(--shadow-xs);
        }

        .card:hover {
            box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.08), 0 10px 28px rgba(0, 0, 0, 0.045);
        }

        .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--rule);
        }

        .card h5,
        .card h6 {
            letter-spacing: -0.01em;
            font-weight: 780;
        }

        .admin-stat-card {
            border-radius: 28px;
        }

        .admin-stat-card::after {
            display: none;
        }

        .admin-stat-card .card-body {
            min-height: 132px;
            align-items: flex-start !important;
        }

        .admin-stat-card small {
            color: var(--muted) !important;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .admin-stat-card h3 {
            margin-top: 16px !important;
            font-size: clamp(1.55rem, 2vw, 2.1rem);
            font-weight: 840;
            letter-spacing: -0.025em;
        }

        .admin-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: var(--ink) !important;
            color: var(--gold) !important;
            font-size: 1.05rem;
        }

        .table-responsive {
            border-radius: 18px;
            box-shadow: inset 0 0 0 1px var(--rule);
        }

        .table {
            font-size: 0.9rem;
        }

        .table>:not(caption)>*>* {
            padding: 15px 16px;
            border-bottom-color: var(--rule);
        }

        .table thead th,
        table thead th {
            padding-top: 14px;
            padding-bottom: 14px;
            background: var(--paper);
            color: var(--muted);
            font-size: 0.68rem;
            font-weight: 900;
            letter-spacing: 0.14em;
        }

        .table tbody tr {
            transition: background-color 0.16s ease;
        }

        .table-hover tbody tr:hover,
        table tbody tr:hover {
            background: rgba(158, 128, 53, 0.05);
        }

        .btn {
            min-height: 42px;
            border-radius: 14px;
            padding: 0.62rem 0.95rem;
            font-size: 0.88rem;
            font-weight: 780;
            letter-spacing: -0.005em;
            box-shadow: none !important;
        }

        .btn-sm {
            min-height: 34px;
            padding: 0.42rem 0.7rem;
            border-radius: 11px;
            font-size: 0.8rem;
        }

        .btn-primary,
        .btn-success {
            background: var(--gold);
            border-color: var(--gold);
        }

        .btn-primary:hover,
        .btn-success:hover,
        .btn-dark:hover {
            background: var(--ink) !important;
            border-color: var(--ink) !important;
        }

        .btn-outline-dark,
        .btn-outline-primary,
        .btn-outline-secondary,
        .btn-warning {
            background: transparent;
            border-color: var(--rule);
            color: var(--ink);
        }

        .btn-outline-dark:hover,
        .btn-outline-primary:hover,
        .btn-outline-secondary:hover,
        .btn-warning:hover {
            background: var(--ink) !important;
            border-color: var(--ink) !important;
            color: var(--paper) !important;
        }

        .form-label {
            margin-bottom: 7px;
            color: var(--ink);
            font-size: 0.78rem;
            font-weight: 800;
        }

        .form-control,
        .form-select,
        .input-group-text,
        textarea.form-control {
            min-height: 48px;
            border: 0;
            border-radius: 15px;
            background: var(--paper);
            box-shadow: inset 0 0 0 1px var(--rule);
        }

        .form-control:focus,
        .form-select:focus,
        textarea.form-control:focus {
            border-color: transparent;
            box-shadow: inset 0 0 0 1px var(--gold), 0 0 0 4px rgba(158, 128, 53, 0.12);
        }

        .badge {
            padding: 0.45rem 0.68rem;
            border: 0;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 850;
        }

        .alert {
            border: 0;
            border-radius: 18px;
            background: var(--paper);
            box-shadow: inset 0 0 0 1px var(--rule);
        }

        .modal-content,
        .dropdown-menu {
            border: 0;
            border-radius: 22px;
            box-shadow: var(--shadow-panel);
        }

        .modal-header,
        .modal-footer {
            border-color: var(--rule);
        }

        .page-link {
            min-width: 38px;
            margin: 0 3px;
            border: 0;
            border-radius: 12px;
            background: var(--paper);
            box-shadow: inset 0 0 0 1px var(--rule);
        }

        .active>.page-link,
        .page-link.active {
            background: var(--ink) !important;
            border-color: var(--ink) !important;
        }

        .admin-notification-menu {
            border-radius: 24px;
        }

        .admin-notification-header {
            padding: 16px 18px;
        }

        .admin-notification-item {
            padding: 14px 18px;
            border-bottom-color: var(--rule);
        }

        .admin-notification-item.is-unread {
            background: rgba(158, 128, 53, 0.06);
        }

        /* Contrast safety: dark surfaces must never inherit black utility text. */
        .sidebar,
        .sidebar * {
            color: inherit;
        }

        .sidebar .menu-text,
        .sidebar .nav-link,
        .sidebar .nav-link span,
        .sidebar .sidebar-brand-copy strong {
            color: rgba(255, 255, 255, 0.76) !important;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link:hover span,
        .sidebar .nav-link.active,
        .sidebar .nav-link.active span {
            color: var(--paper) !important;
        }

        .sidebar .sidebar-brand-copy span,
        .sidebar .sidebar-footer-copy,
        .sidebar .text-muted {
            color: rgba(255, 255, 255, 0.56) !important;
        }

        .sidebar .btn,
        .sidebar .btn-outline-dark,
        .sidebar .btn-outline-primary,
        .sidebar .btn-outline-secondary {
            background: transparent !important;
            border-color: rgba(158, 128, 53, 0.42) !important;
            color: var(--paper) !important;
        }

        .sidebar .btn:hover,
        .sidebar .btn-outline-dark:hover,
        .sidebar .btn-outline-primary:hover,
        .sidebar .btn-outline-secondary:hover {
            background: var(--gold) !important;
            border-color: var(--gold) !important;
            color: var(--paper) !important;
        }

        .bg-dark,
        .bg-info,
        .bg-secondary,
        .bg-danger,
        .text-bg-dark,
        .text-bg-info,
        .text-bg-secondary,
        .text-bg-danger,
        .badge.bg-dark,
        .badge.bg-info,
        .badge.bg-secondary,
        .badge.bg-danger {
            color: var(--paper) !important;
        }

        .bg-dark.text-dark,
        .bg-info.text-dark,
        .bg-secondary.text-dark,
        .bg-danger.text-dark,
        .badge.bg-dark.text-dark,
        .badge.bg-info.text-dark,
        .badge.bg-secondary.text-dark,
        .badge.bg-danger.text-dark {
            color: var(--paper) !important;
        }

        .btn-dark,
        .btn-danger,
        .btn-primary,
        .btn-success,
        .btn-dark *,
        .btn-danger *,
        .btn-primary *,
        .btn-success * {
            color: var(--paper) !important;
        }

        @media (max-width: 991.98px) {
            .admin-layout {
                background: var(--canvas);
            }

            .sidebar {
                left: -320px;
                width: 300px;
            }

            .sidebar.show {
                left: 0;
            }

            .admin-topbar {
                padding: 12px 12px 6px !important;
            }

            .admin-topbar-inner {
                border-radius: 18px;
            }
        }

        @media (max-width: 767.98px) {
            .admin-main-content {
                padding: 18px 14px 34px;
            }

            .admin-page-title {
                font-size: 1.85rem;
            }

            .admin-topbar-meta {
                display: none;
            }
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

            const notificationUrl = @json(route('admin.notifications.index', [], false));
            const notificationReadKey = 'shop-admin-notifications-read-at';
            const notificationList = document.getElementById('adminNotificationList');
            const notificationMeta = document.getElementById('adminNotificationMeta');
            const notificationDot = document.getElementById('adminNotificationDot');
            const notificationCount = document.getElementById('adminNotificationCount');
            const notificationReadBtn = document.getElementById('adminNotificationReadBtn');

            const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            })[char]);

            const currentReadAt = () => localStorage.getItem(notificationReadKey) || new Date(0).toISOString();

            const updateNotificationBadge = (count) => {
                const visible = count > 0;
                notificationDot?.classList.toggle('d-none', !visible);
                notificationCount?.classList.toggle('d-none', !visible);

                if (notificationCount) {
                    notificationCount.textContent = count > 9 ? '9+' : String(count);
                }
            };

            const renderNotifications = (payload) => {
                const items = payload.items || [];
                const unreadCount = payload.unread_count || 0;

                updateNotificationBadge(unreadCount);

                if (notificationMeta) {
                    notificationMeta.textContent = unreadCount
                        ? `${unreadCount} unread update${unreadCount > 1 ? 's' : ''}`
                        : 'All caught up';
                }

                if (!notificationList) {
                    return;
                }

                if (!items.length) {
                    notificationList.innerHTML = '<div class="admin-notification-empty">No notifications yet.</div>';
                    return;
                }

                notificationList.innerHTML = items.map((item) => `
                    <a class="admin-notification-item ${item.is_unread ? 'is-unread' : ''}"
                        data-severity="${escapeHtml(item.severity)}"
                        href="${escapeHtml(item.url)}">
                        <span class="admin-notification-icon">
                            <i class="bi ${escapeHtml(item.icon)}"></i>
                        </span>
                        <span class="admin-notification-body">
                            <span class="admin-notification-title">
                                <strong>${escapeHtml(item.title)}</strong>
                                <span>${escapeHtml(item.time_label)}</span>
                            </span>
                            <p class="admin-notification-message">${escapeHtml(item.message)}</p>
                        </span>
                    </a>
                `).join('');
            };

            const fetchNotifications = () => {
                const params = new URLSearchParams({
                    read_at: currentReadAt(),
                });

                fetch(`${notificationUrl}?${params.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Notification request failed');
                        }

                        return response.json();
                    })
                    .then(renderNotifications)
                    .catch(() => {
                        if (notificationMeta) {
                            notificationMeta.textContent = 'Unable to sync updates';
                        }

                        if (notificationList) {
                            notificationList.innerHTML = '<div class="admin-notification-empty">Unable to load notifications.</div>';
                        }
                    });
            };

            notificationReadBtn?.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                localStorage.setItem(notificationReadKey, new Date().toISOString());
                fetchNotifications();
            });

            fetchNotifications();
            setInterval(fetchNotifications, 30000);
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    fetchNotifications();
                }
            });
        });
    </script>

    @include('Shared.disable-submit-script')
    @stack('scripts')
</body>

</html>
