# Header HTML Parity — Design Comuni vs Blade v1

**Data analisi**: 2026-05-04  
**Raw source DC**: `docs/design-comuni/raw/cmp-header.hbs`  
**Blade locale**: `resources/views/components/sections/header/v1.blade.php`  
**Riferimento live**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html  
**Riferimento HBS**: https://github.com/italia/design-comuni-pagine-statiche/blob/main/src/components/cmp-header/cmp-header.hbs

---

## 1. Metodologia

**Non rifare da zero ciò che esiste già.** Il `cmp-header.hbs` è il template canonico DC.  
Il Blade `v1.blade.php` deve mappare **1:1 ogni elemento HTML** del HBS, adattando:
- `{{#if logged}}` → `@auth/@guest`
- `{{#if active1}} active{{/if}}` → classi dinamiche Laravel  
- URL statici → `route()` Laravel
- Variabili HBS → variabili Blade/PHP
- `data-bs-toggle="navbarcollapsible"` → Alpine.js `x-data="headerMobileNav"` (`Alpine.data`, non una funzione globale)
- `xlink:href` → `href` (svg sprite moderni)

Stack locale: **Tailwind + Alpine.js + Lit** (no Bootstrap JS). I `data-bs-toggle` per dropdown sono gestiti da `app.js` custom.

---

## 2. Differenze Architetturali Intenzionali (non gap, scelte deliberate)

Il progetto locale diverge dal DC HBS in alcuni punti **intenzionali** documentati:

| Elemento | DC HBS | Blade locale | Motivazione |
|----------|--------|--------------|-------------|
| **CTA guest posizione** | Nella slim (`it-header-slim-right-zone`) | Nel center (`it-right-zone`, solo `d-none d-md-flex`) | Scelta UX: mostra la CTA più prominente nel center |
| **Login dropdown (auth)** | `<a>` con `btn btn-primary btn-icon btn-full` + avatar img | Allineato STORY-147: stesso pattern guest + `rounded-icon` | Visual parity > nav-link; logout resta POST |
| **Logout** | `<a class="list-item left-icon">` | `<form POST>` + `<button>` | Sicurezza: logout DEVE essere POST |
| **i18n testi** | Hardcoded ITA | `__('pub_theme::...')` | Multilinguismo |
| **Mobile nav** | Bootstrap JS `data-bs-toggle="navbarcollapsible"` | Alpine.js `x-data="headerMobileNav"` | No Bootstrap JS runtime |
| **Dropdown** | Bootstrap JS `data-bs-toggle="dropdown"` | `data-bs-toggle` + `app.js` custom | Livewire compatibility |

---

## 3. Gap Analysis — Diff HBS vs Blade

### 3.1 SLIM WRAPPER

| Elemento | DC HBS | Blade locale | Status |
|----------|--------|--------------|--------|
| `<a class="d-lg-block navbar-brand"` | `class="d-lg-block navbar-brand"` | `class="d-lg-block navbar-brand"` | ✅ **corretto dopo fix G1** |
| CTA guest | In slim right zone | Nel center (scelta intenzionale) | ℹ️ divergenza documentata |
| Logged user: `it-user-wrapper nav-item dropdown` | ✅ | ✅ | ✅ |
| Lang switcher: `visually-hidden` "Lingua attiva:" | ✅ | ✅ | ✅ |
| Lang switcher: `link-list-wrapper` wrapping `ul` | `div.link-list-wrapper > ul.link-list` | `ul.link-list` diretto | ❌ G16 manca wrapper div |

### 3.2 CENTER WRAPPER

| Elemento | DC HBS | Blade locale | Status |
|----------|--------|--------------|--------|
| Logo `width="82" height="82"` | ✅ | ✅ | ✅ |
| Brand link `title="Vai alla homepage"` | ✅ | ✅ **corretto dopo fix G6** | ✅ |
| CTA guest nel center | Non in DC | ✅ locale (intenzionale) | ℹ️ |
| Burger nel center | Non in DC | `custom-navbar-toggler-center` | ❌ G3 extra non in DC |
| Socials `d-none d-lg-flex` | ✅ | ✅ | ✅ |
| Search `d-none d-md-block` span | ✅ | ✅ | ✅ |

### 3.3 NAVBAR WRAPPER

