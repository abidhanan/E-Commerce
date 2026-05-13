@php
    $footerSocialLinks = $footerSocialLinks ?? collect();
    $footerMarketplaceLinks = $footerMarketplaceLinks ?? collect();
@endphp

<section class="newsletter-section">
    <div class="newsletter-container">
        <div class="newsletter-left">
            <div class="newsletter-label">Gloaming Imagine</div>
            <h2 class="newsletter-title">Be the first to know about<br>upcoming drops, events and deals.</h2>
        </div>
        <div class="newsletter-right">
            @if (Auth::check())
                <div class="newsletter-logged-in">

                    <a href="{{ route('home') }}" class="newsletter-btn text-decoration-none">Continue Shopping</a>
                </div>
            @else
            <div class="newsletter-logged-in">

                <a href="{{ route('login') }}" class="newsletter-btn text-decoration-none">Sign Up</a>
            </div>
            @endif

        </div>
    </div>
</section>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-top">
            @if ($footerMarketplaceLinks->isNotEmpty())
                <div class="footer-column">
                    <h3>Official Stores</h3>
                    <ul class="footer-links">
                        @foreach ($footerMarketplaceLinks as $link)
                            <li>
                                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">
                                    {{ $link->display_label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="footer-column">
                <h3>Customer Care</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('how-to-buy') }}">How to Buy</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li><a href="{{ route('return-policy') }}">Returns</a></li>
                    <li><a href="{{ route('crash-replacement') }}">Crash Replacement</a></li>
                    <li><a href="{{ route('care-guide') }}">Care Guide</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h3>About Gloaming Imagine</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('post') }}">Blogs</a></li>
                </ul>
            </div>


        </div>

        <div class="footer-bottom">
            {{-- <div class="footer-legal">
                <a href="#">TERMS & CONDITIONS</a>
                <a href="#">PRIVACY POLICY</a>
                <a href="#">COOKIE POLICY</a>
                <a href="#">COOKIE POLICY SETTING</a>
            </div> --}}

            <div class="footer-social">
                @foreach ($footerSocialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">
                        {{ \Illuminate\Support\Str::upper($link->display_label) }}
                    </a>
                @endforeach
            </div>

            <div class="footer-copyright">
                © GLOAMING IMAGINE 2026
            </div>
        </div>
    </div>
</footer>
