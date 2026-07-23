---
title: "PTVX Architecture Summary"
type: concept
tags: [architecture, summary]
created: 2026-07-14
updated: 2026-07-14
qmd: "architecture-summary ptvx architecture summary"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# PTVX Architecture Summary

> **Status**: Consolidated from Deep Study (2026-03-10)
> **Confidence Level**: High (100%)

## 1. The Laraxot Core (Engine)
The system is built on the **Laraxot** modular framework. At its center is the `Xot` module.

### Core Abstractions
- **Models**: Every model in the system follows the chain: `Model` -> `ModuleBaseModel` -> `XotBaseModel` -> `Eloquent\Model`. This ensures global traits like `HasXotFactory`, `RelationX`, and `Updater` are available everywhere.
- **Resources**: Filament resources extend `XotBaseResource`, which enforces `final` `form()` and `table()` methods, forcing developers to use `getFormSchema()` and `getTableColumns()`. This enables automatic translation key generation and consistent UI patterns.
- **Service Providers**: All module providers extend `XotBaseServiceProvider`, which handles automatic discovery of routes, views, translations, and migrations.

## 2. Module Ecosystem
PTVX is a **Modular Monolith**.

### Key Modules
- **Xot**: Infrastructure, base classes, monitoring (Pulse), sessions, cache.
- **User**: Identity management, Spatie Permissions, Passport/OAuth2 wrappers, Profile EAV system.
- **Ptv**: The main business process engine for evaluations (Schede, Valutatori). It coordinates legacy data mapping and sync logic.
- **UI**: Shared Blade components and frontend utilities.
- **Lang**: Centralized translation management.

## 3. Themes (The "Vestito")
Themes are decoupled from business logic.
- **Zero Theme**: The minimalist, high-performance foundation based on Tailwind CSS 4.0.
- **One Theme**: An alternative premium theme.
- **Philosophy**: Business logic resides in modules; themes only handle visualization and design tokens.

## 4. Engineering Standards
- **PHPStan**: 100% Level 10 compliance across all 35 modules.
- **Testing**: Pest-driven development. `DatabaseTransactions` used instead of `RefreshDatabase`. Global `XotBaseTestCase` for all tests.
- **Conventions**: No hardcoded labels; strict use of translation keys; mandatory lowercase date-free doc filenames.

## 5. Directory Mapping
- Root: `./`
- Laravel Core: `laravel/`
- Modules: `laravel/Modules/`
- Themes: `laravel/Themes/`
- Documentation: `docs/` (global) and `laravel/Modules/{Name}/docs/` (local).

---
[Return to Documentation Index](../../AGENTS.md)
