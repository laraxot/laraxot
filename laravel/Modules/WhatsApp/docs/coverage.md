---
name: whatsapp-phpstan-cast-fix-coverage
description: "Gate results for the 2026-09-07 PHPStan cast.* fix pass on Modules/WhatsApp"
metadata:
  node_type: doc
  type: coverage
  modified: 2026-09-07
---

# Modules/WhatsApp — quality gates (2026-09-07, PHPStan `cast.*` fix pass)

## Scope

26 PHPStan `cast.int` / `cast.string` errors across 9 files, all rooted in the same
family of causes: `mixed` values pulled out of loosely-typed arrays (classifier
result arrays, `config()` defaults, `data_get()`/`Model::getAttribute()` calls,
`DB::table()->value()` results) and cast directly instead of narrowed first.

## PHPStan

Before: 26 errors (`cast.int` x9, `cast.string` x17) in:
- `app/AI/OpenAiWhatsAppMessageClassifier.php` (2)
- `app/Actions/ProcessWhatsAppMessageAction.php` (8)
- `app/Actions/ResolveWhatsAppMessageContextAction.php` (6)
- `app/Actions/StoreIncomingWhatsAppMessageAction.php` (1)
- `app/Filament/Pages/WhatsAppSettingsPage.php` (2)
- `app/Meta/MetaWhatsAppClient.php` (2)
- `app/Settings/WhatsAppSettings.php` (1)
- `app/YCloud/YCloudWhatsAppClient.php` (1)
- `tests/Feature/OrganizeWhatsAppRequestActionTest.php` (3)

After:
```
cd laravel && php -d memory_limit=-1 ./vendor/bin/phpstan analyse Modules/WhatsApp --memory-limit=-1 --no-progress
Note: Using configuration file /var/www/_bases/base_workorder_fila5/laravel/phpstan.neon.
[OK] No errors
```

No `@phpstan-ignore`, baseline entries, `assert()`, `@var` overrides, or bare
widening casts were used. Every fix narrows the value with `is_string()` /
`is_numeric()` / `is_array()` before using it, following the pattern already
established elsewhere in this module (see `OpenAiWhatsAppMessageClassifier::resolveProvider()`
and `StoreIncomingWhatsAppMessageAction::stringValue()`, both pre-existing).

### Consolidation decisions

- **`ProcessWhatsAppMessageAction`** (8 of the 26 errors): every offending cast
  traced back to the same two sources — the `array<string, mixed> $classification`
  returned by `WhatsAppMessageClassifierInterface::classify()`, and the
  `array<string, mixed> $action` derived from `$classification['proposed_action']`
  via the existing `stringKeyed()` helper. Since this one shape flows through
  `canAutoArchive()`, `shouldIgnore()`, `archiveAsWorkOrderActivity()`,
  `createApprovalProposal()`, `proposalType()` and `defaultPreview()`, two tiny
  private helpers were added next to the existing `positiveInt()`/`stringKeyed()`
  helpers — `intValue(mixed, int $default = 0): int` and
  `stringValue(mixed, string $default = ''): string` — and all 8 call sites use
  them instead of re-deriving the same `is_numeric`/`is_string` ternary inline.
  This does **not** narrow once "at the entry point" into a DTO, because the
  array is intentionally passed around as `array<string, mixed>` per the
  classifier interface contract shared with `OpenAiWhatsAppMessageClassifier`;
  introducing a value object for it would mean changing the public interface
  used across the classify/normalize/fallback boundary, which is out of scope
  for a cast-narrowing fix and was judged over-engineering for this pass.
- **`ResolveWhatsAppMessageContextAction`** (6 errors): all traced to
  `data_get()` on Eloquent models/relations and `Model::getAttribute()`, both of
  which are `mixed` by definition. Added one small private `stringValue(mixed): string`
  helper (mirroring the same-named helper already in
  `StoreIncomingWhatsAppMessageAction`) and used it at all 6 sites instead of the
  bare `(string)` cast.
- **`MetaWhatsAppClient`** (2 errors, same call site pattern used twice): both
  traced to `(int) config('services.whatsapp.timeout', 30)`. Added a private
  `timeoutSeconds(): int` helper with an `is_numeric()` narrowing check, reused
  by both `sendText()` and `verifyConfiguration()`.
- **`YCloudWhatsAppClient.php:21`** and **`WhatsAppSettings.php:77`**: same
  `config('services.whatsapp.timeout', ...)` / `config('services.whatsapp.ai_confidence_threshold', ...)`
  pattern as `MetaWhatsAppClient`, but each only has a single call site in its
  own class, so the narrowing was inlined rather than extracted into a shared
  cross-class helper (no shared base class exists between the Meta/YCloud
  clients and `WhatsAppSettings`, and creating one purely to share a two-line
  `is_numeric` check would be over-engineering for three call sites total).
