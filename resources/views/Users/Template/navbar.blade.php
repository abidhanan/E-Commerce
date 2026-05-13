@if (Auth::user() && Auth::user()->hasRole('admin|superadmin|editor|finance|staff'))
    <nav class="admin-shortcut-header">
        <a href="{{ route('dashboard') }}" class="admin-shortcut-link">
            Go to Admin Panel →
        </a>
    </nav>
@endif
<header class="at-top">
    <div class="header-main">
        <div class="header-left">
            <button class="mobile-menu-toggle" id="mobileMenuToggle" type="button" aria-controls="shopSidebar"
                aria-expanded="false">
                Menu
            </button>
            <a href="#" id="shopToggle" class="desktop-nav-link" aria-controls="shopSidebar"
                aria-expanded="false">Shop</a>
            <a href="{{ route('about') }}" class="desktop-nav-link">About</a>
            <a href="{{ route('explore.index') }}" class="desktop-nav-link">Explore</a>
            @if (Auth::check())
                <a href="{{ route('account.index') }}" class="desktop-nav-link">Account</a>
                <a href="{{ route('user.orders.index') }}" class="desktop-nav-link">Orders</a>
            @else
                <a href="{{ route('login') }}" class="desktop-nav-link">Login</a>
            @endif
        </div>

        <div class="logo">
            <a href="{{ route('home') }}" aria-label="Gloaming Imagine home">
                <img src="{{ asset('images/logogloaming.png') }}" 
                     alt="Gloaming Imagine" 
                     class="logo-image logo-light">
                <img src="{{ asset('images/logogloamingdark.png') }}" 
                     alt="Gloaming Imagine" 
                     class="logo-image logo-dark">
            </a>
        </div>

        <div class="header-right">
            <button class="dark-mode-toggle" id="darkModeToggle" type="button" title="Toggle Dark Mode"
                aria-label="Toggle dark mode">
                {{-- <span class="toggle-icon" aria-hidden="true"></span> --}}
                <span class="toggle-label">Dark Mode</span>
            </button>
            <div class="search-container">
                <button class="search-toggle" id="searchToggle" type="button" aria-controls="searchBox"
                    aria-expanded="false">
                    Search
                </button>
                <div class="search-box" id="searchBox">
                    <div class="search-input-wrapper">
                        <input type="text" class="search-input" placeholder="Search products..." id="searchInput">
                        <button class="search-close" id="searchClose" type="button"
                            aria-label="Close search">×</button>
                    </div>
                    <div class="search-dropdown" id="searchDropdown"></div>
                </div>
            </div>
            <a href="{{ route('wishlist.index') }}" class="header-badge-wrapper header-link-optional">
                Wishlist
                <span id="wishlistHeaderCount" class="header-badge">0</span>
            </a>

            <a href="#" id="cartToggle" class="header-badge-wrapper" aria-controls="cartSidebar"
                aria-expanded="false">
                Cart
                <span id="cartCount" class="header-badge">0</span>
            </a>
        </div>
        <div id="cartContainer"></div>
    </div>
</header>
