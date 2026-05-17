<div class="auth-visual">
    @forelse ($displayLogins as $index => $displayLogin)
        <div class="auth-slide {{ $index === 0 ? 'active' : '' }}">
            <img src="{{ asset('storage/' . $displayLogin->image_path) }}" alt="{{ $displayLogin->label }}">
        </div>
    @empty
        <div class="auth-slide active">
            <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?w=1200&h=1600&fit=crop"
                alt="Premium account access">
        </div>
    @endforelse


    @if ($displayLogins->count() > 1)
        <button class="auth-arrow prev" onclick="changeSlide(-1)" type="button" aria-label="Previous slide">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>

        <button class="auth-arrow next" onclick="changeSlide(1)" type="button" aria-label="Next slide">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>

        <div class="auth-indicators">
            @foreach ($displayLogins as $index => $displayLogin)
                <button class="auth-indicator {{ $index === 0 ? 'active' : '' }}"
                    onclick="goToSlide({{ $index }})" type="button"
                    aria-label="Go to slide {{ $index + 1 }}">
                </button>
            @endforeach
        </div>
    @endif
</div>
