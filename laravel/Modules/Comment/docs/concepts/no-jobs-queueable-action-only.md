---
title: "Comment — no Jobs, solo QueueableAction"
type: concept
module: Comment
tags: [comment, queueable-action, no-jobs, spatie, kiss]
created: 2026-06-10
updated: 2026-06-10
qmd: "comment no jobs queueable action spatie async onQueue"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/16"
discussions:
  - "https://github.com/laraxot/module_comment_fila5/discussions/17"
related:
  - ./actions-domain-subfolders.md
  - ../../../../docs/wiki/rules/no-module-jobs-queueable-action.md
---

# No Jobs nel modulo Comment

## Perché

Job + Action = due entry point, anti-KISS. Async = `QueueableAction` + `->onQueue()`.

```php
app(SendNotificationsForApprovedCommentAction::class)->onQueue()->execute($comment);
```

## Vietato

`app/Jobs/*`, `dispatch(new *Job(...))` per dominio Comment.

## GitHub

- Issue [#16](https://github.com/laraxot/module_comment_fila5/issues/16)
- Discussion [#17](https://github.com/laraxot/module_comment_fila5/discussions/17)
