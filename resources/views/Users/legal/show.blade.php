<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $document->title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <style>
        :root {
            --gold: #9e8035;
            --black: #000000;
            --dark: #111111;
            --white: #ffffff;
            --soft-bg: #f8f8f8;
            --border-soft: #e5e5e5;
            --muted: #777777;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--soft-bg);
            color: var(--dark);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .legal-shell {
            min-height: 100vh;
            padding: clamp(24px, 5vw, 64px);
        }

        .legal-card {
            max-width: 920px;
            margin: 0 auto;
            border: 1px solid var(--border-soft);
            border-radius: 24px;
            background: var(--white);
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .legal-header {
            padding: clamp(28px, 5vw, 52px);
            background: var(--black);
            color: var(--white);
        }

        .legal-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--gold);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .legal-kicker::before {
            content: "";
            width: 28px;
            height: 1px;
            background: var(--gold);
        }

        h1 {
            margin: 16px 0 12px;
            font-size: clamp(32px, 5vw, 54px);
            line-height: 1;
            letter-spacing: 0;
        }

        .legal-summary {
            max-width: 680px;
            color: var(--border-soft);
            line-height: 1.7;
        }

        .legal-body {
            padding: clamp(28px, 5vw, 52px);
            color: var(--dark);
            font-size: 16px;
            line-height: 1.85;
            white-space: pre-line;
        }

        .legal-footer {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 20px clamp(28px, 5vw, 52px);
            border-top: 1px solid var(--border-soft);
            color: var(--muted);
            font-size: 14px;
        }

        .legal-link {
            color: var(--gold);
            font-weight: 700;
            text-decoration: none;
        }

        .legal-link:hover {
            color: var(--black);
        }

        @media (max-width: 575px) {
            .legal-footer {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <main class="legal-shell">
        <article class="legal-card">
            <header class="legal-header">
                <span class="legal-kicker">{{ $document->type_label }}</span>
                <h1>{{ $document->title }}</h1>
                @if ($document->summary)
                    <p class="legal-summary">{{ $document->summary }}</p>
                @endif
            </header>

            <section class="legal-body">{{ $document->content }}</section>

            <footer class="legal-footer">
                <span>Last updated {{ optional($document->updated_at)->format('d M Y') }}</span>
                <a href="{{ route('register') }}" class="legal-link">Back to register</a>
            </footer>
        </article>
    </main>
</body>

</html>
