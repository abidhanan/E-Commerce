<div class="sidebar p-3" id="sidebar">

    <ul class="nav flex-column gap-2" style="list-style: none; padding-left: 0;">

        {{-- DASHBOARD --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>
        {{-- =================== USER ================== --}}
        <li class="nav-item">

            <a class="nav-link d-flex justify-content-between align-items-center 
    {{ request()->routeIs('admin.users.*') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" data-bs-target="#userMenu">

                <span>
                    <i class="bi bi-people"></i>
                    <span class="menu-text">Users</span>
                </span>

                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse {{ request()->routeIs('superadmin.users.*') ? 'show' : '' }}" id="userMenu">

                <ul class="nav flex-column ms-4 mt-2">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.users.index') ? 'active' : '' }}"
                            href="{{ route('superadmin.users.index') }}">
                            <i class="bi bi-list"></i>
                            <span class="menu-text">All Users</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.users.create') ? 'active' : '' }}"
                            href="{{ route('superadmin.users.create') }}">
                            <i class="bi bi-plus-circle"></i>
                            <span class="menu-text">Add User</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>
        {{-- =================== Category ================== --}}
        <li class="nav-item">

            <a class="nav-link d-flex justify-content-between align-items-center 
    {{ request()->routeIs('superadmin.categories.*') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" data-bs-target="#categoryMenu">

                <span>
                    <i class="bi bi-people"></i>
                    <span class="menu-text">Categories</span>
                </span>

                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse {{ request()->routeIs('superadmin.categories.*') ? 'show' : '' }}" id="categoryMenu">

                <ul class="nav flex-column ms-4 mt-2">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.categories.index') ? 'active' : '' }}"
                            href="{{ route('superadmin.categories.index') }}">
                            <i class="bi bi-list"></i>
                            <span class="menu-text">All Categories</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.categories.create') ? 'active' : '' }}"
                            href="{{ route('superadmin.categories.create') }}">
                            <i class="bi bi-plus-circle"></i>
                            <span class="menu-text">Add Category</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>
    </ul>
</div>
