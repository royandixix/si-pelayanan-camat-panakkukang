const initializePengunjung = () => {
    const site = document.querySelector('.public-site');

    if (!site) {
        return;
    }

    const navbar = document.querySelector('.public-nav');
    const menuButton = document.querySelector('[data-public-menu-button]');
    const mobileMenu = document.querySelector('[data-public-mobile-menu]');
    const backTop = document.querySelector('[data-public-back-top]');

    const openMenu = () => {
        if (!menuButton || !mobileMenu) {
            return;
        }

        mobileMenu.classList.add('is-open');
        menuButton.classList.add('is-open');
        menuButton.setAttribute('aria-expanded', 'true');
        menuButton.setAttribute('aria-label', 'Tutup menu');
    };

    const closeMenu = () => {
        if (!menuButton || !mobileMenu) {
            return;
        }

        mobileMenu.classList.remove('is-open');
        menuButton.classList.remove('is-open');
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.setAttribute('aria-label', 'Buka menu');
    };

    const toggleMenu = () => {
        if (!mobileMenu) {
            return;
        }

        if (mobileMenu.classList.contains('is-open')) {
            closeMenu();
            return;
        }

        openMenu();
    };

    const setScrollState = () => {
        navbar?.classList.toggle('is-scrolled', window.scrollY > 20);
        backTop?.classList.toggle('is-visible', window.scrollY > 500);
    };

    setScrollState();

    window.addEventListener(
        'scroll',
        setScrollState,
        {
            passive: true,
        }
    );

    menuButton?.addEventListener('click', (event) => {
        event.stopPropagation();
        toggleMenu();
    });

    mobileMenu?.addEventListener('click', (event) => {
        event.stopPropagation();
    });

    mobileMenu?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            closeMenu();
        });
    });

    document.addEventListener('click', () => {
        closeMenu();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeMenu();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) {
            closeMenu();
        }
    });

    const reveals = document.querySelectorAll('[data-public-reveal]');

    if ('IntersectionObserver' in window) {
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
    } else {
        reveals.forEach((element) => {
            element.classList.add('is-visible');
        });
    }

    const counters = document.querySelectorAll('[data-counter]');

    if ('IntersectionObserver' in window) {
        const counterObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    const element = entry.target;
                    const target = Math.max(
                        0,
                        Number(element.dataset.counter ?? 0)
                    );

                    const duration = 1200;
                    const startedAt = performance.now();

                    const updateCounter = (time) => {
                        const progress = Math.min(
                            (time - startedAt) / duration,
                            1
                        );

                        const eased = 1 - Math.pow(1 - progress, 3);
                        const value = Math.floor(target * eased);

                        element.textContent =
                            new Intl.NumberFormat('id-ID').format(value);

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
    }

    backTop?.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    });
};

if (document.readyState === 'loading') {
    document.addEventListener(
        'DOMContentLoaded',
        initializePengunjung
    );
} else {
    initializePengunjung();
}


const initializePublicFaq = () => {
    const items = document.querySelectorAll('[data-faq-item]');

    if (!items.length) {
        return;
    }

    const openItem = (item) => {
        const button = item.querySelector('[data-faq-button]');
        const content = item.querySelector('[data-faq-content]');

        if (!button || !content) {
            return;
        }

        item.classList.add('is-open');
        button.setAttribute('aria-expanded', 'true');
        content.style.height = `${content.scrollHeight}px`;

        window.setTimeout(() => {
            if (item.classList.contains('is-open')) {
                content.style.height = 'auto';
            }
        }, 500);
    };

    const closeItem = (item) => {
        const button = item.querySelector('[data-faq-button]');
        const content = item.querySelector('[data-faq-content]');

        if (!button || !content) {
            return;
        }

        content.style.height = `${content.scrollHeight}px`;

        requestAnimationFrame(() => {
            content.style.height = '0px';
            item.classList.remove('is-open');
            button.setAttribute('aria-expanded', 'false');
        });
    };

    items.forEach((item) => {
        const button = item.querySelector('[data-faq-button]');
        const content = item.querySelector('[data-faq-content]');

        if (!button || !content) {
            return;
        }

        if (item.classList.contains('is-open')) {
            content.style.height = 'auto';
            button.setAttribute('aria-expanded', 'true');
        } else {
            content.style.height = '0px';
            button.setAttribute('aria-expanded', 'false');
        }

        button.addEventListener('click', () => {
            const currentlyOpen = item.classList.contains('is-open');

            items.forEach((otherItem) => {
                if (otherItem !== item && otherItem.classList.contains('is-open')) {
                    closeItem(otherItem);
                }
            });

            if (currentlyOpen) {
                closeItem(item);
            } else {
                openItem(item);
            }
        });
    });

    window.addEventListener('resize', () => {
        items.forEach((item) => {
            if (!item.classList.contains('is-open')) {
                return;
            }

            const content = item.querySelector('[data-faq-content]');

            if (content) {
                content.style.height = 'auto';
            }
        });
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePublicFaq);
} else {
    initializePublicFaq();
}
