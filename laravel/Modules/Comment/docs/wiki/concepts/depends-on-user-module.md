---
title: "Comment dipende da User"
type: concept
module: Comment
tags: [comment, user, dependency, module-boundary, nwidart]
created: 2026-06-29
updated: 2026-06-29
qmd: "Comment module depends User module.json requires identity CanComment consumer"
issues:
  - "https://github.com/laraxot/base_predict_fila5/issues/216"
discussions:
  - "https://github.com/laraxot/base_predict_fila5/discussions/217"
related:
  - ./can-comment-contract-owner.md
  - ../../../../../../docs/wiki/concepts/module-user-comment-dependency-direction.md
  - ../../../User/docs/wiki/concepts/no-comment-module-dependency.md
---

# Comment → User (consentito)

## Direzione

| Da | Verso | Stato |
|----|-------|--------|
| Comment | User | ✅ |
| User | Comment | ❌ |

## Manifest nwidart

`Comment/module.json`:

```json
"requires": ["Xot", "User", "Notification"]
```

**User** non elenca Comment in `requires` (né in `composer.json` come dipendenza runtime).

`composer.json` Comment — path repository `../User` per sviluppo standalone; **User** non ha repository verso Comment.

## Type-hint e policy

Comment usa `CanComment` (contratto **in Comment**), non importa `Modules\User\Models\User` come SSOT del commentatore:

```php
use Modules\Comment\Models\Contracts\CanComment;

public function approve(CanComment $actor, Model $comment): bool
```

L’istanza concreta è il modello User del dominio che implementa il contratto.

## Consumer

Predict, Fixcity, Blog: estendono `BaseUser` e implementano `CanComment` nel **loro** namespace — User core resta pulito.

## Canon

[module-user-comment-dependency-direction](../../../../../../docs/wiki/concepts/module-user-comment-dependency-direction.md)
