# Design Comuni - Block Development Rules

**Created:** 2026-04-08  
**Status:** Active  
**Type:** Architectural Rules

---

## 1. Tech Stack: TailwindCSS + Alpine.js ONLY

### Core Principle
Replicate Design Comuni pages using TailwindCSS + Alpine.js.
Bootstrap Italia is the **visual reference only**, NOT a dependency.

### Forbidden
```blade
<script src="...bootstrap-italia.bundle.min.js"></script>
<link rel="stylesheet" href="...bootstrap-italia.min.css" />
@extends('pub_theme::layouts.bootstrap-italia')
<x-layouts.design-comuni>
```

### Required
```blade
@vite(['resources/css/app.css'], 'themes/Sixteen')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<x-layouts.app>
```

### Exception: data-bs-* Attributes for HTML Parity
`data-bs-toggle`, `data-bs-target`, `data-bs-dismiss` **MUST be kept** for HTML structure parity with the reference.
The comparison script checks these as structural attributes.
**Add Alpine.js IN PARALLEL**, don't replace:

```blade
<!-- ✅ CORRECT: data-bs for parity + Alpine for behavior -->
<button data-bs-toggle="dropdown" @click="open = !open">Toggle</button>
<button data-bs-dismiss="modal" @click="modalOpen = false">Chiudi</button>
<button data-bs-toggle="collapse" data-bs-target="#collapse-one" @click="accordionOpen = !accordionOpen">Toggle</button>
<a data-bs-toggle="tab" @click="activeTab = 'tab1'">Tab 1</a>

<!-- ❌ WRONG: removing data-bs attributes breaks HTML parity -->
<button @click="open = !open">Toggle</button>
```

### Exception: SVG Sprite Paths
SVG sprite paths like `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg` are acceptable - these are static icon files, NOT Bootstrap framework code.

### Exception
SVG sprite paths like `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg` are acceptable - these are static icon files, NOT Bootstrap framework code.

---

## 2. Alpine.js Replaces Bootstrap JS

| Bootstrap JS | Alpine.js Equivalent |
|-------------|---------------------|
| `data-bs-toggle="dropdown"` | `x-data="{ open: false }" @click="open = !open"` |
| `data-bs-toggle="collapse"` | `x-data="{ open: false }" x-show="open"` |
| `data-bs-toggle="modal"` | `x-data="{ show: false }" @click="show = true"` |
| `data-bs-dismiss="modal"` | `@click="show = false"` |
| `aria-expanded="false"` (static) | `:aria-expanded="open.toString()"` (dynamic) |

### Dropdown Pattern
```blade
<div class="dropdown" x-data="{ shareOpen: false }">
    <button @click="shareOpen = !shareOpen" :aria-expanded="shareOpen.toString()">
        Toggle
    </button>
    <div class="dropdown-menu" x-show="shareOpen" @click.away="shareOpen = false" x-cloak>
        <!-- items -->
    </div>
</div>
```

---

## 3. Test Pages Must Use `<x-layouts.app>`

All `/it/tests/` pages use the `[slug].blade.php` pattern with `<x-layouts.app>`.

### FORBIDDEN
```blade
<x-layouts.design-comuni>
<x-layouts.bootstrap-italia>
```

### CORRECT
```blade
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

---

## 4. Volt Pattern Must Match `[container0]/[slug].blade.php`

### FORBIDDEN
```blade
@php
    use Modules\Cms\Models\Page;
    $cmsPage = Page::where('slug', $pageSlug)->first();
    foreach ($rawBlocks as $blockData) { ... }
@endphp
```

### CORRECT
```blade
<?php
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

---

## 5. All Text Must Be Translatable

### Translation Key Pattern
`<namespace>::<context>.<collection>.<key>.<type>`

| Bad | Good |
|-----|------|
| `SEGNALAZIONE::SEGNALAZIONE.ELENCO.TITLE` | `fixcity::segnalazione.fields.title.label` |
| `fixcity::segnalazione.heading.title_label` | `fixcity::segnalazione.heading.title.label` |

### Correct Usage in Blade
```blade
{{ __($ns.'.detail.share.label') }}
{{ __($ns.'.detail.actions.aria') }}
```

---

## 6. HTML Structure Must Match Reference

### Same Semantic HTML
- Reference uses `<nav>`, `<ol>`, `<li>` → We use `<nav>`, `<ol>`, `<li>`
- Reference uses `<main id="main-container">` → We use `<main id="main-container">`
- Reference uses `<footer class="it-footer">` → We use `<footer class="it-footer">`

### Same CSS Classes
- Reference uses `btn btn-primary` → We use `btn btn-primary` (replicated via Tailwind @apply)
- Reference uses `dropdown-menu shadow-lg` → We use `dropdown-menu shadow-lg`

### Acceptable Differences
- `data-bs-toggle="dropdown"` → `@click="open = !open"` (Alpine replaces Bootstrap JS)
- `aria-expanded="false"` → `:aria-expanded="open.toString()"` (dynamic binding)
- `href="sprites.svg#icon"` → `xlink:href="sprites.svg#icon"` (SVG compatibility)

---

## Cross-References

- → [Master Docs Index](../../../docs/MODULE_DOCS_INDEX.md)
- → [HTML Structure Comparison](../../../docs/html-structure-comparison.md)
- → [Body Structure Comparison Index](../body-structure-comparison/INDEX.md)
- → [Comparison Scripts](../../../../bashscripts/docs/html/README.md)

---

**Last Updated:** 2026-04-08  
**Next Review:** 2026-04-15  
**Owner:** Frontend Team
