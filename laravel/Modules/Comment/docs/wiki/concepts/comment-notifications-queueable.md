---
title: "Comment — notifiche via QueueableAction"
type: concept
tags: [comment, notifications, queueable-action, no-jobs, kiss]
created: 2026-07-01
updated: 2026-07-01
qmd: "comment notifications queueable action pending approved onQueue"
related:
  - ../../../concepts/no-jobs-queueable-action-only.md
  - native-comments-architecture.md
  - ponytail-audit.md
---

# Notifiche commento — solo QueueableAction

## Perché

Job + Action = due entry point per la stessa logica (anti-KISS). Nel modulo Comment le notifiche pending e approved vivono in `app/Actions/Notification/` con `Spatie\QueueableAction\QueueableAction`.

Async = `->onQueue()->execute($comment)`, non `dispatch(new *Job(...))`.

## Action canoniche

| Config key | Classe | Ruolo |
|------------|--------|-------|
| `send_notifications_for_pending_comment` | `Notification\SendNotificationsForPendingCommentAction` | Mail ai moderatori su commento in attesa |
| `send_notifications_for_approved_comment` | `Notification\NotifyApprovedCommentAction` | Mail a subscriber/mention su commento approvato |

Risoluzione config: `CommentConfigNotifications::sendPendingAction()` / `sendApprovedAction()`.

## Pattern async

```php
// Approvazione → notifica subscriber in coda
app(NotifyApprovedCommentAction::class)->onQueue()->execute($comment);

// Pending → moderatori (quando il flusso lo invoca)
CommentConfigNotifications::sendPendingAction()->onQueue()->execute($comment);
```

## Vietato in `app/`

- `app/Jobs/SendNotificationsFor*CommentJob.php` — archiviati in `.bak`
- `app/Actions/QueueNotifyApprovedCommentAction.php` — duplicato di `NotifyApprovedCommentAction`
- `app/Actions/SendNotificationsForPendingCommentAction.php` (root) — wrapper che dispatchava il job

Il pacchetto Spatie in `packages/spatie/laravel-comments/` conserva i job originali come riferimento; il codice nativo Laraxot non li usa.

## Test

`tests/Unit/CommentEngineParityTest.php` — `SendNotificationsForPendingCommentAction` via `execute()` + `Notification::fake()`.

## Collegamenti

- [No Jobs — regola modulo](../../../concepts/no-jobs-queueable-action-only.md)
- [Architettura commenti nativi](native-comments-architecture.md)
