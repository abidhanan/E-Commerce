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
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        min-height: 100vh;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        color: var(--dark);
        background: var(--soft-bg);
        overflow: hidden;
    }

    .auth-shell {
        min-height: 100vh;
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(420px, 0.95fr);
        background: var(--soft-bg);
    }

    .auth-visual {
        position: relative;
        overflow: hidden;
        background: var(--black);
    }

    .auth-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.8s ease;
    }

    .auth-slide.active {
        opacity: 1;
    }

    .auth-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .auth-slide::after {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
    }

    .auth-visual-brand {
        position: absolute;
        left: clamp(28px, 4vw, 56px);
        right: clamp(28px, 4vw, 56px);
        bottom: clamp(28px, 5vw, 64px);
        z-index: 2;
        color: var(--white);
    }

    .auth-visual-kicker,
    .auth-brand-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--gold);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.8px;
        text-transform: uppercase;
    }

    .auth-visual-kicker::before,
    .auth-brand-kicker::before {
        content: "";
        width: 28px;
        height: 1px;
        background: var(--gold);
    }

    .auth-visual-title {
        max-width: 620px;
        margin-top: 16px;
        font-size: clamp(34px, 4.8vw, 68px);
        line-height: 0.98;
        font-weight: 700;
        letter-spacing: 0;
    }

    .auth-visual-copy {
        max-width: 480px;
        margin-top: 18px;
        color: var(--border-soft);
        font-size: 15px;
        line-height: 1.7;
    }

    .auth-arrow {
        position: absolute;
        top: 50%;
        z-index: 3;
        width: 42px;
        height: 42px;
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.16);
        color: var(--white);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.22s ease, transform 0.22s ease;
    }

    .auth-arrow:hover {
        background: rgba(255, 255, 255, 0.28);
    }

    .auth-arrow.prev {
        left: 24px;
        transform: translateY(-50%);
    }

    .auth-arrow.next {
        right: 24px;
        transform: translateY(-50%);
    }

    .auth-indicators {
        position: absolute;
        left: 50%;
        bottom: 24px;
        z-index: 3;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
    }

    .auth-indicator {
        width: 8px;
        height: 8px;
        border: 0;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: width 0.22s ease, background 0.22s ease;
    }

    .auth-indicator.active {
        width: 34px;
        background: var(--gold);
    }

    .auth-panel {
        min-width: 0;
        display: flex;
        align-items: safe center;
        justify-content: center;
        padding: clamp(24px, 5vw, 56px);
        background: var(--white);
        overflow-y: auto;
    }

    .auth-card {
        width: 100%;
        max-width: 470px;
        padding: clamp(28px, 4vw, 44px);
        border: 1px solid var(--border-soft);
        border-radius: 24px;
        background: var(--white);
        box-shadow: 0 28px 70px rgba(0, 0, 0, 0.08);
    }

    .auth-card--register {
        max-width: 430px;
        padding: clamp(22px, 2.5vw, 30px) clamp(24px, 3vw, 34px);
    }

    .auth-logo {
        width: 50px;
        height: 50px;
        border: 1px solid var(--border-soft);
        border-radius: 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--white);
        margin-bottom: 18px;
        overflow: hidden;
    }

    .auth-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .auth-title {
        margin: 14px 0 8px;
        font-size: clamp(28px, 4vw, 36px);
        line-height: 1.08;
        font-weight: 750;
        color: var(--black);
        letter-spacing: 0;
    }

    .auth-subtitle {
        color: var(--muted);
        font-size: 14px;
        line-height: 1.65;
        margin-bottom: 24px;
    }

    .auth-tabs {
        display: flex;
        gap: 4px;
        padding: 4px;
        margin-bottom: 24px;
        border: 1px solid var(--border-soft);
        border-radius: 999px;
        background: var(--soft-bg);
    }

    .auth-tab {
        flex: 1;
        min-height: 42px;
        border: 0;
        border-radius: 999px;
        background: transparent;
        color: var(--dark);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.4px;
        cursor: pointer;
        transition: background 0.22s ease, color 0.22s ease;
    }

    .auth-tab:hover {
        color: var(--gold);
    }

    .auth-tab.active {
        background: var(--black);
        color: var(--white);
    }

    .auth-group {
        margin-bottom: 16px;
    }

    .auth-card--register .auth-logo {
        width: 42px;
        height: 42px;
        margin-bottom: 10px;
    }

    .auth-card--register .auth-title {
        margin: 8px 0 6px;
        font-size: clamp(24px, 3vw, 29px);
    }

    .auth-card--register .auth-subtitle,
    .auth-card--register .auth-tabs {
        margin-bottom: 14px;
    }

    .auth-card--register .auth-subtitle {
        font-size: 13px;
        line-height: 1.5;
    }

    .auth-card--register .auth-tabs {
        padding: 3px;
    }

    .auth-card--register .auth-tab {
        min-height: 38px;
        font-size: 12px;
    }

    .auth-card--register .auth-group {
        margin-bottom: 10px;
    }

    .auth-card--register .auth-label {
        margin-bottom: 6px;
        font-size: 11px;
    }

    .auth-card--register .auth-input {
        min-height: 42px;
        padding: 9px 13px;
        font-size: 13px;
        border-radius: 12px;
    }

    .auth-card--register .auth-input.has-toggle {
        padding-right: 66px;
    }

    .auth-card--register .password-toggle {
        right: 8px;
        font-size: 11px;
        padding: 7px;
    }

    .auth-card--register .checkbox-wrap {
        gap: 8px;
        margin: 2px 0 12px;
    }

    .auth-card--register .checkbox-group {
        gap: 8px;
        font-size: 12px;
        line-height: 1.45;
    }

    .auth-card--register .checkbox-group input {
        width: 14px;
        height: 14px;
        min-width: 14px;
    }

    .auth-card--register .submit-btn {
        min-height: 44px;
        border-radius: 12px;
        font-size: 13px;
    }

    .auth-card--register .form-footer {
        margin-top: 14px;
        font-size: 13px;
    }

    .auth-label {
        display: block;
        margin-bottom: 8px;
        color: var(--dark);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.7px;
        text-transform: uppercase;
    }

    .auth-input-wrap {
        position: relative;
    }

    .auth-input {
        width: 100%;
        min-height: 50px;
        padding: 13px 15px;
        border: 1px solid var(--border-soft);
        border-radius: 14px;
        background: var(--white);
        color: var(--dark);
        font-size: 14px;
        outline: none;
        transition: border-color 0.22s ease, box-shadow 0.22s ease;
    }

    .auth-input::placeholder {
        color: var(--muted);
        opacity: 0.72;
    }

    .auth-input:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 4px rgba(158, 128, 53, 0.18);
    }

    .auth-input.is-invalid {
        border-color: var(--gold);
    }

    .auth-input.has-toggle {
        padding-right: 76px;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: var(--gold);
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        padding: 8px;
    }

    .auth-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin: 4px 0 18px;
    }

    .auth-check,
    .checkbox-group {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.5;
        cursor: pointer;
    }

    .auth-check input,
    .checkbox-group input {
        width: 16px;
        height: 16px;
        min-width: 16px;
        margin-top: 2px;
        accent-color: var(--gold);
        cursor: pointer;
    }

    .checkbox-wrap {
        display: grid;
        gap: 10px;
        margin: 6px 0 18px;
    }

    .auth-link,
    .form-footer button {
        border: 0;
        background: transparent;
        color: var(--gold);
        font: inherit;
        font-weight: 750;
        text-decoration: none;
        cursor: pointer;
        transition: color 0.22s ease;
    }

    .auth-link:hover,
    .form-footer button:hover {
        color: var(--black);
    }

    .submit-btn,
    .auth-button {
        width: 100%;
        min-height: 50px;
        border: 0;
        border-radius: 14px;
        background: var(--gold);
        color: var(--white);
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.7px;
        cursor: pointer;
        transition: background 0.22s ease, transform 0.22s ease, box-shadow 0.22s ease, opacity 0.22s ease;
        box-shadow: 0 14px 26px rgba(0, 0, 0, 0.14);
    }

    .submit-btn:hover:not(:disabled),
    .auth-button:hover:not(:disabled) {
        background: var(--black);
        color: var(--white);
        transform: translateY(-1px);
    }

    .submit-btn:disabled,
    .auth-button:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        box-shadow: none;
    }

    .form-footer {
        margin-top: 18px;
        text-align: center;
        color: var(--muted);
        font-size: 14px;
    }

    .invalid-feedback {
        display: block;
        margin-top: 6px;
        color: var(--gold);
        font-size: 12px;
        font-weight: 650;
    }

    .alert-error,
    .alert-success,
    .auth-alert {
        margin-bottom: 16px;
        padding: 12px 14px;
        border-radius: 14px;
        background: var(--soft-bg);
        border: 1px solid var(--border-soft);
        color: var(--dark);
        font-size: 13px;
        line-height: 1.5;
    }

    .alert-error {
        border-color: var(--gold);
    }

    .alert-error ul {
        margin: 0;
        padding-left: 18px;
    }

    @media (max-width: 1024px) {
        body {
            overflow: auto;
        }

        .auth-shell {
            display: block;
            min-height: 100vh;
            background: var(--black);
        }

        .auth-visual {
            display: none;
        }

        .auth-panel {
            min-height: 100vh;
            padding: 24px;
            background: var(--soft-bg);
        }
    }

    @media (max-width: 575px) {
        .auth-panel {
            padding: 16px;
        }

        .auth-card {
            padding: 24px 20px;
            border-radius: 18px;
        }

        .auth-row {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
