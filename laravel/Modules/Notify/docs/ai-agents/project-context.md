---
title: "Project Context"
type: concept
tags: [project, context]
created: 2026-07-14
updated: 2026-07-14
qmd: "project-context project context"
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

# Project Context

> Contesto generale del progetto PTVX Fila5 Mono.

## 📋 Informazioni Progetto

- **Project**: PTVX Fila5 Mono
- **Stack**: Laravel 12 | Filament v5 | Pest v4 | PHPStan Level 10 | PHP 8.3+
- **Tipo**: Modular HR & Performance evaluation system

## 🎯 Regole Fondamentali

1. **Leggi → Ragiona → Studia → Aggiorna Docs → Migliora**
2. `declare(strict_types=1);` in ogni file PHP
3. Short array syntax `[]` - MAI usare `array()`
4. NEVER use `property_exists()` su modelli Eloquent

## 📁 Documentazione Locations

| Tipo | Percorso |
|------|----------|
| Project docs | `docs/` |
| Module docs | `laravel/Modules/{Module}/docs/` |
| Bashscripts docs | `bashscripts/docs/` |
| MCP config | `laravel/.mcp.json` |
| GitHub workflows | `.github/workflows/` |

## 🔗 Link

**Precedente:** [INDEX](INDEX.md) | **Successivo:** [Module Architecture](module-architecture.md)

**Di ritorno:**
- [claude.md](../../claude.md)
- [AGENTS.md](../../AGENTS.md)
