# Filosofia TailwindCSS + Alpine (NO Bootstrap Italia)

> **REGOLA FONDAMENTALE**: Il tema Sixteen usa TailwindCSS + Alpine.js. MAI Bootstrap Italia JS/CSS.

## 🎯 Principio

Il progetto Design Comuni ha come obiettivo la migrazione completa da **Bootstrap Italia** a **TailwindCSS + Alpine.js**, mantenendo la conformità alle Linee Guida di Design per i Servizi Digitali della PA.

## ✅ Stack Tecnologico Consentito

| Tecnologia | Uso | Dove |
|------------|-----|------|
| **TailwindCSS v4** | Styling di tutti i componenti | `resources/css/app.css` |
| **Alpine.js** | Interattività leggera (toggle, modal, dropdown) | `resources/js/app.js` |
| **Livewire Volt** | Componenti dinamici nelle pagine CMS | `pages/[container0]/[slug].blade.php` |
| **SVG Sprites** | Icone Bootstrap Italia (solo SVG, NO JS) | `design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg` |

## ❌ MAI Utilizzare

```blade
{{-- MAI Bootstrap Italia JS --}}
<script src="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/js/bootstrap-italia.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-italia@2.8.8/dist/js/bootstrap-italia.bundle.min.js"></script>

{{-- MAI Bootstrap Italia CSS --}}
<link rel="stylesheet" href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/css/bootstrap-italia.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-italia@2.8.8/dist/css/bootstrap-italia.min.css">

{{-- MAI Bootstrap vanilla JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

## 📐 Architettura Layout

### Gerarchia Corretta

```
main.blade.php (Base layout - HTML boilerplate, Vite assets Tailwind+Alpine)
    ↓
app.blade.php (Public layout - Header, Footer, Skip links, <main>)
    ↓
[slug].blade.php (Page template - Solo contenuto, Livewire Volt)
```

### `main.blade.php` - Base Layout

**File:** `resources/views/components/layouts/main.blade.php`

```blade
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
</head>
<body>
    {{ $slot }}
</body>
```

- ✅ TailwindCSS via Vite
- ✅ Alpine.js via Vite
- ❌ NO Bootstrap Italia

### `app.blade.php` - Public Layout

**File:** `resources/views/components/layouts/app.blade.php`

```blade
<x-layouts.main>
    <x-section slug="header" />
    <main>{{ $slot }}</main>
    <x-section slug="footer" tpl="full" />
</x-layouts.main>
```

### `[slug].blade.php` - Page Template

**File:** `resources/views/pages/tests/[slug].blade.php` (stessa filosofia di `[container0]/[slug].blade.php`)

```php
<?php
declare(strict_types=1);
use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('tests.view');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $slug = '';
    public string $pageSlug = '';
    public array $data = [];

    public function mount(string $slug = ''): void
    {
        $this->slug = $slug;
        $this->pageSlug = 'tests.'.$slug;
        $this->data = ['slug' => $slug];
    }
};
?>

<x-layouts.app>
    @volt('tests.view')
    <div class="cms-view-wrapper">
        @php
            $blocks = \Modules\Cms\Models\Page::getBlocksBySlug($this->pageSlug, 'content');
        @endphp
        <x-page side="content" :slug="$this->pageSlug" :data="$this->data" :blocks="$blocks" />
    </div>
    @endvolt
</x-layouts.app>
```

## 🔄 Bootstrap Italia → Tailwind Mapping

I componenti Bootstrap Italia vengono replicati con classi Tailwind usando `@apply`:

| Bootstrap Italia | TailwindCSS (via @apply) |
|-----------------|-------------------------|
| `.btn.btn-primary` | `@apply bg-primary-500 text-white px-4 py-2 rounded` |
| `.card` | `@apply bg-white rounded-lg shadow` |
| `.accordion-collapse` | `@apply overflow-hidden transition-all duration-300` |
| `.modal.fade` | Alpine.js: `x-show="open" x-transition` |
| `.navbar` | `@apply flex items-center justify-between` |

## 📚 Documenti Correlati

- [Layout Architecture](./layout-architecture.md)
- [Page Component Architecture](./page-component-architecture.md)
- [Bootstrap Italia → Tailwind Migration](../bootstrap-italia-to-tailwind.md)
- [CSS/JS Parity](../design-comuni/css-js-parity-2026-04-04.md)
