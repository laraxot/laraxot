# Design Comuni Replication - Team Guide

## Quick Start

### 1. Understand the Goal
Replicate **38 Design Comuni static pages** using **Tailwind CSS + Alpine.js** with **JSON-driven content blocks**.

**Source**: https://italia.github.io/design-comuni-pagine-statiche/  
**Target**: http://fixcity.local/it/tests/[page-slug]

### 2. Core Principles

#### DRY (Don't Repeat Yourself)
- ✅ **Universal components**: 47 components for 38 pages
- ❌ **Page-specific components**: NEVER create `cmp-homepage-hero`

#### KISS (Keep It Simple, Stupid)
- ✅ **Simple JSON**: Content separated from presentation
- ❌ **Hardcoded HTML**: NEVER hardcode content in Blade

#### One Dynamic Route
- ✅ **Single `[slug].blade.php`**: All pages use same file
- ❌ **Multiple files**: NEVER create `homepage.blade.php`, `amministrazione.blade.php`

### 3. Architecture Overview

```
User Request → Laravel Folio → [slug].blade.php → Volt Component
                                           ↓
                                    PageSlugMiddleware
                                           ↓
                                    ResolvePageAction
                                           ↓
                                    Read JSON File
                                           ↓
                                    Load Blocks
                                           ↓
                                    Render Components
                                           ↓
                                    HTML Response
```

### 4. File Locations

#### Pages
```
laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php
```
**DO NOT CREATE**: `homepage.blade.php`, `amministrazione.blade.php`, etc.

#### Components
```
laravel/Themes/Sixteen/resources/views/components/blocks/<type>/<component>.blade.php
```
**Examples**:
- `pub_theme::components.blocks.header.main`
- `pub_theme::components.blocks.hero.default`
- `pub_theme::components.blocks.footer.full`

#### JSON Content
```
laravel/config/local/fixcity/database/content/pages/tests.[slug].json
```
**Example**: `tests.homepage.json`, `tests.amministrazione.json`

#### CSS Styles
```
laravel/Themes/Sixteen/resources/css/style-apply.css
```
**Contains**: Tailwind @apply for all Bootstrap Italia classes

---

## Development Workflow

### 1. Start Working on a Page

**Step 1**: Check component inventory
```bash
# Check if components exist
ls -la laravel/Themes/Sixteen/resources/views/components/blocks/
```

**Step 2**: Create missing components
- Follow component template
- Use Tailwind @apply
- Ensure WCAG 2.1 AA compliance
- Write Pest tests

**Step 3**: Create JSON content file
```json
{
  "slug": "tests.homepage",
  "title": "Homepage - Comune di FixCity",
  "blocks": [
    {
      "type": "header",
      "view": "pub_theme::components.blocks.header.main",
      "data": { ... }
    }
  ]
}
```

**Step 4**: Test page
```bash
# Visit page in browser
http://fixcity.local/it/tests/homepage
```

### 2. Verify HTML Parity

**Step 1**: Open source page
```
https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
```

**Step 2**: View source
```
Right-click → View Page Source
```

**Step 3**: Compare with target
```
http://fixcity.local/it/tests/homepage
```

**Step 4**: Check inside `<body>` tag (excluding scripts)
- ✅ Header structure matches
- ✅ Navigation structure matches
- ✅ Main content structure matches
- ✅ Footer structure matches

### 3. Verify Visual Parity

**Step 1**: Take screenshots
- Use browser DevTools (F12)
- Screenshot desktop (1920x1080)
- Screenshot mobile (375x812)

**Step 2**: Compare side-by-side
- Colors (use color picker)
- Typography (font, size, weight)
- Spacing (margins, paddings)
- Layout (grid, flexbox)

**Step 3**: Document differences
```markdown
# Screenshot Analysis: Homepage

## Differences Found
1. Header background color: #007a52 (source) vs #00614a (target)
2. Logo size: 82x82px (source) vs 64x64px (target)

## Fixes Applied
1. Updated --bs-primary in style-apply.css
2. Adjusted logo width in header component
```

### 4. Build and Deploy

**Step 1**: Build theme
```bash
cd laravel/Themes/Sixteen
npm install
npm run build
```

**Step 2**: Copy to public
```bash
npm run copy
```

**Step 3**: Clear cache
```bash
cd /var/www/_bases/base_fixcity_fila5
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

**Step 4**: Verify
```bash
# Check manifest exists
ls -la public_html/themes/Sixteen/manifest.json
```

---

## Common Tasks

### Task 1: Create New Component

**Template**: `.github/ISSUE_TEMPLATE/component-implementation.md`

**Steps**:
1. Create Blade component
2. Add Tailwind @apply styles
3. Add Alpine.js interactions (if needed)
4. Write Pest tests
5. Document in `COMPONENT_CATALOG.md`

**Example**:
```blade
{{-- laravel/Themes/Sixteen/resources/views/components/blocks/hero/default.blade.php --}}
@props(['data' => []])

