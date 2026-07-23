---
title: "Actions — sottocartelle per dominio e risoluzione container"
type: concept
module: Comment
tags: [actions, queueable-action, domain-folder, comment-config, dry, kiss]
created: 2026-06-10
updated: 2026-06-10
qmd: "actions domain subfolders context actor app execute CommentConfig SSOT queueable"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/319"
related:
  - ../../../../docs/wiki/rules/actions-domain-subfolders.md
  - ./spatie-v2-residual-gap-analysis.md
---

# Actions — dominio in cartella, app() al call site

## Perché

- **KISS:** namespace = contesto (`Comment`, `Notification`, `Mention`).
- **DRY:** no service locator su `CommentConfig` per behavior.
- **Zen:** `CommentConfig` = config; Action = comportamento via container.

## Layout

```
app/Actions/Comment/          Approve, Reject, Process
app/Actions/Notification/     SendNotificationsFor*
app/Actions/Mention/          ResolveMentionsAutocomplete
```

## Pattern

```php
app(RejectCommentAction::class)->execute($comment); // ✅
```

`config/comments.php` → `actions.*` = FQCN default; override tenant = binding container.

## CommentConfig (rimosso STORY-292)

Vedi [no-comment-config-ssot](./no-comment-config-ssot.md) — tutto via `config()` + `app()`.

Vedi [no-jobs-queueable-action-only](./no-jobs-queueable-action-only.md).
