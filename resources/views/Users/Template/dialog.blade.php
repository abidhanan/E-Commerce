<style>
    .app-dialog-root[hidden] {
        display: none !important;
    }

    .app-dialog-root {
        position: fixed;
        inset: 0;
        z-index: 13000;
    }

    .app-dialog-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.46);
        opacity: 0;
        transition: opacity 0.22s ease;
    }

    .app-dialog-panel {
        position: absolute;
        top: 50%;
        left: 50%;
        width: min(460px, calc(100vw - 32px));
        padding: 20px;
        border: 1px solid #dedede;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 24px 56px rgba(0, 0, 0, 0.18);
        transform: translate(-50%, calc(-50% + 12px));
        opacity: 0;
        transition: transform 0.22s ease, opacity 0.22s ease;
        backdrop-filter: blur(12px);
    }

    .app-dialog-root.is-open .app-dialog-backdrop {
        opacity: 1;
    }

    .app-dialog-root.is-open .app-dialog-panel {
        opacity: 1;
        transform: translate(-50%, -50%);
    }

    .app-dialog-title {
        margin: 0 0 8px;
        font-size: 18px;
        font-weight: 600;
        line-height: 1.35;
        color: #111;
    }

    .app-dialog-message {
        margin: 0;
        font-size: 13px;
        line-height: 1.65;
        color: #4b4b4b;
        white-space: pre-line;
    }

    .app-dialog-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .app-dialog-button {
        min-width: 110px;
        padding: 11px 14px;
        border: 1px solid #ddd;
        background: #fff;
        color: #111;
        font-size: 12px;
        cursor: pointer;
    }

    .app-dialog-button--confirm {
        border-color: #111;
        background: #111;
        color: #fff;
    }

    .app-dialog-button--danger {
        border-color: #9b2c2c;
        background: #9b2c2c;
        color: #fff;
    }

    .app-dialog-lock {
        overflow: hidden;
    }

    body.dark-mode .app-dialog-panel {
        background: rgba(18, 18, 18, 0.98);
        border-color: #303030;
        box-shadow: 0 24px 56px rgba(0, 0, 0, 0.38);
    }

    body.dark-mode .app-dialog-title {
        color: #f3f3f3;
    }

    body.dark-mode .app-dialog-message {
        color: #cfcfcf;
    }

    body.dark-mode .app-dialog-button {
        border-color: #3a3a3a;
        background: #1c1c1c;
        color: #f3f3f3;
    }

    body.dark-mode .app-dialog-button--confirm {
        border-color: #f3f3f3;
        background: #f3f3f3;
        color: #111;
    }

    body.dark-mode .app-dialog-button--danger {
        border-color: #c34a4a;
        background: #c34a4a;
        color: #fff;
    }
</style>

<div id="appDialogRoot" class="app-dialog-root" hidden>
    <div class="app-dialog-backdrop" data-dialog-close></div>
    <div class="app-dialog-panel" role="dialog" aria-modal="true" aria-labelledby="appDialogTitle">
        <h3 class="app-dialog-title" id="appDialogTitle">Konfirmasi</h3>
        <p class="app-dialog-message" id="appDialogMessage"></p>
        <div class="app-dialog-actions">
            <button type="button" class="app-dialog-button" id="appDialogCancelBtn">Batal</button>
            <button type="button" class="app-dialog-button app-dialog-button--confirm" id="appDialogConfirmBtn">
                Lanjutkan
            </button>
        </div>
    </div>
</div>

<script>
    (() => {
        const root = document.getElementById('appDialogRoot');
        const titleEl = document.getElementById('appDialogTitle');
        const messageEl = document.getElementById('appDialogMessage');
        const cancelBtn = document.getElementById('appDialogCancelBtn');
        const confirmBtn = document.getElementById('appDialogConfirmBtn');
        const backdrop = root?.querySelector('[data-dialog-close]');

        if (!root || !titleEl || !messageEl || !cancelBtn || !confirmBtn) {
            return;
        }

        let resolver = null;
        let currentMode = 'confirm';

        const closeDialog = (result) => {
            root.classList.remove('is-open');
            document.body.classList.remove('app-dialog-lock');
            window.setTimeout(() => {
                root.hidden = true;
            }, 220);

            if (resolver) {
                const activeResolver = resolver;
                resolver = null;
                activeResolver(result);
            }
        };

        const openDialog = (options = {}, mode = 'confirm') => {
            currentMode = mode;
            titleEl.textContent = options.title || (mode === 'alert' ? 'Informasi' : 'Konfirmasi');
            messageEl.textContent = options.message || '';
            cancelBtn.hidden = mode === 'alert';
            confirmBtn.textContent = options.confirmText || (mode === 'alert' ? 'Tutup' : 'Lanjutkan');
            confirmBtn.classList.toggle('app-dialog-button--danger', options.variant === 'danger');
            confirmBtn.classList.toggle('app-dialog-button--confirm', options.variant !== 'danger');

            root.hidden = false;
            document.body.classList.add('app-dialog-lock');
            window.requestAnimationFrame(() => {
                root.classList.add('is-open');
            });
        };

        cancelBtn.addEventListener('click', () => closeDialog(false));
        confirmBtn.addEventListener('click', () => closeDialog(true));
        backdrop?.addEventListener('click', () => closeDialog(currentMode === 'alert'));

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !root.hidden) {
                closeDialog(currentMode === 'alert' ? true : false);
            }
        });

        window.appDialog = {
            alert(message, title = 'Informasi', options = {}) {
                return new Promise((resolve) => {
                    resolver = resolve;
                    openDialog({
                        ...options,
                        title,
                        message,
                        confirmText: options.confirmText || 'Tutup',
                    }, 'alert');
                });
            },
            confirm(message, title = 'Konfirmasi', options = {}) {
                return new Promise((resolve) => {
                    resolver = resolve;
                    openDialog({
                        ...options,
                        title,
                        message,
                        confirmText: options.confirmText || 'Lanjutkan',
                    }, 'confirm');
                });
            },
        };
    })();
</script>
