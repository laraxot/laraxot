---
title: "Folio — parametri route via mount(), non request()->route()"
type: concept
tags: [folio, volt, mount, container0, slug0, six]
created: 2026-06-05
updated: 2026-06-05
qmd: "folio route params mount container0 slug0 request route forbidden php block"
related:
  - ../../folio-page-pattern.md
  - ../../../../../../docs/wiki/rules/laravel/volt-route-params-via-mount.md
  - ../../../../../../docs/wiki/memories/volt-route-params-mount-contract.md
  - ../../../../../../docs/wiki/rules/no-volt-in-blade-views.md
---

# Folio — parametri route via `mount()`

## Regola

Nei file Folio con segmenti `[container0]`, `[slug0]`, `[container1]`:

- **Vietato:** `@php` + `request()->route('container0')` — bypassa l'injection Folio/Livewire
- **Obbligatorio:** anonymous `Livewire\Volt\Component` + `mount(string $container0, ...)`

Folio inietta i segmenti del filename come argomenti di `mount()` (docs Laravel Folio + Livewire).

## Esempio `[container0]/index` (lista — Filament way)

```php
name('container0.index');

new class extends Component {
    public string $container0 = '';
    public string $pageSlug = '';
    public array $data = [];

    public function mount(string $container0): void
    {
        $this->container0 = $container0;
        $this->pageSlug = $container0.'.index';
        $this->data = ['container0' => $container0];
    }
};
```

```blade
<x-layouts.app>
    @volt('container0.index')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
```

Niente `match` su locale, `CmsPage`, titoli o meta nel mount — semantica in JSON CMS (`tickets.index`, …). Home = `pages/index.blade.php` con `slug="home"`.

## Esempio `[container0]/[slug0]/index` (dettaglio)

```php
new class extends Component {
    public string $container0 = '';
    public string $slug0 = '';
    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container0, string $slug0 = ''): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;
        $this->pageSlug = $container0.'.view';
        $this->data = ['container0' => $container0, 'slug0' => $slug0];
    }
};
```

```blade
<x-layouts.app>
    @volt('container0.view')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
```

### `[container0]/[slug0]/[container1]/index.blade.php`

Stesso schema: `name('container1.index')` + `@volt('container1.index')` (non `$pageSlug`).

`$pageSlug` resta `$container0.'.view'`; `container1` solo in `$data`.

`@volt('…')` deve essere **uguale** a `name('…')` — stringa statica, obbligatoria con `new class extends Component`.

## Vietato

```blade
@php
    $container0 = (string) request()->route('container0', '');
@endphp

@volt($pageSlug)
@volt('folio.' . $container0)
```

## Livelli URL (Sixteen)

| File Folio | `name()` | `@volt()` | `$pageSlug` tipico |
|------------|----------|-----------|-------------------|
| `[container0]/index` | `container0.index` | `container0.index` | `{container0}.index` |
| `[container0]/[slug0]/index` | `container0.view` | `container0.view` | `{container0}.view` |
| `[container0]/[slug0]/[container1]/index` | `container1.index` | `container1.index` | `{container0}.view` |

## Distinzioni (zen)

| Pattern | Quando |
|---------|--------|
| `mount($container0, …)` | Pagine Folio dinamiche `[container0]/...` |
| `@volt('name.folio')` | **Obbligatorio** se c'è `new class extends Component` — nome = `name()` |
| `@volt($pageSlug)` | **Mai** — slug CMS, non nome Volt |
| `request()->route()` | Mai per leggere params già nel path Folio |

## Perché

- Folio **è** il contratto URL → params; `mount()` è il lifecycle corretto
- `request()->route()` duplica, non è type-safe, rompe testabilità
- `@volt('folio.'.$container0)` valuta in compile-time → 500 (STORY-141)

## Verifica

- Pest: `tests/Unit/FolioPageMountContractTest.php`
- Grep: `rg "request\(\)->route\('container0" Themes/Sixteen/resources/views/pages`

## Backlink

- [folio-page-pattern.md](../../folio-page-pattern.md)
- [cms-x-page-data-bag-only.md](../../../../../../docs/wiki/rules/cms-x-page-data-bag-only.md)
