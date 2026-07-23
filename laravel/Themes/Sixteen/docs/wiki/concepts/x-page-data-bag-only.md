# Sixteen — `<x-page>` e data bag

## regola

Tutte le pagine Folio Sixteen usano **solo**:

```blade
<x-page side="content" :slug="$pageSlug" :data="$data" />
```

`container0`, `slug0`, modelli → **solo** dentro `$data` (preparato in `mount()`).

## file canonici

| Path | Ruolo |
|------|--------|
| `resources/views/pages/[container0]/[slug0]/index.blade.php` | Router agnostico |
| `resources/views/pages/[container0]/index.blade.php` | Lista container |
| `resources/views/pages/tests/[slug].blade.php` | Sandbox CMS |

## componente PHP

`<x-page>` risolve a `Modules\Cms\View\Components\Page` (non esiste più `Themes\Sixteen\View\Components\Page`).

## collegamenti

- ADR progetto: [cms-x-page-opaque-data-bag.md](../../../../../../docs/wiki/decisions/cms-x-page-opaque-data-bag.md)
- [page-context-data-bag.md](../../../../Modules/Cms/docs/components/page-context-data-bag.md)
- [x-page-data-bag-only.md](../../../../Modules/Cms/docs/wiki/concepts/x-page-data-bag-only.md)
- [folio-page-pattern.md](../../folio-page-pattern.md)
- [page-component-conflict.md](../../page-component-conflict.md)
