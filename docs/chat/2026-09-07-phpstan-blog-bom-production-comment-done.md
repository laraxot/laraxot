---
title: "PHPStan Blog/Bom/Production/Comment — done (19 -> 1 errors)"
type: coordination
tags: [phpstan, blog, bom, production, comment, done]
created: 2026-09-07T01:50:00Z
status: done
agent: claude (sonnet 5) — this session
blocking: false
---

# Small unclaimed modules cleaned up

Fixed directly (no subagent needed, all mechanical Filament v4->v5 deprecations):

- **Blog**: 8 -> 0 (`BaseFileUpload::enableOpen()/enableDownload()` -> `openable()/downloadable()`)
- **Bom**: 3 -> 0 (`Table::actions()/bulkActions()` -> `recordActions()/toolbarActions()`)
- **Production**: 3 -> 0 (same pattern)
- **Comment**: 5 -> 1 (dead `isset()` removed + 3 deprecated test calls moved to the
  dedicated `CommentsTable` class; 1 left, see below)

All committed + pushed to `laraxot/dev` in each module's own repo.

## New finding: `HasXotTable`/`XotBaseResourceTable` deprecation leak

`Modules/Xot/app/Filament/Traits/HasXotTable.php`'s base `getTableActions()` carries
its own `@deprecated override the table() method to configure the table` docblock.
Any concrete `XotBaseResourceTable` subclass that overrides `getTableActions()` (e.g.
`Modules\Comment\...\Tables\CommentsTable`) still gets flagged by PHPStan as calling a
deprecated method, even though the subclass's own override isn't itself deprecated.
This is likely present in **every** module using the `{Resource}\Tables\{X}Table`
pattern with its own `getTableActions()`/`getTableBulkActions()`/`getTableFilters()`
override — worth a grep across the tree
(`grep -rl "extends XotBaseResourceTable" laravel/Modules`) once someone's doing a
dedicated pass on this trait. Left unfixed in Comment (1 remaining error) rather than
touch the shared trait — flagging since at least one other agent is already
mid-refactor on the same underlying `getTableActions()`-to-`table()` migration on
`Modules/Platform/app/Filament/Resources/AuditLogResource.php`
(`docs/chat/2026-09-06-platform-duplicate-assignment-standdown.md`) and might own this.

Full detail per module in each `Modules/<Mod>/docs/coverage.md` and updated
`docs/stories/phpstan-<Mod>-fix.md`.
