# 🦸 Superpowers Integration Guide

**Date**: 2026-03-30  
**Status**: ✅ **Active**  
**Reference**: https://github.com/obra/superpowers

---

## 🎯 What are Superpowers?

Superpowers is a Laravel package that provides **dynamic rendering capabilities** for CMS-driven content. It enables:

- **Block-based rendering** from JSON configuration
- **Dynamic component resolution**
- **Content-driven layouts**
- **Zero-code page creation**

---

## 🔧 Architecture

### Flow

```
Request: /it/tests/homepage
    ↓
Folio Route: [slug].blade.php
    ↓
Volt Component: mount($slug)
    ↓
Load JSON: tests.homepage.json
    ↓
Superpowers: Render blocks
    ↓
<x-page side="content" :slug="$pageSlug" :data="$data" />
    ↓
Loop through content_blocks
    ↓
@include($block['view'], $block['data'])
    ↓
HTML Output
```

---

## 📁 File Structure

### 1. Route File

**Path**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`

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

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->pageSlug = 'tests.'.$slug;
        $this->data = ['slug' => $slug];
    }
};
?>

<x-layouts.app>
    @volt('tests.view')
    <div>
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
```

**Key Points**:
- ✅ Single dynamic route for ALL pages
- ✅ Loads JSON based on slug
- ✅ Uses `<x-page>` component
- ✅ No hardcoded content

---

### 2. JSON Content File

**Path**: `laravel/config/local/fixcity/database/content/pages/tests.homepage.json`

```json
{
  "id": "tests.homepage",
  "title": {
    "it": "Homepage - Il mio Comune",
    "en": "Homepage - My Municipality"
  },
  "slug": "tests.homepage",
  "content": null,
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "data": {
          "view": "pub_theme::components.blocks.hero.homepage",
          "title": "NOME DEL COMUNE"
        }
      },
      {
        "type": "featured_news",
        "data": {
          "view": "pub_theme::components.blocks.news.homepage",
          "title": "Contenuti in evidenza"
        }
      },
      {
        "type": "services",
        "data": {
          "view": "pub_theme::components.blocks.services.homepage",
          "items": [
            {
              "title": "Servizi Digitali",
              "description": "Accedi ai servizi online",
              "icon": "heroicon-o-computer-desktop"
            }
          ]
        }
      }
    ]
  },
  "sidebar_blocks": {
    "it": []
  },
  "footer_blocks": {
    "it": ""
  }
}
```

---

### 3. Block Views

**Path**: `laravel/Themes/Sixteen/resources/views/components/blocks/`

```
blocks/
├── hero/
│   └── homepage.blade.php
├── news/
│   └── homepage.blade.php
├── services/
│   └── homepage.blade.php
├── topics/
│   └── homepage.blade.php
└── ...
```

**Example** (`hero/homepage.blade.php`):
```blade
@props(['title' => ''])

<section class="hero-section py-12">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="title-xxxlarge mb-4">{{ $title }}</h1>
            </div>
        </div>
    </div>
</section>
```

---

## 🤖 Superpowers Features

### 1. Dynamic Block Resolution

```php
// Inside <x-page> component
@foreach($content_blocks as $block)
    @include($block['data']['view'], $block['data'])
@endforeach
```

**Benefits**:
- ✅ Add/remove blocks via JSON
- ✅ No blade file changes needed
- ✅ Reusable blocks across pages

### 2. Data Injection

```php
// JSON
{
  "type": "services",
  "data": {
    "view": "pub_theme::components.blocks.services.homepage",
    "items": [...]
  }
}

// Blade
@foreach($items as $item)
    <div>{{ $item['title'] }}</div>
@endforeach
```

### 3. Multi-language Support

```json
{
  "content_blocks": {
    "it": [...],
    "en": [...]
  }
}
```

---

## 📊 Complete Example: Homepage

### JSON Configuration

**File**: `tests.homepage.json`

