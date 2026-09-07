---
title: "PHPStan constantTypeCoverage — Production done (3 constants typed)"
type: coordination
tags: [phpstan, typecoverage, production, story-2.8, done]
created: 2026-09-07T15:25:00Z
status: done
agent: claude (sonnet 5) — this session
scope: Modules/Production
blocking: false
---

# Modules/Production — constantTypeCoverage fix (story 2.8)

Typed 3 previously-untyped class constants in
`app/Filament/Resources/ProductionOrderResource.php`:

```php
private const string ACCOUNT_FEATURE_ADMIN = 'admin.production';
private const ?string ACCOUNT_FEATURE_OPERATOR = null;
private const bool ACCOUNT_FEATURE_OPERATOR_DEFAULT = false;
```

`?string` on `ACCOUNT_FEATURE_OPERATOR` matches
`App\Support\AccountFeatures::currentPanelAllowsFeature(?string $operatorFeature = null, ...)`.
Pattern already used in `Modules/Vehicle/app/Filament/Resources/VehicleResource.php`
(`private const string ACCOUNT_FEATURE_ADMIN = ...`). Dropped the now-redundant
`@var` docblocks.

## Note on the metric

`typeCoverage.constantTypeCoverage` only shows up when PHPStan analyses the full
`./Modules/` scope (project-wide %); scanning `Modules/Production` alone reports
"No errors" even with untyped consts present — confirmed by grepping the file
directly. Don't rely on a per-module `phpstan analyse Modules/<X>` run to verify
this specific rule is clear; grep for `const ` without a native type as the real
check.

## Quality gate

- PHPStan `Modules/Production`: 0 errors before/after.
- `php -l`: OK.
- phpmd (`Modules/Production/phpmd.ruleset.xml`, was untracked, committed now):
  6 pre-existing violations, unrelated to this fix, left untouched (out of
  story-2.8 scope): `CouplingBetweenObjects` on `ProductionOrderResource.php:32`,
  `UnusedFormalParameter` x3 on `ProductionOrderPolicy.php`, x2 on
  `ProductionPhasePolicy.php`.
- phpinsights (worked around the known `composer.lock not found` bug when
  scoping to a single module — ephemeral `/tmp` config removing
  `ForbiddenSecurityIssues`, see second-brain
  `project_phpinsights_composer_lock_scoped_path.md`): Code 94.8, Complexity
  100, Architecture 88.2, Style 90.4 — all above the 70 threshold.
- Pest (`Modules/Production/tests`): 2 pre-existing failures, both
  `SQLSTATE[42S02]: Table 'workorder_data_test.production_orders' doesn't
  exist` — documented DB-migration blocker
  (`project_test_db_missing_migrations_blocks_feature_tests.md`), not caused
  by this change (no model/migration/DB touched).

Commit `46ea119`, pushed clean to `laraxot dev` (no merge needed, remote wasn't
ahead). Full detail in `Modules/Production/docs/coverage.md`.

Also found and left in place a small pre-existing unrelated WIP diff (harmless
import reordering — `use Modules\Xot\Filament\Resources\XotBaseResource;`
alphabetical move) already in the working tree before I started; included it
in the same commit since it was trivial and unrelated to any active claim.
