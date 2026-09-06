# Cms Module: Content Management Philosophy

> **Block-Based Page Builder** — Custom Filament Builder integration. Zero external deps (Xot, UI, Tenant only).

---

## Zen

**"Content is blocks. Blocks are registered by UI. Cms orchestrates them."**

---

## Architecture

### Core Pattern

```
UI Module (defines block types)
  ↓
UI::GetAllBlocksAction (discover ComponentFileData blocks)
  ↓
Cms::PageContentBuilder (wraps Filament\Forms\Components\Builder)
  ↓
PageContent model (stores block[] data JSON)
  ↓
PageContentResource (edit UI)
```

### Models (12)

- **PageContent** — Page with blocks array (JSON)
- **Block** — Metadata only (blocks live in blocks array)
- **Attachment** — Polymorphic media

### Actions (8)

- `BuildPageSchemaAction` — Merge block schemas
- `ResolveBlockQueryAction` — Execute block queries (e.g., latest posts)
- `GetViewThemeByViewAction` — Resolve template path

---

## Competitors & Analysis

### 1. Filament Fabricator

| Aspect | Fabricator | Our Cms |
|--------|-----------|---------|
| Block Registration | Visual UI (paid addon) | Programmatic (free, code-first) |
| Extensibility | Addon ecosystem | UI module integration |
| Cost | €199+ license | Free |
| Control | Medium (opinionated) | High (full control) |
| Multi-tenancy | Basic | Native (Tenant module) |

**Verdict**: Fabricator = WYSIWYG for non-devs. Our Cms = developer-driven, open-source.

**When to choose Fabricator**: Client wants visual drag-drop, budget available.
**When to choose Cms**: Need open-source, custom blocks, developer control.

---

### 2. Statamic CMS

| Aspect | Statamic | Our Cms |
|--------|----------|---------|
| Scope | Full CMS (auth, media, users) | Content blocks only |
| Content Model | Collections + entries | Pages + blocks |
| Dependencies | Large ecosystem | Minimal (Xot, UI, Tenant) |

**Inspiration from Statamic**:
- ✓ Block-based content model (their fieldsets → our blocks)
- ✓ Modular field system (validates block types)
- ✓ Translatable content (use LangBaseResource like Statamic)

---

### 3. Craft CMS

**Premium headless CMS**. Language: Yii (not Laravel). Verdict: Powerful but not Laravel-native. Our Cms stays in Laravel ecosystem.

---

## Recommended Packages to Install

### Priority 1: Laravel Head ⭐ INSTALL THIS

**Package**: `unvoid/laravel-head`

**What it does**: Manage `<head>` tags (meta, OpenGraph, structured data) programmatically.

**Why for Cms**:
- Each block registers its own meta tags
- SEO fields per block type
- Open Graph generation (social previews)
- Structured data (JSON-LD for rich snippets)

**Example**:
```php
// In a block:
Head::title('Block Title');
Head::meta('og:image', block.imageUrl);
Head::structuredData('Article', [...]);
```

**Installation**:
```bash
composer require unvoid/laravel-head
```

**Rationale**: Cms without meta management is incomplete for SEO projects. Essential for modern web.

---

### Priority 2: Spatie Media Library

**Package**: `spatie/laravel-medialibrary`

**What it does**: Media management with conversions, optimizations.

**Why for Cms**:
- Block image optimization (thumbnails, WebP)
- Lazy loading support
- CDN integration
- Collections (organize media by block type)

**Installation**:
```bash
composer require spatie/laravel-medialibrary
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
```

**Note**: Cms module already integrates Media module. Media Library adds conversion pipelines.

---

### Priority 3: Staudenmeir Eloquent Extensions

**Already included** (in Xot): `staudenmeir/eloquent-has-many-deep`

**Why important for Cms**:
- Query blocks transitively (find pages by nested block content)
- Performance: eager load blocks + nested relations
- Example: "Show all pages containing product block with SKU X"

---

### Priority 4: Fractal (Design System Documentation)

**Package**: `frctl/fractal` (npm)

**What it does**: Living design system documentation.

**Why for Cms**:
- Document each block type (schema, examples, usage)
- Visual block catalog for admins
- Version block schemas

**Installation**:
```bash
npm install @frctl/fractal
```

**Use case**: Generate /blocks-catalog for admins (block library explorer).

---

## Inspiration Sources

### Codebases to Study

| Source | What to Learn | Link |
|--------|---------------|------|
| Statamic | Block-based content model, fieldsets | github.com/statamic/cms |
| Craft CMS | Entry/section system, permissions | github.com/craftcms/cms |
| Peak CMS | Laravel headless CMS | github.com/peakphp/cms |
| Meerkat CMS | Headless, Laravel stack | github.com/MeerkatLabs/meerkat-cms |

### Patterns to Steal

1. **Statamic's fieldsets** → Our block schemas (validation, UI generation)
2. **Craft's element system** → Our pages + blocks (publish, draft states)
3. **Peak's API focus** → Extend PageContent API (headless queries)
4. **Meerkat's query builder** → Improve block query performance

---

## Best/Bad Practices

✓ **Block registration via UI** (dynamic, no hardcoding)
✓ **Schema-driven per block** (validation, reusability)
✓ **PageContent is dumb** (stores blocks[], doesn't interpret)
✓ **Install Laravel Head** (meta tag management)

❌ **Hardcoding block types** (violates extensibility)
❌ **Block logic in PageContent** (violates separation)
❌ **External page builder** (Fabricator dependency not needed)
❌ **No meta tag management** (use Laravel Head)

---

## Roadmap

**Phase 1 (NOW)**: Block model + UI registration ✓
**Phase 2 (SOON)**: Install Laravel Head (meta/OG/SEO) ⭐
**Phase 3 (NEXT)**: Block versioning (migrate old schemas)
**Phase 4**: Block templates (layouts/variants)
**Phase 5**: A/B testing block variants
**Phase 6**: Block-level revisions (history)
**Phase 7**: Fractal design system integration

---

## Summary

```
┌──────────────────────────────────────┐
│ Cms (Block Page Builder)             │
├──────────────────────────────────────┤
│ Pattern: Custom Builder + UI registry│
│ Models: 12                           │
│ Actions: 8                           │
│ External deps: 0 (today)             │
│ Recommended: +1 (laravel/head)       │
│ Competitors: Fabricator, Statamic    │
│ Inspiration: Statamic, Craft, Peak   │
│ Status: Stable                       │
└──────────────────────────────────────┘
```

---

- **Generated**: 2026-09-06 (verified + competitors + packages)
- **Author**: Claude (market-aware research)

