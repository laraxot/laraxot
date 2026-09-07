---
title: "CommentNotificationSubscription: @return MorphTo<BaseMorphPivot,...> cannot pass PHPStan"
type: coordination
tags: [phpstan, comment, morphto, generics, pivot]
created: 2026-09-07T12:06:00+02:00
status: resolved
blocking: false
agent: claude (sonnet 5) — this session
---

## Resolved (2026-09-07T12:20+02:00)

Final committed state (`bce43c6`, pushed): `@property-read BaseMorphPivot|null`
kept on both class-level hints (confirmed correct per user review — this IS
a pivot, extends `Comment\Models\BaseMorphPivot`, not `BaseModel`). Both
`commentable()`/`subscriber()` method-level annotations are
`@return MorphTo<Model, $this>` — **correction to the note below**: I had
written that Eloquent's `morphTo()` returns `MorphTo<Model, static>` — that
was wrong, verified by reading the actual vendor source
(`vendor/laravel/framework/src/Illuminate/Database/Eloquent/Concerns/
HasRelationships.php:414`, declares `@return ...MorphTo<...Model, $this>`).
`$this` is correct; `static` is not, and produces `return.type` +
"TDeclaringModel is not covariant" regardless of the declaring class being a
Pivot subclass or a regular Model subclass — this generic slot is invariant,
must match Laravel's own declaration exactly, `$this` in all cases. Verified
stable across 2 repeated clean-cache `phpstan analyse Modules/Comment` runs:
0 errors. Story `CMT-002` updated with full outcome.

# Why MorphTo<BaseMorphPivot, $this> will keep failing PHPStan

Found `laravel/Modules/Comment/app/Models/CommentNotificationSubscription.php`
under an active lock (`comment-notificationsubscription-pivot-fix`, still
young when I checked — backed off, did not force an edit). Current state on
disk has:

```php
/** @return MorphTo<BaseMorphPivot, $this> */
public function commentable(): MorphTo
{
    return $this->morphTo();
}
```

**This will not pass PHPStan**, and it's not a docblock-syntax issue — it's
structural. `$this->morphTo()` with no arguments resolves the related class
**at runtime** from the `commentable_type` column; Eloquent's own
`morphTo()` signature returns `MorphTo<Model, static>` — the widest possible
bound, because the actual related class isn't known until a row is loaded.
PHPStan enforces that a method's declared `@return` generic must be a
supertype-compatible match of what the method body actually returns. Since
`Model` (Eloquent's own bound) is wider than `BaseMorphPivot`, annotating a
narrower `MorphTo<BaseMorphPivot, ...>` produces `generics.notSubtype` +
`return.type` — confirmed by direct measurement (4→8 errors depending on
which half of the annotation was mid-edit) every time this exact pattern
landed on disk in the last ~10 minutes.

**What does work, confirmed 0 errors:**
```php
/**
 * @property-read BaseMorphPivot|null $commentable   // fine: docblock-only, unenforced, same namespace so no import needed
 * @property-read BaseMorphPivot|null $subscriber
 */
class CommentNotificationSubscription extends BaseMorphPivot
{
    /** @return MorphTo<\Illuminate\Database\Eloquent\Model, $this> */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
    // same for subscriber()
}
```

The class-level `@property-read BaseMorphPivot|null` hint is a *pure
IDE/documentation annotation* — PHPStan doesn't enforce it against the real
relation's generic bound, so it's free to be as narrow/specific as the domain
intent requires (both `$commentable`/`$subscriber` conceptually being
Comment-ecosystem pivots). The method-level `@return MorphTo<...>` generic
IS enforced, and must stay at `Model` (the framework's real bound) — use the
FQCN `\Illuminate\Database\Eloquent\Model` directly in the docblock instead of
a `use` import, since an auto-formatter in this repo has stripped a bare
`use Illuminate\Database\Eloquent\Model;` import as "unused" 4+ times in the
last 10 minutes (it doesn't parse PHPDoc-only usage) — the FQCN form is
immune to that.

Not editing the file myself — lock is active. Whoever holds
`comment-notificationsubscription-pivot-fix`: this is a direct answer to "how
do I keep BaseMorphPivot on property-read AND pass PHPStan", not a
disagreement with the underlying design intent (BaseMorphPivot ownership is
correct per user's 2026-09-07 correction, see
`Modules/Comment/docs/stories/CMT-002...story.md`).
