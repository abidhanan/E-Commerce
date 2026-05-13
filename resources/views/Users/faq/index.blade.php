@extends('Users.Template.index')

@section('title', 'FAQ | Gloaming Imagine')

@push('css')
    <style>
        .faq-page {
            padding: 112px 40px 88px;
        }

        .faq-page__inner {
            max-width: 1280px;
            margin: 0 auto;
        }

        .faq-header {
            display: grid;
            grid-template-columns: minmax(240px, 320px) minmax(0, 1fr);
            gap: 40px;
            padding-bottom: 48px;
            border-bottom: 1px solid #e7e7e7;
        }

        .faq-kicker {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #666;
        }

        .faq-title {
            max-width: 760px;
            font-size: clamp(40px, 6vw, 76px);
            font-weight: 300;
            line-height: 0.96;
            color: #111;
        }

        .faq-intro {
            max-width: 560px;
            margin-top: 22px;
            font-size: 16px;
            line-height: 1.8;
            color: #5f5f5f;
        }

        .faq-layout {
            display: grid;
            grid-template-columns: minmax(220px, 320px) minmax(0, 760px);
            gap: 48px;
            padding-top: 40px;
        }

        .faq-aside {
            position: sticky;
            top: 112px;
            align-self: start;
        }

        .faq-aside__label {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #666;
        }

        .faq-aside__text {
            margin-top: 16px;
            max-width: 220px;
            font-size: 14px;
            line-height: 1.8;
            color: #5f5f5f;
        }

        .faq-aside__link {
            display: inline-block;
            margin-top: 20px;
            padding-bottom: 4px;
            border-bottom: 1px solid #111;
            color: #111;
            font-size: 13px;
            text-decoration: none;
        }

        .faq-list {
            border-top: 1px solid #e7e7e7;
        }

        .faq-item {
            border-bottom: 1px solid #e7e7e7;
        }

        .faq-item summary {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            list-style: none;
            cursor: pointer;
            padding: 24px 0;
        }

        .faq-item summary::-webkit-details-marker {
            display: none;
        }

        .faq-question {
            font-size: 22px;
            font-weight: 400;
            line-height: 1.4;
            color: #111;
        }

        .faq-icon {
            flex: 0 0 auto;
            width: 24px;
            height: 24px;
            position: relative;
            margin-top: 3px;
        }

        .faq-icon::before,
        .faq-icon::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            background: #111;
            transform: translate(-50%, -50%);
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .faq-icon::before {
            width: 14px;
            height: 1.5px;
        }

        .faq-icon::after {
            width: 1.5px;
            height: 14px;
        }

        .faq-item[open] .faq-icon::after {
            opacity: 0;
            transform: translate(-50%, -50%) scaleY(0);
        }

        .faq-answer {
            padding: 0 0 24px;
            max-width: 680px;
            font-size: 15px;
            line-height: 1.9;
            color: #5f5f5f;
        }

        .faq-empty {
            padding-top: 28px;
            font-size: 16px;
            line-height: 1.8;
            color: #666;
        }

        body.dark-mode .faq-header,
        body.dark-mode .faq-list,
        body.dark-mode .faq-item {
            border-color: #2c2c2c;
        }

        body.dark-mode .faq-kicker,
        body.dark-mode .faq-intro,
        body.dark-mode .faq-aside__label,
        body.dark-mode .faq-aside__text,
        body.dark-mode .faq-answer,
        body.dark-mode .faq-empty {
            color: #bdbdbd;
        }

        body.dark-mode .faq-title,
        body.dark-mode .faq-question,
        body.dark-mode .faq-aside__link {
            color: #f3f3f3;
        }

        body.dark-mode .faq-aside__link {
            border-bottom-color: #f3f3f3;
        }

        body.dark-mode .faq-icon::before,
        body.dark-mode .faq-icon::after {
            background: #f3f3f3;
        }

        @media (max-width: 900px) {
            .faq-page {
                padding: 112px 20px 72px;
            }

            .faq-header,
            .faq-layout {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .faq-header {
                padding-bottom: 36px;
            }

            .faq-layout {
                padding-top: 32px;
            }

            .faq-aside {
                position: static;
            }

            .faq-aside__text {
                max-width: none;
            }

            .faq-question {
                font-size: 18px;
            }

            .faq-answer {
                font-size: 14px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="faq-page">
        <div class="faq-page__inner">

            <div class="faq-header">
                <div class="faq-kicker">Customer Support</div>

                <div>
                    <h1 class="faq-title">
                        Everything you need to know before placing your order.
                    </h1>

                    <p class="faq-intro">
                        Find quick answers about shipping, sizing, returns,
                        payments, and product details—so your shopping experience
                        feels simple, clear, and effortless.
                    </p>
                </div>
            </div>

            <div class="faq-layout">
                <aside class="faq-aside">
                    <div class="faq-aside__label">Need More Help?</div>

                    <p class="faq-aside__text">
                        Learn more about our philosophy, product design,
                        and what drives Gloaming Imagine beyond the products we create.
                    </p>

                    <a href="{{ route('about') }}" class="faq-aside__link">
                        Explore Our Story
                    </a>
                </aside>

                <div>
                    @if ($faqs->isNotEmpty())
                        <div class="faq-list">
                            @foreach ($faqs as $faq)
                                <details class="faq-item" @if ($loop->first) open @endif>
                                    <summary>
                                        <span class="faq-question">
                                            {{ $faq->question }}
                                        </span>
                                        <span class="faq-icon" aria-hidden="true"></span>
                                    </summary>

                                    <div class="faq-answer">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    @else
                        <div class="faq-empty">
                            We’re currently updating our help center.
                            Please check back soon for more information.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>
@endsection
