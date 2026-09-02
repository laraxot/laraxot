---
title: "no app/Support — business logic in QueueableAction"
type: concept
tags: [comment, actions, queueable-action, support, refactor]
created: 2026-07-12
updated: 2026-07-13
qmd: "Comment module PHPStan L10 no app Support dead code cleanup Reaction Notification cast Xot"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/372"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ../../../../docs/wiki/rules/queueable-action-trait-mandatory.md
  - native-comments-engine-workflow.md
  - ../../User/docs/wiki/concepts/no-app-support-queueable-actions.md
---

# no `app/Support/` — business logic in QueueableAction

## Scopo

Nel modulo Comment **non** esiste più `app/Support/`. Config tipizzata in `CommentConfigData`; comportamento di dominio in `app/Actions/`.

## Migrazione completata (2026-07-13)

Tutti i file `app/Support/*.php` archiviati in `.bak`. Zero import `Modules\Comment\Support\*` nel codice attivo.

| Area | Caller aggiornato |
|------|-------------------|
| `Comment` model | `app(*Action::class)->execute()` per reazioni, URL, approve/reject |
| Livewire / Blade | `ResolveAllowedReactionsAction`, `ResolveCommentatorModelNameFieldAction`, `CommentConfigData` |
| `comments.php` | `comment_sanitizer` → `SanitizeCommentHtmlAction::class` |


| Legacy `app/Support/` | Destinazione |
|----------------------|--------------|
| `CommentSanitizer` | `Actions/Content/SanitizeCommentHtmlAction` |
| `CommentSavingPipeline` | `Actions/Comment/RunCommentSavingPipelineAction` |
| `CommentReactionHelper` | `Actions/Reaction/*` (Find, GetCounts, React, Delete) |
| `CommentApprovingUsersResolver` | `Actions/Resolve/ResolveCommentApprovingUsersAction` |
| `CommentMentioneesResolver` | `Actions/Resolve/ResolveCommentMentioneesAction` |
| `CommentParticipatingCommentatorsResolver` | `Actions/Resolve/ResolveParticipatingCommentatorsAction` |
| `CommentModelSupport` | Azioni granulari + delega dal modello `Comment` |
| `CommentConfig*` (static) | `Datas/CommentConfigData` |
| `CommentatorProperties` | `Datas/CommentatorProperties` |
| `Gravatar` | `Actions/Gravatar/BuildGravatarUrlAction` |
| `ConfigCommenti` | Eliminato (legacy, nessun consumer) |

## Perché

- Allineamento a User/Xot: no classi statiche di dominio in `Support/`
- `CommentConfigData` = SSOT config; Actions = side-effect e query
- Test: `app(FooAction::class)->execute()`

## PHPStan L10 (2026-07-12)

`php -d memory_limit=2048M vendor/bin/phpstan analyse Modules` → **0 errori** su tutti i moduli.

Fix chiave post-migrazione:

| Errore legacy | Canon |
|---------------|-------|
| `Support\CommentConfig` | `CommentConfigData` + `ResolveConfigStringAction` / `ResolveClassStringAction` |
| `Actions\ApproveCommentAction` (root) | `Actions\Comment\ApproveCommentAction` |
| `Actions\Cast\*` (duplicato modulo) | `Modules\Xot\Actions\Cast\SafeStringCastAction` / `SafeIntCastAction` |
| `Models\CanComment` | `Models\Contracts\CanComment` |

## Ponytail — dead code rimosso (2026-07-12)

Wrapper e duplicati root **senza consumer** (modello `Comment` usa solo sottocartelle dominio):

| Eliminato | Canon |
|-----------|-------|
| `Support*Reaction*` / `GetCommentReactionCountsAction` (root) | `Actions\Reaction\*` |
| `ResolveLegacy*` (6 file) | `Resolve*Action` diretti + config |
| `ResolveCommentCommentUrlAction` | `Comment\BuildCommentUrlAction` |
| `ResolveCommentCommentableNameAction` | `Comment\GetCommentableNameAction` |
| `ShouldCommentBeAutomaticallyApprovedAction` (root) | `Comment\ShouldAutomaticallyApproveCommentAction` |
| `SendNotificationsForPendingCommentAction` (root job wrapper) | `Notification\SendNotificationsForPendingCommentAction` |
| `Cast/Safe*CastAction` (modulo) | `Xot\Actions\Cast\Safe*CastAction` |

## Collegamenti

- [comment-notifications-queueable.md](comment-notifications-queueable.md)
- [native-comments-engine-workflow.md](native-comments-engine-workflow.md)
- [queueable-action-trait-mandatory](../../../../docs/wiki/rules/queueable-action-trait-mandatory.md)
