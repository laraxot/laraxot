# Tests Pages Architecture: Why NO design-comuni.blade.php

**Decision:** We DO NOT use `<x-layouts.design-comuni>` for tests pages.

**Pattern:** Tests pages MUST use `<x-layouts.app>` EXACTLY like CMS pages.

---

## ✅ Correct Pattern

### File: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`

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

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $slug = ''): void
    {
        $this->slug = $slug;
        $this->pageSlug = $slug ? 'tests.'.$slug : 'tests';

        $this->data = [
            'slug' => $slug,
        ];
    }
};
?>

<x-layouts.app>
    @volt('tests.view')
    {{-- Single root wrapper for Livewire --}}
    <div id="main-container" class="container cms-view-wrapper">
        @php
            // Load blocks from CMS Page model
            $blocks = \Modules\Cms\Models\Page::getBlocksBySlug($this->pageSlug, 'content');
        @endphp

        {{-- Page content via CMS blocks --}}
        <div class="page-content content" data-slug="{{ $this->pageSlug }}" data-side="content">
            @foreach($blocks as $block)
                @include($block->view, array_merge(['data' => []], $block->data))
            @endforeach
        </div>
    </div>
    @endvolt
</x-layouts.app>
```

---

## ❌ What NOT to Do

### 1. DON'T Create Custom Layout
```blade
{{-- WRONG --}}
<x-layouts.design-comuni>
    ...
</x-layouts.design-comuni>
```

**Why?**
- ❌ Duplicates `<x-layouts.app>` functionality
- ❌ Violates DRY principle
- ❌ Creates maintenance burden
- ❌ Breaks consistency with CMS pages

### 2. DON'T Use @php Blocks for Data Loading
```blade
{{-- WRONG - Logic in blade --}}
@php
    use Modules\Cms\Models\Page;
    
    $pageSlug = 'tests.'.$slug;
    $locale = app()->getLocale();
    
    $cmsPage = Page::where('slug', $pageSlug)->first();
    // ... more logic
@endphp
```

**Why?**
- ❌ Mixes concerns (logic + presentation)
- ❌ Duplicates logic from [container0]/[slug].blade.php
- ❌ Harder to test
- ❌ Violates "religion/political/zen vision" of the architecture

### 3. DON't Create Page-Specific Blades
```blade
{{-- WRONG --}}
laravel/Themes/Sixteen/resources/views/pages/tests/segnalazione-01-privacy.blade.php
```

**Why?**
- ❌ We use DYNAMIC `[slug].blade.php` pattern
- ❌ Each page should be data-driven from JSON
- ❌ Creates code duplication

---

## 🎯 Architecture Philosophy

### Same Pattern as CMS Pages

Tests pages follow the EXACT same architecture as CMS pages:

**CMS Pages:**
```
pages/[container0]/[slug].blade.php
  ↓
<x-layouts.app>
  ↓
Volt component class
  ↓
Load blocks via Page::getBlocksBySlug()
  ↓
Render blocks dynamically
```

**Tests Pages:**
```
pages/tests/[slug].blade.php
  ↓
<x-layouts.app>
  ↓
Volt component class
  ↓
Load blocks via Page::getBlocksBySlug()
  ↓
Render blocks dynamically
```

### Key Differences (Only in Data, Not Code)

| Aspect | CMS Pages | Tests Pages |
|--------|-----------|-------------|
| **Container** | `[container0]` (e.g., `famiglia`, `turismo`) | `tests` |
| **PageSlug** | `{container}.{slug}` | `tests.{slug}` |
| **JSON Location** | `pages/{container}.{slug}.json` | `pages/tests.{slug}.json` |
| **Layout** | `<x-layouts.app>` | `<x-layouts.app>` (SAME!) |
| **Block Loading** | `Page::getBlocksBySlug()` | `Page::getBlocksBySlug()` (SAME!) |

---

## 🔗 Related Documentation

- [CMS Page Component Architecture](../../Modules/Cms/docs/PAGE_COMPONENT_ARCHITECTURE.md)
- [Page Routing Architecture](../architecture/PAGE_ROUTING_ARCHITECTURE.md)
- [Folio Pages Architecture](../architecture/FOLIO_PAGES.md)
- [Design Comuni Block Analysis](_bmad-output/design-comuni-block-analysis.md)

---

**Decision Date:** 2025-04-08  
**Rationale:** DRY, KISS, consistency with CMS pages, maintainability
