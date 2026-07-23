---
title: "Code quality commands"
type: concept
tags: [code, quality, commands]
created: 2026-07-14
updated: 2026-07-14
qmd: "code-quality-commands code quality commands"
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

# Code quality commands

## Laravel Pint (formatting)

```bash
cd laravel && vendor/bin/pint
cd laravel && vendor/bin/pint --dirty
```

## PHPStan (static analysis)

Project rules:

- Use **only** `laravel/phpstan.neon` (do not edit it).
- Do **not** pass `--level`.
- Do **not** generate or use baselines.

```bash
cd laravel && vendor/bin/phpstan analyse Modules
```

## PHP Insights

```bash
cd laravel && php artisan insights
cd laravel && vendor/bin/phpinsights
```

## Rector

```bash
cd laravel && vendor/bin/rector process
cd laravel && vendor/bin/rector process --dry-run
```
