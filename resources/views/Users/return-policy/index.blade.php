<x-layouts.app>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kebijakan Pengembalian — Clothique</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --cream: #F7F4ED;
    --ink: #171410;
    --taupe: #8C8577;
    --line: #DCD5C5;
    --rust: #7C4A30;
    --rust-light: #F2EBE6;
  }
  *, *::before, *::after { box-sizing: border-box; }
  body { background: var(--cream); color: var(--ink); font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
  .font-display { font-family: 'Playfair Display', serif; }
  .tracking-wide-xs { letter-spacing: .18em; }

  .underline-grow { position: relative; text-decoration: none; color: var(--ink); }
  .underline-grow::after { content: ''; position: absolute; left: 0; bottom: -4px; height: 1px; width: 0; background: var(--ink); transition: width .4s ease; }
  .underline-grow:hover::after { width: 100%; }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .reveal { opacity: 0; animation: fadeUp .75s cubic-bezier(.16,1,.3,1) forwards; }
  .delay-0 { animation-delay: 0s; }
  .delay-1 { animation-delay: .08s; }
  .delay-2 { animation-delay: .18s; }
  .delay-3 { animation-delay: .28s; }
  .delay-4 { animation-delay: .38s; }
  .delay-5 { animation-delay: .48s; }
  .delay-6 { animation-delay: .58s; }
  @media (prefers-reduced-motion: reduce) { .reveal { animation: none; opacity: 1; } }

  /* ── Header ── */
  header { border-bottom: 1px solid var(--line); }
  .header-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; padding: 24px 40px; }
  .nav-links { display: flex; gap: 32px; font-size: 11px; text-transform: uppercase; letter-spacing: .18em; }
  .nav-links a { color: var(--ink); text-decoration: none; }
  .logo { font-family: 'Playfair Display', serif; font-size: 28px; letter-spacing: .04em; color: var(--ink); text-decoration: none; }
  .nav-icons { display: flex; align-items: center; gap: 20px; }
  .nav-icons a { color: var(--ink); text-decoration: none; font-size: 11px; text-transform: uppercase; letter-spacing: .18em; }
  .nav-icons a.icon-link { font-size: 19px; }

  /* ── Hero ── */
  .hero { max-width: 1200px; margin: 0 auto; padding: 80px 40px 48px; }
  .hero-eyebrow { font-size: 11px; text-transform: uppercase; letter-spacing: .18em; color: var(--taupe); margin-bottom: 24px; }
  .hero-title { font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(44px, 7vw, 88px); line-height: 1.04; color: var(--ink); max-width: 660px; }
  .hero-title strong { font-style: normal; font-weight: 600; }
  .hero-desc { margin-top: 28px; max-width: 520px; font-size: 17px; line-height: 1.75; color: var(--taupe); }

  /* ── Progress ── */
  .progress-section { max-width: 1200px; margin: 0 auto; padding: 0 40px 48px; }
  .progress-track { display: flex; align-items: center; }
  .progress-segment { flex: 1; height: 1px; }
  .progress-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
  .progress-label { margin-top: 14px; font-size: 11px; text-transform: uppercase; letter-spacing: .14em; color: var(--taupe); }

  /* ── Steps ── */
  .steps-section { max-width: 1200px; margin: 0 auto; padding: 0 40px 80px; }
  .step-divider { border: none; border-top: 1px solid var(--line); margin: 0; }
  .step-article { padding: 52px 0; display: grid; grid-template-columns: 160px 1fr; gap: 40px; align-items: start; }
  .step-num { font-family: 'Playfair Display', serif; font-style: italic; font-size: 68px; line-height: 1; }
  .step-body h3 { font-family: 'Playfair Display', serif; font-size: 28px; margin-bottom: 12px; color: var(--ink); }
  .step-body p  { line-height: 1.75; color: var(--taupe); max-width: 520px; font-size: 15px; }

  /* ── Empty state ── */
  .empty-state { max-width: 1200px; margin: 0 auto; padding: 80px 40px; text-align: center; }
  .empty-state h2 { font-family: 'Playfair Display', serif; font-size: 32px; color: var(--ink); margin-bottom: 16px; }
  .empty-state p  { color: var(--taupe); font-size: 16px; max-width: 400px; margin: 0 auto; }

  /* ── CTA ── */
  .cta-section { max-width: 1200px; margin: 0 auto; padding: 0 40px 80px; }
  .cta-inner { border-top: 1px solid var(--line); padding: 56px 0; display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap; }
  .cta-copy h2 { font-family: 'Playfair Display', serif; font-size: 32px; color: var(--ink); margin-bottom: 10px; }
  .cta-copy p  { color: var(--taupe); font-size: 15px; max-width: 420px; line-height: 1.65; }
  .cta-btn { display: inline-block; padding: 14px 36px; background: var(--ink); color: var(--cream); font-family: 'Inter', sans-serif; font-size: 11px; text-transform: uppercase; letter-spacing: .18em; text-decoration: none; border: none; cursor: pointer; transition: background .25s, transform .15s; }
  .cta-btn:hover { background: var(--rust); transform: translateY(-1px); }

  @media (max-width: 768px) {
    .header-inner { padding: 18px 20px; }
    .nav-links { display: none; }
    .hero { padding: 52px 20px 32px; }
    .progress-section { padding: 0 20px 32px; }
    .steps-section { padding: 0 20px 48px; }
    .step-article { grid-template-columns: 80px 1fr; gap: 20px; padding: 36px 0; }
    .step-num { font-size: 48px; }
    .cta-section { padding: 0 20px 48px; }
    .cta-inner { flex-direction: column; align-items: flex-start; gap: 24px; }
  }
