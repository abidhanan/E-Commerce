<x-layouts.app>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panduan Perawatan — Clothique</title>
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
  .guide-num{
    font-family:'Playfair Display',serif;
    font-style:italic;
  }
  .guide-card{
    transition:transform .5s cubic-bezier(.16,1,.3,1), opacity .6s ease;
  }
  .underline-grow{
    position:relative;
  }
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
    from{opacity:0; transform:translateY(28px);}
    to{opacity:1; transform:translateY(0);}
  }
  .reveal{
    opacity:0;
    animation:fadeUp .8s cubic-bezier(.16,1,.3,1) forwards;
  }
  .delay-1{animation-delay:.05s;}
  .delay-2{animation-delay:.18s;}
  .delay-3{animation-delay:.31s;}
  .delay-4{animation-delay:.44s;}

  @media (prefers-reduced-motion: reduce){
    .reveal{animation:none; opacity:1;}
  }

  /* Step bullets untuk jawaban multi-baris */
  .answer-line{
    display:flex;
    gap:.75rem;
    align-items:flex-start;
  }
  .answer-line::before{
    content:'—';
    flex-shrink:0;
    color:var(--taupe);
    font-family:'Playfair Display',serif;
    font-style:italic;
  }
</style>
</head>
<body class="antialiased">



  <main>

    <!-- ===== HERO ===== -->
    <section class="max-w-7xl mx-auto px-6 md:px-10 pt-20 pb-16 md:pt-28 md:pb-24">
      <p class="reveal text-xs tracking-wide-xs uppercase mb-6" style="color:var(--taupe);">Panduan Produk</p>
      <h1 class="reveal delay-1 font-display italic text-5xl md:text-7xl leading-[1.05] max-w-3xl" style="color:var(--ink);">
        Cara <span class="not-italic font-semibold">Merawat</span> Produk Anda
      </h1>
      <p class="reveal delay-2 mt-7 max-w-xl text-base md:text-lg leading-relaxed" style="color:var(--taupe);">
        Produk yang dirawat dengan benar akan bertahan jauh lebih lama.
        Ikuti panduan ini agar setiap helai tetap dalam kondisi terbaiknya.
      </p>
    </section>

    <!-- ===== GUIDE LIST ===== -->
    <section class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-20">

      @if($guides->isEmpty())
        {{-- ===== EMPTY STATE ===== --}}
        <div class="border-t py-24 text-center" style="border-color:var(--line);">
          <p class="font-display italic text-3xl md:text-4xl mb-4" style="color:var(--taupe);">Belum ada panduan tersedia.</p>
          <p class="text-sm" style="color:var(--taupe);">Panduan perawatan akan segera kami tambahkan. Kembali lagi nanti.</p>
        </div>

      @else
        <div class="divide-y" style="border-color:var(--line);">

          @foreach($guides as $index => $guide)
          <article
            class="guide-card reveal group py-10 md:py-14 grid md:grid-cols-12 gap-6 md:gap-10 items-start"
            style="animation-delay:{{ $index * 0.13 }}s"
          >
            {{-- Nomor urut --}}
            <div class="md:col-span-2">
              <span
                class="guide-num text-5xl md:text-6xl"
                style="color:{{ $index === 0 ? 'var(--ink)' : 'var(--taupe)' }};"
              >{{ str_pad($guide['position'], 2, '0', STR_PAD_LEFT) }}</span>
            </div>

            {{-- Konten --}}
            <div class="md:col-span-10">
              <h3 class="font-display text-2xl md:text-3xl mb-4" style="color:var(--ink);">
                {{ $guide['question'] }}
              </h3>

              {{-- Jawaban: render tiap baris sebagai item terpisah --}}
              <div class="space-y-2">
                @foreach(explode("\n", trim($guide['answer'])) as $line)
                  @if(trim($line) !== '')
                    <p class="answer-line text-base leading-relaxed" style="color:var(--taupe);">
                      {{ trim($line) }}
                    </p>
                  @endif
                @endforeach
              </div>
            </div>
          </article>
          @endforeach

        </div>
      @endif

    </section>


  </main>

</body>
</html>
</x-layouts.app>