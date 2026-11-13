---
id: "sixteen-fo-tests-risultati-ricerca-parity"
type: "story"
title: "Tests DesignComuni: risultati-ricerca (search results) parity"
status: todo
parent: STORY-702
tags: [design-comuni, search, risultati-ricerca, tailwind, alpine, parity, tests-pages, bmad]
design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html"
local_route: "http://192.168.1.40:8000/it/tests/risultati-ricerca"
content_json: "laravel/config/local/fixcity/database/content/pages/tests.risultati-ricerca.json"
created: 2026-10-13
updated: 2026-10-13
---

# Story 702 — Tests risultati-ricerca parity

## Goal

Portare `http://192.168.1.40:8000/it/tests/risultati-ricerca` al minimo
90% di body HTML parity e 97% di visual parity con
`https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html`.

## Why now (root cause of the gap)

La rotta `/it/tests/risultati-ricerca` e' servita da
`pages/tests/[slug].blade.php` (Folio `name('tests.view')`) che renderizza
`@volt('tests.view')` + `<x-page :slug="tests.risultati-ricerca">`.
Il blocco CMS attuale e' un componente Livewire/Volt generico che:

1. mostra chiavi di traduzione NON risolte (`fixcity::search.results.title`,
   `fixcity::search.results.subtitle`) invece del testo italiano;
2. produce un `<main>` di sole 46 tag visibili (titolo, sottotitolo, poche
   card "Risultati per cimitero", badge "service") contro le 1227 tag della
   reference;
3. inoltra il `wire:snapshot` Livewire nel DOM (payload ~70 KB), che gonfia
   il body e rompe la comparazione token-per-token;
4. NON contiene breadcrumb, form di ricerca (lente + pulsante "Cerca"),
   filtri sidebar (Tipologie, altre tipologie, argomenti), lista risultati
   con paginazione, rating/feedback e contatti.

La reference `risultati-ricerca.html` ha invece questa struttura DOM:

- `<main>` > `h1.visually-hidden#search-container` ("Ricerca")
- `container` > `cmp-breadcrumbs` (Home / Ricerca)
- `container` > `form-group.cmp-input-search-button` > `input-group`
  (prepend con `svg#it-search` + `input[type=search]` + `btn.btn-primary`
  "Cerca")
- `container` > `row.justify-content-center`
  - `col-lg-3.d-none.d-lg-block.scroll-filter-wrapper` > `fieldset`
    (legend "Tipologie" + checkbox list con contatori, altri filtri)
  - `col-lg-9` > risultati (card con titolo, badge tipo, snippet, categoria,
    data) + paginazione
- `h2.title-medium-2-semi-bold[data-element=feedback-title]` + rating
  (`#rating-feedback`)
- `h2.title-medium-2-semi-bold` + contatti (card contatti + "Problemi in
  città")

## Tasks

- [ ] Creare/aggiornare `tests.risultati-ricerca.json` con tutti i blocchi
      della reference (breadcrumb, search form, filters, results, rating,
      contacts) — contenuto JSON-driven, nessun testo hardcoded nel blade.
- [ ] Creare un blocco riutilizzabile `components/blocks/search-results/`
      (o `catalog-page` dedicato) che renderizzi la struttura reference con
      Tailwind utilities + SVG inline (NO Bootstrap Italia, NO `btn-primary`
      come classe Bootstrap, NO sprite `bootstrap-italia`).
- [ ] Risolvere le chiavi `fixcity::search.results.*`: usare testo italiano
      dal JSON o registrare le chiavi nel language file `fixcity`.
- [ ] Eliminare il `wire:snapshot` Livewire dal body della pagina di test
      (il confronto parity deve misurare il DOM visibile, non il payload
      Livewire). Valutare rendering server-side del blocco per le pagine di
      parity.
- [ ] Filtri sidebar: checkbox `input[type=checkbox]` con contatori,
      `legend.h6.text-uppercase`, accessibili (label/for).
- [ ] Risultati: card con titolo link, badge tipo, snippet, categoria, data;
      paginazione `cmp-pagination`.
- [ ] Rating 1-5 + feedback positivo/negativo + dettaglio opzionale +
      conferma (stessa contract della homepage).
- [ ] Contatti: card "Contatta il comune" + "Problemi in città" con icone
      SVG inline.

## Acceptance criteria

1. body HTML parity >= 90% contro `risultati-ricerca.html` (tag visibili,
   esclusi script/style e snapshot Livewire).
2. visual parity >= 97% a 375/768/1440 px (controlled, con
   `--stub-images picsum.photos`).
3. HTTP 200, zero errori console, zero immagini mancanti, zero overflow
   orizzontale.
4. Zero riferimenti `bootstrap-italia` nel markup di produzione; zero classi
   Bootstrap utility (`d-none`, `d-md-block`, `btn-primary`).
5. WCAG 2.1 AA: focus visibile, label/for, contrasto, nomi accessibili.

## Verification

```bash
npm run build   # da laravel/Themes/Sixteen
node bashscripts/tools/visual/compare-body-html.mjs \
  https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html \
  http://192.168.1.40:8000/it/tests/risultati-ricerca --json
node bashscripts/tools/visual/compare-screenshots.mjs ... --stub-images picsum.photos
curl -sS http://192.168.1.40:8000/it/tests/risultati-ricerca | rg -i \
  "bootstrap-italia\.(min\.css|comuni\.css|bundle)|bootstrap\.loadFonts" | wc -l  # => 0
```

## Definition of done

Metriche allegate alla story (non valutazioni soggettive). Controlled visual
parity e' il release gate; raw parity resta evidence diagnostica.
