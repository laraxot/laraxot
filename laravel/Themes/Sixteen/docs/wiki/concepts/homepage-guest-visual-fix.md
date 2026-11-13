---
title: "homepage guest visual fix"
type: note
tags: [documentation, ui, ux, design-comuni, puppeteer]
created: 2026-09-27
updated: 2026-10-15
qmd: "homepage guest visual fix design comuni hero cta"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/572"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/573"
status: draft
---

# Home guest `/it` — fix visuale demo

## perche'

Su `http://localhost:8001/it` la home guest si vedeva male: hero Tailwind
(`bg-blue-950`) fuori dal linguaggio Design Comuni, CTA con contrasto rotto
(box bianco vuoto: `btn-primary` + `bg-white` → testo bianco su bianco),
eyebrow `APP_NAME=Laravel`.

## fix (KISS)

- ECC 2026-10-15: l’`h1` nascosto del blocco `blocks/homepage.blade.php` (path
  `/it/tests/homepage` / JSON tests) non usa piu’ `id="main-container"`
  (duplicato con `<main id="main-container">` in `layouts/app`); id dedicato
  `home-page-title`. La home prodotto `/it` usa invece
  `blocks/home/hero` → `id="fc-home-hero-heading"` (tipografia Lora, DCP-221).
- Markup allineato a Bootstrap Italia / Design Comuni (`container`, `card`,
  `btn`, `bg-primary`, `map-lit`).
- CTA hero con classi dedicate + CSS nello slot (`btn-hero-primary` /
  `btn-hero-secondary`) perche' le utility BI/Tailwind si annullano.
- Secondaria con sfondo `rgba(255,255,255,0.14)` (non trasparente puro).
- Critical CSS anti-FOUC in `layouts/main` (`#sixteen-critical-fouc`).
- Search modal i18n (`pub_theme::ui.*` + suggerimenti FixCity, no CIE hardcode).
- Copy solo da `pub_theme::home.*` / `pub_theme::navigation.site_title`.
- `APP_NAME=FixCity` in `.env`.

## verifica Puppeteer + Playwright

Chrome locale con `LD_LIBRARY_PATH` da `.deb` estratti. Playwright MCP non
disponibile in sessione: usato Playwright Node locale.

Screenshot: `/tmp/fixcity-visual/bmad-verify/`.

FOUC: con CSS Vite ritardato 2.5s hero resta verde. CTA secondaria misurata
`rgba(255,255,255,0.14)`. `/en` search senza italiano hardcoded.

## collegamenti

- [atteso Fixcity](../../../../../Modules/Fixcity/docs/bmad/homepage-guest-expected-visual.md)
- [confronto](../../../../../Modules/Fixcity/docs/bmad/homepage-guest-visual-comparison.md)
- [piano](../../../../../Modules/Fixcity/docs/bmad/homepage-guest-visual-correction-plan.md)
- [contratto Sixteen](../../bmad/homepage-guest-visual-contract.md)
- [STORY-513](../../../../../Modules/Fixcity/docs/bmad/stories/STORY-513-public-guest-visual-demo.md)
- [bootstrap-italia-tailwind-philosophy](./bootstrap-italia-tailwind-philosophy.md)
- [visual-parity-verification-rule](./visual-parity-verification-rule.md)
