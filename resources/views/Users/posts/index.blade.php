<x-layouts.app>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Artikel — Clothique</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --cream:#F7F4ED;
    --ink:#171410;
    --taupe:#8C8577;
    --line:#DCD5C5;
    --rust:#7C4A30;
  }
  html{scroll-behavior:smooth;}
  body{background:var(--cream);color:var(--ink);font-family:'Inter',sans-serif;}
  .font-display{font-family:'Playfair Display',serif;}
  .tracking-wide-xs{letter-spacing:.18em;}

  .underline-grow{position:relative;}
  .underline-grow::after{
    content:'';position:absolute;left:0;bottom:-6px;
    height:1px;width:0;background:var(--ink);transition:width .4s ease;
  }
  .underline-grow:hover::after{width:100%;}

  @keyframes fadeUp{
    from{opacity:0;transform:translateY(28px);}
    to{opacity:1;transform:translateY(0);}
  }
  .reveal{opacity:0;animation:fadeUp .8s cubic-bezier(.16,1,.3,1) forwards;}
  .delay-1{animation-delay:.05s;}
  .delay-2{animation-delay:.18s;}
  .delay-3{animation-delay:.31s;}
  @media(prefers-reduced-motion:reduce){.reveal{animation:none;opacity:1;}}

  /* ── Post card ── */
  .post-card{transition:opacity .3s ease;}
  .post-card:hover .post-title{text-decoration:underline;text-underline-offset:4px;}
  .post-card:hover .post-thumb{transform:scale(1.03);}
  .post-thumb{transition:transform .6s cubic-bezier(.16,1,.3,1);}
  .thumb-wrap{overflow:hidden;}

  /* ── Pagination ── */
  .page-link{
    display:inline-flex;align-items:center;justify-content:center;
    width:2.25rem;height:2.25rem;
    font-size:.75rem;letter-spacing:.12em;
    border:1px solid var(--line);
    color:var(--taupe);
    transition:border-color .25s, color .25s, background .25s;
  }
  .page-link:hover{border-color:var(--ink);color:var(--ink);}
  .page-link.active{background:var(--ink);border-color:var(--ink);color:var(--cream);}
  .page-link.disabled{opacity:.35;pointer-events:none;}
