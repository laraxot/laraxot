---
title: "Comment — config/comments.php SSOT, no CommentConfig"
type: concept
module: Comment
tags: [comment, config, ssot, dry, kiss, laravel-config]
created: 2026-06-10
updated: 2026-06-10
qmd: "comment config ssot no CommentConfig app container config comments.php"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/322"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/323"
related:
  - ./actions-domain-subfolders.md
  - ../../../../docs/wiki/rules/no-comment-config-ssot.md
  - ../../../../docs/stories/STORY-292-remove-comment-config.md
---

# config/comments.php — unica fonte, zero facade statica

## Perché (filosofia)

`CommentConfig` duplicava `config('comments.*')` con getter statici: stesso dato in due posti, più token per gli agenti, più rischio drift.

**Regola:** valori → `config('comments.*')`; classi risolvibili → `app(Fqcn::class)`; notifiche → `app(config('comments.notifications.notifications.*'), $params)`.

## Pattern

| Bisogno | Prima (vietato) | Dopo |
|---|---|---|
| Flag mentions | `CommentConfig::mentionsEnabled()` | `config('comments.mentions.enabled')` |
| Policy FQCN | `CommentConfig::commentPolicyClass()` | `config('comments.policies.comment')` |
| Notifica approved | `CommentConfig::approvedCommentNotification(...)` | `app(ApprovedCommentNotification::class, [...])` |
| Reazioni | `CommentConfig::allowedReactions()` | `config('comments.allowed_reactions', [])` |
| Action | `CommentConfig::rejectCommentAction()` | `app(RejectCommentAction::class)->execute()` |

## Cosa resta in Support/

- `CommentSanitizer`, `Gravatar` — utility, non SSOT config.
- `ConfigCommenti` e `CommentConfig` — **rimossi** (STORY-292).

## Override tenant

Binding container su FQCN in `config/comments.php` (`actions.*`, `notifications.*`, `policies.*`) senza toccare il codice call-site.

## Collegamenti

- [actions-domain-subfolders](./actions-domain-subfolders.md)
- [comment-module-fo-integration](../../../Themes/docs/concepts/comment-module-fo-integration.md)
