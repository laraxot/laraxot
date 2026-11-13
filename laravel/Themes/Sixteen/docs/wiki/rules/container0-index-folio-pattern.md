---
title: "container0 index folio pattern"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-10-22
qmd: "container0 index folio pattern resolve action aliases"
issues: []
discussions: []
status: draft
---

# Regola: Folio Route Pattern per `container0.index`

## Sintesi

Quando si definisce una pagina Folio per `pages/[container0]/index.blade.php`:

| Elemento | Corretto | Errato |
|----------|----------|--------|
| `name()` | `name('container0.index')` | `name('container0.list')` |
| `render()` | `ResolveContainer0IndexAction` | alias / `Auth::check` / `abort` inline |
| Proprietà Component | Solo `container0`, `pageSlug`, `data` | Aggiungere `pageTitle`, `metaDescription`, logica complessa |
| `mount()` | Semplice: `$this->pageSlug = $container0.'.index'` | `match()` con locale, CMS, fallback |

## File di riferimento

```
laravel/Themes/Sixteen/resources/views/pages/[container0]/index.blade.php
```

## Codice corretto

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
        <div class="page-content content" data-side="content">
            <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
        </div>
    @endvolt
</x-layouts.app>
```

## Perché questa regola

1. **Filament Way** — liste FO = slug `{container}.index`, non `.list`
2. **DRY** — titoli/meta/blocchi nel JSON CMS; la Folio page non li ricalcola
3. **KISS** — mount lineare + `<x-page>`; zero `match` dominio nella shell
4. **Separation of Concerns** — tema = vestito; Cms/moduli = contenuto e widget Filament

## Applicabilità

Questa regola vale per **tutti** i file `pages/[container0]/index.blade.php` nel tema Sixteen e moduli correlati.

## Anti-pattern rimossi (D20)

Vietati anche: `nativePage`/`utilityPage`, `@include` di `components/pages/*`,
`x-dynamic-component` per il layout, chiusura tag mismatched.
Contratto: [container0-index-shell-contract.md](../../bmad/container0-index-shell-contract.md).