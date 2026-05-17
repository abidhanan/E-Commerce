@php
    $currentUser = auth()->user();
    $currentRole = $currentUser?->roles?->first()?->name ?? 'Administrator';
@endphp

<nav class="navbar admin-topbar px-3 px-lg-4 py-3">
    <div class="admin-topbar-inner d-flex align-items-center gap-3 w-100">
        <div class="d-flex align-items-center gap-3 min-w-0">
            <button class="btn btn-outline-dark btn-sm topbar-toggle" id="toggleBtn" type="button" aria-label="Toggle sidebar">
                <i class="bi bi-list fs-5"></i>
            </button>

            <div class="min-w-0">
                <span class="admin-topbar-label">Operations Studio</span>
                <h1 class="admin-topbar-title text-truncate">{{ $pageTitle ?? 'Admin Panel' }}</h1>
            </div>
        </div>

        <div class="ms-auto d-flex align-items-center gap-2 gap-lg-3">
            @can('manage products')
                <form class="admin-topbar-search d-none d-xl-block" action="{{ route('admin.products.index') }}" method="GET">
                    <i class="bi bi-search"></i>
                    <input class="form-control" type="search" name="search" placeholder="Find products, catalog entries..."
                        value="{{ request('search') }}" aria-label="Search admin content">
                </form>
            @endcan

            <div class="admin-topbar-meta">
                <i class="bi bi-calendar3"></i>
                <span>{{ now()->format('D, d M Y') }}</span>
            </div>

            <div class="dropdown">
                <button class="btn admin-icon-button dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false" aria-label="Notifications" id="adminNotificationToggle">
                    <i class="bi bi-bell"></i>
                    <span class="admin-notification-dot d-none" id="adminNotificationDot"></span>
                    <span class="admin-notification-count d-none" id="adminNotificationCount">0</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end admin-notification-menu">
                    <div class="admin-notification-header">
                        <div>
                            <h6>Notifications</h6>
                            <span id="adminNotificationMeta">Syncing updates...</span>
                        </div>
                        <button type="button" class="admin-notification-read" id="adminNotificationReadBtn">
                            Mark read
                        </button>
                    </div>

                    <div class="admin-notification-list" id="adminNotificationList">
                        <div class="admin-notification-empty">Loading notifications...</div>
                    </div>
                </div>
            </div>

            <div class="dropdown">
                <a class="admin-user-toggle dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <span class="admin-user-copy order-2 order-lg-1">
                        <strong>{{ $currentUser?->name ?? 'Admin' }}</strong>
                        <span>{{ ucfirst($currentRole) }}</span>
                    </span>
                    <span class="admin-user-avatar order-1 order-lg-2">
                        <i class="bi bi-person-fill"></i>
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ $currentUser?->can('manage users') ? route('admin.users.edit', $currentUser?->id) : route('account.index') }}">
                            <i class="bi bi-person me-2"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