</style>
</head>
<body class="antialiased">

 <main>

    <!-- ===== HERO ===== -->
    <section class="max-w-7xl mx-auto px-6 md:px-10 pt-20 pb-16 md:pt-28 md:pb-24">
      <p class="reveal text-xs tracking-wide-xs uppercase mb-6" style="color:var(--taupe);">Jurnal & Panduan</p>
      <h1 class="reveal delay-1 font-display italic text-5xl md:text-7xl leading-[1.05] max-w-3xl" style="color:var(--ink);">
        Cerita dari <span class="not-italic font-semibold">Lapangan</span>
      </h1>
      <p class="reveal delay-2 mt-7 max-w-xl text-base md:text-lg leading-relaxed" style="color:var(--taupe);">
        Panduan gear, tips layering, dan cerita di balik pilihan material kami —
        ditulis untuk orang-orang yang bergerak.
      </p>
    </section>

    <!-- ===== DIVIDER ===== -->
    <div class="max-w-7xl mx-auto px-6 md:px-10">
      <hr style="border-color:var(--line);">
    </div>

    @if($posts->isEmpty())
      {{-- ===== EMPTY STATE ===== --}}
      <section class="max-w-7xl mx-auto px-6 md:px-10 py-32 text-center">
        <p class="font-display italic text-3xl md:text-4xl mb-4" style="color:var(--taupe);">Belum ada artikel tersedia.</p>
        <p class="text-sm" style="color:var(--taupe);">Konten sedang disiapkan. Kembali lagi segera.</p>
      </section>

    @else

      <!-- ===== POST GRID ===== -->
      <section class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-20">

        {{-- Featured post: baris penuh, lebih besar --}}
        @php $featured = $posts->first(); @endphp
        <a href="{{ route('post.show', $featured['slug']) }}"
           class="post-card reveal block group mb-16 md:mb-20"
           style="animation-delay:.05s">
          <div class="grid md:grid-cols-2 gap-8 md:gap-14 items-center">

            {{-- Thumb --}}
            <div class="thumb-wrap aspect-w-16 aspect-h-10 bg-gray-100 order-1" style="background:var(--line);">
              @if($featured['thumbnail'])
                <img src="{{ Storage::url($featured['thumbnail']) }}"
                     alt="{{ $featured['title'] }}"
                     class="post-thumb w-full h-64 md:h-80 object-cover">
              @else
                <div class="w-full h-64 md:h-80 flex items-center justify-center">
                  <span class="font-display italic text-4xl" style="color:var(--taupe);">Q</span>
                </div>
              @endif
            </div>

            {{-- Meta + text --}}
            <div class="order-2">
              @if($featured['category'])
                <p class="text-xs tracking-wide-xs uppercase mb-4" style="color:var(--taupe);">
                  {{ $featured['category']['name'] }}
                </p>
              @endif
              <h2 class="post-title font-display text-3xl md:text-4xl leading-[1.15] mb-4" style="color:var(--ink);">
                {{ $featured['title'] }}
              </h2>
              <p class="text-base leading-relaxed mb-6" style="color:var(--taupe);">
                {{ $featured['excerpt'] }}
              </p>
              {{-- Tags --}}
              @if(!empty($featured['tags']))
                <div class="flex flex-wrap gap-2 mb-6">
                  @foreach($featured['tags'] as $tag)
                    <span class="text-xs tracking-wide-xs uppercase px-3 py-1 border" style="border-color:var(--line);color:var(--taupe);">
                      {{ $tag['name'] }}
                    </span>
                  @endforeach
                </div>
              @endif
              <p class="text-xs tracking-wide-xs uppercase" style="color:var(--taupe);">
                {{ \Carbon\Carbon::parse($featured['published_at'])->translatedFormat('d F Y') }}
              </p>
            </div>
          </div>
        </a>

        {{-- Divider sebelum grid --}}
        @if($posts->count() > 1)
          <hr class="mb-16" style="border-color:var(--line);">

          {{-- Grid post lainnya: 3 kolom --}}
          <div class="grid md:grid-cols-3 gap-10 md:gap-12">
            @foreach($posts->skip(1) as $index => $post)
            <a href="{{ route('post.show', $post['slug']) }}"
               class="post-card reveal block group"
               style="animation-delay:{{ ($index + 1) * 0.13 }}s">

              {{-- Thumb --}}
              <div class="thumb-wrap mb-5" style="background:var(--line);">
                @if($post['thumbnail'])
                  <img src="{{ Storage::url($post['thumbnail']) }}"
                       alt="{{ $post['title'] }}"
                       class="post-thumb w-full h-52 object-cover">
                @else
                  <div class="w-full h-52 flex items-center justify-center">
                    <span class="font-display italic text-3xl" style="color:var(--taupe);">Q</span>
                  </div>
                @endif
              </div>

              {{-- Meta --}}
              @if($post['category'])
                <p class="text-xs tracking-wide-xs uppercase mb-2" style="color:var(--taupe);">
                  {{ $post['category']['name'] }}
                </p>
              @endif
              <h3 class="post-title font-display text-xl md:text-2xl leading-[1.2] mb-3" style="color:var(--ink);">
                {{ $post['title'] }}
              </h3>
              <p class="text-sm leading-relaxed mb-4" style="color:var(--taupe);">
                {{ $post['excerpt'] }}
              </p>

              {{-- Tags --}}
              @if(!empty($post['tags']))
                <div class="flex flex-wrap gap-2 mb-4">
                  @foreach($post['tags'] as $tag)
                    <span class="text-xs tracking-wide-xs uppercase px-2 py-1 border" style="border-color:var(--line);color:var(--taupe);">
                      {{ $tag['name'] }}
                    </span>
                  @endforeach
                </div>
              @endif

              <p class="text-xs tracking-wide-xs uppercase" style="color:var(--taupe);">
                {{ \Carbon\Carbon::parse($post['published_at'])->translatedFormat('d F Y') }}
              </p>
            </a>
            @endforeach
          </div>
        @endif

      </section>

      <!-- ===== PAGINATION ===== -->
      @if($posts->lastPage() > 1)
        <div class="max-w-7xl mx-auto px-6 md:px-10 pb-20">
          <div class="border-t pt-10 flex items-center gap-3" style="border-color:var(--line);">

            {{-- Previous --}}
            @if($posts->previousPageUrl())
              <a href="{{ $posts->previousPageUrl() }}" class="page-link" aria-label="Sebelumnya">&#8592;</a>
            @else
              <span class="page-link disabled" aria-disabled="true">&#8592;</span>
            @endif

            {{-- Page numbers --}}
            @for($p = 1; $p <= $posts->lastPage(); $p++)
              <a href="{{ $posts->url($p) }}"
                 class="page-link {{ $p === $posts->currentPage() ? 'active' : '' }}">
                {{ $p }}
              </a>
            @endfor

            {{-- Next --}}
            @if($posts->nextPageUrl())
              <a href="{{ $posts->nextPageUrl() }}" class="page-link" aria-label="Selanjutnya">&#8594;</a>
            @else
              <span class="page-link disabled" aria-disabled="true">&#8594;</span>
            @endif

            <p class="ml-auto text-xs tracking-wide-xs uppercase" style="color:var(--taupe);">
              {{ $posts->total() }} artikel
            </p>
          </div>
        </div>
      @endif

    @endif

    <!-- ===== SIGNATURE BAND ===== -->
    <section class="border-t" style="border-color:var(--line);background:var(--ink);">
      <div class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-24 text-center">
        <p class="font-display italic text-3xl md:text-5xl leading-[1.3] max-w-3xl mx-auto" style="color:var(--cream);">
          Baca dulu, pilih dengan yakin —
          <span class="not-italic font-semibold">koleksi menunggu.</span>
        </p>
        <a href="#" class="inline-block mt-10 text-xs tracking-wide-xs uppercase px-8 py-4 border"
           style="border-color:var(--cream);color:var(--cream);">
          Lihat Koleksi
        </a>
      </div>
    </section>

  </main>

  <!-- ===== FOOTER MINIMAL ===== -->
  <footer class="border-t py-10" style="border-color:var(--line);">
    <div class="max-w-7xl mx-auto px-6 md:px-10 flex flex-col md:flex-row justify-between text-xs tracking-wide-xs uppercase" style="color:var(--taupe);">
      <span>© 2026 Clothique</span>
      <span>Build Your Style</span>
    </div>
  </footer>

</body>
</html>
</x-layouts.app>