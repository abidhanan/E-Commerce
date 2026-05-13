@extends('Users.Template.index')

@section('title', 'Explore GloamingImaginee')

@push('css')
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --clay: #7a7367;
            --clay-dark: #5c574f;
            --clay-light: #a09890;
            --off-white: #f5f3ef;
            --ink: #1a1916;
            --ink-muted: #6b6860;
            --white: #ffffff;
            --accent: #c8b99a;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--off-white);
            color: var(--ink);
            overflow-x: hidden;
        }

        /* ─── ANNOUNCEMENT BAR ─── */
        .announcement {
            background: var(--ink);
            color: var(--white);
            text-align: center;
            font-size: 11px;
            letter-spacing: 0.08em;
            padding: 10px 48px 10px 16px;
            position: relative;
            z-index: 200;
        }

        .announcement a {
            color: var(--accent);
            text-decoration: underline;
            cursor: pointer;
        }

        .announcement-close {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--white);
            font-size: 18px;
            cursor: pointer;
            line-height: 1;
        }

        /* ─── NAVBAR ─── */
        nav {
            background: var(--white);
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.12);
            padding: 0 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-links {
            display: flex;
            gap: 32px;
        }

        .nav-links a,
        .nav-actions a {
            font-size: 13px;
            letter-spacing: 0.06em;
            color: var(--ink);
            text-decoration: none;
            text-transform: uppercase;
            transition: color 0.2s;
        }

        .nav-links a:hover,
        .nav-actions a:hover {
            color: var(--clay);
        }

        .nav-logo {
            text-align: center;
            line-height: 1;
        }

        .nav-logo-name {
            font-family: Arial, sans-serif;
            font-weight: 700;
            font-size: 18px;
            letter-spacing: 0.08em;
            color: var(--ink);
        }

        .nav-logo-sub {
            font-size: 8px;
            letter-spacing: 0.2em;
            color: var(--ink-muted);
            text-transform: uppercase;
            margin-top: 2px;
        }

        .nav-actions {
            display: flex;
            gap: 28px;
            align-items: center;
        }

        .cart-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            background: var(--ink);
            color: var(--white);
            font-size: 10px;
            border-radius: 50%;
            margin-left: 4px;
        }

        .nav-hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
        }

        .nav-hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--ink);
            border-radius: 2px;
            transition: transform 0.3s, opacity 0.3s;
        }

        .nav-drawer {
            display: none;
            position: fixed;
            top: 64px;
            left: 0;
            right: 0;
            background: var(--white);
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.12);
            padding: 24px;
            z-index: 99;
            flex-direction: column;
            gap: 20px;
        }

        .nav-drawer.open {
            display: flex;
        }

        .nav-drawer a {
            font-size: 14px;
            letter-spacing: 0.06em;
            color: var(--ink);
            text-decoration: none;
            text-transform: uppercase;
            padding: 8px 0;
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.08);
        }

        /* ─── BANNER ZONE ─── */
        .banner-zone {
            background: var(--clay);
            position: relative;
            overflow: hidden;
        }

        .banner {
            padding: 48px 0 40px;
            position: relative;
            z-index: 2;
            overflow: visible;
        }

        .banner-text-block {
            width: 100%;
            transform: rotate(-5deg);
            transform-origin: center center;
        }

        .banner-line {
            font-family: Arial, sans-serif;
            font-weight: 700;
            font-size: clamp(36px, 10.5vw, 155px);
            color: var(--white);
            letter-spacing: 0.04em;
            line-height: 1.0;
            white-space: nowrap;
            overflow: hidden;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            will-change: transform, opacity;
        }

        .banner-line.l1 {
            transform: translateX(110vw);
            transition: transform 1.1s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.01s 0s;
        }

        .banner-line.l2 {
            transform: translateX(-110vw);
            transition: transform 1.1s cubic-bezier(0.16, 1, 0.3, 1) 0.08s, opacity 0.01s 0.08s;
        }

        .banner-line.visible {
            transform: translateX(0);
            opacity: 1;
        }

        .banner-text {
            display: block;
            width: 100%;
            text-align: center;
        }

        /* ─── FILTER BAR ─── */
        .filter-section {
            position: relative;
            z-index: 2;
            padding: 24px 48px 48px;
            display: flex;
            justify-content: center;
            gap: 0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .filter-section::-webkit-scrollbar {
            display: none;
        }

        .filter-btn {
            padding: 12px 40px;
            border: 1px solid rgba(255, 255, 255, 0.45);
            background: transparent;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.75);
            cursor: pointer;
            transition: background 0.25s, color 0.25s, border-color 0.25s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .filter-btn:not(:last-child) {
            border-right: none;
        }

        .filter-btn.active {
            background: var(--white);
            color: var(--ink);
            border-color: var(--white);
        }

        .filter-btn:hover:not(.active) {
            background: rgba(255, 255, 255, 0.12);
            color: var(--white);
        }

        /* ─── CONTENT ZONE ─── */
        .content-zone {
            background: var(--clay);
            padding-bottom: 72px;
            position: relative;
        }

        .content-zone::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4'%3E%3Crect width='1' height='1' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* ─── WELCOME BAR ─── */
        .welcome-bar {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--white);
            padding: 24px 32px;
            margin: 0 48px 40px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .welcome-bar h3 {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 5px;
            letter-spacing: 0.01em;
        }

        .welcome-bar p {
            font-size: 13px;
            color: var(--ink-muted);
            max-width: 380px;
            line-height: 1.6;
        }

        .btn-account {
            background: var(--ink);
            color: var(--white);
            border: none;
            padding: 14px 28px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-account:hover {
            background: var(--clay-dark);
        }


        /* ════════════════════════════════════════
                                           BLOG GRID — 3 col desktop
                                        ════════════════════════════════════════ */
        .blog-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            width: calc(100% - 96px);
            margin: 0 auto;
        }

        /* ── CARD ── */
        .blog-card {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            background: var(--clay-dark);
            width: 100%;
            /* Fixed aspect ratio so all 3 are same height */
            aspect-ratio: 2 / 3;
            border-radius: 2px;
        }

        .blog-card.hidden {
            display: none;
        }

        /* Blurred bg */
        .card-bg {
            position: absolute;
            inset: -20px;
            background-size: cover;
            background-position: center;
            filter: blur(16px) brightness(0.55) saturate(0.8);
            transform: scale(1.06);
            transition: filter 0.6s ease;
            will-change: filter;
        }

        .blog-card:hover .card-bg {
            filter: blur(20px) brightness(0.45) saturate(0.7);
        }

        /* Sharp center image */
        .card-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            will-change: transform;
        }

        .blog-card:hover .card-img {
            transform: scale(1.04);
        }

        /* Dark gradient overlay — stronger at bottom */
        .card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                    rgba(5, 4, 2, 0.95) 0%,
                    rgba(5, 4, 2, 0.60) 35%,
                    rgba(5, 4, 2, 0.15) 65%,
                    transparent 100%);
            pointer-events: none;
        }

        /* Content anchored to bottom */
        .card-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 28px 28px 32px;
            color: var(--white);
        }

        .card-tag {
            font-size: 10px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 10px;
            display: block;
            font-weight: 600;
        }

        .card-title {
            font-family: Arial, sans-serif;
            font-weight: 700;
            font-size: clamp(18px, 1.6vw, 26px);
            letter-spacing: 0.01em;
            line-height: 1.15;
            margin-bottom: 20px;
            color: var(--white);
        }

        .card-explore {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.08);
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            text-decoration: none;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: background 0.25s, border-color 0.25s, gap 0.25s;
        }

        .card-explore:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--white);
            gap: 16px;
        }

        .card-explore .arrow {
            font-size: 14px;
            transition: transform 0.25s;
            display: inline-block;
        }

        .card-explore:hover .arrow {
            transform: translateX(4px);
        }


        /* ══════════════════════════
                                           TABLET ≤ 900px — 2 kolom
                                        ══════════════════════════ */
        @media (max-width: 900px) {
            nav {
                padding: 0 24px;
            }

            .nav-links,
            .nav-actions {
                display: none;
            }

            .nav-hamburger {
                display: flex;
            }

            .welcome-bar {
                margin: 0 24px 32px;
                padding: 20px 24px;
            }

            .filter-section {
                padding: 20px 24px 40px;
                justify-content: flex-start;
            }

            .blog-grid {
                grid-template-columns: repeat(2, 1fr);
                width: calc(100% - 48px);
                gap: 16px;
            }

            .card-title {
                font-size: clamp(16px, 2.4vw, 22px);
            }
        }


        /* ══════════════════════════
                                           MOBILE ≤ 600px — 1 kolom
                                        ══════════════════════════ */
        @media (max-width: 600px) {
            nav {
                padding: 0 16px;
            }

            .nav-logo-name {
                font-size: 15px;
            }

            .banner {
                padding: 36px 0 28px;
            }

            .banner-text-block {
                transform: rotate(-3deg);
            }

            .filter-section {
                padding: 16px 16px 32px;
            }

            .filter-btn {
                padding: 10px 22px;
                font-size: 11px;
            }

            .welcome-bar {
                margin: 0 16px 24px;
                padding: 20px 20px;
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }

            .btn-account {
                width: 100%;
                text-align: center;
                padding: 13px 20px;
            }

            .blog-grid {
                /* Single column, horizontal card */
                grid-template-columns: 1fr;
                width: calc(100% - 32px);
                gap: 14px;
            }

            /* Mobile: landscape card (wider than tall) */
            .blog-card {
                aspect-ratio: 16 / 10;
                border-radius: 2px;
            }

            .card-content {
                padding: 20px 20px 24px;
            }

            .card-tag {
                font-size: 9px;
                letter-spacing: 0.18em;
                margin-bottom: 7px;
            }

            .card-title {
                font-size: clamp(18px, 5.5vw, 26px);
                margin-bottom: 14px;
                line-height: 1.15;
            }

            .card-explore {
                padding: 8px 16px;
                font-size: 10px;
            }
        }


        /* ══════════════════════════
                                           SMALL ≤ 380px
                                        ══════════════════════════ */
        @media (max-width: 380px) {
            .blog-card {
                aspect-ratio: 4 / 3;
            }

            .banner-text-block {
                transform: rotate(-2deg);
            }

            .card-title {
                font-size: clamp(16px, 5vw, 22px);
            }
        }
    </style>
