# Sixteen Theme Documentation Index

**Theme**: Sixteen (Design Comuni / AGID Compliant)  
**Last Update**: 2026-04-01  
**Status**: ✅ Active  
**Version**: 2.0

---

## 🎯 Quick Navigation

### 🚀 Start Here
1. **[README](./README.md)** - Theme overview
2. **[00-index](./00-index.md)** - Main documentation index
3. **[Roadmap](./roadmap/README.md)** - Project status and plan

### 🏗️ Architecture (NEW)
1. **[Layout Hierarchy](./layout-hierarchy.md)** ✅ - `x-layouts.app` extends `x-layouts.main`
2. **[Components Directory](./components-directory-structure.md)** ✅ - Complete component organization
3. **[Vite Configuration](./vite-configuration-guide.md)** ✅ - Build configuration (`outDir: './public'`)
4. **[Build Commands](./build-commands-guide.md)** ✅ - Complete build process
5. **[Vite Config Fix](./vite-config-fix-summary.md)** ✅ - Why `outDir: './public'`

---

## 📚 Documentation by Category

### 🏛️ Architecture & Standards

| Document | Status | Description |
|----------|--------|-------------|
| [Layout Hierarchy](./layout-hierarchy.md) | ✅ NEW | Base structure → Public frontend → Specialized layouts |
| [Components Directory](./components-directory-structure.md) | ✅ NEW | Layouts, Sections, Blocks, AGID components |
| [Vite Configuration](./vite-configuration-guide.md) | ✅ NEW | Build configuration with `outDir: './public'` |
| [Build Commands](./build-commands-guide.md) | ✅ NEW | `composer update -W` → `npm run build` → `npm run copy` |
| [Vite Config Fix](./vite-config-fix-summary.md) | ✅ NEW | Why `outDir: './public'` explained |
| [Block System](./blocks-system.md) | ✅ | Content blocks architecture |
| [File Naming Rules](./file-naming-rules.md) | ✅ | Naming conventions |
| [Critical Rules](./critical-rules.md) | ✅ | Must-follow rules |

### 🇮🇹 Design Comuni / AGID Compliance

| Document | Status | Description |
|----------|--------|-------------|
| [Design Comuni Implementation](./design-comuni-implementation.md) | ✅ | Primary technical reference |
| [AGID Checklist](./agid-checklist.md) | ✅ | Compliance verification |
| [AGID Compliance Summary](./agid-compliance-summary.md) | ✅ | Summary of work done |
| [AGID Components Reorganization](./agid-components-reorganization.md) | ✅ | Component structure |
| [AGID Layout Usage Rules](./agid-layout-usage-rules.md) | ✅ | How to use AGID layouts |
| [Components Status](./components-status.md) | ✅ | Current state of all components |

### 🎨 Components & Blocks

| Document | Status | Description |
|----------|--------|-------------|
| [Components](./components.md) | ✅ | Component overview |
| [Component Usage Guide](./component-usage-guide.md) | ✅ | How to use components |
| [Complete Component Categorization](./complete-component-categorization.md) | ✅ | All components organized |
| [AGID Components](./agid/) | 📁 | AGID-specific components |
| [Blocks](./blocks/) | 📁 | Content blocks documentation |

### 🔐 Authentication

| Document | Status | Description |
|----------|--------|-------------|
| [Auth Best Practices](./auth-best-practices.md) | ✅ | Security guidelines |
| [Auth Pages Analysis](./auth-pages-analysis.md) | ✅ | Authentication pages |
| [AGID Login Implementation](./agid-login-implementation-plan.md) | ✅ | Login compliance plan |

### 📧 Email Templates

| Document | Status | Description |
|----------|--------|-------------|
| [Christmas Email Template](./christmas-email-template-implementation.md) | ✅ | Holiday email design |
| [Advanced Email Templates](./advanced-email-templates.md) | ✅ | Email template guide |

### 🛠️ Build & Development

| Document | Status | Description |
|----------|--------|-------------|
| [Build Commands Guide](./build-commands-guide.md) | ✅ NEW | Complete build process |
| [Vite Configuration](./vite-configuration-guide.md) | ✅ NEW | Vite setup and usage |
| [Build System Best Practices](./build-system-best-practices.md) | ✅ | Build optimization |
| [Build Issues Resolution](./build-issues-resolution.md) | ✅ | Troubleshooting builds |

### ♿ Accessibility

| Document | Status | Description |
|----------|--------|-------------|
| [Accessibility Guide](./accessibility.md) | ✅ | WCAG 2.1 AA compliance |
| [Accessibility Implementation](./accessibility-implementation-guide.md) | ✅ | Implementation details |
| [Accessibility Components](./accessibility-components.md) | ✅ | Accessible components |

