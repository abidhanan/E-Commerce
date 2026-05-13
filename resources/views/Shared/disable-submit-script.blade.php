<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-disable-on-submit]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (form.dataset.submitting === 'true') {
                    event.preventDefault();
                    return;
                }

                if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                    return;
                }

                form.dataset.submitting = 'true';

                form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach((button) => {
                    const loadingText = button.dataset.loadingText || form.dataset.loadingText || 'Mohon tunggu...';

                    if (button.tagName === 'BUTTON') {
                        button.dataset.originalText = button.innerHTML;
                        button.innerHTML = loadingText;
                    } else {
                        button.dataset.originalText = button.value;
                        button.value = loadingText;
                    }

                    button.disabled = true;
                    button.setAttribute('aria-busy', 'true');
                });
            });
        });
    });
</script>
