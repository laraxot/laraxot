# GSAP & ScrollTrigger Configuration

**Theme**: TwentyOne  
**Version**: 1.0.0  
**Last Updated**: 2026-03-23  
**Status**: ✅ OPERATIONAL

---

## 📚 Overview

Questo documento descrive la configurazione di **GSAP (GreenSock Animation Platform)** e **ScrollTrigger** nel tema TwentyOne per Base Predict Fila5.

### Cos'è GSAP?

GSAP è una libreria JavaScript ad alte prestazioni per animazioni CSS/JavaScript. È lo standard del settore per animazioni web professionali.

**Caratteristiche**:
- ⚡ **Performance**: GPU-accelerated, 60 FPS
- 🎯 **Precision**: Controllo frame-perfect
- 🔄 **Cross-browser**: Funziona su tutti i browser moderni
- ♿ **Accessible**: Rispetta `prefers-reduced-motion`
- 📦 **Modular**: Importa solo ciò che ti serve

### Cos'è ScrollTrigger?

ScrollTrigger è un plugin GSAP che triggera animazioni basate sulla posizione di scroll dell'utente.

**Use Cases**:
- Fade-in elementi quando entrano nel viewport
- Animazioni "scrubbate" (legate allo scroll)
- Pinning sezioni durante lo scroll
- Trigger di timeline complesse

---

## 📦 Installazione

### NPM Dependencies

```json
{
  "dependencies": {
    "gsap": "^3.14.2"
  }
}
```

**Nota**: ScrollTrigger è **incluso** in GSAP 3, non richiede installazione separata.

### Installazione

```bash
cd laravel
npm install
npm run dev  # Sviluppo
npm run build # Production
```

---

## 🏗️ Architettura File

```
Themes/TwentyOne/resources/js/
├── app.js                      # Entry point principale
├── gsap-config.js              # Configurazione GSAP Core
├── scroll-trigger-config.js    # Configurazione ScrollTrigger Plugin
├── particles.js                # Particles cinematici (CSS-only)
├── dark-mode.js                # Dark mode toggle
├── custom.js                   # Custom scripts
└── cookie-consent.js           # GDPR cookie consent
```

### File Structure

#### 1. `gsap-config.js`

**Scopo**: Configurazione centrale di GSAP Core

**Contenuto**:
- Configurazione globale (`gsap.config()`)
- Match media per `prefers-reduced-motion`
- Utility functions:
  - `isGSAPAvailable()`
  - `areAnimationsEnabled()`
  - `createCountUpAnimation()`

**Export**:
```javascript
export { gsap };
export default gsap;
export function isGSAPAvailable() { ... }
export function areAnimationsEnabled() { ... }
export function createCountUpAnimation(selector, endValue, options) { ... }
```

#### 2. `scroll-trigger-config.js`

**Scopo**: Configurazione plugin ScrollTrigger

**Contenuto**:
- Registrazione plugin (`gsap.registerPlugin(ScrollTrigger)`)
- Configurazione globale (`ScrollTrigger.config()`)
- Match media per `prefers-reduced-motion`
- Utility functions:
  - `isScrollTriggerAvailable()`
  - `createScrollTriggerAnimation()`
  - `createRevealAnimation()`
  - `createFadeInAnimation()`
  - `createSlideUpAnimation()`

**Export**:
```javascript
export { ScrollTrigger };
export default ScrollTrigger;
export function isScrollTriggerAvailable() { ... }
export function createScrollTriggerAnimation(selector, animation) { ... }
export function createRevealAnimation(selector, options) { ... }
export function createFadeInAnimation(selector, options) { ... }
export function createSlideUpAnimation(selector, options) { ... }
```

#### 3. `app.js`

**Scopo**: Entry point principale, importa e inizializza tutto

**Import Order**:
```javascript
// 1. Vendor libraries
import "flowbite";
import Swiper from "swiper/bundle";

// 2. Theme scripts
import "./custom.js";
import { initCinematicParticles } from "./particles.js";
import { initDarkMode } from "./dark-mode.js";

// 3. GSAP & ScrollTrigger
import { gsap } from "./gsap-config.js";
import { ScrollTrigger } from "./scroll-trigger-config.js";

// 4. Export globale (opzionale)
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;
```

---

## 🎯 Utilizzo

### Import in Blade Components

