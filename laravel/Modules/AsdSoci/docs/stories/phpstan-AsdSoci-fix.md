---
id: phpstan-AsdSoci-fix
slug: phpstan-AsdSoci
scope: [module:AsdSoci, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Problema
PHPStan errors in Modules/AsdSoci (2, resurfaced after the 2026-09-06 22:05
phpstan.neon Larastan/Pest-extension regression — see second-brain
`project_phpstan_neon_larastan_disabled_2026_09_07.md`; owner confirmed
config stays as-is, fix real code instead).

## Solution
1. Analyze with phpstan -> 2 errors in `AsdSociDocumentationPage.php`
   (`cast.string`, `argument.type` on `request()->query()` mixed narrowing).
2. Fix: `is_string()` guard before use instead of a direct `(string)` cast.
3. Verify: phpstan 0 errors; phpmd (Xot ruleset copied in, 1 pre-existing
   non-actionable finding); pest 1/2 (1 pre-existing env gap, DB migrations
   missing on `mariadb` test connection, not code). Details in
   `docs/coverage.md`.
4. Git sync: pending commit+push in this module's own repo.
