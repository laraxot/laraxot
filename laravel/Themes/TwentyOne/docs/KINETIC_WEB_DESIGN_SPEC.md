# SPEC: Kinetic Web Design — Motion as Central Design Element

> **Data**: 2026-03-18  
> **Fonte**: [Berger Team - Kinetic Web Design](https://www.berger.team/it/website/kinetisches-webdesign-bewegung-als-zentrales-designelement/)  
> **Stato**: ⏳ In sviluppo  
> **Priorità**: Alta

---

## 1. Vision & Goals

### Obiettivo
Trasformare il sito Base Predict in un'esperienza dinamica e interattiva che:
- **Cattura l'attenzione** con animazioni mirate
- **Guida l'utente** attraverso feedback visivo immediato
- **Aumenta l'engagement** con transizioni fluide
- **Rafforza il brand** con motion design consistente

### Benefici Attesi (da Berger)
| Benefici | Impatto |
|----------|---------|
| **Maggiore interazione** | Utenti restano più a lungo, interagiscono di più |
| **Migliore comprensibilità** | Idee e processi complessi resi tangibili |
| **Presenza unica** | Sito memorabile, distintivo dalla concorrenza |

---

## 2. Kinetic Web Design Principles (da Berger)

### 2.1 User Experience Through Motion

```
Animazioni fluide → Orientamento e contesto → Gestione attenzione → Feedback immediato
```

### 2.2 Best Practices

| Pratica | Implementazione |
|---------|----------------|
| **Utilità** | Animazioni con funzione, non decorative |
| **Performance** | Ottimizzazione, no rallentamenti |
| **Consistenza** | Coerenza in ogni aspetto |
| **Usabilità** | Brevi e chiare, non confuse |

---

## 3. Animation Techniques (da Berger)

### 3.1 CSS Animations (Leggere, veloci)

```css
/* Effetti hover */
.transition-all.duration-200
.transition-colors
.transition-transform

/* Keyframes base */
@keyframes fadeIn
@keyframes slideUp
@keyframes pulse
```

**Usare per**:
- Hover effects su bottoni e link
- Transizioni colore/dimensione
- Entrata elementi

### 3.2 JavaScript Animations (Complessi)

Per animazioni avanzate considerare:
- **GSAP** (GreenSock) per timeline animations
- **Parallax scrolling** per profondità
- **Scroll-triggered animations** per reveal

### 3.3 Visual Feedback

```
Click bottone → Risposta immediata
Hover elemento → Evidenziazione
Loading → Indicatore progress
```

---

## 4. Componenti da Animare

### 4.1 Prediction Cards (Predict Table)

**Stato Attuale**:
- `hover:shadow-lg hover:border-indigo-200` ✓
- `transition-all duration-200` ✓

**Miglioramenti**:
```css
/* Hover transform */
.predict-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -8px rgba(99, 102, 241, 0.25);
}

/* Scale on hover */
.predict-card:hover .card-action {
    transform: scale(1.05);
}
```

### 4.2 Probability Bars

**Stato Attuale**:
- `transition-all duration-500` ✓

**Miglioramenti**:
```css
/* Animate on load */
.probability-bar {
    animation: slideInWidth 0.8s ease-out forwards;
}

/* Color pulse for high activity */
.probability-bar.high-activity {
    animation: pulse-glow 2s ease-in-out infinite;
}
```

### 4.3 Buttons & CTAs

**Stato Attuale**: Base transition

**Miglioramenti**:
```css
/* Press effect */
.btn-primary:active {
    transform: scale(0.97);
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
}

/* Ripple effect on click */
.btn-primary::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
    transform: scale(0);
    transition: transform 0.5s;
}
```

### 4.4 Page Transitions

**Implementare**:
```css
/* Page enter */
.page-enter {
    animation: fadeSlideUp 0.4s ease-out;
}

/* Staggered list items */
.list-item:nth-child(1) { animation-delay: 0ms; }
.list-item:nth-child(2) { animation-delay: 50ms; }
.list-item:nth-child(3) { animation-delay: 100ms; }
```

### 4.5 Scroll Animations

```css
/* Reveal on scroll */
.reveal-on-scroll {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s, transform 0.6s;
}

.reveal-on-scroll.visible {
    opacity: 1;
    transform: translateY(0);
}
```

### 4.6 Loading States

```css
/* Skeleton shimmer */
.skeleton {
    background: linear-gradient(
        90deg,
        #f0f0f0 25%,
        #e0e0e0 50%,
        #f0f0f0 75%
    );
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}
```

---

## 5. Keyframes Library

```css
/* Fade In */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Slide Up */
@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Scale In */
@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

/* Pulse Glow */
@keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 5px rgba(99, 102, 241, 0.3); }
    50% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.6); }
}

/* Shimmer (Loading) */
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Float */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* Bounce */
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
```

---

## 6. Motion Tokens (Tailwind Custom)

```js
// tailwind.config.js
module.exports = {
    theme: {
        extend: {
            animation: {
                'fade-in': 'fadeIn 0.4s ease-out',
                'slide-up': 'slideUp 0.5s ease-out',
                'scale-in': 'scaleIn 0.3s ease-out',
                'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
                'shimmer': 'shimmer 1.5s infinite',
                'float': 'float 3s ease-in-out infinite',
                'bounce-subtle': 'bounce 0.5s ease-out',
            },
            transitionDuration: {
                '200': '200ms',
                '300': '300ms',
                '400': '400ms',
                '500': '500ms',
                '800': '800ms',
            },
        },
    },
}
```

---

## 7. Accessibility & Performance

### 7.1 Motion Accessibility

```css
/* Respect prefers-reduced-motion */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
```

### 7.2 Performance Rules

1. **GPU Acceleration**: Usare `transform` e `opacity`
2. **Will-change**: Solo quando necessario
3. **Debounce scroll**: Eventi scroll con throttle
4. **Lazy load**: Animazioni on-demand

---

## 8. Implementation Plan

### Phase 1: Core Transitions (Priorità Alta)
- [ ] Hover effects sui prediction cards
- [ ] Button press feedback
- [ ] Probability bar animations
- [ ] Link hover effects

### Phase 2: Page Transitions (Priorità Media)
- [ ] Page enter animations
- [ ] Staggered list reveals
- [ ] Modal/dialog animations

### Phase 3: Advanced Motion (Priorità Bassa)
- [ ] Scroll-triggered reveals
- [ ] Parallax backgrounds
- [ ] Interactive hover states
- [ ] Loading skeletons

---

## 9. Files to Modify

| File | Changes |
|------|---------|
| `resources/css/app.css` | Add keyframes, motion utilities |
| `tailwind.config.js` | Add custom animations |
| `views/filament/widgets/predict-table.blade.php` | Add animation classes |
| `views/components/**/*.blade.php` | Add motion classes |
| `views/layouts/**/*.blade.php` | Add page transitions |

---

## 10. Success Metrics

| Metrica | Target |
|---------|--------|
| **Session Duration** | +15% |
| **Bounce Rate** | -10% |
| **Interaction Rate** | +20% |
| **Lighthouse Performance** | >90 |

---

**Aggiornato**: 2026-03-18  
**Stato**: ⏳ Pronto per implementazione
