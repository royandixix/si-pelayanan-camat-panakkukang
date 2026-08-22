const initializePengunjung = () => {
    const site = document.querySelector('.public-site');

    if (!site) {
        return;
    }

    const navbar = document.querySelector('.public-nav');
    const menuButton = document.querySelector('[data-public-menu-button]');
    const mobileMenu = document.querySelector('[data-public-mobile-menu]');
    const backTop = document.querySelector('[data-public-back-top]');

    const setScrollState = () => {
        navbar?.classList.toggle('is-scrolled', window.scrollY > 15);
        backTop?.classList.toggle('is-visible', window.scrollY > 500);
    };

    setScrollState();

    window.addEventListener('scroll', setScrollState, {
        passive: true,
    });

    menuButton?.addEventListener('click', () => {
        mobileMenu?.classList.toggle('is-open');

        const isOpen = mobileMenu?.classList.contains('is-open') ?? false;

        menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    mobileMenu?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('is-open');
            menuButton?.setAttribute('aria-expanded', 'false');
        });
    });

    const reveals = document.querySelectorAll('[data-public-reveal]');

    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            });
        },
        {
            threshold: 0.12,
        }
    );

    reveals.forEach((element) => {
        element.classList.add('public-reveal');
        revealObserver.observe(element);
    });

    const counters = document.querySelectorAll('[data-counter]');

    const counterObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                const element = entry.target;
                const target = Number(element.dataset.counter ?? 0);
                const duration = 1200;
                const startedAt = performance.now();

                const updateCounter = (time) => {
                    const progress = Math.min((time - startedAt) / duration, 1);
                    const value = Math.floor(target * (1 - Math.pow(1 - progress, 3)));

                    element.textContent = new Intl.NumberFormat('id-ID').format(value);

                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    }
                };

                requestAnimationFrame(updateCounter);
                counterObserver.unobserve(element);
            });
        },
        {
            threshold: 0.6,
        }
    );

    counters.forEach((element) => {
        counterObserver.observe(element);
    });

    backTop?.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePengunjung);
} else {
    initializePengunjung();
}
