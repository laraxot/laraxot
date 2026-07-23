# Handoff — Comment module: Services/Support → QueueableAction

Date: 2026-07-16
Module: `laravel/Modules/Comment`
Issue: https://github.com/laraxot/base_techplanner_fila5/issues/18

## Summary

`app/Services/` never existed. `app/Support/` (15 classes) has been fully removed
(archived `.bak`). Domain logic moved to `app/Actions/{Context}/` (QueueableAction trait,
single `execute()`); config accessors folded into the existing SSOT `Datas/CommentConfigData`;
the `CommentatorProperties` value object moved to `Datas/`.

## Class → destination mapping

| Old `app/Support/` | New location |
|--------------------|--------------|
| CommentApprovingUsersResolver | Actions/Comment/ResolveApprovingUsersAction |
| CommentMentioneesResolver | Actions/Comment/ResolveMentioneesAction |
| CommentParticipatingCommentatorsResolver | Actions/Comment/ResolveParticipatingCommentatorsAction |
| CommentSavingPipeline | Actions/Comment/PrepareCommentForSavingAction |
| CommentSanitizer | Actions/Comment/SanitizeCommentTextAction |
| CommentReactionHelper (4 methods) | Actions/Reaction/{FindReaction,GetReactionCounts,ReactToComment,DeleteReaction}Action |
| Gravatar | Actions/Gravatar/GetGravatarUrlAction |
| CommentatorProperties | Datas/CommentatorProperties |
| CommentModelSupport (facade) | inlined into Model\Comment + Actions |
| CommentConfig, CommentConfigContent, CommentConfigUi, CommentConfigNotifications, CommentConfigActions, ConfigCommenti | Datas/CommentConfigData (accessors added) |

Legacy flat duplicate Actions (app/Actions/{ProcessComment,ApproveComment,RejectComment,ResolveMentionsAutocomplete}Action.php)
were only referenced by the removed CommentConfigActions facade; archived `.bak`. The canonical
versions live in Actions/Comment/* and Actions/Mention/* (registered in config/comments.php).

## Callers updated

- app/Models/Comment.php (reactions, resolvers, approve/reject, urls, saving hook, CommentatorProperties import)
- app/Models/Contracts/CanComment.php (CommentatorProperties import → Datas)
- app/Http/Livewire/CommentComponent.php (allowedReactions)
- app/Http/Livewire/CommentsComponent.php (showAvatars, pagination)
- app/Datas/CommentConfigData.php (sanitizer type → SanitizeCommentTextAction; +accessor methods)
- app/Actions/Comment/ProcessCommentAction.php (sanitizer ->execute())
- config/comments.php (comment_sanitizer → SanitizeCommentTextAction)
- resources/views/livewire/comment.blade.php, mention-search.blade.php
- tests/Unit/CommentSanitizerTest.php, tests/Support/ParityCommentatorStub.php (import fix only)

## Quality gates

- PHPStan (`analyse Modules/Comment`): 0 code errors. Only 2 "unmatched ignored error pattern"
  artifacts from the global immutable phpstan.neon (patterns match other modules, not visible
  in module-scoped run). Not introduced by this change.
- check-no-app-support.sh: no Comment violations (remaining 9 are other modules).
- audit-queueable-action-trait.sh: no Comment violations — every Comment Action declares the trait.
- Pint: applied cosmetic fixes to new files.
- Pest Modules/Comment: see commit note.

## Notes

Old files kept as `*.php.bak` (no `git rm`) pending end-to-end validation.
