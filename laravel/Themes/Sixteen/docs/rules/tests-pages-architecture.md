# Tests Pages Architecture Rules

**Scope:** `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`

## Golden Rules

### 1. TailwindCSS + Alpine.js ONLY
- **NO** Bootstrap Italia CSS (`bootstrap-italia.min.css`)
- **NO** Bootstrap Italia JS (`bootstrap-italia.bundle.min.js`)
- **NO** CDN links to Bootstrap Italia
- **NO** `asset()` references to Bootstrap CSS/JS
- Bootstrap Italia **SVG sprites** for icons ARE acceptable (just icon files)
- Match Bootstrap class names via **Tailwind @apply** in `style-apply.css`

### 2. Volt Component Pattern
Pages must use Volt component class, NOT raw `@php` blocks:

```blade
<?php
new class extends Component {
    public string $slug = '';
    public string $pageSlug = '';
    public array $data = [];
    public function mount(string $slug = ''): void { ... }
};
?>
```

### 3. Layout
- Use `<x-layouts.app>` ONLY
- NEVER use `<x-layouts.design-comuni>` or custom layouts

### 4. Data Loading
- Use `\Modules\Cms\Models\Page::getBlocksBySlug($this->pageSlug, 'content')`
- NEVER inline raw PHP data loading in `@php` blocks

### 5. Multilingual
- NEVER hardcode Italian text in blade files
- Use `__('namespace::collection.key.type')` pattern
- Example: `__('fixcity::segnalazione.heading.title.label')`

### 6. Single Root Element
- Livewire/Volt components MUST have single root HTML element
- Use canonical root wrapper: `<div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">...</div>`

## Reference
- See: `pages/[container0]/[slug].blade.php` for correct pattern
- See: `docs/architecture/PAGE_ROUTING_ARCHITECTURE.md`

### 7. HTML Parity Tooling
- Use `bashscripts/html/html-structure-compare.sh` as the canonical parity script
- Pass the output path explicitly; do not hardcode theme paths inside `bashscripts`
- Canonical page output for this phase: `laravel/Themes/Sixteen/docs/prompts/<slug>/body-structure-comparison/`