@php
    $title = $data['title'] ?? 'Default Title';
    $subtitle = $data['subtitle'] ?? '';
    $bgImage = $data['background_image'] ?? '';
@endphp

<section {{ $attributes->merge(['class' => 'cmp-hero']) }}
         @if($bgImage) style="background-image: url('{{ $bgImage }}')" @endif>
    <div class="container">
        <h1 class="title-xxxlarge">{{ $title }}</h1>
        @if($subtitle)
            <p class="subtitle">{{ $subtitle }}</p>
        @endif
    </div>
</section>
```

```css
/* laravel/Themes/Sixteen/resources/css/style-apply.css */
.cmp-hero {
  @apply relative py-20 bg-cover bg-center;
  min-height: 400px;
}

.cmp-hero .title-xxxlarge {
  @apply text-5xl font-bold text-white mb-4;
}

.cmp-hero .subtitle {
  @apply text-xl text-white/90;
}
```

### Task 2: Create JSON Content File

**Template**: See examples in `laravel/config/local/fixcity/database/content/pages/`

**Steps**:
1. Copy existing JSON file
2. Update `slug` field
3. Define blocks structure
4. Add data for each block
5. Validate JSON syntax

**Example**:
```json
{
  "slug": "tests.amministrazione",
  "title": "Amministrazione - Comune di FixCity",
  "meta_description": "Organi di governo e aree amministrative del Comune",
  "blocks": [
    {
      "type": "header",
      "view": "pub_theme::components.blocks.header.main",
      "data": {
        "institution_name": "Comune di FixCity",
        "tagline": "Un comune da vivere"
      }
    },
    {
      "type": "hero",
      "view": "pub_theme::components.blocks.hero.default",
      "data": {
        "title": "Amministrazione",
        "subtitle": "Organi di governo e aree amministrative"
      }
    },
    {
      "type": "admin-list",
      "view": "pub_theme::components.blocks.admin-list.default",
      "data": {
        "items": [
          {
            "name": "Sindaco",
            "fullName": "Mario Rossi",
            "email": "sindaco@comune.fixcity.it"
          }
        ]
      }
    },
    {
      "type": "footer",
      "view": "pub_theme::components.blocks.footer.full",
      "data": {
        "address": "Via Roma 1, FixCity",
        "phone": "+39 0123 456789"
      }
    }
  ]
}
```

### Task 3: Debug Component Issues

**Issue**: Component not rendering

**Debug Steps**:
1. Check component exists
```bash
ls -la laravel/Themes/Sixteen/resources/views/components/blocks/<type>/<component>.blade.php
```

2. Check namespace registered
```bash
grep -r "pub_theme" laravel/config/view.php
```

3. Clear view cache
```bash
php artisan view:clear
```

4. Check JSON structure
```bash
cat laravel/config/local/fixcity/database/content/pages/tests.homepage.json | jq
```

5. Check error logs
```bash
tail -f storage/logs/laravel.log
```

**Issue**: Styles not applying

**Debug Steps**:
1. Check style-apply.css exists
```bash
ls -la laravel/Themes/Sixteen/resources/css/style-apply.css
```

2. Check Vite config
```bash
cat laravel/Themes/Sixteen/vite.config.js
```

3. Rebuild theme
```bash
cd laravel/Themes/Sixteen
npm run build
npm run copy
```

4. Check manifest
```bash
cat public_html/themes/Sixteen/manifest.json
```

5. Hard refresh browser
```
Ctrl+Shift+R (Windows/Linux)
Cmd+Shift+R (Mac)
```

---

## Testing

### Run Tests

**Unit Tests**:
```bash
cd /var/www/_bases/base_fixcity_fila5
./vendor/bin/pest tests/Unit
```

**Browser Tests**:
```bash
./vendor/bin/pest tests/Browser
```

**Accessibility Tests**:
```bash
./vendor/bin/pest tests/Browser/Accessibility
```

**Performance Tests**:
```bash
./vendor/bin/pest tests/Browser/Performance
```

### Write New Tests

**Component Test**:
```php
// tests/Unit/Components/Blocks/HeaderTest.php
it('renders header correctly', function () {
    $data = [
        'institution_name' => 'Comune di FixCity',
        'tagline' => 'Un comune da vivere',
    ];

    $html = view('pub_theme::components.blocks.header.main', ['data' => $data])
        ->render();

    expect($html)->toContain('Comune di FixCity')
        ->toContain('Un comune da vivere');
});
```

**Browser Test**:
```php
// tests/Browser/Pages/HomepageTest.php
it('matches Design Comuni homepage', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/it/tests/homepage')
                ->waitFor('@header')
                ->assertSee('Comune di FixCity')
                ->assertScreenshot('homepage-desktop');
    });
});
```

---

## Documentation

### Update Component Catalog

**File**: `Themes/Sixteen/docs/COMPONENT_CATALOG.md`

**Template**:
```markdown
## Header Main

