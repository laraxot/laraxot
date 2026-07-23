---
title: "TwentyOne Folio container0 — Filament way"
type: concept
tags: [twentyone, folio, container0, index, view, volt, mount]
created: 2026-06-06
updated: 2026-06-06
qmd: "twentyone folio container0 index view filament way mount volt"
related:
  - ../../../../../../docs/wiki/memories/folio-container0-index-filament-way.md
  - ../../route-names-philosophy.md
  - ../../blade-generic-architecture.md
---

# Folio `container0` — Filament way (TwentyOne)

## One-liner

Lista → `container0.index` + mount lineare. Dettaglio → `container0.view` + `ResolvePageAction`. **Mai** `container0.list` / `container0.detail`.

## File

| Path Folio | `name()` / `@volt()` | `$pageSlug` |
|------------|----------------------|-------------|
| `[container0]/index` | `container0.index` | `{container0}.index` |
| `[container0]/[slug0]/index` | `container0.view` | da `ResolvePageAction` (es. `predicts.view`) |

## Lista — mount lineare

```php
$this->pageSlug = $container0.'.index';
$this->data = ['container0' => $container0];
```

## Dettaglio — ResolvePageAction

```php
$resolved = app(ResolvePageAction::class)->execute($container0, $slug0);
$this->pageSlug = $resolved->pageSlug;
$this->data = ['container0' => $container0, 'slug0' => $slug0, 'item' => $resolved->item, ...];
```

Titoli/meta → blocchi CMS o layout, non match nel mount lista.

## Verifica

- Pest: `tests/Unit/FolioPageMountContractTest.php`
- Grep tema: `container0.list` = 0 (eccetto log storico)

## Backlink

- [route-names-philosophy.md](../../route-names-philosophy.md)
- [routing-architecture.md](../../routing-architecture.md)
