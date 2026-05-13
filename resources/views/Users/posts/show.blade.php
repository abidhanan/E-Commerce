@extends('Users.Template.index')

@section('title', $blog->title)

@php
    $sanitizedContent = \App\Support\HtmlSanitizer::clean(
        (string) ($blog->content ?? ''),
        ['p', 'br', 'strong', 'b', 'em', 'i', 'ul', 'ol', 'li', 'blockquote', 'div', 'h1', 'h2', 'h3', 'h4', 'a', 'img', 'figure', 'figcaption', 'pre', 'code', 'hr'],
        true,
    );
@endphp

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">

<style>
/* ─── RESET (scoped) ─── */
.gi-blog * { box-sizing: border-box; }

/* ─── CSS VARIABLES ─── */
.gi-blog {
  --bg:      #ffffff;
  --surface: #ffffff;
  --border:  #e0e0e0;
  --text:    #111111;
  --muted:   #888888;
  --accent:  #111111;
}

body.dark-mode .gi-blog {
  --bg:      #111111;
  --surface: #1a1a1a;
  --border:  #2a2a2a;
  --text:    #f0f0f0;
  --muted:   #666666;
  --accent:  #f0f0f0;
}

/* ══════════════════════════════
   COVER HERO
══════════════════════════════ */
.gi-cover {
  position: relative;
  width: 100%;
  height: 92vh;
  min-height: 500px;
  overflow: hidden;
  background: #0f0f0f;
}

.gi-cover-img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  transform-origin: center;
  animation: gi-coverReveal 1.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes gi-coverReveal {
  from { transform: scale(1.08); opacity: 0; }
  to   { transform: scale(1);    opacity: 1; }
}

/* No thumbnail fallback */
.gi-cover-no-img {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
}

.gi-cover-gradient {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    rgba(0,0,0,0) 30%,
    rgba(0,0,0,0.25) 60%,
    rgba(0,0,0,0.72) 100%
  );
}

/* Category badge top-left */
.gi-cover-badge {
  position: absolute;
  top: 40px;
  left: 48px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.5);
  font-family: 'Libre Franklin', sans-serif;
  opacity: 0;
  animation: gi-fadeUp 0.7s ease 0.6s forwards;
}
.gi-cover-badge::before {
  content: '';
  width: 28px;
  height: 1px;
  background: rgba(255,255,255,0.4);
}
.gi-cover-badge a {
  color: inherit;
  text-decoration: none;
}
.gi-cover-badge a:hover { color: rgba(255,255,255,0.9); }

/* Bottom content */
.gi-cover-content {
  position: absolute;
  bottom: 0;
  left: 0; right: 0;
  padding: 0 48px 52px;
  max-width: 900px;
}
.gi-cover-title {
  font-family: 'Libre Franklin', sans-serif;
  font-size: clamp(36px, 5.5vw, 76px);
  font-weight: 700;
  line-height: 1.0;
  letter-spacing: -0.04em;
  color: #f5f0e8;
  margin-bottom: 20px;
  opacity: 0;
  animation: gi-fadeUp 0.85s cubic-bezier(0.16, 1, 0.3, 1) 0.5s forwards;
}
.gi-cover-meta {
  display: flex;
  align-items: center;
  gap: 20px;
  opacity: 0;
  animation: gi-fadeUp 0.7s ease 0.75s forwards;
}
.gi-cover-category {
  font-family: 'Libre Franklin', sans-serif;
  font-size: 12px;
  font-weight: 500;
  color: rgba(255,255,255,0.6);
  letter-spacing: 0.08em;
  text-transform: uppercase;
}
.gi-cover-date {
  font-family: 'Libre Franklin', sans-serif;
  font-size: 12px;
  color: rgba(255,255,255,0.4);
}
.gi-cover-sep {
  width: 3px; height: 3px;
  background: rgba(255,255,255,0.3);
  border-radius: 50%;
}

@keyframes gi-fadeUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* Scroll cue */
.gi-scroll-cue {
  position: absolute;
  bottom: 52px;
  right: 48px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  opacity: 0;
  animation: gi-fadeUp 0.7s ease 1s forwards;
}
.gi-scroll-cue span {
  font-family: 'Libre Franklin', sans-serif;
  font-size: 8px;
  font-weight: 700;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.22);
}
.gi-scroll-line {
  width: 1px; height: 36px;
  background: linear-gradient(to bottom, rgba(255,255,255,0.25), transparent);
  animation: gi-scrollDrop 1.8s ease-in-out infinite;
}
@keyframes gi-scrollDrop {
  0%   { transform: scaleY(0); transform-origin: top; }
  50%  { transform: scaleY(1); transform-origin: top; }
  51%  { transform: scaleY(1); transform-origin: bottom; }
  100% { transform: scaleY(0); transform-origin: bottom; }
}

