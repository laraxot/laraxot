---
title: "Zero Theme - Documentation Index"
type: concept
tags: ['filament', 'laravel', 'charts', 'testing']
created: 2026-07-14
updated: 2026-07-14
qmd: "zero theme - documentation index"
related:
  - "./00-INDEX.md"
  - "./00-index.md"
---

# Zero Theme - Documentation Index

**Last Update**: 2026-03-28
**Status**: Available
**Theme Version**: 1.0

## Quick Navigation

### Essential Reading
1. [README.md](./README.md) - Theme overview
2. [architecture.md](./architecture.md) - Theme architecture ("vestito" pattern)
3. [modern-theme-architecture.md](./modern-theme-architecture.md) - Modern stack (Livewire 4, Volt, Flux UI)
4. [packages-integration.md](./packages-integration.md) - Package integration (Flux UI, Volt, Vite, Tailwind v4)
5. [model-docs-governance.md](./model-docs-governance.md) - Naming and docs hygiene rules

### Development Guides
- [ai-development-guide.md](./ai-development-guide.md) - AI-assisted development workflows
- [accessor-delegation-pattern.md](./accessor-delegation-pattern.md) - Accessor delegation & auto-persistence (SACRO)
- [filament-chart-integration.md](./filament-chart-integration.md) - Filament 5.x ChartWidget integration
- [phpstan-dry-kiss-theme-guidelines.md](./phpstan-dry-kiss-theme-guidelines.md) - PHPStan Level 10 + DRY/KISS for themes
- [components.md](./components.md) - Component catalog
- [layouts.md](./layouts.md) - Layout system
- [authentication.md](./authentication.md) - Auth integration
- [customization.md](./customization.md) - Theme customization

### Product & Planning
- [product-requirements.md](./product-requirements.md) - PRD
- [roadmap.md](./roadmap.md) - Theme roadmap
- [changelog.md](./changelog.md) - Version history

- [CHANGELOG.md](./CHANGELOG.md) - Version history

### Stack Reference
| Technology | Version | Purpose |
|------------|---------|---------|
| Laravel | 12 | Core framework |
| Filament | 5.x | Admin panel & widgets |
| Livewire | 4.x | Reactive components |
| Tailwind CSS | v4 | Utility-first CSS |
| Vite | v6 | Build tool |
| Flux UI | latest | Accessible UI components |
| Volt | latest | Livewire functional/class components |

### Anti-Patterns
- QuestionChartAnswersTripleChartWidget — why multi-chart widgets should be avoided (link removed 2026-07-24: target doc not found in repo, could not verify)
- **[QuestionChartAnswersTripleChartWidget](../../Modules/docs/anti-pattern-question-chart-answers-triple-widget.md)** - Why multi-chart widgets should be avoided

### Theme Characteristics
- **Style**: Ultra-minimalist
- **Target**: Lightweight applications
- **Framework**: Minimal CSS via Tailwind v4

### Related Resources

- [Cms Module](../../../Modules/Cms/docs/README.md)
- [UI Module](../../../Modules/UI/docs/README.md)
- [Xot Module](../../../Modules/Xot/docs/00-index.md) - Base classes
- [Cms Module](../../Modules/Cms/docs/README.md)
- [UI Module](../../Modules/UI/docs/README.md)
- [Xot Module](../../Modules/Xot/docs/00-index.md) - Base classes
- [Cms Module](../../Modules/Cms/docs/README.md)
- [UI Module](../../Modules/UI/docs/README.md)
- [Xot Module](../../Modules/Xot/docs/00-index.md) - Base classes
- [Cms Module](../../Modules/Cms/docs/README.md)
- [UI Module](../../Modules/UI/docs/README.md)
- [Xot Module](../../Modules/Xot/docs/00-index.md) - Base classes
- [Cms Module](../../Modules/Cms/docs/README.md)
- [UI Module](../../Modules/UI/docs/README.md)
- [Xot Module](../../Modules/Xot/docs/00-index.md) - Base classes
- [agents.md](../../../agents.md) - Project guidelines

- [AGENTS.md](../../../AGENTS.md) - Project guidelines

---

*Theme documentation conforming to Laraxot standards*

---

<!-- Merged from 00-INDEX.md, which collided with this file on case-insensitive filesystems. -->

---
title: "Zero Theme Documentation Index"
type: concept
tags: ['laravel']
created: 2026-07-14
updated: 2026-07-14
qmd: "zero theme documentation index"
related:
  - "./00-index.md"
---

# Zero Theme Documentation Index

> **Nota 2026-07-24**: file d'indice ridondante rispetto a [00-index.md](./00-index.md), che è il più recente
> (2026-03-28) e allineato allo stack corrente (Filament 5, Livewire 4, Volt, Tailwind v4). Consultare
> `00-index.md` come punto di ingresso primario; questo file resta come vista alternativa non aggiornata
> nella stessa sessione.

# Zero Theme Documentation Index

**Path**: `laravel/Themes/Zero/docs/`  
**Last updated**: 2026-06-30

## Theme Overview

Zero is a minimal theme variant providing core layout and styling structures without heavy customization.

## Documentation Structure

Zero theme documentation follows the standard theme structure with focus on:
- Minimal, semantic HTML
- Essential CSS for layout
- Cross-browser compatibility
- Progressive enhancement principles

## Key References

- [Theme Hub](../Barthelemy/docs/00-index.md) - Overview of all themes
- [Sixteen Theme](../Sixteen/docs/00-index.md) - Primary theme (Design Comuni)
- [TwentyOne Theme](../TwentyOne/docs/00-index.md) - Prediction market theme
- [TwentyOne Theme](../TwentyOne/docs/00-index.md) - forecast market theme
- [TwentyOne Theme](../TwentyOne/docs/00-index.md) - Prediction market theme
- [Directory Structure Rules](../Barthelemy/docs/directory-structure-rules.md) - Theme organization

## Related Modules

- [UI Module](../../Modules/UI/docs/00-index.md) - Shared UI components
- [Xot Module](../../Modules/Xot/docs/00-index.md) - Core utilities

## Asset Publication

Theme assets are published to: `public_html/themes/Zero/`

Refer to the main theme documentation for build and deployment procedures.
# 📚 Zero Theme - Documentation Index

**Path**: `laravel/Themes/Zero/docs/`  
**Tema**: @Themes/Zero

## 📄 Documenti

### Architettura
| File | Scopo |
|------|-------|
| ARCHITECTURE_UX_IMPROVEMENTS.md | Architecture & UX |
| HOMEPAGE_ARCHITECTURE.md | Homepage architecture |
| HEADERNAV_CMS_ARCHITECTURE.md | Header/Nav CMS |
| FOOTER_ARCHITECTURE.md | Footer architecture |

### Filament
| File | Scopo |
|------|-------|
| FILAMENT_5_IMPLEMENTATION.md | Filament v5 setup |
| FILAMENT_TABLES_SETUP.md | Tables setup |

### GSAP & Animations
| File | Scopo |
|------|-------|
| GSAP_ANIMATIONS_GUIDE.md | GSAP guide |
| GSAP_SCROLLTRIGGER_CONFIGURATION.md | ScrollTrigger config |

### Frontend
| File | Scopo |
|------|-------|
| BUILD_PROCESS.md | Build process |
| CODE_QUALITY_ANALYSIS.md | Code quality |

## 🔗 Riferimenti

- [Predict Module](../../Modules/Predict/docs/00-index.md) - Main module
- [Xot Module](../../Modules/Xot/docs/00-index.md) - Base classes
- [agents.md](../../../agents.md) - Project guidelines

---

**Ultimo Aggiornamento**: 2026-03-24
