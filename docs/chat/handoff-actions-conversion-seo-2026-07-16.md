# Handoff — Seo Services→Actions/Adapters conversion (2026-07-16)

## Scope
Module `laravel/Modules/Seo`. Goal: eliminate `app/Services/` per the monorepo golden
rule (no Services/Support; domain logic → `app/Actions/` QueueableAction).

## Findings
- `app/Services/` contained exactly one class: `MetatagService`.
- `app/Support/` did not exist.
- Repo-wide callers of `Modules\Seo\Services\` (rg across laravel/): only the module's
  own Provider, Facade, and tests. No consumers in other modules / Themes / laravel/app.
- `MetatagService` is a **stateful singleton facade coordinator** (accumulates
  `MetatagData` via successive setters, read once at render), exposed through the
  `Metatag` facade — NOT stateless domain logic.

## Decision
Per the Xot canonical rule, a Facade coordinator belongs in `app/Adapters/`, not
`app/Actions/` (a stateless `execute()` would break the accumulating facade semantics).

## Mapping
| Before | After |
|--------|-------|
| `Modules\Seo\Services\MetatagService` (`app/Services/MetatagService.php`) | `Modules\Seo\Adapters\MetatagManager` (`app/Adapters/MetatagManager.php`) |

Note: `app/Actions/GenerateSocialShareLinksAction.php` was already a correct
QueueableAction (stateless `execute()`), left unchanged.

## Callers updated
- `app/Facades/Metatag.php` — accessor + @see → `MetatagManager`.
- `app/Providers/SeoServiceProvider.php` — singleton binding + `provides()`.
- `tests/Feature/MetatagServiceTest.php`
- `tests/Unit/Services/MetatagServiceExtendedTest.php`
- `tests/Unit/Facades/MetatagFacadeTest.php`
- `tests/Unit/Providers/SeoProvidersTest.php`
- Old file → `app/Services/MetatagService.php.bak` (not deleted).

## Quality gates
- `rg "Modules\\Seo\\Services\\"` repo-wide (excl. .bak): **0** references.
- `check-no-app-support.sh`: no Seo violations (only pre-existing Activity/AI/Comment).
- `audit-queueable-action-trait.sh`: no Seo MISSING_TRAIT.
- PHPStan `Modules/Seo`: only 2 pre-existing "unmatched ignored error pattern" entries,
  confirmed identical on baseline (stash test) — NOT introduced by this change.
- Pest `Modules/Seo`: 6 passed / 20 failed, all failures are environmental
  (`Unknown database 'techplanner_data_test'`) — no class-resolution failures.
- Pint: applied to changed files.
- phpmd: not installed (skipped). phpinsights: ran clean for Seo.

## Docs
- `docs/wiki/concepts/seo-services-support-to-actions.md` (new).
- This handoff.
