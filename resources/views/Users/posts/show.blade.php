<x-layouts.app>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $blog['title'] }} — Clothique</title>
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
  .delay-4{animation-delay:.44s;}
  @media(prefers-reduced-motion:reduce){.reveal{animation:none;opacity:1;}}

  /* ── Article body prose ── */
  .prose-post p{
    margin-bottom:1.5rem;
    line-height:1.85;
    color:var(--taupe);
    font-size:1.0625rem;
  }
  .prose-post h2{
    font-family:'Playfair Display',serif;
    font-size:1.75rem;
    font-weight:600;
    color:var(--ink);
    margin-top:3rem;
    margin-bottom:1rem;
    line-height:1.2;
  }
  .prose-post h3{
    font-family:'Playfair Display',serif;
    font-size:1.35rem;
    font-weight:500;
    color:var(--ink);
    margin-top:2.25rem;
    margin-bottom:.75rem;
  }
  .prose-post ul{
    margin-bottom:1.5rem;
    padding-left:0;
    list-style:none;
  }
  .prose-post ul li{
    position:relative;
    padding-left:1.5rem;
    margin-bottom:.6rem;
    color:var(--taupe);
    line-height:1.75;
  }
  .prose-post ul li::before{
    content:'—';
    position:absolute;left:0;
    font-family:'Playfair Display',serif;
    font-style:italic;
    color:var(--taupe);
  }
  .prose-post ol{
    margin-bottom:1.5rem;
    counter-reset:ol-counter;
    padding-left:0;list-style:none;
  }
  .prose-post ol li{
    position:relative;
    padding-left:2rem;
    margin-bottom:.6rem;
    color:var(--taupe);
    line-height:1.75;
    counter-increment:ol-counter;
  }
  .prose-post ol li::before{
    content:counter(ol-counter,decimal-leading-zero);
    position:absolute;left:0;
    font-family:'Playfair Display',serif;
    font-style:italic;
    font-size:.85rem;
    color:var(--taupe);
    top:.15rem;
  }
  .prose-post blockquote{
    border-left:1px solid var(--ink);
    margin:2.5rem 0;
    padding:1rem 0 1rem 1.75rem;
    font-family:'Playfair Display',serif;
    font-style:italic;
    font-size:1.35rem;
    line-height:1.5;
    color:var(--ink);
  }
  .prose-post strong{color:var(--ink);font-weight:600;}
  .prose-post a{
    color:var(--ink);
    text-decoration:underline;
    text-underline-offset:3px;
  }
  .prose-post img{
    width:100%;
    margin:2rem 0;
  }

  /* ── Related card hover ── */
  .related-card:hover .related-title{text-decoration:underline;text-underline-offset:4px;}
  .related-card:hover .related-thumb{transform:scale(1.03);}
  .related-thumb{transition:transform .6s cubic-bezier(.16,1,.3,1);}
  .thumb-wrap{overflow:hidden;}
