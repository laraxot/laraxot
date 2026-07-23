# Handoff — PHPStan fix Modules/Lang (2026-07-16)

## TL;DR
- The 24 PHPStan errors (`staticMethod.notFound` on `assertTrue()`) came from fake
  coverage-boost scaffolding under `tests/AuditCoverage`.
- A prior agent only **renamed** the folder to `tests/AuditCoverage.bak/` and gitignored it —
  that did NOT stop PHPStan (it scans every `*.php`, ignoring both `.bak` suffix and `.gitignore`).
- Fix: **deleted** `tests/AuditCoverage.bak/` (23 files, untracked, zero value).
- Result: `phpstan analyse Modules/Lang` → 0 real code errors.

## AuditCoverage status
- Confirmed: neither `tests/AuditCoverage/` nor `tests/AuditCoverage.bak/` exists after cleanup.
- The generator `bashscripts/tools/claude-audit-module-static-boost.sh` is already fixed
  (writes to `build/audit-coverage/${MODULE}/`, namespace `Build\AuditCoverage\...`) — **no
  regression**, it no longer writes into the module tree. The `.bak` folder was pre-fix debris.

## PHPStan
```
cd laravel && ./vendor/bin/phpstan analyse Modules/Lang --memory-limit=-1
→ [ERROR] Found 1 error
```
The single remaining "error" is not a Lang code issue: it is
`Ignored error pattern #PHPDoc tag @mixin contains unknown class # was not matched`, emitted by
the immutable global `laravel/phpstan.neon` when the analysis is scoped to a single module (the
`@mixin` ignore pattern legitimately matches nothing inside Lang). `phpstan.neon` is immutable
per project rules — left untouched. Zero real findings in Lang code.

## Quality gates
- **PHPMD** (`cleancode,codesize,controversial,design,naming,unusedcode`): only advisory/style
  findings — Laraxot conventional snake_case props (`$module_ns`, `$module_dir`,
  `$fallback_locale`), facade `StaticAccess`, factory unused `$_attributes` params, a couple of
  complexity/NPath warnings in `LangServiceProvider::registerFilamentLabel()` and
  `ThemeComposer::languages()`, and long loose scripts under `docs/*.php`. No real bugs; not
  churned to avoid touching established convention code.
- **phpinsights**: tooling failure in module context (`composer.lock not found` /
  `ForbiddenSecurityIssues`) — environmental, not a Lang code issue.
- **Pest** (`./vendor/bin/pest Modules/Lang`): 144 tests, all fail with the SAME infra error:
  `SQLSTATE[HY000] [1049] Unknown database 'techplanner_data_test'`. Created the test DBs, but
  `php artisan migrate --env=testing` then fails on a pre-existing cross-connection FK ordering
  problem (`Failed to open the referenced table 'teams'` — `user` connection referencing a table
  on another connection). This is the known repo-wide bootstrap/migration blocker (see
  `docs/chat/tenantservice-missing-blocks-phpstan.md` and MEMORY "Xot/Tenant bootstrap break"),
  NOT introduced by Lang. Lang tests cannot be exercised until the repo-wide migration/tenant
  bootstrap is repaired.

## Tests
- All Lang tests are already Pest (no class-based PHPUnit tests remain under `tests/Unit` or
  `tests/Feature`). No conversion needed.

## Docs / rules updated
- `laravel/Modules/Lang/docs/no-ai-tool-scaffold-dirs.md` — added section on the
  `AuditCoverage.bak` rename trap.
- `docs/wiki/rules/no-phpstan-probe-models.md` (canonical on-demand rule) — added a
  "Coverage-boost scaffolds" section + enforcement step covering `tests/AuditCoverage` and its
  `.bak` variants, and the fact that neither `.bak` rename nor `.gitignore` silences PHPStan.

## Follow-ups (out of scope here)
- Repo-wide: fix the cross-connection migration ordering (`teams`/`team_permissions`) so the
  `techplanner_*_test` databases can migrate and the whole suite (incl. Lang) can run.