### 📊 Project Management

| Document | Status | Description |
|----------|--------|-------------|
| [Roadmap](./roadmap/README.md) | 📁 | Project roadmap |
| [Product Docs](./product/README.md) | 📁 | Product documentation |
| [Design Comuni Screenshots](./screenshots/) | 📁 | Visual comparisons |
| [FAQ Page Parity](./FAQ-PARITY.md) | ✅ | FAQ page visual parity |
| [Homepage Parity](./homepage-parity-2026-04-02.md) | ✅ | Homepage visual parity |
| [Visual Parity Analysis](./visual-parity/00-INDEX.md) | ✅ NEW | Cross-page visual comparison |

### 🔗 Bidirectional Links

All documentation files now include **bidirectional links** for easy navigation:

### From Architecture Docs
- [[00-index]] - Main index
- [[components-directory-structure]] - Components
- [[vite-configuration-guide]] - Vite config
- [[build-commands-guide]] - Build process

### From Build Docs
- [[00-index]] - Main index
- [[layout-hierarchy]] - Layouts
- [[vite-configuration-guide]] - Vite config
- [[components-directory-structure]] - Components

### From Component Docs
- [[00-index]] - Main index
- [[layout-hierarchy]] - Layouts
- [[blocks-system]] - Blocks
- [[README]] - Theme overview

### From FAQ Docs
- [[faq-screenshots.md]] - Screenshot script docs
- [[bashscripts/docs/faq-screenshots.md]] - External script docs

---

## 📁 Documentation Structure

```
laravel/Themes/Sixteen/docs/
├── 00-index.md                    ← Main documentation index
├── README.md                      ← Theme overview
├── layout-hierarchy.md            ← Layout architecture (NEW)
├── components-directory-structure.md ← Components index (NEW)
├── vite-configuration-guide.md    ← Vite config (NEW)
├── build-commands-guide.md        ← Build process (NEW)
├── vite-config-fix-summary.md     ← Vite fix summary (NEW)
├── agid/                          ← AGID compliance docs
├── auth/                          ← Authentication docs
├── blocks/                        ← Content blocks docs
├── components/                    ← Component docs
├── design-comuni/                 ← Design Comuni docs
├── homepage/                      ← Homepage docs
├── product/                       ← Product docs
├── prompts/                       ← AI prompts
├── roadmap/                       ← Project roadmap
└── screenshots/                   ← Visual comparisons
```

---

## 🎯 DRY + KISS Compliance

### DRY (Don't Repeat Yourself)
✅ All configuration in ONE place  
✅ Documentation indexed with bidirectional links  
✅ No duplicate information across files  

### KISS (Keep It Simple, Stupid)
✅ Clear file naming conventions  
✅ Organized by category  
✅ Easy to navigate with links  

---

## 📊 Documentation Statistics

| Metric | Count |
|--------|-------|
| **Main Index Files** | 2 (00-index.md, README.md) |
| **Architecture Docs** | 5 (NEW) |
| **AGID Compliance** | 15+ |
| **Component Docs** | 20+ |
| **Build Docs** | 5 |
| **Accessibility** | 5 |
| **Total Files** | 200+ |

---

## 🔗 External Resources

### Laravel & Vite
- [Laravel Documentation](https://laravel.com/docs)
- [Vite Documentation](https://vitejs.dev/)
- [Tailwind CSS](https://tailwindcss.com/docs)

### Design Comuni & AGID
- [Design Comuni](https://italia.github.io/design-comuni-pagine-statiche/)
- [Bootstrap Italia](https://italia.github.io/bootstrap-italia/)
- [AGID Guidelines](https://www.agid.gov.it/it/argomenti/linee-guida-design-pa)

### Filament
- [Filament v5](https://filamentphp.com/docs/5.x)

---

## 📝 Changelog

| Date | Version | Change | Author |
|------|---------|--------|--------|
| 2026-04-03 | 2.3 | Added FAQ Page Parity docs + bidirectional links | AI Agent |
| 2026-04-02 | 2.2 | Added Homepage visual parity docs | AI Agent |
| 2026-04-01 | 2.0 | Complete documentation reorganization | AI Agent Team |
| 2026-04-01 | 2.1 | Added architecture docs (NEW) | AI Agent Team |
| 2026-04-01 | 2.2 | Added bidirectional links | AI Agent Team |

---

*Documentation conforme agli standard Laraxot - DRY + KISS compliant*
