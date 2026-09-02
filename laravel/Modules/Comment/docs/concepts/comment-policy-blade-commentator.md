---
title: "CommentPolicy — commentator Blade e contratto CanComment"
type: concept
module: Comment
tags: [comment, policy, can-comment, blade, auth, typeerror, php84]
created: 2026-06-10
updated: 2026-06-10
qmd: "CommentPolicy see CanComment auth user blade commentator instanceof User contract"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/301"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/302"
  - "https://github.com/laraxot/module_comment_fila5/discussions/19"
related:
  - ../wiki/concepts/can-comment-contract-php84.md
  - ./comments-widget-livewire-mount.md
  - ./has-comments-implements-commentable.md
---

# CommentPolicy — commentator in Blade

## Problema (2026-06-10)

Dopo submit commento su `/it/tickets/{id}` il commento **persisteva** ma il re-render Livewire andava in 500:

```
CommentPolicy::see(): Argument #1 ($user) must be of type ?CanComment, …\User given
```

Stack: `comments.blade.php` → `$commentPolicy->see(auth()->user(), $comment)`.

## Causa

1. **Type hint policy** — `CommentPolicy` deve usare `Modules\User\Contracts\CanComment` (owner). L'alias `Modules\Comment\Models\Contracts\CanComment` estende il contratto User ed è **deprecato**; in PHP 8.4 una classe che implementa solo il parent **non** soddisfa il type hint del child interface.
2. **Blade** — `auth()->user()` restituisce `Authenticatable|null`; passarlo direttamente a metodi con `?CanComment` provoca `TypeError` anche quando il modello runtime è corretto (opcache / policy non allineata).
3. **Consumer User** — `BaseUser` implementa già `User\Contracts\CanComment` + `InteractsWithComments`. `Fixcity\Models\User` aggiunge solo `implements Comment\Contracts\CanComment` (alias) per `is_subclass_of` legacy; **non** ridichiarare il trait.

## Fix (canon)

### Policy e provider

- `CommentPolicy` → `use Modules\User\Contracts\CanComment`
- `CommentEngineServiceProvider::resolveCommentatorModel()` → `is_subclass_of($userClass, User\Contracts\CanComment::class)`

### Blade (comments + comment widget)

```php
use Modules\User\Contracts\CanComment;

$commentator = Auth::user();
$commentator = $commentator instanceof CanComment ? $commentator : null;
```

Poi: `$commentPolicy->see($commentator, $comment)` — mai `auth()->user()` grezzo.

### Widget PHP

Stesso pattern in `CommentsWidget` (già presente): `instanceof CanComment` prima di policy/notify.

## Gerarchia contratti

| Contratto | Owner | Uso |
|-----------|-------|-----|
| `User\Contracts\CanComment` | User | Policy, provider, Blade, test |
| `Comment\Models\Contracts\CanComment` | Comment (alias) | Retrocompat; estende User |

## Checklist anti-regressione

1. `php artisan view:clear` dopo edit Blade commenti
2. `./vendor/bin/pest Modules/Comment/tests/Unit/CommentPolicyTest.php`
3. Submit + lista su `/it/tickets/{id}` senza 500 POST livewire
4. `AUTH_MODEL` = classe che estende `BaseUser` (es. `Fixcity\Models\User`)

## File coinvolti

- `app/Policies/CommentPolicy.php`
- `app/Providers/CommentEngineServiceProvider.php`
- `resources/views/filament/widgets/commentable/comments.blade.php`
- `resources/views/filament/widgets/comment/comment.blade.php`
- `Modules/Fixcity/app/Models/User.php` (no trait `InteractsWithComments` duplicato)
