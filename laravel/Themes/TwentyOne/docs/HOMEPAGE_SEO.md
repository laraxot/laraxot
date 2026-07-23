# Homepage Improvements — Sprint 1 (Critical)

> **Data**: 2026-03-18  
> **Sprint**: 1 (Critical - Accessibility, Performance, SEO)  
> **Stato**: 🔄 IN PROGRESS

---

## ✅ Completati

### 1. SEO Meta Tags + JSON-LD
- [x] Creato componente `seo-meta.blade.php`
- [x] Implementati meta tag: title, description, keywords, canonical
- [x] Open Graph tags per social sharing
- [x] Twitter Card per Twitter sharing
- [x] JSON-LD structured data:
  - WebSite schema
  - Organization schema
  - WebPage schema
- [x] Integrato in `layouts/app.blade.php`

**File Modificati**:
- `Themes/TwentyOne/resources/views/components/seo-meta.blade.php` (NUOVO)
- `Themes/TwentyOne/resources/views/components/layouts/app.blade.php` (AGGIORNATO)
- `config/local/predict/database/content/pages/home.json` (AGGIORNATO)

### 2. Accessibilità WCAG 2.2 AA
- [x] Skip navigation link (già presente, verificato)
- [x] Focus indicators visibili (outline 3px emerald-600)
- [x] Touch targets minimi 44x44px (CSS globale)
- [x] Reduced motion support (già presente)
- [x] ARIA labels ready

**CSS Implementato**:
```css
/* Focus Indicators - WCAG 2.2 AA */
*:focus-visible {
    outline: 3px solid #059669 !important;
    outline-offset: 2px !important;
    border-radius: 4px !important;
}

/* Touch Targets - Minimum 44x44px */
button, a.btn, [role="button"],
input[type="button"], input[type="submit"] {
    min-width: 44px !important;
    min-height: 44px !important;
}
```

### 3. Documentazione
- [x] Creato `HOMEPAGE_IMPROVEMENT_PLAN.md`
- [x] Creato `HOMEPAGE_SEO.md` (questo file)
- [x] Aggiornato `home.json` con meta title/description

---

## 🔄 In Corso

### 4. Performance Optimization
- [ ] Critical CSS inline per above-the-fold
- [ ] Lazy loading immagini below-fold
- [ ] Ottimizzazione LCP (hero image)
- [ ] Code splitting JavaScript

### 5. Micro-Interazioni
- [ ] Hover effects su tutti i bottoni
- [ ] Loading states (skeleton screens)
- [ ] Counters animati (volume, utenti)
- [ ] Scroll reveal animations

---

## 📊 Metriche

### Prima (Da Misurare)
- Lighthouse Performance: ❓
- Lighthouse Accessibility: ❓
- Lighthouse SEO: ❓
- LCP: ❓
- INP: ❓
- CLS: ❓

### Dopo (Target Sprint 1)
- Lighthouse Performance: > 85
- Lighthouse Accessibility: > 90
- Lighthouse SEO: > 95
- LCP: < 2.5s
- INP: < 200ms
- CLS: < 0.1

---

## 📝 Note Tecniche

### SEO Implementation
- **Title Tag**: "Home - Prevedi il Futuro, Guadagna Crediti | Base Predict"
- **Meta Description**: "Unisciti alla più grande piattaforma di prediction market..."
- **Canonical**: URL corrente dinamico
- **JSON-LD**: 3 schema types (WebSite, Organization, WebPage)

### Accessibility Implementation
- **Skip Link**: Già presente in entrambi i layout
- **Focus Visible**: Outline 3px emerald-600 con offset
- **Touch Targets**: 44x44px minimo (WCAG 2.2)
- **Reduced Motion**: Supportato tramite media query

### Performance Considerations
- Critical CSS da estrarre per above-the-fold
- Immagini hero da ottimizzare (WebP, lazy loading)
- JavaScript da splittare per route

---

## 🔗 Riferimenti

- [website-checklist.md](../../../docs/project/website-checklist.md)
- [HOMEPAGE_IMPROVEMENT_PLAN.md](HOMEPAGE_IMPROVEMENT_PLAN.md)
- [044-website-checklist.md](../../../.github/ISSUES/044-website-checklist.md)

---

**Ultimo aggiornamento**: 2026-03-18  
**Prossimo step**: Implementare micro-interazioni e lazy loading
