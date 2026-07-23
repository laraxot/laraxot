# WCAG 2.1 AA - Piano di Conformità Tema Two

## Fonte: Validazione MAUVE su sottana.net

### Stato Attuale

**ERRORI** (blockers per conformità AA):

| Codice | Criterio WCAG | Count | Priorità |
|--------|--------------|-------|---------|
| ARIA6 | 1.1.1 Alt Text | 26 | P1 |
| G18 | 1.4.3 Contrasto | 25 | P1 |
| F78/G195 | 2.4.7 Focus | 5+5 | P1 |
| C21 | 1.4.12 Line Height | 5 | P1 |
| H30 | 2.4.4 Link Purpose | 4 | P1 |

**WARNINGS** (da migliorare):

| Codice | Criterio WCAG | Count | Note |
|--------|--------------|-------|------|
| F96 | 2.5.3 Label in Name | 85 | Alpine.js attrs |
| C12-14 | 1.4.4 Resize Text | 5 | px → rem |
| ARIA11 | 2.4.1 Bypass | 3 | landmarks mancanti |
| H67 | 1.1.1 Decorative img | 2 | alt="" ok, rimuovere title |

---

## Soluzioni per Errore

### ARIA6 - 26 occorrenze: aria-label su elementi icon-only
**Problema**: Bottoni e link con solo SVG senza testo alternativo.
**Dove**: Social icons header/footer (5), icone bottoni, link navigazione
**Fix**:
```html
<!-- SBAGLIATO -->
<a href="https://linkedin.com"><svg>...</svg></a>

<!-- CORRETTO -->
<a href="https://linkedin.com" aria-label="Seguici su LinkedIn">
    <svg aria-hidden="true">...</svg>
</a>
```
**File da modificare**:
- `Themes/Two/resources/views/components/sections/footer/v1.blade.php`
- `Themes/Two/resources/views/components/sections/header/v1.blade.php`

### G18 - 25 occorrenze: Contrasto insufficiente
**Problema**: Testo con contrasto < 4.5:1 su sfondi colorati.
**Pattern comuni**:
- Testo grigio chiaro su bianco (es. `text-gray-400`)
- Testo bianco su sfondi azzurri chiari
- Placeholder input
**Fix**: Usare colori con contrasto verificato 4.5:1 minimo
```css
/* SBAGLIATO - contrasto ~2.5:1 */
.text-gray-400 { color: #9CA3AF; }

/* CORRETTO - contrasto 7:1 su bianco */
.text-accessible { color: #4B5563; } /* gray-600 */
```

### F78/G195 - Focus indicator
**Problema**: CSS rimuove o nasconde il focus outline.
**Causa probabile**: Tailwind/daisyUI reset `outline: none`
**Fix già in app.css**:
```css
:where(a, button, input,...):focus-visible {
    outline: 3px solid #1E5A96 !important;
    outline-offset: 3px !important;
}
```
**Azione**: Verificare che nessun componente Filament/daisyUI sovrascriva.

### C21 - Line-height
**Problema**: Line-height < 1.5 × font-size
**Fix aggiunto in app.css**:
```css
body { line-height: 1.5; }
p, li, dt, dd, label { line-height: 1.5; }
```

### H30 - 4 link senza testo descrittivo
**Problema**: Link con solo SVG icon senza aria-label
**Esempio nel codice**: Link social media, link logo
**Fix**: Aggiungere `aria-label` o `<span class="sr-only">testo</span>`

### C21 - Già fixato in app.css ✅

---

## Implementazione in Blade

### Pattern corretto per social icons
```blade
<a href="{{ $social['url'] }}"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Seguici su {{ $social['platform'] }} (apre in nuova finestra)"
   class="...">
    <svg aria-hidden="true" focusable="false">...</svg>
</a>
```

### Pattern corretto per input form (H44 + H98)
```blade
<label for="email" class="block text-sm font-medium text-gray-700">
    Email <span aria-hidden="true" class="text-red-600">*</span>
    <span class="sr-only">(campo obbligatorio)</span>
</label>
<input
    type="email"
    id="email"
    name="email"
    autocomplete="email"
    required
    aria-required="true"
    class="..."
/>
```

### Skip to content link (ARIA11)
```blade
{{-- In layout principale, primo elemento dopo <body> --}}
<a href="#main-content" class="skip-to-content">
    Vai al contenuto principale
</a>
...
<main id="main-content" tabindex="-1">
```

### ARIA landmarks
```blade
<header role="banner">...</header>
<nav role="navigation" aria-label="Navigazione principale">...</nav>
<main role="main" id="main-content">...</main>
<footer role="contentinfo">...</footer>
```

---

## Checklist Implementazione

- [x] C21: line-height 1.5 aggiunto a app.css
- [x] G195: focus-visible outline in app.css
- [x] sr-only class in app.css
- [x] skip-to-content CSS in app.css
- [ ] H30/ARIA6: aria-label su social icons (header + footer)
- [ ] H30/ARIA6: aria-label su logo link
- [ ] ARIA11: skip-to-content link nel layout
- [ ] ARIA11: landmark roles su header/main/footer/nav
- [ ] H98: autocomplete su form contatti
- [ ] G18: audit contrasto su tutti i componenti
- [ ] C12-14: convertire px → rem nei font-size custom

---

## Tool di Validazione

- **MAUVE**: https://mauve.isti.cnr.it/
- **W3C Nu HTML Checker**: https://validator.w3.org/nu/?doc=https://sottana.net
- **Axe DevTools**: Chrome extension gratuita
- **WebAIM Contrast Checker**: https://webaim.org/resources/contrastchecker/

---

## GitHub Issue

Tracking: https://github.com/laraxot/base_techplanner_fila5/issues/4