@endpush

@section('content')

    <div class="banner-zone" id="heroZone">

        <section class="banner" id="banner">
            <div class="banner-text-block">
                <div class="banner-line l1" id="banner-l1">
                    <span class="banner-text" id="line1-text">LET'S EXPLORE</span>
                </div>
                <div class="banner-line l2" id="banner-l2">
                    <span class="banner-text" id="line2-text">GLOAMINGIMAGINEE</span>
                </div>
            </div>
        </section>

        <!-- Filter Bar -->
        {{-- <div class="filter-section">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="stories">Stories</button>
            <button class="filter-btn" data-filter="events">Events</button>
        </div> --}}

    </div><!-- /banner-zone -->


    <div class="content-zone">

        <!-- Welcome -->
        <div class="welcome-bar">
            @if (Auth::check())
                <div>
                    <h3>Welcome back, {{ Auth::user()->name }}!</h3>
                    <p>Discover new stories, events, and product recommendations curated just for you.</p>
                </div>
                <a href="{{ route('home') }}" class="btn-account">Continue Shopping</a>
            @else
                <div>
                    <h3>Welcome</h3>
                    <p>Get inspiration for your next cycling experiences, see our events and get product recommendations.
                    </p>
                </div>
                <button class="btn-account">Create Account</button>
            @endif
        </div>

        <!-- Blog Grid — 3 col desktop / 2 col tablet / 1 col mobile -->
        <div class="blog-grid" id="blog-grid">
            @foreach ($posts as $blog)
                <div class="blog-card" data-cat="{{ $blog->category->name }}">
                    <div class="card-bg" style="background-image: url('{{ asset('storage/' . $blog->thumbnail) }}');">
                    </div>
                    <img class="card-img" src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}">
                    <div class="card-overlay"></div>
                    <div class="card-content">
                        <span class="card-tag">{{ $blog->category->name }}</span>
                        <div class="card-title">{{ $blog->title }}</div>
                        <a href="{{ route('post.show', $blog->slug) }}" class="card-explore">
                            Explore <span class="arrow">→</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div><!-- /blog-grid -->

    </div><!-- /content-zone -->

