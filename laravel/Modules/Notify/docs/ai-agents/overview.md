---
title: "Overview"
type: concept
tags: [overview]
created: 2026-07-14
updated: 2026-07-14
qmd: "overview overview"
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

# Overview

## Project

PTVX is a modular HR & Performance evaluation system built on Laravel + Filament + Laraxot.

## Non-negotiables (Laraxot)

- Do not extend Filament classes directly in application modules: use `XotBase*` wrappers.
- Translations must not be hardcoded in Filament components.
- Prefer Actions (e.g. Spatie Queueable Action) over Services.
- Use PHPStan Level 10 approach: "Fix, Don't Ignore" - all errors must be resolved.
- Follow module-per-module workflow: complete one module before moving to the next.
- Use MCP tools when encountering file access limitations.

## Where the authoritative docs live

- Project docs: `docs/`
- Module docs: `laravel/Modules/{Module}/docs/`
- MCP configuration: `laravel/.mcp.json`

## Links

- [General docs index](../../docs/README.md)
- [Claude docs index](./context.md)
- [Workflow](./workflow.md)
- [PHPStan](./phpstan.md)
- [Filament](./filament.md)
- [MCP](./mcp.md)
