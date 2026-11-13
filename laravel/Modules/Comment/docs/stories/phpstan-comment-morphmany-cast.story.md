---
title: PHPStan Comment — MorphMany Commentable + subscriberUniqueKey (cast.string)
type: story
module: Comment
status: done
created: 2026-10-20
updated: 2026-10-20
qmd: "phpstan comment morphmany childReturnType cast.string subscriberUniqueKey Commentable HasComments"
related:
  - ../wiki/concepts/can-comment-contract-php84.md
  - ../wiki/concepts/phpstan-pest-test-patterns.md
  - ./phpstan-comment-participating-commentators.story.md
  - ../../../../../bashscripts/docs/prompts/11-phpstan.md
---

# PHPStan Comment — MorphMany + cast.string

Lane swarm (scope disgiunto). Ground truth: `.cursor-scratch/phpstan-wave/Comment.tsv`.

## Scopo (1 frase)

Notificare i subscriber di un commento approvato con chiave univoca tipizzata, e esporre `comments()` morph coerente tra contratto `Commentable` e trait `HasComments`.

## Famiglie (TSV)

| Identifier | Causa radice | Fix canonico |
|---|---|---|
| `cast.string` su unique key | `CanComment::getKey(): mixed` (LSP Eloquent) | `subscriberUniqueKey()` con `match` null / scalar / object — niente `(string) mixed` |
| `method.childReturnType` su `HasComments::comments()` | interfaccia senza generic vs trait `@return MorphMany<Comment, $this>` | `Commentable::comments(): @return MorphMany<Comment, Model>` |

## Esito lane

| Voce | Valore |
|---|---|
| Errori TSV iniziali | **5** (2 cast + 3 childReturnType context) |
| Verify mirata (Action + Job + HasComments) | **0 errori**, exit 0 (codice gia' allineato) |
| `Commentable.php` | gia' con `@return MorphMany<Comment, Model>` |
| SKIP HOT | `NotifyApprovedCommentAction.php`, `SendNotificationsForApprovedCommentJob.php` (mtime &lt; 120s al lock) |
| SKIP LOCKED | `HasComments.php` — lock stale `2026-10-08` agent `sweep-comment-ai` (Age ~17850m). Coordinatore: valutare `unlock` del `.lock` orfano |

## Verifica

```bash
cd laravel && flock -w 1800 /tmp/phpstan-gate.lock nice -n 10 ./vendor/bin/phpstan analyse \
  Modules/Comment/app/Actions/Notification/NotifyApprovedCommentAction.php \
  Modules/Comment/app/Jobs/SendNotificationsForApprovedCommentJob.php \
  Modules/Comment/app/Models/Concerns/HasComments.php \
  Modules/Comment/app/Models/Contracts/Commentable.php \
  --memory-limit=2G --no-progress
```
