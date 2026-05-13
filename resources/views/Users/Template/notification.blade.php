@php
    $notificationErrors = $errors ?? new \Illuminate\Support\ViewErrorBag();
    $flashNotifications = [];
    $sessionNotifications = session('notifications', []);
    $singleNotification = session('notify');

    if ($singleNotification) {
        $sessionNotifications[] = $singleNotification;
    }

    foreach ($sessionNotifications as $notification) {
        if (!is_array($notification)) {
            continue;
        }

        $flashNotifications[] = [
            'type' => $notification['type'] ?? 'info',
            'title' => $notification['title'] ?? null,
            'message' => $notification['message'] ?? null,
            'duration' => $notification['duration'] ?? 4200,
        ];
    }

    if (session('status')) {
        $flashNotifications[] = [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => session('status'),
            'duration' => 4200,
        ];
    }

    if (session('error')) {
        $flashNotifications[] = [
            'type' => 'error',
            'title' => 'Gagal',
            'message' => session('error'),
            'duration' => 5200,
        ];
    }

    if ($notificationErrors->any()) {
        $flashNotifications[] = [
            'type' => 'error',
            'title' => 'Periksa Form',
            'message' => $notificationErrors->first(),
            'duration' => 5200,
        ];
    }
@endphp

<style>
    .app-notify-root {
        position: fixed;
        top: 96px;
        right: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: min(360px, calc(100vw - 32px));
        z-index: 12000;
        pointer-events: none;
    }

    .app-notify {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 12px;
        align-items: start;
        padding: 14px 14px 14px 12px;
        border: 1px solid #dedede;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
        color: #111;
        transform: translateY(-10px);
        opacity: 0;
        transition: transform 0.22s ease, opacity 0.22s ease;
        pointer-events: auto;
        backdrop-filter: blur(10px);
    }

    .app-notify.is-visible {
        transform: translateY(0);
        opacity: 1;
    }

    .app-notify--success {
        border-color: #cfe5d1;
    }

    .app-notify--error {
        border-color: #efc3c3;
    }

    .app-notify--warning {
        border-color: #ead8a4;
    }

    .app-notify--info {
        border-color: #cfd8e8;
    }

    .app-notify__marker {
        width: 10px;
        height: 10px;
        margin-top: 6px;
        border-radius: 999px;
        background: #577590;
    }

    .app-notify--success .app-notify__marker {
        background: #2f7d32;
    }

    .app-notify--error .app-notify__marker {
        background: #b02a37;
    }

    .app-notify--warning .app-notify__marker {
        background: #a06a00;
    }

    .app-notify__body {
        min-width: 0;
    }

    .app-notify__title {
        margin: 0 0 4px;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.35;
    }

    .app-notify__message {
        margin: 0;
        font-size: 12px;
        line-height: 1.5;
        color: #4b4b4b;
        word-break: break-word;
    }

    .app-notify__close {
        border: none;
        background: transparent;
        color: #666;
        font-size: 18px;
        line-height: 1;
        cursor: pointer;
        padding: 0;
    }

    body.dark-mode .app-notify {
        background: rgba(20, 20, 20, 0.95);
        border-color: #303030;
        color: #f1f1f1;
        box-shadow: 0 18px 44px rgba(0, 0, 0, 0.34);
    }

    body.dark-mode .app-notify__message {
        color: #d0d0d0;
    }

    body.dark-mode .app-notify__close {
        color: #bbb;
    }

    @media (max-width: 768px) {
        .app-notify-root {
            top: 88px;
            right: 12px;
            left: 12px;
            width: auto;
        }
    }
</style>

<div id="appNotifyRoot" class="app-notify-root" aria-live="polite" aria-atomic="true"></div>

<script>
    (() => {
        const root = document.getElementById('appNotifyRoot');
        const flashNotifications = @json($flashNotifications);

        if (!root) {
            return;
        }

        const normalizeOptions = (options = {}) => ({
            type: ['success', 'error', 'warning', 'info'].includes(options.type) ? options.type : 'info',
            title: options.title || '',
            message: options.message || '',
            duration: Number.isFinite(Number(options.duration)) ? Number(options.duration) : 4200,
        });

        const extractMessage = (payload, fallback = 'Terjadi kesalahan.') => {
            if (!payload) {
                return fallback;
            }

            if (typeof payload === 'string') {
                return payload;
            }

            if (payload.errors && typeof payload.errors === 'object') {
                const firstError = Object.values(payload.errors).flat().find(Boolean);
                if (firstError) {
                    return firstError;
                }
            }

            if (payload.message && payload.message !== 'The given data was invalid.') {
                return payload.message;
            }

            return fallback;
        };

        const removeToast = (toast) => {
            if (!toast || !toast.parentNode) {
                return;
            }

            toast.classList.remove('is-visible');
            window.setTimeout(() => toast.remove(), 220);
        };

        const showNotification = (options = {}) => {
            const config = normalizeOptions(options);

            if (!config.message) {
                return null;
            }

            const toast = document.createElement('div');
            toast.className = `app-notify app-notify--${config.type}`;
            toast.setAttribute('role', config.type === 'error' ? 'alert' : 'status');

            const marker = document.createElement('span');
            marker.className = 'app-notify__marker';
            marker.setAttribute('aria-hidden', 'true');

            const body = document.createElement('div');
            body.className = 'app-notify__body';

            if (config.title) {
                const title = document.createElement('p');
                title.className = 'app-notify__title';
                title.textContent = config.title;
                body.appendChild(title);
            }

            const message = document.createElement('p');
            message.className = 'app-notify__message';
            message.textContent = config.message;
            body.appendChild(message);

            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.className = 'app-notify__close';
            closeButton.setAttribute('aria-label', 'Tutup notifikasi');
            closeButton.innerHTML = '&times;';
            closeButton.addEventListener('click', () => removeToast(toast));

            toast.appendChild(marker);
            toast.appendChild(body);
            toast.appendChild(closeButton);
            root.appendChild(toast);

            window.requestAnimationFrame(() => {
                toast.classList.add('is-visible');
            });

            if (config.duration > 0) {
                window.setTimeout(() => removeToast(toast), config.duration);
            }

            return toast;
        };

        const notify = (options) => showNotification(options);
        notify.success = (message, title = 'Berhasil', options = {}) => showNotification({
            ...options,
            type: 'success',
            title,
            message,
        });
        notify.error = (message, title = 'Gagal', options = {}) => showNotification({
            ...options,
            type: 'error',
            title,
            message,
        });
        notify.warning = (message, title = 'Perhatian', options = {}) => showNotification({
            ...options,
            type: 'warning',
            title,
            message,
        });
        notify.info = (message, title = 'Informasi', options = {}) => showNotification({
            ...options,
            type: 'info',
            title,
            message,
        });
        notify.extractMessage = extractMessage;
        window.appNotify = notify;

        flashNotifications.forEach((notification) => showNotification(notification));
    })();
</script>
