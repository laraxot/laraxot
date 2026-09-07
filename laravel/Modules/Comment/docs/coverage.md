---
name: comment-coverage
description: PHPStan/PHPMD/Pest gate status for Modules/Comment — 2026-09-06/07 PHPStan L10 cleanup
metadata:
  type: quality-gate
---

# Comment — quality gate status (2026-09-07)

## PHPStan

- Before: 5 errors.
- Fixed (4):
  - `app/Actions/Resolve/ResolveCommentMentioneesAction.php:25` — `isset.offset`:
    `Safe\preg_match_all`'s stub guarantees `$matches` is a 2-tuple
    `array{list<string>, list<non-empty-string>}`, so `isset($matches[1])` was
    always true (dead check). Removed the redundant guard, use `$matches[1]`
    directly.
  - `tests/Feature/Filament/Pages/ListCommentsTest.php:24,25,35` —
    `Modules\Comment\Filament\Resources\CommentResource\Pages\ListComments::getTableBulkActions()/getTableFilters()/getTableColumns()`
    deprecated (Filament v5's `table()`-based config). The dedicated
    `CommentResource\Tables\CommentsTable` class already exists with its own
    (non-deprecated in this respect) equivalents — switched the two tests from
    `(new ListComments)->getTableActions()/...` to `(new CommentsTable)->...`,
    matching the pattern already used by a third test in the same file.
- **Not fixed (1, documented, deferred):**
  `tests/Feature/Filament/Pages/ListCommentsTest.php:23` —
  `CommentsTable::getTableActions()` is *itself* still flagged `method.deprecated`.
  Root cause: the shared trait `Modules\Xot\Filament\Traits\HasXotTable` (composed
  into `XotBaseResourceTable`, which `CommentsTable` extends) carries its own
  `@deprecated override the table() method to configure the table` docblock on its
  base `getTableActions()` — `CommentsTable` overrides it with a concrete
  implementation, but PHPStan still flags the call as deprecated. Fixing this
  properly means either renaming `CommentsTable`'s own actions method away from the
  shared trait's deprecated name, or reworking `HasXotTable`/`XotBaseResourceTable`'s
  `table()` composition — both are cross-module, high-blast-radius changes (this
  trait is used by most Filament resources in the monorepo, and at least one other
  agent is mid-refactor on the same `getTableActions()`-to-`table()` migration
  pattern on `Modules/Platform/app/Filament/Resources/AuditLogResource.php`, see
  `docs/chat/2026-09-06-platform-duplicate-assignment-standdown.md`). Left as-is
  rather than risk destabilizing shared code for one error — flagging here so
  whoever owns that `HasXotTable`/`XotBaseResourceTable` refactor picks it up.

After: **1 error remaining** (`phpstan analyse Modules/Comment`, clean cache),
down from 5.

## PHPMD

No new findings in touched files (pre-existing `ElseExpression`/`TooManyMethods`/
`ShortVariable` notes elsewhere, unrelated to this fix).

## Pest

`Modules/Comment/tests` full run: 43 failed / 1 passed — pre-existing, all 43
failures are `InvalidArgumentException: Database connection [fixcity] not
configured` thrown in `Modules/Xot/tests/XotBaseTestCase.php:158` during
`TestCase::setUp()`, before any test body runs (confirmed: happens identically for
all 4 assertions in the file I touched, `ListCommentsTest.php`, regardless of test
content). Pre-existing environment/bootstrap issue, not introduced or fixable by
this PHPStan pass — not touched (no `migrate:fresh`, no DB config changes).
