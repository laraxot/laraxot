---
id: phpstan-intervention-module-fix
slug: phpstan-intervention-module
scope:
  - module:Intervention
  - project:base_workorder_fila5
status: In Progress
epic: PHPStan Quality Gates
priority: High
created: 2026-09-06
---

## Problema

PHPStan su Modules/Intervention restituisce **110+ errori**.

## Errori Principali

1. **Cast mixed to int**: 50+ occorrenze
2. **staticMethod.notFound**: factory() non definiti
3. **method.nonObject**: chiamate su mixed

## Solution Overview

1. Fix cast mixed to int pattern
2. Generate factories con artisan
3. Add proper type assertions

## Acceptance Criteria

- [ ] 0 PHPStan errors in Intervention module
- [ ] PHPMD passes
- [ ] PHPInsights > 90%
- [ ] Pest coverage incremented

## Pattern Fix

```php
// Cast mixed to int
$id = is_numeric($data['id'] ?? null) ? (int) $data['id'] : 0;
```
