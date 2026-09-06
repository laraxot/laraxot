---
id: phpstan-timber-module-fix
slug: phpstan-timber-module
scope:
  - module:Timber
  - project:base_workorder_fila5
status: Pending
epic: PHPStan Quality Gates
priority: High
created: 2026-09-06
---

## Problema

PHPStan su Modules/Timber restituisce **50+ errori** in seeders.

## Errori Principali

1. **staticMethod.notFound**: factory() non definiti
2. **method.nonObject**: count(), create() su mixed

## Solution Overview

1. Generate factories con artisan
2. Fix seeder patterns
3. Add proper type assertions

## Acceptance Criteria

- [ ] 0 PHPStan errors in Timber module
- [ ] PHPMD passes
- [ ] PHPInsights > 90%
- [ ] Pest coverage incremented
