---
id: phpstan-xot-module-fix
slug: phpstan-xot-module
scope:
  - module:Xot
  - project:base_workorder_fila5
status: In Progress
epic: PHPStan Quality Gates
priority: High
created: 2026-09-06
---

## Problema

PHPStan su Modules/Xot restituisce **215 errori**.

## Errori Principali

1. **Pest internal class**: `toBe()`, `toBeTrue()` - 180+ errori in tests
2. **Method not found**: `expectExceptionMessageIsOrContains()` in XotBaseTestCase.php
3. **Internal class calls**: `in()` in tests/pest.php

## Solution Overview

1. Fix pattern Pest internal calls
2. Fix XotBaseTestCase helper methods
3. Verify with phpstan + phpmd + phpinsights + pest

## Acceptance Criteria

- [ ] 0 PHPStan errors in Xot module
- [ ] PHPMD passes
- [ ] PHPInsights > 90%
- [ ] Pest coverage incremented

## Stories Figlie

- [ ] `phpstan-xot-pest-fix` - Fix Pest internal errors
- [ ] `phpstan-xot-testcase-fix` - Fix XotBaseTestCase