</style>
</head>
<body class="antialiased">


  <main>

    <!-- ===== BACK + META ===== -->
    <section class="max-w-7xl mx-auto px-6 md:px-10 pt-12 pb-0">
      <a href="{{ route('post') }}"
         class="reveal inline-flex items-center gap-2 text-xs tracking-wide-xs uppercase"
         style="color:var(--taupe);">
        <span>&#8592;</span> Semua Artikel
      </a>
    </section>

    <!-- ===== ARTICLE HEADER ===== -->
    <section class="max-w-7xl mx-auto px-6 md:px-10 pt-10 pb-12 md:pb-16">

      {{-- Category + date --}}
      <div class="reveal flex flex-wrap items-center gap-4 mb-6 text-xs tracking-wide-xs uppercase" style="color:var(--taupe);">
        @if($blog['category'])
          <span>{{ $blog['category']['name'] }}</span>
          <span style="color:var(--line);">—</span>
        @endif
        <span>{{ \Carbon\Carbon::parse($blog['published_at'])->translatedFormat('d F Y') }}</span>
      </div>

      {{-- Title --}}
      <h1 class="reveal delay-1 font-display italic text-4xl md:text-6xl leading-[1.08] max-w-4xl mb-6" style="color:var(--ink);">
        {{ $blog['title'] }}
      </h1>

      {{-- Excerpt --}}
      <p class="reveal delay-2 text-base md:text-lg leading-relaxed max-w-2xl mb-8" style="color:var(--taupe);">
        {{ $blog['excerpt'] }}
      </p>

      {{-- Tags --}}
      @if(!empty($blog['tags']))
        <div class="reveal delay-3 flex flex-wrap gap-2">
          @foreach($blog['tags'] as $tag)
            <span class="text-xs tracking-wide-xs uppercase px-3 py-1 border"
                  style="border-color:var(--line);color:var(--taupe);">
              {{ $tag['name'] }}
            </span>
          @endforeach
        </div>
      @endif
    </section>

    <!-- ===== THUMBNAIL ===== -->
    @if($blog['thumbnail'])
      <div class="max-w-7xl mx-auto px-6 md:px-10 mb-16 reveal delay-3">
        <div class="thumb-wrap w-full" style="background:var(--line);">
          <img src="{{ Storage::url($blog['thumbnail']) }}"
               alt="{{ $blog['title'] }}"
               class="w-full object-cover"
               style="max-height:540px;">
        </div>
      </div>
    @endif

    <!-- ===== ARTICLE BODY ===== -->
    <section class="max-w-7xl mx-auto px-6 md:px-10 pb-20">
      <div class="reveal delay-4 grid md:grid-cols-12 gap-10 md:gap-16">

        {{-- Sticky sidebar kiri --}}
        <aside class="hidden md:block md:col-span-3">
          <div class="sticky top-10 space-y-8">

            {{-- Category --}}
            @if($blog['category'])
              <div>
                <p class="text-xs tracking-wide-xs uppercase mb-2" style="color:var(--taupe);">Kategori</p>
                <p class="font-display italic text-lg" style="color:var(--ink);">{{ $blog['category']['name'] }}</p>
              </div>
            @endif

            {{-- Tags --}}
            @if(!empty($blog['tags']))
              <div>
                <p class="text-xs tracking-wide-xs uppercase mb-3" style="color:var(--taupe);">Tag</p>
                <div class="flex flex-col gap-2">
                  @foreach($blog['tags'] as $tag)
                    <span class="text-xs tracking-wide-xs uppercase" style="color:var(--taupe);">— {{ $tag['name'] }}</span>
                  @endforeach
                </div>
              </div>
            @endif

            {{-- Tanggal --}}
            <div>
              <p class="text-xs tracking-wide-xs uppercase mb-2" style="color:var(--taupe);">Diterbitkan</p>
              <p class="text-sm" style="color:var(--ink);">
                {{ \Carbon\Carbon::parse($blog['published_at'])->translatedFormat('d F Y') }}
              </p>
            </div>

          </div>
        </aside>

        {{-- Body artikel --}}
        <div class="md:col-span-9">
          <div class="prose-post">
            {!! $blog['content'] !!}
          </div>
        </div>

      </div>
    </section>

    <!-- ===== RELATED POSTS ===== -->
    @if(!empty($relatedPosts))
      <section class="border-t" style="border-color:var(--line);">
        <div class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-20">

          <p class="text-xs tracking-wide-xs uppercase mb-10" style="color:var(--taupe);">Artikel Terkait</p>

          <div class="grid md:grid-cols-3 gap-10 md:gap-12">
            @foreach($relatedPosts as $index => $related)
              <a href="{{ route('post', $related['slug']) }}"
                 class="related-card reveal block group"
                 style="animation-delay:{{ $index * 0.13 }}s">

                {{-- Thumb --}}
                <div class="thumb-wrap mb-5" style="background:var(--line);">
                  @if($related['thumbnail'])
                    <img src="{{ Storage::url($related['thumbnail']) }}"
                         alt="{{ $related['title'] }}"
                         class="related-thumb w-full h-52 object-cover">
                  @else
                    <div class="w-full h-52 flex items-center justify-center">
                      <span class="font-display italic text-3xl" style="color:var(--taupe);">Q</span>
                    </div>
                  @endif
                </div>

                @if($related['category'])
                  <p class="text-xs tracking-wide-xs uppercase mb-2" style="color:var(--taupe);">
                    {{ $related['category']['name'] }}
                  </p>
                @endif

                <h3 class="related-title font-display text-xl md:text-2xl leading-[1.2] mb-3" style="color:var(--ink);">
                  {{ $related['title'] }}
                </h3>
                <p class="text-sm leading-relaxed mb-4" style="color:var(--taupe);">
                  {{ $related['excerpt'] }}
                </p>

                @if(!empty($related['tags']))
                  <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($related['tags'] as $tag)
                      <span class="text-xs tracking-wide-xs uppercase px-2 py-1 border"
                            style="border-color:var(--line);color:var(--taupe);">
                        {{ $tag['name'] }}
                      </span>
                    @endforeach
                  </div>
                @endif

                <p class="text-xs tracking-wide-xs uppercase" style="color:var(--taupe);">
                  {{ \Carbon\Carbon::parse($related['published_at'])->translatedFormat('d F Y') }}
                </p>
              </a>
            @endforeach
          </div>

        </div>
      </section>
    @endif

    <!-- ===== SIGNATURE BAND ===== -->
    {{-- <section class="border-t" style="border-color:var(--line);background:var(--ink);">
      <div class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-24 text-center">
        <p class="font-display italic text-3xl md:text-5xl leading-[1.3] max-w-3xl mx-auto" style="color:var(--cream);">
          Temukan gear yang tepat —
          <span class="not-italic font-semibold">koleksi menunggu di sini.</span>
        </p>
        <a href="#" class="inline-block mt-10 text-xs tracking-wide-xs uppercase px-8 py-4 border"
           style="border-color:var(--cream);color:var(--cream);">
          Lihat Koleksi
        </a>
      </div>
    </section> --}}

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