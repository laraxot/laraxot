---
type: investigation
theme: Sixteen
slug: sixteen-architecture-analysis
stage: Investigation
status: completed
updated: 2026-10-11
issues:
  - https://github.com/laraxot/base_fixcity_fila5/issues/383
discussions:
  - https://github.com/laraxot/base_fixcity_fila5/discussions/392
created: 2026-10-11
title: "Sixteen — Architettura Folio + JSON content blocks + PageSlugMiddleware"
tags: [docs, sixteen, investigation]
qmd: "sixteen   architettura folio + json content blocks + pageslugmiddleware sixteen"
---

# Sixteen — Architettura Folio + JSON content blocks + PageSlugMiddleware

## 1. Folio page shell (routes generiche)

Struttura file chiave (`resources/views/pages/`):

- `index.blade.php` — `name('home')` + `PageSlugMiddleware`
- `[container0]/index.blade.php` — `name('container0.index')`
- `[container0]/[slug0]/index.blade.php` — `name('container0.view')` + `ResolvePageAction`
- `[container0]/[slug0]/[container1]/index.blade.php` — `name('container0.index')`
- `[container0]/[slug0]/[container1]/[token].blade.php` — `name('container1.token')`
- `tests/[slug].blade.php` — `name('tests.view')`

Ogni shell usa `@volt` Livewire con `x-page` (`side="content"`, `:slug="$pageSlug"`). La `Page` component (`app/View/Components/Page.php`) carica i blocchi dal modello CMS tramite `PageModel::getBlocksBySlug()`.

## 2. JSON CMS content (local/fixcity)

File: `laravel/config/local/fixcity/database/content/pages/*.json`

- `home.json`: slug `home`, `content_blocks` per locale (`it`/`en`), schema `type` + `data.view`
- `tickets.index.json`: slug `tickets.index`
- `tickets.create.json` / `tickets.confirmation.json`

Schema blocco: `type` (es. `home.hero`), `data.view` (blade include), `data` (parametri). `BlockData` (modulo Cms) verifica che `view` esista.

## 3. Page Component (`resources/views/components/page.blade.php`)

- Riceve `slug`, `side`, `data` (bag runtime)
- Chiama `CmsPage::getBlocksBySlug()` o usa i `blocks` passati
- Per ogni blocco: `@include($block->view, array_merge($blockData, $pageData))`
- Nessun accesso diretto al JSON; il resolver CMS (`Sushi`/`Page`) fornisce i blocchi

## 4. PageSlugMiddleware (`Modules/Cms/app/Http/Middleware/PageSlugMiddleware.php`)

- Risolve slug CMS da `route()->getName()` (es. `tickets.create`) o da parametri (`container0.slug0`)
- Cerca in `Page::findUniqueBySlug()`
- Se trovato, esegue middleware dichiarati nel JSON CMS (`middleware` array, es. `["auth"]`)
- **Nota:** usa solo alias middleware singoli, non gruppi (`["web","auth"]` fallirebbe)
- Usato in tutte le pagine Folio per sincronizzare auth CMS con la richiesta

## 5. ResolvePageAction (`Modules/Cms/app/Actions/ResolvePageAction.php`)

Priorità per `[container0]/[slug0]`:
1. Carica modello dinamico (`loadDynamicModel`) tramite mapping `xra.container0_model_map`
2. Cerca pagina CMS con slug esatto (`container0.slug0`)
3. Fallback a `container0.view`

Restituisce `ResolvePageData` con `renderMode`, `item`, `pageSlug`.

## 6. Block components (esempi chiave)

- `hero/home.blade.php`: hero Design Comuni, token CSS (`fc-hero`), `x-data` motion
- `how-it-works.blade.php`: griglia step, numeri decorativi, `prefers-reduced-motion`
- `quick-actions.blade.php`: CTA con icone Bootstrap Italia
- `ticket-layout/layout.blade.php`: layout lista segnalazioni
- `map-preview.blade.php`: mappa pubblico con `leaflet`

Tutti usano token CSS Design Comuni (`fc-*`), non classi Bootstrap generiche.

## 7. Design Comuni — stack moderno (cosa cambiare)

### Stato attuale
- CSS: `bootstrap-italia.css`, `design-comuni-global.css`, token locali (`fc-*`)
- Font: non esplicito in Sixteen (eredita Bootstrap Italia)
- Componenti: Blade include con `x-data`, `data-reveal`, `prefers-reduced-motion`
- No CDN Bootstrap Italia nel markup moderno (già rimosso, come da design-comuni v2.4.0)

### Cambi per stack moderno
1. **Token CSS**: mantenere `fc-*` ma allineare a `tailwind.config.js` (già presente in `resources/css/filament/`). Aggiungere variabili CSS per colori Design Comuni (`primary`, `secondary`, `neutral`).
2. **Componenti blocco**: migrare da include Blade a componenti Blade (`@component`) con props tipizzati (`string $view`, `array $data`). Evitare `mixed` nei parametri.
3. **Motion**: `motion-reveal.js` + `prefers-reduced-motion` già presente; mantenere ma documentare API.
4. **PageSlugMiddleware**: nessuna modifica; funziona con JSON CMS attuale. Verificare che `tickets.create` abbia `middleware: ["auth"]` (non `web`).
5. **Folio shell**: mantenere `name()` e `middleware()`; aggiornare `mount()` per usare `ResolvePageAction::execute()` con tipi concreti (`ResolvePageData`).
6. **BlockData**: verificare in `Cms` che `view` sia validato; aggiungere `BlockCategoryRegistryData` se mancano categorie.

## 8. Route / naming conventions

- `container0.index` → lista (`[container0]/index.blade.php`)
- `container0.view` → dettaglio (`[container0]/[slug0]/index.blade.php`)
- `tests.view` → test parity (`tests/[slug].blade.php`)
- Tutte usano `PageSlugMiddleware`; nessuna rotta usa controller HTTP (regola `no-controllers-rule` rispettata)

## 9. File audit / note operative

- `Pages/` generiche (`[container0]/...`) usano `ResolvePageAction`
- `Pages_old/` contiene vecchi pattern con `container0.slug` diretto; non più usato
- `components/blocks/` contiene ~60 blocchi; molti con `.blade.php.lock` (version lock)
- `docs/bmad/json-driven-homepage/` documenta già l'architettura; questo file integra
