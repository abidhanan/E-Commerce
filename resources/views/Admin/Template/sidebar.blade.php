@php
    $matchesAny = static function (array $patterns): bool {
        foreach ($patterns as $pattern) {
            if (request()->routeIs($pattern)) {
                return true;
            }
        }

        return false;
    };

    $resourceListPatterns = static function (string $routePrefix, array $extra = []): array {
        return array_merge(["{$routePrefix}.index", "{$routePrefix}.edit", "{$routePrefix}.show"], $extra);
    };

    $sidebarItems = [
        [
            'label' => 'Dashboard',
            'icon' => 'bi bi-speedometer2',
            'route' => 'dashboard',
            'active' => ['dashboard'],
            'permission' => 'view dashboard',
        ],
        [
            'label' => 'Displays',
            'icon' => 'bi bi-display',
            'permission' => 'manage displays',
            'collapse_id' => 'displayMenu',
            'active' => [
                'admin.displays.*',
                'admin.bestsellers.*',
                'admin.custom-collections-display.*',
                'admin.social-links.*',
                'admin.faqs.*',
                'admin.aboutus.*',
                'admin.return-steps.*',
                'admin.how-to-buy-steps.*',
                'admin.care-guides.*',
                'admin.consent-documents.*',
            ],
            'children' => [
                // PERBAIKAN MUTLAK: Mengubah label agar admin tidak tersesat mencari menu Kolaborasi/Kampanye
                [
                    'label' => 'Displays & Collaboration',
                    'icon' => 'bi bi-layout-wtf',
                    'route' => 'admin.displays.index',
                    'active' => ['admin.displays.*'],
                ],

                [
                    'label' => 'Bestsellers',
                    'icon' => 'bi bi-stars',
                    'route' => 'admin.bestsellers.index',
                    'active' => ['admin.bestsellers.*'],
                ],
                [
                    'label' => 'Display Login',
                    'icon' => 'bi bi-door-open',
                    'route' => 'admin.display-logins.index',
                    'active' => ['admin.display-logins.*'],
                ],

                [
                    'label' => 'Custom Collections',
                    'icon' => 'bi bi-grid-1x2',
                    'route' => 'admin.custom-collections-display.index',
                    'active' => ['admin.custom-collections-display.*'],
                ],

                [
                    'label' => 'Social & Stores',
                    'icon' => 'bi bi-share-fill',
                    'route' => 'admin.social-links.index',
                    'active' => ['admin.social-links.*'],
                ],

                [
                    'label' => 'FAQs',
                    'icon' => 'bi bi-patch-question',
                    'route' => 'admin.faqs.index',
                    'active' => ['admin.faqs.*'],
                ],

                [
                    'label' => 'About Us',
                    'icon' => 'bi bi-building',
                    'route' => 'admin.aboutus.index',
                    'active' => ['admin.aboutus.*'],
                ],

                [
                    'label' => 'Return Steps',
                    'icon' => 'bi bi-arrow-return-left',
                    'route' => 'admin.return-steps',
                    'active' => ['admin.return-steps.*'],
                ],

                [
                    'label' => 'How To Buy Steps',
                    'icon' => 'bi bi-cart-check',
                    'route' => 'admin.how-to-buy-steps',
                    'active' => ['admin.how-to-buy-steps.*'],
                ],

                [
                    'label' => 'Care Guides',
                    'icon' => 'bi bi-journal-text',
                    'route' => 'admin.care-guides.index',
                    'active' => ['admin.care-guides.*'],
                ],
                [
                    'label' => 'Consent Documents',
                    'icon' => 'bi bi-file-earmark-lock',
                    'route' => 'admin.consent-documents.index',
                    'active' => ['admin.consent-documents.*'],
                ],
                [
                    'label' => 'Crash Replacements',
                    'icon' => 'bi bi-bandaid',
                    'route' => 'admin.crash-replacements.index',
                    'active' => ['admin.crash-replacements.*'],
                ],
            ],
        ],
        [
            'label' => 'Product',
            'icon' => 'bi bi-box',
            'collapse_id' => 'productMenu',
            'active' => [
                'admin.products.*',
                'admin.categories.*',
                'admin.collections.*',
                'admin.temperatures.*',
                'admin.intensities.*',
                'admin.insulations.*',
                'admin.breathabilities.*',
                'admin.materials.*',
                'admin.size-guides.*',
            ],
            'children' => [
                [
                    'label' => 'Products',
                    'icon' => 'bi bi-box-seam',
                    'permission' => 'manage products',
                    'collapse_id' => 'productsSubMenu',
                    'active' => ['admin.products.*'],
                    'children' => [
                        [
                            'label' => 'All Products',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.products.index',
                            'active' => $resourceListPatterns('admin.products'),
                        ],
                        [
                            'label' => 'Add Product',
                            'icon' => 'bi bi-plus-circle',
                            'route' => 'admin.products.create',
                            'active' => ['admin.products.create'],
                        ],
                    ],
                ],
                [
                    'label' => 'Categories',
                    'icon' => 'bi bi-tags',
                    'permission' => 'manage categories',
                    'collapse_id' => 'categoryMenu',
                    'active' => ['admin.categories.*'],
                    'children' => [
                        [
                            'label' => 'All Categories',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.categories.index',
                            'active' => $resourceListPatterns('admin.categories'),
                        ],
                        [
                            'label' => 'Add Category',
                            'icon' => 'bi bi-plus-circle',
                            'route' => 'admin.categories.create',
                            'active' => ['admin.categories.create'],
                        ],
                    ],
                ],
                [
                    'label' => 'Collections',
                    'icon' => 'bi bi-grid',
                    'permission' => 'manage collections',
                    'collapse_id' => 'collectionMenu',
                    'active' => ['admin.collections.*'],
                    'children' => [
                        [
                            'label' => 'All Collections',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.collections.index',
                            'active' => $resourceListPatterns('admin.collections'),
                        ],
                        [
                            'label' => 'Add Collection',
                            'icon' => 'bi bi-plus-circle',
                            'route' => 'admin.collections.create',
                            'active' => ['admin.collections.create'],
                        ],
                    ],
                ],
                [
                    'label' => 'Temperature',
                    'icon' => 'bi bi-thermometer',
                    'permission' => 'manage product attributes',
                    'collapse_id' => 'temperatureMenu',
                    'active' => ['admin.temperatures.*'],
                    'children' => [
                        [
                            'label' => 'All Temperature',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.temperatures.index',
                            'active' => $resourceListPatterns('admin.temperatures'),
                        ],
                        [
                            'label' => 'Add Temperature',
                            'icon' => 'bi bi-plus-circle',
                            'route' => 'admin.temperatures.create',
                            'active' => ['admin.temperatures.create'],
                        ],
                    ],
                ],
                [
                    'label' => 'Intensities',
                    'icon' => 'bi bi-bar-chart',
                    'permission' => 'manage product attributes',
                    'collapse_id' => 'intensityMenu',
                    'active' => ['admin.intensities.*'],
                    'children' => [
                        [
                            'label' => 'All Intensities',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.intensities.index',
                            'active' => $resourceListPatterns('admin.intensities'),
                        ],
                        [
                            'label' => 'Add Intensity',
                            'icon' => 'bi bi-plus-circle',
                            'route' => 'admin.intensities.create',
                            'active' => ['admin.intensities.create'],
                        ],
                    ],
                ],
                [
                    'label' => 'Insulations',
                    'icon' => 'bi bi-shield-check',
                    'permission' => 'manage product attributes',
                    'collapse_id' => 'insulationMenu',
                    'active' => ['admin.insulations.*'],
                    'children' => [
                        [
                            'label' => 'All Insulations',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.insulations.index',
                            'active' => $resourceListPatterns('admin.insulations'),
                        ],
                        [
                            'label' => 'Add Insulation',
                            'icon' => 'bi bi-plus-circle',
                            'route' => 'admin.insulations.create',
                            'active' => ['admin.insulations.create'],
                        ],
                    ],
                ],
                [
                    'label' => 'Breathability',
                    'icon' => 'bi bi-wind',
                    'permission' => 'manage product attributes',
                    'collapse_id' => 'breathabilityMenu',
                    'active' => ['admin.breathabilities.*'],
                    'children' => [
                        [
                            'label' => 'All Breathability',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.breathabilities.index',
                            'active' => $resourceListPatterns('admin.breathabilities'),
                        ],
                        [
                            'label' => 'Add Breathability',
                            'icon' => 'bi bi-plus-circle',
                            'route' => 'admin.breathabilities.create',
                            'active' => ['admin.breathabilities.create'],
                        ],
                    ],
                ],
                [
                    'label' => 'Materials',
                    'icon' => 'bi bi-box-seam',
                    'permission' => 'manage product attributes',
                    'collapse_id' => 'materialsMenu',
                    'active' => ['admin.materials.*'],
                    'children' => [
                        [
                            'label' => 'All Materials',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.materials.index',
                            'active' => $resourceListPatterns('admin.materials'),
                        ],
                        [
                            'label' => 'Add Material',
                            'icon' => 'bi bi-plus-circle',
                            'route' => 'admin.materials.create',
                            'active' => ['admin.materials.create'],
                        ],
                    ],
                ],
                [
                    'label' => 'Size Guides',
                    'icon' => 'bi bi-rulers',
                    'permission' => 'manage size guides',
                    'collapse_id' => 'sizeGuideMenu',
                    'active' => ['admin.size-guides.*'],
                    'children' => [
                        [
                            'label' => 'All Size Guides',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.size-guides.index',
                            'active' => ['admin.size-guides.*'],
                        ],
                    ],
                ],
            ],
        ],
        [
            'label' => 'Orders',
            'icon' => 'bi bi-receipt',
            'route' => 'admin.orders.index',
            'active' => ['admin.orders.*'],
            'permission' => 'manage orders',
        ],
        [
            'label' => 'Order Complaints',
            'icon' => 'bi bi-exclamation-triangle',
            'route' => 'admin.order-complaints.index',
            'active' => ['admin.order-complaints.*'],
            'permission' => 'manage orders',
        ],
        [
            'label' => 'Finance',
            'icon' => 'bi bi-cash-coin',
            'roles' => ['superadmin', 'admin', 'finance'],
            'collapse_id' => 'financeMenu',
            'active' => ['admin.finance.*'],
            'children' => [
                [
                    'label' => 'Overview',
                    'icon' => 'bi bi-speedometer2',
                    'route' => 'admin.finance.index',
                    'active' => ['admin.finance.index'],
                ],
                [
                    'label' => 'Laporan Transaksi',
                    'icon' => 'bi bi-receipt-cutoff',
                    'route' => 'admin.finance.orders',
                    'active' => ['admin.finance.orders'],
                ],
            ],
        ],
        [
            'label' => 'Error Logs',
            'icon' => 'bi bi-bug',
            'route' => 'admin.error-logs.index',
            'active' => ['admin.error-logs.*'],
            'roles' => ['superadmin', 'admin'],
        ],
        [
            'label' => 'Blog',
            'icon' => 'bi bi-journal',
            'permission' => 'manage blogs',
            'collapse_id' => 'blogMenu',
            'active' => ['admin.blogs.*', 'admin.blog-categories.*', 'admin.tags.*'],
            'children' => [
                [
                    'label' => 'Blogs',
                    'icon' => 'bi bi-file-text',
                    'collapse_id' => 'blogsSubMenu',
                    'active' => ['admin.blogs.*'],
                    'children' => [
                        [
                            'label' => 'All Blogs',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.blogs.index',
                            'active' => $resourceListPatterns('admin.blogs', ['admin.blogs.publish']),
                        ],
                        [
                            'label' => 'Add Blog',
                            'icon' => 'bi bi-plus-circle',
                            'route' => 'admin.blogs.create',
                            'active' => ['admin.blogs.create'],
                        ],
                    ],
                ],
                [
                    'label' => 'Categories',
                    'icon' => 'bi bi-tags',
                    'collapse_id' => 'blogCategoryMenu',
                    'active' => ['admin.blog-categories.*'],
                    'children' => [
                        [
                            'label' => 'All Categories',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.blog-categories.index',
                            'active' => $resourceListPatterns('admin.blog-categories'),
                        ],
                        [
                            'label' => 'Add Category',
                            'icon' => 'bi bi-plus-circle',
                            'route' => 'admin.blog-categories.create',
                            'active' => ['admin.blog-categories.create'],
                        ],
                    ],
                ],
                [
                    'label' => 'Tags',
                    'icon' => 'bi bi-tag',
                    'collapse_id' => 'tagsMenu',
                    'active' => ['admin.tags.*'],
                    'children' => [
                        [
                            'label' => 'All Tags',
                            'icon' => 'bi bi-list',
                            'route' => 'admin.tags.index',
                            'active' => $resourceListPatterns('admin.tags'),
                        ],
                        [
                            'label' => 'Add Tag',
                            'icon' => 'bi bi-plus-circle',
                            'route' => 'admin.tags.create',
                            'active' => ['admin.tags.create'],
                        ],
                    ],
                ],
            ],
        ],
        [
            'label' => 'Users',
            'icon' => 'bi bi-people',
            'collapse_id' => 'userMenu',
            'active' => ['admin.users.*', 'admin.role-access.*', 'admin.performance.*'],
            'children' => [
                [
                    'label' => 'All Users',
                    'icon' => 'bi bi-list',
                    'route' => 'admin.users.index',
                    'active' => $resourceListPatterns('admin.users', ['admin.users.loglogin']),
                    'permission' => 'manage users',
                ],
                [
                    'label' => 'Add User',
                    'icon' => 'bi bi-plus-circle',
                    'route' => 'admin.users.create',
                    'active' => ['admin.users.create'],
                    'permission' => 'manage users',
                ],
                [
                    'label' => 'Kinerja Tim',
                    'icon' => 'bi bi-bar-chart-line',
                    'route' => 'admin.performance.index',
                    'active' => ['admin.performance.*'],
                    'superadmin_only' => true,
                ],
                [
                    'label' => 'Hak Akses Role',
                    'icon' => 'bi bi-shield-lock',
                    'route' => 'admin.role-access.index',
                    'active' => ['admin.role-access.*'],
                    'permission' => 'manage role access',
                    'superadmin_only' => true,
                ],
            ],
        ],
    ];

    $canSeeSidebarItem = static function (array $item, ?string $inheritedPermission = null): bool {
        if (($item['superadmin_only'] ?? false) && !auth()->user()?->hasRole('superadmin')) {
            return false;
        }

        if (!empty($item['roles']) && !auth()->user()?->hasAnyRole($item['roles'])) {
            return false;
        }

        $permission = $item['permission'] ?? $inheritedPermission;

        return !$permission || auth()->user()?->can($permission);
    };

    $filterSidebarItems = function (array $items, ?string $inheritedPermission = null) use (
        &$filterSidebarItems,
        $canSeeSidebarItem,
    ): array {
        $filtered = [];

        foreach ($items as $item) {
            $permission = $item['permission'] ?? $inheritedPermission;

            if (!empty($item['children'])) {
                $item['children'] = $filterSidebarItems($item['children'], $permission);

                if (!empty($item['children']) && $canSeeSidebarItem($item, $inheritedPermission)) {
                    $filtered[] = $item;
                }

                continue;
            }

            if ($canSeeSidebarItem($item, $inheritedPermission)) {
                $filtered[] = $item;
            }
        }

        return $filtered;
    };

    $sidebarItems = $filterSidebarItems($sidebarItems);
