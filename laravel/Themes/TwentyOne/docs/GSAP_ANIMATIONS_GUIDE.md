# GSAP Animations - Theme TwentyOne

**Data**: 2026-03-23  
**Tema**: TwentyOne  
**GSAP Version**: 3.14+  
**Stato**: ✅ DOCUMENTATO

---

## ARCHITETTURA

### File Structure

```
Themes/TwentyOne/
├── resources/
│   ├── js/
│   │   ├── gsap-config.js         # GSAP configuration
│   │   ├── scroll-trigger-config.js # ScrollTrigger setup
│   │   ├── animations/
│   │   │   ├── hero.js            # Hero animations
│   │   │   ├── cards.js           # Card animations
│   │   │   └── sections.js        # Section animations
│   │   └── app.js                 # Entry point
│   └── css/
│       └── app.css                # GSAP styles
```

---

## GSAP CONFIGURATION

### gsap-config.js

```javascript
// File: resources/js/gsap-config.js
import { gsap } from 'gsap';

// Global config
gsap.config({
    force3D: true,      // GPU acceleration
    nullTargetWarn: false,
    trialWarn: false,
});

// prefers-reduced-motion
gsap.matchMedia().add('(prefers-reduced-motion: reduce)', () => {
    return {
        init: () => {
            gsap.globalTimeline.pause();
        },
    };
});

// Utility functions
export function createCountUpAnimation(selector, endValue, options = {}) {
    const element = document.querySelector(selector);
    if (!element) return;
    
    gsap.to(element, {
        textContent: endValue,
        duration: options.duration || 2,
        snap: { textContent: 1 },
        ease: options.ease || 'power2.out',
        ...options,
    });
}

export { gsap };
```

### scroll-trigger-config.js

```javascript
// File: resources/js/scroll-trigger-config.js
import { gsap } from './gsap-config.js';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Register plugin
gsap.registerPlugin(ScrollTrigger);

// prefers-reduced-motion
gsap.matchMedia().add('(prefers-reduced-motion: reduce)', () => {
    return {
        init: () => {
            ScrollTrigger.getAll().forEach(trigger => trigger.kill());
        },
    };
});

// Utility functions
export function createFadeInAnimation(selector, options = {}) {
    gsap.utils.toArray(selector).forEach(element => {
        gsap.from(element, {
            opacity: 0,
            y: 30,
            duration: options.duration || 0.8,
            ease: options.ease || 'power2.out',
            scrollTrigger: {
                trigger: element,
                start: 'top bottom-=100',
                toggleActions: 'play none none none',
                ...options.scrollTrigger,
            },
        });
    });
}

export function createSlideUpAnimation(selector, options = {}) {
    gsap.utils.toArray(selector).forEach(element => {
        gsap.from(element, {
            y: 50,
            opacity: 0,
            duration: options.duration || 0.6,
            ease: options.ease || 'power3.out',
            scrollTrigger: {
                trigger: element,
                start: 'top bottom-=50',
                toggleActions: 'play none none none',
                ...options.scrollTrigger,
            },
        });
    });
}

export { ScrollTrigger };
```

### app.js

```javascript
// File: resources/js/app.js
import { gsap } from './gsap-config.js';
import { ScrollTrigger } from './scroll-trigger-config.js';
import { initHeroAnimations } from './animations/hero.js';
import { initCardAnimations } from './animations/cards.js';
import { initSectionAnimations } from './animations/sections.js';

// Initialize all animations
document.addEventListener('DOMContentLoaded', () => {
    initHeroAnimations();
    initCardAnimations();
    initSectionAnimations();
});

// Export for external use
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;
```

---

## HERO ANIMATIONS

### Hero Cinematic

```javascript
// File: resources/js/animations/hero.js
import { gsap } from '../gsap-config.js';

export function initHeroAnimations() {
    const hero = document.querySelector('.hero');
    if (!hero) return;
    
    const title = hero.querySelector('.hero-title');
    const subtitle = hero.querySelector('.hero-subtitle');
    const cta = hero.querySelector('.hero-cta');
    const stats = hero.querySelectorAll('.hero-stat');
    
    const tl = gsap.timeline({ delay: 0.5 });
    
    tl.from(title, {
        y: 50,
        opacity: 0,
        duration: 1,
        ease: 'power3.out',
    })
    .from(subtitle, {
        y: 30,
        opacity: 0,
        duration: 0.8,
        ease: 'power3.out',
    }, '-=0.6')
    .from(cta, {
        y: 20,
        opacity: 0,
        duration: 0.6,
        ease: 'power3.out',
    }, '-=0.4')
    .from(stats, {
        y: 40,
        opacity: 0,
        duration: 0.6,
        stagger: 0.1,
        ease: 'power3.out',
    }, '-=0.4');
}
```

