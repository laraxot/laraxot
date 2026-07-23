# Layout Architecture Map - Sixteen Theme

**Created:** 2026-04-01  
**Type:** Navigation Index with Bidirectional Links  
**Status:** ✅ Complete  

---

## 🎯 Purpose

Questo documento crea **collegamenti bidirezionali** tra tutti i documenti relativi all'architettura layout del tema Sixteen, per garantire:

1. **Discoverability**: Trovare facilmente documenti correlati
2. **Traceability**: Tracciare relazioni tra documenti
3. **Maintainability**: Aggiornare documenti in modo coordinato
4. **Consistency**: Mantenere coerenza tra documenti

---

## 🏗️ Core Architecture Documents

### Primary Document

**📄 [Layout Architecture](layout-architecture.md)** ← **START HERE**

Comprehensive guide to layout hierarchy, components, and DRY/KISS rationale.

**Related Documents:**
- → [Layout Hierarchy](layout-hierarchy.md) - Detailed hierarchy breakdown
- → [BMAD_LAYOUT_CORRECTION.md](BMAD_LAYOUT_CORRECTION.md) - Correction history
- → [Layout Fix Summary](layout-fix-summary.md) - Fix implementation report
- → [README.md](README.md) - Theme documentation index

**Implements:**
- ← [UI Spec](_bmad-output/ui-spec.md) - Component library requirements
- ← [Architecture](_bmad-output/architecture.md) - System architecture patterns
- ← [PRD](_bmad-output/prd.md) - Product requirements

---

## 📁 Layout Files

### Base Layout

**📄 main.blade.php**  
**Path:** `resources/views/components/layouts/main.blade.php`  
**Component:** `x-layouts.main`  
**Type:** Base HTML Structure

**Extends:** None (Base component)

