---
title: "Code Quality"
type: concept
tags: [code, quality]
created: 2026-07-14
updated: 2026-07-14
qmd: "code-quality code quality"
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

# Code Quality

> Standard di qualità del codice per PTVX.

## 🔍 Quality Checks (obbligatori dopo ogni modifica)

### PHPStan (Level 10)
```bash
php -d memory_limit=2G ./vendor/bin/phpstan analyse
```

**Regole:**
- Tutti gli errori devono essere risolti
- Nessun ignore errors
- Livello 10 obbligatorio

### PHPMD
```bash
bash laravel/tools/phpmd.sh laravel text phpmd.xml --exclude vendor,node_modules,bootstrap,caches
```

**Regola:** Usare sempre il wrapper PHAR, mai composer require.

### PHPInsights
```bash
./vendor/bin/phpinsights -v --no-interaction
```

## ✨ Laravel Pint (PSR-12)
```bash
# Verifica
./vendor/bin/pint --test

# Correzione automatica
./vendor/bin/pint --dirty
```

## 🔗 Link

**Di ritorno:**
- → [claude.md - Code Quality](../../claude.md)
- → [AGENTS.md - Quality Checks](../../AGENTS.md#quality-checks-obbligatori-dopo-ogni-modifica)
- → [INDEX](index.md)
