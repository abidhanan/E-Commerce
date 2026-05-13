<div class="modal fade" id="adminConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminConfirmModalTitle">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="adminConfirmModalMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="adminConfirmModalAcceptBtn">Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<script>
    (() => {
        const modalEl = document.getElementById('adminConfirmModal');
        const titleEl = document.getElementById('adminConfirmModalTitle');
        const messageEl = document.getElementById('adminConfirmModalMessage');
        const acceptBtn = document.getElementById('adminConfirmModalAcceptBtn');

        if (!modalEl || !titleEl || !messageEl || !acceptBtn || typeof bootstrap === 'undefined') {
            return;
        }

        const modal = new bootstrap.Modal(modalEl);
        let resolver = null;
        let acceptHandler = null;

        const resetConfirmButton = () => {
            acceptBtn.textContent = 'Lanjutkan';
            acceptBtn.className = 'btn btn-danger';
        };

        const closeResolver = (value) => {
            if (!resolver) {
                return;
            }

            const activeResolver = resolver;
            resolver = null;
            activeResolver(value);
        };

        acceptBtn.addEventListener('click', () => {
            const handler = acceptHandler;
            acceptHandler = null;
            closeResolver(true);
            modal.hide();

            if (typeof handler === 'function') {
                handler();
            }
        });

        modalEl.addEventListener('hidden.bs.modal', () => {
            acceptHandler = null;
            resetConfirmButton();
            closeResolver(false);
        });

        window.adminConfirmDialog = (options = {}) => {
            titleEl.textContent = options.title || 'Konfirmasi';
            messageEl.textContent = options.message || '';
            acceptBtn.textContent = options.confirmText || 'Lanjutkan';
            acceptBtn.className = `btn ${options.confirmClass || 'btn-danger'}`;
            acceptHandler = options.onConfirm || null;

            return new Promise((resolve) => {
                resolver = resolve;
                modal.show();
            });
        };

        document.addEventListener('submit', (event) => {
            const form = event.target.closest('form[data-confirm-message]');

            if (!form) {
                return;
            }

            if (form.dataset.confirmed === 'true') {
                form.dataset.confirmed = '';
                return;
            }

            event.preventDefault();

            window.adminConfirmDialog({
                title: form.dataset.confirmTitle || 'Konfirmasi',
                message: form.dataset.confirmMessage || 'Lanjutkan tindakan ini?',
                confirmText: form.dataset.confirmButton || 'Lanjutkan',
                confirmClass: form.dataset.confirmClass || 'btn-danger',
            }).then((confirmed) => {
                if (!confirmed) {
                    return;
                }

                form.dataset.confirmed = 'true';

                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                    return;
                }

                form.submit();
            });
        }, true);
    })();
</script>
