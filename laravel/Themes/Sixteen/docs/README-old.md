# Sixteen Theme Documentation Index

**Theme:** Sixteen  
**Version:** 1.0  
**Last Updated:** 2026-04-01  
**Status:** ✅ Active  
**Total Files:** 325+

---

## 🎯 Quick Navigation

| Category | Document | Description |
|----------|----------|-------------|
| 🏗️ **Architecture** | [Layout Architecture](layout-architecture.md) | Layout hierarchy e componenti |
| 🧭 **Page Tree** | [page-directory-structure.md](page-directory-structure.md) | Regola canonica per `resources/views/pages` |
| 🗺️ **Navigation Map** | [LAYOUT_ARCHITECTURE_MAP.md](LAYOUT_ARCHITECTURE_MAP.md) | Bidirectional links |
| 🔧 **Recent Fixes** | [LAYOUT_FIX_COMPLETE_BMAD.md](LAYOUT_FIX_COMPLETE_BMAD.md) | Layout fix summary |
| ⚡ **Vite Fix** | [VITE_MANIFEST_FIX_COMPLETE.md](VITE_MANIFEST_FIX_COMPLETE.md) | Build manifest fix |
| 🇮🇹 **Design Comuni** | [design-comuni/HTML_PARITY_VERIFICATION_REPORT.md](design-comuni/HTML_PARITY_VERIFICATION_REPORT.md) | HTML parity verification |
| 📚 **Master Index** | [../../../docs/MODULE_DOCS_INDEX.md](../../../docs/MODULE_DOCS_INDEX.md) | Central navigation |

---

## 🏗️ Architecture Documents

### Layout Architecture

**Main Document:** [`layout-architecture.md`](layout-architecture.md)

**Hierarchy:**
```
x-layouts.main (Base)
    ├── x-layouts.app (Public Frontend)
    ├── x-layouts.guest (Authentication)
    ├── x-layouts.auth (Dashboard)
    └── x-layouts.admin (Filament Admin)
```

**Files:**
| File | Component | Extends | Description |
|------|-----------|---------|-------------|
| [`main.blade.php`](../resources/views/components/layouts/main.blade.php) | `x-layouts.main` | None | Base HTML structure |
| [`app.blade.php`](../resources/views/components/layouts/app.blade.php) | `x-layouts.app` | `main` | Public frontend wrapper |
| [`guest.blade.php`](../resources/views/components/layouts/guest.blade.php) | `x-layouts.guest` | `main` | Authentication pages |
| [`auth.blade.php`](../resources/views/components/layouts/auth.blade.php) | `x-layouts.auth` | `main` | Protected dashboard |
| [`admin.blade.php`](../resources/views/components/layouts/admin.blade.php) | `x-layouts.admin` | `main` | Filament admin panel |

**Cross-References:**
- → [UI Spec](_bmad-output/ui-spec.md) - Component library
- → [Architecture](_bmad-output/architecture.md) - System architecture
- → [Cms Module](../../../Modules/Cms/docs/) - CMS sections integration
- → [Filament](../../../Modules/Filament/docs/) - Admin panel

---

## 📦 Component Registry

### Layout Components

| Component | File | Type | Description |
|-----------|------|------|-------------|
| `x-layouts.main` | `resources/views/components/layouts/main.blade.php` | Base | HTML structure |
| `x-layouts.app` | `resources/views/components/layouts/app.blade.php` | Wrapper | Public frontend |
| `x-layouts.guest` | `resources/views/components/layouts/guest.blade.php` | Wrapper | Auth pages |
| `x-layouts.auth` | `resources/views/components/layouts/auth.blade.php` | Wrapper | Dashboard |
| `x-layouts.admin` | `resources/views/components/layouts/admin.blade.php` | Wrapper | Admin panel |

### UI Components

| Component | File | Used In | Description |
|-----------|------|---------|-------------|
| `x-section` | `resources/views/components/section.blade.php` | app layout | CMS section wrapper |
| `x-header` | `resources/views/components/header.blade.php` | app layout | Public header |
| `x-footer` | `resources/views/components/footer.blade.php` | app layout | Public footer |
| `x-nav` | `resources/views/components/nav.blade.php` | app, auth | Navigation bar |
| `x-sidebar` | `resources/views/components/sidebar.blade.php` | auth layout | Sidebar navigation |

**Component Documentation:** [`components/README.md`](components/README.md)

---

## 🎨 Design System

### Colors

```css
--color-primary: #3b82f6;
--color-secondary: #64748b;
--color-success: #10b981;
--color-warning: #f59e0b;
--color-danger: #ef4444;
```

### Typography

```css
--font-sans: 'Inter', sans-serif;
--font-mono: 'Fira Code', monospace;
```

**Design System Docs:** [`design-system.md`](design-system.md)

---

## 🚀 Getting Started