```blade
{{-- I file sono già importati in app.js, GSAP è disponibile globalmente --}}

{{-- Esempio: Count-up animation --}}
<div class="stat-card">
    <span class="counter-value" data-prefix="" data-suffix="K">0</span>
</div>

{{-- Esempio: Reveal animation --}}
<div data-kinetic-block class="reveal-kinetic">
    <h2>Titolo che appare allo scroll</h2>
</div>
```

### Utilizzo JavaScript Diretto

```javascript
// Import da altri file JS
import { gsap, createCountUpAnimation } from './gsap-config.js';
import { ScrollTrigger, createFadeInAnimation } from './scroll-trigger-config.js';

// Count-up animation
createCountUpAnimation('.stat-number', 1000, {
    duration: 2,
    ease: 'power2.out',
    delay: 0.5
});

// Fade-in animation
createFadeInAnimation('.hero-section', {
    duration: 1.5,
    delay: 0.2
});

// Slide-up animation
createSlideUpAnimation('.market-card', {
    y: 80,
    duration: 1,
    stagger: 0.1
});

// Custom ScrollTrigger
gsap.from('.predict-card', {
    scrollTrigger: {
        trigger: '.predict-card',
        start: 'top 85%',
        toggleClass: 'visible'
    },
    opacity: 0,
    y: 60,
    duration: 0.8
});
```

### Esempi Pratici

#### 1. Statistiche Homepage (Count-up)

```blade
{{-- Homepage stats con count-up animation --}}
<div class="stats-grid">
    <div class="stat-item">
        <span class="counter-value" 
              data-prefix="" 
              data-suffix="+" 
              data-kinetic-counter 
              data-counter-target="50">0</span>
        <p>Mercati Attivi</p>
    </div>
    <div class="stat-item">
        <span class="counter-value" 
              data-prefix="€" 
              data-suffix="M" 
              data-kinetic-counter 
              data-counter-target="12">0</span>
        <p>Volume Scambiato</p>
    </div>
</div>
```

#### 2. Reveal Blocks (Scroll-triggered)

```blade
{{-- Blocchi che appaiono allo scroll --}}
<section data-kinetic-block class="reveal-kinetic">
    <h2>Come Funziona</h2>
    <p>Spiegazione della piattaforma</p>
</section>

<section data-kinetic-block class="reveal-kinetic">
    <h3>Feature Principali</h3>
    <ul>
        <li>Feature 1</li>
        <li>Feature 2</li>
        <li>Feature 3</li>
    </ul>
</section>
```

#### 3. Market Cards (Stagger Animation)

```javascript
// In custom.js o componente dedicato
import { gsap } from './gsap-config.js';
import { ScrollTrigger } from './scroll-trigger-config.js';

gsap.from('.market-card', {
    scrollTrigger: {
        trigger: '.markets-grid',
        start: 'top 80%',
    },
    opacity: 0,
    y: 60,
    duration: 0.8,
    stagger: 0.1, // Ritardo tra ogni card
    ease: 'power3.out'
});
```

---

## ♿ Accessibilità

### prefers-reduced-motion

GSAP e ScrollTrigger **rispettano automaticamente** le preferenze di accessibilità:

```css
/* CSS dell'utente */
@media (prefers-reduced-motion: reduce) {
    /* Le animazioni sono disabilitate */
}
```

```javascript
// Configurazione automatica in gsap-config.js
gsap.matchMedia().add('(prefers-reduced-motion: reduce)', () => {
    return {
        init: () => {
            gsap.globalTimeline.pause();
        }
    };
});

// Configurazione automatica in scroll-trigger-config.js
gsap.matchMedia().add('(prefers-reduced-motion: reduce)', () => {
    return {
        init: () => {
            ScrollTrigger.getAll().forEach(trigger => trigger.kill());
        }
    };
});
```

### Best Practices

1. ✅ **Sempre testare** con `prefers-reduced-motion: reduce` attivo
2. ✅ **Fornire fallback** statici per contenuti animati
3. ✅ **Non usare animazioni** per informazioni critiche
4. ✅ **Rispettare** le impostazioni di sistema dell'utente

---

## 🚀 Performance

### Ottimizzazioni Incluse

1. **GPU Acceleration**
   ```javascript
   gsap.config({
       force3D: true // Usa trasformazioni 3D per performance
   });
   ```

2. **Debounced Scroll Events**
   - ScrollTrigger usa internamente requestAnimationFrame
   - Events sono throttled automaticamente

3. **Lazy Initialization**
   - Animazioni inizializzate solo quando elementi sono nel viewport
   - Observer unobserve dopo prima animazione

### Best Practices