</style>
</head>
<body class="antialiased">

  <!-- ===== NAVBAR ===== -->
  <header>
    <div class="header-inner">
      <nav class="nav-links">
        <a href="#" class="underline-grow">Shop</a>
        <a href="#" class="underline-grow">Search</a>
      </nav>
      <a href="#" class="logo">Clothique</a>
      <div class="nav-icons">
        <a href="#" class="underline-grow hidden md:inline">My Account</a>
        <a href="#" class="icon-link" aria-label="Wishlist">&#9825;</a>
        <a href="#" class="icon-link" aria-label="Cart">&#128722;</a>
      </div>
    </div>
  </header>

  <main>

    <!-- ===== HERO ===== -->
    <section class="hero">
      <p class="hero-eyebrow reveal delay-0">Layanan Purna Beli</p>
      <h1 class="hero-title reveal delay-1">
        Kebijakan <strong>Pengembalian</strong>
      </h1>
      <p class="hero-desc reveal delay-2">
        Jika produk tidak sesuai harapan, kami ingin proses pengembaliannya
        sejelas dan setenang proses Anda memilihnya.
      </p>
    </section>

    @php
      $activeSteps = collect($steps)
        ->filter(fn($s) => $s['is_active'] == 1)
        ->sortBy('step_order')
        ->values();

      $total    = $activeSteps->count();
      $hasSteps = $total > 0;

      function dotColor(int $order, int $current): string {
        if ($order < $current)  return 'var(--ink)';
        if ($order === $current) return 'var(--rust)';
        return 'var(--line)';
      }

      function numColor(int $order, int $current): string {
        if ($order < $current)  return 'var(--ink)';
        if ($order === $current) return 'var(--rust)';
        return 'var(--line)';
      }
    @endphp

    @if ($hasSteps)

      <!-- ===== PROGRESS BAR ===== -->
      <section class="progress-section reveal delay-3">
        <div class="progress-track">
          @foreach ($activeSteps as $i => $step)
            @if ($i > 0)
              @php
                $prevOrder = $activeSteps[$i - 1]['step_order'];
                $segColor  = $prevOrder < $currentStep ? 'var(--ink)' : 'var(--line)';
              @endphp
              <div class="progress-segment" style="background: {{ $segColor }};"></div>
            @endif
            <div
              class="progress-dot"
              style="background: {{ dotColor($step['step_order'], $currentStep) }};"
              title="{{ $step['title'] }}"
            ></div>
          @endforeach
        </div>
        <p class="progress-label">Langkah {{ $currentStep }} dari {{ $total }}</p>
      </section>

      <!-- ===== STEPS ===== -->
      <section class="steps-section">
        @foreach ($activeSteps as $i => $step)
          @php
            $padNum = str_pad($step['step_order'], 2, '0', STR_PAD_LEFT);
            $delay  = 'delay-' . min($i + 3, 6);
          @endphp

          @if ($i > 0)
            <hr class="step-divider">
          @endif

          <article class="step-article reveal {{ $delay }}">
            <span class="step-num" style="color: {{ numColor($step['step_order'], $currentStep) }};">{{ $padNum }}</span>
            <div class="step-body">
              <h3>{{ $step['title'] }}</h3>
              <p>{{ $step['description'] }}</p>
            </div>
          </article>
        @endforeach
      </section>

    @else

      <!-- ===== EMPTY STATE ===== -->
      <section class="empty-state reveal delay-2">
        <h2>Kebijakan belum tersedia</h2>
        <p>Informasi langkah pengembalian sedang kami siapkan. Silakan hubungi tim kami untuk bantuan langsung.</p>
      </section>

    @endif

  </main>

</body>
</html>
</x-layouts.app>