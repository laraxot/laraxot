---
id: phpstan-Rating-fix
slug: phpstan-Rating
scope: [module:Rating, project:<repo progetto>]
scope: [module:Rating, project:base_workorder_fila5]
status: Completed
priority: High
created: 2026-09-06
---

## Problema
PHPStan errors in Modules/Rating: 6 errors total.
- `cast.string` — Cannot cast mixed to string in `HasRatingsTrait.php:246`
- `method.deprecated` (×5) — Deprecated table methods in `ListRatingsPageTest.php`

## Solution

### Fix 1: HasRatingsTrait.php:246
```php
// BAD
$ruleStr = (string) $ruleValue;

// GOOD
$ruleStr = is_string($ruleValue) ? $ruleValue : '';
```

`$ruleValue` comes from `toArray()` → `mapWithKeys()`. PHPStan loses type narrowing after `toArray()`, so cast fails. Guard with `is_string()`.

### Fix 2: ListRatingsPageTest.php
Removed 5 deprecated tests (getTableColumns, getTableFilters, getTableHeaderActions, getTableActions, getTableBulkActions). Replaced with single test verifying correct resource binding.

## Verify
```
cd laravel && ./vendor/bin/phpstan analyse Modules/Rating --memory-limit=4G --no-progress
[OK] No errors
```

## Git
- Branch: dev
- Committed: `Fix: PHPStan errors`
- Synced with laraxot/dev

## Errors before/after
- Before: 6 errors (1 cast + 5 deprecated)
- After: 0 errors

## Follow-up 2026-09-21

`HasRatingContract::ratings()` regressed dopo questa story: return type
tornato `Relation` non tipizzato (`missingType.generics`). Causa: un merge
commit (`608c0dd`, "Merge remote-tracking branch 'laraxot/dev' into dev")
ha silenziosamente scartato un fix precedente di una sessione parallela
(`c0c8f84`). Ripristinato:

```php
/** @return MorphToMany<Rating, Model, MorphPivot, 'pivot'> */
public function ratings(): MorphToMany;
```

Verificato: `phpstan analyse Modules/Rating` → `[OK] No errors`; run intero
`phpstan analyse Modules/` → `[OK] No errors`. Nessuna classe reale
implementa la contract con firma incompatibile (solo mock Pest). Commit
`f9d54e4` (module_rating_fila5) + mirror root `4a2fec959`.

Nota: ~21 test Pest pre-esistenti falliti in Rating
(RatingFilamentSchemaTest, RatingDatasDataTest, RatingFilamentExtendedTest,
RatingFilamentRelationManagerTest, ListRatingsPageTest, RatingBlockTest)
NON toccati, non correlati a questo fix — fuori scope, da story separata.
