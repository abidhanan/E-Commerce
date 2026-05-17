<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.auth-slide');
    const indicators = document.querySelectorAll('.auth-indicator');
    const totalSlides = slides.length;

    function showSlide(index) {
        if (!totalSlides) {
            return;
        }

        slides.forEach((slide) => slide.classList.remove('active'));
        indicators.forEach((indicator) => indicator.classList.remove('active'));
        currentSlide = (index + totalSlides) % totalSlides;
        slides[currentSlide].classList.add('active');

        if (indicators[currentSlide]) {
            indicators[currentSlide].classList.add('active');
        }
    }

    function changeSlide(direction) {
        showSlide(currentSlide + direction);
    }

    function goToSlide(index) {
        showSlide(index);
    }

    const saved = JSON.parse(localStorage.getItem('slideState') || 'null');
    if (saved && totalSlides) {
        const elapsed = (Date.now() - saved.time) / 1000;
        const skipped = Math.floor(elapsed / 5);
        showSlide((saved.index + skipped) % totalSlides);
        localStorage.removeItem('slideState');
    }

    if (totalSlides > 1) {
        setInterval(() => changeSlide(1), 5000);
    }

    function authNavigate(url) {
        localStorage.setItem('slideState', JSON.stringify({
            index: currentSlide,
            time: Date.now()
        }));

        const card = document.querySelector('.auth-card');
        if (card) {
            card.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
            card.style.opacity = '0';
            card.style.transform = 'translateY(6px)';
        }

        setTimeout(() => {
            window.location.href = url;
        }, 200);
    }

    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);

            if (!input) {
                return;
            }

            const shouldShow = input.type === 'password';
            input.type = shouldShow ? 'text' : 'password';
            button.textContent = shouldShow ? 'Hide' : 'Show';
        });
    });

    document.querySelectorAll('form[data-save-slide-state]').forEach((form) => {
        form.addEventListener('submit', () => {
            localStorage.setItem('slideState', JSON.stringify({
                index: currentSlide,
                time: Date.now()
            }));
        });
    });
</script>
