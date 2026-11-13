---
title: "Investigazione — Redesign FO con DesignComuni"
type: investigation
tags: [design-comuni, front-office, redesign]
links:
  story: docs/stories/front-office-design-comuni-rebuild.story.md
status: draft
updated: 2026-10-11
qmd: "fo designcomuni   investigazione sixteen"
---

# FO DesignComuni — Investigazione

## Pagine FO identificate
- `home.blade.php` / `home-improved.blade.php` / `index.blade.php`
- `show.blade.php` / `404.blade.php`
- `accessibility.blade.php`
- `privacy.blade.php` / `terms.blade.php` / `policy.blade.php`
- `pages_old/*.blade.php`

## DesignComuni v2.4.0 — Componenti chiave
- Header `it-header-wrapper`: slim + nav + brand + search
- Footer `it-footer`: EU logo + brand + links + social
- Skip links (`#main-container`, `#footer`)
- Card `card card-bg` con `rounded shadow-sm border-0`
- Button `btn btn-primary` / `btn btn-secondary`
- Navigation `navbar` con `dropdown`

## Note
- Template `homepage.html` come riferimento per struttura hero + sezione contenuto.
- Template `segnalazione-dettaglio.html` per pagina servizio (descrizione, stato, azioni).
- `404.blade.php`: seguire pattern `risultati-ricerca.html` (messaggio chiaro + link a contenuti correlati).