**Extended By:**
- → [`app.blade.php`](#app-layout)
- → [`guest.blade.php`](#guest-layout)
- → [`auth.blade.php`](#auth-layout)
- → [`admin.blade.php`](#admin-layout)

**Documentation:**
- → [Layout Architecture](layout-architecture.md#x-layoutsmain)
- → [Component Library](components/README.md#main)
- → [Vite Configuration](guides/vite-config.md#main-layout)

---

### App Layout {#app-layout}

**📄 app.blade.php**  
**Path:** `resources/views/components/layouts/app.blade.php`  
**Component:** `x-layouts.app`  
**Type:** Public Frontend Wrapper

**Extends:** [`main.blade.php`](#base-layout)

**Why Extends main:**
1. **DRY**: HTML structure definita una volta sola
2. **KISS**: main gestisce complessità, app aggiunge semantica
3. **Maintainability**: Update 1 file instead of 4
4. **Consistency**: Stessa struttura per tutti i layout

**Used By Pages:**
- Homepage
- CMS pages
- Blog listing
- Public profiles

**Documentation:**
- → [Layout Architecture](layout-architecture.md#x-layoutsapp)
- → [Component Library](components/README.md#app)
- → [CMS Integration](../../../Modules/Cms/docs/sections.md)
- → [Blog Pages](../../../Modules/Blog/docs/public-pages.md)

**Related Components:**
- → [`x-section`](components/section.blade.php) - CMS sections
- → [`x-header`](components/header.blade.php) - Public header
- → [`x-footer`](components/footer.blade.php) - Public footer

---

### Guest Layout {#guest-layout}

**📄 guest.blade.php**  
**Path:** `resources/views/components/layouts/guest.blade.php`  
**Component:** `x-layouts.guest`  
**Type:** Authentication Pages Wrapper

**Extends:** [`main.blade.php`](#base-layout)

**Used By Pages:**
- Login
- Register
- Forgot password
- Reset password

**Documentation:**
- → [Layout Architecture](layout-architecture.md#x-layoutsguest)
- → [Auth Best Practices](auth-best-practices.md)
- → [User Module](../../../Modules/User/docs/authentication.md)

**Related Components:**
- → [`x-auth-card`](components/auth-card.blade.php) - Auth container
- → [`x-logo`](components/logo.blade.php) - Brand logo

---

### Auth Layout {#auth-layout}

**📄 auth.blade.php**  
**Path:** `resources/views/components/layouts/auth.blade.php`  
**Component:** `x-layouts.auth`  
**Type:** Protected Dashboard Wrapper

**Extends:** [`main.blade.php`](#base-layout)

**Used By Pages:**
- User dashboard
- Profile settings
- User tickets
- User notifications

**Documentation:**
- → [Layout Architecture](layout-architecture.md#x-layoutsauth)
- → [Dashboard Guide](guides/dashboard.md)
- → [User Module](../../../Modules/User/docs/dashboard.md)

**Related Components:**
- → [`x-sidebar`](components/sidebar.blade.php) - Sidebar navigation
- → [`x-topbar`](components/topbar.blade.php) - Top navigation
- → [`x-user-menu`](components/user-menu.blade.php) - User dropdown

---

### Admin Layout {#admin-layout}

**📄 admin.blade.php**  
**Path:** `resources/views/components/layouts/admin.blade.php`  
**Component:** `x-layouts.admin`  
**Type:** Filament Admin Panel Wrapper

**Extends:** [`main.blade.php`](#base-layout)

**Used By Pages:**
- Filament resources
- Admin dashboard
- Admin settings
- Admin reports

**Documentation:**
- → [Layout Architecture](layout-architecture.md#x-layoutsadmin)
- → [Filament Integration](../../../Modules/Filament/docs/)
- → [Admin Guide](guides/admin.md)

**Related Components:**
- → Filament Navigation
- → Filament Sidebar
- → Filament Topbar

---

## 🔗 Cross-Document References

### From Other Documents TO Layout Architecture

| Document | Section | Link To |
|----------|---------|---------|
| [README.md](README.md) | Architecture | → [layout-architecture.md](layout-architecture.md) |
| [components.md](components.md) | Layouts | → [layout-architecture.md#component-registry](layout-architecture.md#component-registry) |
| [auth-best-practices.md](auth-best-practices.md) | Layouts | → [layout-architecture.md#x-layoutsguest](layout-architecture.md#x-layoutsguest) |
| [filament-integration.md](filament-integration.md) | Admin Layout | → [layout-architecture.md#x-layoutsadmin](layout-architecture.md#x-layoutsadmin) |

### From Layout Architecture TO Other Documents

| Section | Links To | Purpose |
|---------|----------|---------|
| Executive Summary | [README.md](README.md) | Theme overview |
| Component Registry | [components/README.md](components/README.md) | Component details |
| Design System | [design-system.md](design-system.md) (if exists) | Colors, typography |
| Related Modules | [Modules/Cms/docs/](../../../Modules/Cms/docs/) | CMS integration |

---

## 📊 Document Dependency Graph

```
README.md (Theme Index)
    │
    └──→ layout-architecture.md (Core Architecture)
             │
             ├──→ layout-hierarchy.md (Detailed Hierarchy)
             ├──→ layout-fix-summary.md (Fix Report)
             ├──→ BMAD_LAYOUT_CORRECTION.md (Correction History)
             │
             ├──→ Components
             │    ├──→ components/README.md
             │    ├──→ components/section.blade.php
             │    ├──→ components/header.blade.php
             │    └──→ components/footer.blade.php
             │
             ├──→ Guides
             │    ├──→ guides/vite-config.md
             │    ├──→ guides/dark-mode.md
             │    └──→ guides/cms-sections.md
             │
             └──→ Modules
                  ├──→ Modules/Cms/docs/
                  ├──→ Modules/Blog/docs/
                  ├──→ Modules/User/docs/
                  └──→ Modules/Filament/docs/
```

---

## 🔄 Bidirectional Link Examples

### Example 1: main.blade.php ↔ app.blade.php

**In main.blade.php (header comment):**
```blade
{{--
    Main Layout - Base HTML Structure
    
    EXTENDED BY:
    - x-layouts.app (Public Frontend)
    - x-layouts.guest (Authentication)
    - x-layouts.auth (Dashboard)
    - x-layouts.admin (Admin Panel)
    
    DOCUMENTATION:
    → Layout Architecture: docs/layout-architecture.md#x-layoutsmain
--}}
```

**In app.blade.php (header comment):**
```blade
{{--
    App Layout - Public Frontend
    
    EXTENDS:
    - x-layouts.main (Base HTML Structure)
    
    WHY EXTENDS MAIN:
    1. DRY: HTML structure definita una volta sola
    2. KISS: main gestisce complessità, app aggiunge semantica
    3. Maintainability: Update 1 file instead of 4
    
    DOCUMENTATION:
    → Layout Architecture: docs/layout-architecture.md#x-layoutsapp
    → Main Layout: docs/layout-architecture.md#x-layoutsmain
--}}
```

### Example 2: layout-architecture.md ↔ README.md

**In layout-architecture.md:**
```markdown
## Related Documents

- → [README.md](README.md) - Theme documentation index
- → [components/README.md](components/README.md) - Component library
```

**In README.md:**
```markdown
## Architecture

- → [layout-architecture.md](layout-architecture.md) - Layout hierarchy e componenti
```

---

## 📋 Maintenance Checklist

### When Adding New Layout

- [ ] Create layout file
- [ ] Update layout-architecture.md Component Registry
- [ ] Add bidirectional links in layout file header
- [ ] Update README.md Architecture section
- [ ] Create/update component docs
- [ ] Update this map (Layout Architecture Map)

### When Modifying Layout

- [ ] Update layout-architecture.md if structure changes
- [ ] Update related component docs
- [ ] Check bidirectional links still valid
- [ ] Update fix history if needed

### When Deleting Layout

- [ ] Remove from layout-architecture.md
- [ ] Update "Extended By" in main.blade.php
- [ ] Remove from README.md
- [ ] Archive or redirect old docs
- [ ] Update this map

---

## 🎯 Quick Reference

### File Locations

```
Themes/Sixteen/
└── docs/
    ├── README.md                          ← Theme Index
    ├── layout-architecture.md             ← Core Architecture (START HERE)
    ├── layout-hierarchy.md                ← Detailed Hierarchy
    ├── layout-fix-summary.md              ← Fix Report
    ├── BMAD_LAYOUT_CORRECTION.md          ← Correction History
    └── LAYOUT_ARCHITECTURE_MAP.md         ← This Document (Navigation)
```

### Component Locations

```
Themes/Sixteen/
└── resources/views/
    └── components/
        └── layouts/
            ├── main.blade.php             ← Base
            ├── app.blade.php              ← Public (extends main)
            ├── guest.blade.php            ← Auth (extends main)
            ├── auth.blade.php             ← Dashboard (extends main)
            └── admin.blade.php            ← Admin (extends main)
```

---

## 🔍 Search Index

### By Component Name

| Component | File | Docs |
|-----------|------|------|
| `x-layouts.main` | [main.blade.php](#base-layout) | [layout-architecture.md#x-layoutsmain](layout-architecture.md#x-layoutsmain) |
| `x-layouts.app` | [app.blade.php](#app-layout) | [layout-architecture.md#x-layoutsapp](layout-architecture.md#x-layoutsapp) |
| `x-layouts.guest` | [guest.blade.php](#guest-layout) | [layout-architecture.md#x-layoutsguest](layout-architecture.md#x-layoutsguest) |
| `x-layouts.auth` | [auth.blade.php](#auth-layout) | [layout-architecture.md#x-layoutsauth](layout-architecture.md#x-layoutsauth) |
| `x-layouts.admin` | [admin.blade.php](#admin-layout) | [layout-architecture.md#x-layoutsadmin](layout-architecture.md#x-layoutsadmin) |

### By Topic

| Topic | Document | Section |
|-------|----------|---------|
| Layout Hierarchy | [layout-architecture.md](layout-architecture.md) | §2 System Architecture |
| DRY Rationale | [layout-architecture.md](layout-architecture.md) | §3 Why app extends main |
| Component Usage | [layout-architecture.md](layout-architecture.md) | §4 Component Details |
| Fix History | [layout-fix-summary.md](layout-fix-summary.md) | Complete |
| Correction Process | [BMAD_LAYOUT_CORRECTION.md](BMAD_LAYOUT_CORRECTION.md) | Complete |

---

**Last Updated:** 2026-04-01  
**Next Review:** 2026-04-08  
**Owner:** Frontend Team  
**Status:** ✅ Complete
