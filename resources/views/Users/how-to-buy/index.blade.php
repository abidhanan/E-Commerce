<x-layouts.app>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cara Belanja — Clothique</title>
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
  html { scroll-behavior: smooth; }
  body { background: var(--cream); color: var(--ink); font-family: 'Inter', sans-serif; }
  .font-display { font-family: 'Playfair Display', serif; }
  .tracking-wide-xs { letter-spacing: .18em; }
  .step-num { font-family: 'Playfair Display', serif; font-style: italic; }
  .step-card { transition: transform .5s cubic-bezier(.16,1,.3,1), opacity .6s ease; }

  .underline-grow { position: relative; }
  .underline-grow::after {
    content: ''; position: absolute; left: 0; bottom: -6px;
    height: 1px; width: 0; background: var(--ink);
    transition: width .4s ease;
  }
  .underline-grow:hover::after { width: 100%; }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(28px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .reveal { opacity: 0; animation: fadeUp .8s cubic-bezier(.16,1,.3,1) forwards; }
  .delay-1 { animation-delay: .05s; }
  .delay-2 { animation-delay: .18s; }
  .delay-3 { animation-delay: .31s; }
  .delay-4 { animation-delay: .44s; }
  .delay-5 { animation-delay: .57s; }
  @media (prefers-reduced-motion: reduce) { .reveal { animation: none; opacity: 1; } }
</style>
</head>
<body class="antialiased">

  <main>

    {{-- ===== HERO ===== --}}
    <section class="max-w-7xl mx-auto px-6 md:px-10 pt-20 pb-16 md:pt-28 md:pb-24">
      <p class="reveal text-xs tracking-wide-xs uppercase mb-6" style="color:var(--taupe);">Panduan Belanja</p>
      <h1 class="reveal delay-1 font-display italic text-5xl md:text-7xl leading-[1.05] max-w-3xl" style="color:var(--ink);">
        Cara <span class="not-italic font-semibold">Berbelanja</span> di Clothique
      </h1>
      <p class="reveal delay-2 mt-7 max-w-xl text-base md:text-lg leading-relaxed" style="color:var(--taupe);">
        {{ $steps->count() }} langkah sederhana dari memilih produk hingga pesanan Anda terkonfirmasi —
        dirancang seringkas perjalanan dari rak ke pintu rumah Anda.
      </p>
    </section>

    {{-- ===== STEPS ===== --}}
    <section class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-20">

      @if($steps->isNotEmpty())
      <div class="divide-y" style="border-color:var(--line);">

        @foreach($steps as $step)
        <article
          class="step-card reveal {{ $loop->index <= 4 ? 'delay-' . ($loop->index + 1) : '' }} group py-10 md:py-14 grid md:grid-cols-12 gap-6 md:gap-10 items-start"
          data-step="{{ $step->step_order }}"
        >
          <div class="md:col-span-2">
            <span
              class="step-num text-5xl md:text-6xl"
              style="color:{{ $loop->first ? 'var(--ink)' : 'var(--taupe)' }};"
            >
              {{ str_pad($step->step_order, 2, '0', STR_PAD_LEFT) }}
            </span>
          </div>
          <div class="md:col-span-10">
            <h3 class="font-display text-2xl md:text-3xl mb-3" style="color:var(--ink);">
              {{ $step->title }}
            </h3>
            <p class="leading-relaxed max-w-xl" style="color:var(--taupe);">
              {{ $step->description }}
            </p>
          </div>
        </article>
        @endforeach

      </div>
      @else
      {{-- Empty state jika belum ada langkah --}}
      <div class="py-24 text-center">
        <p class="font-display italic text-3xl md:text-4xl mb-4" style="color:var(--ink);">Panduan belum tersedia</p>
        <p class="max-w-md mx-auto leading-relaxed" style="color:var(--taupe);">
          Kami sedang menyiapkan panduan belanja. Silakan kembali lagi sebentar.
        </p>
      </div>
      @endif

     

    </section>

  </main>

  {{-- ===== FOOTER ===== --}}
  <footer class="border-t py-10" style="border-color:var(--line);">
    <div class="max-w-7xl mx-auto px-6 md:px-10 flex flex-col md:flex-row justify-between text-xs tracking-wide-xs uppercase" style="color:var(--taupe);">
      <span>© {{ date('Y') }} Clothique</span>
      <span>Build Your Style</span>
    </div>
  </footer>

</body>
</html>
</x-layouts.app>