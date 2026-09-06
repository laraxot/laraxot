---
name: PHPStan WorkOrder Claim
description: Claim + progress log for PHPStan level-max fixes and Filament v4→v5 deprecations in Modules/WorkOrder
date: 2026-09-06
---

# PHPStan WorkOrder — Claim

## Scope
`Modules/WorkOrder` only (own git repo, remote `laraxot/module_workorder_fila5.git`, branch `dev`).

## Status: IN PROGRESS

## Files locked (bashscripts/lock)
- app/Filament/Resources/WorkOrderResource.php
- app/Filament/Resources/WorkOrderResource/Pages/{CreateWorkOrder,EditWorkOrder,ListWorkOrders,ViewWorkOrder}.php
- app/Filament/Resources/WorkOrderResource/RelationManagers/{Activities,Assignments,ChangeOrders,ComplianceDocuments,Interventions,PublicProcurementContracts,StatusEvents}RelationManager.php
- app/Models/Profile.php
- tests/Feature/PeriodicSummaryTest.php

## Notes
- Found a partially-completed, uncommitted work-in-progress already in the working tree (some cast/Filament-v5 fixes applied to WorkOrderResource.php, its Pages, Activities/Assignments/InterventionsRelationManager.php). Continuing that work rather than redoing it. Found and fixed one real bug introduced by that WIP: operator-precedence error in InterventionsRelationManager.php materials_summary state closure (ternary vs concatenation).
- Cross-module heads-up: `tests/Feature/PeriodicSummaryTest.php` failures trace to `Modules\User\Models\BaseUser` using `HasXotFactory` with NO `newFactory()` override (confirmed via grep — only `use HasXotFactory` at line 144, no override), same root cause as the WorkOrder.php factory bug fixed earlier this session (commit c65731b). NOT fixing `Modules/User` here (different repo/owner) — narrowing the test helper's return type locally instead.
- No other active lock or docs/chat claim found for WorkOrder at claim time; all target files were FREE per `bashscripts/lock/check.sh`.
