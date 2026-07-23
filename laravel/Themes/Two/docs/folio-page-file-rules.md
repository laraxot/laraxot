# Folio Page File Rules - For ALL AI Agents

> **Date**: February 2026
> **Author**: Claude Code
> **Status**: CRITICAL - Must be followed by all agents

## The Problem We Solved

Multiple AI agents created individual page blade files that caused:
1. **SerializableClosure TypeError** crashes on page load
2. **Route conflicts** between duplicate route names
3. **Rendering failures** from mixing `@extends` with Folio

## The Architecture

This project uses a **CMS dynamic page system** where:
- `pages/pages/[slug].blade.php` catches ALL content page slugs
- Page data lives in JSON at `config/local/techplanner/database/content/pages/<slug>.json`
- `<x-page side="content" :slug="$slug" />` loads JSON and renders blocks
- Each block has a `view` key pointing to `pub_theme::components.blocks.<type>.<variant>`

## File Organization

```
pages/                                    # Utility pages ONLY
├── index.blade.php                       # Homepage
├── show.blade.php                        # Generic show
├── show-home.blade.php                   # Home variant
├── coming-soon.blade.php                 # Coming soon
└── preview-content.blade.php             # Content preview

pages/pages/                              # ALL content pages
├── [slug].blade.php                      # CATCH-ALL for most pages
├── home.blade.php                        # Homepage
├── services/index.blade.php              # Services page
├── faq/index.blade.php                   # FAQ page
├── contacts/index.blade.php              # Contacts page
├── blog/index.blade.php                  # Blog listing
├── blog/[slug].blade.php                 # Blog post
└── auth/login.blade.php                  # Auth pages (vedi [auth-login-page](auth-login-page.md))
```

## NEVER Create These Files

```
# ALL OF THESE ARE WRONG - they conflict with the catch-all
pages/about.blade.php              ❌ Use [slug].blade.php with JSON
pages/chi-siamo.blade.php          ❌ Use [slug].blade.php with JSON
pages/services.blade.php           ❌ Use pages/pages/services/index.blade.php
pages/servizi.blade.php            ❌ Use pages/pages/services/index.blade.php
pages/faq.blade.php                ❌ Use pages/pages/faq/index.blade.php
pages/contacts.blade.php           ❌ Use pages/pages/contacts/index.blade.php
pages/contatti.blade.php           ❌ Use pages/pages/contacts/index.blade.php
```

## Correct Folio Page Template

```php
<?php
use function Laravel\Folio\name;
use Livewire\Volt\Component;

name('contacts.index');

new class extends Component {};
?>

<x-layouts.app>
    @volt('contacts.index')
    <div>
        <x-page side="content" slug="contacts" />
    </div>
    @endvolt
</x-layouts.app>
```

## Common Syntax Errors That Cause Crashes

### 1. Using `@php` for Folio imports (causes SerializableClosure error)
```php
// ❌ WRONG - causes TypeError: SerializableClosure
@php
use function Laravel\Folio\{name, middleware};
name('about');
@endphp

// ✅ CORRECT - use <?php ?> block
<?php
use function Laravel\Folio\name;
name('about');
?>
```

### 2. Mixing `@extends` with Folio (incompatible rendering)
```php
// ❌ WRONG - @extends is NOT compatible with Folio pages
<?php use function Laravel\Folio\name; name('about'); ?>
@extends('layouts.app')
@section('content')
...
@endsection

// ✅ CORRECT - use <x-layouts.app> component
<?php use function Laravel\Folio\name; name('about'); ?>
<x-layouts.app>
    <div>...</div>
</x-layouts.app>
```

### 3. Duplicate route names
```php
// ❌ WRONG - TWO files both defining name('faq')
// File 1: pages/faq.blade.php → name('faq')
// File 2: pages/pages/faq/index.blade.php → name('faq.index')
// This causes route resolution conflicts!

// ✅ CORRECT - only ONE file per route name
// pages/pages/faq/index.blade.php → name('faq.index')
```

### 4. Double Folio declarations
```php
// ❌ WRONG - declaring name() twice
@php
use function Laravel\Folio\{name, middleware};
name('faq');
?>
<?php
use function Laravel\Folio\name;
name('faq');
?>

// ✅ CORRECT - declare once
<?php
use function Laravel\Folio\name;
name('faq.index');
?>
```

## How to Add a New Page

1. Create JSON: `config/local/techplanner/database/content/pages/<slug>.json`
2. Add content blocks with proper `view` references
3. The `[slug].blade.php` catch-all will handle routing automatically
4. If you need a specific named route, create `pages/pages/<name>/index.blade.php`
5. Clear caches: `php artisan config:clear && cache:clear && route:clear && view:clear`

## Debugging Page Errors

1. Check `php artisan folio:list` for route conflicts
2. Look for duplicate files at `pages/` level
3. Clear ALL caches (config, cache, route, view)
4. Check JSON file exists and has valid structure
5. Verify block views exist at `components/blocks/<type>/<variant>.blade.php`
