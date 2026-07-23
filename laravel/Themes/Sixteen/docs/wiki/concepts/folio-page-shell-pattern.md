---
title: "Folio Page Shell Pattern - Volt Component"
type: concept
tags: [folio, volt, page-shell, pattern, architecture]
created: 2026-06-06
updated: 2026-06-06
issues:
  - "https://github.com/laraxot/theme_sixteen_fila5/issues/65"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/287"
related:
  - ../../../docs/wiki/rules/folio-frontoffice-navigation-links.md
  - ../../../docs/wiki/concepts/fo-folio-links-multilingua.md
---

# Folio Page Shell Pattern - Volt Component

## Pattern per le pagine Folio `[container0]/index.blade.php`

### File corretto
```php
<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('container0.index');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';

    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container0): void
    {
        $this->container0 = $container0;
        $this->pageSlug = $container0.'.index';
        $this->data = ['container0' => $container0];
    }
};
?>

<x-layouts.app>
    @volt('container0.index')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
```

### File sbagliato (da evitare)
```php
@props([...])  {{-- NON usare @props --}}

@php
    $pageSlug = $container0.'.index';
?>
@extends('layouts.app')

@section('content')
    <x-layouts.app>
        @volt('container0.index')
        ...
    @endvolt
@endsection
```

## Perché questo pattern

1. **Folio routing**: `name('container0.index')` è il nome della route Folio
2. **Volt component**: `new class extends Component` definisce il montante
3. **Middleware**: `PageSlugMiddleware` gestisce lo slug della pagina
4. **Layout**: `<x-layouts.app>` è il layout principale
5. **Page component**: `<x-page>` renderizza il contenuto dinamico

## Regola
> **Le pagine Folio devono usare il Volt Component pattern, non @props/@php/@extends.**

## Verifica
- [ ] `name('container0.index')` corretto
- [ ] `new class extends Component` presente
- [ ] Nessun `@props` o `@extends`
- [ ] `@volt('container0.index')` coerente con il nome

## Backlink
- [TRIGGER_MAP](../../../docs/wiki/rules/00-TRIGGER_MAP.md)