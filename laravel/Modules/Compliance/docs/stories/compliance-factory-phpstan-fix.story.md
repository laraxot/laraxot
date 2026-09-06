---
id: compliance-factory-phpstan-fix
slug: compliance-factory-phpstan
scope: [module:Compliance, project:base_workorder_fila5]
status: done
priority: High
created: 2026-09-06
---

## Problema
PHPStan 4 errors in Compliance module - factory methods not found:
- `BoilerFactory.php:25` - `HeatingSystem::factory()`
- `MaintenanceControlFactory.php:25` - `HeatingSystem::factory()`
- `WorkOrderChangeOrderFactory.php:25` - `WorkOrder::factory()`
- `WorkOrderReadinessItemFactory.php:25` - `WorkOrder::factory()`

## Root Cause
`HeatingSystem` model missing `HasXotFactory` trait. `WorkOrder` has it but PHPStan needs proper type stubs.

## Fix
1. Add `HasXotFactory` trait to `HeatingSystem` model

## Verify
- PHPStan: 0 errors
- PHPMD: pass
- PHPInsights: pass
- Pest: pass