/* ══════════════════════════════
   ARTICLE LAYOUT
══════════════════════════════ */
.gi-blog {
  background: var(--bg);
  font-family: 'Libre Franklin', -apple-system, sans-serif;
  -webkit-font-smoothing: antialiased;
}

.gi-article-wrapper {
  max-width: 1200px;
  margin: 0 auto;
  padding: 72px 40px 120px;
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 80px;
  align-items: start;
}

/* ── BREADCRUMB ── */
.gi-breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 48px;
  font-size: 11px;
  color: var(--muted);
  letter-spacing: 0.05em;
}
.gi-breadcrumb a {
  color: var(--muted);
  text-decoration: none;
  transition: color 0.2s;
}
.gi-breadcrumb a:hover { color: var(--text); }
.gi-breadcrumb-sep { font-size: 10px; }

/* ── LEAD ── */
.gi-article-lead {
  font-size: clamp(17px, 2.2vw, 22px);
  font-weight: 300;
  line-height: 1.65;
  letter-spacing: -0.02em;
  color: var(--text);
  margin-bottom: 48px;
  padding-bottom: 48px;
  border-bottom: 1px solid var(--border);
}

/* ── PROSE ── */
.gi-prose h1,
.gi-prose h2,
.gi-prose h3,
.gi-prose h4 {
  font-weight: 700;
  letter-spacing: -0.04em;
  line-height: 1.15;
  color: var(--text);
  margin: 52px 0 20px;
}
.gi-prose h1 { font-size: 34px; }
.gi-prose h2 { font-size: 26px; }
.gi-prose h3 { font-size: 20px; font-weight: 600; letter-spacing: -0.02em; margin: 36px 0 14px; }
.gi-prose h4 { font-size: 17px; font-weight: 600; }

.gi-prose p,
.gi-prose div,
.gi-prose ul,
.gi-prose ol {
  font-size: 16px;
  font-weight: 300;
  line-height: 1.85;
  color: var(--text);
  opacity: 0.9;
  margin-bottom: -20px;
  letter-spacing: -0.01em;
  text-align: justify;
}
.gi-prose ul,
.gi-prose ol { padding-left: 22px; }
.gi-prose li + li { margin-top: 10px; }

.gi-prose strong { font-weight: 600; opacity: 1; color: var(--text); }
.gi-prose em { font-style: italic; font-weight: 300; }

.gi-prose a {
  color: var(--text);
  text-decoration-thickness: 1px;
  text-underline-offset: 3px;
  word-break: break-word;
}

/* Blockquote */
.gi-prose blockquote {
  margin: 52px 0;
  padding: 36px 40px;
  border-left: 3px solid var(--text);
  background: var(--surface);
  position: relative;
}
body.dark-mode .gi-prose blockquote { background: #222; }
.gi-prose blockquote p,
.gi-prose blockquote div {
  font-size: 20px;
  font-weight: 600;
  line-height: 1.4;
  letter-spacing: -0.03em;
  color: var(--text);
  opacity: 1;
  margin: 0;
  font-style: italic;
}

/* Figure / Image */
.gi-prose figure {
  margin: 44px 0;
  display: grid;
  gap: 12px;
}
.gi-prose figure img,
.gi-prose img {
  display: block;
  width: 100%;
  max-width: 100%;
  height: auto;
  border-radius: 4px;
}
.gi-prose figcaption {
  font-size: 11px;
  color: var(--muted);
  letter-spacing: 0.03em;
  text-align: left;
}

/* Code */
.gi-prose pre {
  overflow-x: auto;
  padding: 18px 20px;
  border-radius: 4px;
  background: #111;
  color: #f5f5f5;
  font-size: 13px;
  line-height: 1.8;
  margin-bottom: 24px;
}
.gi-prose code {
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Courier New", monospace;
  font-size: 0.92em;
}
.gi-prose :not(pre) > code {
  padding: 2px 7px;
  border-radius: 3px;
  background: #f1f1ed;
  color: #111;
}
body.dark-mode .gi-prose :not(pre) > code {
  background: #2a2a2a;
  color: #f5f5f5;
}

.gi-prose hr {
  border: 0;
  border-top: 1px solid var(--border);
  margin-bottom: 24px;
}

/* ══════════════════════════════
   SIDEBAR
══════════════════════════════ */
.gi-sidebar {
  position: sticky;
  top: 100px;
}

.gi-sidebar-section {
  padding-bottom: 28px;
  margin-bottom: 28px;
  border-bottom: 1px solid var(--border);
}
.gi-sidebar-section:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}
.gi-sidebar-label {
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 14px;
}

