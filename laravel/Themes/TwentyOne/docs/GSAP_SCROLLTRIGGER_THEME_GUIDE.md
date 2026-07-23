# 🎬 GSAP & SCROLLTRIGGER - Theme Implementation

**Data**: 2026-03-23  
**Version**: GSAP 3.14.2, ScrollTrigger 3.14.2  
**Status**: ✅ THEME REFERENCE

---

## 🏗 ARCHITETTURA TEMA

### File Structure

```
Themes/TwentyOne/
├── resources/
│   ├── js/
│   │   ├── app.js                    ← Entry point
│   │   ├── gsap-config.js            ← GSAP Core configuration
│   │   ├── scroll-trigger-config.js  ← ScrollTrigger plugin
│   │   ├── particles.js              ← Cinematic particles
│   │   ├── dark-mode.js              ← Dark mode toggle
│   │   └── components/
│   │       └── tradingview-chart.js  ← TradingView (usa window.gsap)
│   └── css/
│       ├── app.css                   ← CSS animations
│       └── components/
│           └── filament-widgets.css  ← Widget styling
└── docs/
    └── GSAP_SCROLLTRIGGER_CONFIGURATION.md
```

---

## ⚙️ CONFIGURAZIONE

### 1. gsap-config.js

```javascript
/**
 * GSAP Core Configuration
 * 
 * Configura GSAP con impostazioni globali e utility functions.
 * Esporta gsap per uso in altri moduli.
 */

import { gsap } from 'gsap';

// ============================================
// Global Configuration
// ============================================

gsap.config({
    force3D: true,        // GPU acceleration
    nullTargetWarn: false, // Disable warnings
    trialWarn: false,     // Disable trial warnings
});

// ============================================
// prefers-reduced-motion Support
// ============================================

gsap.matchMedia().add('(prefers-reduced-motion: reduce)', () => {
    return {
        init: () => {
            // Pause all animations
            gsap.globalTimeline.pause();
            
            // Set final state for all elements
            gsap.set('*', { 
                clearProps: "all" 
            });
        }
    };
});

// ============================================
// Utility Functions
// ============================================

/**
 * Crea animazione count-up per numeri
 * 
 * @param {string} selector - Selettore elemento
 * @param {number} endValue - Valore finale
 * @param {Object} options - Opzioni (duration, prefix, suffix)
 */
export function createCountUpAnimation(selector, endValue, options = {}) {
    const element = document.querySelector(selector);
    if (!element) {
        console.warn(`CountUp: Element not found: ${selector}`);
        return;
    }

    const duration = options.duration || 2;
    const prefix = options.prefix || '';
    const suffix = options.suffix || '';
    const easing = options.ease || 'power2.out';

    gsap.to(element, {
        innerText: endValue,
        duration,
        snap: { innerText: 1 },
        ease: easing,
        onUpdate: function() {
            const value = Math.ceil(this.targets()[0].innerText);
            element.textContent = prefix + value.toLocaleString() + suffix;
        }
    });
}

/**
 * Crea animazione fade-in
 * 
 * @param {string} selector - Selettore elemento
 * @param {Object} options - Opzioni (duration, delay, yOffset)
 */
export function createFadeInAnimation(selector, options = {}) {
    const duration = options.duration || 0.8;
    const delay = options.delay || 0;
    const yOffset = options.yOffset || 30;

    gsap.from(selector, {
        opacity: 0,
        y: yOffset,
        duration,
        delay,
        ease: 'power2.out'
    });
}

/**
 * Crea animazione slide-up
 * 
 * @param {string} selector - Selettore elemento
 * @param {Object} options - Opzioni (duration, delay, yOffset)
 */
export function createSlideUpAnimation(selector, options = {}) {
    const duration = options.duration || 1;
    const delay = options.delay || 0;
    const yOffset = options.yOffset || 50;

    gsap.from(selector, {
        opacity: 0,
        y: yOffset,
        duration,
        delay,
        ease: 'power3.out'
    });
}

// ============================================
// Export
// ============================================

export { gsap };
```

### 2. scroll-trigger-config.js

