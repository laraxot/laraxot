---
title: HasComments MorphMany secondo generico $this
status: done
epic: code-quality
type: story
updated: 2026-10-20
tags: [comment, phpstan, morphmany]
qmd: "hascomments morphmany this childreturntype comment article ticket"
---

# HasComments MorphMany secondo generico `$this`

## Scopo

`HasComments::comments()` espone il thread sul soggetto commentabile
(Article, Ticket, Comment). PHPStan deve vedere il parent concreto, non
`Model` generico: altrimenti `method.childReturnType` su ogni host.

## Causa

`@return MorphMany<Comment, Model>` mentre i host tipizzano `$this`.
`notificationSubscriptions()` era gia' corretto con `$this`.

## Fix

1. `@return MorphMany<Comment, $this>` su `comments()`, allineato allo scopo
   del trait (relazione tipizzata sull'host).
2. Dedup notifiche: Action e Job usano `CommentatorUniqueKey::for()` (niente
   `(string)` su `mixed` da `getKey()` / `is_scalar`) — stesso SSoT, niente
   `subscriberUniqueKey` privato duplicato.

## Verifica

```bash
cd laravel && flock -w 1800 /tmp/phpstan-gate.lock nice -n 10 \
  ./vendor/bin/phpstan analyse \
  Modules/Comment/app/Models/Concerns/HasComments.php \
  Modules/Comment/app/Support/CommentatorUniqueKey.php \
  Modules/Comment/app/Actions/Notification/NotifyApprovedCommentAction.php \
  Modules/Comment/app/Jobs/SendNotificationsForApprovedCommentJob.php \
  --no-progress
```

## Related

- [CommentatorUniqueKey](../../../app/Support/CommentatorUniqueKey.php) — SSoT chiave dedup notifiche
- Wave: [phpstan-modules-swarm-random](../../../../Xot/docs/bmad/stories/phpstan-modules-swarm-random.story.md)
