<x-layouts.app>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $about ? $about['title'] : 'About Us' }} — Clothique</title>
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
  body{
    background:var(--cream);
    color:var(--ink);
    font-family:'Inter',sans-serif;
  }
  .font-display{font-family:'Playfair Display',serif;}
  .tracking-wide-xs{letter-spacing:.18em;}
  .underline-grow{position:relative;}
  .underline-grow::after{
    content:'';
    position:absolute;
    left:0; bottom:-6px;
    height:1px; width:0;
    background:var(--ink);
    transition:width .4s ease;
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

  /* ── Rich-text content dari CMS ── */
  .prose-about p{
    margin-bottom:1.4rem;
    line-height:1.75;
    color:var(--taupe);
    font-size:1rem;
  }
  .prose-about h2{
    font-family:'Playfair Display',serif;
    font-size:1.75rem;
    font-weight:600;
    color:var(--ink);
    margin-top:3rem;
    margin-bottom:1rem;
    line-height:1.2;
  }
  .prose-about ul{
    margin-bottom:1.4rem;
    padding-left:0;
    list-style:none;
  }
  .prose-about ul li{
    position:relative;
    padding-left:1.5rem;
    margin-bottom:.6rem;
    color:var(--taupe);
    line-height:1.7;
  }
  .prose-about ul li::before{
    content:'—';
    position:absolute;
    left:0;
    font-family:'Playfair Display',serif;
    font-style:italic;
    color:var(--taupe);
  }
  .prose-about blockquote{
    border-left:1px solid var(--ink);
    margin:2.5rem 0;
    padding:1rem 0 1rem 1.75rem;
    font-family:'Playfair Display',serif;
    font-style:italic;
    font-size:1.35rem;
    line-height:1.5;
    color:var(--ink);
  }
</style>
</head>
<body class="antialiased">

  <!-- ===== NAVBAR ===== -->
  <header class="border-b" style="border-color:var(--line);">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 md:px-10 py-6">
      <nav class="hidden md:flex gap-8 text-xs tracking-wide-xs uppercase" style="color:var(--ink);">
        <a href="#" class="underline-grow">Shop</a>
        <a href="#" class="underline-grow">Search</a>
      </nav>
      <a href="#" class="font-display text-2xl md:text-3xl tracking-wide" style="color:var(--ink);">Clothique</a>
      <nav class="flex items-center gap-6 text-xs tracking-wide-xs uppercase" style="color:var(--ink);">
        <a href="#" class="hidden md:inline underline-grow">My Account</a>
        <a href="#" aria-label="Wishlist">&#9825;</a>
        <a href="#" aria-label="Cart">&#128722;</a>
      </nav>
    </div>
  </header>

  <main>

    @if(!$about)
      {{-- ===== EMPTY STATE ===== --}}
      <section class="max-w-7xl mx-auto px-6 md:px-10 py-40 text-center">
        <p class="font-display italic text-3xl md:text-4xl mb-4" style="color:var(--taupe);">Belum ada informasi tentang kami.</p>
        <p class="text-sm" style="color:var(--taupe);">Konten halaman ini sedang disiapkan. Kembali lagi nanti.</p>
      </section>

    @else

      <!-- ===== HERO ===== -->
      <section class="max-w-7xl mx-auto px-6 md:px-10 pt-20 pb-16 md:pt-28 md:pb-24">
        <p class="reveal text-xs tracking-wide-xs uppercase mb-6" style="color:var(--taupe);">Tentang Kami</p>

        {{-- Pisah judul menjadi dua bagian di tanda spasi pertama untuk italic/normal split --}}
        @php
          $titleWords = explode(' ', $about['title'], 2);
          $titleFirst = $titleWords[0] ?? $about['title'];
          $titleRest  = $titleWords[1] ?? '';
        @endphp

        <h1 class="reveal delay-1 font-display italic text-5xl md:text-7xl leading-[1.05] max-w-4xl" style="color:var(--ink);">
          {{ $titleFirst }}
          @if($titleRest)
            <span class="not-italic font-semibold">{{ $titleRest }}</span>
          @endif
        </h1>
      </section>

      <!-- ===== DIVIDER ===== -->
      <div class="max-w-7xl mx-auto px-6 md:px-10">
        <hr style="border-color:var(--line);">
      </div>

      <!-- ===== CONTENT ===== -->
      <section class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-24">
        <div class="reveal delay-2 grid md:grid-cols-12 gap-10 md:gap-16">

          {{-- Label kolom kiri --}}
          <div class="md:col-span-3">
            <p class="text-xs tracking-wide-xs uppercase sticky top-10" style="color:var(--taupe);">
              Brand Story
            </p>
          </div>

          {{-- Rich-text body --}}
          <div class="md:col-span-9">
            <div class="prose-about">
              {!! $about['content'] !!}
            </div>
          </div>

        </div>
      </section>

      <!-- ===== SIGNATURE BAND ===== -->
      <section class="border-t" style="border-color:var(--line); background:var(--ink);">
        <div class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-24 text-center">
          <p class="font-display italic text-3xl md:text-5xl leading-[1.3] max-w-3xl mx-auto" style="color:var(--cream);">
            Pakaian yang tepat tidak menuntut perhatian —
            <span class="not-italic font-semibold">ia hanya bekerja.</span>
          </p>
          <a href="#" class="inline-block mt-10 text-xs tracking-wide-xs uppercase px-8 py-4 border" style="border-color:var(--cream);color:var(--cream);">
            Lihat Koleksi
          </a>
        </div>
      </section>

    @endif

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