**Type**: Layout  
**View**: `pub_theme::components.blocks.header.main`  
**Used by**: All pages

### Usage
```blade
<x-pub_theme::blocks.header.main
  :data="$data"
/>
```

### Data Structure
```json
{
  "institution_name": "Comune di FixCity",
  "tagline": "Un comune da vivere",
  "logo_url": "/themes/sixteen/images/logo.svg"
}
```

### Example
![Header example](screenshots/header-example.png)

### Accessibility Notes
- Uses semantic `<header>` tag
- ARIA labels for navigation
- Keyboard accessible

### Browser Support
- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support
- Mobile: ✅ Responsive
```

### Update Master Index

**File**: `docs/MODULE_DOCS_INDEX.md`

**Add bidirectional links**:
```markdown
## Theme Documentation

### Design Comuni Replication
- [Team Guide](../Themes/Sixteen/docs/DESIGN_COMUNI_TEAM_GUIDE.md)
- [Component Catalog](../Themes/Sixteen/docs/COMPONENT_CATALOG.md)
- [Block Types](../Themes/Sixteen/docs/BLOCK_TYPES.md)
- [JSON Structure](../Themes/Sixteen/docs/JSON_STRUCTURE.md)
- [Screenshot Analysis](../Themes/Sixteen/docs/screenshots/)

← Back to Master Index
```

---

## Troubleshooting

### Problem: Vite Manifest Not Found

**Error**: `Vite manifest not found at: /var/www/_bases/base_fixcity_fila5/public_html/themes/Sixteen/manifest.json`

**Solution**:
```bash
cd laravel/Themes/Sixteen
composer update -W
npm install
npm run build
npm run copy
```

### Problem: Multiple Root Elements

**Error**: `Livewire only supports one HTML element per component. Multiple root elements detected`

**Solution**:
Wrap everything in single `<div>`:
```blade
<div>
    <x-layouts.app>
        @volt('tests.view')
            <x-page side="content" :slug="$pageSlug" :data="$data" />
        @endvolt
    </x-layouts.app>
</div>
```

### Problem: Component Not Found

**Error**: `Unable to locate a class or view for component [pub_theme::components.blocks.header.main]`

**Solution**:
1. Check file exists: `ls -la laravel/Themes/Sixteen/resources/views/components/blocks/header/main.blade.php`
2. Check namespace registered: `grep -r "pub_theme" laravel/config/view.php`
3. Clear cache: `php artisan view:clear`

### Problem: Styles Not Applying

**Issue**: Bootstrap Italia classes in HTML but no styles

**Solution**:
1. Add Tailwind @apply to `style-apply.css`
2. Rebuild theme: `npm run build`
3. Copy to public: `npm run copy`
4. Hard refresh browser

---

## Resources

### Documentation
- **Roadmap**: `.planning/ROADMAP.md`
- **Research**: `.planning/research/design-comuni-pages.md`
- **Block Analysis**: `_bmad-output/design-comuni-block-analysis.md`
- **Architecture**: `_bmad-output/design-comuni-architecture.md`
- **Component Catalog**: `Themes/Sixteen/docs/COMPONENT_CATALOG.md`
- **Block Types**: `Themes/Sixteen/docs/BLOCK_TYPES.md`
- **JSON Structure**: `Themes/Sixteen/docs/JSON_STRUCTURE.md`

### GitHub
- **Issues**: https://github.com/laraxot/base_fixcity_fila5/issues
- **Discussions**: https://github.com/laraxot/base_fixcity_fila5/discussions
- **Templates**: `.github/ISSUE_TEMPLATE/`

### Design References
- **Design Comuni**: https://italia.github.io/design-comuni-pagine-statiche/
- **Bootstrap Italia**: https://italia.github.io/bootstrap-italia/
- **Tailwind CSS**: https://tailwindcss.com/docs
- **DaisyUI**: https://daisyui.com/components/
- **Flowbite**: https://flowbite.com/blocks/
- **Alpine.js**: https://alpinejs.dev/

### Tools
- **GSD Workflow**: `.cursor/get-shit-done/`
- **BMad Method**: `_bmad-output/`
- **OpenViking**: Global installation
- **Ralph Loop**: Autonomous execution

---

## Questions?

### Quick Questions
- Check this guide first
- Search existing documentation
- Ask in GitHub Discussions

### Complex Questions
- Create GitHub Discussion
- Tag project maintainers
- Schedule team meeting

### Office Hours
- **When**: Every Tuesday 14:00-16:00 CET
- **Where**: Google Meet
- **Who**: All team members welcome

---

**Last Updated**: April 1, 2026  
**Version**: 1.0  
**Maintained By**: Project maintainers
