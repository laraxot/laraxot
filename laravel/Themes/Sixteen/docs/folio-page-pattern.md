---
title: "Sixteen Folio Page Pattern - mount() + x-page"
type: guide
tags: [theme-sixteen, folio, blade, x-page, data-bag, mount]
created: 2026-06-05
updated: 2026-06-06
qmd: "sixteen folio page pattern mount container0 slug0 x-page data bag no request route"
issues:
  - "https://github.com/laraxot/theme_sixteen_fila5/issues/50"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ../../../../docs/wiki/bmad/architecture-folio-page-shell.md
  - docs/wiki/concepts/folio-route-params-mount.md
---

# Folio Page Pattern — `mount()` + `<x-page>`

## Regola fondamentale

Pagine Folio dinamiche (`[container0]`, `[slug0]`, …): **parametri route in `mount()`**, non in `@php` con `request()->route()`.

Il rendering CMS resta in `<x-page>`; contesto solo in `:data` (data bag).

## Esempio corretto

```php
<?php
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('container0.view');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';
    public string $slug0 = '';
    public string $pageSlug = '';
    public array $data = [];

    public function mount(string $container0, string $slug0 = ''): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;
        $this->pageSlug = $container0.'.view';
        $this->data = ['container0' => $container0, 'slug0' => $slug0];
    }
};
?>

<x-layouts.app>
    @volt('container0.view')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
```

Con `new class extends Component`, `@volt('…')` statico (stesso valore di `name()`) è **obbligatorio** — senza → `VoltDirectiveMissingException`.

| File Folio | `name()` | `@volt()` |
|------------|----------|-----------|
| `[container0]/index` | `container0.index` | `container0.index` |
| `[container0]/[slug0]/index` | `container0.view` | `container0.view` |
| `[container0]/[slug0]/[container1]/index` | `container1.index` | `container1.index` |

**Mai** `@volt($pageSlug)` — `$pageSlug` dipende da `$container0` a runtime.

## Vietato

```blade
@props(['container0' => 'string', 'pageSlug' => 'string'])  {{-- ❌ Folio pages --}}
@php
    $pageSlug = $container0.'.index';
@endphp
@extends('layouts.app')
@section('content')
    <x-layouts.app>...</x-layouts.app>
@endsection

@php
    $container0 = (string) request()->route('container0', '');
@endphp

@volt($pageSlug)
@volt('folio.' . $container0 . '.view')
```

`@props` resta valido solo nei **componenti** (`components/`, blocchi CMS), non nei file `pages/` Folio.

## Livelli URL (Sixteen)

| Path Folio | `name()` | `@volt()` |
|------------|----------|-----------|
| `[container0]/index` | `container0.index` | `container0.index` |
| `[container0]/[slug0]/index` | `container0.view` | `container0.view` |
| `[container0]/[slug0]/[container1]/index` | `container1.index` | `container1.index` |

`$pageSlug` (es. `tickets.index`, `services.view`) è slug CMS per `<x-page>` — **non** va in `@volt()`.

### `[container0]/index` — mount lineare (Filament way)

```php
name('container0.index');

new class extends Component {
    public function mount(string $container0): void
    {
        $this->container0 = $container0;
        $this->pageSlug = $container0.'.index';
        $this->data = ['container0' => $container0];
    }
};
```

**Vietato nel mount:** `match` locale→`home`, `CmsPage`, `pageTitle`, `metaDescription`. Home = `pages/index.blade.php` (`slug="home"`); liste = JSON `{container}.index`.

## Responsabilità

| Layer | Ruolo |
|-------|-------|
| Folio + `mount()` | Riceve segmenti URL, costruisce `$pageSlug` e `$data` |
| `@volt` statico | Collega template SFC a `name()` |
| `<x-page>` | Carica blocchi CMS |
| Modulo | Business logic |

## Collegamenti

- [folio-route-params-mount.md](docs/wiki/concepts/folio-route-params-mount.md)
- [cms-x-page-data-bag-only.md](../../../docs/wiki/rules/cms-x-page-data-bag-only.md)
- STORY-141 (solo anti-pattern `@volt` dinamico)
