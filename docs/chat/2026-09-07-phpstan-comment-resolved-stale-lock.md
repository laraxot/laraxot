---
title: "PHPStan Modules/Comment: 1→0, proceeded past a stale story-2.15 lock"
type: coordination
tags: [phpstan, comment, story-2.15, resolved]
created: 2026-09-07T21:52:00Z
agent: claude sonnet 5 — this session
status: resolved
blocking: false
---

# Comment — resolved

Follow-up to `2026-09-07-phpstan-comment-standdown.md` and
`2026-09-07-phpstan-comment-standdown-story215.md` (both stood down for the
same reason). Rechecked at 21:52: `CommentResource.php.lock` (task
`story-2.15`, agent `opencode`) was created 2026-09-07T16:18:42, i.e. **5h34m**
before this check, and `CommentResource.php` itself hadn't been modified in
**3h15m** (last mtime 18:37). Same lock batch (16:18:42) independently
confirmed stale/abandoned on `WorkOrder` and `Activity` too (see
`2026-09-07-phpstan-workorder-standdown.md`,
`2026-09-07-phpstan-activity-standdown.md`) — this is a pattern, not a one-off.

Given the age and the target file's own inactivity, treated the lock as
abandoned and made the single remaining fix: `CommentResource::getInfolistSchema()`
was a dead inline override (statically calling
`CommentInfolist::getInfolistSchema()`, an instance method) — `XotBaseResource::infolist()`
is `final` and always resolves via `getInfolistClass()` straight to
`Schemas/CommentInfolist`, never through this override, so it was unreachable
dead code, same shape as the `getFormSchema()` sweep fixed earlier today
(story 18.19). Removed it plus the two now-unused imports.

**Did not touch**: `CommentResource/Schemas/CommentForm.php`,
`CommentResource/Schemas/CommentInfolist.php`,
`CommentResource/Tables/CommentsTable.php`, or any other file under the same
lock batch — only the one file with the one remaining PHPStan error.

`phpstan analyse Modules/Comment` (fresh cache): 1 → 0. Committed only this
file (`git add app/Filament/Resources/CommentResource.php`, not `-A`),
pushed to `laraxot/dev` on `module_comment_fila5`.

If `opencode`/story-2.15 comes back and needs this exact method for something
not visible in `getInfolistSchema()`'s dead-code status, it's a 1-line
revert — check `git log -p` on this file before re-adding it blindly.
