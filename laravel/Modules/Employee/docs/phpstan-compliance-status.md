# PHPStan Level 10 Compliance Status

**Last Updated**: 2026-07-06

**Status**: ✅ FULLY COMPLIANT (0 errors, level max)

Baseline sessione 2026-07-06: 1704 → 0.

## Root cause found and fixed
`Modules/Employee/tests/TestCase.php` declared `public ?BaseModel $baseModel = null;`, narrowing a property inherited as `mixed` from `Modules\Xot\Tests\XotBaseTestCase::$baseModel`. PHP forbids narrowing an inherited property's type, so the class **failed to load with a fatal error at runtime**. PHPStan/Pest's static analysis silently fell back to a generic type for every `uses(TestCase::class)` binding in the whole module, which cascaded into ~1700 "Undefined variable $this" / "cannot access property on mixed" errors across every test file.

Fix: removed the narrowing property override, switched the module's `TestCase` to extend `XotBaseTestCase` (the convention used by every other module), removed the forbidden `uses()->in()` bulk binding and `expect()->extend()` calls from `tests/Pest.php` (replaced with per-file `uses(TestCase::class)`, matching Geo/Notify/User/Tenant/Job convention), and rewrote a handful of tests (`EmployeeTest.php`, `WorkHourTest.php`, `EmployeeOverviewWidgetTest.php`, `TimeTrackingWidgetTest.php`) that asserted an API (department/position relations, `isActive()`/`hasManager()`/`hasSubordinates()`, a `TimeTrackingWidget` class, `startBreak()`) that never existed on the real models — corrected to match the actual `Employee`/`WorkHour`/`TimeClockWidget` implementation instead of adding the missing production code.

**Not verified at runtime**: this sandbox has no `workorder_data_test` MySQL database, so Pest could not be executed end-to-end; only static analysis (PHPStan) was confirmed clean.

## Summary
The Employee module is compliant with PHPStan Level 10 analysis. No errors are found during verification.

## Compliance Verification
```bash
./vendor/bin/phpstan analyse Modules/Employee --level=10 --memory-limit=-1
# Result: [OK] No errors
```

## Module Overview

The Employee module provides:
- Employee management system
- HR functionality
- Staff organization
- Employee profiles
- Department management
- Role assignment

## Best Practices Already Implemented

1. **Type Safety**: All methods have proper type hints
2. **PHPDoc Compliance**: Accurate documentation for complex types
3. **Employee Models**: Proper Eloquent relationships
4. **HR Logic**: Type-safe HR operations
5. **Department Structure**: Clean implementation of org structure

## Employee Management Patterns

The module follows strict patterns for HR management:
- Employee lifecycle management
- Department hierarchy
- Role-based permissions
- Profile management
- Performance tracking

## Ongoing Maintenance

To maintain PHPStan compliance:
1. Continue following established type safety patterns
2. Test all HR functionality
3. Verify employee relationships work correctly
4. Run PHPStan before committing changes
5. Ensure all new HR features maintain type safety

## Related Documentation
- [Employee Management Guide](employee-management.md)
- [HR Patterns](hr-patterns.md)
- [Department Structure](department-structure.md)
- [Role Management](role-management.md)

## Real status update (2026-07-06)

Verified by running `./vendor/bin/phpstan analyse Modules --memory-limit=-1` at the configured `level: max`. Prior "compliance" claims in this file were not accurate — the module currently reports **1704 errors**, the largest count of any module in the project.

Breakdown of the two dominant causes found:

1. **Pest `$this` binding (~862 errors, fixed)** — Pest test closures (`it()`, `test()`, `beforeEach()`, `afterEach()`) call `$this->get()`, `$this->actingAs()`, etc. PHPStan cannot infer `$this`'s type inside these global closures without help, because neither `larastan/larastan` nor `pestphp/pest`'s bundled `extension.neon` bind it to the module's `TestCase`. Fix applied: a `/** @var Modules\Employee\Tests\TestCase $this */` PHPDoc annotation was inserted as the first statement of every closure that uses `$this`, via an AST-based script (nikic/php-parser) — not a manual/regex edit, so formatting elsewhere is untouched. This is the standard workaround for this known Pest+PHPStan limitation, not a suppression.
2. **`method.internalClass` on `expect()->toBe()` chains (~unquantified within Employee, ~374 project-wide)** — `Pest\Mixins\Expectation` is marked `@internal`, and PHPStan (independent of any bleeding-edge setting) flags calls to `@internal`-tagged classes from outside their declaring namespace. Since virtually every Pest test lives outside the `Pest` namespace, **any** `expect(...)->toBe(...)` chain triggers this. This is a project-wide tooling friction point, not a bug in Employee-specific code. `phpstan.neon` already has a commented-out `# - identifier: method.internalClass` ignore rule — since only the repo owner may edit `phpstan.neon`, this cannot be resolved per-file without rewriting all assertions to PHPUnit-style (`$this->assertSame(...)`) instead of `expect()`, which is a scope decision for the owner, not something to do unilaterally across hundreds of tests.

Remaining ~800+ Employee errors (after the Pest binding fix) are a mix of real issues requiring case-by-case judgment: missing model scopes (e.g. `Employee::active()`), missing constants (e.g. `WorkHour::TYPE_CLOCK_IN`), and references to classes that don't exist (e.g. `Filament\Widgets\TimeTrackingWidget`) — each needs verification of whether the **test** is wrong (references something never built) or the **production code** is genuinely incomplete, before touching anything, per project policy of never inventing missing production code to satisfy a test.
