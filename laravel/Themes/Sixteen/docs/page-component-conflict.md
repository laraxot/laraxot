# Page Component — conflitto namespace (storico)

> **Stato 2026-06-04**: risolto. `<x-page>` → **solo** `Modules\Cms\View\Components\Page`. Rimosso `Themes\Sixteen\View\Components\Page` (duplicato senza spread `$data`). Regola: [wiki/concepts/x-page-data-bag-only.md](wiki/concepts/x-page-data-bag-only.md).

## Problema (storico)

Quando si usa `<x-page side="content" :slug="$pageSlug" :data="$data" />` in Sixteen, viene usato il componente `Modules\Cms\View\Components\Page` invece del componente del tema `Themes\Sixteen\View\Components\Page`.

### Causa

1. Il namespace `sixteen` **non è registrato** - il progetto usa il namespace `pub_theme` per permettere cambio tema
2. Il componente `<x-page>` del Cms ha la priorità perché viene registrato dopo il tema

## Soluzione

Usare `@include` con il namespace `pub_theme`:

```blade
<x-layouts.app>
    @volt('tests.view')
    <div>
        {{-- Usare @include con pub_theme namespace (standard per i temi) --}}
        @include('pub_theme::components.page', ['side' => 'content', 'slug' => $pageSlug, 'data' => $data])
    </div>
    @endvolt
</x-layouts.app>
```

## Perché NON usare `<x-page>`

- Il namespace `sixteen` non esiste - solo `pub_theme` è registrato
- `<x-page>` richiamerebbe `Modules\Cms\View\Components\Page` (non quello del tema)
- Non c'è modo di registrare `sixteen::page` perché il tema può cambiare

## Perché `pub_theme` funziona

1. `pub_theme` è registrato come namespace nel CmsServiceProvider
2. Il file `components/page.blade.php` esiste in `Themes/Sixteen/resources/views/components/`
3. Il namespace `pub_theme` → `Themes/Sixteen/resources/views` (tema attivo)

## Regola aggiornata (2026-05-20)

| Contesto | Pattern |
|----------|---------|
| Route Folio Sixteen (tests, container0, home) | `<x-page side="content" :slug="$pageSlug" :data="$data" />` |
| Override markup tema senza classe PHP | `@include('pub_theme::components.page', [...])` solo se serve view anonima tema |
| Fetch blocchi nel route | **Vietato** — delegare a `Modules\Cms\View\Components\Page` |

Discussione e acceptance: [GitHub #114](https://github.com/laraxot/base_fixcity_fila5/issues/114)

## Regola legacy (non più canonica)

~~**Usare SEMPRE `@include('pub_theme::components.page')` per le pagine del tema, MAI `<x-page>`**~~