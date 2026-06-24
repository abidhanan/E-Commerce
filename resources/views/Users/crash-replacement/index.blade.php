<x-layouts.app>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crash Replacement — Clothique</title>
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
  body{background:var(--cream); color:var(--ink); font-family:'Inter',sans-serif;}
  .font-display{font-family:'Playfair Display',serif;}
  .tracking-wide-xs{letter-spacing:.18em;}
  .step-num{font-family:'Playfair Display',serif; font-style:italic;}

  .underline-grow{position:relative;}
  .underline-grow::after{
    content:''; position:absolute; left:0; bottom:-6px;
    height:1px; width:0; background:var(--ink);
    transition:width .4s ease;
  }
  .underline-grow:hover::after{width:100%;}

  @keyframes fadeUp{
    from{opacity:0; transform:translateY(24px);}
    to{opacity:1; transform:translateY(0);}
  }
  .reveal{opacity:0; animation:fadeUp .75s cubic-bezier(.16,1,.3,1) forwards;}
  .delay-1{animation-delay:.05s;} .delay-2{animation-delay:.16s;}
  .delay-3{animation-delay:.27s;} .delay-4{animation-delay:.38s;}
  @media (prefers-reduced-motion: reduce){.reveal{animation:none; opacity:1;}}

  .faq-item .faq-answer{
    max-height:0; overflow:hidden;
    transition:max-height .45s cubic-bezier(.16,1,.3,1), opacity .35s ease;
    opacity:0;
  }
  .faq-item.open .faq-answer{opacity:1;}
  .faq-item .chevron{transition:transform .4s cubic-bezier(.16,1,.3,1);}
  .faq-item.open .chevron{transform:rotate(45deg);}
  .faq-item.open .faq-q{color:var(--ink);}
  .faq-item .faq-q{transition:color .3s ease;}
</style>
</head>
<body class="antialiased">

  <main>

    <!-- ===== HERO ===== -->
    <section class="max-w-7xl mx-auto px-6 md:px-10 pt-20 pb-12 md:pt-28 md:pb-16">
      <p class="reveal text-xs tracking-wide-xs uppercase mb-6" style="color:var(--taupe);">Layanan Purna Beli</p>
      <h1 class="reveal delay-1 font-display italic text-5xl md:text-7xl leading-[1.05] max-w-3xl" style="color:var(--ink);">
        Program <span class="not-italic font-semibold">Crash Replacement</span>
      </h1>
      <p class="reveal delay-2 mt-7 max-w-xl text-base md:text-lg leading-relaxed" style="color:var(--taupe);">
        Insiden saat pemakaian tidak seharusnya mengakhiri cerita produk Anda.
        Program ini menjamin penggantian untuk kerusakan yang masuk dalam kebijakan layanan kami.
      </p>
    </section>

    <!-- ===== PROSES & SYARAT ===== -->
    <section class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-20 border-t" style="border-color:var(--line);">
      @php
        $activeItems = collect($crashReplacements)
          ->filter(fn($item) => $item['is_active'] == 1)
          ->sortBy('position')
          ->values();
        $isEmpty = $activeItems->isEmpty();
      @endphp

      @if (!$isEmpty)
        <div id="claim-list" class="divide-y" style="border-color:var(--line);">

          @foreach ($activeItems as $i => $item)
            @php
              $padNum = str_pad($item['position'], 2, '0', STR_PAD_LEFT);
              $delay  = 'delay-' . min($i + 1, 4);
              $paragraphs = explode("\n", $item['answer']);
            @endphp

            <div class="faq-item reveal {{ $delay }} border-t" style="border-color:var(--line);">
              <button class="faq-trigger w-full text-left py-8 md:py-10 grid md:grid-cols-12 gap-6 md:gap-10 items-start">
                <div class="md:col-span-1">
                  <span class="step-num text-2xl md:text-3xl" style="color:var(--taupe);">{{ $padNum }}</span>
                </div>
                <div class="md:col-span-9">
                  <h3 class="faq-q font-display text-xl md:text-2xl" style="color:var(--taupe);">
                    {{ $item['question'] }}
                  </h3>
                </div>
                <div class="md:col-span-2 flex justify-start md:justify-end">
                  <span class="chevron text-2xl leading-none" style="color:var(--ink);">+</span>
                </div>
              </button>
              <div class="faq-answer">
                <div class="md:grid md:grid-cols-12 gap-10 pb-8 md:pb-10">
                  <div class="hidden md:block md:col-span-1"></div>
                  <div class="md:col-span-9 text-sm md:text-base leading-relaxed space-y-3" style="color:var(--taupe);">
                    @foreach ($paragraphs as $paragraph)
                      @if (trim($paragraph) !== '')
                        <p>{{ trim($paragraph) }}</p>
                      @endif
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          @endforeach

          <div class="border-t" style="border-color:var(--line);"></div>
        </div>

      @else

        <!-- ===== EMPTY STATE ===== -->
        <div id="claim-empty" class="py-24 text-center">
          <p class="font-display italic text-3xl md:text-4xl mb-4" style="color:var(--ink);">Program belum tersedia</p>
          <p class="max-w-md mx-auto leading-relaxed" style="color:var(--taupe);">
            Informasi crash replacement untuk kategori ini sedang disiapkan.
            Hubungi tim kami bila Anda memiliki kasus yang ingin didiskusikan lebih dulu.
          </p>
          <a href="#" class="inline-block mt-8 text-xs tracking-wide-xs uppercase px-8 py-4 border" style="border-color:var(--ink); color:var(--ink);">
            Hubungi Kami
          </a>
        </div>

      @endif
    </section>

  </main>

  <script>
    document.querySelectorAll('.faq-trigger').forEach(btn => {
      btn.addEventListener('click', () => {
        const item   = btn.closest('.faq-item');
        const answer = item.querySelector('.faq-answer');
        const isOpen = item.classList.contains('open');

        document.querySelectorAll('.faq-item.open').forEach(open => {
          if (open !== item) {
            open.classList.remove('open');
            open.querySelector('.faq-answer').style.maxHeight = null;
            open.querySelector('.faq-q').style.color = 'var(--taupe)';
          }
        });

        if (isOpen) {
          item.classList.remove('open');
          answer.style.maxHeight = null;
        } else {
          item.classList.add('open');
          answer.style.maxHeight = answer.scrollHeight + 'px';
        }
      });
    });
  </script>

</body>
</html>
</x-layouts.app>