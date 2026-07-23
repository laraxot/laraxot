# Design Comuni Documentation Index

This directory contains all documentation for the Design Comuni Italiani static pages replication project.

## Overview

The goal is to replicate the Bootstrap Italia design using **Tailwind CSS + Alpine.js** (NO Bootstrap Italia CSS/JS).

**Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
**Local:** http://127.0.0.1:8000/it/tests/homepage

## Documents

| Document | Description | Last Updated |
|----------|-------------|--------------|
| [FixCity Product Brief (BMAD)](../../../../docs/product-brief-fixcity-2026-05-27.md) | Visione prodotto, scope, stakeholder, rischi — fase 1 Analysis | — |
| [Homepage HTML Structure Comparison](homepage-html-comparison.md) | Detailed analysis of HTML structure and CSS differences between reference and local homepage | 2026-04-07 |
| [Block Analysis](../../../../_bmad-output/design-comuni-block-analysis.md) | 47 reusable components identified across 38 pages (BMad) | 2026-03-15 |
| [PRD](../../../../_bmad-output/design-comuni-prd.md) | Product Requirements Document (BMad) | 2026-03-15 |
| [Architecture](../../../../_bmad-output/design-comuni-architecture.md) | Technical architecture document (BMad) | 2026-03-15 |
| [UI Spec](../../../../_bmad-output/design-comuni-ui-spec.md) | UI specifications (BMad) | 2026-03-15 |

## Architecture

### Key Files
- **Layout:** `resources/views/components/layouts/main.blade.php` - Adds `dc-homepage-parity` body class
- **Page Component:** `resources/views/components/page.blade.php` - Block assembler
- **Route:** `resources/views/pages/tests/[slug].blade.php` - Dynamic page handler
- **Content:** `config/local/fixcity/database/content/pages/tests.homepage.json`

### CSS Pipeline
- **Entry:** `resources/css/app.css` (2514+ lines)
- **Homepage Parity:** `resources/css/homepage-parity-v2.css` (1252 lines)
- **Style Mapping:** `resources/css/style-apply.css` - Bootstrap Italia → Tailwind @apply
- **Build:** Vite → `public/` → `npm run copy` → `public_html/themes/Sixteen/`

### Block Components
All homepage blocks are in `resources/views/components/blocks/`:
- `hero/homepage.blade.php` - Hero section with news card
- `governance/cards.blade.php` - Organi di governo + events calendar
- `topics/highlight.blade.php` - Argomenti in evidenza + siti tematici
- `search/support-links.blade.php` - Search + useful links
- `feedback/rating.blade.php` - Star rating with Alpine.js
- `contact/homepage.blade.php` - Contact links
- `services/homepage.blade.php` - Services grid
- `administration/homepage.blade.php` - Administration grid

## Screenshots

All comparison screenshots are in the `screenshots/` subdirectory:

### Before Fixes
- [Reference Homepage](screenshots/ref-homepage.png)
- [Local Homepage (before)](screenshots/local-homepage.png)

### After Fixes
- [Local Homepage (after)](screenshots/local-homepage-after-fix.png)
- Section screenshots: hero, calendar, evidence, rating, contacts, footer

## Related Documentation

### Module Docs
- [Cms Module - Page Component Architecture](../../../../Modules/Cms/docs/PAGE_COMPONENT_ARCHITECTURE.md)
- [Theme - Page Routing Architecture](../architecture/PAGE_ROUTING_ARCHITECTURE.md)
- [Fixcity - Ticket Wizard Frontoffice](../../../../Modules/Fixcity/docs/ticket-wizard-frontoffice.md)
- [Fixcity - CreateTicketWizardWidget](../../../../Modules/Fixcity/docs/CreateTicketWizardWidget.md)
- [Story 7-47 - Privacy notice parity nello step 1](../../../../../_bmad-output/implementation-artifacts/7-47-segnalazione-crea-step1-privacy-notice-design-comuni-parity.md)

### BMad Docs
- [Design Comuni Block Analysis](../../../../_bmad-output/design-comuni-block-analysis.md)
- [Design Comuni PRD](../../../../_bmad-output/design-comuni-prd.md)

