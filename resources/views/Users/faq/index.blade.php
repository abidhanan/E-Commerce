<x-layouts.app>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQ — Clothique</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --cream: #F7F4ED;
    --ink:   #171410;
    --taupe: #8C8577;
    --line:  #DCD5C5;
    --rust:  #7C4A30;
  }
  body { background: var(--cream); color: var(--ink); font-family: 'Inter', sans-serif; }
  .font-display { font-family: 'Playfair Display', serif; }
  .tracking-wide-xs { letter-spacing: .18em; }
  .step-num { font-family: 'Playfair Display', serif; font-style: italic; }

  .underline-grow { position: relative; }
  .underline-grow::after {
    content: ''; position: absolute; left: 0; bottom: -6px;
    height: 1px; width: 0; background: var(--ink);
    transition: width .4s ease;
  }
  .underline-grow:hover::after { width: 100%; }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .reveal { opacity: 0; animation: fadeUp .75s cubic-bezier(.16,1,.3,1) forwards; }
  .delay-1 { animation-delay: .05s; }
  .delay-2 { animation-delay: .16s; }
  .delay-3 { animation-delay: .27s; }
  .delay-4 { animation-delay: .38s; }
  .delay-5 { animation-delay: .49s; }
  @media (prefers-reduced-motion: reduce) { .reveal { animation: none; opacity: 1; } }

  /* Accordion */
  .faq-item .faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height .45s cubic-bezier(.16,1,.3,1), opacity .35s ease;
    opacity: 0;
  }
  .faq-item.open .faq-answer { opacity: 1; }
  .faq-item .chevron { transition: transform .4s cubic-bezier(.16,1,.3,1); }
  .faq-item.open .chevron { transform: rotate(45deg); }
  .faq-item .step-num,
  .faq-item .faq-q { transition: color .3s ease; }

  /* Search input */
  .faq-search:focus { outline: none; border-color: var(--ink); }
</style>
</head>
<body class="antialiased">

  <main>

    {{-- ===== HERO ===== --}}
    <section class="max-w-7xl mx-auto px-6 md:px-10 pt-20 pb-12 md:pt-28 md:pb-16">
      <p class="reveal text-xs tracking-wide-xs uppercase mb-6" style="color:var(--taupe);">Bantuan</p>
      <h1 class="reveal delay-1 font-display italic text-5xl md:text-7xl leading-[1.05] max-w-3xl" style="color:var(--ink);">
        Pertanyaan yang <span class="not-italic font-semibold">Sering Diajukan</span>
      </h1>
      <p class="reveal delay-2 mt-7 max-w-xl text-base md:text-lg leading-relaxed" style="color:var(--taupe);">
        Jawaban singkat untuk hal-hal yang paling sering ditanyakan sebelum, selama,
        dan setelah Anda berbelanja di Clothique.
      </p>

      {{-- Search filter --}}
      <div class="reveal delay-3 mt-10 max-w-md">
        <input
          id="faq-search"
          type="text"
          placeholder="Cari pertanyaan…"
          class="faq-search w-full bg-transparent border-b py-3 text-sm focus:border-b-2 transition-all"
          style="border-color:var(--line); color:var(--ink);"
          autocomplete="off"
        >
      </div>
    </section>

    {{-- ===== FAQ LIST ===== --}}
    <section class="max-w-7xl mx-auto px-6 md:px-10 py-10 md:py-16">

      <div id="faq-list" class="divide-y" style="border-color:var(--line);">

        @forelse($faqs as $faq)
        <div
          class="faq-item reveal {{ $loop->index <= 4 ? 'delay-' . ($loop->index + 1) : '' }} border-t"
          style="border-color:var(--line);"
          data-q="{{ strtolower($faq->question) }}"
        >
          <button class="faq-trigger w-full text-left py-8 md:py-10 grid md:grid-cols-12 gap-6 md:gap-10 items-start group">
            <div class="md:col-span-1">
              <span class="step-num text-2xl md:text-3xl" style="color:var(--taupe);">
                {{ str_pad($faq->position, 2, '0', STR_PAD_LEFT) }}
              </span>
            </div>
            <div class="md:col-span-9">
              <h3 class="faq-q font-display text-xl md:text-2xl" style="color:var(--taupe);">
                {{ $faq->question }}
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
                @foreach(explode("\n", $faq->answer) as $paragraph)
                  @if(trim($paragraph))
                    <p>{{ trim($paragraph) }}</p>
                  @endif
                @endforeach
              </div>
            </div>
          </div>
        </div>
        @empty
        {{-- Jika koleksi $faqs kosong dari controller, empty state di bawah sudah menanganinya --}}
        @endforelse

        <div class="border-t" style="border-color:var(--line);"></div>
      </div>

      {{-- ===== EMPTY STATE (hasil pencarian / data kosong) ===== --}}
      <div id="faq-empty" class="hidden py-24 text-center">
        <p class="font-display italic text-3xl md:text-4xl mb-4" style="color:var(--ink);">Tidak ada yang cocok</p>
        <p class="max-w-md mx-auto leading-relaxed" style="color:var(--taupe);">
          Kami belum punya jawaban untuk pencarian itu. Coba kata kunci lain,
          atau hubungi customer care kami langsung.
        </p>
        <a href="#" class="inline-block mt-8 text-xs tracking-wide-xs uppercase px-8 py-4 border" style="border-color:var(--ink); color:var(--ink);">
          Hubungi Kami
        </a>
      </div>

    </section>
  </main>

  {{-- ===== FOOTER ===== --}}
  <footer class="border-t py-10" style="border-color:var(--line);">
    <div class="max-w-7xl mx-auto px-6 md:px-10 flex flex-col md:flex-row justify-between text-xs tracking-wide-xs uppercase" style="color:var(--taupe);">
      <span>© {{ date('Y') }} Clothique</span>
      <span>Build Your Style</span>
    </div>
  </footer>

  <script>
    // Accordion — hanya satu item terbuka pada satu waktu
    document.querySelectorAll('.faq-trigger').forEach(btn => {
      btn.addEventListener('click', () => {
        const item   = btn.closest('.faq-item');
        const answer = item.querySelector('.faq-answer');
        const isOpen = item.classList.contains('open');

        // Tutup semua item lain
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
          item.querySelector('.faq-q').style.color = 'var(--taupe)';
        } else {
          item.classList.add('open');
          answer.style.maxHeight = answer.scrollHeight + 'px';
          item.querySelector('.faq-q').style.color = 'var(--ink)';
        }
      });
    });

    // Search / filter + empty state
    const searchInput = document.getElementById('faq-search');
    const faqList     = document.getElementById('faq-list');
    const emptyState  = document.getElementById('faq-empty');
    const items       = Array.from(document.querySelectorAll('.faq-item'));

    searchInput.addEventListener('input', e => {
      const term = e.target.value.trim().toLowerCase();
      let visibleCount = 0;

      items.forEach(item => {
        const match = item.dataset.q.includes(term);
        item.style.display = match ? '' : 'none';
        if (match) visibleCount++;
      });

      if (visibleCount === 0) {
        faqList.classList.add('hidden');
        emptyState.classList.remove('hidden');
      } else {
        faqList.classList.remove('hidden');
        emptyState.classList.add('hidden');
      }
    });
  </script>

</body>
</html>
</x-layouts.app>