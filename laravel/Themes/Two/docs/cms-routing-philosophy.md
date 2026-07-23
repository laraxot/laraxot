# CMS Routing Philosophy - The [slug] Dynamic Page System

## Core Principle: ONE Blade, MANY Pages

The CMS uses a **single dynamic Folio page** to handle ALL content pages:

```
pages/pages/[slug].blade.php  →  /it/pages/{slug}
```

This ONE file loads content from JSON files dynamically:
- `/it/pages/blog` → reads `blog.json`
- `/it/pages/about` → reads `about.json`
- `/it/pages/faq` → reads `faq.json`
- `/it/pages/services` → reads `services.json`
- `/it/pages/contacts` → reads `contacts.json`
- etc.

## NEVER Create Individual Page Blade Files

**WRONG:**
```
pages/pages/about/index.blade.php    ← DON'T DO THIS
pages/pages/blog/index.blade.php     ← DON'T DO THIS
pages/pages/faq/index.blade.php      ← DON'T DO THIS
```

**RIGHT:**
```
pages/pages/[slug].blade.php         ← THIS handles ALL pages
config/local/techplanner/database/content/pages/about.json  ← Create JSON instead
```

## How It Works

### 1. Dynamic Route (`[slug].blade.php`)
```php
<?php
declare(strict_types=1);
use function Laravel\Folio\{name};
use Livewire\Volt\Component;

name('pages.view');

new class extends Component {
    public string $slug;
};
?>

<x-layouts.app>
    @volt('pages.view')
    <div>
        <x-page side="content" :slug="$slug" />
    </div>
    @endvolt
</x-layouts.app>
```

### 2. Page Component (`Modules/Cms/View/Components/Page.php`)
- Receives `$slug` parameter
- Loads page from `PageModel::firstWhere('slug', $slug)`
- Extracts blocks for current locale (`it`, `en`, etc.)
- Converts to `BlockData` collection
- Renders via `cms::components.page-content`

### 3. Block Rendering (`page-content.blade.php`)
```blade
@foreach($blocks as $block)
    @if(isset($block->view) && view()->exists($block->view))
        @include($block->view, $block->data)
    @else
        <div class="bg-red-100 ...">View not found: {{ $block->view }}</div>
    @endif
@endforeach
```

### 4. JSON Content Structure
```json
{
    "slug": "about",
    "title": {"it": "Chi Siamo", "en": "About Us"},
    "content_blocks": {
        "it": [
            {
                "type": "hero",
                "slug": "about-hero",
                "data": {
                    "view": "pub_theme::components.blocks.hero.internal",
                    "title": "Chi Siamo",
                    "subtitle": "...",
                    "breadcrumb_label": "Chi Siamo"
                }
            }
        ]
    }
}
```

## The Data Flow

```
URL: /it/pages/about
    ↓
Folio Route: pages/pages/[slug].blade.php  ($slug = "about")
    ↓
<x-page side="content" slug="about" />
    ↓
Page.php constructor: PageModel::firstWhere('slug', 'about')
    ↓
Loads: config/local/techplanner/database/content/pages/about.json
    ↓
Extracts blocks for current locale ('it')
    ↓
BlockData::collect($blocks)
    ↓
page-content.blade.php: @include($block->view, $block->data)
    ↓
Renders: hero.internal, services.cards, etc.
```

## Adding a New Page

To add a new page (e.g. `/it/pages/privacy`):

1. **Create JSON file ONLY**: `config/local/techplanner/database/content/pages/privacy.json`
2. **Reuse existing block views**: `hero.internal`, `blog.grid`, `newsletter.simple`, etc.
3. **NO new blade file needed** - `[slug].blade.php` handles it automatically

## Available Block Views

### Hero variants
- `pub_theme::components.blocks.hero.simple` - Simple hero
- `pub_theme::components.blocks.hero.internal` - Blue hero with breadcrumb (for internal pages)
- `pub_theme::components.blocks.hero.enhanced` - Glassmorphism hero (for landing pages)
- `pub_theme::components.blocks.hero.blog` - Blog hero with search + categories
- `pub_theme::components.blocks.hero.faq` - FAQ hero
- `pub_theme::components.blocks.hero.about` - About hero

### Content blocks
- `pub_theme::components.blocks.blog.grid` - Article cards grid
- `pub_theme::components.blocks.blog.search-bar` - Search input
- `pub_theme::components.blocks.blog.category-filter` - Category pills
- `pub_theme::components.blocks.blog.featured-grid` - Featured articles
- `pub_theme::components.blocks.blog.articles-grid` - All articles grid
- `pub_theme::components.blocks.blog.tags` - Tag cloud
- `pub_theme::components.blocks.newsletter.simple` - Simple newsletter
- `pub_theme::components.blocks.newsletter.enhanced` - Enhanced newsletter with social proof
- `pub_theme::components.blocks.cta.banner` - CTA banner
- `pub_theme::components.blocks.cta.consultation` - CTA consultation
- `pub_theme::components.blocks.services.cards-image` - Service cards with images
- `pub_theme::components.blocks.sectors.split` - Sectors split layout

### Section components (via `<x-section>`)
- `header` → `header/v1.blade.php` (navigation)
- `footer` → `footer/v1.blade.php` (footer)

## Exceptions: When Individual Blade Files ARE Needed

Some pages need custom logic beyond CMS blocks:
- **Auth pages** (`pages/auth/login.blade.php`) - Custom auth forms
- **Dashboard** (`pages/dashboard/index.blade.php`) - Authenticated user area
- **Profile** (`pages/profile/edit.blade.php`) - User profile management
- **Services detail** (`pages/pages/services/[slug].blade.php`) - Dynamic service detail pages

## Key Rules for AI Agents

1. **NEVER create `pages/pages/<name>/index.blade.php`** for content pages
2. **ALWAYS create a JSON file** at `config/local/techplanner/database/content/pages/<slug>.json`
3. **REUSE existing block views** - check what's available before creating new ones
4. **Multilingual**: Every JSON must have `it` and `en` blocks
5. **View names**: Always prefix with `pub_theme::components.blocks.`
6. **`@include` spreads data**: `$block->data` keys become individual variables in the view