```javascript
/**
 * ScrollTrigger Plugin Configuration
 * 
 * Registra ScrollTrigger e configura utility functions.
 * Esporta ScrollTrigger per uso in altri moduli.
 */

import { gsap } from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

// ============================================
// Register Plugin
// ============================================

gsap.registerPlugin(ScrollTrigger);

// ============================================
// prefers-reduced-motion Support
// ============================================

gsap.matchMedia().add('(prefers-reduced-motion: reduce)', () => {
    return {
        init: () => {
            // Kill all ScrollTriggers
            ScrollTrigger.getAll().forEach(trigger => trigger.kill());
            
            // Set final state
            gsap.set('[data-kinetic-block]', { 
                opacity: 1, 
                y: 0 
            });
        }
    };
});

// ============================================
// Utility Functions
// ============================================

/**
 * Crea animazione reveal allo scroll
 * 
 * @param {string} selector - Selettore elemento
 * @param {Object} options - Opzioni (start, duration)
 */
export function createRevealAnimation(selector, options = {}) {
    const start = options.start || 'top 85%';
    const duration = options.duration || 0.8;

    gsap.from(selector, {
        opacity: 0,
        y: 50,
        duration,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: selector,
            start,
            toggleActions: 'play none none none'
        }
    });
}

/**
 * Crea animazione pin allo scroll
 * 
 * @param {string} selector - Selettore elemento
 * @param {Object} options - Opzioni (start, end, scrub)
 */
export function createPinAnimation(selector, options = {}) {
    const start = options.start || 'top top';
    const end = options.end || '+=' + window.innerHeight;
    const scrub = options.scrub || 1;

    gsap.to(selector, {
        scale: 0.8,
        scrollTrigger: {
            trigger: selector,
            start,
            end,
            pin: true,
            pinSpacing: true,
            scrub
        }
    });
}

// ============================================
// Export
// ============================================

export { ScrollTrigger };
```

### 3. app.js

```javascript
/**
 * Entry Point - Theme JavaScript
 * 
 * Importa e inizializza tutti i moduli JavaScript.
 * Esporta funzioni globali per uso in blade e moduli.
 */

import "flowbite";

import Swiper from "swiper/bundle";
window.Swiper = Swiper;

// Alpine.js is initialized by Livewire/Filament scripts in this theme layout.
// Do not start Alpine manually here, otherwise Filament table helpers can be undefined.

import "./custom.js";
import { initCinematicParticles } from "./particles.js";
import { initDarkMode } from "./dark-mode.js";

// ============================================
// GSAP & ScrollTrigger Configuration
// ============================================

import { gsap } from "./gsap-config.js";
import { ScrollTrigger } from "./scroll-trigger-config.js";

// Export per uso globale (per moduli che usano window.gsap pattern)
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

// ============================================
// TradingView Lightweight Charts
// ============================================

import { createTradingViewChart, formatChartData, initTradingViewCharts } from "./components/tradingview-chart.js";

// Export per uso globale (per moduli che usano window.gsap pattern)
window.createTradingViewChart = createTradingViewChart;
window.formatChartData = formatChartData;

// ============================================
// Kinetic Web Design - Interactions
// ============================================

/**
 * Inizializza reveal blocks allo scroll
 */
const revealKineticBlocks = () => {
    const blocks = document.querySelectorAll('[data-kinetic-block].reveal-kinetic');

    if (!blocks.length) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        blocks.forEach((block) => block.classList.add('visible'));
        return;
    }

    const observer = new IntersectionObserver((entries, currentObserver) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('visible');
            currentObserver.unobserve(entry.target);
        });
    }, {
        rootMargin: '0px 0px -8% 0px',
        threshold: 0.12,
    });

    blocks.forEach((block) => observer.observe(block));
};

/**
 * Inizializza counter animations
 */
const initCounterAnimation = () => {
    const counters = document.querySelectorAll('[data-kinetic-counter]');

    if (!counters.length) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const animateCounter = (element) => {
        const target = parseInt(element.dataset.counterTarget, 10) || 0;
        const duration = parseInt(element.dataset.counterDuration, 10) || 2000;
        const counterValue = element.querySelector('.counter-value');

        if (!counterValue) {
            return;
        }

        if (prefersReducedMotion) {
            counterValue.textContent = target.toLocaleString();
            return;
        }

        const prefix = counterValue.dataset.prefix || '';
        const suffix = counterValue.dataset.suffix || '';
        const startTimestamp = performance.now();

        const step = (timestamp) => {
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const currentValue = Math.floor(target * easeOutQuart);

            counterValue.textContent = prefix + currentValue.toLocaleString() + suffix;

            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };

        requestAnimationFrame(step);
    };

    if (prefersReducedMotion) {
        counters.forEach(counter => {
            const target = parseInt(counter.dataset.counterTarget, 10) || 0;
            const counterValue = counter.querySelector('.counter-value');
            if (counterValue) {
                counterValue.textContent = target.toLocaleString();
            }
        });
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, {
        rootMargin: '0px 0px -10% 0px',
        threshold: 0.1,
    });

    counters.forEach((counter) => observer.observe(counter));
};

/**
 * Inizializza antigravity fields (mouse interaction)
 */
const initAntigravityFields = () => {
    const fields = document.querySelectorAll('[data-antigravity-field]');

    if (!fields.length) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    fields.forEach((field) => {
        const resetPointer = () => {
            field.style.setProperty('--ag-pointer-x', '50%');
            field.style.setProperty('--ag-pointer-y', '50%');
        };

        if (prefersReducedMotion) {
            resetPointer();
            return;
        }

        const updatePointer = (event) => {
            const rect = field.getBoundingClientRect();
            const relativeX = ((event.clientX - rect.left) / rect.width) * 100;
            const relativeY = ((event.clientY - rect.top) / rect.height) * 100;

            field.style.setProperty('--ag-pointer-x', `${Math.max(0, Math.min(100, relativeX))}%`);
            field.style.setProperty('--ag-pointer-y', `${Math.max(0, Math.min(100, relativeY))}%`);
        };

        resetPointer();
        field.addEventListener('pointermove', updatePointer, { passive: true });
        field.addEventListener('pointerleave', resetPointer, { passive: true });
    });
};

/**
 * Inizializza GSAP count-up animations
 */
const initGSAPCountUp = () => {
    if (!gsap) {
        return;
    }

    const countUpElements = document.querySelectorAll('.count-up');

    if (!countUpElements.length) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        countUpElements.forEach(el => {
            const target = parseInt(el.getAttribute('data-target'));
            el.innerText = target;
        });
        return;
    }

    countUpElements.forEach(el => {
        const target = parseInt(el.getAttribute('data-target'));

        gsap.to(el, {
            innerText: target,
            duration: 2,
            scrollTrigger: {
                trigger: el,
                start: 'top 80%',
            },
            snap: { innerText: 1 },
            ease: 'power2.out',
            onUpdate: function() {
                el.innerText = Math.ceil(this.targets()[0].innerText);
            }
        });
    });
};

// ============================================
// Initialize on DOM Ready
// ============================================

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        revealKineticBlocks();
        initAntigravityFields();
        initCinematicParticles();
        initCounterAnimation();
        initGSAPCountUp();
        initDarkMode();
        initTradingViewCharts();
    }, { once: true });
} else {
    revealKineticBlocks();
    initAntigravityFields();
    initCinematicParticles();
    initCounterAnimation();
    initGSAPCountUp();
    initDarkMode();
    initTradingViewCharts();
}
```

