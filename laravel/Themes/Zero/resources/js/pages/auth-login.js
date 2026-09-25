import gsap from 'gsap';

/**
 * Motion for the auth login page (Themes/Zero/resources/views/pages/auth/login.blade.php).
 * No-ops entirely when the page's markup isn't present, so importing this from the
 * theme's global app.js is safe on every other page.
 */
export function initAuthLoginMotion() {
    const card = document.querySelector('[data-auth-card]');
    if (!card) {
        return;
    }

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const items = card.querySelectorAll('[data-auth-card-item], [data-auth-hero-item]');

    if (reduceMotion) {
        gsap.set(items, { opacity: 1, y: 0 });
    } else {
        gsap.from(items, {
            opacity: 0,
            y: 16,
            duration: 0.45,
            stagger: 0.07,
            ease: 'power1.out',
        });
    }

    // Shake the card when Filament/Livewire renders a validation error inside the
    // login widget. Purely decorative, never touches the error message markup
    // itself. Filament's own error paragraph (.fi-fo-field-wrp-error-message)
    // isn't wrapped in role="alert"/aria-live, so screen readers won't announce
    // it either way — matched here too so the shake still fires, but that gap
    // is a Filament-core rendering choice this theme-level pass doesn't touch.
    const widget = document.getElementById('login-widget');
    if (!widget) {
        return;
    }

    // Subtle press feedback for the submit button (data-auth-submit, in the
    // Zero-themed LoginWidget view). Delegated on the widget container —
    // rather than bound directly to the button — so it keeps working if
    // Livewire morphs/replaces the button node on re-render (e.g. after a
    // failed-login validation pass). No-op under prefers-reduced-motion.
    if (!reduceMotion) {
        const pressScale = (target, scale) => gsap.to(target, { scale, duration: 0.12, ease: 'power1.out' });

        widget.addEventListener('pointerdown', (event) => {
            const button = event.target.closest('[data-auth-submit]');
            if (button && !button.disabled) {
                pressScale(button, 0.97);
            }
        });

        ['pointerup', 'pointerout', 'pointercancel'].forEach((type) => {
            widget.addEventListener(type, (event) => {
                const button = event.target.closest('[data-auth-submit]');
                if (button) {
                    pressScale(button, 1);
                }
            });
        });
    }

    if (reduceMotion) {
        return;
    }

    const shake = () => {
        gsap.fromTo(
            card,
            { x: -6 },
            { x: 0, duration: 0.4, ease: 'elastic.out(1, 0.35)' }
        );
    };

    const observer = new MutationObserver((mutations) => {
        const hasNewAlert = mutations.some((mutation) =>
            Array.from(mutation.addedNodes).some(
                (node) =>
                    node.nodeType === Node.ELEMENT_NODE &&
                    (node.matches?.('[role="alert"], .fi-fo-field-wrp-error-message') ||
                        node.querySelector?.('[role="alert"], .fi-fo-field-wrp-error-message'))
            )
        );
        if (hasNewAlert) {
            shake();
        }
    });

    observer.observe(widget, { childList: true, subtree: true });
}