1. ✅ **Limitare** numero di animazioni simultanee (< 10)
2. ✅ **Usare** `will-change` con parsimonia
3. ✅ **Evitare** animazioni su `width`, `height`, `top`, `left`
4. ✅ **Preferire** `transform` e `opacity`
5. ✅ **Cleanup** ScrollTrigger in componenti Livewire/Volt

---

## 🐛 Troubleshooting

### GSAP non è definito

**Errore**: `ReferenceError: gsap is not defined`

**Soluzione**:
```javascript
// Assicurati che gsap-config.js sia importato PRIMA dell'uso
import { gsap } from './gsap-config.js';

// Oppure usa window.gsap (se esposto globalmente)
if (window.gsap) {
    window.gsap.to(...)
}
```

### ScrollTrigger non funziona

**Errore**: `ScrollTrigger is not defined` o animazioni non partono

**Soluzione**:
```javascript
// Verifica che ScrollTrigger sia registrato
import { ScrollTrigger } from './scroll-trigger-config.js';

// Controlla che il plugin sia registrato
console.log(ScrollTrigger); // Deve essere una funzione

// Verifica trigger selector
ScrollTrigger.create({
    trigger: '.mio-elemento', // Deve esistere nel DOM
    start: 'top 80%',
    onEnter: () => console.log('Enter!')
});
```

### Animazioni troppo veloci/lente

**Soluzione**: Regola `duration` e `ease`:

```javascript
gsap.to('.element', {
    duration: 2, // Secondi (default: 1)
    ease: 'power2.out', // 'linear', 'power1.out', 'elastic.out', etc.
    scrollTrigger: {
        trigger: '.element',
        start: 'top 85%', // Più alto = prima triggera
    }
});
```

### Conflitti con Livewire/Filament

**Problema**: Animazioni si resettano dopo aggiornamenti Livewire

**Soluzione**:
```javascript
// Re-inizializza dopo aggiornamenti Livewire
Livewire.on('componentUpdated', () => {
    ScrollTrigger.refresh();
    initGSAPCountUp();
    initCounterAnimation();
});

// O usa hook globale
document.addEventListener('livewire:navigated', () => {
    ScrollTrigger.refresh();
});
```

---

## 📖 Risorse

### Documentazione Ufficiale

- [GSAP Docs](https://greensock.com/docs/)
- [ScrollTrigger Docs](https://greensock.com/docs/v3/Plugins/ScrollTrigger)
- [GSAP Cheat Sheet](https://greensock.com/cheatsheet/)

### Esempi e Tutorial

- [GSAP CodePen Collection](https://codepen.io/collection/DPEage/)
- [ScrollTrigger Examples](https://codepen.io/collection/81d0c7f97b9a8afb906193008a85a5b9)
- [GSAP Forum](https://greensock.com/forums/)

### Performance

- [GSAP Performance Tips](https://greensock.com/docs/v3/FAQs#performance)
- [ScrollTrigger Best Practices](https://greensock.com/docs/v3/Plugins/ScrollTrigger#best-practices)

---

## 🧪 Testing Checklist

Prima di commitare animazioni GSAP:

- [ ] ✅ **Desktop**: Chrome, Firefox, Safari
- [ ] ✅ **Mobile**: iOS Safari, Chrome Android
- [ ] ✅ **prefers-reduced-motion**: Verifica disabilitazione
- [ ] ✅ **Performance**: Lighthouse > 90
- [ ] ✅ **Scroll**: Tutte le animazioni triggerano correttamente
- [ ] ✅ **Resize**: Animazioni si adattano a diverse viewport
- [ ] ✅ **Livewire**: Nessun conflitto dopo aggiornamenti
- [ ] ✅ **Console**: NO errori JavaScript

---

## 📝 Changelog

### 1.0.0 (2026-03-23)

- ✅ Installazione GSAP ^3.14.2 via npm
- ✅ Creazione `gsap-config.js` (configurazione core)
- ✅ Creazione `scroll-trigger-config.js` (plugin ScrollTrigger)
- ✅ Aggiornamento `app.js` con import corretti
- ✅ Documentazione completa in `Themes/TwentyOne/docs/`
- ✅ Accessibilità: `prefers-reduced-motion` support
- ✅ Performance: GPU acceleration, lazy init
- ✅ Utility functions per animazioni comuni

---

**Maintained By**: AI Agents Team  
**Review Cycle**: Ogni modifica a file JS  
**Next Milestone**: Animazioni ordine book e trading form
