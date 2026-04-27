<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Super Admin')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Favicon Standard -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    <!-- PNG Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <!-- Apple -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <!-- Android -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/android-chrome-512x512.png') }}">
    <style>
        :root {
            --bg: #f5f6f8;
            --bg-soft: #ffffff;
            --sidebar: #ffffff;
            --primary: #111827;
            --primary-light: #374151;
            --border: #e5e7eb;
            --text: #111827;
            --text-muted: #6b7280;
        }
    
        body {
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            font-size: 14px;
        }
    
        /* Loader */
        #loader {
            position: fixed;
            inset: 0;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
    
        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: var(--sidebar);
            border-right: 1px solid var(--border);
            padding-top: 10px;
        }
    
        .sidebar .nav-link {
            color: var(--text-muted);
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 4px;
            font-weight: 500;
            transition: 0.2s;
        }
    
        .sidebar .nav-link:hover {
            background: #f3f4f6;
            color: var(--primary);
        }
    
        .sidebar .nav-link.active {
            background: var(--primary);
            color: #fff !important;
        }
    
        .sidebar .collapse .nav-link {
            font-size: 13px;
            margin-left: 8px;
        }
    
        .sidebar.icon-only {
            width: 90px;
        }
    
        .sidebar.icon-only .menu-text {
            display: none;
        }
    
        @media(max-width:991px) {
            .sidebar {
                position: fixed;
                left: -260px;
                top: 0;
            }
    
            .sidebar.show {
                left: 0;
            }
        }
    
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1040;
            display: none;
        }
    
        .sidebar-overlay.active {
            display: block;
        }
    
        /* ================= NAVBAR ================= */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 10px 20px;
        }
    
        /* ================= PAGE ================= */
        .container-fluid {
            padding: 24px;
        }
    
        /* ================= CARD ================= */
        .card {
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
    
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
        }
    
        /* ================= TABLE ================= */
        table {
            width: 100%;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
        }
    
        table th {
            font-size: 13px;
            background: #f9fafb;
            padding: 12px;
            color: var(--text-muted);
        }
    
        table td {
            padding: 12px;
            border-top: 1px solid var(--border);
        }
    
        table tbody tr:hover {
            background: #f9fafb;
        }
    
        /* ================= BUTTON ================= */
        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 8px;
        }
    
        .btn-primary:hover {
            background: var(--primary-light);
        }
    
        .btn-create {
            background: var(--primary);
            padding: 8px 14px;
            border-radius: 8px;
            color: #fff !important;
            text-decoration: none;
        }
    
        .btn-create:hover {
            background: var(--primary-light);
        }
    
        /* ================= DROPDOWN ================= */
        .navbar .dropdown-menu {
            border-radius: 10px;
            border: 1px solid var(--border);
        }
    
        .navbar .dropdown-item:hover {
            background: var(--primary);
            color: #fff;
        }

        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: var(--primary);
            border-radius: 8px;
            margin: 0 2px;
            border-color: var(--border);
            box-shadow: none !important;
        }

        .page-link:hover {
            color: var(--primary-light);
            background: #f3f4f6;
            border-color: var(--border);
        }

        .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .page-item.disabled .page-link {
            color: var(--text-muted);
            background: #fff;
            border-color: var(--border);
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>


    <div id="loader">
        <div class="spinner-border text-dark"></div>
    </div>

    <div class="d-flex">
        @include('SuperAdmin.Template.Sidebar')

        <div class="flex-grow-1">
            @include('SuperAdmin.Template.Header')

            <div class="container-fluid py-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>

            @include('SuperAdmin.Template.Footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.addEventListener("load", function() {
            setTimeout(() => {
                document.getElementById("loader").style.display = "none";
            }, 500);
        });

        document.addEventListener("DOMContentLoaded", function() {

            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("sidebarOverlay");
            const toggleBtn = document.getElementById("toggleBtn");

            toggleBtn.addEventListener("click", function() {

                if (window.innerWidth < 992) {
                    sidebar.classList.toggle("show");
                    overlay.classList.toggle("active");
                } else {
                    sidebar.classList.toggle("icon-only");
                }

            });

            overlay.addEventListener("click", function() {
                sidebar.classList.remove("show");
                overlay.classList.remove("active");
            });

        });
    </script>


    @stack('scripts')

</body>

</html>
