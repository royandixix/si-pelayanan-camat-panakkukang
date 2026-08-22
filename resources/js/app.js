import '@fontsource/poppins/400.css';
import '@fontsource/poppins/500.css';
import '@fontsource/poppins/600.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('heroSlider', () => ({
    active: 0,
    total: 3,
    timer: null,
    init() {
        this.start();
    },
    start() {
        this.stop();
        this.timer = window.setInterval(() => this.next(), 5500);
    },
    stop() {
        if (this.timer) {
            window.clearInterval(this.timer);
            this.timer = null;
        }
    },
    next() {
        this.active = (this.active + 1) % this.total;
    },
    prev() {
        this.active = (this.active - 1 + this.total) % this.total;
    },
    goTo(index) {
        this.active = index;
        this.start();
    },
}));

Alpine.data('scrollCarousel', () => ({
    scroll(direction) {
        const track = this.$refs.track;
        if (!track) return;
        const amount = Math.max(track.clientWidth * 0.72, 280);
        track.scrollBy({ left: direction * amount, behavior: 'smooth' });
    },
}));

Alpine.start();

const revealElements = document.querySelectorAll('[data-reveal], [data-reveal-left], [data-reveal-right], [data-reveal-item]');

if ('IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, {
        threshold: 0.14,
        rootMargin: '0px 0px -30px 0px',
    });

    revealElements.forEach((element) => revealObserver.observe(element));
} else {
    revealElements.forEach((element) => element.classList.add('is-visible'));
}

const counters = document.querySelectorAll('[data-counter]');

if ('IntersectionObserver' in window) {
    const counterObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;

            const element = entry.target;
            const target = Number.parseInt(element.dataset.counter ?? '0', 10);
            const suffix = element.dataset.counterSuffix ?? '';
            const duration = 1200;
            const start = performance.now();

            const tick = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const value = Math.round(target * eased);
                element.textContent = `${value.toLocaleString('id-ID')}${suffix}`;

                if (progress < 1) {
                    window.requestAnimationFrame(tick);
                }
            };

            window.requestAnimationFrame(tick);
            observer.unobserve(element);
        });
    }, { threshold: 0.5 });

    counters.forEach((counter) => counterObserver.observe(counter));
} else {
    counters.forEach((counter) => {
        counter.textContent = `${Number.parseInt(counter.dataset.counter ?? '0', 10).toLocaleString('id-ID')}${counter.dataset.counterSuffix ?? ''}`;
    });
}

const backToTop = document.querySelector('[data-back-to-top]');

if (backToTop) {
    const updateBackToTop = () => {
        const visible = window.scrollY > 500;
        backToTop.classList.toggle('pointer-events-none', !visible);
        backToTop.classList.toggle('opacity-0', !visible);
        backToTop.classList.toggle('translate-y-4', !visible);
    };

    updateBackToTop();
    window.addEventListener('scroll', updateBackToTop, { passive: true });
    backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}


import '../css/pengunjung.css';
import './pengunjung';