# Segnalazione Flow — CSS/JS Parity Status

**Date:** 2026-04-07
**Status:** ✅ CSS/JS complete, HTML structure updated for pages 01-03
**Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/
**Local:** http://127.0.0.1:8000/it/tests/segnalazione-{page}

---

## Summary

All 8 Segnalazione pages now use Design Comuni-compatible HTML structure and CSS styling. No Bootstrap Italia runtime — only Tailwind CSS + Alpine.js.

### Changes Made

#### 1. JSON Content Files Updated
- `tests.segnalazione-01-privacy.json` — Replaced placeholder blocks with `flow-stepper` + `flow.ticket-privacy`
- `tests.segnalazione-02-dati.json` — Replaced placeholder blocks with `flow-stepper` + `flow.ticket-dati`
- `tests.segnalazione-03-riepilogo.json` — Replaced placeholder blocks with `flow-stepper` + `flow.ticket-riepilogo`

#### 2. Blade Components Rewritten
- `flow.ticket/01-privacy.blade.php` — Now uses Design Comuni classes: `container`, `row`, `col-lg-8`, `text-paragraph`, `form-check`, `checkbox-body`, `btn btn-primary mobile-full`
- `flow.ticket/02-dati.blade.php` — Now uses: `fieldset`, `legend`, `form-group`, `form-control`, `card user-card`, `steppers-nav`, `btn btn-primary`, `btn btn-outline-primary`
- `flow.ticket/03-riepilogo.blade.php` — Now uses: `it-alert alert-warning`, `card-wrapper`, `card-header`, `it-list-wrapper`, `actions-wrapper`, `terms-section`, `form-check`, `btn btn-primary`, `btn btn-outline-danger`

#### 3. CSS Additions (segnalazione-parity.css)
Added 220+ lines covering:
- Alert/warning boxes (`.it-alert`, `.alert-warning`)
- Card wrappers for summary (`.card-wrapper`, `.card-header`, `.card-body`)
- Definition lists (`.it-list-wrapper`, `dt`, `dd`)
- Text utilities (`.t-primary`, `.t-secondary`)
- Actions wrapper (`.actions-wrapper`)
- Terms section (`.terms-section`)
- File upload (`.fileupload`, `.fileupload-filename`)
- User card (`.user-card`, `.contacts`)
- Stepper navigation (`.steppers-nav`)
- Secondary buttons (`.btn-outline-secondary`, `.btn-outline-danger`)
- Form note (`.form-note`)
- Text danger (`.text-danger`)
- Responsive mobile button handling (`.mobile-full`)

### Page-by-Page Status

| Page | HTML Structure | CSS Styling | Responsive | Notes |
|------|---------------|-------------|------------|-------|
| segnalazione-disservizio | ✅ | ✅ | ✅ | Uses `tests/segnalazione-dettaglio` component |
| segnalazione-01-privacy | ✅ Updated | ✅ | ✅ | Rewritten with Design Comuni classes |
| segnalazione-02-dati | ✅ Updated | ✅ | ✅ | Full form with fieldsets, cards, stepper nav |
| segnalazione-03-riepilogo | ✅ Updated | ✅ | ✅ | Summary cards, alert, terms section |
| segnalazione-04-conferma | ✅ | ✅ | ✅ | Already using Design Comuni classes |
| segnalazione-area-personale | ✅ | ✅ | ✅ | Hero + card list layout |
| ticket-list | ✅ | ✅ | ✅ | Map/list tabs, filters, cards |
| segnalazione-dettaglio | ✅ | ✅ | ✅ | Full detail with sidebar index |

### CSS Architecture

```
app.css
├── style-apply.css              — Bootstrap Italia → Tailwind @apply
├── container-override.css       — Container width overrides
├── footer-override.css          — Footer style fixes
├── bootstrap-italia.css         — Bootstrap Italia component classes
├── components/*.css             — Component-specific classes
├── design-comuni.css            — 1912 lines of Design Comuni classes
├── design-comuni-global.css     — 300+ lines of global overrides
├── segnalazione-parity.css      — 1581 lines (now covers flow pages)
└── ... other parity files
```

### Key Design Tokens

| Token | Value | Usage |
|-------|-------|-------|
| Green | `#007A52` | Primary color, buttons, links, focus rings |
| Green Dark | `#005c40` | Hover state |
| Header Slim | `#00402b` | Top bar background |
| Text Primary | `#191919` | Body text |
| Text Secondary | `#5c6f82` | Muted text, labels |
| Border | `#d9e1e8` | Input borders |
| Card Border | `#e8e8e8` | Card borders |
| Background Grey | `#f5f5f5` / `#f5f6f7` | Section backgrounds |
| Warning Border | `#995C00` | Alert left border |
| Danger | `#cc0000` | Error states, required asterisk |

### Typography

| Class | Size | Weight | Usage |
|-------|------|--------|-------|
| `title-xxxlarge` | 2.5rem | 700 | Page H1 |
| `title-xxlarge` | 2rem | 700 | Section H2 |
| `title-xlarge` | 1.5rem | 700 | Subsection H2 |
| `title-medium-2-semi-bold` | 1.125rem | 600 | Card titles |
| `title-small-semi-bold` | 1rem | 600 | Labels, fieldset legends |
| `text-paragraph` | 1rem | 400 | Body text |
| `subtitle-small` | 0.875rem | 400 | Small descriptions |

### Responsive Breakpoints

| Breakpoint | Width | Behavior |
|------------|-------|----------|
| Mobile | <576px | Single column, full-width buttons, hidden sidebar |
| Tablet | 768px-991px | 2-column grid where applicable |
| Desktop | ≥992px | Full 2-column layout with sidebar index |

### Build Pipeline

```bash
cd laravel/Themes/Sixteen
npm run build    # Vite production build → ./public/
npm run copy     # Copy to public_html/themes/Sixteen/ + assets
```

### Related Documentation

- [Design Comuni Master Index](../00-index.md)
- [Architectural Decisions](../ARCHITECTURAL_DECISIONS.md)
- [HTML Structural Diff](../../../../.planning/research/html-structural-diff.md)
- [Reference HTML Structure](../../../../.planning/research/reference-html-structure.md)
- [Codebase Mapping](../../../../.planning/codebase/design-comuni-segnalazioni-mapping.md)

### Screenshots

All pages captured at desktop (1440px) and tablet (768px) breakpoints:
- [screenshots/segnalazione-01-privacy/](../screenshots/segnalazione-01-privacy/)
- [screenshots/segnalazione-02-dati/](../screenshots/segnalazione-02-dati/)
- [screenshots/segnalazione-03-riepilogo/](../screenshots/segnalazione-03-riepilogo/)
- [screenshots/segnalazione-04-conferma/](../screenshots/segnalazione-04-conferma/)
- [screenshots/segnalazione-area-personale/](../screenshots/segnalazione-area-personale/)
- [screenshots/segnalazione-dettaglio/](../screenshots/segnalazione-dettaglio/)
- [screenshots/segnalazione-disservizio/](../screenshots/segnalazione-disservizio/)
- [screenshots/ticket-list/](../screenshots/ticket-list/)
