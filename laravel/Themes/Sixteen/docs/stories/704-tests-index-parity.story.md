---
id: "sixteen-fo-tests-index-parity"
type: "story"
title: "Tests DesignComuni: /it/tests/ index parity"
status: todo
parent: STORY-704
tags: [design-comuni, index, tailwind, alpine, parity, tests-pages, bmad]
design_ref: "https://italia.github.io/design-comuni-pagine-statiche/index.html"
local_route: "http://192.168.1.40:8000/it/tests/"
content_json: "laravel/config/local/fixcity/database/content/pages/tests.index.json"
created: 2026-10-13
updated: 2026-10-13
---

# Story 704 — Tests index parity

## Goal

Portare `http://192.168.1.40:8000/it/tests/` al minimo 90% body HTML
parity e 97% visual parity con
`https://italia.github.io/design-comuni-pagine-statiche/index.html`.

## Current state (2026-10-13)

- ref tags: 1492, live tags: 472 -> **32%**.
- La rotta e' `pages/tests/index.blade.php` (`name('tests.index')`);
  il fallback `pageSlug` di `[slug].blade.php` ora e' `tests.index`
  (fix 2026-10-13: prima era `tests`, che non corrispondeva a nessun
  contenuto JSON).

## Root cause

L'index locale e' un elenco sintetico di link alle pagine di test; la
reference e' una landing completa Design Comuni (hero, servizi, novita',
argomenti, siti tematici, contatti, footer). Manca la maggior parte dei
blocchi.

## Tasks

- [ ] Allineare `tests.index.json` ai blocchi della reference.
- [ ] Completare `pages/tests/index.blade.php` (o il blocco che
      renderizza) con i blocchi mancanti in Tailwind + SVG inline.
- [ ] Verificare che `mount()` di `[slug].blade.php` continui a
      passare `tests.index` (non `tests`) quando `$slug` e' vuoto.

## Acceptance criteria

1. body HTML parity >= 90%, visual parity >= 97% (controlled) a
   375/768/1440 px.
2. HTTP 200, zero errori console, zero overflow orizzontale.
3. Zero riferimenti `bootstrap-italia`; zero classi Bootstrap utility.
4. WCAG 2.1 AA pulito.

## Verification

```bash
npm run build   # da laravel/Themes/Sixteen
node bashscripts/tools/visual/compare-body-html.mjs \
  https://italia.github.io/design-comuni-pagine-statiche/index.html \
  http://192.168.1.40:8000/it/tests/ --json
```

## Definition of done

Metriche allegate; controlled visual parity e' il release gate.