/* Category pill */
.gi-category-pill {
  display: inline-flex;
  align-items: center;
  padding: 8px 16px;
  background: var(--text);
  color: var(--surface);
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  text-decoration: none;
  transition: opacity 0.2s;
}
.gi-category-pill:hover { opacity: 0.7; }
body.dark-mode .gi-category-pill { background: #f0f0f0; color: #111; }

/* Tags */
.gi-tags-list {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}
.gi-tag {
  display: inline-block;
  padding: 6px 12px;
  border: 1px solid var(--border);
  font-size: 11px;
  font-weight: 500;
  letter-spacing: 0.05em;
  color: var(--muted);
  transition: border-color 0.2s, color 0.2s;
}

/* Meta */
.gi-meta-row {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.gi-meta-item { display: flex; flex-direction: column; gap: 3px; }
.gi-meta-key {
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: var(--muted);
}
.gi-meta-val {
  font-size: 13px;
  font-weight: 400;
  color: var(--text);
}

/* Share */
.gi-share-btns { display: flex; gap: 10px; }
.gi-share-btn {
  flex: 1;
  padding: 10px 0;
  border: 1px solid var(--border);
  background: transparent;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--text);
  cursor: pointer;
  font-family: 'Libre Franklin', sans-serif;
  transition: background 0.2s, color 0.2s, border-color 0.2s;
}
.gi-share-btn:hover {
  background: var(--text);
  color: var(--surface);
  border-color: var(--text);
}

/* ══════════════════════════════
   RELATED POSTS
══════════════════════════════ */
.gi-related-section {
  background: var(--surface);
  padding: 80px 40px;
  border-top: 1px solid var(--border);
}
body.dark-mode .gi-related-section { background: #1a1a1a; }

.gi-related-header {
  max-width: 1200px;
  margin: 0 auto 48px;
  display: flex;
  justify-content: space-between;
  align-items: baseline;
}
.gi-related-heading {
  font-family: 'Libre Franklin', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--muted);
}

.gi-related-cards {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}

.gi-rel-card {
  cursor: pointer;
  text-decoration: none;
  display: block;
}
.gi-rel-card-img-wrap {
  width: 100%;
  height: 240px;
  overflow: hidden;
  margin-bottom: 20px;
  background: #eee;
}
body.dark-mode .gi-rel-card-img-wrap { background: #2a2a2a; }
.gi-rel-card-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.5s ease;
}
.gi-rel-card:hover .gi-rel-card-img { transform: scale(1.03); }
.gi-rel-card-tag {
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 8px;
}
.gi-rel-card-title {
  font-family: 'Libre Franklin', sans-serif;
  font-size: 17px;
  font-weight: 700;
  letter-spacing: -0.03em;
  line-height: 1.25;
  color: var(--text);
  transition: opacity 0.2s;
}
.gi-rel-card:hover .gi-rel-card-title { opacity: 0.5; }

/* ── BACK LINK ── */
.gi-back-link {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-top: 56px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  text-decoration: none;
  color: var(--muted);
  border-bottom: 1px solid transparent;
  transition: color 0.2s, border-color 0.2s;
}
.gi-back-link:hover {
  color: var(--text);
  border-bottom-color: var(--text);
}

/* ══════════════════════════════
   REVEAL
══════════════════════════════ */
.gi-reveal {
  opacity: 0;
  transform: translateY(18px);
  transition: opacity 0.65s ease, transform 0.65s ease;
}
.gi-reveal.gi-in { opacity: 1; transform: none; }

/* ══════════════════════════════
   RESPONSIVE
══════════════════════════════ */
@media (max-width: 1024px) {
  .gi-article-wrapper {
    grid-template-columns: 1fr;
    gap: 60px;
  }
  .gi-sidebar { position: static; }
  .gi-related-cards { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 768px) {
  .gi-cover-content { padding: 0 24px 40px; }
  .gi-cover-badge { left: 24px; }
  .gi-scroll-cue { right: 24px; }
  .gi-article-wrapper { padding: 48px 20px 80px; }
  .gi-related-section { padding: 60px 20px; }
  .gi-related-cards { grid-template-columns: 1fr; }
  .gi-prose blockquote { padding: 24px 22px; }
  .gi-prose blockquote p,
  .gi-prose blockquote div { font-size: 18px; }
  .gi-cover {
    height: auto;
    aspect-ratio: 3 / 4;
  }
}
</style>
@endpush

@section('content')
<div class="gi-blog">

    {{-- ══════════════════════════════
         COVER HERO
    ══════════════════════════════ --}}
    <section class="gi-cover">

        @if ($blog->thumbnail)
            <img class="gi-cover-img"
                 src="{{ asset('storage/' . $blog->thumbnail) }}"
                 alt="{{ $blog->title }}">
        @else
            <div class="gi-cover-no-img"></div>
        @endif

        <div class="gi-cover-gradient"></div>

        <div class="gi-cover-badge">
            <a href="{{ route('post') }}">Story</a>
            <span>›</span>
            <span>{{ $blog->category->name }}</span>
        </div>

        <div class="gi-cover-content">
            <h1 class="gi-cover-title">{{ $blog->title }}</h1>
            <div class="gi-cover-meta">
                <span class="gi-cover-category">{{ $blog->category->name }}</span>
                <span class="gi-cover-sep"></span>
                <span class="gi-cover-date">
                    {{ $blog->published_at ? $blog->published_at->format('d M Y') : 'Draft' }}
                </span>
            </div>
        </div>

        <div class="gi-scroll-cue">
            <div class="gi-scroll-line"></div>
            <span>Scroll</span>
        </div>

    </section>

    {{-- ══════════════════════════════
         ARTICLE BODY + SIDEBAR
    ══════════════════════════════ --}}
    <div class="gi-article-wrapper">

        {{-- ── MAIN BODY ── --}}
        <main>

            <nav class="gi-breadcrumb gi-reveal">
                <a href="{{ route('home') }}">Home</a>
                <span class="gi-breadcrumb-sep">›</span>
                <a href="{{ route('post') }}">Story</a>
                <span class="gi-breadcrumb-sep">›</span>
                <span>{{ $blog->title }}</span>
            </nav>

            @if ($blog->excerpt)
                <p class="gi-article-lead gi-reveal">{{ $blog->excerpt }}</p>
            @endif

            <div class="gi-prose gi-reveal">
                {!! $sanitizedContent !!}
            </div>

            <a href="{{ route('post') }}" class="gi-back-link gi-reveal">
                ← Back to Story
            </a>

        </main>

        {{-- ── SIDEBAR ── --}}
        <aside class="gi-sidebar">

            {{-- Category --}}
            <div class="gi-sidebar-section">
                <div class="gi-sidebar-label">Category</div>
                <span class="gi-category-pill">{{ $blog->category->name }}</span>
            </div>

            {{-- Tags --}}
            @if ($blog->tags->count())
                <div class="gi-sidebar-section">
                    <div class="gi-sidebar-label">Tags</div>
                    <div class="gi-tags-list">
                        @foreach ($blog->tags as $tag)
                            <span class="gi-tag">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Meta --}}
            <div class="gi-sidebar-section">
                <div class="gi-sidebar-label">About this piece</div>
                <div class="gi-meta-row">
                    <div class="gi-meta-item">
                        <span class="gi-meta-key">Published</span>
                        <span class="gi-meta-val">
                            {{ $blog->published_at ? $blog->published_at->format('d M Y') : 'Draft Article' }}
                        </span>
                    </div>
                    <div class="gi-meta-item">
                        <span class="gi-meta-key">Category</span>
                        <span class="gi-meta-val">{{ $blog->category->name }}</span>
                    </div>
                </div>
            </div>


        </aside>

    </div>

    {{-- ══════════════════════════════
         RELATED POSTS
    ══════════════════════════════ --}}
    @if ($relatedPosts->count())
        <section class="gi-related-section">

            <div class="gi-related-header">
                <span class="gi-related-heading">Continue Reading</span>
            </div>

            <div class="gi-related-cards">
                @foreach ($relatedPosts as $post)
                    <a href="{{ route('post.show', $post->slug) }}" class="gi-rel-card">

                        <div class="gi-rel-card-img-wrap">
                            @if ($post->thumbnail)
                                <img class="gi-rel-card-img"
                                     src="{{ asset('storage/' . $post->thumbnail) }}"
                                     alt="{{ $post->title }}">
                            @endif
                        </div>

                        <div class="gi-rel-card-tag">{{ $post->category->name }}</div>
                        <div class="gi-rel-card-title">{{ $post->title }}</div>

                    </a>
                @endforeach
            </div>

        </section>
    @endif

</div>
@endsection

@push('scripts')
<script>
(function () {
    // Reveal on scroll
    const revealEls = document.querySelectorAll('.gi-reveal');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('gi-in');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
        revealEls.forEach(el => io.observe(el));
    } else {
        revealEls.forEach(el => el.classList.add('gi-in'));
    }
})();
</script>
@endpush