| Elemento | DC HBS | Blade locale | Status |
|----------|--------|--------------|--------|
| `class="overlay"` | ✅ | ✅ **corretto dopo fix G4** | ✅ |
| nav active dinamico | `{{#if active3}} active{{/if}}` | hardcoded `class="nav-link active"` su Servizi | ❌ G5 |
| Nav link `data-element` | ✅ tutti presenti | ✅ | ✅ |

---

## 4. Delta List — Gap Rimanenti (post fix 7-109 + 2026-05-04)

### FISSI ✅ (già risolti)

| ID | Elemento | Fix applicato |
|----|---------|--------------|
| G1 | `navbar-brand` manca `d-lg-block` | ✅ Fix 2026-05-04 |
| G4 | `navbar-overlay` → `overlay` | ✅ Fix 2026-05-04 |
| G6 | `title="Vai alla homepage"` su brand link | ✅ Fix 2026-05-04 |
| G8 | Lang switcher `visually-hidden` "Lingua attiva:" | ✅ già presente |

### APERTI — Alta priorità

| ID | Elemento | Fix da fare |
|----|---------|-------------|
| G5 | nav `active` hardcoded su "Servizi" | Rendere dinamico via route check PHP |
| G16 | Lang dropdown: manca `div.link-list-wrapper` | Aggiungere wrapper in `language-switcher.blade.php` |

### APERTI — Bassa priorità / Divergenze accettabili

| ID | Elemento | Note |
|----|---------|------|
| G3 | Center toggler extra (non in DC) | DC ha 1 burger, locale ha 2. UX choice — centro mostra burger prima del logo |
| G7 | Lang `aria-controls="header-language-menu"` vs `"languages"` | Funzionale — ID locale più descrittivo |
| G9 | User dropdown: 2 `divider` vs 1 DC | Locale è più ricco |
| G10 | Logout via `<form POST>` vs `<a>` DC | Sicurezza — POST obbligatorio |
| G14 | User dropdown toggle `<button>` vs `<a>` | Accessibilità — button è corretto per toggle |
| G15 | User dropdown: icone SVG extra | Locale è più ricco del DC, non un problema |

---

## 4. Cosa NON rifare da zero (già presente nel DC)

Il progetto ha già implementato correttamente:
- Struttura 3-layer: slim / center / navbar ✅
- Socials lista (6 icone) ✅
- Menu hamburger con socials mobile ✅
- Nav principale (4 voci) + secondaria (4 voci) ✅
- Search wrapper con span "Cerca" + button ✅
- Logo SVG con brand-text + tagline ✅
- Dropdown lingua con `link-list-wrapper` ✅
- User dropdown con menu voci ✅

---

## 5. Variabili DC (da `_variables.scss`)

```scss
$header-center-bg-color: $primary;    /* #007a52 */
$header-slim-bg-color: #00402b;       /* verde scuro slim */
$primary: #007A52;                     /* verde comunale */
```

**Mapping token locale** (in `app.css` `:root`):
```css
--dc-green: #007a52;       /* = $primary DC */
--color-italia: #007a52;   /* = $primary DC */
--color-italia-dark: #00402b; /* = $header-slim-bg-color DC */
```

---

## 6. Regola operativa per futuri agenti

> **Prima di implementare qualsiasi elemento header, consultare `raw/cmp-header.hbs` e questo documento.**  
> Ogni elemento deve avere una corrispondenza 1:1 con il HBS DC.  
> Le uniche variazioni accettabili sono quelle documentate nella sezione 2 (Alpine vs BS-JS, route() vs URL statici, variabili Blade vs HBS).

---

## 7. File correlati

- `../../wiki/concepts/livewire-alpine-esm-order.md` — ordinamento `@livewireScripts` vs `@vite` (ES module) e bootstrap Alpine header
- `raw/cmp-header.hbs` — sorgente HBS originale DC
- `raw/_cmp-header.scss` — SCSS DC header  
- `resources/views/components/sections/header/v1.blade.php` — SSoT Blade
- `resources/views/components/sections/header/partials/` — partials (language-switcher, user-dropdown, cta)
- `resources/css/app.css` — override CSS
- `resources/css/components/header-footer-colors.css` — colori header

## 8. Log modifiche

| Data | Story | Cambiamento |
|------|-------|-------------|
| 2026-05-04 | 7-107 | menu-wrapper bg, CTA bg fix |
| 2026-05-04 | 7-108 | slim container transparent |
| 2026-05-04 | 7-109 | CTA bg #007a52, search flex-row, dropdown min-width |
| 2026-05-04 | — | Questo documento creato — ingesta raw sources DC |
