# Handoff — Job module: Services → QueueableAction conversion (2026-07-16)

## Scope

Module `laravel/Modules/Job`. Golden rule: `app/Services/` and `app/Support/` must not exist; domain logic lives in `app/Actions/` with the `Spatie\QueueableAction\QueueableAction` trait and a single `execute()` entrypoint.

## Starting state

- `app/Services/ScheduleService.php` was the only Service class (no `app/Support/`).
- Two Action classes already existed and were committed (`GetActiveSchedulesAction`, `ClearScheduleCacheAction`) plus their tests, and the concept doc, from a prior partial pass — but the caller updates and the Service removal had been lost in the earlier orphan-commit regression. This pass completed the conversion.

## Mapping

| Legacy `ScheduleService` method | Action (`Modules\Job\Actions\`) | Notes |
|---|---|---|
| `getActives()` (+ private `getFromCache()`) | `GetActiveSchedulesAction::execute()` | QueueableAction; cache read via `Cache::store()->rememberForever()`; constructor resolves `config('job::model')` |
| `clearCache()` | `ClearScheduleCacheAction::execute()` | QueueableAction; `Cache::store()->forget()` |

Both Actions use `QueueableAction` and expose a single public `execute()`. Placed in `app/Actions/` root to match existing module convention (`ExecuteTaskAction`, `GetTaskFrequenciesAction`, etc.).

## Callers updated (repo-wide search)

`rg "Modules\\Job\\Services\\"` across the whole monorepo found only:

- `app/Observers/ScheduleObserver::clearCache()` → now `app(ClearScheduleCacheAction::class)->execute()`.
- `app/Console/Commands/ScheduleClearCacheCommand::handle()` → now `app(ClearScheduleCacheAction::class)->execute()` (previously dead/commented-out `//WIP` code).
- `tests/Unit/Services/ScheduleServiceTest.php` → superseded by `tests/Unit/Actions/{ClearScheduleCacheActionTest,GetActiveSchedulesActionTest}.php`.

No caller of `getActives()` existed outside the Service itself; `GetActiveSchedulesAction` is available for future use (schedule listing / dashboard widgets).

## Files retired

- `app/Services/ScheduleService.php` → `app/Services/ScheduleService.php.bak`
- `tests/Unit/Services/ScheduleServiceTest.php` → `tests/Unit/Services/ScheduleServiceTest.php.bak`

Renamed (never `git rm`) per repo policy.

## Quality gates

- `vendor/bin/pint` on changed files: passed.
- `phpstan analyse Modules/Job --memory-limit=-1`: only 1 "error" — an unmatched `#PHPDoc tag @mixin contains unknown class #` ignore pattern. This is a per-module scoping artifact (the shared `phpstan.neon` ignore pattern matches errors in other modules, not Job) and is NOT introduced by this conversion (no `@mixin` added/removed). No real analysis errors in the converted code.
- `bashscripts/tools/check-no-app-support.sh`: no `app/Support/` violations in Job (violations reported are in other modules — out of scope).
- `bashscripts/tools/audit-queueable-action-trait.sh`: no Job Actions flagged for missing trait (flagged files are in User/Xot/Themes — out of scope).
- `pest Modules/Job/tests/Unit/Actions/...`: FAILS in this environment with `SQLSTATE[HY000] [1049] Unknown database 'techplanner_data_test'` at TestCase bootstrap — an infra/DB-provisioning issue, not a code fault. The Action tests are pure reflection assertions.

## Push status

Committed and pushed to `laraxot/dev` (module_job_fila5).