@endsection

@push('scripts')
    <script>
        // ── 1. BANNER ENTRANCE ──────────────────────────────────────────────
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.getElementById('banner-l1').classList.add('visible');
                document.getElementById('banner-l2').classList.add('visible');
            }, 150);
        });

        // ── 2. SCROLL FADE ──────────────────────────────────────────────────
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const bannerSection = document.getElementById('banner');

        function updateBannerScroll() {
            if (prefersReduced) return;
            const scrollY = window.scrollY;
            const bannerH = bannerSection.offsetHeight;
            const progress = Math.min(scrollY / (bannerH * 0.6), 1);
            const opacity = Math.max(1 - progress * 1.05, 0);
            const slide = scrollY * 0.55;

            const l1 = document.getElementById('banner-l1');
            const l2 = document.getElementById('banner-l2');

            if (l1) {
                l1.style.transition = 'opacity 0.05s linear';
                l1.style.opacity = opacity;
                l1.style.transform = `translateX(${slide}px)`;
            }
            if (l2) {
                l2.style.transition = 'opacity 0.05s linear';
                l2.style.opacity = opacity;
                l2.style.transform = `translateX(${-slide}px)`;
            }
        }

        window.addEventListener('scroll', updateBannerScroll, {
            passive: true
        });

        // ── 3. FILTER ────────────────────────────────────────────────────────
        const filterBtns = document.querySelectorAll('.filter-btn');
        const cards = document.querySelectorAll('.blog-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.dataset.filter;
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                cards.forEach(card => {
                    const match = filter === 'all' || card.dataset.cat === filter;
                    card.classList.toggle('hidden', !match);
                });
            });
        });

        // ── 4. HAMBURGER MENU ────────────────────────────────────────────────
        const hamburger = document.getElementById('nav-hamburger');
        const drawer = document.getElementById('nav-drawer');

        if (hamburger && drawer) {
            hamburger.addEventListener('click', () => {
                const isOpen = drawer.classList.toggle('open');
                hamburger.setAttribute('aria-expanded', isOpen);
                hamburger.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
            });

            document.addEventListener('click', (e) => {
                if (!hamburger.contains(e.target) && !drawer.contains(e.target)) {
                    drawer.classList.remove('open');
                    hamburger.setAttribute('aria-expanded', false);
                }
            });
        }
    </script>
@endpush