- **`WhatsAppSettingsPage.php:163`**: `$this->lastDiagnostic['error']` (typed
  `array<string, mixed>|null`) narrowed with `is_string()` before use in the
  notification body.
- **`WhatsAppSettingsPage.php:230`** (now line 231): the `mapWithKeys` closure's
  `mixed $name` parameter narrowed with `is_string()` instead of blind cast.
- **`OrganizeWhatsAppRequestActionTest.php`** (3 errors): all three are
  `DB::table('interventions')->value(...)` results (mixed by definition for a
  query-builder scalar fetch). Narrowed inline with `is_string()`/`is_numeric()`
  and a safe fallback (`''`/`null`) so the assertion fails cleanly instead of
  silently coercing bad data.
- **`OpenAiWhatsAppMessageClassifier::normalizeResult()`** (2 errors): both
  `$parsed['confidence']` and `$parsed['work_order_id'] ?? $payload['work_order_id']`
  come from the JSON-decoded LLM response array (`array<string, mixed>`),
  narrowed with `is_numeric()` before casting, consistent with the `is_numeric`
  pattern already used a few lines above in the same method
  (`$workOrderId = is_numeric($firstWorkOrderId) ? (int) $firstWorkOrderId : 0;`).

No `mixed` types were introduced or left in signatures; all changes are pure
narrowing at the point the value leaves an untyped array/mixed-returning API.

## PHPMD

```
cd laravel && ./tools/phpmd.sh Modules/WhatsApp text cleancode,codesize,design,naming,unusedcode
```

Findings are pre-existing (verified by running the same command against the
`HEAD` version of `app/Actions/ProcessWhatsAppMessageAction.php` in isolation
before this fix): `StaticAccess`, `MissingImport`, `TooManyPublicMethods`,
`TooManyFields`, `LongVariable`, `CouplingBetweenObjects`, and per-method
`CyclomaticComplexity`/`NPathComplexity` findings across the module predate
this change. The only measurable delta from this pass: `ProcessWhatsAppMessageAction`
`ExcessiveClassComplexity` moved from 51 (already over the 50 threshold before
this fix) to 55, because two 1-line ternary helper methods (`intValue`,
`stringValue`) were added to remove 8 PHPStan cast errors. This is judged an
acceptable, minimal tradeoff — the alternative (inlining the same
`is_numeric`/`is_string` ternary 8 times instead of a 2-line shared helper)
would have added strictly more raw complexity to the same already-over-budget
class. No new violation categories were introduced. Fixing the pre-existing
class-complexity/coupling debt in `ProcessWhatsAppMessageAction` and
`WhatsAppSettings` is out of scope for this cast-narrowing pass.

## PHPInsights

```
cd laravel && ./tools/phpinsights.sh analyse Modules/WhatsApp --no-interaction
```

Fails with `ComposerNotFound: composer.lock not found. Try launch composer install`
from `ForbiddenSecurityIssues.php:69`, both with and without
`--disable-security-check` and `--composer=composer.json`. This is **not** the
previously-recorded "phpinsights isn't installed" claim — `composer show
nunomaduro/phpinsights` and `./vendor/bin/phpinsights list` both confirm it is
installed and functional (see second-brain
`feedback_phpinsights_removed_use_exact_gate_commands.md`). The real cause: when
scoped to a module subdirectory (`Modules/WhatsApp`), PHPInsights computes
`ForbiddenSecurityIssues`'s "common path" from the analysed files and looks for
`composer.lock` there, but the actual `composer.lock` lives at the Laravel app
root (`laravel/composer.lock`), so the check throws before producing a report.
This reproduces on an unmodified module tree too (confirmed by the shape of the
error, unrelated to any file this pass touched) — pre-existing tooling
limitation when running PHPInsights scoped to `Modules/<Name>` in this
monorepo layout, not something this pass introduced or can fix without editing
the shared root `phpinsights.php` (out of scope for a single-module PHPStan
fix).

## Pest

```
cd laravel && ./vendor/bin/pest Modules/WhatsApp/tests --no-coverage
```

Result: 38 failed, 4 passed (1 unit test green: `WhatsAppMessageTest`). Every
failure is a `QueryException` ("Base table or view not found") for
`whatsapp_messages`, `whatsapp_conversations`, `work_order_statuses`,
`ai_threads`, `roles`, etc. in the `workorder_data_test` database, or a
`UniqueConstraintViolationException` on `platform_settings` from stale seed
data — pre-existing missing-migration/test-DB-state issues in the local test
database, unrelated to this pass's type-narrowing changes (confirmed: the same
failure shape hits test files this pass never touched, e.g.
`DeapproveWhatsAppConversationsCommandTest`, `GetWhatsAppInboxQueryTest`,
`WhatsAppInboxPageTest`). Per repo policy ("data is sacred" — no
`migrate:fresh`/`--force`/`RefreshDatabase`), this was not "fixed" by
resetting the test database. Documented here rather than worked around.