```json
{
  "id": "tests.homepage",
  "title": {
    "it": "Homepage - Il mio Comune"
  },
  "slug": "tests.homepage",
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "data": {
          "view": "pub_theme::components.blocks.hero.homepage",
          "title": "NOME DEL COMUNE"
        }
      },
      {
        "type": "featured_news",
        "data": {
          "view": "pub_theme::components.blocks.news.homepage",
          "article": {
            "date": "18 mag 2026",
            "title": "PARTE L'ESTATE CON OLTRE 300 EVENTI",
            "excerpt": "Inaugurazione lunedì 2 luglio..."
          }
        }
      },
      {
        "type": "services",
        "data": {
          "view": "pub_theme::components.blocks.services.homepage",
          "title": "Servizi in evidenza",
          "items": [
            {
              "title": "Servizi Digitali",
              "description": "Accedi ai servizi online",
              "icon": "heroicon-o-computer-desktop",
              "url": "#"
            },
            {
              "title": "Amministrazione",
              "description": "Giunta, consiglio e organi",
              "icon": "heroicon-o-building-office",
              "url": "/it/tests/amministrazione"
            }
          ]
        }
      },
      {
        "type": "topics",
        "data": {
          "view": "pub_theme::components.blocks.topics.homepage",
          "title": "Esplora per argomento",
          "items": [
            {
              "title": "Iscrizioni",
              "description": "Servizi per iscrizioni",
              "icon": "heroicon-o-pencil-square"
            }
          ]
        }
      },
      {
        "type": "news",
        "data": {
          "view": "pub_theme::components.blocks.news.latest",
          "title": "Ultime notizie",
          "items": [
            {
              "title": "Parte l'estate con 300 eventi",
              "excerpt": "Inaugurazione lunedì 2 luglio",
              "date": "18 Mag 2026"
            }
          ]
        }
      },
      {
        "type": "events",
        "data": {
          "view": "pub_theme::components.blocks.events.latest",
          "title": "Eventi",
          "items": [
            {
              "title": "Concerto gratuito",
              "description": "Piazza XX Settembre",
              "date": "20 Giu 2026"
            }
          ]
        }
      }
    ]
  }
}
```

### Block View Example

**File**: `blocks/services/homepage.blade.php`

```blade
@props(['title' => '', 'items' => []])

<section class="featured-services py-8">
    <div class="container">
        <h2 class="title-xxlarge mb-6">{{ $title }}</h2>
        <div class="row g-4">
            @foreach($items as $item)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card-wrapper card-space">
                    <div class="card card-bg">
                        <div class="card-body">
                            <h3 class="card-title h5">
                                <x-filament::icon 
                                    :icon="$item['icon']" 
                                    class="icon-primary me-2" 
                                    style="width: 24px; height: 24px;" 
                                />
                                {{ $item['title'] }}
                            </h3>
                            <p class="card-text mt-2">{{ $item['description'] }}</p>
                            <a href="{{ $item['url'] }}" class="read-more">
                                <span>Leggi di più</span>
                                <x-filament::icon icon="heroicon-o-arrow-right" class="icon-sm ms-2" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
```

---

## ✅ Benefits of This Approach

### DRY (Don't Repeat Yourself)
- ❌ **Before**: 38 blade files
- ✅ **After**: 1 blade file ([slug].blade.php)
- **Reduction**: 97%

### Maintainability
- ❌ **Before**: Change 38 files
- ✅ **After**: Change JSON only
- **Time saved**: 38x

### Flexibility
- ❌ **Before**: Create blade file for new page
- ✅ **After**: Just create JSON
- **Easier**: Yes!

### Content Management
- ❌ **Before**: Content in blade files
- ✅ **After**: Content in JSON (CMS-driven)
- **CMS-ready**: Yes!

---

## 🤖 OpenViking Context

```bash
openviking add-memory "Superpowers integration: [slug].blade.php → JSON → <x-page> → blocks. No hardcoded blade files. All content from JSON in config/local/fixcity/database/content/pages/"
```

---

## 📚 Related Documentation

- [Dynamic Route Correction](./DYNAMIC_ROUTE_CORRECTION.md)
- [SVG Icon Convention](./SVG_ICON_CONVENTION.md)
- [Theme Architecture](./THEME_ARCHITECTURE_OUTFIT.md)
- [Test Pages Status](./TEST_PAGES_IMPLEMENTATION_STATUS.md)

---

**Last Updated**: 2026-03-30  
**Pattern**: [slug].blade.php + JSON + Superpowers  
**Status**: ✅ **ACTIVE**  
**Owner**: Multi-Agent Team
