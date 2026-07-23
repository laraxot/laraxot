# Handoff — PHPStan fix Modules/Gdpr (2026-07-16)

## Finding
`laravel/build/phpstan/Gdpr.txt` reported 1 error:
```
Ignored error pattern #PHPDoc tag @mixin contains unknown class # was not
matched in reported errors.
```

## Root cause
Two distinct things:

1. **The reported error is a known partial-scope false positive**, not a code
   bug. The `ignoreErrors` pattern in the immutable `laravel/phpstan.neon` is
   authored for the whole `Modules/` tree; on a `Modules/Gdpr`-only run
   `\Eloquent` resolves via ide-helper, no `@mixin` error occurs, so PHPStan
   flags the ignore as unmatched. Documented at
   `Modules/Xot/docs/wiki/concepts/phpstan-partial-scope-false-positives.md`.
   Not fixable in the module; config is immutable.

2. **A real design smell found while investigating:**
   `Modules/Gdpr/app/Models/GdprPhpstanTraitProbe.php` — a fake Eloquent model
   whose only job was to host the `HasGdpr` trait for PHPStan. This violates the
   `no-phpstan-probe-models` rule.

## Fix applied (and why it's right)
- **Deleted `GdprPhpstanTraitProbe.php`.** `HasGdpr` is genuinely used by
  `Modules/Employee/app/Models/User.php` and already exercised by the test
  fixture `tests/Fixtures/HasGdprDummy.php`. The probe was redundant dead code
  forbidden by rule. Confirmed: after deletion `phpstan analyse Modules/Gdpr`
  produces NO `trait.unused` (the fixture keeps the trait in scope) — only the
  documented scope artifact remains.
- **Pest-ified** `tests/Unit/Traits/HasGdprTraitTest.php`: replaced
  `PHPUnit\Framework\Assert::` static calls with idiomatic `expect()`.
- **Docs**: new `Modules/Gdpr/docs/concepts/phpstan-probe-model-removal.md`;
  updated `docs/roadmap/quality-fixes-log.md`.

## Quality gates
- `phpstan analyse Modules/Gdpr --memory-limit=-1`: only the documented
  partial-scope false positive remains; no real code error. No regression from
  the probe removal.
- `phpmd Modules/Gdpr` (cleancode,codesize,controversial,design,naming,unusedcode):
  no violations (only a harmless "No node to visit for visitAnonymousClass"
  tool warning).
- `phpinsights`: unable to run — crashes in `ForbiddenSecurityIssues.php:69`
  (tooling bug, even with `--disable-security-check`). Not related to Gdpr code.
- `pest Modules/Gdpr`: BLOCKED by a pre-existing repo-wide bootstrap break —
  `SQLiteDatabaseDoesNotExistException` at `XotBaseTestCase.php:177` /
  `TestCase.php:33` setUp. Untouched tests fail identically, so it is not caused
  by this change (matches the known "Xot tenant bootstrap break" memory).

## Follow-ups (out of scope here)
- ~30 Gdpr test files still use the `PHPUnit\Framework\Assert::` facade inside
  Pest functions (established convention) — candidate for a bulk Pest refactor.
- Repo-wide pest bootstrap break (Xot) must be fixed before Gdpr tests can run.
- `Modules/Gdpr/docs/` has case-duplicate `00-index.md` / `00-INDEX.md`.

## Push
Committed and pushed to `laraxot/dev` of `module_gdpr_fila5`
(`a901295..8f661e2`, rebased on latest). No force-push.
