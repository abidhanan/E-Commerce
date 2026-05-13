@extends('Users.Template.index')

@section('title', ($about?->title ?: 'About Us') . ' | Gloaming Imagine')

@push('css')
    <style>
        .content-page {
            padding: 112px 0 0;
        }

        .content-hero,
        .content-body,
        .content-cta {
            padding: 0 40px;
        }

        .content-hero__inner,
        .content-body__inner,
        .content-cta__inner {
            max-width: 1280px;
            margin: 0 auto;
        }

        .content-hero {
            border-bottom: 1px solid #e7e7e7;
        }

        .content-hero__inner {
            display: grid;
            grid-template-columns: minmax(180px, 260px) minmax(0, 1fr);
            gap: 36px;
            padding: 40px 0 56px;
        }

        .content-kicker {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #666;
        }

        .content-title {
            max-width: 780px;
            font-size: clamp(42px, 6vw, 80px);
            font-weight: 300;
            line-height: 0.95;
        }

        .content-summary {
            max-width: 560px;
            margin-top: 24px;
            font-size: 16px;
            line-height: 1.8;
            color: #5f5f5f;
        }

        .content-body__inner {
            display: grid;
            grid-template-columns: minmax(220px, 300px) minmax(0, 760px);
            gap: 48px;
            padding: 56px 0 96px;
        }

        .content-aside {
            position: sticky;
            top: 112px;
            align-self: start;
            display: grid;
            gap: 20px;
        }

        .content-aside__label {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #666;
        }

        .content-aside__text {
            max-width: 220px;
            font-size: 14px;
            line-height: 1.8;
            color: #5f5f5f;
        }

        .content-link {
            width: fit-content;
            padding-bottom: 4px;
            border-bottom: 1px solid #111;
            color: #111;
            font-size: 13px;
            text-decoration: none;
        }

        .content-prose {
            font-size: 17px;
            line-height: 1.9;
            color: #1d1d1d;
        }

        .content-prose h1,
        .content-prose h2,
        .content-prose h3,
        .content-prose h4 {
            margin: 0 0 20px;
            font-weight: 500;
            line-height: 1.15;
            color: #111;
        }

        .content-prose h1 {
            font-size: 44px;
        }

        .content-prose h2 {
            margin-top: 52px;
            font-size: 32px;
        }

        .content-prose h3,
        .content-prose h4 {
            margin-top: 40px;
            font-size: 24px;
        }

        .content-prose p,
        .content-prose ul,
        .content-prose ol,
        .content-prose blockquote {
            margin: 0 0 20px;
        }

        .content-prose ul,
        .content-prose ol {
            padding-left: 22px;
        }

        .content-prose li+li {
            margin-top: 10px;
        }

        .content-prose blockquote {
            margin-top: 36px;
            padding-left: 24px;
            border-left: 2px solid #111;
            font-size: 24px;
            line-height: 1.55;
            color: #111;
        }

        .content-empty {
            border-top: 1px solid #e7e7e7;
            padding: 32px 0 0;
            font-size: 16px;
            line-height: 1.8;
            color: #666;
        }

        .content-cta {
            border-top: 1px solid #e7e7e7;
        }

        .content-cta__inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            padding: 28px 0 40px;
        }

        .content-cta__copy {
            font-size: 14px;
            line-height: 1.7;
            color: #5f5f5f;
        }

        .content-cta__action {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #111;
            font-size: 13px;
            text-decoration: none;
            white-space: nowrap;
        }

        @media (max-width: 900px) {

            .content-hero,
            .content-body,
            .content-cta {
                padding: 0 20px;
            }

            .content-hero__inner,
            .content-body__inner,
            .content-cta__inner {
                grid-template-columns: 1fr;
            }

            .content-aside {
                position: static;
            }
        }
    </style>
@endpush

@section('content')
    <section class="content-page">
        <div class="content-hero">
            <div class="content-hero__inner">
                <div class="content-kicker">Our Story</div>

                <div>
                    <h1 class="content-title">
                        {{ $about?->title ?: 'Designed for movement. Built for everyday journeys.' }}
                    </h1>

                    <p class="content-summary">
                        Gloaming Imagine was created for people who move with purpose —
                        from early morning rides to everyday commutes.
                        We believe performance gear should feel functional,
                        timeless, and effortless in every environment.
                    </p>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="content-body__inner">
                <aside class="content-aside">
                    <div class="content-aside__label">Our Philosophy</div>

                    <p class="content-aside__text">
                        We focus on clean silhouettes, technical comfort,
                        and versatile essentials made to support modern lifestyles—
                        on the road and beyond.
                    </p>

                    <a href="{{ route('faq') }}" class="content-link">
                        Explore FAQ
                    </a>
                </aside>

                <div>
                    @if ($about?->content)
                        <div class="content-prose">
                            {!! \App\Support\HtmlSanitizer::clean($about->content ?? '', ['p', 'br', 'b', 'strong', 'i', 'em', 'ul', 'ol', 'li', 'a', 'h1', 'h2', 'h3', 'h4', 'blockquote']) !!}
                        </div>
                    @else
                        <div class="content-empty">
                            We’re currently updating our story.
                            Please check back soon to learn more about our journey.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="content-cta">
            <div class="content-cta__inner">
                <p class="content-cta__copy">
                    Have questions about sizing, shipping, orders, or product details?
                </p>

                <a href="{{ route('faq') }}" class="content-cta__action">
                    Visit FAQ
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>
@endsection
