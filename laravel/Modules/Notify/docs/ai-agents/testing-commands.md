---
title: "Testing commands"
type: concept
tags: [testing, commands]
created: 2026-07-14
updated: 2026-07-14
qmd: "testing-commands testing commands"
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

# Testing commands

## Run all tests

```bash
cd laravel && php artisan test
```

## Run a specific test file

```bash
cd laravel && php artisan test tests/Feature/ExampleTest.php
```

## Run by filter/name

```bash
cd laravel && php artisan test --filter=testName
```

## Run tests for a specific module

```bash
cd laravel && php artisan test Modules/User/tests
```

## Create a new test (Pest)

```bash
cd laravel && php artisan make:test --pest FeatureTestName
```

## PHPUnit directly

```bash
cd laravel && vendor/bin/phpunit
```
