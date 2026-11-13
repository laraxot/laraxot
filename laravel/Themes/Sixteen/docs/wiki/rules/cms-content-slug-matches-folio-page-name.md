---
qmd: "cms content slug matches folio page name"
title: "CMS content slug = Folio page name (two page families)"
type: rule
theme: Sixteen
confidence: high
created: 2026-10-13
updated: 2026-10-13
tags: [sixteen, cms, content, slug, folio, tests-pages]
related:
  - ../../../../../Modules/Fixcity/docs/tests-pages-content-files.md
  - ../concepts/test-page-routing.md
  - ../../../../../Modules/Cms/app/Models/Traits/HasBlocks.php
status: draft
---

# CMS content slug = Folio page name

Il file JSON di contenuto di una pagina si chiama **esattamente come lo
slug che la pagina interroga**, e lo slug e' il **nome della pagina
Folio** (`name('...')`), non il percorso URL e non una convenzione
"globale".

## Le due famiglie di pagine

1. **Pagine app** (Folio per modulo, es. `Modules/Fixcity/resources/views/pages/tickets/create.blade.php`):
   - `name('tickets.create')` → rotta `/it/tickets/create`
   - La vista: `<x-page side="content" slug="tickets.create" />`
   - `x-page` → `Page::getBlocksBySlug('tickets.create')` →
     `where('slug', 'tickets.create')` → Sushi legge
     **`tickets.create.json`**
   - **NON esiste** e **non deve esistere** un
     `tests.tickets-create.json`: il prefisso `tests.` non c'entra
     nulla con questa famiglia.

2. **Famiglia di anteprima parity Design Comuni** (route dinamica):
   - `Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
   - Costruisce `'tests.' . $slug` → `tests.<slug>.json`
   - URL: `/it/tests/<slug>` (homepage, eventi, risultati-ricerca, ...)

## Perché la confusione è facile

- Entrambe le famiglie vivono nella stessa cartella
  `database/content/pages/`;
- Il blocco di `tickets.create.json` punta a una vista del tema che
  sta (storicamente) sotto `components/blocks/tests/ticket-crea.blade.php`:
  **la cartella della vista non determina lo slug**;
- `/it/tests/*` e `/it/tickets/*` sono URL simili ma famiglie diverse.

## Regola operativa

Prima di creare o cercare un JSON:
1. Trova la vista Folio della rotta (`rg "name\('...'"`);
2. Leggi lo `slug` passato a `<x-page>` (o il `pageSlug` costruito);
3. Il JSON si chiama `<slug>.json` e il suo campo `slug` deve essere
   identico (altrimenti `getBlocksBySlug()` lancia
   `ModelNotFoundException` e la pagina renderizza vuota);
4. Mai inventare un prefisso: se la pagina non è sotto `pages/tests/`,
   lo slug non inizia con `tests.`.

## Evidence

- `pages/tickets/create.blade.php:14` → `name('tickets.create')`
- `tickets.create.json` → `"slug": "tickets.create"`, blocco
  `ticket_create` → `pub_theme::components.blocks.tests.ticket-crea`
- `HasBlocks::getBlocksBySlug()` query su `slug` esatto

Nota verificata 2026-10-13 (DCP-35 S4): per `/it/tickets/create` la Folio che risponde è la generica `Themes/Sixteen/resources/views/pages/[container0]/[slug0]/index.blade.php` (slug = `<container0>.<slug0>` = `tickets.create`, dal `data-slug` della risposta); `pages/tickets/create.blade.php` del modulo è registrata ma non risponde. La regola «JSON = slug» resta valida. Dettagli: `Modules/Fixcity/docs/wiki/concepts/ticket-crea-url-routing-chain.md`.
