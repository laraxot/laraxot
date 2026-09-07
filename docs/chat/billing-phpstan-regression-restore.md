---
title: "Billing PHPStan fix — regressed WIP restored to already-fixed HEAD (story 4.1.1)"
type: coordination
tags: [phpstan, billing, story-4.1.1, multi-agent, regression]
created: 2026-09-07T19:00:00Z
agent: claude (sonnet 5)
status: done
blocking: false
see_also: 2026-09-07-phpstan-billing-standdown.md
---

Note: a separate, independently-dispatched session ran on this same module around the
same time and stood down without touching anything, see
`2026-09-07-phpstan-billing-standdown.md`. No conflict — this session went further:
found and fixed the actual regression (below) and one additional real error that
surfaced from live concurrent editing, both committed and pushed.

# Billing — PHPStan fix, story 4.1.1

## Scope

Assigned task: close residual PHPStan errors on `Modules/Billing` (swarm PHPStan-zero campaign).
Story: `laravel/Modules/Billing/docs/stories/4.1.1.billing-phpstan-fixes.story.md`.

## Finding

`git status` on module start showed 17 fresh PHPStan errors in exactly the 3 files story 4.1.1
already lists (`InvoiceResource.php`, `InvoiceResource/Tables/InvoicesTable.php`,
`SupplierResource.php`). Diffing the dirty working tree against `HEAD` (commit `90af443
fix(billing): risolvi errori PHPStan residui su InvoiceResource/SupplierResource [story: 4.1.1]`)
showed the working tree was NOT new work — it was an uncommitted regression that reverted these
3 already-fixed-and-pushed files (plus the story file itself, whose `status: done` and full
"Dev Agent Record" section had been reverted back to `status: open` with the record deleted) back
to their pre-fix, broken state: wrong Filament v4 namespace (`Filament\Tables\Actions\*` instead
of `Filament\Actions\*`), `canEdit(mixed $record)`/`canView(mixed $record)` widened from the
correct `Model $record`, a bogus `Assert::isInstanceOf($resource, Model::class)` on a Resource
instance (never a Model), and `getGloballySearchableAttributes()` losing its `array_combine()`
string-keying.

Likely cause: another concurrent session (structural Schemas/Tables extraction, story 4.1) started
from a stale pre-90af443 checkout and, on saving its own unrelated edits, clobbered these 3 files
back to that older content without re-reading first. Matches the known repo pattern "Write tool
won't stop a concurrent-swarm race" (second-brain).

## Action taken

Restored the 3 code files and the story file to their exact `HEAD` content (manual rewrite, not
`git checkout`, per the repo's no-checkout git constraint). Result: 0 diff vs `HEAD` on all 4
files — nothing new to commit, `Modules/Billing` was already fixed and pushed by a prior session.
Verified fresh (`clear-result-cache` first): `phpstan analyse Modules/Billing` → **0 errors**.
`pint --test` on the 3 files → passed. `php -l` → clean.

## Not touched

Other uncommitted WIP in the Billing working tree (`app/Datas/*`, `*/Pages/View*.php`,
`SupplierInvoiceResource.php`, new `Schemas/`/`Tables/` dirs, `docs/README.md`, `docs/module.md`,
stories 4.1/4.5/4.6/4.7/4.11/4.14/5.71) belongs to other stories/agents (structural canonicalization,
docs consolidation) — left as-is, not part of this story's scope, not blocking PHPStan (0 errors
confirmed with it present).

## Result

No commit was needed/made for the 3 code files + story file (working tree matched already-pushed
`HEAD` after restore). Committed and pushed a `docs/coverage.md` re-verification note instead
(`735002a`, `laraxot/dev` on `module_billing_fila5`). Pest: 51 passed / 29 failed, identical to
the 2026-09-06 baseline — failures are a pre-existing test-DB migration gap (missing
`customers`/`suppliers`/`permissions` tables), not module code; coverage unchanged (could not be
raised without an owner-run test-DB migration, out of this story's scope).

## Addendum — live race observed mid-session on InvoicesTable.php

While writing this note, `InvoiceResource/Tables/InvoicesTable.php` changed on disk twice more —
another agent actively editing it concurrently. Intermediate state had `EditAction`/`DeleteBulkAction`
etc. imported from `Filament\Tables\Actions\*` (confirmed via `class_exists()` not to exist in this
Filament v4 install; real classes live in `Filament\Actions\*`), which would have been a runtime
fatal, mixed with a legitimate new `getTableFilters()` addition. `bashscripts/lock/check.sh` reported
FREE throughout — no lock held on the file. By the time this was checked again the other agent had
already self-corrected the imports. Left the file as-is (not mine to commit, not blocking PHPStan);
see second-brain `pattern_uncommitted_wip_can_revert_already_fixed_pushed_files.md` and
`project_shared_phpstan_tmpdir_result_cache_unreliable_under_swarm.md` for the generalized lessons
(the latter: `/tmp/phpstan` result cache is shared/racy under this swarm's concurrency, a clean
phpstan run isn't sufficient proof on a file you just watched another agent edit — cross-check with
`php -l` + `class_exists()`).

## Final state

The concurrent agent's `getTableFilters()` addition on `InvoicesTable.php` settled with a real
`method.childReturnType` PHPStan error (copy-pasted `Action|ActionGroup` docblock instead of the
parent's `BaseFilter` contract). After 3+ minutes of file stability (no further concurrent edits),
fixed the docblock, removed the now-unused `Webmozart\Assert\Assert` import, ran `pint` (auto-fixed
import order + FQCN shortening), re-verified `phpstan analyse Modules/Billing` → 0 errors,
`pint --test` → passed, `php -l` → clean. Committed and pushed:
`4efe093 fix(billing): correct getTableFilters() covariant return type on InvoicesTable`
(`laraxot/dev` on `module_billing_fila5`).

**Module status at handoff**: `Modules/Billing` — 0 PHPStan errors (verified independently of the
shared/racy result cache). Other uncommitted structural WIP (Datas/, Pages/, SupplierInvoiceResource.php,
docs/README.md, docs/module.md, several story files) still present in the working tree, untouched,
not blocking PHPStan, left for its owning agent/story.
