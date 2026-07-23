---
title: "Segnalazioni elenco — gap visivo vs Design Comuni"
type: troubleshooting
sources:
  - shared-components/segnalazioni-elenco-reference-body.html
confidence: high
created: 2026-06-02
updated: 2026-06-02
tags: [segnalazioni-elenco, visual-parity, design-comuni]
related:
  - ../../../../docs/stories/STORY-109-it-homepage-visual-parity-design-comuni.md
  - segnalazioni-elenco-local-body.html
---

# Gap audit — screenshot `3b.png` (atteso) vs `3.png` (locale)

| Riferimento | Path |
|-------------|------|
| **Atteso (Design Comuni)** | `/mnt/c/Users/Marco/Pictures/Screenshots/fixcity/3b.png` |
| **Locale** | `/mnt/c/Users/Marco/Pictures/Screenshots/fixcity/3.png` |
| **URL** | `http://127.0.0.1:8000/it/` |
| **HTML reference** | [segnalazioni-elenco.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazioni-elenco.html) |

## GitHub

| Tipo | Repo | # | URL |
|------|------|---|-----|
| Issue | `laraxot/base_fixcity_fila5` | #192 | https://github.com/laraxot/base_fixcity_fila5/issues/192 |
| Issue | `laraxot/base_fixcity_fila5` | #188 | https://github.com/laraxot/base_fixcity_fila5/issues/188 |
| Discussion | `laraxot/base_fixcity_fila5` | #189 | https://github.com/laraxot/base_fixcity_fila5/discussions/189 |
| Issue | `laraxot/module_fixcity_fila5` | #7 | https://github.com/laraxot/module_fixcity_fila5/issues/7 |
| Issue | `laraxot/theme_sixteen_fila5` | #22 | https://github.com/laraxot/theme_sixteen_fila5/issues/22 |

**Story:** [STORY-109](../../../docs/stories/STORY-109-it-homepage-visual-parity-design-comuni.md)

---

## Differenze (screenshot 2026-06-02)

| # | Area | `3b.png` (atteso) | `3.png` (locale prima fix) | Fix |
|---|------|-------------------|---------------------------|-----|
| D1 | Tab Mappa | Sottolineatura **verde** | Blu `#0066cc` | CSS `body[data-page='segnalazioni-elenco']` + `listing-parity.css` |
| D2 | Checkbox | 11 voci, **non** selezionate | 3 voci tutte spuntate | `use_design_comuni_list_demo` + catalog `disservizio-lista.json`; `@checked` solo se in query |
| D3 | Risultati | **645 Risultati** | 3 Risultati | Demo catalog `REFERENCE_RESULTS_TOTAL` |
| D4 | Rimuovi filtri | Link testo **blu** | Bottone verde | Markup reference `btn p-0 pe-2` + `#block-clear-filters`; CSS su id (no `filter-clear-link`) |
| D5 | Mappa | Firenze, immagine statica, no toolbar | Italia + controlli Leaflet | Demo → `<img map-placeholder.svg>`; live → `map-lit` + `fitBounds` |
| D6 | Sottotitolo H1 | **73** segnalazioni risolte | 1 segnalazione | Demo → `REFERENCE_RESOLVED_LAST_12_MONTHS` |
| D7 | Categorie sidebar | 11 label reference | 3 da DB | `LoadDesignComuniElencoFilterCatalogAction` |
| D8 | Shell | Card bianca su grigio | Doppio `#main-container` / classe IT | Un solo `container#main-container` in `2col`; scope CSS `main[data-page]` (no `segnalazioni-elenco-page`) |
| D9 | Griglia | `col-lg-3` + `col-lg-8 offset-lg-1` | OK in `2col` | — |
| D10 | Header logo desktop | `container` max 1320px, gutter 12px (~322px da sinistra a 1920px) | Logo flush sinistra (`max-width: none` da Tailwind `.container`) | Rimosso `@apply` su `.container`; fix `.it-header-wrapper .container` ≥992px |

## Implementazione

| Componente | Modifica |
|------------|----------|
| `home.json` | `use_design_comuni_list_demo: true` (IT) |
| `TicketLayoutViewModel` | Catalog demo, 645/73, mappa statica in demo |
| `column-main.blade.php` | `map-lit` vs img reference |
| `results-header.blade.php` | `btn p-0 pe-2` + `#block-clear-filters` (parity reference) |
| `layouts/app.blade.php` | `<main>` senza secondo `container#main-container` |
| `filters-sidebar.blade.php` | Label con conteggio non duplicato |
| `listing-parity.css` | Tab verde, clear link, hide `.layer-controls-overlay` |

## Verifica

```bash
cd laravel/Themes/Sixteen && npm run build
# http://127.0.0.1:8000/it/ — confronto con 3b.png
cd laravel/Modules/Geo && npx playwright test tests/Playwright/ticket-list-visual-parity.spec.js
```

## Delta accettati (produzione senza demo)

Con `use_design_comuni_list_demo: false`: conteggi e categorie da `tickets.json` / DB; mappa interattiva `map-lit` (miglioramento funzionale vs mock statico).
