---
title: "Code Coverage: Job"
module: "Job"
type: concept
tags: [coverage]
created: 2026-07-14
updated: 2026-09-06
qmd: "coverage"
related:
  - "./phpstan-fixes-archive-2.md"
  - "./stories/01.Job-phpstan-fix.story.md"
---

## 2026-09-06 — PHPStan zero-errors pass (this session)

Scope: `app/Filament/Columns/ScheduleArguments.php`,
`app/Filament/Resources/JobManagerResource/Widgets/JobStatsOverview.php`,
`app/Filament/Resources/ScheduleResource.php`,
`app/Filament/Resources/ScheduleResource/Schemas/ScheduleForm.php`,
`app/Models/JobBatch.php`, `app/Models/Task.php`. Full detail:
`docs/stories/01.Job-phpstan-fix.story.md`.

- Targeted test files that directly cover the changed code:
  `tests/Unit/Filament/Columns/ScheduleArgumentsTest.php` — **11/11 passed** (ran
  clean, confirms `getTags()`/`formatArrayTags()`/`withValue()` behavior unchanged
  after switching to `SafeStringCastAction::cast()`).
- `tests/Feature/JobBatchBusinessLogicTest.php`, `tests/Feature/TaskBusinessLogicTest.php`,
  `tests/Unit/ScheduleFormCoverage100Test.php`, `tests/Unit/JobScheduleFormCoverageTest.php`,
  `tests/Unit/Models/JobModelsCoverageTest.php`: **could not run** — Pest bootstraps
  the whole monorepo (all Filament panels across all `Modules/*`), and
  `Modules/Platform/app/Filament/Resources/AuditLogResource.php` was mid-refactor by
  another agent for the entire session (locked via `bashscripts/lock`, `LOCKED at
  2026-09-06T22:04:52+02:00`, 23+ minutes), causing a fatal
  `Could not check compatibility between
  Modules\Platform\Filament\Resources\AuditLogResource::table(...)` on every
  full-app bootstrap attempt (reproduced twice). Not caused by, or fixable from,
  this Job-module story — flagging as an environment blocker for whoever owns
  Platform's concurrent refactor. Full-tree run for `Modules/Job/tests` (background
  PID 2140515) also never completed in this session due to the same shared-bootstrap
  contention plus general system load from the many concurrent agents active on this
  repo (dozens of other `pest`/`phpstan` processes observed running in parallel via
  `ps aux` throughout this session).
- PHPStan (`clear-result-cache` + `analyse Modules/Job`, module-scoped, not affected
  by the Platform bootstrap issue): **15 -> 0 errors**, reverified 3 times.
- No behavior change intended or expected in any of the 5 untested files: JobBatch's
  fix is a pure refactor from `$this->attributes[...]` to the model's own typed
  accessor (documented in `docs/typed-model-properties-over-raw-attributes.md` as
  runtime-equivalent); `ScheduleResource`/`ScheduleForm`'s fix
  (`->toCollection()->where()->first()`) is exactly Spatie's own documented
  replacement for the deprecated `DataCollection::where()/first()`; `Task::compileParameters()`
  and `JobStatsOverview` now delegate to the same `SafeStringCastAction`/
  `SafeEloquentCastAction` helpers already used elsewhere in this module for
  identical semantics.

# Code Coverage: Job

**Lines Coverage:** N/A (Failed to parse)
**Test Exit Code:** 2

## Output

```text
▕             }
    1119▕         }
    1120▕ 
## Status

**2026-09-06**: philosophy.md created. PHPStan analyzed (OK). Pest suite (TBD). Coverage target: +5% per module.

    1121▕         try {
  ➜ 1122▕             $reflector = new ReflectionClass($concrete);
    1123▕         } catch (ReflectionException $e) {
    1124▕             throw new BindingResolutionException("Target class [$concrete] does not exist.", 0, $e);
    1125▕         }
    1126▕

      [2m+7 vendor frames [22m
  8   Modules/Job/tests/Feature/TaskFrequenciesIntegrationTest.php:204

  ──────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Modules\Job\tests\Feature\TaskFrequenciesIntegr…  BindingResolutionException   
  Target class [config] does not exist.

  at vendor/laravel/framework/src/Illuminate/Container/Container.php:1122
    1118▕             }
    1119▕         }
    1120▕ 
    1121▕         try {
  ➜ 1122▕             $reflector = new ReflectionClass($concrete);
    1123▕         } catch (ReflectionException $e) {
    1124▕             throw new BindingResolutionException("Target class [$concrete] does not exist.", 0, $e);
    1125▕         }
    1126▕

      [2m+7 vendor frames [22m
  8   Modules/Job/tests/Feature/TaskFrequenciesIntegrationTest.php:211

  ──────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Modules\Job\tests\Feature\TaskFrequenciesIntegr…  BindingResolutionException   
  Target class [config] does not exist.

  at vendor/laravel/framework/src/Illuminate/Container/Container.php:1122
    1118▕             }
    1119▕         }
    1120▕ 
    1121▕         try {
  ➜ 1122▕             $reflector = new ReflectionClass($concrete);
    1123▕         } catch (ReflectionException $e) {
    1124▕             throw new BindingResolutionException("Target class [$concrete] does not exist.", 0, $e);
    1125▕         }
    1126▕

      [2m+7 vendor frames [22m
  8   Modules/Job/tests/Feature/TaskFrequenciesIntegrationTest.php:229

  ──────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Modules\Job\tests\Unit\Models\BaseModelTest > b…  BindingResolutionException   
  Unresolvable dependency resolving [Parameter #0 [ <required> string $storedEventRepository ]] in class Spatie\EventSourcing\StoredEvents\EventSubscriber

  at vendor/laravel/framework/src/Illuminate/Container/Container.php:1429
    1425▕     protected function unresolvablePrimitive(ReflectionParameter $parameter)
    1426▕     {
    1427▕         $message = "Unresolvable dependency resolving [$parameter] in class {$parameter->getDeclaringClass()->getName()}";
    1428▕ 
  ➜ 1429▕         throw new BindingResolutionException($message);
    1430▕     }
    1431▕ 
    1432▕     /**
    1433▕      * Register a new before resolving callback for all types.

      [2m+15 vendor frames [22m
  16  Modules/Job/app/Models/BaseModel.php:72
  17  Modules/Job/tests/Unit/Models/BaseModelTest.php:11


  Tests:    26 failed, 11 warnings, 38 skipped, 20 passed (47 assertions)
  Duration: 9.72s


```