### Installation

```bash
# Install theme dependencies
composer require laravel/themes

# Activate theme
php artisan theme:use sixteen

# Build assets
npm run build
```

**Installation Guide:** [`installation.md`](installation.md)

---

## 📖 Guides Index

### For Developers

1. **[Vite Configuration Guide](./vite-configuration-guide.md)** ✅ - Build configuration
   - `outDir: './public'` explained
   - Manifest generation and Laravel integration
2. **[Build Commands Guide](./build-commands-guide.md)** ✅ - Complete build process
   - `composer update -W` → `npm install` → `npm run build` → `npm run copy`
3. [Creating New Layouts](guides/creating-layouts.md)
4. [Adding Components](guides/adding-components.md)
5. [Customizing Styles](guides/customizing-styles.md)
6. [Dark Mode Implementation](guides/dark-mode.md)

### For Designers

1. [Design Tokens](guides/design-tokens.md)
2. [Color Palette](guides/color-palette.md)
3. [Typography Scale](guides/typography.md)
4. [Component Patterns](guides/component-patterns.md)

### For Content Editors

1. [CMS Sections](guides/cms-sections.md)
2. [Managing Blocks](guides/managing-blocks.md)
3. [Menu Configuration](guides/menu-config.md)

**All Guides:** [`guides/README.md`](guides/README.md)

---

## 📋 Module Integration

### Connected Modules

| Module | Integration Point | Documentation |
|--------|------------------|---------------|
| **Cms** | Sections, Blocks | [`Modules/Cms/docs/`](../../../Modules/Cms/docs/) |
| **Blog** | Public pages | [`Modules/Blog/docs/`](../../../Modules/Blog/docs/) |
| **User** | Auth pages | [`Modules/User/docs/`](../../../Modules/User/docs/) |
| **Filament** | Admin panel | [`Modules/Filament/docs/`](../../../Modules/Filament/docs/) |
| **Geo** | Maps integration | [`Modules/Geo/docs/`](../../../Modules/Geo/docs/) |
| **Media** | Image handling | [`Modules/Media/docs/`](../../../Modules/Media/docs/) |

### Integration Patterns

```blade
{{-- CMS Section in app layout --}}
<x-layouts.app>
    <x-section slug="header" />
    <main>{{ $slot }}</main>
    <x-section slug="footer" />
</x-layouts.app>

{{-- Filament Admin in admin layout --}}
<x-layouts.admin>
    <livewire:filament.resources />
</x-layouts.admin>
```

---

## 🔗 External References

### BMad Documentation

| Document | Link | Relevance |
|----------|------|-----------|
| **PRD** | `_bmad-output/prd.md` | Product requirements |
| **Architecture** | `_bmad-output/architecture.md` | System architecture |
| **UI Spec** | `_bmad-output/ui-spec.md` | Component library |
| **Epics** | `_bmad-output/epics-and-stories.md` | Feature backlog |

### Project Documentation

| Document | Link | Relevance |
|----------|------|-----------|
| **Project Docs** | `docs/` | Project-wide documentation |
| **Module Docs** | `Modules/*/docs/` | Module-specific guides |
| **Quality** | `docs/quality/` | Quality standards |

---

## 📊 Theme Statistics

| Metric | Value |
|--------|-------|
| **Layout Files** | 5 |
| **Components** | 15+ |
| **Views** | 50+ |
| **Lines of Code** | 2,500+ |
| **Test Coverage** | 75% |
| **PHPStan Level** | 10 |

---

## 🎯 Quality Standards

### Code Quality

- ✅ PHPStan Level 10
- ✅ Laravel Pint (PSR-12)
- ✅ No N+1 queries
- ✅ Proper typing
- ✅ DRY + KISS principles

### Documentation Quality

- ✅ All components documented
- ✅ Usage examples provided
- ✅ Cross-references complete
- ✅ Bidirectional links

### Performance

- ✅ Vite bundling
- ✅ Lazy loading
- ✅ Image optimization
- ✅ Caching enabled

---

## 📝 Document Maintenance

### Update Frequency

- **Architecture:** After major changes
- **Components:** When adding/modifying
- **Guides:** As needed
- **Index:** Weekly review

### Review Process

1. Developer makes changes
2. Updates relevant docs
3. Updates index if needed
4. Code review includes doc review

### Version Control

- Docs versioned with code
- Changelog in each doc
- Deprecation notices added

---

## 🆘 Support

### Getting Help

1. Check guides first
2. Review component docs
3. Search architecture docs
4. Ask in team chat

### Reporting Issues

- **Layout bugs:** Create GitHub issue
- **Doc errors:** PR with fix
- **Feature requests:** Discuss in planning

---

**Last Index Update:** 2026-04-01  
**Next Review:** 2026-04-08  
**Owner:** Frontend Team  
**Status:** ✅ Complete
