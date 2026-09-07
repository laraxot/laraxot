---
name: workorder-coverage
description: PHPStan/PHPMD/Pest/PHPInsights gate status for Modules/WorkOrder — 2026-09-06/07 PHPStan L10 cleanup + 2026-09-07 Epic4 Schemas/Tables tail fix
metadata:
  type: quality-gate
---

# WorkOrder — quality gate status (2026-09-06/07, updated 2026-09-07 evening)

## PHPStan

- 2026-09-06/07 pass: 75 real errors → **0 errors**.
- 2026-09-07 evening pass (this session): regression to **6 errors**, all inside the
  Epic4 `Schemas/`/`Tables/` extraction left mid-flight by a prior opencode session
  (story-2.15, lock released, WIP uncommitted, last touch ~3h before this check — see
  `docs/chat/2026-09-07-phpstan-workorder-standdown.md` for the standdown that found
  the same 3 files still contended). Root cause and fix per file:
  - `WorkOrderResource.php`: `getCustomerOptionsForCurrentPanel()` was `private
    static` but called from the sibling class
    `WorkOrderResource\Schemas\WorkOrderForm` (not a subclass — extraction moved the
    caller into a different class in the same namespace tree). Made `public static`
    with a docblock note explaining why. This alone cleared both the
    `staticMethod.private` error in `WorkOrderForm.php` and the `method.unused` error
    on the Resource itself (PHPStan considered it dead because the only real caller
    was invisible to it).
  - `WorkOrderResource/Schemas/WorkOrderForm.php`: `getFormSchema()` returned a plain
    list (`array<int, Section>`, single `Section::make()` with no key) but the
    abstract parent `XotBaseResourceForm::getFormSchema()` requires
    `array<string, Component>`. Gave the top-level `Section` the key `'main'`
    (mirrors `WorkOrderResource::getInfolistSchema()` in the same file family, which
    already used `'main' => Section::make()...`).
  - `WorkOrderResource/Tables/WorkOrdersTable.php`: three `return.type` errors where
    the method's own `@return` docblock didn't match what it actually returned —
    `getTableFilters()` declared `array<string, Filter>` but returns a mix of `Filter`
    and `SelectFilter`; `getTableActions()`/`getTableBulkActions()` declared
    `array<int, Action>` but both return associative arrays keyed by action name
    (`'exportReport'`, `'delete'`, ...). Widened each docblock to match the real
    shape: `array<string, Filter|SelectFilter>`, `array<string, Action>`,
    `array<string, DeleteBulkAction|RestoreBulkAction|ForceDeleteBulkAction>`. Same
    exact pattern already applied in
    `Modules/Intervention/app/Filament/Resources/InterventionResource/Tables/InterventionsTable.php`
    — reused verbatim, not reinvented.
- After (this session): **0 errors** (`phpstan analyse Modules/WorkOrder
  --memory-limit=-1 --no-progress`, clean `/tmp/phpstan/resultCache.php`, verified
  after every single-file edit and once more after `pint`).

## Pint

`pint` run scoped to only the 3 files touched (not `--dirty`, which would have
reformatted ~15 other files still dirty from the Epic4 WIP belonging to other
agents/sessions — see second-brain `feedback_pint_dirty_scope_multi_agent.md`).
Fixed unused-import and spacing nits on `WorkOrderResource.php` (imports that became
dead once their code moved into `Schemas/`/`Tables/`) and `WorkOrderForm.php`
(`fully_qualified_strict_types`). Re-ran PHPStan after: still 0 errors.

## PHPMD

`./tools/phpmd.sh Modules/WorkOrder text Modules/WorkOrder/phpmd.ruleset.xml` (the
module does have its own ruleset now, unlike the 2026-09-06/07 pass which didn't find
one). Findings are all pre-existing (`CyclomaticComplexity`/`NPathComplexity` on
`BuildPeriodicSummaryAction::execute()`, `ShortVariable` on `$to`/`$a`/`$b`,
`UnusedFormalParameter` on Filament relation-manager lifecycle hooks and
`WorkOrderPolicy` methods, `CouplingBetweenObjects`/`ExcessiveClassComplexity` on
`WorkOrderResource`/`WorkOrder` model, `ElseExpression` in the report actions). None
introduced by this pass (none of the 3 touched files gained a new finding beyond the
pre-existing `CouplingBetweenObjects`/`ExcessiveClassComplexity` already present on
`WorkOrderResource`). Left as-is — out of scope for a PHPStan-errors fix.

## Pest

No module-local `phpunit.xml` (uses root config).
`./vendor/bin/pest Modules/WorkOrder/tests --no-coverage`:
**7 passed / 45 failed / 15 skipped (10 assertions)** — identical to the
2026-09-06/07 baseline, confirmed unchanged by this pass. All 45 failures are
`SQLSTATE[42S02]: Base table or view not found: ... workorder_data_test.customers`
— the shared test DB is still missing tables, pre-existing environment blocker, not
touched (per "data is sacred", see second-brain
`project_test_db_missing_migrations_blocks_feature_tests.md`). Coverage cannot be
meaningfully raised while this blocker stands; no regression introduced by this pass.

## PHPInsights

Confirmed installed and functional (`vendor/bin/phpinsights` exists,
`tools/phpinsights.sh` present) — the "not installed" hook message some sessions see
is stale/incorrect, see second-brain
`feedback_phpinsights_removed_use_exact_gate_commands.md`. Scoping to a single module
triggers `ForbiddenSecurityIssues: composer.lock not found` even with
`disable-security-check: true` in `phpinsights.php` and `--disable-security-check` on
the CLI (looks for `composer.lock` under the analysed path's own ancestor, not the
app root — see second-brain `project_phpinsights_composer_lock_scoped_path.md`).
Worked around with an ephemeral config at `/tmp/phpinsights-workorder.php` that
removes only `ForbiddenSecurityIssues` from the insights list (nothing touched in the
shared repo `phpinsights.php`):

```
./tools/phpinsights.sh analyse Modules/WorkOrder --no-interaction \
  --config-path=/tmp/phpinsights-workorder.php --summary
```

Result: **Code 89.7 / Complexity 87.7 / Architecture 88.2 / Style 90.4** — all above
the repo's `min-*: 70` requirement.