@endphp

<div class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <span class="sidebar-brand-mark"><img src="{{ asset('images/logo.png') }}" alt=""></span>
        <span class="sidebar-brand-copy">
            <strong>E-Store Studio</strong>
            <span>Brand operations, catalog, orders, and content.</span>
        </span>
    </a>

    <div class="sidebar-scroll">
        <div class="sidebar-section-label">Navigation</div>
        <ul class="nav flex-column gap-2 list-unstyled mb-0">
            @foreach ($sidebarItems as $item)
                @php
                    $itemHasChildren = !empty($item['children']);
                    $itemIsOpen = $itemHasChildren && $matchesAny($item['active']);
                    $itemIsActive = !$itemHasChildren && $matchesAny($item['active'] ?? [$item['route']]);
                @endphp

                <li class="nav-item">
                    @if ($itemHasChildren)
                        <a class="nav-link d-flex justify-content-between align-items-center {{ $itemIsOpen ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#{{ $item['collapse_id'] }}"
                            aria-controls="{{ $item['collapse_id'] }}"
                            aria-expanded="{{ $itemIsOpen ? 'true' : 'false' }}" role="button">
                            <span class="d-flex align-items-center gap-2">
                                <i class="{{ $item['icon'] }}"></i>
                                <span class="menu-text">{{ $item['label'] }}</span>
                            </span>
                            <i class="bi bi-chevron-down small"></i>
                        </a>

                        <div class="collapse {{ $itemIsOpen ? 'show' : '' }}" id="{{ $item['collapse_id'] }}">
                            <ul class="nav flex-column ms-4 mt-2 list-unstyled">
                                @foreach ($item['children'] as $child)
                                    @php
                                        $childHasChildren = !empty($child['children']);
                                        $childIsOpen = $childHasChildren && $matchesAny($child['active']);
                                        $childIsActive =
                                            !$childHasChildren && $matchesAny($child['active'] ?? [$child['route']]);
                                    @endphp

                                    <li class="nav-item {{ $childHasChildren ? '' : 'nav-sub-item' }}">
                                        @if ($childHasChildren)
                                            <a class="nav-link d-flex justify-content-between align-items-center {{ $childIsOpen ? '' : 'collapsed' }}"
                                                data-bs-toggle="collapse" data-bs-target="#{{ $child['collapse_id'] }}"
                                                aria-controls="{{ $child['collapse_id'] }}"
                                                aria-expanded="{{ $childIsOpen ? 'true' : 'false' }}" role="button">
                                                <span class="d-flex align-items-center gap-2">
                                                    <i class="{{ $child['icon'] }}"></i>
                                                    <span class="menu-text">{{ $child['label'] }}</span>
                                                </span>
                                                <i class="bi bi-chevron-down small"></i>
                                            </a>

                                            <div class="collapse {{ $childIsOpen ? 'show' : '' }}"
                                                id="{{ $child['collapse_id'] }}">
                                                <ul class="nav flex-column ms-4 mt-2 list-unstyled">
                                                    @foreach ($child['children'] as $grandchild)
                                                        @php
                                                            $grandchildIsActive = $matchesAny(
                                                                $grandchild['active'] ?? [$grandchild['route']],
                                                            );
                                                        @endphp

                                                        <li class="nav-item nav-sub-item">
                                                            <a class="nav-link {{ $grandchildIsActive ? 'active' : '' }}"
                                                                href="{{ route($grandchild['route']) }}">
                                                                <i class="{{ $grandchild['icon'] }}"></i>
                                                                <span
                                                                    class="menu-text">{{ $grandchild['label'] }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <a class="nav-link {{ $childIsActive ? 'active' : '' }}"
                                                href="{{ route($child['route']) }}">
                                                <i class="{{ $child['icon'] }}"></i>
                                                <span class="menu-text">{{ $child['label'] }}</span>
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <a class="nav-link {{ $itemIsActive ? 'active' : '' }}" href="{{ route($item['route']) }}">
                            <i class="{{ $item['icon'] }}"></i>
                            <span class="menu-text">{{ $item['label'] }}</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-footer-copy">
            Preview storefront after publishing catalog, display, and content changes.
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-dark">
            <i class="bi bi-box-arrow-up-right"></i>
            <span class="menu-text">View Store</span>
        </a>
    </div>
</div>