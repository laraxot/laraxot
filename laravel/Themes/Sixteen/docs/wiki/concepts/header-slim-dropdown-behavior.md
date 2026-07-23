# Header slim: dropdown lingua e utente (Sixteen)

## Scopo

La slim bar è il micro-contesto **istituzionale** (Regione / accesso lingua / area personale). I dropdown devono aprirsi e chiudersi in modo **prevedibile**, senza competere con overlay Filament/Livewire e **senza** `display: inline` lasciato sporco da vecchie chiusure JS.

## Regola operativa (aggiornata 2026-05-04 — story 8-103)

**Meccanismo ufficiale approvato**: `data-bs-toggle="dropdown"` nel markup + runtime owner-side in `resources/js/app.js`.

> **VIETATO** un secondo sistema Alpine inline (`x-data="{ langOpen: false }"`) per questi dropdown:
> su pagine **Livewire + Filament** Alpine inline può non agganciarsi e i menu restano chiusi.
> Il runtime owner-side in `app.js` è la soluzione canonica (Story 7-54, confermata 8-103).

1. **Markup:** `data-bs-toggle="dropdown"` + `.dropdown-menu` come da Bootstrap Italia.
2. **Runtime:** `resources/js/app.js` — listener su `[data-bs-toggle="dropdown"]`.
3. **Chiusura click esterno:** listener `document.click` in `app.js` — ignora click su `[data-bs-toggle]` e `.dropdown-menu`.
4. **CSS display:** classe `.show` su `.dropdown-menu`; dopo chiusura `removeProperty('display')`.
5. **Stacking:** `.it-header-slim-wrapper .dropdown-menu` usa `z-index` elevato (es. 2000) e `overflow: visible` sulla right zone.
6. **Colore slim:** `#00402B` verde scuro — **branding deliberato del Comune**, non `#0066CC` blu Design Comuni standard. Confermato da `header-footer-colors.css` e wiki Fixcity `header-green-branding-rule.md`.
7. **Guest CTA color:** `#007A52` verde inline in `personal-area-guest-cta.blade.php` — **deliberato** per evitare "blue spots" nel branding verde comunale.

## Problema Livewire — Root Cause e Fix (story 8-103)

I listener in `app.js` sono registrati su `DOMContentLoaded`. Livewire 4 esegue DOM morphing dopo ogni step del wizard:
- rimuove/reinserisce elementi → **listener persi**
- `wire:navigate` non ri-scatta `DOMContentLoaded`

**Fix**: estrarre funzioni helper in scope modulo + aggiungere hook Livewire:

```javascript
// Scope modulo — fuori da DOMContentLoaded
function closeDropdownMenu(menu) { ... }
function openDropdownMenu(menu, dropdown, toggle) { ... }

function initHeaderDropdowns() {
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(toggle) {
        if (toggle._headerDropdownInit) return; // prevent double-binding
        toggle._headerDropdownInit = true;
        toggle.addEventListener('click', function(e) { ... });
    });
}

// Hook Livewire 4
document.addEventListener('livewire:navigated', initHeaderDropdowns);
document.addEventListener('livewire:update', initHeaderDropdowns);
document.addEventListener('DOMContentLoaded', initHeaderDropdowns);
```

## Stato file (analisi codice reale 2026-05-04)

| File | Stato | Note |
|------|-------|------|
| `partials/language-switcher.blade.php` | ✅ usa `data-bs-toggle` | coerente col runtime |
| `partials/user-dropdown.blade.php` | ✅ usa `data-bs-toggle` | coerente col runtime |
| `partials/personal-area-guest-cta.blade.php` | ✅ verde `#007A52` inline | deliberato — branding |
| `resources/js/app.js` | ⚠️ solo DOMContentLoaded | mancano hook Livewire |
| `components/header-footer-colors.css` | ✅ slim `#00402B` | verde scuro corretto |

## Cause tipiche di dropdown non funzionanti

- `app.js` non caricato nel layout della pagina
- Livewire full-page re-render che distrugge i listener vanilla JS (→ fix: hook `livewire:navigated`)
- z-index / overflow / stacking context bloccante
- doppio sistema interazione Alpine inline + runtime owner-side (→ evitare Alpine inline)

## Riferimenti

- Codice owner: `resources/views/components/sections/header/v1.blade.php` (SSoT)
- Runtime: `resources/js/app.js`
- Story attiva: `_bmad-output/implementation-artifacts/8-103-header-segnalazione-crea-step2-design-comuni-visual-parity.story.md`
- Story legacy: `_bmad-output/implementation-artifacts/7-54-*.md` (prima implementazione data-bs-toggle)
- Branding verde: `Modules/Fixcity/docs/llm-wiki/concepts/header-green-branding-rule.md`
- Parallelo auth: [header-authenticated-state](./header-authenticated-state.md)
- Color parity: [header-color-parity](./header-color-parity.md)
- Reference Design Comuni: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html

## Backlink

- [Sixteen wiki index](../index.md)
- [Root wiki log](../log.md)
