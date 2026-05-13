@extends('Users.Template.index')

@section('title', 'Product Care Guide | Gloaming Imagine')

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
            transition: all 0.2s ease;
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

        @media (max-width: 900px) {
            .faq-page {
                padding: 112px 20px 72px;
            }

            .faq-header,
            .faq-layout {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .faq-aside {
                position: static;
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

            {{-- Header --}}
            <div class="faq-header">
                <div class="faq-kicker">Product Care</div>

                <div>
                    <h1 class="faq-title">
                        Keep your products in their best condition.
                    </h1>

                    <p class="faq-intro">
                        Learn how to properly clean, store, and maintain your products.
                        Following these care instructions will help extend product lifespan
                        and maintain quality over time.
                    </p>
                </div>
            </div>

            {{-- Content --}}
            <div class="faq-layout">

                {{-- Left Sidebar --}}
                <aside class="faq-aside">
                    <div class="faq-aside__label">
                        Care Tips
                    </div>

                    <p class="faq-aside__text">
                        Every product deserves proper care. Follow these instructions
                        to preserve durability, appearance, and long-term performance.
                    </p>

                    <a href="{{ route('home') }}" class="faq-aside__link">
                        Continue Shopping
                    </a>
                </aside>

                {{-- Guide List --}}
                <div>
                    @if ($guides->isNotEmpty())
                        <div class="faq-list">
                            @foreach ($guides as $guide)
                                <details class="faq-item" @if ($loop->first) open @endif>
                                    <summary>
                                        <span class="faq-question">
                                            {{ $guide->question }}
                                        </span>

                                        <span class="faq-icon"></span>
                                    </summary>

                                    <div class="faq-answer">
                                        {!! nl2br(e($guide->answer)) !!}
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    @else
                        <div class="faq-empty">
                            Product care instructions are not available yet.
                            Please check back later.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>
@endsection