### HTML Structure Comparison Tools
- [bashscripts/html/README.md](../../../bashscripts/html/README.md) — Tool documentation for compare-html-body.py and html-structure-compare.sh
- [bashscripts/body/](../../../bashscripts/body/) — Bash orchestrator scripts (html-structure-compare.sh, compare-ticket-list.sh)
- [Body Structure Comparison Output](../body-structure-comparison/) — Generated reports and parity scores
- [Segnalazioni Elenco Report](../body-structure-comparison/ticket-list/report.md) — Structured diff output with BLOCK/FLAG/WARN severity
- [Segnalazioni Elenco Parity Score](../body-structure-comparison/ticket-list/parity-score.md) — Score card tracking parity over runs
- [Segnalazioni Elenco Analysis](../prompts/segnalazione_disservizio/ticket-list-html-parity-analysis.md) — Phase 1 parity summary (90%+ structural)

## Progress Status

### Completed ✓
- [x] HTML structure comparison
- [x] Screenshot capture and analysis
- [x] CSS color fixes (hero, chip, read-more)
- [x] Rating block styling (white card, green bg, stars)
- [x] Login button styling
- [x] Governance cards layout (3-column)
- [x] Events carousel visibility
- [x] Evidence section styling
- [x] Siti tematici colored cards
- [x] Footer dark navy background
- [x] Documentation with bidirectional links

### In Progress ~
- [ ] Responsive breakpoint testing (mobile/tablet)
- [ ] Alpine.js interactivity (rating steps, carousel)
- [ ] Cross-browser verification

### Planned
- [ ] Remaining 37 pages replication
- [ ] Performance optimization
- [ ] Accessibility audit (WCAG 2.1 AA)

## Frontend Filament Runtime

Pages under `tests/*` normally avoid Filament runtime chrome for parity work, but any page that mounts a Filament widget on the public frontend must opt back into `@filamentStyles` and `@filamentScripts`.

Current reference case:
- `tests.segnalazione-crea`
- widget: `Modules\\Fixcity\\Filament\\Widgets\\CreateTicketWizardWidget`
- failure mode without runtime: Alpine errors such as `step is not defined`, `isLastStep is not defined`, and `filamentSchemaComponent is not defined`

- `tests/segnalazione-crea` usa runtime Livewire frontoffice, non `@filamentScripts`: il widget e' Livewire puro e Filament iniettava Alpine duplicato.

## Ticket Wizard Note

Per `tests/segnalazione-crea`, la parity con `segnalazione-01-privacy` non e solo un tema di CSS. Se il reference espone un blocco GDPR esplicito prima del checkbox, il tema deve conservare spazio e gerarchia per quel contenuto, mentre la scelta del componente resta governata dal modulo Fixcity:

- `summary/autore read-only` -> Infolist
- `privacy notice editoriale/legale nel form wizard` -> contenuto read-only nel Form Schema

Questa distinzione evita di usare Infolists come martello universale e impedisce parity solo cosmetica.

## Docs-first + agenti concorrenti

- Prima di modificare asset o Blade per `segnalazione-crea`, aggiornare i docs canonici del modulo Fixcity e del tema Sixteen invece di aprire nuovi file paralleli.
- Assumere sempre concorrenza tra agenti AI: preferire patch locali, link bidirezionali e indici aggiornati.
- Per lo step 2 (`segnalazione-02-dati`) la regola condivisa e:
  - `Section` governa la gerarchia visiva e il ritmo della pagina.
  - `Infolist` governa solo dati read-only strutturati.
  - `Placeholder` / `Text` / `View` nel Form Schema restano legittimi quando servono contenuti di supporto e non description-list data-driven.

Documenti canonici:
- [Fixcity / Filament Wizard Rules](../../../../Modules/Fixcity/docs/rules/filament-wizard-rules.md)
- [Fixcity / Ticket Wizard Frontoffice](../../../../Modules/Fixcity/docs/ticket-wizard-frontoffice.md)
- [Story 7-48 - step 2 visual parity via sections and infolist](../../../../../_bmad-output/implementation-artifacts/7-48-segnalazione-crea-step2-visual-parity-via-sections-and-infolist.md)
