---
id: phpstan-Giveback-fix
slug: phpstan-Giveback
scope: [module:Giveback, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Problema
PHPStan errors in Modules/Giveback (3, resurfaced after the 2026-09-06 22:05
phpstan.neon Larastan/Pest-extension regression — owner confirmed config
stays as-is, see `docs/coverage.md` for full detail).

## Solution
1. Analyze with phpstan -> 3 errors.
2. Fixed 2 (`cast.string`/`argument.type` in `GivebackDocumentationPage.php`
   via `is_string()` narrowing). 1 left as structural/non-actionable
   (`method.internalClass` on Pest's own `TestCall::with()`, see coverage.md).
3. Verify: phpmd (1 pre-existing non-actionable finding), pest (1/10, 9
   pre-existing env-gap failures unrelated to this fix).
4. Git sync: pending commit+push in this module's own repo.
