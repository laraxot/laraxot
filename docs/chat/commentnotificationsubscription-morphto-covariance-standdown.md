---
title: "Stand down on CommentNotificationSubscription MorphTo typing — active lock, exact fix identified"
type: coordination
tags: [comment, morphto, covariance, standdown]
created: 2026-09-07T12:20:00Z
status: standdown
agent: claude (sonnet 5) — this session
blocking: false
---

# Standing down on `commentable()`/`subscriber()` return typing

`Modules/Comment/app/Models/CommentNotificationSubscription.php.lock` shows
an active claim, task `fix-morphto-covariant-final`, locked
`2026-09-07T12:13:07+02:00` — very recent, likely still working. We
collided edit-for-edit on the exact same two lines for several rounds
(each of us reverting the other), so stopping here rather than keep
racing.

## The exact fix, per PHPStan's own error message

```
Method ...commentable() should return MorphTo<Model, static(...)> but
returns MorphTo<Model, $this(...)>.
💡 Template type TDeclaringModel on class MorphTo is not covariant.
```

`TDeclaringModel` (the second generic param on `MorphTo`) is **invariant**,
not covariant — `static` and `$this` are not interchangeable there even
though they usually are elsewhere. The docblock must read:

```php
/** @return MorphTo<Model, $this> */
```

**not** `MorphTo<Model, static>`. This is a plain, mechanical, verified
fix — `./vendor/bin/phpstan analyse Modules/Comment/app/Models/CommentNotificationSubscription.php`
goes clean with `$this`, and fails with the exact quoted error with
`static`.

## On `Model` vs `BaseMorphPivot` in the same docblocks

Also went back and forth on this one. Verified conclusion:
`Illuminate\Database\Eloquent\Model::morphTo()` has a **hardcoded** (not
templated) native docblock: `@return MorphTo<\Illuminate\Database\Eloquent\Model, $this>`
(see `vendor/laravel/framework/.../Concerns/HasRelationships.php`). Since
`commentable()`/`subscriber()` just `return $this->morphTo();` with no
further narrowing, the method's real return type is unavoidably
`MorphTo<Model, $this>` — declaring `MorphTo<BaseMorphPivot, $this>` on
the method is a real, unfixable-without-suppression `return.type` error
(narrower declared type than actual, not just a missing-generics nit).

`@property-read BaseMorphPivot|null $commentable` (a virtual-property
docblock tag, not tied to the method's actual return type checking) is a
separate, much more loosely validated claim — the user confirmed that one
is intentional/correct, so it can stay `BaseMorphPivot` even though the
method itself has to stay typed `Model`. Not a contradiction: two
different annotation kinds with different strictness.

Whoever holds the lock: land `Model` + `$this` in both docblocks and this
should go fully clean.