---

## 🎨 CSS ANIMATIONS

### app.css

```css
/* ============================================
   Cinematic Animations
   ============================================ */

/* Floating particles */
@keyframes kinetic-float {
    0%, 100% {
        transform: translateY(0) translateX(0);
    }
    25% {
        transform: translateY(-20px) translateX(10px);
    }
    50% {
        transform: translateY(-10px) translateX(-10px);
    }
    75% {
        transform: translateY(-30px) translateX(5px);
    }
}

.animate-kinetic-float {
    animation: kinetic-float 8s ease-in-out infinite;
}

/* Blob animation */
@keyframes blob {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

/* Reveal kinetic blocks */
.reveal-kinetic {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.reveal-kinetic.visible {
    opacity: 1;
    transform: translateY(0);
}

/* prefers-reduced-motion */
@media (prefers-reduced-motion: reduce) {
    .animate-kinetic-float,
    .animate-blob,
    .reveal-kinetic {
        animation: none;
        transition: none;
        opacity: 1;
        transform: none;
    }
}
```

---

## ✅ CHECKLIST IMPLEMENTAZIONE

Prima di commitare animazioni nel tema:

### Performance
- [ ] ✅ Usa transform (GPU), NO layout properties (CPU)
- [ ] ✅ will-change appropriato
- [ ] ✅ Batch animations con stagger
- [ ] ✅ NO animazioni infinite senza pause

### Accessibilità
- [ ] ✅ respects prefers-reduced-motion
- [ ] ✅ Fallback statico per reduced-motion
- [ ] ✅ NO animazioni che causano nausea

### Coerenza
- [ ] ✅ Timing uniformi (0.3s, 0.6s, 1s)
- [ ] ✅ Easing coerenti (power2.out, power3.out)
- [ ] ✅ Stesso stile in tutto il tema

### Responsive
- [ ] ✅ MatchMedia per desktop/mobile
- [ ] ✅ Test su tutti i breakpoints
- [ ] ✅ NO animazioni pesanti su mobile

### Quality Gate
- [ ] ✅ NO errori console
- [ ] ✅ Lighthouse Performance > 90
- [ ] ✅ Lighthouse Accessibility > 90
- [ ] ✅ 60 FPS su desktop e mobile

---

## 📖 DOCUMENTAZIONE CORRELATA

### Progetto
- [GSAP_V3_COMPLETE_GUIDE.md](../../docs/project/GSAP_V3_COMPLETE_GUIDE.md)
- [CINEMATIC_PARTICLES_EFFECTS.md](../../docs/project/CINEMATIC_PARTICLES_EFFECTS.md)

### Modulo Predict
- [GSAP_ANIMATION_GUIDE.md](../../Modules/Predict/docs/GSAP_ANIMATION_GUIDE.md)

### Ufficiale
- [GSAP v3 Docs](https://gsap.com/docs/v3/)
- [ScrollTrigger Docs](https://gsap.com/docs/v3/Plugins/ScrollTrigger/)

---

**Status**: ✅ THEME REFERENCE  
**Priority**: CRITICAL  
**Application**: ALL THEME ANIMATIONS
