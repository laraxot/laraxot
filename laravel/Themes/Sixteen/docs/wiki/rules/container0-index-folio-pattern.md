# Regola: Folio Route Pattern per `container0.index`

## Sintesi

Quando si definisce una pagina Folio per `pages/[container0]/index.blade.php`:

| Elemento | Corretto | Errato |
|----------|----------|--------|
| `name()` | `name('container0.index')` | `name('container0.list')` |
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
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
```

## Perché questa regola

1. **Filament Way** — Le pagine Folio usano `.index` per le liste, non `.list`
2. **DRY** — Il component non deve contenere logica di business (titoli, meta, CMS). Quello spetta al componente Volt `container0.index`
3. **KISS** — Meno proprietà, meno bug, più facile da mantenere
4. **Separation of Concerns** — Il Blade è solo "shell", la logica vive nel Volt component

## Applicabilità

Questa regola vale per **tutti** i file `pages/[container0]/index.blade.php` nel tema Sixteen e moduli correlati.