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
        {{-- =================== Collection ================== --}}
        <li class="nav-item">

            <a class="nav-link d-flex justify-content-between align-items-center 
    {{ request()->routeIs('superadmin.collections.*') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" data-bs-target="#collectionMenu">

                <span>
                    <i class="bi bi-people"></i>
                    <span class="menu-text">Collections</span>
                </span>

                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse {{ request()->routeIs('superadmin.collections.*') ? 'show' : '' }}" id="collectionMenu">

                <ul class="nav flex-column ms-4 mt-2">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.collections.index') ? 'active' : '' }}"
                            href="{{ route('superadmin.collections.index') }}">
                            <i class="bi bi-list"></i>
                            <span class="menu-text">All Collections</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.collections.create') ? 'active' : '' }}"
                            href="{{ route('superadmin.collections.create') }}">
                            <i class="bi bi-plus-circle"></i>
                            <span class="menu-text">Add Collection</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>
        {{-- ================= BLOG ================= --}}
        <li class="nav-item">

            <a class="nav-link d-flex justify-content-between align-items-center 
            {{ request()->routeIs('superadmin.blogs.*') || request()->routeIs('superadmin.blog-categories.*') || request()->routeIs('superadmin.tags.*') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" data-bs-target="#blogMenu">

                <span>
                    <i class="bi bi-journal"></i>
                    <span class="menu-text">Blog</span>
                </span>

                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse 
            {{ request()->routeIs('superadmin.blogs.*') || request()->routeIs('superadmin.blog-categories.*') || request()->routeIs('superadmin.tags.*') ? 'show' : '' }}"
                id="blogMenu">

                <ul class="nav flex-column ms-4 mt-2">

                    {{-- BLOGS --}}
                    <li class="nav-item">

                        <a class="nav-link d-flex justify-content-between align-items-center 
                        {{ request()->routeIs('superadmin.blogs.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#blogsSubMenu">

                            <span>
                                <i class="bi bi-file-text"></i>
                                <span class="menu-text">Blogs</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('superadmin.blogs.*') ? 'show' : '' }}"
                            id="blogsSubMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('superadmin.blogs.index') ? 'active' : '' }}"
                                        href="{{ route('superadmin.blogs.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Blogs</span>
                                    </a>
                                </li>

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('superadmin.blogs.create') ? 'active' : '' }}"
                                        href="{{ route('superadmin.blogs.create') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="menu-text">Add Blog</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    {{-- BLOG CATEGORIES --}}
                    <li class="nav-item">

                        <a class="nav-link d-flex justify-content-between align-items-center 
                        {{ request()->routeIs('superadmin.blog-categories.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#blogCategoryMenu">

                            <span>
                                <i class="bi bi-tags"></i>
                                <span class="menu-text">Categories</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('superadmin.blog-categories.*') ? 'show' : '' }}"
                            id="blogCategoryMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('superadmin.blog-categories.index') ? 'active' : '' }}"
                                        href="{{ route('superadmin.blog-categories.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Categories</span>
                                    </a>
                                </li>

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('superadmin.blog-categories.create') ? 'active' : '' }}"
                                        href="{{ route('superadmin.blog-categories.create') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="menu-text">Add Category</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    {{-- TAGS --}}
                    <li class="nav-item">

                        <a class="nav-link d-flex justify-content-between align-items-center 
                        {{ request()->routeIs('superadmin.tags.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#tagsMenu">

                            <span>
                                <i class="bi bi-tag"></i>
                                <span class="menu-text">Tags</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('superadmin.tags.*') ? 'show' : '' }}" id="tagsMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('superadmin.tags.index') ? 'active' : '' }}"
                                        href="{{ route('superadmin.tags.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Tags</span>
                                    </a>
                                </li>

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('superadmin.tags.create') ? 'active' : '' }}"
                                        href="{{ route('superadmin.tags.create') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="menu-text">Add Tag</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                </ul>
            </div>

        </li>

    </ul>
</div>
