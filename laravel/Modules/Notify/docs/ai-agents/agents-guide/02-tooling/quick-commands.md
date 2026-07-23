---
title: "Quick Commands"
type: concept
tags: [quick, commands]
created: 2026-07-14
updated: 2026-07-14
qmd: "quick-commands quick commands"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./build-lint-test-commands.md"
---

# Quick Commands

## Testing

```bash
# Run specific test
./vendor/bin/pest --filter="test_name"

# Run module tests
./vendor/bin/pest Modules/Xot/tests

# Coverage
./vendor/bin/pest --coverage
```

## Quality (Mandatory after changes)

```bash
# PHPStan
php -d memory_limit=2G ./vendor/bin/phpstan analyse

# PHPMD
bash laravel/tools/phpmd.sh laravel text phpmd.xml --exclude vendor,node_modules,bootstrap,caches

# PHPInsights
./vendor/bin/phpinsights -v --no-interaction
```

## Format & Build

```bash
# Pint formatter
./vendor/bin/pint --dirty

# Frontend build
npm run dev && npm run build

# Module merge
composer go
```

---
[Back to index](../index.md)
