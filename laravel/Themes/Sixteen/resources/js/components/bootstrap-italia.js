/**
 * Design Comuni Components - NO Bootstrap Italia runtime
 *
 * Uses Bootstrap JS (Tab, Collapse, Modal) for data-bs-toggle behaviors
 * + Alpine.js + Splide for remaining interactive elements.
 * Behaviors replicated from:
 * https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
 */

import { Tab, Collapse, Modal, Dropdown } from 'bootstrap';

// Espone Bootstrap per componenti ES module (es. map-lit.js)
window.bootstrap = window.bootstrap || {};
window.bootstrap.Modal = Modal;
window.bootstrap.Tab = Tab;
window.bootstrap.Collapse = Collapse;
window.bootstrap.Dropdown = Dropdown;

/* ============================================================
   BOOTSTRAP — Tab, Collapse, Modal initialization
   ============================================================ */
function initBootstrapComponents() {
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(el => new Tab(el));
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(el => new Collapse(el, { toggle: false }));
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(el => new Modal(el));
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
        if (el._bsDropdownInit) {
            return;
        }
        el._bsDropdownInit = true;
        Dropdown.getOrCreateInstance(el);
    });
}

/* ============================================================
   SPLIDE CAROUSEL — Calendar Events
   ============================================================ */
function initSplideCarousels() {
    const carousels = document.querySelectorAll('.splide:not([data-splide-initialized])');
    if (!carousels.length) return;

    // Splide is bundled with bootstrap-italia package — use it standalone
    import('@splidejs/splide').then(({ default: Splide }) => {
        carousels.forEach(el => {
            el.setAttribute('data-splide-initialized', 'true');

            const isFourCols = el.classList.contains('it-carousel-landscape-abstract-four-cols');
            const perPage   = isFourCols ? 4 : 3;

            new Splide(el, {
                type      : 'slide',
                perPage   : perPage,
                perMove   : 1,
                gap       : '1rem',
                padding   : { right: '1rem' },
                arrows    : true,
                pagination: false,
                breakpoints : {
                    992 : { perPage: 2 },
                    768 : { perPage: 1 },
                },
            }).mount();
        });
    }).catch(() => {
        // Splide not available — fall back to scrollable flex list (CSS already handles it)
        console.info('Splide not available; carousels use scrollable flex layout.');
    });
}

/* ============================================================
   HEADER — hamburger + language dropdown (Alpine.js data)
   ============================================================ */
function registerAlpineComponents() {
    if (typeof Alpine === 'undefined') return;

    // Mobile hamburger menu
    Alpine.data('navMenu', () => ({
        open: false,
        toggle() { this.open = !this.open; },
        close() { this.open = false; },
    }));

    // Language dropdown
    Alpine.data('langDropdown', () => ({
        open: false,
        toggle() { this.open = !this.open; },
        close() { this.open = false; },
    }));

    // Search modal (header search button)
    Alpine.data('searchModal', () => ({
        open: false,
        query: '',
        show()  { this.open = true;  document.body.style.overflow = 'hidden'; },
        hide()  { this.open = false; document.body.style.overflow = ''; },
        toggle(){ this.open ? this.hide() : this.show(); },
    }));

    // Multi-step Rating widget
    Alpine.data('ratingWidget', () => ({
        step   : 'stars',   // 'stars' | 'detail' | 'text' | 'done'
        stars  : 0,
        detail : null,
        text   : '',
        selectStar(n) {
            this.stars = n;
            if (n <= 3) {
                this.step = 'detail';
            } else {
                this.step = 'done';
                this.submit();
            }
        },
        selectDetail(val) {
            this.detail = val;
            this.step = 'text';
        },
        submit() {
            // dispatch event for Livewire/Alpine listeners
            this.$dispatch('rating-submitted', {
                stars : this.stars,
                detail: this.detail,
                text  : this.text,
            });
            this.step = 'done';
        },
        back() {
            if (this.step === 'text')   { this.step = 'detail'; }
            if (this.step === 'detail') { this.step = 'stars'; }
        },
    }));
}

/* ============================================================
   STICKY HEADER
   ============================================================ */
function initStickyHeader() {
    const header = document.querySelector('.it-header-wrapper');
    if (!header) return;

    const onScroll = () => {
        header.classList.toggle('header-sticky', window.scrollY > 80);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
}

/* ============================================================
   SKIP LINKS — accessibility
   ============================================================ */
function initSkipLinks() {
    document.querySelectorAll('.skiplink a').forEach(link => {
        link.addEventListener('focus',  () => link.classList.remove('sr-only'));
        link.addEventListener('blur',   () => link.classList.add('sr-only'));
    });
}

/* ============================================================
   NOTIFICATION DISMISS
   ============================================================ */
function initNotifications() {
    document.querySelectorAll('.notification [data-dismiss]').forEach(btn => {
        btn.addEventListener('click', () => {
            const notif = btn.closest('.notification');
            if (notif) notif.remove();
        });
    });
}

/* ============================================================
   BOOTSTRAP — close open dropdowns on Escape
   ============================================================ */
function initKeyboardNav() {
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        // Notify Alpine stores via custom event
        window.dispatchEvent(new CustomEvent('close-all-dropdowns'));
    });
}

/* ============================================================
   BOOTSTRAP — cookiebar
   ============================================================ */
function initCookiebar() {
    const bar = document.querySelector('.cookiebar');
    if (!bar) return;
    if (localStorage.getItem('cookiesAccepted')) { bar.remove(); return; }

    bar.querySelector('[data-accept]')?.addEventListener('click', function () {
        localStorage.setItem('cookiesAccepted', this.dataset.accept);
        bar.remove();
    });
}

/* ============================================================
   INIT
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    initBootstrapComponents();
    initSkipLinks();
    initCookiebar();
    initNotifications();
    initKeyboardNav();
    initStickyHeader();
    initSplideCarousels();
});

// Alpine components registered after alpine:init
document.addEventListener('alpine:init', () => {
    registerAlpineComponents();
});
