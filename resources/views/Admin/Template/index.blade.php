<div class="sidebar p-3" id="sidebar">

    <ul class="nav flex-column gap-2" style="list-style: none; padding-left: 0;">

        {{-- DASHBOARD --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>

        {{-- ================= PRODUCT ================= --}}
        {{-- ================= PRODUCT ================= --}}
        <li class="nav-item">

            <a class="nav-link d-flex justify-content-between align-items-center 
    {{ request()->routeIs('admin.products.*') ||
    request()->routeIs('admin.categories.*') ||
    request()->routeIs('admin.collections.*') ||
    request()->routeIs('admin.temperatures.*')
        ? ''
        : 'collapsed' }}"
                data-bs-toggle="collapse" data-bs-target="#productMenu">

                <span>
                    <i class="bi bi-box"></i>
                    <span class="menu-text">Product</span>
                </span>

                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse 
    {{ request()->routeIs('admin.products.*') ||
    request()->routeIs('admin.categories.*') ||
    request()->routeIs('admin.collections.*') ||
    request()->routeIs('admin.temperatures.*')
        ? 'show'
        : '' }}"
                id="productMenu">

                <ul class="nav flex-column ms-4 mt-2">

                    {{-- PRODUCTS (LEVEL 2) --}}
                    <li class="nav-item">

                        <a class="nav-link d-flex justify-content-between align-items-center 
                {{ request()->routeIs('admin.products.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#productsSubMenu">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                <span class="menu-text">Products</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('admin.products.*') ? 'show' : '' }}"
                            id="productsSubMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}"
                                        href="{{ route('admin.products.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Products</span>
                                    </a>
                                </li>

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}"
                                        href="{{ route('admin.products.create') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="menu-text">Add Product</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    {{-- CATEGORIES --}}
                    <li class="nav-item">

                        <a class="nav-link d-flex justify-content-between align-items-center 
    {{ request()->routeIs('admin.categories.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#categoryMenu">

                            <span>
                                <i class="bi bi-tags"></i>
                                <span class="menu-text">Categories</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('admin.categories.*') ? 'show' : '' }}"
                            id="categoryMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}"
                                        href="{{ route('admin.categories.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Categories</span>
                                    </a>
                                </li>

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}"
                                        href="{{ route('admin.categories.create') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="menu-text">Add Category</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    {{-- COLLECTIONS --}}
                    <li class="nav-item">

                        <a class="nav-link d-flex justify-content-between align-items-center 
    {{ request()->routeIs('admin.collections.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#collectionMenu">

                            <span>
                                <i class="bi bi-grid"></i>
                                <span class="menu-text">Collections</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('admin.collections.*') ? 'show' : '' }}"
                            id="collectionMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.collections.index') ? 'active' : '' }}"
                                        href="{{ route('admin.collections.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Collections</span>
                                    </a>
                                </li>

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.collections.create') ? 'active' : '' }}"
                                        href="{{ route('admin.collections.create') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="menu-text">Add Collection</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    {{-- TEMPERATURE --}}
                    <li class="nav-item">

                        <a class="nav-link d-flex justify-content-between align-items-center 
    {{ request()->routeIs('admin.temperatures.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#temperatureMenu">

                            <span>
                                <i class="bi bi-thermometer"></i>
                                <span class="menu-text">Temperature</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('admin.temperatures.*') ? 'show' : '' }}"
                            id="temperatureMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.temperatures.index') ? 'active' : '' }}"
                                        href="{{ route('admin.temperatures.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Temperature</span>
                                    </a>
                                </li>

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.temperatures.create') ? 'active' : '' }}"
                                        href="{{ route('admin.temperatures.create') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="menu-text">Add Temperature</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    {{-- INTENSITIES --}}
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center 
    {{ request()->routeIs('admin.intensities.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#intensityMenu">

                            <span>
                                <i class="bi bi-bar-chart"></i>
                                <span class="menu-text">Intensities</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('admin.intensities.*') ? 'show' : '' }}"
                            id="intensityMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                {{-- ALL INTENSITIES --}}
                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.intensities.index') ? 'active' : '' }}"
                                        href="{{ route('admin.intensities.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Intensities</span>
                                    </a>
                                </li>

                                {{-- ADD INTENSITY --}}
                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.intensities.create') ? 'active' : '' }}"
                                        href="{{ route('admin.intensities.create') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="menu-text">Add Intensity</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    {{-- INSULATIONS --}}
                    <li class="nav-item">

                        <a class="nav-link d-flex justify-content-between align-items-center 
    {{ request()->routeIs('admin.insulations.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#insulationMenu">

                            <span>
                                <i class="bi bi-shield-check"></i>
                                <span class="menu-text">Insulations</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('admin.insulations.*') ? 'show' : '' }}"
                            id="insulationMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                {{-- ALL INSULATIONS --}}
                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.insulations.index') ? 'active' : '' }}"
                                        href="{{ route('admin.insulations.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Insulations</span>
                                    </a>
                                </li>

                                {{-- ADD INSULATION --}}
                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.insulations.create') ? 'active' : '' }}"
                                        href="{{ route('admin.insulations.create') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="menu-text">Add Insulation</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    {{-- BREATHABILITY --}}
                    <li class="nav-item">

                        <a class="nav-link d-flex justify-content-between align-items-center 
    {{ request()->routeIs('admin.breathabilities.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#breathabilityMenu">

                            <span>
                                <i class="bi bi-wind"></i>
                                <span class="menu-text">Breathability</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('admin.breathabilities.*') ? 'show' : '' }}"
                            id="breathabilityMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                {{-- ALL DATA --}}
                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.breathabilities.index') ? 'active' : '' }}"
                                        href="{{ route('admin.breathabilities.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Breathability</span>
                                    </a>
                                </li>

                                {{-- ADD --}}
                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.breathabilities.create') ? 'active' : '' }}"
                                        href="{{ route('admin.breathabilities.create') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="menu-text">Add Breathability</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    {{-- MATERIALS --}}
                    <li class="nav-item">

                        <a class="nav-link d-flex justify-content-between align-items-center 
    {{ request()->routeIs('admin.materials.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#materialsMenu">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                <span class="menu-text">Materials</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('admin.materials.*') ? 'show' : '' }}"
                            id="materialsMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                {{-- ALL MATERIALS --}}
                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.materials.index') ? 'active' : '' }}"
                                        href="{{ route('admin.materials.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Materials</span>
                                    </a>
                                </li>

                                {{-- ADD MATERIAL --}}
                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.materials.create') ? 'active' : '' }}"
                                        href="{{ route('admin.materials.create') }}">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="menu-text">Add Material</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </li>

        {{-- ================= BLOG ================= --}}
        <li class="nav-item">

            <a class="nav-link d-flex justify-content-between align-items-center 
            {{ request()->routeIs('admin.blogs.*') || request()->routeIs('admin.blog-categories.*') || request()->routeIs('admin.tags.*') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" data-bs-target="#blogMenu">

                <span>
                    <i class="bi bi-journal"></i>
                    <span class="menu-text">Blog</span>
                </span>

                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse 
            {{ request()->routeIs('admin.blogs.*') || request()->routeIs('admin.blog-categories.*') || request()->routeIs('admin.tags.*') ? 'show' : '' }}"
                id="blogMenu">

                <ul class="nav flex-column ms-4 mt-2">

                    {{-- BLOGS --}}
                    <li class="nav-item">

                        <a class="nav-link d-flex justify-content-between align-items-center 
                        {{ request()->routeIs('admin.blogs.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#blogsSubMenu">

                            <span>
                                <i class="bi bi-file-text"></i>
                                <span class="menu-text">Blogs</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('admin.blogs.*') ? 'show' : '' }}"
                            id="blogsSubMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.blogs.index') ? 'active' : '' }}"
                                        href="{{ route('admin.blogs.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Blogs</span>
                                    </a>
                                </li>

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.blogs.create') ? 'active' : '' }}"
                                        href="{{ route('admin.blogs.create') }}">
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
                        {{ request()->routeIs('admin.blog-categories.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#blogCategoryMenu">

                            <span>
                                <i class="bi bi-tags"></i>
                                <span class="menu-text">Categories</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('admin.blog-categories.*') ? 'show' : '' }}"
                            id="blogCategoryMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.blog-categories.index') ? 'active' : '' }}"
                                        href="{{ route('admin.blog-categories.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Categories</span>
                                    </a>
                                </li>

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.blog-categories.create') ? 'active' : '' }}"
                                        href="{{ route('admin.blog-categories.create') }}">
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
                        {{ request()->routeIs('admin.tags.*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#tagsMenu">

                            <span>
                                <i class="bi bi-tag"></i>
                                <span class="menu-text">Tags</span>
                            </span>

                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('admin.tags.*') ? 'show' : '' }}" id="tagsMenu">

                            <ul class="nav flex-column ms-4 mt-2">

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.tags.index') ? 'active' : '' }}"
                                        href="{{ route('admin.tags.index') }}">
                                        <i class="bi bi-list"></i>
                                        <span class="menu-text">All Tags</span>
                                    </a>
                                </li>

                                <li class="nav-item nav-sub-item">
                                    <a class="nav-link {{ request()->routeIs('admin.tags.create') ? 'active' : '' }}"
                                        href="{{ route('admin.tags.create') }}">
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

            <div class="collapse {{ request()->routeIs('admin.users.*') ? 'show' : '' }}" id="userMenu">

                <ul class="nav flex-column ms-4 mt-2">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            <i class="bi bi-list"></i>
                            <span class="menu-text">All Users</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}"
                            href="{{ route('admin.users.create') }}">
                            <i class="bi bi-plus-circle"></i>
                            <span class="menu-text">Add User</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>
    </ul>
</div>
