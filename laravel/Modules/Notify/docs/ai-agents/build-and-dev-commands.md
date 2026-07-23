---
title: "Build & development commands"
type: concept
tags: [build, dev, commands]
created: 2026-07-14
updated: 2026-07-14
qmd: "build-and-dev-commands build & development commands"
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

# Build & development commands

## Frontend

```bash
cd laravel && npm run dev
cd laravel && npm run build
cd laravel && composer run dev
```

## Laravel optimize/clear

```bash
cd laravel && php artisan optimize
cd laravel && php artisan config:clear
cd laravel && php artisan route:clear
cd laravel && php artisan view:clear
```
