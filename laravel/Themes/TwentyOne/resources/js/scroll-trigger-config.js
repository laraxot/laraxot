/**
 * ScrollTrigger Configuration
 * 
 * GSAP ScrollTrigger per animazioni basate sullo scroll
 * 
 * @see https://greensock.com/scrolltrigger/
 * @see https://greensock.com/docs/v3/Plugins/ScrollTrigger
 * 
 * @package TwentyOne Theme
 * @author Base Predict Fila5
 * @license MIT
 */

import { gsap } from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

// ============================================
// Register ScrollTrigger Plugin
// ============================================

/**
 * Registra il plugin ScrollTrigger
 * 
 * DEVE essere chiamato prima di usare ScrollTrigger
 */
gsap.registerPlugin(ScrollTrigger);

// ============================================
// ScrollTrigger Global Configuration
// ============================================

/**
 * Configura le impostazioni globali di ScrollTrigger
 * 
 * - autoRefreshEvents: Eventi che triggerano il refresh
 * - refreshPriority: Priorità di refresh
 */
ScrollTrigger.config({
    autoRefreshEvents: 'visibilitychange,DOMContentLoaded,load',
    refreshPriority: 0,
});

/**
 * Imposta la tolleranza per prefers-reduced-motion
 * 
 * Rispetta le preferenze di accessibilità dell'utente
 */
gsap.matchMedia().add('(prefers-reduced-motion: reduce)', () => {
    // Quando l'utente preferisce moto ridotto, disabilita ScrollTrigger
    return {
        init: () => {
            ScrollTrigger.getAll().forEach(trigger => trigger.kill());
        }
    };
});

// ============================================
// Utility Functions
// ============================================

/**
 * Verifica se ScrollTrigger è disponibile
 * 
 * @returns {boolean} true se ScrollTrigger è caricato
 */
export function isScrollTriggerAvailable() {
    return typeof ScrollTrigger !== 'undefined';
}

/**
 * Crea un'animazione triggerata dallo scroll
 * 
 * @param {string} selector - Selettore CSS dell'elemento
 * @param {object} animation - Oggetto con i parametri dell'animazione
 * @returns {ScrollTrigger} Instance di ScrollTrigger
 */
export function createScrollTriggerAnimation(selector, animation = {}) {
    const {
        trigger = selector,
        start = 'top 80%',
        end = 'bottom 20%',
        scrub = false,
        toggleClass = '',
        onEnter = null,
        onLeave = null,
        onEnterBack = null,
        onLeaveBack = null,
        ...gsapProps
    } = animation;

    if (!gsapProps.length && !gsapProps.x && !gsapProps.y && !gsapProps.opacity && !gsapProps.scale) {
        console.warn('[ScrollTrigger] Nessuna proprietà GSAP specificata per', selector);
        return null;
    }

    return ScrollTrigger.create({
        trigger,
        start,
        end,
        scrub,
        toggleClass,
        onEnter,
        onLeave,
        onEnterBack,
        onLeaveBack,
        animation: gsap.fromTo(selector, 
            { ...gsapProps.from || {} },
            { ...gsapProps.to || gsapProps }
        ),
    });
}

/**
 * Crea un'animazione "reveal" per blocchi di contenuto
 * 
 * @param {string} selector - Selettore CSS degli elementi
 * @param {object} options - Opzioni (stagger, threshold, etc.)
 * @returns {ScrollTrigger[]} Array di istanze ScrollTrigger
 */
export function createRevealAnimation(selector, options = {}) {
    const {
        stagger = 0.1,
        threshold = 0.1,
        rootMargin = '0px 0px -10% 0px',
        from = { opacity: 0, y: 60 },
        to = { opacity: 1, y: 0 },
        duration = 0.8,
        ease = 'power3.out',
    } = options;

    const elements = document.querySelectorAll(selector);

    if (!elements.length) {
        return [];
    }

    const triggers = [];

    elements.forEach((element, index) => {
        const trigger = ScrollTrigger.create({
            trigger: element,
            start: 'top 85%',
            toggleClass: 'reveal-kinetic-visible',
            onEnter: () => {
                gsap.fromTo(element,
                    { ...from },
                    {
                        ...to,
                        duration,
                        ease,
                        delay: stagger * index,
                    }
                );
            },
        });

        triggers.push(trigger);
    });

    return triggers;
}

/**
 * Crea un'animazione "fade-in" semplice
 * 
 * @param {string} selector - Selettore CSS degli elementi
 * @param {object} options - Opzioni (duration, delay, etc.)
 */
export function createFadeInAnimation(selector, options = {}) {
    const {
        duration = 1,
        delay = 0,
        ease = 'power2.out',
    } = options;

    if (!areAnimationsEnabled()) {
        document.querySelectorAll(selector).forEach(el => {
            el.style.opacity = 1;
        });
        return;
    }

    gsap.fromTo(selector,
        { opacity: 0 },
        {
            opacity: 1,
            duration,
            delay,
            ease,
            scrollTrigger: {
                trigger: selector,
                start: 'top 85%',
            }
        }
    );
}

/**
 * Crea un'animazione "slide-up"
 * 
 * @param {string} selector - Selettore CSS degli elementi
 * @param {object} options - Opzioni (y, duration, etc.)
 */
export function createSlideUpAnimation(selector, options = {}) {
    const {
        y = 100,
        duration = 1,
        delay = 0,
        ease = 'power3.out',
        stagger = 0,
    } = options;

    if (!areAnimationsEnabled()) {
        document.querySelectorAll(selector).forEach(el => {
            el.style.transform = 'translateY(0)';
            el.style.opacity = 1;
        });
        return;
    }

    gsap.fromTo(selector,
        { opacity: 0, y: y },
        {
            opacity: 1,
            y: 0,
            duration,
            delay,
            stagger,
            ease,
            scrollTrigger: {
                trigger: selector,
                start: 'top 85%',
            }
        }
    );
}

/**
 * Verifica se le animazioni sono abilitate
 * 
 * @returns {boolean} true se animazioni sono permesse
 */
function areAnimationsEnabled() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    return !prefersReducedMotion && typeof gsap !== 'undefined';
}

// ============================================
// Export
// ============================================

export { ScrollTrigger };
export default ScrollTrigger;
