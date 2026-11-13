---
title: "layout runtime contract"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "layout runtime contract"
issues: []
discussions: []
status: draft
---

# Layout Runtime Contract

## Scopo

Il tema deve separare in modo netto tre responsabilita:

- `pages/tests/[slug].blade.php` e `pages/tests/index.blade.php`: routing Folio + stato Volt + invocazione di `<x-page />`
- `layouts/app.blade.php`: struttura pubblica comune del frontoffice
- `layouts/main.blade.php`: shell HTML, Vite, Filament, script globali

Questa separazione evita che una pagina CMS ridefinisca header, footer o skiplinks.

## Contratto

### Identità del layout pubblico

`Sixteen` è già la resa Design Comuni. Per questo il componente pubblico
canonico è `components/layouts/app.blade.php` (`x-layouts.app`): il valore CMS
storico `layout: design-comuni` viene interpretato dal resolver come la stessa
shell e non come un nome di view. `components/layouts/design-comuni.blade.php`
non deve esistere, nemmeno come alias, perché reintrodurrebbe il secondo
entrypoint che questa regola elimina.

`design-comuni-authenticated` e `design-comuni-bare` restano solo per i template
di test che dichiarano esplicitamente quei contratti: il primo modifica lo
stato del menu account, il secondo omette intenzionalmente il chrome globale.
Non sono sostituti del layout pubblico.

### `pub_theme::layouts.app`

Deve contenere solo struttura pubblica condivisa:

- skiplinks
- `<x-section slug="header" />`
- `<main id="main-container">{{ $slot }}</main>`
- `<x-section slug="footer" tpl="full" />`

Non deve contenere logica di pagina specifica.

### `x-layouts.main`

Deve contenere solo shell tecnica condivisa:

- `<!DOCTYPE html>`
- `<head>`
- `@vite(['resources/css/app.css'], 'themes/Sixteen')`
- `@vite(['resources/js/app.js'], 'themes/Sixteen')`
- Filament styles/scripts
- script globali e asset globali

Non deve contenere header, footer o blocchi CMS.

### `pages/tests/[slug].blade.php`

Deve contenere solo:

- `name()` e `middleware()` Folio
- classe Volt con `slug`, `pageSlug`, `data`
- `<x-layouts.app>`
- `<x-page side="content" :slug="$pageSlug" :data="$data" />`

Non deve importare `File`, consultare `tests.catalog.json`, eseguire fallback
per slug speciali o includere blocchi direttamente. Il JSON canonico è
`database/content/pages/tests/<slug>.json` (lo slug nel file è l'ultimo pezzo, la cartella `tests` lo prefissa); la risoluzione e il rendering dei
blocchi restano responsabilità del CMS e del tema.

Se il componente viene montato senza parametro, il valore canonico è
`tests.index`, mai `tests`: il file è `pages/tests/` con slug `index`.

## Perche

### DRY

Header, footer e skiplinks sono riutilizzati da tutte le pagine pubbliche. Tenerli nelle page blades duplica markup e crea divergenze tra slug diversi.

### KISS

La pagina dinamica deve sapere solo quale slug caricare. Tutto il resto e responsabilita del layout.

### CMS-driven

`<x-page />` legge blocchi JSON. Se la struttura globale vive nella singola pagina, il confine tra contenuto e shell applicativa si rompe.

## Bridge runtime

Nel runtime corrente `<x-layouts.main>` e risolto dal componente anonimo in `resources/views/components/layouts/main.blade.php`.

Per mantenere una sola fonte di verita, quel file deve fare da bridge verso `resources/views/layouts/main.blade.php` invece di duplicare la shell HTML.

## Collegamenti

- [README.md](./README.md)
- [design-comuni-page-census.md](./design-comuni-page-census.md)
- [../../Modules/Cms/docs/layout-runtime-contract.md](../../../Modules/Cms/docs/layout-runtime-contract.md)
