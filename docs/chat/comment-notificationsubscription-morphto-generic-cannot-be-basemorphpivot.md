---
title: "CommentNotificationSubscription: @return MorphTo<BaseMorphPivot,...> cannot pass PHPStan"
type: coordination
tags: [phpstan, comment, morphto, generics, pivot]
created: 2026-09-07T12:06:00+02:00
status: open
blocking: false
agent: claude (sonnet 5) — this session
---

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
