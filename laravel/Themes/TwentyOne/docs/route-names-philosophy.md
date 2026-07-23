# Route Names — Filosofia e Convenzioni

## Scopo

Nomi di route Folio semantici e coerenti (Filament way) per le pagine container generiche del tema.

## Convenzione

| Pagina | Route Name | `@volt()` | File | URL esempio |
|--------|------------|-----------|------|-------------|
| Lista container | `container0.index` | `container0.index` | `[container0]/index.blade.php` | `/it/predicts` |
| Dettaglio item | `container0.view` | `container0.view` | `[container0]/[slug0]/index.blade.php` | `/it/predicts/f1-champion-2026` |

**Deprecato:** `container0.list`, `container0.detail`.

## Regola anti-pattern

**MAI** creare `pages/it/predicts/[slug].blade.php`. Il routing usa il catch-all generico
`[container0]/[slug0]/index.blade.php` per tutti i container.

## Motivazione

- **index** = pagina elenco (Filament: index action)
- **view** = pagina dettaglio (Filament: view/show)

Mount lista: solo `$pageSlug = $container0.'.index'` — semantica in JSON CMS.

Dettaglio: `ResolvePageAction` → `$pageSlug` + `item` in data bag.

## Uso nei componenti

```blade
{{-- Link alla lista --}}
<a href="{{ route('container0.index', ['container0' => 'predicts']) }}">Mercati</a>

{{-- Link al dettaglio --}}
<a href="{{ route('container0.view', ['container0' => 'predicts', 'slug0' => $slug]) }}">Dettaglio</a>
```

## Route legacy da non usare

- `container0.list`
- `container0.detail`
- `predicts.list`
- `predicts.detail`
- `predict.view`
- `predicts.show`

Nel front office pubblico del tema tutto converge su `container0.index` e `container0.view`.

## Canon condiviso

- Root: [folio-container0-index-filament-way.md](../../../../../docs/wiki/memories/folio-container0-index-filament-way.md)
- Sixteen: `laravel/Themes/Sixteen/docs/folio-page-pattern.md`
- Wiki locale: [wiki/concepts/folio-container0-filament-way.md](wiki/concepts/folio-container0-filament-way.md)
