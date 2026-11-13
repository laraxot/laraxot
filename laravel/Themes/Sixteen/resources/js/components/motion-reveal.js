/**
 * Motion Reveal utility for Alpine.js
 * Provides scroll-reveal animations with respects-reduced-motion.
 *
 * Uses Intersection Observer to detect when elements enter the viewport
 * and applies CSS transitions. Designed as a composable mixed into
 * page-level Alpine data controllers.
 */

export function motionReveal() {
    return {
        /**
         * Setup fade-up reveal on elements matching selector.
         * Elements should have data-reveal="fade-up" and optional data-delay.
         */
        setupReveal(selector) {
            const prefersReduced = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;
            if (prefersReduced) {
                return;
            }

            const elements =
                typeof selector === 'string'
                    ? document.querySelectorAll(selector)
                    : selector instanceof NodeList
                      ? selector
                      : document.querySelectorAll(selector);

            if (!('IntersectionObserver' in window)) {
                elements.forEach(el => {
                    if (el) {
                        el.classList.add('motion-reveal-visible');
                    }
                });
                return;
            }

            const observer = new IntersectionObserver(
                entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const el = entry.target;
                            const delay = parseInt(
                                el.getAttribute('data-delay') || '0',
                                10
                            );
                            if (delay > 0) {
                                el.style.transitionDelay = `${delay}ms`;
                            }
                            el.classList.add('motion-reveal-visible');
                            observer.unobserve(el);
                        }
                    });
                },
                { threshold: 0.1, rootMargin: '0px 0px -30px 0px' }
            );

            elements.forEach(el => {
                if (el) {
                    observer.observe(el);
                }
            });
        },

        /**
         * Setup hover elevation effect on cards.
         */
        setupCardHover(selector) {
            const prefersReduced = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;
            if (prefersReduced) {
                return;
            }

            const cards =
                typeof selector === 'string'
                    ? document.querySelectorAll(selector)
                    : selector instanceof NodeList
                      ? selector
                      : document.querySelectorAll(selector);

            cards.forEach(card => {
                if (!card) {
                    return;
                }
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = '';
                });
            });
        },
    };
}

export default motionReveal;
