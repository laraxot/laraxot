# TwentyOne Theme — Routing Architecture

## The theme is the sole front-office

The theme owns **all** public-facing routing via Laravel Folio.
Modules provide logic and components — the theme decides how to render them.

## Key pages

| File | Route | Purpose |
|------|-------|---------|
| `pages/[container0]/[slug0]/index.blade.php` | `/{locale}/{container}/{slug}` | Catch-all detail page (`container0.view`) |
| `pages/[container0]/index.blade.php` | `/{locale}/{container}` | Container list page (`container0.index`) |
| `pages/index.blade.php` | `/{locale}` | Homepage |

## Catch-all flow

```
/it/predicts/{slug}
  → [container0]/[slug0]/index.blade.php
  → ResolvePageAction::execute('predicts', '{slug}')
  → pageSlug = 'predicts.view'
  → <x-page slug="predicts.view" side="content" :data="$data">
  → CMS blocks from config/local/predict/database/content/pages/predicts-view.json
  → Filament Widgets rendered via @livewire(...)
```

## Route names

Vedi [route-names-philosophy.md](route-names-philosophy.md) per convenzioni e uso.

## Why modules must NOT have conflicting Folio pages

`FolioVoltServiceProvider` registers all `Modules/*/resources/views/pages/` paths.
A module page at `predicts/[slug].blade.php` would match before this catch-all,
bypassing the CMS block system entirely.

**Rule**: rename conflicting module pages to `.blade.php.old`.

## Folio registration order

1. `Themes/TwentyOne/resources/views/pages/` (theme — registered first)
2. All `Modules/*/resources/views/pages/` (modules — registered after)

Folio resolves by first match. The theme catch-all wins for all `/{container}/{slug}` URLs
as long as no module has a more specific matching file.