---

## CARD ANIMATIONS

### Card Entrance

```javascript
// File: resources/js/animations/cards.js
import { gsap } from '../gsap-config.js';

export function initCardAnimations() {
    const cards = document.querySelectorAll('.card-kinetic');
    if (!cards.length) return;
    
    gsap.from(cards, {
        y: 50,
        opacity: 0,
        duration: 0.6,
        stagger: {
            each: 0.1,
            from: 'top',
        },
        ease: 'power3.out',
    });
}
```

### Card Hover

```javascript
// File: resources/js/animations/cards.js
import { gsap } from '../gsap-config.js';

export function initCardHover() {
    const cards = document.querySelectorAll('.card-kinetic');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, {
                y: -6,
                scale: 1.01,
                duration: 0.3,
                ease: 'power2.out',
            });
        });
        
        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                y: 0,
                scale: 1,
                duration: 0.3,
                ease: 'power2.out',
            });
        });
    });
}
```

---

## SECTION ANIMATIONS

### Section Reveal

```javascript
// File: resources/js/animations/sections.js
import { gsap } from '../gsap-config.js';
import { ScrollTrigger } from '../scroll-trigger-config.js';

export function initSectionAnimations() {
    const sections = document.querySelectorAll('.section');
    
    sections.forEach(section => {
        const header = section.querySelector('.section-header');
        const content = section.querySelector('.section-content');
        
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: section,
                start: 'top bottom-=100',
                toggleActions: 'play none none none',
            },
        });
        
        if (header) {
            tl.from(header, {
                y: 30,
                opacity: 0,
                duration: 0.8,
                ease: 'power3.out',
            });
        }
        
        if (content) {
            tl.from(content, {
                y: 20,
                opacity: 0,
                duration: 0.6,
                ease: 'power3.out',
            }, '-=0.4');
        }
    });
}
```

---

## CSS INTEGRATION

### app.css

```css
/* File: resources/css/app.css */

/* Kinetic animations */
.card-kinetic {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1),
                box-shadow 0.3s ease-out,
                border-color 0.2s ease-out;
    will-change: transform, box-shadow;
}

.card-kinetic:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: 0 20px 60px -20px rgba(56, 189, 248, 0.35);
    border-color: rgba(56, 189, 248, 0.35);
}

/* Probability bar */
.probability-bar-animated {
    transition: width 1s ease-out;
    animation: fadeInUp 0.5s ease-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .card-kinetic,
    .probability-bar-animated {
        animation: none !important;
        transition: none !important;
        will-change: auto;
    }
}
```

---

## PERFORMANCE

### Optimization

1. **Lazy load GSAP**
   ```javascript
   // Load GSAP only when needed
   const loadGSAP = async () => {
       const { gsap } = await import('./gsap-config.js');
       return gsap;
   };
   
   // Usage
   const hero = document.querySelector('.hero');
   if (hero) {
       const gsap = await loadGSAP();
       // Initialize animations
   }
   ```

2. **Batch animations**
   ```javascript
   // ✅ CORRETTO
   gsap.from('.card', { stagger: 0.1 });
   
   // ❌ SBAGLIATO
   cards.forEach(card => gsap.from(card, { delay: 0.1 }));
   ```

3. **Kill on cleanup**
   ```javascript
   // Cleanup before component unmount
   ScrollTrigger.getAll().forEach(trigger => trigger.kill());
   gsap.globalTimeline.kill();
   ```

---

## RIFERIMENTI

- `docs/project/GSAP_V3_COMPLETE_GUIDE.md`
- `docs/project/GSAP_SCROLLTRIGGER_ADVANCED.md`
- `docs/project/GSAP_PERFORMANCE_ACCESSIBILITY.md`
- `Modules/Predict/docs/GSAP_ANIMATIONS_GUIDE.md`

---

**Maintained By**: AI Agents Team  
**Last Review**: 2026-03-23  
**Status**: ✅ DOCUMENTATO
